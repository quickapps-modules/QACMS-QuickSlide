<?php 
class AlbumsController extends QuickSlideAppController {
    public $uses = array('QuickSlide.Album', 'QuickSlide.Link');
    
    public function beforeFilter() {
        parent::beforeFilter();

        $this->JqueryUI->theme();
        $this->setCrumb(array(__d('quick_slide', 'Albums'), '/admin/quick_slide/albums'));
    }

    public function admin_index() {
        $conditions = isset($this->data['search']) ? "Album.name LIKE '%{$this->data['search']}%'" : "";
        $results = $this->paginate('Album', $conditions);
        $this->set('results', $results);
    }

    public function admin_edit($id, $tab = false) {
        if (isset($this->data['Album'])) {
            $validate = $tab != 'audio';

            if ($this->Album->save($this->data, $validate)) {
                $this->flashMsg(__d('quick_slide', 'Album has been updated'), 'success');
                $this->redirect($this->referer());
            } else {
                $this->flashMsg(__d('quick_slide', 'Album could not be saved'), 'error');
            }
        }

        $this->data = $this->Album->find('first', array('conditions' => "Album.id = {$id}"));

        switch ($tab) {
            case 'audio':
                $mp3 = array(0 => __d('quick_slide', 'No audio for this album'));
                $files = QS::directory(QS_FOLDER . "album-audio" . DS, "mp3");

                foreach ($files as $f) {
                    $mp3[$f] = $f;
                }

                $this->set('mp3', $mp3);

                $view = 'edit_audio';
            break;

            case 'content':
                if (!count($this->data['Image'])) {
                    $this->flashMsg(
                        __d('quick_slide', 'This album is empty.') .
                        ' <a href="' . Router::url("/admin/quick_slide/albums/edit/{$this->data['Album']['id']}/upload") . '">' . 
                            __d('quick_slide', 'Click here for upload contents') .
                        '</a>',
                        'alert'
                    );
                }

                $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfobject.js';
                $this->Layout['javascripts']['file'][] = '/comment/js/jquery.scrollTo-min.js';

                $this->JqueryUI->add('slider');
                $this->JqueryUI->add('droppable');
                $this->JqueryUI->add('draggable');
                $this->JqueryUI->add('sortable');

                $view = 'edit_content';
            break;

            case 'upload':
                $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfupload/swfupload.js';
                $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfupload/swfupload.queue.js';
                $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfupload/fileprogress.js';
                $this->Layout['javascripts']['file'][] = '/quick_slide/js/swfupload/handlers.js';
                $view = 'edit_upload';
            break;

            case 'summary':
                default:
                    if (!$this->data['Album']['status']) {
                        $this->flashMsg(__d('quick_slide', 'This album is inactive. Set its publish status to active to make it available to galleries.'), 'alert', 'album-off');
                    }

                    $this->JqueryUI->add('dialog');

                    $view = 'edit_summary';
            break;
        }

        $this->title(__d('quick_slide', 'Album: %s', $this->data['Album']['name']));
        $this->set('tab', str_replace('edit_', '', $view));
        $this->set('all_albums', $this->Album->find('all', array('fields' => array('id', 'name'), 'recursive' => -1))); /* for quick switcher */
        $this->render("admin_{$view}");
    }

    public function admin_delete($id) {
        $this->Album->delete($id, true);
        $this->redirect($this->referer());
    }

    public function admin_update() {
        $status = $this->Album->save($this->data);

        if (!$status) {
            header('HTTP/1.1 403 Forbidden');
        }

        $this->Album->id = $this->data['Album']['id'];
        $this->set('albumData', $this->Album->read());
        $this->render("/elements/album_summary_right_panel");
    }

    public function update_audio() {
        $this->Album->save($this->data);
        die();
    }

    public function admin_add() {
        if (isset($this->data['Album'])) {
            $this->Album->create($this->data);

            if ($new = $this->Album->save()) {
                $this->flashMsg(__d('quick_slide', 'The album has been created'));
                $this->redirect("/admin/quick_slide/albums/edit/{$new['Album']['id']}");
            } else {
                $this->flashMsg(__d('quick_slide', 'Album could not be created'));
            }
        }
    }

    public function activate($id) {
        $data['Album'] = array('id' => $id, 'active' => 1);
        $this->Album->save($data);
        $this->redirect($this->referer());
    }

    public function desactivate($id) {
        $data['Album'] = array('id' => $id, 'active' => 0);
        $this->Album->save($data);
        $this->Link->deleteAll("Link.aid = {$id}");
        $this->redirect($this->referer());
    }

    public function live_search() {
        $toSearch = $this->params['form']['search_live'];
        $result = $this->Album->find('all', array('conditions' => "Album.name LIKE '%{$toSearch}%'"));

        if (count($result)) {
            echo "<ul>\n";

            foreach($result as $item) {
                echo "<li>".str_ireplace($toSearch, "<span id='coincidence'>".$toSearch."</span>", $item['Album']['name'])."</li> \n";
            }

            echo "</ul>\n";
        } else {
            echo "<ul>\n<li>No se encontraron coincidencias</li>\n</ul>\n";
        }

        die();    
    }
}