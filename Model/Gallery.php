<?php
class Gallery extends QuickSlideAppModel {
	public $useTable = 'qs_galleries';
	public $order = array('Gallery.created' => 'DESC');

	public $hasAndBelongsToMany = array(
		'Album' => array(
			'className' => 'QuickSlide.Album',
			'joinTable' => 'ss_links',
			'foreignKey' => 'gid',
			'associationForeignKey'  => 'aid',
			'with' => 'QuickSlide.Link',
			'fields' => array('id', 'name', 'aTn', 'created', 'modified'),
			'order' => 'ABS(Link.display) ASC'
		)
	);

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false,
			'on' => 'create', // or: 'update'
			'message' => 'Invalid gallery name'
		)
	);
}