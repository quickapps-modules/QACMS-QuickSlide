<?php
class QuickSlideHookHelper extends AppHelper {
	public function beforeLayout() {
		if ($this->_View->request->params['plugin'] == 'quick_slide') {
			$this->_View->Layout->blockPush(array('body' => $this->_View->element('toolbar')), 'toolbar');
		}
	}

	public function qs_album_tn($album, $width, $height, $options = array()) {
		if (isset($album['id']) && isset($album['aTn'])) {
			if (empty($album['aTn'])) {
				$img = '/quick_slide/img/no_preview.png';
				$options['width'] = !isset($options['width']) ? $width : $options['width'];
				$options['height'] = !isset($options['height']) ? $height : $options['height'];
			} else {
				$img = '/quick_slide/images/p/';
				$img .= QS::p(QS_FOLDER . "album-{$album['id']}" . DS . "{$album['aTn']}", $width, $height, 100, 1, 0, 0, 0);
			}
		} else {
			$img = '/quick_slide/img/no_preview.png';
			$options['width'] = !isset($options['width']) ? $width : $options['width'];
			$options['height'] = !isset($options['height']) ? $height : $options['height'];
		}

		$options['class'] = isset($options['class']) ? $options['class'] . ' album-tn' : ' album-tn';

		return $this->_View->Html->image($img, $options);
	}

	public function qs_tooltip($label, $desc, $__d = true) {
		if ($__d) {
			$label = __d('quick_slide', $label);
			$desc = __d('quick_slide', $desc);
		}

		return $label . ' ' . $this->_View->Html->link('[?]', '#', array('onclick' => 'return false;', 'title' => $desc));
	}
}