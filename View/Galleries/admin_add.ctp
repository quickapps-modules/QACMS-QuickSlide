<?php echo $this->Form->create('Gallery'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Title & Description')); ?>
        <?php
            echo $this->Form->input('Gallery.name',
                array(
                    'type' => 'text',
                    'label' => 
                    __d('quick_slide', ('Gallery Name')) . ' ' . 
                    $this->Html->link('[?]', '#',
                        array(
                            'title' => __d('quick_slide', 'This identifies this gallery within the management system. It does not appear in Slideshow Viewer.')
                        )
                    )
                )
            );
        ?>

        <?php 
            echo $this->Form->input('Gallery.description',
                array(
                    'type' => 'textarea',
                    'label' => __d('quick_slide', 'Gallery description') . ' ' . 
                    $this->Html->link('[?]', '#',
                        array(
                            'title' => __d('quick_slide', 'Provides extra information about this gallery for organizational purposes within the management system. It does not appear in Slideshow Viewer.')
                        )
                    )
                    
                )
            );
        ?>  
        <?php echo $this->Form->submit(__d('quick_slide', 'Add Gallery')); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

<?php echo $this->Form->end(); ?>