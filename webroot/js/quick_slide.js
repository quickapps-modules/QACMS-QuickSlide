var current_image_edit = 0;
var anc_x = 50;
var anc_y = 50;
var cur_val = 0;
var selector = 'tr.album';

function filter_albums_list() {
    var val = $('#album-filter').val();
    var vis = false;

    if (cur_val != val) {
        switch (val) {
            case '0':
                selector = 'tr.album';

                $('tr.album').each(function () { $(this).show(); });
            break;

            case '1':
                selector = 'tr.active';

                $('tr.active').each(function () { $(this).show(); });
                $('tr.inactive').each(function () { $(this).hide(); });
            break;

            case '2':
                selector = 'tr.inactive';

                $('tr.active').each(function () { $(this).hide(); });
                $('tr.inactive').each(function () { $(this).show(); });
            break;
        }

        cur_val = val;
    }

    if ($(selector).length > 0) {
        vis = true;
    }

    if (vis) {
        $('table.albums-list').show();
        $('#list-alert').hide();
    } else {
        $('table.albums-list').hide();
        $('#list-alert').show();
    }
}

function images_counters() {
    var elem = $('.counter');
    var total = elem.length;

    for (i = 0; i < total; i++) {
        var el = $(elem[i]).children('span');
        var out = (i+1) + '/' + total;

        $(el).children('span.img-num').html(out);
    }

    if (total == 0) {
        $('#track').hide();
    } else {
        $('#track').show();
    }
}

function albums_counters() {
    var elem = $('div.counter');
    var total = elem.length;

    for (i = 0; i < total; i++) {
        var out = (i+1) + '/' + total;

        $(elem[i]).html(out);
    }
}

function albums_sortable() {
    try {
       $('#albums-grid').sortable('enable');
       $('#albums-grid').sortable('refresh');
    } catch (e) {

    }

    if ($('.album-block').length > 1) {
        $('#albums-grid').sortable({
            update: function(event, ui) {
                $.ajax({
                    type: 'POST',
                    data: $('#albums-grid').sortable('serialize'),
                    url: QuickApps.settings.base_url + 'admin/quick_slide/galleries/sort_albums',
                    success: function(data) { albums_counters(); }
                });
            },
            handle: 'div',
            cursor: 'crosshair'
        });
        $('#albums-grid').disableSelection();
    } else {
        try {
           $('#albums-grid').sortable('disable');
        } catch (e) {

        }
    }
}

function toggle_image(id) {
    var elem = $('#image_' + id);
    var val = 0;

    if ($(elem).hasClass('active')) { // active -> inactivate
        toggle_image_exec(id, false);
        val = 0;
    } else { // inactive -> activate
        toggle_image_exec(id, true);
        val = 1;
    }

    images_counters();

    var param = 'data[Image][status]=' + val;

    $.ajax({
        type: 'POST',
        data: param,
        url: QuickApps.settings.base_url + 'admin/quick_slide/images/toggle/' + id
    });
}

function toggle_image_exec(id, status) {
    if (status) {
        var counter = $('#image_' + id + ' .counter-off');

        if (counter) {
            counter.removeClass('counter-off');
            counter.addClass('counter');
        }

        $('#image_' + id).removeClass('inactive');
        $('#image_' + id).addClass('active');
        $('#image_' + id + ' .inactive-subtitle').html('&nbsp;');
        $('#image_' + id + ' .img-num').show();
        $('#image_' + id + ' .actBtn').removeClass('inactive-image-btn');
        $('#image_' + id + ' .actBtn').addClass('active-image-btn');
        $('#image_' + id + ' .actBtn').attr('title', QuickApps.__t('Desactivate'));
    } else {
        var counter = $('#image_' + id + ' .counter');

        if (counter) {
            counter.removeClass('counter');
            counter.addClass('counter-off');

            $('#image_' + id + ' .inactive-subtitle').html(QuickApps.__t('Inactive'));
            $('#image_' + id + ' .img-num').hide();
            $('#image_' + id + ' .actBtn').removeClass('active-image-btn');
            $('#image_' + id + ' .actBtn').addClass('inactive-image-btn');
            $('#image_' + id + ' .actBtn').attr('title', QuickApps.__t('Activate'));
        }

        $('#image_' + id).removeClass('active');
        $('#image_' + id).addClass('inactive');
    }

    images_counters();
}

function add_album_dialog(gid) {
    $("#albums-dialog").load(
        QuickApps.settings.base_url + 'admin/quick_slide/galleries/add_album/' + gid,
        function(response, status, xhr) {
            $('#albums-dialog').dialog({
                modal: true,
                autoOpen: true,
                draggable: false,
                width: 600,
                height: 400,
                maxWidth: 600,
                maxHeight: 400,
                minWidth: 600,
                minHeight: 400
            });
        }
    );
}

function add_album(aid) {
    $.ajax({
        type: 'POST',
        data: 'data[Album][id]=' + aid + '&data[Gallery][id]=' + $('#GalleryId').val(),
        url: QuickApps.settings.base_url + 'admin/quick_slide/galleries/add_link',
        success: function(data) {
            $('div#album-add-' + aid).remove();
            $('#albums-grid').html(data);

            if (!$('div.album-row-preview').length) {
                $('#albums-dialog').dialog('close');
            }

            albums_sortable();
            albums_counters();
        }
    });
}

function remove_album(link_id) {
    $.ajax({
        type: 'POST',
        data: 'data[Link][id]=' + link_id + '&data[Gallery][id]=' + $('#GalleryId').val(),
        url: QuickApps.settings.base_url + 'admin/quick_slide/galleries/delete_link',
        success: function(data) {
            $('#albm_' + link_id).fadeOut();
            $('#albm_' + link_id).remove();
            albums_sortable();
            albums_counters();
        }
    });
}

function clearClasses(class_name) {
    $('.' + class_name).each(function () {
        $(this).removeClass(class_name);
    });
}

function edit_image(id) {
    $('#image_' + current_image_edit + ' .editBttns').fadeIn();
    clearClasses('current');
    $("#editImageContainer").load(
        QuickApps.settings.base_url + 'admin/quick_slide/images/edit/' + id,
        function(response, status, xhr) {
            current_image_edit = id;
            $('#image_' + id).addClass('current');
            $('#image_' + id + ' .editBttns').fadeOut();
            $.scrollTo('#edit-box', 800);

            try { init_players(); } catch(e) {}
        }
    );  
}

function prev_image() {
    lis = $('#images-grid .active');

    for (i = 0; i < lis.length; i++) {
        if ($(lis[i]).hasClass('current')) {
            if (lis[i-1] == undefined) {
                id = $(lis[lis.length-1]).attr('id').split('image_')[1];
            } else {
                id = $(lis[i-1]).attr('id').split('image_')[1];
            }

            edit_image(id);

            break;
        }
    }
}

function next_image() {
    lis = $('#images-grid .active');

    for (i = 0; i < lis.length; i++) {
        if ($(lis[i]).hasClass('current')) {
            if (lis[i+1] == undefined) {
                id = $(lis[0]).attr('id').split('image_')[1];
            } else {
                id = $(lis[i+1]).attr('id').split('image_')[1];
            }

            edit_image(id);

            break;
        }
    }
}

function hide_image_edit() {
    $('#edit-box').hide('blind');
    $('#image_' + current_image_edit).removeClass('current');
    $('#image_' + current_image_edit + ' .editBttns').fadeIn();

    current_image_edit = 0;
}

function delete_image(image_id) {
    c = confirm('Delete this content from this album and from the server?');

    if (c) {
        delete_image_exe(image_id);
    }
}

function delete_image_exe(id) {
    $.ajax({
        type: 'POST',
        data: $('#images-grid').sortable('serialize'),
        url: QuickApps.settings.base_url + 'admin/quick_slide/images/delete/' + id,
        success: function(data) {
            $('li#img_' + id).fadeOut(function() {
                $('li#img_' + id).remove();
                images_counters();
            });
        }
    });
}

/**
 * Send Image form data using Ajax.
 *
 */
function update_image() {
    $('#image-messenger').html('<div class="form-msg icon-spin">' + QuickApps.__t('Saving...') + '</div>');
    $.ajax({
        type: 'POST',
        data: $('#ImageAdminEditForm').serialize(),
        url: QuickApps.settings.base_url + 'admin/quick_slide/images/edit/',
        success: function(data) {
            id = $('#ImageId').val();
            elem = $('#image_' + id);
            meta = $('#counter_' + id);

            if ($('#ImagePreview').val() == 'exclude') {
                $('span.is-preview').each(function () {
                    if ($(this).prev('div').hasClass('counter-off')) {
                        $(this).html('Inactive');
                    }

                    $(this).removeClass('is-preview');
                });

                $(elem).children('span').addClass('is-preview');
                if ($('#counter_' + id).hasClass('counter')) {
                    toggle_image(id);
                }

                $('#counter_' + id + ' .is-preview-subtitle').html(QuickApps.__t('(Album preview)'));
            } else if ($('#ImagePreview').val() == 'include') {
                $('span.is-preview').each(function () {
                    if ($(this).prev('div').hasClass('counter-off')) {
                        $(this).html(QuickApps.__t('Inactive'));
                    }

                    $(this).removeClass('is-preview');
                });

                if ($('#counter_' + id).hasClass('counter-off')) {
                    toggle_image(id);
                }

                $(elem).children('span').addClass('is-preview');
                $('#counter_' + id + ' .is-preview-subtitle').html(QuickApps.__t('(Album preview)'));
            } else {
                e = $('#counter_' + id).children();

                if ($(e).hasClass('is-preview')) {
                    $(e).removeClass('is-preview');

                    if ($(e).prev('div').hasClass('counter-off')) {
                        $(e).html(QuickApps.__t('Inactive'));
                    }
                }

                $('#counter_' + id + ' .is-preview-subtitle').html('&nbsp;');
            }

            form_message('#image-messenger', '<span class="check">' + QuickApps.__t('Image Updated!') + '</span>');
            images_counters();
        }
    });

    return false;
}

function form_message(selector, msg, autoHide) {
    var autoHide = autoHide == null ? true : autoHide;

    $(selector).hide();
    $(selector).html(msg);
    $(selector).fadeIn();

    if (autoHide) {
        $(selector).delay(2000).fadeOut(400);
    }
}

function scaleIt(v) {
    var scalePhotos = $('.scale-image');
    sacaledSize = v;
    floorSize = 0.9993;
    ceilingSize = 1;
    v = floorSize + (v * (ceilingSize - floorSize));

    for (i = 0; i < scalePhotos.length; i++) {
        scalePhotos[i].style.width = (v * 176) + "px";
        scalePhotos[i].style.height = (v * 132) + "px";
        scalePhotos[i].parentNode.parentNode.style.width = (v * 176) + "px";
    }
}

/**
 * Multiple Selection-> Mass Actions
 *
 */
var ctlActive = false;
var multiMode = false;

$(document).bind('keydown', detectKeys);
$(document).bind('keyup', detectKeysUp);

function detectKeys(e) {
    var key = e.which || e.keyCode;

    if (key == 27) {
        clear_selection();
    } else if (key == 17) {
        ctlActive = true;
    }
}

function detectKeysUp(e) {
    var key = e.which || e.keyCode;

    if (key == 17) {
        ctlActive = false;
    }
}

function image_select(id) {
    if (current_image_edit == 0) {
        if (ctlActive) {
            if ($('#multi-select').is(':hidden')) {
                $('#multi-select').fadeIn();
            }

            $('#images-grid').sortable('destroy');

            if (!multiMode) {
                multiMode = true;

                $('div.editBttns').each(function () {
                    $(this).hide();
                });
            }

            $('#img_' + id).toggleClass('block-selected');
        }

        if (multiMode) {
            if ($('li.block-selected').length == 0) {
                clear_selection();
            } else {
                var len = $('li.block-selected').length;
                $('#multi-count').html(len);
            }
        }
    }
}

function clear_selection() {
    if (multiMode) {
        multiMode = false;
        $('#multi-select').fadeOut();
        $('div.select').each(function () { $(this).removeClass('select'); });
        $('div.editBttns').each(function () { $(this).show(); });

        images_sortable();
    }
}

function mass_deactivate(action) {
    selected = $('div.select');
    var actionStr = (action == 1) ? QuickApps.__t('activate') : QuickApps.__t('desactivate');

    if (confirm(QuickApps.__t('You are about to %s %d pieces of content. Are you sure you want to do that?', actionStr, selected.length))) {
        mass_deactivate_exe(action);
    }
}

function mass_deactivate_exe(action) {
    selected = $('div.select');
    var actionStr = (action == 1) ? QuickApps.__t('Activating') : QuickApps.__t('Desactivating');
    Messaging.hello(actionStr + QuickApps.__t(' content...'), 1, false);
    var id_arr = new Array();

    selected.each(function () {
        var id = $(this).attr('id').split('_')[1];
        toggle_image_exe(id, action);
        id_arr.push(id);
    });
    var ids = id_arr.join(',');
    var param = 'data[Image][active]=' + action;

    var myAjax = new Ajax.Request(QuickApps.settings.base_url + 'quick_slide/images/activate/' + ids, {
        method: 'post',
        parameters: param,
        onSuccess: function () {
            Messaging.hello(actionStr + QuickApps.__t(' content...done'), 2, false);
            window.setTimeout("Messaging.kill('')", 2000);
            clear_selection();
        }
    });
}

function mass_delete() {
    selected = $('div.select');
    Messaging.confirm(sprintf(QuickApps.__t('You are about to delete %d pieces of content. Are you sure you want to do that?'), selected.length), 'mass_delete_exe()');
}

function mass_delete_exe() {
    var id_arr = new Array();
    $('div.select').each(function () {
        id_arr.push($(this).attr('id').split('_')[1]);
    });
    var ids = id_arr.join(',');

    $.ajax({
        type: 'GET',
        data: $('#images-grid').sortable('serialize'),
        url: QuickApps.settings.base_url + 'admin/quick_slide/images/delete/' + ids,
        success: function(data) {
            id_arr.each(function (id) {
                $('#image_' + id).hide();
                toggle_image_exe(id, false);
            });
            images_counters();
            clear_selection();
        }
    });
}

var dropArgs = new Array;

function images_droppables() {
    var drops = $('div.video');
    if (drops.length > 0) {
        drops.each(function () {
            $(this).droppable({
                activeClass: 'image-drop-highlight',
                drop: function(event, ui) {
                    dropArgs = [];
                    dropArgs = [$(this), ui.draggable];
                    var c = confirm(QuickApps.__t("Do you want to use this image as this video's preview image?"));

                    if (c) {
                        finish_drop();
                    }
                }
            });           
        });
    }
}

function finish_drop() {
    var video = dropArgs[0].attr('id').split("_")[1]; // video
    var image = dropArgs[1].attr('id').split("_")[1]; // image

    $.ajax({
        type: 'GET',
        url: QuickApps.settings.base_url + 'admin/quick_slide/images/video_thumb/' + video + '/' + image,
        success: function(data) {
            delete_image_exe(image);
            $('#drop_' + video).attr('src', $('#drop_' + image).attr('src'));
        }
    });
}

function rotate_img(id, r) {
    if (current_image_edit == 0) {
        if (id == current_image_edit) {
            $('#the_img').fadeOut();
        }

        $.ajax({
            type: 'GET',
            url: QuickApps.settings.base_url + 'admin/quick_slide/images/rotate/' + id + '/' + r,
            success: function(data) {
                el = $('#image_' + id + ' img')[0];
                var src = $(el).attr('src');
                src += '/&nc=' + parseInt(Math.random() * 1000);
                $(el).attr('src', src);
            }
        });
    }
}

function delete_audio() {
    if (isNaN($('#AlbumAudioFile').val())) {
        c = confirm(QuickApps.__t('Do you want to delete this audio file from server ?'));

        if (c) {
            delete_audio_exe();
        }
    }
}

function delete_audio_exe() {
    form_message('#delete-audio-messenger', QuickApps.__t('Deleting audio file...'), false);

    $.ajax({
        type: 'POST',
        data: 'data[Album][id]=' + $('#AlbumId').val() + '&data[Audio][name]=' + $('#AlbumAudioFile').val(),
        url: QuickApps.settings.base_url + 'admin/quick_slide/audio/delete/',
        success: function(data) {
            form_message('#delete-audio-messenger', QuickApps.__t('Deleting audio file...done'));
            $("#AlbumAudioFile").find("option[value='" + $('#AlbumAudioFile').val() + "']").remove(); 
        }
    });
}

function toggleAnchor() {
    if ($('#anchor').is(':hidden')) {
        $('#anchor').fadeIn();
        setAnchor();
    } else {
        $('#anchor').fadeOut();
    }
}

function setAnchor() {
    var elem = $('#anchor');
    var w = parseFloat($('#img_edit').css('width'));
    var h = parseFloat($('#img_edit').css('height'));

    if (w > h) {
        h += 4;
    }

    $('#img_wrapper').css({width: w + 'px', height: h + 'px'});

    elem.css({
        left: (w * (anc_x / 100)) - 25 + 'px',
        top: (h * (anc_y / 100)) - 25 + 'px'
    });

    $(elem).fadeIn(100, function () {
        $('#anchor').draggable({
            containment: 'parent',
            stop: function(event, ui) {
                $('#drop_' + current_image_edit).attr('src', $('#loading-gif').attr('href')); // loading ico - refresh thumbnail

                var left = Math.round(((parseFloat($(elem).css('left').split('px')[0]) + 25) / w) * 100);
                var top = Math.round(((parseFloat($(elem).css('top').split('px')[0]) + 25) / h) * 100);
                anc_x = left;
                anc_y = top;
                var params = 'data[x]=' + left + '&data[y]=' + top;

                $.ajax({
                    type: 'POST',
                    data: params,
                    url: QuickApps.settings.base_url + 'admin/quick_slide/images/anchor/' + current_image_edit,
                    success: function(data) {
                        $('#drop_' + current_image_edit).attr('src', data); // refresh thumbnail
                    }
                });
            }
        });
    });
}

function images_scale_slider() {
    $('#slider').slider({
            slide: function(event, ui) {
                scaleIt($(this).slider('value'));
            },
            max: 350,
            min: 0
        }
    );
}

// TODO:
function preview_embed_code() {
    
}

function generate_embed_code() {
    var type = QuickApps.settings.url.match(/\/albums\//gi) ? 'album' : 'gallery'; 
    var id = type == 'album' ? $('#AlbumId').val() : $('#GalleryId').val();
    var code = '';

    code += '[quick_slide ';
        code += 'id=' + type + '-' + id +  '\n';

        if (!isNaN($('#EmbedWidth').val()) && !isNaN($('#EmbedHeight').val())) {
            code += 'width=' + $('#EmbedWidth').val() +  '\n';
            code += 'height=' + $('#EmbedHeight').val() + '\n';
        }

        code += 'theme=' + $('#EmbedTheme').val() + '\n';
        code += 'content_scale="' + $('#EmbedContentScale').val() + '"\n';
        code += 'transition_style="' + $('#EmbedTransitionStyle').val() + '"\n';
        code += 'feedback_preloader_appearance="' + $('#EmbedFeedbackPreloaderAppearance').val() + '"\n';
        
        if (!$('#EmbedDisplayMode').is(':checked')) {
            code += 'display_mode="Manual"' + '\n';
        }

        if ($('#EmbedStartup').is(':checked')) {
            code += 'startup="Open Gallery"' + '\n';
        }

        if ($('#EmbedPanZoom').is(':checked')) {
            code += 'pan_zoom="On"' + '\n';
        }

        if (!$('#EmbedVideoAutoStart').is(':checked')) {
            code += 'video_auto_start="Off"' + '\n';
        }

        if ($('#EmbedNavAppearance').is(':checked')) {
            code += 'nav_appearance="Visible on Rollover"' + '\n';
        }

        if (!$('#EmbedNavLinkAppearance').is(':checked')) {
            code += 'nav_link_appearance="Numbers"' + '\n';
        }

    code += ' /]';

    $('#embed-code-render .code-content').html(code);
    $('#embed-code-render').dialog({
        minWidth:480,
        maxWidth:480,
        width:480,
        draggable: true,
        modal:true
    });

    save_embed_cookie();
}

function images_sortable() {
    $('#images-grid').sortable({
        update: function(event, ui) {
            $.ajax({
                type: 'POST',
                data: $('#images-grid').sortable('serialize'),
                url: QuickApps.settings.base_url + 'admin/quick_slide/images/sort',
                success: function(data) { images_counters(); }
            });
        },
        handle: 'div',
        cursor: 'crosshair'
    });
    $('#images-grid').disableSelection();
}