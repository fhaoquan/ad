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
 * 检查用户是否已收藏
 * @param $uid 用户id
 * @param $sid 收藏目标id
 * @return bool 是否已经收藏
 */
function check_favorite($uid, $sid){
    $Favorite = D('user_favorite');
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

/**
 * 检查电话号码格式
 * @param $phone 电话号码
 * @return bool 格式是否正确
 */
function check_phone($phone){
    $pattern = '/^(1(([35][0-9])|(47)|[8][01236789]))\\d{8}$/';
    if(preg_match($pattern, $phone)){
        return true;
    }else{
        return false;
    }
}