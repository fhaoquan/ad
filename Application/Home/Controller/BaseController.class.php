<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/6
 * Time: 下午3:26
 */

namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller{
    public function _initialize()
    {
        $site = M('site')->find();
        $this->site = $site;
    }
}