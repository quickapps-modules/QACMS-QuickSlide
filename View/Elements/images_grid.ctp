<?php if (count($this->data['Image'])): ?>
	<div id="track" style="width: 200px; height:18px;">
		<div id="slider"></div>
	</div>

	<ul id="images-grid">
		<?php foreach ($this->data['Image'] as $image): ?>
			<li id="img_<?php echo $image['id']; ?>">
				<?php echo $this->element('QuickSlide.image_block', array('image' => $image, 'album' => $this->data['Album'])); ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<script type="text/javascript">
		scaleIt(0);
		images_scale_slider();
		images_counters();
		images_droppables();
		images_sortable();
	</script>
<?php endif; ?>