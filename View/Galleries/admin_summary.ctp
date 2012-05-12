<div style="width:49%; float:left;">
	<?php echo $this->Form->create('Gallery'); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Gallery Information')); ?>
			<?php echo $this->Form->hidden('Gallery.id'); ?>
			<?php
				echo $this->Form->input('Gallery.name',
					array(
						'type' => 'text',
						'label' =>
							$this->QuickSlideHook->qs_tooltip(
								'Gallery Name',
								'This identifies this gallery within the management system. It does not appear in SlideShow Player.'
							)
					)
				);
			?>
			<?php
				echo $this->Form->input('Gallery.description',
					array(
						'type' => 'textarea',
						'label' =>
							$this->QuickSlideHook->qs_tooltip(
								'Gallery Description',
								'Provides extra information about this gallery for organizational purposes within the management system. It does not appear in SlideShow Player.'
							)
					)
				);
			?>
			<?php echo $this->Form->submit(__d('quick_slide', 'Update Gallery')); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Publishing')); ?>
			<?php echo $this->Form->label('Gallery.XmlFilepath', __d('quick_slide', 'XML file path')); ?>
			<em><?php echo $this->Html->url("/quick_slide/xml/data/gallery:{$this->data['Gallery']['id']}", true); ?></em>

			<p><?php echo $this->Form->submit(__d('quick_slide', 'Embed Code'), array('onclick' => 'return false;', 'id' => 'EmbedCodeBtn')); ?></p>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
</div>

<div style="width:49%; float:right;">
	<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Albums in this gallery (%d)', count($this->data['Album']))); ?>
		<?php if (count($this->data['Album'])): ?>
			<ul class="gallery-albums">
			<?php foreach ($this->data['Album'] as $album): ?>
				<li>
					<?php echo $this->QuickSlideHook->qs_album_tn($album, 46, 36); ?>
					<a href="<?php echo $this->Html->url("/admin/quick_slide/albums/edit/{$album['id']}"); ?>"><?php echo $album['name'];?></a>
				</li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<?php echo __d('quick_slide', 'There are no albums on this gallery'); ?>
		<?php endif; ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

</div>

<?php echo $this->element('embed_code_generator'); ?>