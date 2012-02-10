<div id="embed-generator" style="display:none;">
    <?php echo $this->Form->create('Embed'); ?>
        <?php echo $this->Html->useTag('fieldsetstart', __d('quick_slide', 'Embed Slideshow')); ?>
            <div style="width:300px; float:left; font-size:10px;">
                <?php echo $this->Form->label('Embed.width', __d('quick_slide', 'Size')); ?>
                <?php echo $this->Form->text('Embed.width', array('size' => 2)); ?>x<?php echo $this->Form->text('Embed.height', array('size' => 2)); ?>
                <?php
                    echo $this->Form->input('Embed.theme',
                        array(
                            'type' => 'select',
                            'options' => Configure::read('qs_themes'),
                            'label' => __d('quick_slide', 'Theme')
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input('Embed.contentScale',
                        array(
                            'type' => 'select',
                            'options' => array(
                                'Downscale Only' => __d('quick_slide', 'Downscale Only'),
                                'Proportional' => __d('quick_slide', 'Proportional'),
                                'Crop to Fit' => __d('quick_slide', 'Crop to Fit'),
                                'Crop to Fit All' => __d('quick_slide', 'Crop to Fit All')
                            ),
                            'label' => __d('quick_slide', 'Content scale')
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input('Embed.transitionStyle',
                        array(
                            'type' => 'select',
                            'options' => array(
                                'Blur' => __d('quick_slide', 'Blur'),
                                'Cross Fade' => __d('quick_slide', 'Cross Fade'),
                                'Fade to Background' => __d('quick_slide', 'Fade to Background'),
                                'Dissolve' => __d('quick_slide', 'Dissolve'),
                                'Drop' => __d('quick_slide', 'Drop'),
                                'Lens' => __d('quick_slide', 'Lens'),
                                'Photo Flash' => __d('quick_slide', 'Photo Flash'),
                                'Push' => __d('quick_slide', 'Push'),
                                'Wipe' => __d('quick_slide', 'Wipe'),
                                'Wipe and Fade' => __d('quick_slide', 'Wipe and Fade'),
                                'Wipe to Background' => __d('quick_slide', 'Wipe to Background'),
                                'Wipe and Fade to Background' => __d('quick_slide', 'Wipe and Fade to Background')
                            ),
                            'label' => __d('quick_slide', 'Transition')
                        )
                    );
                ?>
                <?php
                    echo $this->Form->input('Embed.feedbackPreloaderAppearance',
                        array(
                            'type' => 'select',
                            'options' => array(
                                'Hidden' => __d('quick_slide', 'Hidden'),
                                'Bar' => __d('quick_slide', 'Bar'),
                                'Beam' => __d('quick_slide', 'Beam'),
                                'Line' => __d('quick_slide', 'Line'),
                                'Pie' => __d('quick_slide', 'Pie'),
                                'Pie Spinner' => __d('quick_slide', 'Pie Spinner'),
                                'Spinner' => __d('quick_slide', 'Spinner')
                            ),
                            'label' => __d('quick_slide', 'Preloader')
                        )
                    );
                ?>

                <table width="100%">
                    <tr>
                        <td width="50%" align="left">
                            <?php echo $this->Form->input('Embed.displayMode', array('label' => __d('quick_slide', 'Auto Playback'), 'type' => 'checkbox')); ?>
                            <?php echo $this->Form->input('Embed.startup', array('label' => __d('quick_slide', 'Open Gallery'), 'type' => 'checkbox')); ?>
                            <?php echo $this->Form->input('Embed.panZoom', array('label' => __d('quick_slide', 'Pan Zoom'), 'type' => 'checkbox')); ?>
                        </td>

                        <td align="left">
                            <?php echo $this->Form->input('Embed.videoAutoStart', array('label' => __d('quick_slide', 'Auto start videos'), 'type' => 'checkbox')); ?>
                            <?php echo $this->Form->input('Embed.navAppearance', array('label' => __d('quick_slide', 'Mouseover navigation'), 'type' => 'checkbox')); ?>
                            <?php echo $this->Form->input('Embed.navLinkAppearance', array('label' => __d('quick_slide', 'Thumbnail links'), 'type' => 'checkbox')); ?>
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="left"><?php echo $this->Form->submit(__d('quick_slide', 'Generate Hooktag Code'), array('onclick' => 'generate_embed_code(); return false;')); ?></td>
                        <td align="right"><?php echo $this->Form->submit(__d('quick_slide', 'Preview'), array('onclick' => 'preview_embed_code(); return false;')); ?></td>
                    </tr>
                </table>
            </div>
            
            <div id="embed-preview" style="width:480px; height:350px; float:right; margin-right:15px">
                <?php if(defined('QS_NO_SWF')): ?>
                    <!-- TODO Preview -->
                <?php else: ?>
                    <?php echo __d('quick_slide', 'SlideShow Pro Player not found. Preview not available.'); ?>
                <?php endif; ?>
            </div>
        <?php echo $this->Html->useTag('fieldsetend'); ?>
    <?php echo $this->Form->end(); ?>
</div>

<div id="embed-code-render" style="display:none;">
    <h3><?php echo __d('quick_slide', 'Your Hooktag Code'); ?>:</h3>
    <pre class="code-content"></pre>
</div>

<script>
    $('#EmbedCodeBtn').click(function () {
        $('#embed-generator').dialog({
            width:900,
            resizable: false
        });
    });
</script>