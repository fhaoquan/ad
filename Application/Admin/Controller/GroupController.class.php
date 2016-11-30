<?php
/**
 * Created by PhpStorm.
 * User: 亚东
 * Date: 2016/3/11
 * Time: 9:55
 */

namespace Admin\Controller;
use Admin\Controller\BaseController;

class GroupController extends BaseController{

    public function index(){
        $this->groups = M('user_group')->select();
        $this->fields = M('fields')->where(array('model'=>'user_group'))->select();
        $this->display();
    }

    public function add(){

        if(IS_POST){
            if (I("title") == '') $this->error('名称不能为空！');
            if (I('level') == '') $this->error('等级不能为空！');

            $count = M('user_group')->where(array('title' => I('title')))->count();
            if ($count != 0) {
                $this->error('用户组已存在，请重新定义!');
            }

            $db=M('user_group');
            $data = $db->create();
            $data['level'] = is_numeric($data['level']) ? intval($data['level']) : 0;
            $db->add($data);
            $this->success('创建完成！');

        }else{
            $this->fields = M('fields')->where(array('model'=>'user_group'))->select();
            $this->display();
        }
    }

    public function modify(){
        if(IS_POST){
            if (I("title") == '') $this->error('名称不能为空！');
            if (I('level') == '') $this->error('等级不能为空！');

            $where=array();
            $where['title']=array('eq',I("title"));
            $where['id']=array('neq',I('id'));
            $count = M('user_group')->where($where)->count();
            if ($count != 0) {
                $this->error('用户组已存在，请重新定义!');
            }

            $db=M('user_group');
            $data=$db->create();
            $data['level'] = is_numeric($data['level']) ? intval($data['level']) : 0;
            $db->where(array('id'=>I('id')))->save($data);
            $this->success('修改会员组成功！');
        }else{
            $where['sys'] = array('neq',1);
            $where['model'] = 'user_group';
            $this->fields=M('fields')->where($where)->order('orderid asc')->select();
            $this->body=M('user_group')->find(I('id'));
            $this->display();
        }
    }

    public function delete(){
        $id=I('id');

        $count = M('user')->where(array('gid'=>$id))->count();
        if($count>0){
            $this->error('请先删除用户组下的用户！');
        }
        $db = M('user_group');
        $data=$db->find(I('id'));
        if($data['sys']==1) $this->error('系统用户组，不可删除！');
        $db->where(array('id'=>$id))->delete();
        $this->success('删除成功！');
    }

}