<?php if (empty($this->data)): ?>
    <?php echo __d('quick_slide', 'There are no albums available.'); ?>
<?php else: ?>
    <?php foreach ($this->data as $album): ?>
        <div class="album-row-preview" id="album-add-<?php echo $album['Album']['id']; ?>">
            <div class="tn">
                <a href="" class="inactive-image-btn" onclick="add_album(<?php echo $album['Album']['id']; ?>); return false;"></a>
                <a href="<?php echo $this->Html->url("/admin/quick_slide/albums/edit/{$album['Album']['id']}"); ?>">
                    <?php echo $this->QuickSlideHook->qs_album_tn($album['Album'], 46, 36); ?>
                </a>
            </div>

            <div class="meta">
                <span><?php echo $album['Album']['name']; ?></span><br />
                <em><?php echo __d('quick_slide', '%d images', count($album['Image'])); ?></em>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>