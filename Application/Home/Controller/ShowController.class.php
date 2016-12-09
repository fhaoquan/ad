<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/6
 * Time: 下午3:19
 */

namespace Home\Controller;
use Home\Controller\BaseController;
class ShowController extends BaseController{
    public function index(){

    }

    public function getAllShows(){
        $Show = D('Show');
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 2;

        //参数检查

        //分页查询节目信息列表
        $list = $Show->limit($perpage)->page($page)->select();
        //返回数据
        $this->ajaxReturn(array('error'=>false,'data'=>$list));
    }

    public function getShows(){
        $Show = D('Show');
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 2;
        $filter = $Show->create();

        //参数检查
        foreach ($filter as $key => $value){
            if(empty($filter[$key])){
                unset($filter[$key]);
            }
        }
        //分页查询节目信息列表
        $list = $Show->where($filter)->limit($perpage)->page($page)->select();
        //返回数据
        $this->ajaxReturn(array('error'=>false,'data'=>$list));
    }

}