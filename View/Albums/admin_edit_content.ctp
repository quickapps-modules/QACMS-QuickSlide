<?php if (count($this->data['Image'])): ?>
    <div class="ss-frame"><?php  echo $this->element('QuickSlide.images_grid'); ?></div>
    <div class="ss-frame" id="editImageContainer"></div>
<?php endif; ?>

<a href="<?php echo $this->Html->url('/quick_slide/img/loading.gif'); ?>" id="loading-gif" style="display:none;"></a>

<div id="multi-select" style="display:none;">
    <div class="fl">
        <b><span id="multi-count"></span></b>
        <?php echo __d('quick_slide', 'items selected'); ?> / <b><?php echo __d('quick_slide', 'Actions'); ?>: </b>
    </div>

    <div class="fl">
        <a title="<?php __d('quick_slide', "Desactivate"); ?>" class="active-image-btn" href="" onClick="mass_deactivate(0); return false;"></a>
        <a title="<?php __d('quick_slide', "Activate"); ?>" class="inactive-image-btn" href="" onClick="mass_deactivate(1); return false;"></a>
        <a title="<?php __d('quick_slide', "Delete"); ?>" class="delete-image-btn" href="" onClick="mass_delete(); return false;"></a>
    </div>
</div>