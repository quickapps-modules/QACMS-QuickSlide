<?php
class ImagesController extends QuickSlideAppController {
    public $uses = array('QuickSlide.Image');

    public function __construct($request = null, $response = null) {
        $params = Router::getParams();

        if ($params['action'] == 'admin_upload') {
            App::uses('CakeSession', 'Model/Datasource');
            CakeSession::id($params['named']['session_id']);
            CakeSession::start();
        }

        parent::__construct($request, $response);
    }

    public function admin_video_thumb($video, $image) {
        $image = $this->Image->findById($image);
        $video = $this->Image->findById($video);
        $aid = $image['Image']['aid'];
        $imageSrc = $image['Image']['src'];
        $videoSrc = $video['Image']['src'];
        $newImageName = '___tn___' . str_replace('.' . QS::findexts($videoSrc), '.', $videoSrc) . QS::findexts($imageSrc);
        $oldImageName = '___tn___' . str_replace('.' . QS::findexts($videoSrc), '*.', $videoSrc) . '*';
        $newImageTn = str_replace('.' . QS::findexts($imageSrc), '', $imageSrc) . '*.' . QS::findexts($imageSrc);
        $leaving['lg'] = glob(QS_FOLDER . "album-{$aid}" . DS . $newImageName);
        $leaving['olg'] = glob(QS_FOLDER . "album-{$aid}" . DS . $oldImageName);
        $leaving['tn'] = glob(QS_FOLDER . "album-{$aid}" . DS . 'cache' . DS . $oldImageName);
        $leaving['ntn'] = glob(QS_FOLDER . "album-{$aid}" . DS . 'cache' . DS . $newImageTn);

        foreach($leaving as $la) {
            foreach ($la as $l) {
                @unlink($l);
            }
        }

        rename(QS_FOLDER . "album-{$aid}" . DS . $imageSrc, QS_FOLDER . "album-{$aid}" . DS . $newImageName);

        if ($imageSrc == $image['Album']['aTn']) {
            $image['Album']['aTn'] = '';
            $this->Image->Album->save($image['Album']);
        }

        die(' ');
    }

    public function admin_rotate($ids, $deg) {
        $ids = explode(",", $ids);
        $degree = $deg;
        $images = $this->Image->find('all', array('conditions' => array('Image.id' => $ids), 'recursive' => -1));

        foreach ($images as $image) {
            $path = QS_FOLDER . "album-{$image['Image']['aid']}" . DS;
            $leaving['tn'] = glob(QS_FOLDER . "album-{$image['Image']['aid']}" . DS . 'cache' . DS . str_replace("." . QS::findexts($image['Image']['src']), '', $image['Image']['src']) . "_*." . QS::findexts($image['Image']['src']));

            foreach($leaving['tn'] as $l) {
                @unlink($l);
            }

            QS::rotateImage($path . $image['Image']['src'], $path . $image['Image']['src'], $degree);
            QS::rotateImage($path . 'cache' . DS . $image['Image']['src'], $path . 'cache' . DS . $image['Image']['src'], $degree);
        }

        die(' ');
    }

    public function admin_upload() {
        if ($this->data['Upload']['type'] == 'audio' ||
            in_array($this->params['form']['Filedata']['type'], Configure::read('qs_mimes'))
        ) {
            App::import('Vendor', 'Upload');

            $handle = new Upload($this->params['form']['Filedata']);
            $handle->file_overwrite = false;
            $folder = $this->data['Upload']['type'] == 'audio' ? QS_FOLDER . 'album-audio' . DS : QS_FOLDER . "album-{$this->data['Album']['id']}" . DS;
            $old_mask = umask(0);

            $handle->Process($folder);

            if ($handle->processed) {
                if ($this->data['Upload']['type'] == 'image') {
                    $images_count = $this->Image->find('count', array('conditions' => "Image.aid = {$this->data['Album']['id']}"));
                    $data['Image'] = array(
                        'aid' => $this->data['Album']['id'],
                        'src' => $handle->file_dst_name,
                        'seq' => $images_count+1,
                        'filesize' => filesize($folder."/".$handle->file_dst_name),
                        'active' => 1
                    );
                    $this->Image->save($data);
                    $adata['Album'] = array('id' => $this->data['Album']['id']);
                    $this->Image->Album->save($adata); // auto modified,updated_by

                    header("HTTP/1.1 200 OK");
                    die(' ');
                }
            } else {
                header("HTTP/1.1 500 File Upload Error");
                echo "Error: {$handle->error}";
            }

            umask($old_mask);
        } else {
            header("HTTP/1.1 500 File Upload Error");
            echo "Error: no data given";
        }

        die(' ');
    }

    public function admin_anchor($id) {
        $this->Image->id = $id;
        $image = $this->Image->read();
        $folder = QS_FOLDER . "album-{$image['Album']['id']}" . DS;
        $videoThumb = '___tn___' . str_replace('.' . QS::findexts($image['Image']['src']), '.', $image['Image']['src']) . '*';
        $cache_thumb = 'cache' . DS . str_replace('.' . QS::findexts($image['Image']['src']), '', $image['Image']['src']) . "_*.*";
        $leaving[0] = glob($folder . $videoThumb);
        $leaving[1] = glob($folder . $cache_thumb);

        foreach($leaving as $la) {
            foreach ($la as $l) {
                unlink($l);
            }
        }

        $data['Image'] = array(
            'id' => $id,
            'anchor' => $this->data
        );

        $this->Image->save($data);
        die(Router::url("/quick_slide/images/p/") . QS::p($folder . $image['Image']['src'], 172, 132, 70, 1, $this->data['x'], $this->data['y'], 0));
    }

    public function admin_edit($id = false) {
        if (isset($this->data['Image'])) {
            switch($this->data['Image']['preview']) {
                case 'exclude':
                    $adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => $this->data['Image']['src']);
                    $this->data['Image']['status'] = 0;

                    $this->Image->Album->save($adata, false);
                break;

                case 'include':
                    $adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => $this->data['Image']['src']);
                    $this->data['Image']['status'] = 1;

                    $this->Image->Album->save($adata, false);
                break;

                default:
                    if ($this->data['Album']['aTn'] == $this->data['Image']['src']) {
                        $adata['Album'] = array('id' => $this->data['Album']['id'], 'aTn' => '');

                        $this->Image->Album->save($adata, false);
                    }
                break;
            }

            $this->Image->save($this->data);
            die(' ');
        }

        $data = $this->Image->find('first', array('conditions' => array('Image.id' => $id)));

        if ($data['Image']['status'] == 0 &&
            $data['Image']['src'] == $data['Album']['aTn']
        ) {
            $data['Image']['preview'] = 'exclude';
        } elseif($data['Image']['status'] > 0 &&
            $data['Image']['src'] == $data['Album']['aTn']
        ) {
            $data['Image']['preview'] = 'include';
        } else {
            $data['Image']['preview'] = 'nouses';
        }

        $this->data = $data;
    }

/**
 * Render encoded image request.
 *
 * @param string $args Encoded image request:
 *  $args[0] Full path to image
 *  $args[1] New width
 *  $args[2] New height
 *  $args[3] Quality (1 to 100)%
 *  $args[4] Square, 0 = FALSE, 1 = TRUE
 *  $args[5] Anchor X coord
 *  $args[6] Anchor Y coord
 *
 * @return die
 */
    public function p($args = false) {
        $args = !$args ? $this->request->query['i'] : $args;
        $specs = explode(',', base64_decode($args));
        $a[7] = isset($a[7]) ? trim($a[7]) : false;

        if (isset($this->request->query['full'])) {
            $full = explode(',', $this->request->query['full']);
            $specs[1] = $full[0];
            $specs[2] = $full[1];
        }

        @QS::image_resize($specs[0], $specs[1], $specs[2], $specs[3], $specs[4], $specs[5], $specs[6], $specs[7]);

        die(' ');
    }

    public function admin_delete($ids) {
        $ids = explode(',', $ids);
        $this->Image->cacheQueries = false;

        foreach ($ids as $id) {
            $this->Image->delete($id);
        }

        die(' ');
    }

    public function admin_toggle($ids) {
        $ids = explode(",", $ids);
        $data = array('Image.status' => $this->data['Image']['status']);
        $this->Image->updateAll($data, array('Image.id' => $ids));

        die(' ');
    }

    public function admin_sort() {
        if (isset($this->params['data']['img'])) {
            foreach($this->params['data']['img'] as $i => $id) {
                $data['Image'] = array('id' => $id, 'seq' => $i+1);
                $this->Image->save($data);
            }
        }

        die(' ');
    }
}