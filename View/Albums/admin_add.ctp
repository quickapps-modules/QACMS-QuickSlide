<?php echo $this->Form->create('Album'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Add New Album')); ?>
        <?php
            echo $this->Form->input('Album.name',
                array(
                    'label' => __d('quick_slide', 'Album Title <a href="#" onClick="return false;" title="%s">[?]</a>',
                        __d('quick_slide', 'This identifies this album within the management system, and also appears as your album title in the Slideshow Viewer.')
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

        <?php echo $this->Form->submit(__d('quick_slide', 'Add Album')); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
<?php echo $this->Form->end(); ?>