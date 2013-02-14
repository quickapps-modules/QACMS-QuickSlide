<?php
class ImagesController extends QuickSlideAppController {
	public $uses = array('QuickSlide.Image');

	public function __construct($request = null, $response = null) {
		$params = Router::getParams();

		if ($params['action'] == 'admin_upload') {
			App::uses('CakeSession', 'Model/Datasource');
			CakeSession::id($params['named']['session_id']);
			CakeSession::start();
		}

		parent::__construct($request, $response);
	}

	public function beforeFilter() {
		$this->Auth->allow('p');
		$this->QuickApps->disableSecurity();

		parent::beforeFilter();
	}

	public function admin_video_thumb($video, $image) {
		$image = $this->Image->findById($image);
		$video = $this->Image->findById($video);
		$aid = $image['Image']['aid'];
		$imageSrc = $image['Image']['src'];
		$videoSrc = $video['Image']['src'];
		$newImageName = '___tn___' . QS::filename($videoSrc) . '_' . $imageSrc;
		$oldImageName = '___tn___' . QS::filename($videoSrc) . '_*';
		$newImageTn =  '*' . $imageSrc;
		$leaving['lg'] = glob(QS_FOLDER . "album-{$aid}" . DS . $newImageName);
		$leaving['olg'] = glob(QS_FOLDER . "album-{$aid}" . DS . $oldImageName);
		$leaving['tn'] = glob(QS_FOLDER . "album-{$aid}" . DS . 'cache' . DS . $oldImageName);
		$leaving['ntn'] = glob(QS_FOLDER . "album-{$aid}" . DS . 'cache' . DS . $newImageTn);

		foreach($leaving as $la) {
			foreach ($la as $l) {
				@unlink($l);
			}
		}

		rename(QS_FOLDER . "album-{$aid}" . DS . $imageSrc, QS_FOLDER . "album-{$aid}" . DS . $newImageName);

		if ($imageSrc == $image['Album']['aTn']) {
			$image['Album']['aTn'] = '';
			$this->Image->Album->save($image['Album']);
		}

		die(' ');
	}

	public function admin_rotate($ids, $deg) {
		$ids = explode(",", $ids);
		$degree = $deg;
		$images = $this->Image->find('all', array('conditions' => array('Image.id' => $ids), 'recursive' => -1));

		foreach ($images as $image) {
			$path = QS_FOLDER . "album-{$image['Image']['aid']}" . DS;
			$leaving['tn'] = glob(QS_FOLDER . "album-{$image['Image']['aid']}" . DS . 'cache' . DS . str_replace("." . QS::findexts($image['Image']['src']), '', $image['Image']['src']) . "_*." . QS::findexts($image['Image']['src']));

			foreach($leaving['tn'] as $l) {
				@unlink($l);
			}

			QS::rotateImage($path . $image['Image']['src'], $path . $image['Image']['src'], $degree);
			QS::rotateImage($path . 'cache' . DS . $image['Image']['src'], $path . 'cache' . DS . $image['Image']['src'], $degree);
		}

		die(' ');
	}

	public function admin_upload() {
		if ($this->data['Upload']['type'] == 'audio' ||
			in_array($this->params['form']['Filedata']['type'], Configure::read('qs_mimes'))
		) {
			App::import('Vendor', 'Upload');

			$handle = new Upload($this->params['form']['Filedata']);
			$handle->file_overwrite = false;
			$folder = $this->data['Upload']['type'] == 'audio' ? QS_FOLDER . 'album-audio' . DS : QS_FOLDER . "album-{$this->data['Album']['id']}" . DS;
			$old_mask = umask(0);

			$handle->Process($folder);

			if ($handle->processed) {
				if ($this->data['Upload']['type'] == 'image') {
					$images_count = $this->Image->find('count', array('conditions' => "Image.aid = {$this->data['Album']['id']}"));
					$data['Image'] = array(
						'aid' => $this->data['Album']['id'],
						'src' => $handle->file_dst_name,
						'seq' => $images_count+1,
						'filesize' => filesize($folder."/".$handle->file_dst_name),
						'active' => 1
					);
					$this->Image->save($data);
					$adata['Album'] = array('id' => $this->data['Album']['id']);
					$this->Image->Album->save($adata); // auto modified,updated_by

					header("HTTP/1.1 200 OK");
					die(' ');
				}
			} else {
				header("HTTP/1.1 500 File Upload Error");
				echo "Error: {$handle->error}";
			}

			umask($old_mask);
		} else {
			header("HTTP/1.1 500 File Upload Error");
			echo "Error: no data given";
		}

		die(' ');
	}

	public function admin_anchor($id) {
		$this->Image->id = $id;
		$image = $this->Image->read();
		$folder = QS_FOLDER . "album-{$image['Album']['id']}" . DS;
		$videoThumb = '___tn___' . str_replace('.' . QS::findexts($image['Image']['src']), '.', $image['Image']['src']) . '*';
		$cache_thumb = 'cache' . DS . str_replace('.' . QS::findexts($image['Image']['src']), '', $image['Image']['src']) . "_*.*";
		$leaving[0] = glob($folder . $videoThumb);
		$leaving[1] = glob($folder . $cache_thumb);

		foreach($leaving as $la) {
			foreach ($la as $l) {
				unlink($l);
			}
		}

		$data['Image'] = array(
			'id' => $id,
			'anchor' => $this->data
		);
		$encode = QS::p(
			array(
				'src' => $image['Image']['src'],
				'album_id' => $image['Image']['aid'],
				'anchor_x' => $this->data['x'],
				'anchor_y' => $this->data['y']
			)
		);

		$this->Image->save($data);
		die(Router::url("/quick_slide/images/p/") . $encode);
	}

	public function admin_edit($id = false) {
		if (isset($this->data['Image'])) {
			switch($this->data['Image']['preview']) {
				case 'exclude':
					$adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => $this->data['Image']['src']);
					$this->data['Image']['status'] = 0;

					$this->Image->Album->save($adata, false);
				break;

				case 'include':
					$adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => $this->data['Image']['src']);
					$this->data['Image']['status'] = 1;

					$this->Image->Album->save($adata, false);
				break;

				default:
					if ($this->data['Album']['aTn'] == $this->data['Image']['src']) {
						$adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => '');

						$this->Image->Album->save($adata, false);
					}
				break;
			}

			$this->Image->save($this->data);
			die(' ');
		}

		$data = $this->Image->find('first', array('conditions' => array('Image.id' => $id)));

		if ($data['Image']['status'] == 0 &&
			$data['Image']['src'] == $data['Album']['aTn']
		) {
			$data['Image']['preview'] = 'exclude';
		} elseif($data['Image']['status'] > 0 &&
			$data['Image']['src'] == $data['Album']['aTn']
		) {
			$data['Image']['preview'] = 'include';
		} else {
			$data['Image']['preview'] = 'nouses';
		}

		$this->data = $data;
	}

/**
 * Render encoded image request.
 *
 * @param string $args Encoded image request:
 *  $args[0] Full path to image
 *  $args[1] New width
 *  $args[2] New height
 *  $args[3] Quality (1 to 100)%
 *  $args[4] Square, 0 = FALSE, 1 = TRUE
 *  $args[5] Anchor X coord
 *  $args[6] Anchor Y coord
 *
 * @return die
 */
	public function p($args = false) {
		Configure::write('debug', 1);

		$args = !$args ? $this->request->query['i'] : $args;
		$args = explode('&', $args);
		$args = $args[0];
		$a = explode(',', base64_decode($args));

		if (isset($this->request->query['full'])) {
			$full = explode(',', $this->request->query['full']);
			$a[2] = $full[0];
			$a[3] = $full[1];
		}

		$file = $fn = basename($a[0]);
		$ext = QS::findexts($file);
		$aid = $a[1];
		$w = QS::n($a[2]);
		$h = QS::n($a[3]);
		$s = QS::n($a[4]);
		$q = QS::n($a[5], 100);
		$sh = QS::n($a[6], 0);
		$x = QS::n($a[7], 50);
		$y = QS::n($a[8], 50);
		$force = QS::n($a[9], 0);

		define('IMG_PATH', QS_FOLDER . "album-{$aid}");

		$base_dir = IMG_PATH;
		$original = IMG_PATH . DS . $file;

		if (!file_exists(IMG_PATH)) {
			exit;
		}

		if ($a[4] == 2) {
			$path_to_cache = $original;
		} else {
			$fn .= "_{$w}_{$h}_{$s}_{$q}_{$sh}_{$x}_{$y}.{$ext}";
			$base_dir = IMG_PATH . DS . 'cache';

			if (!file_exists($base_dir)) {
				QS::rmkdir($base_dir);
			}

			$path_to_cache = IMG_PATH . DS . 'cache' . DS . $fn;
		}

		if (dirname($path_to_cache) !== $base_dir) {
			header('HTTP/1.1 403 Forbidden'); 
			exit;
		}

		$cache_old = file_exists($path_to_cache) ? filectime($path_to_cache) : 0;

		if (!file_exists($path_to_cache) || (time()-604800) > $cache_old) { // +1 week cache
			if ($s == 2) {
				copy($original, $path_to_cache);
			} else {
				if (!is_dir(dirname($path_to_cache))) {
					$parent_perms = substr(sprintf('%o', fileperms(dirname(dirname($path_to_cache)))), -4);
					$old = umask(0);
					mkdir(dirname($path_to_cache), octdec($parent_perms));
					umask($old);
				}

				QS::imageResize(
					array(
						'name' => $original,
						'filename' => $path_to_cache,
						'new_w' => $w,
						'new_h' => $h,
						'quality' => $q,
						'square' => $s,
						'gd' => null,
						'sharpening' => $sh,
						'x' => $x,
						'y' => $y,
						'force' => $force
					)
				);
			}
		}

		$mtime = filemtime($path_to_cache);
		$etag = md5($path_to_cache . $mtime);
		$disabled_functions = explode(',', str_replace(' ', '', ini_get('disable_functions')));
		$specs = getimagesize($path_to_cache);

		header('Content-type: ' . $specs['mime']);
		header('Content-length: ' . filesize($path_to_cache));
		header('Cache-Control: public');
		header('Expires: ' . gmdate('D, d M Y H:i:s', strtotime('+1 year')));
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path_to_cache)));
		header('ETag: ' . $etag);

		if (is_callable('readfile') && !in_array('readfile', $disabled_functions)) {
			readfile($path_to_cache);
		} else {
			die(file_get_contents($path_to_cache));
		}

		die(' ');
	}

	public function admin_delete($ids) {
		$ids = explode(',', $ids);
		$this->Image->cacheQueries = false;

		foreach ($ids as $id) {
			$this->Image->delete($id);
		}

		die(' ');
	}

	public function admin_toggle($ids) {
		$ids = explode(",", $ids);
		$data = array('Image.status' => $this->data['Image']['status']);
		$this->Image->updateAll($data, array('Image.id' => $ids));

		die(' ');
	}

	public function admin_sort() {
		if (isset($this->params['data']['img'])) {
			foreach($this->params['data']['img'] as $i => $id) {
				$data['Image'] = array('id' => $id, 'seq' => $i+1);
				$this->Image->save($data);
			}
		}

		die(' ');
	}
}