<?php
class GalleriesController extends QuickSlideAppController {
    public $uses = array('QuickSlide.Gallery', 'QuickSlide.Album', 'QuickSlide.Link');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->setCrumb(array(__d('quick_slide', 'Galleries'), '/admin/quick_slide/galleries'));
    }

    public function admin_index() {
        $conditions = isset($this->data['search']) ? array("Gallery.name LIKE '%{$this->data['search']}%'") : array();

        $this->data = $this->paginate('Gallery', $conditions);
        $this->title(__d('quick_slide', 'Galleries'));
    }

    public function admin_summary($id) {
        if (isset($this->data['Gallery'])) {
            if ($this->Gallery->save($this->data)) {
                $this->flashMsg(__d('quick_slide', 'Gallery has been saved'));
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__d('quick_slide', 'Gallery could not be saved'), 'error');
            }
        }

        $this->data = $this->Gallery->find('first', array('conditions' => "Gallery.id = {$id}"));
        $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfobject.js';

        $this->JqueryUI->add('dialog');
        $this->JqueryUI->theme();
        $this->title(__d('quick_slide', 'Gallery: %s', $this->data['Gallery']['name']));
    }

    public function admin_albums($id) {
        $this->JqueryUI->theme();
        $this->JqueryUI->add('sortable');
        $this->JqueryUI->add('dialog');

        $this->data = $this->Gallery->find('first', array('conditions' => "Gallery.id = {$id}"));
        $this->title(__d('quick_slide', 'Gallery: %s', $this->data['Gallery']['name']));
    }

    public function admin_add_album($gid, $filter = false) {
        $data = $this->Gallery->findById($gid);
        $exclude = Set::extract('/Album/id', $data);
        $conditions = array(
            'NOT' => array(
                'Album.id' => $exclude
            )
        );

        if ($filter) {
            $conditions['Album.name LIKE'] = "%{$filter}%";
        }

        $this->data = $this->Gallery->Album->find('all', array('conditions' => $conditions));
    }

    public function admin_delete($id) {
        $this->Gallery->delete($id, true);
        $this->redirect($this->referer());
    }

    public function admin_delete_link() {
        $this->data['Link']['display'] = $this->Link->find('count', array('conditions' => "Link.gid = {$this->data['Gallery']['id']}"))+1;

        $this->Link->delete($this->data['Link']['id']);
        $this->__sort_albums($this->data['Gallery']['id']);

        die(' ');
    }

    public function admin_add_link() {
        $this->autoRender = false;
        $data['Link'] = array('aid' => $this->data['Album']['id'], 'gid' => $this->data['Gallery']['id']);

        $this->Link->save($data);
        $this->__sort_albums($this->data['Gallery']['id']);
        $this->admin_albums($this->data['Gallery']['id']);
        $this->render('/Elements/albums_grid');
    }

    private function __sort_albums($gid) {
        $links = $this->Link->find('all', array('conditions' => "Link.gid = {$gid}", 'order' => 'Link.display ASC'));
        $i = 1;

        foreach($links as  $link) {
            $data['Link'] = array('id' => $link['Link']['id'], 'display' => $i);
            $this->Link->save($data);
            $i++;
        }
    }

    public function admin_sort_albums() {
        foreach ($this->data['albm'] as $i => $id) {
            $this->Link->save(array('id' => $id, 'display' => $i));
        }

        die(' ');
    }

    public function admin_add() {
        if (isset($this->data['Gallery'])) {
            if ($this->Gallery->save($this->data)) {
                $this->redirect("/admin/quick_slide/galleries/albums/{$this->Gallery->id}");
            } else {
                $this->flashMsg(__d('quick_slide', 'Gallery could not be saved'));
            }
        }

        $this->title(__d('quick_slide', 'New Gallery'));
    }
}