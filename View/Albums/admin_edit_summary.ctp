<div style="width:49%; float:left;">
	<?php echo $this->Form->create('Album'); ?>
		<div align="right">
			<?php echo $this->Form->submit(__d('quick_slide', 'Update Album')); ?>
		</div>

		<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Album Information')); ?>
			<?php echo $this->Form->hidden('Album.id'); ?>
			<?php
				echo $this->Form->input('Album.name',
					array(
						'label' =>
							$this->QuickSlideHook->qs_tooltip(
								'Album Title',
								'This identifies this album within the management system, and also appears as your album title in the SlideShow Player.'
							)
					)
				);
			?>

			<?php
				echo $this->Form->input('Album.description',
					array(
						'type' => 'textarea',
						'label' => __d('quick_slide', 'Album Description')
					)
				);
			?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>

		<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Publishing')); ?>
			<?php
				echo $this->Form->input('Album.status',
					array(
						'type' => 'select',
						'options' => array(
							0 => __d('quick_slide', 'Inactive'),
							1 => __d('quick_slide', 'Active')
						),
						'label' =>
							$this->QuickSlideHook->qs_tooltip(
								'Status',
								'Controls whether this album is available for publication. `Inactive` will keep this album from appearing in a gallery. `Active` will make this album available for inclusion in a gallery.'
							)
					)
				);
			?>

			<?php echo $this->Form->label('Album.XmlFilepath', __d('quick_slide', 'XML file path')); ?>
			<em><?php echo $this->Html->url("/quick_slide/xml/data/album:{$this->data['Album']['id']}", true); ?></em>

			<p><?php echo $this->Form->submit(__d('quick_slide', 'Embed Code'), array('onclick' => 'return false;', 'id' => 'EmbedCodeBtn')); ?></p>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
</div>

<div style="width:49%; float:right;">
	<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'History')); ?>
		<p>
			<?php echo $this->User->avatar($this->data['CreatedBy'], array('width' => 24, 'height' => 24, 'align' => 'left')); ?>
			&nbsp;
			<?php echo __d('quick_slide', 'This album was created by %s on %s.', "<b>{$this->data['CreatedBy']['name']}</b>", date(__d('quick_slide', 'F jS, Y'), $this->data['Album']['created'])); ?>
		</p>

		<p>
			<?php echo $this->User->avatar($this->data['ModifiedBy'], array('width' => 24, 'height' => 24, 'align' => 'left')); ?>
			&nbsp;<?php printf(__d('quick_slide', "The last user to modify this album was %s on %s."), "<b>{$this->data['ModifiedBy']['name']}</b>", date(__d('quick_slide', 'F jS, Y'), $this->data['Album']['modified'])); ?>
		</p>
	<?php echo $this->Html->useTag('fieldsetend'); ?>

	<?php
		echo $this->Html->useTag('fieldsetstart',
			$this->QuickSlideHook->qs_tooltip(
				'Galleries added to',
				'The following galleries contain this album. To remove this album from a gallery click on a gallery title.'
			)
		);
	?>
		<?php if (count($this->data['Gallery'])): ?>
			<ul>
			<?php foreach ($this->data['Gallery'] as $gallery): ?>
				<li><a href="<?php echo $this->Html->url("/admin/quick_slide/galleries/summary/{$gallery['id']}"); ?>"><?php echo $gallery['name']; ?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<?php echo ($this->data['Album']['status']) ? __d('quick_slide', 'This album has not been included on galleries.') : __d('quick_slide', 'This album is inactive and is not a part of any galleries.'); ?>
		<?php endif; ?>
	<?php echo $this->Html->useTag('fieldsetend'); ?>
</div>

<?php echo $this->element('embed_code_generator'); ?>