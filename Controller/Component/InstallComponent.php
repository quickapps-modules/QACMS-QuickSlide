<?php
class InstallComponent extends Component {
    public $Installer;

    public function beforeInstall() {
        $query = "
            CREATE TABLE IF NOT EXISTS `#__qs_albums` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
              `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `aTn` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
              `audio_file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `audio_caption` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `images_count` int(11) NOT NULL DEFAULT '0',
              `title_template` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `link_template` text COLLATE utf8_unicode_ci NOT NULL,
              `caption_template` text COLLATE utf8_unicode_ci NOT NULL,
              `modified` int(11) NOT NULL,
              `created` int(11) NOT NULL,
              `modified_by` int(11) NOT NULL,
              `created_by` int(11) NOT NULL,
              `status` tinyint(1) NOT NULL DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;     
            CREATE TABLE IF NOT EXISTS `#__qs_galleries` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
              `description` text COLLATE utf8_unicode_ci,
              `modified` int(11) DEFAULT NULL,
              `created` int(11) DEFAULT NULL,
              `main` tinyint(1) DEFAULT '0',
              `updated_by` int(11) DEFAULT NULL,
              `created_by` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
            CREATE TABLE IF NOT EXISTS `#__qs_images` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `aid` int(11) NOT NULL,
              `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `src` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
              `caption` text COLLATE utf8_unicode_ci NOT NULL,
              `author` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
              `link` text COLLATE utf8_unicode_ci NOT NULL,
              `seq` int(4) DEFAULT '999',
              `pause` int(4) NOT NULL DEFAULT '0',
              `target` tinyint(1) NOT NULL DEFAULT '0',
              `anchor` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
              `filesize` int(11) DEFAULT NULL,
              `tags` longtext COLLATE utf8_unicode_ci NOT NULL,
              `modified` int(11) NOT NULL,
              `created` int(11) NOT NULL,
              `modified_by` int(11) NOT NULL,
              `created_by` int(11) NOT NULL,
              `status` tinyint(4) NOT NULL DEFAULT '1',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
            CREATE TABLE IF NOT EXISTS `#__qs_links` (
              `id` int(255) NOT NULL AUTO_INCREMENT,
              `gid` int(11) DEFAULT NULL,
              `aid` int(11) DEFAULT NULL,
              `display` int(11) DEFAULT '800',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
        ";

        return $this->Installer->sql($query);    
    }

    public function afterInstall() {
        $qs_folder = ROOT . DS . 'webroot' . DS . 'files' . DS . 'quick_slide' . DS;
        $Folder = new Folder($qs_folder, true);
        $Folder = new Folder($qs_folder . DS . 'album-audio', true);

        return true;
    }

    public function beforeUninstall() {
        $this->Installer->sql('DROP TABLE #__qs_galleries;');
        $this->Installer->sql('DROP TABLE #__qs_albums;');
        $this->Installer->sql('DROP TABLE #__qs_images;');
        $this->Installer->sql('DROP TABLE #__qs_links;');

        return true;
    }

    public function afterUninstall() {
        return true;
    }
}