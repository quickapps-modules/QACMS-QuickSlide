<?php
    if (isset($this->data['Album']) && $this->request->params['controller'] == 'albums') {
        $links = array(
            array(__d('quick_slide', 'Summary'), "/admin/quick_slide/albums/edit/{$this->data['Album']['id']}"),
            array(__d('quick_slide', 'Content'), "/admin/quick_slide/albums/edit/{$this->data['Album']['id']}/content"),
            array(__d('quick_slide', 'Audio'), "/admin/quick_slide/albums/edit/{$this->data['Album']['id']}/audio"),
            array(__d('quick_slide', 'Upload'), "/admin/quick_slide/albums/edit/{$this->data['Album']['id']}/upload")
        );
    } elseif (isset($this->data['Gallery']) && $this->request->params['controller'] == 'galleries') {
        $links = array(
            array(__d('quick_slide', 'Summary'), "/admin/quick_slide/galleries/summary/{$this->data['Gallery']['id']}"),
            array(__d('quick_slide', 'Albums'), "/admin/quick_slide/galleries/albums/{$this->data['Gallery']['id']}")
        );
    } else {
        $links = array(
            array(__d('quick_slide', 'Albums'), '/admin/quick_slide/albums/', 'pattern' => '*quick_slide/albums/index/*'),
            array(__d('quick_slide', 'Galleries'), '/admin/quick_slide/galleries', 'pattern' => '*quick_slide/galleries/index/*')
        );

        if ($this->request->params['controller'] == 'albums') {
            $links[] = array(__d('quick_slide', 'New Album'), '/admin/quick_slide/albums/add');
        }

        if ($this->request->params['controller'] == 'galleries') {
            $links[] = array(__d('quick_slide', 'New Gallery'), '/admin/quick_slide/galleries/add');
        }

        $links[] = array(__d('quick_slide', 'Help'), '/admin/system/help/module/QuickSlide');
    }

    echo $this->Layout->toolbar($links);