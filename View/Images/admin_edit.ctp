<div id ="edit-box">
<?php echo $this->Form->create('Image', array('onsubmit' => 'update_image(); return false;')); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Editing Image')); ?>
        <div class="top-buttons">
            <?php echo $this->Form->input(__d('quick_slide', 'Previous'), array('label' => false, 'type' => 'submit', 'class' => 'prev', 'onclick' => 'prev_image(); return false;')); ?>
            <span><?php echo $this->Html->link($this->data['Image']['src'], "/files/quick_slide/album-{$this->data['Image']['aid']}/{$this->data['Image']['src']}", array('escape' => false, 'target' => '_blank')); ?></span>
            <?php echo $this->Form->input(__d('quick_slide', 'Next'), array('label' => false, 'type' => 'submit', 'class' => 'next', 'onclick' => 'next_image(); return false;')); ?>

            <?php echo $this->Form->input(__d('quick_slide', 'Close'), array('label' => false, 'type' => 'submit', 'class' => 'close', 'onclick' => 'hide_image_edit(); return false;')); ?>
        </div>
    
        <div id="edit-box-left">
            <?php echo $this->element('QuickSlide.image_edit_frame'); ?>
        </div>

        <div id="edit-box-right">
            <?php echo $this->Form->hidden('Image.id'); ?>
            <?php echo $this->Form->hidden('Image.src'); ?>
            <?php echo $this->Form->hidden('Album.aTn'); ?>
            <?php echo $this->Form->hidden('Album.id'); ?>
            <?php echo $this->Form->input('Image.title', array('type' => 'text', 'label' => __d('quick_slide', 'Title'))); ?>
            <?php echo $this->Form->input('Image.author', array('type' => 'text', 'label' => __d('quick_slide', 'Author'))); ?>
            <?php echo $this->Form->input('Image.link', array('type' => 'text', 'label' => __d('quick_slide', 'URL'))); ?>
            <?php echo $this->Form->input('Image.target', array('type' => 'checkbox', 'label' => __d('quick_slide', 'Open link in same browser window.'))); ?>
            <?php
                if (QS::isImg($this->data['Image']['src'])) {
                    echo $this->Form->input('Image.preview',
                        array(
                            'type' => 'select',
                            'label' => __d('quick_slide', 'Album Preview'),
                            'options' => array(
                                'nouses' => __d('quick_slide', 'Do not use as album preview'),
                                'exclude' => __d('quick_slide', 'Use as album preview and remove from slideshow'),
                                'include' => __d('quick_slide', 'Use as album preview and keep in slideshow')
                            )
                        )
                    );
                } else {
                    echo $this->Form->hidden('Image.preview', array('value' => 'nouses'));
                }
            ?>
            <?php echo $this->Form->input('Image.caption', array('type' => 'text', 'label' => __d('quick_slide', 'Description'))); ?>
            <?php echo $this->Form->input('Image.tags', array('type' => 'text', 'label' => __d('quick_slide', 'Tags'))); ?>
            <span id="image-messenger" class="form-message"></span>
            <?php echo $this->Form->submit(__d('quick_slide', 'Save Changes')); ?>
        </div>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>
</div>