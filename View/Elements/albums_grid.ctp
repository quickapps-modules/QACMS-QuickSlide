	<?php foreach ($this->data['Album'] as $album): ?>
		<li id="albm_<?php echo $album['Link']['id']; ?>">
			<?php echo $this->element('QuickSlide.album_block', array('album' => $album)); ?>
		</li>
	<?php endforeach; ?>