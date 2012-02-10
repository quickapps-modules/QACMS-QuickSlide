<?php
    $tSettings = array(
        'columns' => array(
            __d('quick_slide', 'Gallery Name') => array(
                'value' => '
                    {link}{Gallery.name}|/admin/quick_slide/galleries/summary/{Gallery.id}{/link}<br />
                    <em>{php} return !"{Gallery.description}" ? "' . __d('quick_slide', 'This gallery has no description.') . '" : "{Gallery.description}"; {/php}</em>
                ',
                'tdOptions' => array('width' => '40%', 'align' => 'left'),
                'sort' => 'Gallery.name'
            ),
            __d('quick_slide', '# Albums') => array(
                'value' => '{php} return count($row_data["Album"]); {/php}'
            ),
            __d('quick_slide', 'Actions') => array(
                'value' => '
                    <a href="{url}/admin/quick_slide/galleries/summary/{/url}{Gallery.id}">{img border=0}/quick_slide/img/edit_ico.gif{/img}</a>
                    <a href="{url}/admin/quick_slide/galleries/delete/{/url}{Gallery.id}" onclick="javascript: return confirm(\'' . __d('quick_slide', 'This will delete this gallery and all of the links created within it. Are you sure?') . '\');">{img border=0}/quick_slide/img/delete_ico.gif{/img}</a>
                ',
                'thOptions' => array('align' => 'right'),
                'tdOptions' => array('align' => 'right')
            )
        ),
        'noItemsMessage' => __t('There are no galleries to display'),
        'paginate' => true,
        'headerPosition' => 'top',
        'tableOptions' => array('width' => '100%', 'class' => 'galleries-list')
    );
?>

<!-- table results -->
<?php echo $this->Html->table($this->data, $tSettings); ?>