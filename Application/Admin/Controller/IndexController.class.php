<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class IndexController extends BaseController {
    public function index(){
        $this->display();
    }
    public function menu(){
		$this->model = M('model')->select();
        $this->display();
    }
    public function menu1(){
        $this->display();
    }
    public function menu2(){
        $this->display();
    }
    public function menu3(){
        $this->display();
    }
    public function menu4(){
        $this->display();
    }
}