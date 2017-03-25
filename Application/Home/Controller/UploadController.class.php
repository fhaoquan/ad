<?php
namespace Home\Controller;

use Home\Controller\BaseController;

class UploadController extends BaseController {
    public function index() {
        $setting = C('UPLOAD_SITEIMG_QINIU');
        $Upload = new \Think\Upload($setting);
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