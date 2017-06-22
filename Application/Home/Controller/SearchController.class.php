<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/6
 * Time: 下午3:19
 */

namespace Home\Controller;

use Home\Controller\BaseController;

class SearchController extends BaseController {
    /**
     * 模糊搜索节目信息
     */
    public function index() {
        if (IS_POST) {
            $Show = D('Show');
            //接收参数
            $page = I('page') ? I('page') : 1;
            $perpage = I('perpage') ? I('perpage') : 2;
            $keyword = str_replace(' ', '', I('keyword'));

            //参数检查
            if ($keyword == '') $this->ajaxReturn(array('error' => true, 'data' => '关键字不能为空'));
            //添加搜索记录
            $this->addSearch($keyword);

            //分页查询节目信息列表
            $where['name'] = array('like', '%' . $keyword . '%');
            $where['cast'] = array('like', '%' . $keyword . '%');
            $where['director'] = array('like', '%' . $keyword . '%');
            $where['tv_platform'] = array('like', '%' . $keyword . '%');
            $where['localization'] = array('like', '%' . $keyword . '%');
            $where['company'] = array('like', '%' . $keyword . '%');
            $where['ptype'] = array('like', '%' . $keyword . '%');
            $where['distribute_platform'] = array('like', '%' . $keyword . '%');
            $where['_logic'] = 'or';
            $list = $Show->where($where)->limit($perpage)->page($page)->select();
            //返回数据
            $this->ajaxReturn(array('error' => false, 'data' => $list));
        } else {
            $this->ajaxReturn(array('error' => true, 'data' => C('ERROR_CODE.MUST_POST')));
        }
    }

    /**
     * 添加搜索记录
     * @param $keyword 搜索关键字
     */
    private function addSearch($keyword) {
        //检查登录状态，若登录则保存搜索记录
        if (check_login()) {
            $uid = session('uid');
        } else {
            $uid = 0;
        }
        //保存查询信息
        $SearchContent = D('SearchContent');
        $search = $SearchContent->where(array('content' => $keyword))->find();
        if (!empty($search)) {
            //存在搜索记录
            //更新搜索内容
            $ret = $SearchContent->where(array('id' => $search['id']))->setInc('count', 1);
            $count = $SearchContent->where(array('id' => $search['id']))->getField('count');
            if (!$ret || $count <= 0) {
                $this->ajaxReturn(array('error' => true, 'data' => '搜索记录更新失败'));
            } else {
                $scid = $search['id'];
            }
        } else {
            //不存在搜索记录
            //添加搜索内容
            $res = $SearchContent->add(array('content' => $keyword, 'count' => 1));//如果主键是自增类型的话，add方法的返回值就是该主键的值。不是自增主键的话，返回值表示插入数据的个数。如果返回false则表示写入出错
            if (!$res) {
                $this->ajaxReturn(array('error' => true, 'data' => '搜索记录保存失败'));
            } else {
                $scid = $res;
            }
        }
        //保存搜索记录
        $data = array();
        $data['uid'] = $uid;
        $data['scid'] = $scid;
        $data['time'] = time();
        $data['lastip'] = get_client_ip();
        $res = D('Search')->add($data);
        if($uid){
            $ret = D('SearchUser')->add($data);
        }
        if (!$res || (isset($ret) && !$ret)) {
            $this->ajaxReturn(array('error' => true, 'data' => '搜索记录添加失败'));
        }

    }

    public function deleteSearch() {
        if (IS_POST) {
            $SearchUser = D('SearchUser');
            //接收参数
            $keyword = str_replace(' ', '', I('keyword'));
            $scid = I('id');

            //检查登录状态
            if (check_login()) {
                $uid = session('uid');
            } else {
                $this->ajaxReturn(array('error' => true, 'code' => 201, 'data' => C('ERROR_CODE.UNLOGINED')));
            }

            $where['uid'] = $uid;
            //参数检查
            if ($keyword != '') {
                $where['content'] = $keyword;
            }
            if ($scid != '') {
                $where['scid'] = $scid;
            }

            //删除记录
            $res = $SearchUser->where($where)->delete();//delete方法的返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
            if (!$res) {
                $this->ajaxReturn(array('error' => true, 'data' => '删除搜索记录失败'));
            } else {
                $this->ajaxReturn(array('error' => false, 'data' => '删除搜索记录成功'));
            }
        } else {
            $this->ajaxReturn(array('error' => true, 'data' => C('ERROR_CODE.MUST_POST')));
        }
    }

    /**
     * 获取搜索历史记录
     */
    public function getHistory() {
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 4;

        //检查登录状态
        if (check_login()) {
            $uid = session('uid');
        } else {
            $this->ajaxReturn(array('error' => true, 'code' => 201, 'data' => C('ERROR_CODE.UNLOGINED')));
        }

        $list = D('SearchInfo')->field('content')->where(array('uid' => $uid))->limit($perpage)->page($page)->group('content')->order('time desc')->select();

        //返回数据
        $this->ajaxReturn(array('error' => false, 'data' => $list));
    }

    /**
     * 获取热搜榜
     */
    public function getHot() {
        //接收参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 8;

        //查询热搜关键字
        $list = D('SearchContent')->field('content')->limit($perpage)->page($page)->order('hot desc, count desc')->select();

        //返回数据
        $this->ajaxReturn(array('error' => false, 'data' => $list));
    }

}