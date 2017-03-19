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
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.UNLOGINED')));
        }

        //返回结果
        if($user){
            $this->ajaxReturn(array('error'=>false, 'data'=>$user));
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.USER_NOT_EXIST')));
        }
    }

    /**
     * 登录
     */
    public function login(){
        if(IS_POST){
            $User = D('user');
            //获取参数
            $username = str_replace(' ','',I('username'));
            $password = I('password');

            //参数检查
            if($username == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户名不能为空'));
            }
            if($password == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'密码不能为空'));
            }
            //检查用户名是否存在
            $where['username'] = $username;
            $count = $User->where($where)->count();
            if(!$count){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户不存在'));
            }
            //登录
            $where['password'] = MD5($password);
            $user = $User->relation('user_group')->where($where)->find();
            if(empty($user)){
                $this->ajaxReturn(array('error'=>true, 'data'=>'密码错误'));
            }else{
                //登录成功
                $data['lasttime'] = $user['lasttime'] = time();
                $data['lastip'] = $user['lastip'] = get_client_ip();
                $User->where(array('id'=>$user['id']))->save($data);
                session('uid',$user['id']);
                session('user',$user);

                $this->ajaxReturn(array('error'=>false, 'data'=>$user));
            }
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
    /**
     * 注册
     */
    public function register(){
        if(IS_POST){
            $User = D('user');
            //获取参数
            $username = str_replace(' ','',I('username'));
            $password = I('password');
            //参数检查
            if($username == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户名不能为空'));
            }
            if($password == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'密码不能为空'));
            }
            if(!check_phone($username)){
                $this->ajaxReturn(array('error'=>true, 'data'=>'手机号格式不对'));
            }
            //检查用户名是否存在
            $where['username'] = $username;
            $count = $User->where($where)->count();
            if($count){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户名已存在'));
            }
            //注册
            $data['username'] = $username;
            $data['password'] = MD5($password);
            $data['nickname'] = $username;
            $data['regtime'] = time();
            $data['regip'] = get_client_ip();
            $res = $User->add($data);
            //返回结果
            if($res){
                //注册成功
                $this->ajaxReturn(array('error'=>false, 'data'=>$res));
            }else{
                $this->ajaxReturn(array('error'=>true, 'data'=>'注册失败'));
            }
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
    /**
     * 编辑个人资料
     */
    public function edit(){
        //检查登录状态
        if(check_login()){
            $uid = session('uid');
            $username = session('user.username');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.UNLOGINED')));
        }
        if(IS_POST){
            $User = D('user');
            //获取参数
            $avatar = I('avatar');
            $nickname = I('nickname');
            //参数检查
            if($nickname == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'昵称不能为空'));
            }
            if($avatar == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'头像不能为空'));
            }

            //编辑
            $data['avatar'] = $avatar;
            $data['nickname'] = $nickname;
            $res = $User->where(array('id'=>$uid))->save($data);
            //返回结果
            if ($res === false) {
                $this->ajaxReturn(array('error'=>true, 'data'=>'修改资料失败'));
            } else {
                //更新session
                $user = $User->find($uid);
                session('user', $user);
                $this->ajaxReturn(array('error'=>false, 'data'=>'修改资料成功'));
            }
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
    /**
     * 注销登录
     */
    public function logout(){
        session('[destroy]');
        $this->ajaxReturn(array('error'=>false, 'data'=>'注销登录'));
    }
    /**
     * 找回密码
     */
    public function findPassword(){
        if(IS_POST){
            $User = D('user');
            //获取参数
            $username = str_replace(' ','',I('username'));
            $password = I('password');
            $passwordConfirm = I('passwordConfirm');

            //参数检查
            if($username == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户名不能为空'));
            }
            if($password == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'密码不能为空'));
            }
            if($passwordConfirm == ''){
                $this->ajaxReturn(array('error'=>true, 'data'=>'确认密码不能为空'));
            }
            if($password != $passwordConfirm){
                $this->ajaxReturn(array('error'=>true, 'data'=>'密码不一致'));
            }
            //检查用户名是否存在
            $where['username'] = $username;
            $count = $User->where($where)->count();
            if(!$count){
                $this->ajaxReturn(array('error'=>true, 'data'=>'用户名不存在'));
            }

            //修改密码
            $data['password'] = MD5($password);
            $res = $User->where($where)->save($data);
            //返回结果
            if ($res === false) {
                $this->ajaxReturn(array('error'=>true, 'data'=>'找回密码失败'));
            } else {
                $this->ajaxReturn(array('error'=>false, 'data'=>'找回密码成功'));
            }
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
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
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.UNLOGINED')));
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
        phpinfo();die(1);
        $Favorite = D('user_favorite');
        //检查登录状态
        if(check_login()){
            $uid = session('uid');
        }else{
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.UNLOGINED')));
        }
        if(IS_POST){
            //获取参数
            $sid = I('sid');
            //参数检查
            if ($sid == ''){
                $this->ajaxReturn(array('error' => true, 'data' => '目标id不能为空'));
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
            //添加数据
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
            $this->ajaxReturn(array('error'=>true,'data'=>C('ERROR_CODE.MUST_POST')));
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
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.UNLOGINED')));
        }
        if(IS_POST){
            //获取参数
            $sid = I('sid');
            //参数检查
            if ($sid == ''){
                $this->ajaxReturn(array('error' => true, 'data' => '目标id不能为空'));
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
            $this->ajaxReturn(array('error'=>true, 'data'=>C('ERROR_CODE.MUST_POST')));
        }
    }
}