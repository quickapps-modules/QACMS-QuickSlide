<?php
    $exts = Configure::read('qs_exts');

    foreach ($exts as &$e) {
        $e = "*.{$e}";
    }

    $exts = implode(';', $exts);
?>

<div style="width:49%; float:left;">
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Add Content')); ?>
        <p>
            <?php echo __d('quick_slide', 'Click the "Browse" button below to select one or more files to upload.'); ?>
        </p>

        <b><?php echo __d('quick_slide', 'Browse for'); ?>:</b>

        <select name="upload_type" id="upload_type" onChange="toggle_type(this.value);">
            <option value="images"><?php echo __d('quick_slide', 'Content'); ?></option>
            <option value="audio"><?php echo __d('quick_slide', 'Album Audio'); ?></option>
        </select>
        
        <div id="btnBrowse">&nbsp;</div>

        <p>
            <b><?php echo __d('quick_slide', "Note"); ?>:</b>
            <?php echo __d('quick_slide', 'Your server limits the size of uploaded files to %sB per file.', ini_get('upload_max_filesize')); ?>
        </p>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>

<div style="width:49%; float:right;">
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Queue List')); ?>
         <script type="text/javascript">
            function toggle_type(type) {
                ext = new Array();
                switch(type) {
                    case "images":
                        ext[0] = '<?php echo $exts; ?>';
                        ext[1] = '<?php echo __d('quick_slide', 'Images') ?>';
                        ext[2] = 'image';
                    break;
                    
                    case "audio":
                        ext[0] = '*.mp3;';
                        ext[1] = '<?php echo __d('quick_slide', 'Audio files') ?>';
                        ext[2] = 'audio';
                    break;
                }
                swfu.setPostParams({'data[Album][id]' : <?php echo $this->data['Album']['id']; ?>, "data[Upload][type]" : ext[2] });
                swfu.setFileTypes(ext[0], ext[1]);
            }

            var swfu;
            window.onload = function() {
                swfu = new SWFUpload({
                    upload_url: '<?php echo $this->Html->url('/admin/quick_slide/images/upload/session_id:' . CakeSession::id(), true); ?>',
                    post_params: {'data[Album][id]' : <?php echo $this->data['Album']['id']; ?>, 'data[Upload][type]': 'image' },

                    file_types: '<?php echo $exts; ?>',
                    file_types_description: '<?php echo __d('quick_slide', "Images & Video"); ?>',

                    // The event handler functions are defined in handlers.js
                    swfupload_preload_handler: preLoad,
                    swfupload_load_failed_handler: loadFailed,
                    file_queued_handler: fileQueued,
                    file_queue_error_handler: fileQueueError,
                    file_dialog_complete_handler: fileDialogComplete,
                    upload_start_handler: uploadStart,
                    upload_progress_handler: uploadProgress,
                    upload_error_handler: uploadError,
                    upload_success_handler: uploadSuccess,
                    upload_complete_handler: uploadComplete,
                    queue_complete_handler: queueComplete, // Queue plugin event

                    flash_url: "<?php echo $this->Html->url("/quick_slide/js/swfupload/swfupload.swf"); ?>",
                    flash9_url: "<?php echo $this->Html->url("/quick_slide/js/swfupload/swfupload_fp9.swf"); ?>",

                    // Fix Flash PLayer 10
                    button_placeholder_id: "btnBrowse",
                    button_image_url: '<?php echo $this->Html->url("/quick_slide/img/uploader/upload_btn.png"); ?>',
                    button_text: '<span class="btn"><?php echo __d('quick_slide', 'Browse'); ?></span>',
                    button_width: 61,
                    button_height: 22,
                    button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
                    button_cursor: SWFUpload.CURSOR.HAND,
                    
                    custom_settings: {
                        progressTarget: 'fsUploadProgress',
                        cancelButtonId: 'btnCancel'
                    },
                    debug: false
                });
            };
        </script>

        <div style="width:100%; overflow:hidden; height:30px;">
            <span id="spanButtonPlaceHolder"></span>
            <input id="btnCancel" type="button" class="primary_lg right" value="<?php echo __d('quick_slide', "Stop Upload"); ?>" onclick="swfu.cancelQueue();" disabled="disabled" style="font-size: 8pt;" />
        </div>

        <div id="fsUploadProgress"></div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>