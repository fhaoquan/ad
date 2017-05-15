<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class UploadController extends BaseController {
    public function index() {
        $setting = C('UPLOAD_SITEIMG_QINIU');
        $Upload = new \Think\Upload($setting);
        $Upload->allowExts = array('jpg', 'jpeg', 'gif', 'png', 'bmp', 'mp4', '3gp', 'ogg', 'mkv', 'avi');// 设置附件上传类型
        $info = $Upload->upload($_FILES);
        if (!$info) {
            $data['data'] = $Upload->getError();
            $data['error'] = true;
        } else {
            foreach ($info as $file) {
                $data['data'] = $file['url'];
            }
            $data['error'] = false;
        }
        $this->ajaxReturn($data);
    }
}