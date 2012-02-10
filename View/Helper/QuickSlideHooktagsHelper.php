<?php
class QuickSlideHooktagsHelper extends AppHelper {
    private $__count = 0;

    public function quick_slide($attr, $content = null, $code = '') {
        $out = '';

        if (!defined('QS_NO_SWF')) {
            if (!$this->__count) {
                $out .= $this->_View->Html->script('/quick_slide/js/swfobject.js');
            }

            $out .= $this->_View->element('QuickSlide.embed_code', compact('attr'));
            $this->__count++;
        } else {
            $out = '<!-- QuickSlide: QuickSlidePro Player (slideshowpro.swf) was not found -->';
        }

        return $out;
    }
}