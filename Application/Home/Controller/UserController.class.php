<?php
namespace Home\Controller;
use Home\Controller\BaseController;
class UserController extends BaseController {
    /**
     * 主页
     */
    public function index(){
        //检查登录状态
        if(check_login()){
            $user = session('user');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>'请登录'));
        }

        //返回结果
        if($user){
            $this->ajaxReturn(array('error'=>false, 'data'=>$user));
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>'用户不存在'));
        }
    }

    /**
     * 登录
     */
    public function login(){

    }
    /**
     * 注册
     */
    public function register(){

    }
    /**
     * 编辑个人资料
     */
    public function edit(){

    }
    /**
     * 注销登录
     */
    public function logout(){

    }
    /**
     * 忘记密码
     */
    public function forgetPassword(){

    }
    /**
     * 获取收藏列表
     */
    public function getFavorites(){
        $Favorite = D('user_favorite');
        //检查登录状态
        if(check_login()){
            $uid = session('uid');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>'请登录'));
        }
        //获取参数
        $page = I('page') ? I('page') : 1;
        $perpage = I('perpage') ? I('perpage') : 2;
        $order = I('order') ? I('order') : 'ctime desc';
        $filter = D('show')->create();

        //参数检查
        foreach ($filter as $key => $value){
            if(empty($filter[$key])){
                unset($filter[$key]);
            }
        }

        //分页查询收藏列表
        $sids = $Favorite->where(array('userid'=>$uid))->getField('sids');
        $sids = trim($sids, ',');
        $filter['id'] = array('in',$sids);
        $list = D('show')->where($filter)->limit($perpage)->page($page)->order($order)->select();

        //返回结果
        $this->ajaxReturn(array('error'=>false,'data'=>$list));
    }
    /**
     * 添加收藏
     */
    public function addFavorite(){
        $Favorite = D('user_favorite');
        //检查登录状态
        if(check_login()){
            $uid = session('uid');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>'请登录'));
        }
        if(IS_POST){
            //获取参数
            $sid = I('sid');
            //参数检查
            if (!is_numeric($sid)){
                $this->ajaxReturn(array('error' => true, 'data' => '目标id有误'));
            }
            //查询收藏目标是否存在
            $count = M('show')->where(array('id'=>$sid))->count();
            if(!$count){
                $this->ajaxReturn(array('error' => true, 'data' => '收藏目标不存在'));
            }
//            $result = $Favorite->where(array('uid' => $uid))->find();
//            if(!empty($result)){
//                //查询该用户是否已收藏
//                $sids = explode(',', $result['sids']);
//                if(in_array($sid, $sids)){
//                    $this->ajaxReturn(array('error' => true, 'data' => '已收藏'));
//                }
//                //保存数据
//                $data['sids'] = $result['sids'] . $sid . ',';
//                $res = $Favorite->where(array('id'=>$result['id']))->save($data);//save方法的返回值是影响的记录数，如果返回false则表示更新出错
//            }else{
//                //添加数据
//                $data['sids'] = ',' . $sid . ',';
//                $res = $Favorite->add($data);//如果主键是自增类型的话，add方法的返回值就是该主键的值。不是自增主键的话，返回值表示插入数据的个数。如果返回false则表示写入出错
//            }

            //查询该用户是否已收藏
            if(check_favorite($uid, $sid)){
                $this->ajaxReturn(array('error' => true, 'data' => '已收藏'));
            }
            //保存数据
            $data['sid'] = $sid;
            $data['uid'] = $uid;
            $data['ctime'] = time();
            //检查用户是否收藏过其他同类东西
            $result = $Favorite->where(array('uid' => $uid))->find();
            if (!empty($result)) {
                $data['sids'] = $result['sids'] . $sid . ',';
                $res = $Favorite->where(array('id'=>$result['id']))->save($data);//save方法的返回值是影响的记录数，如果返回false则表示更新出错
            } else {
                $data['sids'] = ',' . $sid . ',';
                $res = $Favorite->add($data);//如果主键是自增类型的话，add方法的返回值就是该主键的值。不是自增主键的话，返回值表示插入数据的个数。如果返回false则表示写入出错
            }

            //返回结果
            if($res === false){
                $this->ajaxReturn(array('error'=>true,'data'=>'收藏失败'));
            }else{
                /**更新收藏数**/

                $this->ajaxReturn(array('error'=>false,'data'=>'收藏成功'));
            }
        }else{
            $this->ajaxReturn(array('error'=>true,'data'=>'非法调用'));
        }
    }
    /**
     * 删除收藏
     */
    public function deleteFavorite(){
        $Favorite = D('user_favorite');
        //检查登录状态
        if(check_login()){
            $uid = session('uid');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>'请登录'));
        }
        if(IS_POST){
            //获取参数
            $sid = I('sid');
            //参数检查
            if (!is_numeric($sid)){
                $this->ajaxReturn(array('error' => true, 'data' => '目标id有误'));
            }
            //查询该用户是否已收藏
            if(!check_favorite($uid, $sid)){
                $this->ajaxReturn(array('error' => true, 'data' => '未收藏'));
            }
            //检查用户是否收藏过其他同类东西
            $result = $Favorite->where(array('uid' => $uid))->find();//由于检测过用户已收藏，result必定不为空
            $sids = preg_replace('/,'.$sid.',/', ',', $result['sids']);
            if(empty($sids) || $sids==','){
                $res = $Favorite->where(array('id'=>$result['id']))->delete();//delete方法的返回值是删除的记录数，如果返回值是false则表示SQL出错，返回值如果为0表示没有删除任何数据
            }else{
                $res = $Favorite->where(array('id'=>$result['id']))->save(array('sids'=>$sids));//save方法的返回值是影响的记录数，如果返回false则表示更新出错
            }

            //返回结果
            if (!$res) {
                $this->ajaxReturn(array('error'=>true,'data'=>'取消收藏失败'));
            } else {
                /**更新收藏数**/

                $this->ajaxReturn(array('error'=>false,'data'=>'取消收藏成功'));
            }
        }else{
            $this->ajaxReturn(array('error'=>true,'data'=>'非法调用'));
        }
    }
}