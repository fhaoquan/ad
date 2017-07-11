<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class ContentController extends BaseController {
    public function index(){
        $model=I('model');

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

        $count=D($model)->where($where)->count();
        $Page= new \Think\Page($count,15);
        $limit=$Page->firstRow .',' .$Page->listRows;
        $this->list=D('Content')->table(C('DB_PREFIX').$model)->relation(true)->where($where)->order('ctime desc')->limit($limit)->select();

        $this->page=$Page->show();
        $this->model=D('model')->where(array('model'=>$model))->find();
        $this->fields=D('fields')->where(array('model'=>$model,'ls'=>0))->order('orderid asc')->select();
        $this->types=D('type')->where(array('model'=>$model))->select();
        $this->select=M('fields')->where(array('model'=>$model))->order('orderid asc')->select();
        $this->display(); 
    }
    public function add(){
		$model=I('model');
		$typeid=I('typeid');
		if(IS_POST){
			if($typeid==0) $this->error('分类不能为空！');
			$db=D($model);
			$data=$db->create();
			$data['ctime']=time();
			$data['uptime']=time();
			$id=$db->add($data);

			$this->success('添加完成',U('index',array('model'=>$model)));
		}else{
			$this->model=D('model')->where(array('model'=>$model))->find();
			$this->fields=D('fields')->where(array('model'=>$model))->order('orderid asc')->select();
			$this->types=D('type')->where(array('model'=>$model))->select();
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
            $type=D('type')->find($typeid);

			$db->save($data);
			$this->success('修改完成',U('index',array('model'=>$model)));
		}else{
			$this->model=D('model')->where(array('model'=>$model))->find();
			$this->fields=D('fields')->where(array('model'=>$model))->order('orderid asc')->select();
			$this->types=D('type')->where(array('model'=>$model))->select();
			$this->body=D($model)->find($id);
			$this->display();
		}
    }
    public function delete(){
		$model=I('model');
        $id=I('id');
        D($model)->where(array('id'=>$id))->delete();
        //D('discuss')->where(array('model'=>$model,'pid'=>$id))->delete();
        $this->redirect('index',array('model'=>$model,'p'=>I('p')));
    }	
}
?>