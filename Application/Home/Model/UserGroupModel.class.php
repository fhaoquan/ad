<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/27
 * Time: 下午8:46
 */

namespace Home\Model;
use Think\Model\RelationModel;
class UserGroupModel extends RelationModel{
    public $_link=array(
        'user'=>array(
            'mapping_type'=>self::HAS_ONE,
            'foreign_key'=>'gid',
            'class_name'=>'user',
            'mapping_name'=>'users',
            'mapping_fields'=>'count(id) as counts',
            'as_fields'=>'counts:users',
        ),
    );
}