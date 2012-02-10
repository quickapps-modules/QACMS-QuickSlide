<h3>Installation</h3>
<dl>
    <dt>SlideShowPro Player</dt>
    <dd>
        <p>
            Once you've purchased and downloaded SlideShowPro, you're just about ready to go.
            Extract the downloaded zip package (SlideShowPro_for_Flash_{xxx}.zip) somewhere to your computer and you'll see a folder inside called "standalone".
            This folder contains a few files which you'll need to upload via FTP.
            You'll need to upload the file <b>slideshowpro.swf</b> to <em>QuickSlide</em> folder <em><?php echo str_replace('quick_slide', '<b>quick_slide</b>', realpath(QS_FOLDER)); ?><em>
        </p>
    </dd>
</dl>

<h3>Usage</h3>
<dl>
    <dt>Adding Album & Galleries</dt>
    <dd>
        Once you've installed <em>Quick Slide</em> a new <em>Content Type</em> will be added on the <?php echo $this->Html->link('Create Content', '/admin/node/contents/create'); ?> section: <b><?php echo __d('quick_slide', 'Quick Slide Content'); ?></b>
    </dd>

    <dt>Showing Slideshows</dt>
    <dd>
        <p>
            To insert a SWF player you must use the <b>[quick_slide]</b> hooktag wherever you want to insert the slider.
            <em>QuickSlide</em> allows you to insert many sliders as you want.
        </p>
        <p>
            To create a fully customized player you can use the <em>Embed</em> code generator to get the hooktag code for each album/gallery. <br />
            Otherwise, you can quickly insert basic player using the following parametters in your hooktag call:
        </p>

        <p>
            <ul>
                <li><b>id</b>: ID of your album or gallery prefixed by <b>album-</b> or <b>gallery-</b></li>
                <li><b>theme</b>: Theme name to use. (<?php echo implode(', ', array_keys(Configure::read('qs_themes'))); ?>)</li>
                <li><b>width</b>: Width of the SWF player. (default 480)</li>
                <li><b>height</b>: Height of the SWF player. (default 350)</li>
            </ul>
        </p>

        <p>
            <h4>Example</h4>
            The following hooktag code will render a SWF Player for the album with an <b>ID equal to 1</b>. Player's dimmensions are <b>600x400</b> and will use the <b>chrome</b> theme.
            
        </p>
        <pre>
            [quick_slide
                id=album-1
                theme=chrome
                width=600
                height=400
            ]
        </pre>
    </dd>
</dl>

<h3>User Guide</h3>
<dl>
    <dt>Albums</dt>
    <dd>
        Albums are containers for slideshow photos and videos.
        They are the means through which slideshow content is retrieved, metadata (like titles, captions, tags and hyperlinks) is assigned, and they can be bundled together as galleries.
        <em>Quick Slide</em> allows you to create as many albums as you want.
    </dd>

    <dt>Gallery</dt>
    <dd>
        A gallery is a collection of albums.
        It serves one purpose: to group albums together as a single collection for a slideshow.
        You may add or remove any album from a gallery.
    </dd>
</dl>

<?php echo $this->Html->link(__d('quick_slide', 'Manage Album/Galleries'), '/admin/quick_slide'); ?>