<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/6
 * Time: 下午3:19
 */

namespace Home\Controller;
use Home\Controller\BaseController;
class SearchController extends BaseController{
    public function index(){
        if(IS_POST){
            $Show = D('Show');
            //接收参数
            $page = I('page') ? I('page') : 1;
            $perpage = I('perpage') ? I('perpage') : 2;
            $keyword = str_replace(' ','',I('keyword'));

            //参数检查
            if($keyword == '') $this->ajaxReturn(array('error'=>true,'data'=>'关键字不能为空'));
            //分页查询节目信息列表
            $list = $Show->where(array('name'=>array('like','%'.$keyword.'%')))->limit($perpage)->page($page)->select();
            //返回数据
            $this->ajaxReturn(array('error'=>false,'data'=>$list));
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
}