<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2017/2/24
 * Time: 下午1:37
 */

namespace Home\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{
    public $_link = array(
        'search'=> array(
            'mapping_type'=>self::MANY_TO_MANY,
            'class_name'=>'search',
            'mapping_name'=>'search_contents',
            'foreign_key'=>'uid',
            'relation_foreign_key'=>'scid',
            'relation_table'=>'ad_search_content',
            'mapping_fields'=>'content',
            'as_fields'=>'search_contents',
        ),
        'user_group'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'foreign_key'=>'gid',
            'class_name'=>'user_group',
            'mapping_name'=>'user_group',
            'as_fields' => 'level'
        ),
    );

}