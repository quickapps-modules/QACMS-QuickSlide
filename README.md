Quick Slide
===========

[![QuickApps CMS](https://raw.github.com/QuickAppsCMS/QuickApps-CMS-Docs/1.x/img/logo.png)](http://www.quickappscms.org)

Rich Media player for presenting photos and videos, powered by [SlideShowPro Player](http://slideshowpro.net/).

***

Installation
------------

Once you've purchased and downloaded [SlideShowPro](http://slideshowpro.net/), you're just about ready to go.
Extract the downloaded zip package (SlideShowPro_for_Flash_{xxx}.zip) somewhere to your computer and you'll see a folder
inside called "standalone". This folder contains a few files which you'll need to upload via FTP. You'll need to upload the file
slideshowpro.swf to QuickSlide folder `webroot/quick_slide`

### Usage

Once you've installed Quick Slide a new Content Type will be added on the `Create Content` section:

##### Showing Slideshows

To insert a SWF player you must use the [quick_slide] hooktag wherever you want to insert the slider.
QuickSlide allows you to insert many sliders as you want.

To create a fully customized player you can use the Embed code generator to get the hooktag code for each album/gallery. 
Otherwise, you can quickly insert basic player using the following parametters in your hooktag call:

- id: ID of your album or gallery prefixed by `album-` or `gallery-`
- theme: Theme name to use. (card, chrome, chromeless, default, glass, ice, mist, rain, salt, single-video, smooth, techno)
- width: Width of the SWF player. (default 480)
- height: Height of the SWF player. (default 350)


###### Example

The following hooktag code will render a SWF Player for the album with an ID equal to 1.
Player's dimmensions are 600x400 and will use the chrome theme.


    [quick_slide
        id=album-1
        theme=chrome
        width=600
        height=400
	/]


***


### User Guide

##### Albums

Albums are containers for slideshow photos and videos. They are the means through which slideshow content is retrieved,
metadata (like titles, captions, tags and hyperlinks) is assigned, and they can be bundled together as galleries.
Quick Slide allows you to create as many albums as you want.


##### Gallery

A gallery is a collection of albums. It serves one purpose: to group albums together as a single collection for a slideshow.
You may add or remove any album from a gallery.