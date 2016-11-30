<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class TypeController extends BaseController {
	public function index(){
		$types = M('type')->select();
		$this->types = node_merge($types);
		$this->display();
	}
	public function orders(){
		$db=M('type');
		foreach($_POST as $id=>$orders){
			$db->where(array('id'=>$id))->setField('orderid',$orders);
		}
		$this->redirect('index');
	}
	public function add(){
		if(IS_POST){
            $db = M('type');
            $data = $db->create();
            if(!$data['title']) $this->error('名称不能为空！');
            if(!$data['model']) $this->error('模型不能为空！');
            if($data['urlname']){
                $res = $db->where(array('urlname'=>$data['urlname']))->count();
                if($res!=0) $this->error('URL别名 字段有重复!');
            }
            $id=$db->add($data);
            $this->success('添加成功!');
		}else{
			$types=M('type')->field('id,pid,title')->select();
			$this->models=M('model')->field('model,title')->select();
			$this->types=node_merge($types);
			$this->display();
		}
	}
	public function modify(){
		if(IS_POST){
            $db = D('type');
            $data = $db->create();
            if(!$data['title']) $this->error('名称不能为空！');
            if(!$data['model']) $this->error('模型不能为空！');
            if($data['urlname']){
                $res = $db->where(array('urlname'=>$data['urlname']))->count();
                if($res!=0) $this->error('URL别名 字段有重复!');
            }
            $db->where(array('id'=>I('id')))->save($data);
            $this->success('修改成功!');
		}else{
			$type = M('type')->find(I('id'));
			$models = M('model')->field('model,title')->select();
			$types = M('type')->field('id,pid,title')->select();
			$this->type = $type;			
			$this->models = $models;			
			$this->types = node_merge($types);
			$this->display();
		}
	}
        public function delete(){
            $db=M('type');
            $res=$db->where(array('pid'=>I('id')))->count();
            if($res) $this->error('请先删除下级类目！');
            $db->where(array('id'=>I('id')))->delete();
            $this->success('删除成功');
        }
	
}
?>