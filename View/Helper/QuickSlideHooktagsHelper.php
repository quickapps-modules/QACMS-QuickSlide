<?php
class QuickSlideHooktagsHelper extends AppHelper {
    public function quick_slide($atts, $content = null, $code = '') {
        if (!defined('QS_NO_SWF')) {
            if (isset($atts['gallery'])) {
                $atts['gallery'] = intval($atts['gallery']);
                $xml = "/quick_slide/xml/data/gallery:{$atts['gallery']}"; 
            } elseif (isset($atts['album'])) {
                $atts['album'] = preg_replace('/[^0-9,]/', '', $atts['album']); 
                $xml = "/quick_slide/xml/data/album:{$atts['album']}";
            }

            if (!empty($xml)) {
                return Router::url($xml, true);
            } else {
                return '<!-- QuickSlide: No album/gallery parametter given -->';
            }
        } else {
            return '<!-- QuickSlide: QuickSlidePro Player (slideshowpro.swf) was not found -->';
        }
    }
}