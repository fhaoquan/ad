<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
use Think\Upload;
class UploadController extends BaseController{

    // 文件上传
    public function upload_tp()
    {
        $setting=C('UPLOAD_SITEIMG_QINIU');
        $Upload = new \Think\Upload($setting);// 实例化七牛上传类
        $Upload->allowExts = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp4', '3gp', 'ogg', 'mkv', 'avi');// 设置附件上传类型
        $info = $Upload->upload($_FILES);

        if (!$info) {// 上传错误提示错误信息
            echo 'error';
        } else {// 上传成功
            foreach($info as $file){

            }
            echo $file['url'];
        }
    }
}