<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

class ModelController extends BaseController
{
    public function index()
    {
        $model = M('model')->order('orderid asc')->select();
        $this->model = $model;
        $this->display();
    }

    public function orders()
    {
        $db = M('model');
        foreach ($_POST as $model => $orderid) {
            $db->where(array('model' => $model))->setField('orderid', $orderid);
        }
        $this->redirect('index');
    }

    public function add()
    {
        if (IS_POST) {
            if (!I("title")) $this->error('名称不能为空！');
            if (!I("model")) $this->error('标识不能为空！');
            $models = array('type', 'model', 'session', 'site', 'user', 'fields');
            if(in_array(I('model'), $models)){
                $this->error('系统保留字段,请重新定义标识！');
            }
            $count = M('model')->where(array('model' => I('model')))->count();
            if ($count != 0) {
                $this->error('标识已存在，请重新定义标识!');
            }
            $table_model = C('DB_PREFIX') . I('model');

            $Model = M();
            $Model->execute("CREATE TABLE `" . $table_model . "` (`id` int(11) NOT NULL AUTO_INCREMENT,`typeid` int(11) NOT NULL,`title` varchar(100) NOT NULL DEFAULT '',`keywords` varchar(100) NULL DEFAULT '',`ctime` int(10) NULL,`uptime` int(10) NULL,`orderid` int(4) NULL DEFAULT  '0', PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            $db = M('model');
            $data = $db->create();
            $data['titlename'] = I('titlename') ? I('titlename') : '标题';
            $res = $db->add($data);
            if ($res) {
                $this->success('创建完成！');
            } else {
                $this->error('创建失败！');
            }
        } else {
            $this->display();
        }
    }

    public function delete()
    {
        $model = I('model');
        $db = M('model');
        if (M($model)->count()) $this->error('请先删除内容!');

        M('fields')->where(array('model' => $model))->delete();
        $db2 = M();
        $db2->execute("DROP TABLE IF EXISTS `" . C('DB_PREFIX') . $model . "`;");
        $db->where(array('model' => $model))->delete();
        $this->success('已删除！');
    }

    public function modify()
    {
        if (IS_POST) {
            $db = M('model');
            $data = $db->create();
            $data['titlename'] = I('titlename') ? I('titlename') : '标题';
            $db->where(array('model' => I('model')))->save($data);
            $this->success('已修改！');
        }else{
            $model = I('model');
            $this->model = M('model')->where(array('model'=>$model))->find();
            $this->display();
        }

    }

}

?>