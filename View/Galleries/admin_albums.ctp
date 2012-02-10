<div id="albums-dialog" style="display:hidden;"></div>
<?php
    $this->Layout->script(
        "$(document).ready(function () {
            albums_counters();
            albums_sortable();
        });", 'inline'
    );
?>
<div style="overflow:hidden;">
    <?php
        echo $this->Form->submit(__d('quick_slide', 'Add Album'),
            array(
                'onclick' => "add_album_dialog({$this->data['Gallery']['id']});",
                'class' => 'add-albums-btn'
            )
        );

        echo $this->Form->hidden('Gallery.id');
    ?>
</div>
<ul id="albums-grid">
    <?php echo $this->element('QuickSlide.albums_grid'); ?>
</ul>