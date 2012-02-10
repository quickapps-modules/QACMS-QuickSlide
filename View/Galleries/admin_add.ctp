<?php echo $this->Form->create('Gallery'); ?>
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Add New Gallery')); ?>
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
        <?php echo $this->Form->submit(__d('quick_slide', 'Add Gallery')); ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

<?php echo $this->Form->end(); ?>