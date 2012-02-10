<?php
    $tSettings = array(
        'columns' => array(
            __d('quick_slide', 'Album Name') => array(
                'value' => '
                    {link}{Album.name}|/admin/quick_slide/albums/edit/{Album.id}{/link}<br />
                    <em>{php} return !"{Album.description}" ? "' . __d('quick_slide', 'This album has no description.') . '" : "{Album.description}"; {/php}</em>
                ',
                'tdOptions' => array('width' => '40%', 'align' => 'left'),
                'sort' => 'Album.name'
            ),
            __d('quick_slide', '# Images') => array(
                'value' => '{php} return count($row_data["Image"]); {/php}'
            ),
            __d('quick_slide', 'Preview') => array(
                'value' => '<img class="album-tn" src="{php} $atn = "{Album.aTn}"; return empty($atn) ? Router::url("/quick_slide/img/no_preview.png") : Router::url("/quick_slide/images/p/") . QS::p(QS_FOLDER . "album-{Album.id}" . DS . "{Album.aTn}", 46, 46, 100, 1, 0, 0, 0); {/php}"/>'
            ),
            __d('quick_slide', 'Actions') => array(
                'value' => '
                    <a href="{url}/admin/quick_slide/albums/edit/{/url}{Album.id}">{img border=0}/quick_slide/img/edit_ico.gif{/img}</a>
                    <a href="{url}/admin/quick_slide/albums/delete/{/url}{Album.id}" onclick="javascript: return confirm(\'' . __d('quick_slide', 'This will delete the album from the database and from your server. Are you sure you want to do this ?') . '\');">{img border=0}/quick_slide/img/delete_ico.gif{/img}</a>
                ',
                'thOptions' => array('align' => 'right'),
                'tdOptions' => array('align' => 'right')
            )
        ),
        'noItemsMessage' => __t('There are no albums to display'),
        'paginate' => true,
        'headerPosition' => 'top',
        'tableOptions' => array('width' => '100%', 'class' => 'albums-list'),
        'rowOptions' => array('class' => 'album {php} return "{Album.status}" == "1" ? "active" : "inactive"; {/php}')
    );
?>

<b><?php echo __d('quick_slide', "Show"); ?>:</b>&nbsp;
<select id="album-filter" onchange="filter_albums_list();">
    <option value="0"><?php echo __d('quick_slide', "All"); ?></option>
    <option value="1"><?php echo __d('quick_slide', "Actives"); ?></option>
    <option value="2"><?php echo __d('quick_slide', "Inactives"); ?></option>
</select>

<!-- table results -->
<?php echo $this->Html->table($results, $tSettings); ?>

<p>&nbsp;</p>

<fieldset id="list-alert" style="display:none;">
    <?php echo __d('quick_slide', "No albums that match your filter settings were found."); ?>
</fieldset>