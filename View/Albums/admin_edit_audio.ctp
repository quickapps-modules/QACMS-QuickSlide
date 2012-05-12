<div style="width:49%; float:left;">
	<?php echo $this->Form->create('Album'); ?>
		<?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Album Audio')); ?>
			<?php echo $this->Form->hidden('Album.id'); ?>

			<?php if (count($mp3) > 1): ?>
				<?php
					echo $this->Form->input('Album.audio_file',
						array(
							'label' => __d('quick_slide', 'Select an audio file'),
							'type' => 'select',
							'options' => $mp3,
							'after' =>
								'&nbsp;&nbsp;' .
								$this->Html->link(
									$this->Html->image('/quick_slide/img/delete_ico.gif', array('border' => 0)),
									'#',
									array('onclick' => 'delete_audio(); return false;', 'escape' => false)
								) .
							'&nbsp;&nbsp;<span id="delete-audio-messenger" class="form-message"></span>',
						)
					);
				?>
			<?php else: ?>
				<span id="no_audio_msg"><?php echo __d('quick_slide', "No audio files were found in 'album-audio' folder."); ?></span>
			<?php endif; ?>

			<?php echo $this->Form->input('Album.audio_caption', array('type' => 'textarea', 'label' => __d('quick_slide', 'Audio caption'))); ?>

			<p>
				<b><?php echo __d('quick_slide', 'Note'); ?></b>:
				<?php echo __d('quick_slide', 'For songs to show up in the drop-down above, they must be uploaded to the album-audio folder. This can be done via FTP or the Upload tab.'); ?>
			</p>

			<?php echo $this->Form->submit(__d('quick_slide', 'Save changes')); ?>
		<?php echo $this->Html->useTag('fieldsetend'); ?>
	<?php echo $this->Form->end(); ?>
</div>