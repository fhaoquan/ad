<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/9
 * Time: 下午10:36
 */

/**
 * 检查登录状态
 * @return bool 是否成功登录
 */
function check_login(){
    $User = D('user');
    $uid = session('uid');
    if(!$uid){
        return false;
    }else{
        //查询用户是否存在或匹配
        $user = $User->find($uid);
        $user = session('user?') ? session('user') : $user;
        if($user){
            return true;
        }else{
            return false;
        }
    }
}

/**
 * @param $uid 用户id
 * @param $sid 收藏目标id
 * @return bool 是否已经收藏
 */
function check_favorite($uid, $sid){
    $Favorite = M('user_favorite');
    $where = array('uid'=>$uid);
    $sids = $Favorite->where($where)->getField('sids');
    $sids = explode(',', $sids);

    if(in_array($sid, $sids)){
        return true;
    }else{
        return false;
    }
    return false;
}