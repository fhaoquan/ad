<?php
/*
    字段表xxx_fields
    xxx为主表名
*/
namespace Admin\Controller;
use Admin\Controller\BaseController;
    class FieldsController extends BaseController {

        public function index(){
            $table='fields';
            $model = I('model');
            if($model){
                $this->model=$model;
                $this->lists=M($table)->where(array('model'=>$model))->order('orderid asc')->select();
                $this->display();
            }
        }
        public function forders(){
            $table='fields';
            $model=I('model');

            $db=M($table);
            foreach($_POST as $id=>$orders){
                $db->where(array('id'=>$id))->setField('orderid',$orders);
            }
            $this->redirect('index',array('model'=>$model));
        }
        public function delete(){
            $table='fields';
            $model = I('model');

            $thispd=M($table)->find(I('id'));
            if($thispd['sys']==1) $this->error('系统保留字段，不可删除！');
            M($table)->where(array('id'=>I('id')))->delete();
            ////////
            $db1 = M();
            $res = $db1->execute("alter table `".C('DB_PREFIX').$model."` drop column `".$thispd[name]."`;");
            if($res)
                $this->success('已删除！');
            else
                $this->error('删除失败！');
//            $this->redirect('index',array('model'=>$model));
        }
        public function add(){
            $table='fields';
            $model = I('model');

            if(IS_POST){
                if(!I("title")) $this->error('名称不能为空！');
                if(!I("name")) $this->error('标识不能为空！');

//                if (I('ftype') == 5) {
//                    $count = M('fields')->where(array('model' => $model, 'ftype' => I('ftype')))->count();
//                    if ($count != 0) $this->error('该类型只能有一个!');
//                }

                $count=M($table)->where(array('name'=>I('name'), 'model'=>$model))->count();
                if($count>0)
                {
                    $this->error('标识已存在，请重新定义标识!');
                }
                $db=M($table);
                $db->startTrans();
                $data=$db->create();
                $db->add($data);
                //创建表
                $db1 = M();
                switch($data['ftype'])
                {
                    case '1':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` varchar(225);');
                        break;
                    case '2':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` mediumtext;');
                        break;
                    case '3':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` int(10);');
                        break;
                    case '4':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` varchar(225);');
                        break;
                    case '5':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` text;');
                        break;
                    case '6':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` varchar(225);');
                        break;
                    case '7':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` int(1);');
                        break;
                    case '8':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` text;');
                        break;
                    case '9':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` varchar(225);');
                        break;
                    case '10':
                        $res=$db1->execute('alter table `'.C('DB_PREFIX').$model.'` add `'.$data['name'].'` varchar(50);');
                        break;
                    default:
                        break;
                }
                if($res === false){
                    $db->rollback();
                    $this->error('添加失败！');
                }
                else{
                    $db->commit();
                    $this->success('添加成功！');
                }

            }
            else{
                $this->model=$model;
                $this->display();
            }
        }
        public function modify(){
            $table='fields';
            $model = I('model');

            if(IS_POST){
                if(!I("title")) $this->error('名称不能为空！');
                if(!I("name")) $this->error('标识不能为空！');

                $db=M($table);
                $data=$db->create();
                unset($data['name']);
                $db->where(array('id'=>I('id')))->save($data);
                $db1 = M();
                switch($data['ftype'])
                {
                    case '1':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` varchar(225);');
                        break;
                    case '2':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` mediumtext;');
                        break;
                    case '3':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` int(10);');
                        break;
                    case '4':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` varchar(225);');
                        break;
                    case '5':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` text;');
                        break;
                    case '6':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` varchar(225);');
                        break;
                    case '7':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` int(1);');
                        break;
                    case '8':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` text;');
                        break;
                    case '9':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` varchar(225);');
                        break;
                    case '10':
                        $db1->execute('alter table `'.C('DB_PREFIX').$model.'` change  `'.I('name').'` `'.I('name').'` varchar(50);');
                        break;
                    default:
                        break;
                }
                $this->success('修改成功！');
            }
            else{
                $thispd=M($table)->find(I('id'));
                if($thispd[sys]==1) $this->error('系统保留字段，不可修改！');

                $this->model=$model;
                $this->field=$thispd;
                $this->display();
            }

        }
    }