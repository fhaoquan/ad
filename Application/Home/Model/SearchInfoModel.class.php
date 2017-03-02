<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2017/2/24
 * Time: 下午1:37
 */

namespace Home\Model;
use Think\Model\ViewModel;

class SearchInfoModel extends ViewModel{

    public $viewFields = array(
        'SearchUser' => array('uid','time'),
        'SearchContent' => array('content', 'count', 'hot', '_on'=>'SearchUser.scid = SearchContent.id'),
    );

}