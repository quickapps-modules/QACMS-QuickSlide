<div style="width:49%; float:left;">
    <?php echo $this->Form->create('Album'); ?>
        <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Title & Description')); ?>
            <?php echo $this->Form->hidden('Album.id'); ?>
            <?php
                echo $this->Form->input('Album.name',
                    array(
                        'label' => 
                            __d('quick_slide', 'Album Title') . ' ' .
                            $this->Html->link('[?]', '#',
                                array(
                                    'title' => __d('quick_slide', 'This identifies this album within the management system, and also appears as your album title in the Slideshow Viewer.')
                                )
                            )
                    )
                );
            ?>

            <?php
                echo $this->Form->input('Album.status',
                    array(
                        'type' => 'radio',
                        'options' => array(
                            0 => __d('quick_slide', 'Unpublished'),
                            1 => __d('quick_slide', 'Published')
                        ),
                        'separator' => '&nbsp; | &nbsp;',
                        'legend' => 
                            __d('quick_slide', 'Publish Status') . ' ' .
                            $this->Html->link('[?]', '#',
                                array(
                                    'title' => __d('quick_slide', 'Controls whether this album is available for publication. `Inactive` will keep this album from appearing in a gallery. `Active` will make this album available for inclusion in a gallery.')
                                )
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

            <?php echo $this->Form->submit(__d('quick_slide', 'Update Album')); ?>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->end(); ?>
</div>

<div style="width:49%; float:right;">
    <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'History')); ?>
        <p>
            <?php echo $this->Layout->userAvatar($this->data['CreatedBy'], array('width' => 24, 'height' => 24, 'align' => 'left')); ?>
            &nbsp;
            <?php echo __d('quick_slide', 'This album was created by %s on %s.', "<b>{$this->data['CreatedBy']['name']}</b>", date(__d('quick_slide', 'F jS, Y'), $this->data['Album']['created'])); ?>
        </p>

        <p>
            <?php echo $this->Layout->userAvatar($this->data['ModifiedBy'], array('width' => 24, 'height' => 24, 'align' => 'left')); ?>
            &nbsp;<?php printf(__d('quick_slide', "The last user to modify this album was %s on %s."), "<b>{$this->data['ModifiedBy']['name']}</b>", date(__d('quick_slide', 'F jS, Y'), $this->data['Album']['modified'])); ?>
        </p>
    <?php echo $this->Html->useTag('fieldsetend'); ?>

    <?php 
        echo $this->Html->useTag('fieldsetstart',
            __d('quick_slide', 'Galleries added to') . ' ' .
            $this->Html->link('[?]', '#',
                array(
                    'title' => __d('quick_slide', 'The following galleries contain this album. To remove this album from a gallery click on a gallery title.')
                )
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
            <?php echo ($this->data['Album']['status']) ? __d('quick_slide', "This album has not been included on galleries.") : __d('quick_slide', 'This album is inactive and is not a part of any galleries.'); ?>
        <?php endif; ?>
    <?php echo $this->Html->useTag('fieldsetend'); ?>
</div>