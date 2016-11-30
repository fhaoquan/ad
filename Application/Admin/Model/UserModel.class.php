<?php
namespace Admin\Model;
use Think\Model\RelationModel;
class UserModel extends RelationModel {
	public $_link=array(
		'user_group'=>array(
			'mapping_type'=>self::BELONGS_TO,
			'foreign_key'=>'gid',
			'class_name'=>'user_group',
			'mapping_name'=>'group'
		),
	);
}
?>