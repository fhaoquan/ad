<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2017/2/24
 * Time: 下午1:37
 */

namespace Home\Model;
use Think\Model\RelationModel;

class SearchContentModel extends RelationModel{
    public $_link = array(
        'search'=> array(
            'mapping_type'=>self::HAS_MANY,
            'class_name'=>'search',
            'mapping_name'=>'search',
            'foreign_key'=>'scid',
            'mapping_key'=>'scid',
            'mapping_order'=>'time desc',
        ),
    );

}