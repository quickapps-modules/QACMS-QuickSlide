<?php
class Image extends QuickSlideAppModel {
	public $useTable = 'qs_images';
	public $order = array('ABS(Image.seq) ASC');
	public $cacheQueries = false;
	public $actsAs = array('Serialized' => array('anchor'));
	public $belongsTo = array(
		'Album' => array(
			'className' => 'QuickSlide.Album',
			'foreignKey' => 'aid',
			'fields' => array('id', 'name', 'aTn')
	   )
	);

	public function clearFiles($image) {
		// Delete it from the filesystem if no other albums use this path
		$path = QS_FOLDER . "album-{$image['Image']['aid']}" . DS;

		@unlink($path . $image['Image']['src']);
		$this->clearCaches($image['Image']['src'], $path);

		if (QS::isVideo($image['Image']['src'])) {
			$frames = glob($path . '___tn___' . str_replace('.' . QS::findexts($this->data['Image']['src']), '', $this->data['Image']['src']) . '*');

			if (!empty($frames)) {
				foreach($frames as $f) {
					@unlink($f);
				}
			}
		}
	}

	public function clearCaches($str, $path) {
		$str = str_replace('.' . QS::findexts($this->data['Image']['src']), '', $this->data['Image']['src']);
		$caches = glob($path . 'cache' . DS . $str . '*');

		if (!empty($caches)) {
			foreach($caches as $cache) {
				@unlink($cache);
			}
		}
	}

	public function beforeDelete() {
		$this->cacheQueries = false;
		$data = $this->read();
		$aid = $data['Image']['aid'];

		$this->clearFiles($data);

		if ($data['Image']['src'] == $data['Album']['aTn']) {
			$adata['Album'] = array('id' => $aid, 'aTn' => 'NULL');
			Classregistry::init('Album')->save($adata);
		}

		// images in album == 1 -> inactivate
		if ($this->find('count', array('conditions' => "Image.aid = {$aid}")) === 1) {
			$this->Album->save(array('Album' => array('id' => $aid, 'active' => 0)));
		} else {
			// mark album as modified
			//Classregistry::init('Album')->save(array('Album' => array('id' => $this->data['Image']['aid'], 'name' => $this->data['Album']['name']))); // update id for auto update updated,updated_by
		}

		return true;
	}
}