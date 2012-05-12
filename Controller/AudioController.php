<?php
class AudioController extends QuickSlideAppController {
	public $uses = array('QuickSlide.Album');

	public function admin_delete() {
		$a_str = $this->data['Audio']['name'];
		$album = $this->Album->findById($this->data['Album']['id']);
		@unlink(QS_FOLDER . 'album-audio' . DS . $a_str);

		if ($album['Album']['audio_file'] == $a_str) {
			$data['Album'] = array('id' => $album['Album']['id'], 'audio_file' => '');
			$this->Album->save($data, false);
		}

		die(' ');
	}
}