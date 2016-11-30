<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class ContentController extends BaseController {
    public function index(){
        $model=I('model');
        $typeid=I('typeid');
        if($typeid){
            $count= M($model)->where(array('typeid'=>$typeid))->count();
		}else{
			$count= M($model)->count();
		}            
        $Page= new \Think\Page($count,15);
        $limit=$Page->firstRow .',' .$Page->listRows;
        if($typeid){
            $this->list=D('Content')->table(C('DB_PREFIX').$model)->relation(true)->where(array('typeid'=>$typeid))->order('ctime desc')->limit($limit)->select();
            $this->typeid=$typeid;
        }else{
            $this->list=D('Content')->table(C('DB_PREFIX').$model)->relation(true)->order('ctime desc')->limit($limit)->select();
        }
        $this->page=$Page->show();
        $this->model=M('model')->where(array('model'=>$model))->find();
        $this->fields=M('fields')->where(array('model'=>$model,'ls'=>0))->order('orderid asc')->select();
        $this->types=M('type')->where(array('model'=>$model))->select();
        $this->display(); 
    }
    public function add(){
		$model=I('model');
		$typeid=I('typeid');
		if(IS_POST){
			if($typeid==0) $this->error('分类不能为空！');
			$db=M($model);
			$data=$db->create();
			$data['ctime']=time();
			$data['uptime']=time();
			$data['userid']=session(C('USER_AUTH_KEY'));
			$id=$db->add($data);

			$this->success('添加完成',U('index',array('model'=>$model)));
		}else{
			$this->model=M('model')->where(array('model'=>$model))->find();
			$this->fields=M('fields')->where(array('model'=>$model))->order('orderid asc')->select();
			$this->types=M('type')->where(array('model'=>$model))->select();
			$this->typeid=$typeid;
			$this->display();
		}
        
    }
    public function modify(){
        $model=I('model');
		$typeid=I('typeid');		
        $id=I('id');
		if(IS_POST){
			if($typeid==0) $this->error('分类不能为空！');
			$db=D($model);
			$data=$db->create();
			$data['uptime']=time();
			$type=M('type')->find($typeid);

			$db->save($data);
			$this->success('修改完成',U('index',array('model'=>$model)));
		}else{
			$this->model=M('model')->where(array('model'=>$model))->find();
			$this->fields=M('fields')->where(array('model'=>$model))->order('orderid asc')->select();
			$this->types=M('type')->where(array('model'=>$model))->select();
			$this->body=M($model)->find($id);
			$this->display();
		}
    }
    public function delete(){
		$model=I('model');
        $id=I('id');
        M($model)->where(array('id'=>$id))->delete();
        //M('discuss')->where(array('model'=>$model,'pid'=>$id))->delete();
        $this->redirect('index',array('model'=>$model,'p'=>I('p')));
    }	
}
?>