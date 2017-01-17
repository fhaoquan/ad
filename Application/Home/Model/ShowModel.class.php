<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/27
 * Time: 下午5:56
 */

namespace Home\Model;
use Think\Model\RelationModel;
class ShowModel extends RelationModel{
    public $_link=array(
        'cast'=>array(
            'mapping_type'=>self::MANY_TO_MANY,
            'class_name'=>'cast',
            'mapping_name'=>'casts',
            'foreign_key'=>'sid',
            'relation_foreign_key'=>'cid',
            'relation_table'=>'ad_sc',
            'mapping_fields'=>'name',
            'as_fields'=>'name:casts',
        ),
        'director'=>array(
            'mapping_type'=>self::MANY_TO_MANY,
            'class_name'=>'director',
            'mapping_name'=>'directors',
            'foreign_key'=>'sid',
            'relation_foreign_key'=>'did',
            'relation_table'=>'ad_sd',
            'mapping_fields'=>'name',
            'as_fields'=>'name',
        ),
    );
}