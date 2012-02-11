<?php
class XmlController extends QuickSlideAppController {
    public $uses = array('QuickSlide.Gallery', 'QuickSlide.Link');

    public function beforeFilter() {
        $this->Auth->allow('data');

        parent::beforeFilter();
    }

    public function data() {
        $this->viewClass = 'Xml';
        $gallery = $albums = array();

        if (isset($this->params['named']['gallery'])) {
            $this->Gallery->recursive = -1;
            $gallery = intval($this->params['named']['gallery']);

            if ($gallery = $this->Gallery->findById($gallery)) {
                $album_ids = $this->Link->find('all',
                    array(
                        'conditions' => array('Link.gid' => $gallery['Gallery']['id']),
                        'recursive' => -1
                    )
                );
                $album_ids = Set::extract('/Link/aid', $album_ids);
                $albums = $this->Gallery->Album->find('all',
                    array(
                        'conditions' => array('Album.id' => $album_ids),
                        'recursive' => 1
                    )
                );
            }
        } elseif (isset($this->params['named']['album'])) {
            $album = $this->params['named']['album'];
            $album = preg_replace('/[^0-9,]/', '', $album);
            $album = explode(',', $album);
            $albums = $this->Gallery->Album->find('all',
                array(
                    'conditions' => array('Album.id' => $album),
                    'recursive' => 1
                )
            );
        }

        $this->set('gallery', $gallery);
        $this->set('albums', $albums);
    }
}