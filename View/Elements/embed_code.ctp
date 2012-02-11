<?php
    if (isset($attr['id'])) {
        $attr['xml_file_path'] = $this->Html->url('/quick_slide/xml/data/', true);
        $e = explode('-', $attr['id']);
        $type = @strtolower($e[0]);
        $id = @intval($e[1]);

        switch ($type) {
            case 'album':
               $attr['xml_file_path'] .= 'album:' . $id;
            break;

            case 'gallery':
                default:
                    $attr['xml_file_path'] .= 'gallery:' . $id;
        }
    }

    if (isset($attr['theme'])) {
        $attr['theme'] = strtolower($attr['theme']);
        $attr['param_xml_path'] = $this->Html->url("/quick_slide/themes/{$attr['theme']}.xml", true);
    }

    $__attr = array(
        'xml_file_path' => false,
        'param_xml_path' => false,
        'pan_zoom' => false,
        'display_mode' => false,
        'startup' => false,
        'video_auto_start' => false,
        'nav_appearance' => false,
        'nav_link_appearance' => false,
        'transition_style' =>false ,
        'feedback_preloader_appearance' => false,
        'content_scale' => 'Crop to Fit All',
        'id' => time(),
        'width' => 480,
        'height' => 350,
    );
    $attr = array_merge($__attr, $attr);

    if ($attr['param_xml_path']) {
        preg_match('/^(.*)\/(.*)\.xml$/i', $attr['param_xml_path'], $cssTheme);

        $cssTheme = isset($cssTheme[2]) ? $cssTheme[2] : 'default';
    } else {
        $cssTheme = 'default';
    }
?>

<div id="qs_<?php echo $attr['id']; ?>" class="quick-slider qs-theme-<?php echo $cssTheme; ?>" style="width:<?php echo $attr['width']; ?>px; height:<?php echo $attr['height']; ?>px;"></div>
<script type="text/javascript">
    <?php
        unset($__attr['id'], $__attr['width'], $__attr['height']);

        $attr['xml_file_path'] .= '/&nc=' . rand(100, 9999);
        $flashvars = array();

        foreach ($attr as $key => $value) {
            if ($value && !in_array($key, array('theme', 'id', 'width', 'height'))) {
                $key = $key == 'param_xml_path' ? 'param_x_m_l_path' : $key; # fix
                $value = is_string($value) ? "\"{$value}\"" : "{$value}";
                $flashvars[] = lcfirst(Inflector::camelize($key)) . ": " . $value;
            }
        }
    ?>
    $(document).ready(function () {
        var flashvars = {
            <?php echo implode(",\n", $flashvars); ?>
        };

        var params = {
            allowfullscreen: true
        };

        var attributes = { };

        swfobject.embedSWF(
            "<?php echo $this->Html->url('/files/quick_slide/slideshowpro.swf', true); ?>",
            "qs_<?php echo $attr['id']; ?>",
            "<?php echo $attr['width']; ?>",
            "<?php echo $attr['height']; ?>",
            "10", false, flashvars, params, attributes);
    });
</script>