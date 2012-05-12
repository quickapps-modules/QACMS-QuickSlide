<?php
class QuickSlideController extends QuickSlideAppController {
	public $uses = array();

	public function admin_index($redirect = 'albums') {
		$this->redirect("/admin/quick_slide/{$redirect}");
	}
}