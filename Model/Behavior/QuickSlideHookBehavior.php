<?php
class QuickSlideHookBehavior extends ModelBehavior {
    public function afterFind(&$model, $results, $primary) {
        if ($model->alias == 'NodeType' && $primary && Router::getParam('action') == 'admin_create') {
            $results[] = array(
                'NodeType' => array(
                    'id' => 'qs_create',
                    'name' => __d('quick_slide', 'Image Album/Gallery'),
                    'description' => __d('quick_slide', 'Creates a algum of images and galleries.')
                )
            );
        }

        return $results;
    }
}