<?php
class Link extends QuickSlideAppModel {
    public $useTable = 'qs_links';
    public $order = 'ABS(Link.display) ASC';
}