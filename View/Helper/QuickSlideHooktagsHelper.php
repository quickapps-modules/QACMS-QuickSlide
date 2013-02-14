<?php
class QuickSlideHooktagsHelper extends AppHelper {
	private $__count = 0;

	public function quick_slide($attr, $content = null, $code = '') {
		$out = '';
		$viewer = !defined('QS_NO_SWF') ? 'viewer_flash' : 'viewer_js';

		if (!$this->__count && !defined('QS_NO_SWF')) {
			$out .= $this->_View->Html->script('/quick_slide/js/swfobject.js');
		} elseif (!$this->__count && defined('QS_NO_SWF')) {
			
		}

		$out .= $this->_View->element("QuickSlide.{$viewer}", compact('attr'));
		$this->__count++;

		return $out;
	}
}