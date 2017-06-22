<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/6
 * Time: 下午3:19
 */

namespace Home\Controller;

use Home\Controller\BaseController;

class ShowController extends BaseController {
    public function index() {

    }

    /**
     * 根据筛选信息获取节目列表(获取全部节目,获取推荐节目,获取分类节目,获取筛选后节目)
     */
    public function getShows() {
        $Show = D('show');
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 2;
        $filter = $Show->create();

        //参数检查
        foreach ($filter as $key => $value) {
            if (empty($filter[$key])) {
                unset($filter[$key]);
            }
        }
//        if(!in_array($filter, array())){
//            $this->ajaxReturn(array('error'=>true,'data'=>'筛选类型有误'));
//        }
        //分页查询节目信息列表
        $list = $Show->where($filter)->relation(true)->field('')->limit($perpage)->page($page)->order('recommanded desc, ctime desc')->select();
        foreach ($list as $index => $item) {
            $list[$index]['casts'] = array_column($item['casts'], 'name');
            $list[$index]['directors'] = array_column($item['directors'], 'name');
            $list[$index]['distribution_platforms'] = array_column($item['distribution_platforms'], 'name');
            $list[$index]['tv_platforms'] = array_column($item['tv_platforms'], 'name');
        }
        //返回数据
        $this->ajaxReturn(array('error' => false, 'data' => $list));
    }

    /**
     * 获取节目详细信息
     */
    public function getShow() {
        $Show = D('show');
        //接收参数
        $id = I('id');

        //参数检查
        if (!$id) {
            if (IS_POST) {
                $this->ajaxReturn(array('error' => true, 'data' => 'id不能为空'));
            } else {
                $this->error('id不能为空');
            }
        }
        //分页查询节目信息列表
        $show = $Show->relation(true)->find($id);
//        $show['casts'] = array_column($show['casts'], 'name');var_dump($show);die(1);
//        $show['directors'] = array_column($show['directors'], 'name');
//        $show['distribution_platforms'] = array_column($show['distribution_platforms'], 'name');
//        $show['tv_platforms'] = array_column($show['tv_platforms'], 'name');
        if(!empty($show['photo'])){
            $show['photos']= explode('`',$show['photo']);array_pop($show['photos']);
        }
        if(!empty($show['preveiw'])){
            $show['previews']= explode('`',$show['preview']);array_pop($show['previews']);
        }

        if (IS_POST) {
            //返回数据
            if ($show) {
                $this->ajaxReturn(array('error' => false, 'data' => $show));
            } else {
                $this->ajaxReturn(array('error' => true, 'data' => '节目不存在'));
            }
        } else {
            //获取节目字段信息
            $fieldList = D('fields')->where(array('model'=>'show'))->order('orderid')->select();
            $this->fieldList = $fieldList;

            $this->show = $show;
            $this->display();
        }
    }

    /**
     * 添加节目详细信息
     */
    public function addShow() {
        $Show = D('show');

        if (IS_POST) {
            $data=$Show->create();
            $data['ctime']=time();
            $data['uptime']=time();
            //节目定位转化
            if (!empty($data['localization'])) {
                $localid = D('localization')->where(array('name', $data['localization']))->getField('id');
                if ($localid) {
                    $data['localization'] = $localid;
                } else {
                    //节目定位不存在
                    if (!D('localization')->where(array('id', $data['localization']))->count()) {
                        $this->ajaxReturn(array('error' => true, 'data' => '节目定位不存在'));
                    }
                }
            }
            //添加节目
            $res = $Show->add($data);
            if (!$res) {
                $this->ajaxReturn(array('error' => true, 'data' => '添加失败'));
            } else {
                $this->ajaxReturn(array('error' => false, 'data' => $res));
            }
        } else {
            //获取节目字段信息
            $fieldList = D('fields')->where(array('model'=>'show'))->order('orderid')->select();
            $this->fieldList = $fieldList;
            //获取所有节目定位信息
            $localizationList = D('localization')->select();
            $this->localizationList = $localizationList;
            //获取所有分类信息
            $typeList= D('type')->field('id,title as name,thumb as icon')->where(array('model'=>'show'))->select();
            $this->typeList= $typeList;
            $this->display();
        }
    }

    /**
     * 修改节目详细信息
     */
    public function editShow() {
        $Show = D('show');
        //接收参数
        $id = I('id');
        if (IS_POST) {
            //参数检查
            if (!$id) {
                $this->ajaxReturn(array('error' => true, 'data' => 'id不能为空'));
            }
            $data=$Show->create();
            $data['uptime']=time();
            //节目定位转化
            if(!empty($data['localization'])){
                $localid = D('localization')->where(array('name', $data['localization']))->field('id');
                if($localid){
                    $data['localization'] = $localid;
                }else{
                    unset($data['localization']);
                }
            }

            //修改节目
            $res = $Show->save($data);
            if(!$res){
                $this->ajaxReturn(array('error' => true, 'data' => '修改失败'));
            }else{
                $this->ajaxReturn(array('error' => false, 'data' => '修改成功'));
            }
        } else {
            //参数检查
            if (!$id) {
                $this->error('id不能为空');
            }
            //分页查询节目信息列表
            $show = $Show->relation(true)->find($id);
            $show['casts'] = array_column($show['casts'], 'name');
            $show['directors'] = array_column($show['directors'], 'name');
            $show['distribution_platforms'] = array_column($show['distribution_platforms'], 'name');
            $show['tv_platforms'] = array_column($show['tv_platforms'], 'name');
            if(!empty($show['photo'])){
                $show['photos']= explode('`',$show['photo']);array_pop($show['photos']);
            }
            if(!empty($show['preveiw'])){
                $show['previews']= explode('`',$show['preview']);array_pop($show['previews']);
            }
            $this->show = $show;
            //获取节目字段信息
            $fieldList = D('fields')->where(array('model'=>'show'))->select();
            $this->fieldList = $fieldList;
            //获取所有节目定位信息
            $localizationList = D('localization')->select();
            $this->localizationList = $localizationList;
            //获取所有分类信息
            $typeList= D('type')->field('id,title as name,thumb as icon')->where(array('model'=>'show'))->select();
            $this->typeList= $typeList;
            $this->display();
        }
    }

    /**
     * 删除节目
     */
    public function deleteShow() {
        $Show = D('show');
        //接收参数
        $id = I('id');  //id如果是数组则批量删除、如果是数字则删除一个
        if (IS_POST) {
            //参数检查
            if (!$id) {
                $this->ajaxReturn(array('error' => true, 'data' => 'id不能为空'));
            }
            $where = array();
            is_array($id) ? $where['id']=array('in', $id) : $where['id']=$id;
            $count = $Show->where($where)->count();
            if(!$count){
                $this->ajaxReturn(array('error' => true, 'data' => '节目不存在'));
            }
            $res = $Show->where($where)->delete();
            if($res){
                $this->ajaxReturn(array('error' => false, 'data' => '删除成功'));
            }else{
                $this->ajaxReturn(array('error' => true, 'data' => '删除失败'));
            }
        } else {
            $this->display();
        }
    }

    /**
     * 获取筛选条件信息列表（高级筛选功能按字段筛选）
     */
    public function getFilters() {
        //接收参数
        $filter = I('filter');
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 10;

        //参数检查
        if (!$filter) {
            $this->ajaxReturn(array('error' => true, 'data' => 'filter不能为空'));
        } elseif (!in_array($filter, array('cast', 'director', 'dplatform', 'tplatform', 'company', 'localization', 'typeid'))) {
            $this->ajaxReturn(array('error' => true, 'data' => 'filter格式错误'));
        }

        $db = D($filter);
        //分页查询筛选条件信息列表
        if (I('page') != '' || I('perpage') != '') {
            $list = $db->limit($perpage)->page($page)->select();    //如果传了分页参数则分页查询
        } else {
            $list = $db->select();  //默认查询全部
        }
        //返回数据
        $this->ajaxReturn(array('error' => false, 'data' => $list));
    }

    /**
     * 获取所有类型列表
     */
    public function getTypes(){
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 20;

        //参数检查

        $db = D('type');
        //分页查询筛选条件信息列表
        if (I('page') != '' || I('perpage') != '') {
            $list = $db->field('id,title as name,thumb as icon')->where(array('model'=>'show'))->limit($perpage)->page($page)->order('orderid')->select();    //如果传了分页参数则分页查询
        } else {
            $list = $db->field('id,title as name,thumb as icon')->where(array('model'=>'show'))->order('orderid')->select();  //默认查询全部
        }
        //返回数据
        $this->ajaxReturn(array('error' => false, 'data' => $list));
    }
}