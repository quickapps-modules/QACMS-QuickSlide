<?php
$imageFolder = QS_FOLDER . "album-{$album['id']}" . DS;

if (QS::isImg($image['src'])) {
    $video_thumb = false;
    $arr = $image['anchor'];

    if (empty($arr)) {
        $arr['x'] = $arr['y'] = 50; 
    }

    $path = "{$imageFolder}{$image['src']}";
    $img_url = $this->Html->url("/quick_slide/images/p/"). QS::p($path, 172, 132, 70, 1, $arr['x'], $arr['y'], 0);
} else {
    # Check for video thumb
    $pos = strrpos($image['src'], '.');
    $clean = substr($image['src'], 0, $pos);
    $custom = glob("{$imageFolder}___tn___" . $clean . '.*');
    $video_thumb = true;
    $img_url = $this->Html->url("/quick_slide/images/p/") . QS::p(QS::movieThumb("{$imageFolder}{$image['src']}"), 172, 132, 70, 0, 0, 0, 0);
}

$images[] = compact(
    'img',
    'aTn',
    'img_url',
    'video_thumb'
);
?>

<div id="image_<?php echo $image['id']; ?>" class="image-block <?php echo $image['status'] == 1 ? "active " : "inactive "; ?><?php echo QS::isImg($image['src']) ? "image " : "video "; ?>">
    <div id="meta_<?php echo $image['id']; ?>" class="meta"><?php echo $image['src']; ?></div>

    <a onclick="return false;" class="img-container">
        <div class="scale-image">
            <img id="drop_<?php echo $image['id']; ?>" src="<?php echo $img_url; ?>" width="100%" border="0" onMouseDown="image_select(<?php echo $image['id']; ?>);" />
            <img src="<?php echo $this->Html->url("/quick_slide/img/vid_overlay.gif"); ?>" class="video-overlay" width="15" height="15" border="0" <?php echo !$video_thumb ? 'style="display:none;"' : ''; ?> />
        </div>
    </a>

    <div id="counter_<?php echo $image['id']; ?>" class="<?php echo $image['status'] == 1 ? 'counter' : 'counter-off'; ?>" >
        <span class="<?php echo $image['src'] == $album['aTn'] ? 'is-preview' : ''; ?>">
            <span class="img-num"></span>
            <span class="inactive-subtitle"><?php echo $image['status'] == 1 ? "&nbsp;" : __d('quick_slide', 'Inactive'); ?></span>
            <span class="is-preview-subtitle"><?php echo $image['src'] == $album['aTn'] ? __d('quick_slide', ' (Album Preview)') : ''; ?></span>
        </span>
    </div>

    <div class="editBttns">
        <?php if ($image['status'] == 1) { ?>
            <a href="" class="active-image-btn actBtn" title="<?php echo __d('quick_slide', 'Desactivate'); ?>" onClick="toggle_image(<?php echo $image['id']; ?>); return false;"></a>
        <?php } else { ?>
            <a href="" class="inactive-image-btn actBtn" title="<?php echo __d('quick_slide', 'Activate'); ?>" onClick="toggle_image(<?php echo $image['id']; ?>); return false;"></a>
        <?php } ?>
        
        <a href="" title="<?php echo __d('quick_slide', "Edit"); ?>" class="edit-image-btn" onClick="edit_image('<?php echo $image['id']; ?>'); return false;"></a>
        
        <?php if (QS::isImg($image['src'])) { ?>
            <a href=""  title="<?php echo __d('quick_slide', 'Rotate Left'); ?>" onClick="rotate_img(<?php echo $image['id']; ?>, 90); return false;" class="rotleft-image-btn"></a>
            <a href=""  title="<?php echo __d('quick_slide', 'Rotate Right'); ?>" onClick="rotate_img(<?php echo $image['id']; ?>, -90); return false;" class="rotright-image-btn"></a>
        <?php } ?>

        <a href="" title="<?php echo __d('quick_slide', 'Delete'); ?>" class="delete-image-btn"  onClick="delete_image(<?php echo $image['id']; ?>, <?php echo $image['aid']; ?>); return false;"></a>
    </div>
</div>