<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;

class BackupController extends BaseController
{
    Public function index()
    {
        $this->display();
    }

    public function handle()
    {
        $backupdir = './Uploads/Backup/' . date('Y-m-d-H-i-s', time());
        if(!mkdir($backupdir,0777,true)){
            $this->error('备份失败');
        }

        $db_conf = array(
            'DB_HOST'=>C('DB_HOST'),
            'DB_USER'=>C('DB_USER'),
            'DB_PWD'=>C('DB_PWD'),
            'DB_NAME'=>C('DB_NAME'),
        );
        $dbname = C('DB_NAME');
        $result = M()->query('SHOW TABLE STATUS FROM ' . $dbname);
        foreach ($result as $vs) {
            if(!dump_table($vs['name'], $backupdir, $db_conf)){
                $this->error('备份失败！');
            }
        }

        $this->success('备份完成！', U('index'));
    }

    public function restore(){
        if(IS_POST){
            $backuppath = './Uploads/Backup/';
            $restoredir = I('restoredir');
            if($restoredir == ''){
                $this->error('请选择备份文件');
            }else if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}/",$restoredir) || !is_dir($backuppath.$restoredir)){
                $this->error('备份文件无效');
            }
            $db_conf = array(
                'DB_HOST'=>C('DB_HOST'),
                'DB_USER'=>C('DB_USER'),
                'DB_PWD'=>C('DB_PWD'),
                'DB_NAME'=>C('DB_NAME'),
            );
            if(restore_db($backuppath.$restoredir, $db_conf))
                $this->success('恢复成功');
            else
                $this->error('恢复失败');
        }else{
            $backuppath = './Uploads/Backup/';
            $backupdirs = scandir($backuppath);
            foreach($backupdirs as $key=>$dir){
                if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}-[0-9]{2}-[0-9]{2}/",$dir)){
                    unset($backupdirs[$key]);
                }
            }
            $this->backupdirs = array_reverse($backupdirs);
            $this->display();
        }
    }
}