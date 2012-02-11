<?php
class Album extends QuickSlideAppModel {
    public $useTable = 'qs_albums';

    public $hasMany = array(
        'Image' => array(
            'className' => 'QuickSlide.Image',
            'foreignKey' => 'aid',
            'order' => array('ABS(Image.seq) ASC'),
            'dependent' => true
       )
    );

    public $belongsTo = array(
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'fields' => array('id', 'name')
       ),
        'ModifiedBy' => array(
            'className' => 'User',
            'foreignKey' => 'updated_by',
            'fields' => array('id', 'name')
       )
    );


    public $hasAndBelongsToMany = array(
        'Gallery' => array(
            'className' => 'QuickSlide.Gallery',
            'joinTable' => 'qs_links',
            'foreignKey' => 'aid',
            'associationForeignKey'  => 'gid',
            'fields' => array('id', 'name')
       )
    );

    public $validate = array(
        'name' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Invalid album name'
       )

    );

    public function afterDelete() {
        $folder = new Folder;
        @$folder->delete(QS_FOLDER . "album-{$this->id}");
    }
}