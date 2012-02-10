<?php
    define('QS_FOLDER', ROOT . DS . 'webroot' . DS . 'files' . DS . 'quick_slide' . DS);

    if (!file_exists(QS_FOLDER . 'slideshowpro.swf')) {
        define('QS_NO_SWF', true);
    }

    Configure::write('qs_mimes',
        array(
            "application/octet-stream",
            "application/x-shockwave-flash",
            "image/jpeg",
            "image/pjpeg",
            "image/gif",
            "image/jpg",
            "image/png"
        )
    ); 

    Configure::write('qs_exts',
        array(
            "swf",
            "flv",
            "jpeg",
            "pjpeg",
            "gif",
            "jpg",
            "png"
        )
    );