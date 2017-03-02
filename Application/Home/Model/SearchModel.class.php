<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2017/2/24
 * Time: 下午1:37
 */

namespace Home\Model;
use Think\Model\RelationModel;

class SearchModel extends RelationModel{
        public $_link = array(
        'search_content'=> array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'search_content',
            'mapping_name'=>'content',
            'foreign_key'=>'scid',
            'mapping_fields'=>'content',
            'as_fields'=>'content',
        ),
    );

}