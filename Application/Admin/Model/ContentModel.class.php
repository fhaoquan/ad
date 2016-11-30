<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class ContentModel extends RelationModel {
    protected $autoCheckFields =false;
	public $_link=array(
		'type'=>array(
			'mapping_type'=>self::BELONGS_TO,
			'foreign_key'=>'typeid',
			'class_name'=>'type',
			'mapping_name'=>'type'
		),
		'user'=>array(
			'mapping_type'=>self::BELONGS_TO,
			'foreign_key'=>'userid',
			'class_name'=>'user',
			'mapping_name'=>'user'
		),
	);
}
?>