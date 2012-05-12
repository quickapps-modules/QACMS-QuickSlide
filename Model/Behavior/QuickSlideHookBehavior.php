<?php
class QuickSlideHookBehavior extends ModelBehavior {
	public function afterFind(&$model, $results, $primary) {
		if ($model->alias == 'NodeType' && $primary && Router::getParam('action') == 'admin_create') {
			$results[] = array(
				'NodeType' => array(
					'id' => 'qs_create',
					'name' => __d('quick_slide', 'Quick Slide Content'),
					'description' => __d('quick_slide', 'Creates rich media slideshows.')
				)
			);
		}

		return $results;
	}
}