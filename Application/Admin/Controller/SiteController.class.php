<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
    class SiteController extends BaseController {
        public function index(){
            $where=array();
            $where['sys']=array('neq',1);
            $where['model']='site';
            $this->fields=M('fields')->where($where)->order('orderid asc')->select();

            $this->site=M('site')->find(1);
            $this->set=include('./Application/Home/Conf/config.php');
            $this->display();
        }
        public function siteHandle(){
            //info edito
            $db=M('site');
            $data=$db->create();
            $db->where('id=1')->save($data);

            //static config edito
            $arras=include('./Application/Home/Conf/config.php');
            if((int)I('URL_MODEL')==0)
                $arras['URL_ROUTER_ON']=0;
            else
                $arras['URL_ROUTER_ON']=1;

            $arras['URL_HTML_SUFFIX']=I('URL_HTML_SUFFIX');
            $arras['URL_MODEL']=(int)I('URL_MODEL');
            $arras['HTML_CACHE_ON']=(int)I('HTML_CACHE_ON');
            file_put_contents('./Application/Home/Conf/config.php', '<?php return '.var_export($arras, true).';');

            $this->success('修改成功');
        }
        //清空缓存
        public function deletecache(){
//            $cachedir = APP_PATH.'/Runtime';
            if(cleardir(RUNTIME_PATH)){
                $this->success('清空完成',U('Index/main'));
            }else{
                $this->error('清理失败');
            }
        }


        public function attachment(){
            $pid=I('pid');
            $tag=I('tag');
            import('ORG.Util.Page');
            if($pid&&$tag)
                $count= M('attachment')->where(array('pid'=>$pid,'tag'=>$tag))->count();
            else
                $count= M('attachment')->count();

            $Page= new Page($count,15);
            $limit=$Page->firstRow .',' .$Page->listRows;

            if($pid&&$tag){
                $this->list=M('attachment')->where(array('pid'=>$pid,'tag'=>$tag))->limit($limit)->select();
                $this->pid=$pid;
                $this->tag=$tag;
            }
            else{
                $this->list=M('attachment')->limit($limit)->select();
            }
            $this->page=$Page->show();
            $this->display();
        }
        public function attachmentorders(){
            $pid=I('pid');
            $tag=I('tag');

            $db=M('attachment');
            foreach($_POST as $ids=>$orders){
                $db->where(array('id'=>$ids))->setField('orders',$orders);
            }
            $this->redirect('Site/attachment',array('pid'=>$pid,'tag'=>$tag));
        }
        public function attachmentdel(){
            $id=I('id');
            $pid=I('pid');
            $tag=I('tag');
            M('attachment')->where(array('id'=>$id))->delete();

            $this->redirect('Site/attachment',array('pid'=>$pid,'tag'=>$tag));
        }
        public function review(){
            $pid=I('pid');
            $tag=I('tag');
            import('ORG.Util.Page');
            if($pid&&$tag)
                $count= M('discuss')->where(array('pid'=>$pid,'tag'=>$tag))->count();
            else
                $count= M('discuss')->count();

            $Page= new Page($count,15);
            $limit=$Page->firstRow .',' .$Page->listRows;

            if($pid&&$tag){
                $this->list=M('discuss')->where(array('pid'=>$pid,'tag'=>$tag))->order('cdate desc')->limit($limit)->select();
                $this->pid=$pid;
                $this->tag=$tag;
            }
            else{
                $this->list=M('discuss')->order('cdate desc')->limit($limit)->select();
            }
            $this->page=$Page->show();
            $this->display();
        }
        public function reviewdel(){
            $id=I('id');
            $pid=I('pid');
            $tag=I('tag');

            M('discuss')->where(array('id'=>$id))->delete();

            $this->redirect('Site/review',array('pid'=>$pid,'tag'=>$tag,'p'=>I('p')));
        }
        public function reviewshow(){
            $id=I('id');
            $this->pid=I('pid');
            $this->tag=I('tag');
            $this->body=M('discuss')->find($id);

            $this->display();
        }
       
    }
?>