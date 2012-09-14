<?php
App::import('Lib', 'QuickSlide.QS');

class QuickSlideAppController extends AppController {
	public function beforeFilter() {
		if (defined('QS_NO_SWF')) {
			$this->flashMsg(
				__d('quick_slide',
					'SlideShow Pro Player was not found. <a href="%s">Click here</a> for more information.',
					Router::url('/admin/system/help/module/QuickSlide')
				),
				'error', 'qs_no_swf'
			);
		}

		if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
			$this->setCrumb(
				array(
					array('Slide Show', '/admin/quick_slide')
				)
			);
			$this->Layout['javascripts']['file'][] = '/quick_slide/js/quick_slide.js';
			$this->Layout['stylesheets']['all'][] = '/quick_slide/css/quick_slide.css';
		}

		$this->jQueryUI->add('effects.all');
	}
}