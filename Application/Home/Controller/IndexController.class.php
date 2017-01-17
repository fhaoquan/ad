<?php
namespace Home\Controller;
use Home\Controller\BaseController;
class IndexController extends BaseController {
    public function index(){
        $Show = M("show");
        //查询推荐节目信息
        $lists = $Show->select();

        $this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px } a,a:hover{color:blue;}</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p><br/>版本 V{$Think.version}</div><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_55e75dfae343f5a1"></thinkad><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');
    }

    public function recommend(){
        $Show = M("show");
        //查询推荐节目信息
        $lists = $Show->where(array("recommended"=>1))->order("hot desc")->select();

    }

    public function feedback(){
        if(IS_POST){
            $FeedBack = D('feedback');
            //获取参数
            $data = $FeedBack->create();
            //参数检查

            //保存数据
            $res = $FeedBack->add($data);
            //返回结果
            if($res){
                $this->ajaxReturn(array("error"=>false,"data"=>$res));
            }else{
                $this->ajaxReturn(array("error"=>true,"data"=>"反馈失败"));
            }
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
}