<div style="width:49%; float:left;">
    <?php echo $this->Form->create('Gallery'); ?>
        <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Title & Description')); ?>
            <?php echo $this->Form->hidden('Gallery.id'); ?>
            <?php
                echo $this->Form->input('Gallery.name',
                    array(
                        'type' => 'text',
                        'label' => 
                        __d('quick_slide', 'Gallery Name') . ' ' . 
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
                        'label' => __d('quick_slide', 'Gallery Description') . ' ' . 
                        $this->Html->link('[?]', '#',
                            array(
                                'title' => __d('quick_slide', 'Provides extra information about this gallery for organizational purposes within the management system. It does not appear in Slideshow Viewer.')
                            )
                        )
                        
                    )
                );
            ?>  
            <?php echo $this->Form->submit(__d('quick_slide', 'Update Gallery')); ?>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->end(); ?>
</div>

<div style="width:49%; float:right;">
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Albums in this gallery (%d)', count($this->data['Album']))); ?>
        <?php if (count($this->data['Album'])): ?>
            <ul>
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