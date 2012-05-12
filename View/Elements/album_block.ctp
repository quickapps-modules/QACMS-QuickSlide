<div id="album_<?php echo $album['Link']['id']; ?>" class="image-block album-block">
	<div align="center" class="header">
		<?php echo $album['name']; ?>
	</div>

	<div class="album-body">
		<div class="preview">
			<?php echo $this->QuickSlideHook->qs_album_tn($album, 46, 36); ?>
		</div>

		<div class="meta">
			<?php echo __d('quick_slide', 'Created: %s', date('Y/m/d', $album['created'])); ?><br />
			<?php echo __d('quick_slide', 'Modified: %s', date('Y/m/d', $album['modified'])); ?>
		</div>
	</div>

	<div id="counter_<?php echo $album['Link']['id']; ?>" class="counter"></div>

	<div class="editBttns">
		 <a href="" class="active-image-btn actBtn" title="<?php echo __d('quick_slide', 'Remove from gallery'); ?>" onclick="remove_album(<?php echo $album['Link']['id']; ?>); return false;"></a>
		<a title="<?php echo __d('quick_slide', 'Edit this album'); ?>" class="edit-image-btn" href="<?php echo $this->Html->url("/admin/quick_slide/albums/edit/{$album['id']}"); ?>"></a>
	</div>
</div>