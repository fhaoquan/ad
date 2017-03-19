<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
use Libray\Vendor\UcApi;
Vendor('ThinkphpUcenter.UcApi');
    class UserController extends BaseController {
        public function index(){
            $where=array();
            $select_name=I('select_name');
            $select_val=I('select_val');
            if(I('select')=='select'){
                if(I('vague')=='vague'){
                    $where[$select_name]=array('like', '%'.$select_val.'%');
                }else{
                    $where[$select_name]=$select_val;
                }
                $this->select_name=$select_name;
                $this->select_val=$select_val;
                $this->vague=I('vague');
            }


            $count= M('user')->where($where)->count();
            $Page= new \Think\Page($count,15);
            $limit=$Page->firstRow .',' .$Page->listRows;

            $this->fields=M('fields')->where(array('ls'=>1, 'model'=>'user'))->order('orderid asc')->select();
            $this->select=M('fields')->where(array('model'=>'user'))->order('orderid asc')->select();
            $this->lists=D('User')->relation(true)->limit($limit)->where($where)->select();

            $this->page=$Page->show();
            $this->display();
        }
        public function add(){
            if(IS_POST){
                $username=str_replace(' ','',I('username'));
                $password=I('password');
                $email=str_replace(' ','',I('email'));
                if($username=='') $this->error('用户名不能为空！');
                if($password=='') $this->error('密码不能为空！');
                if($email=='') $this->error('邮箱不能为空！');

//                $reg = UcApi::reg(I('username'), I('password'), I('email'));
//                if($reg <= 0){
//                    $this->error(UcApi::getError());
//                }else{
                    $count=M('user')->where(array('username'=>$username))->count();
                    if($count) $this->error('用户名已存在！');
                    $password=md5($password);
                    $db=M('user');
                    $data=$db->create();
                    $data['username']=$username;
                    $data['password']=$password;
                    $db->add($data);
                    $this->success('添加会员成功！');
//                }

            }else{
                $where['sys'] = array('neq',1);
                $where['model'] = 'user';
                $this->fields=M('fields')->where($where)->order('orderid asc')->select();
                //获取会员组信息
                $this->groups=M('user_group')->select();
                $this->display();
            }

        }
        public function modify(){
            if(IS_POST){
                $username=str_replace(' ','',I('username'));
                $email=str_replace(' ','',I('email'));
                $password=I('password');
                if($username=='') $this->error('用户名不能为空！');
//                if($email=='') $this->error('邮箱不能为空！');

                $where=array();
                $where['username']=array('eq',$username);
                $where['id']=array('neq',I('id'));
                $count=M('user')->where($where)->count();
                if($count) $this->error('用户名已存在！');

                $db=M('user');
                $data=$db->create();
                $ucdata = array();
                $data['username'] = $ucdata['username'] =$username;
                if($password==''){
                    unset($data['password']);
                }
                else{
                    $data['password']=md5($password);
                    $ucdata['password']=$password;
                }
                $ucdata['email'] = $email;
                //修改ucenter用户名密码等
//                $ret = UcApi::edit($ucdata);
//                if(!$ret) $this->error(UcApi::getError());
                //修改本站用户
                $db->where(array('id'=>I('id')))->save($data);
                $this->success('修改会员成功！');
            }else{
                $where['sys'] = array('neq',1);
                $where['model'] = 'user';
                $this->fields=M('fields')->where($where)->order('orderid asc')->select();
                $this->body=M('user')->find(I('id'));
                //获取会员组信息
                $this->groups=M('user_group')->select();
                $this->display();
            }
        }
        public function delete(){
            $id=I('id');
            $ucdelete = false;
            M('user')->where(array('id'=>$id))->delete();
            if($ucdelete && !UcApi::delete($id)){
                $this->error(UcApi::getError());
            }
            $this->success('删除成功！');
        }
    }