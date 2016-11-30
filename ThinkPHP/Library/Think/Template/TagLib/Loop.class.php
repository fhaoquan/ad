<?php
namespace Think\Template\TagLib;
use Think\Template\TagLib;
/**
 * loop标签库解析类
 */
class Loop extends TagLib {
    
        protected $tags   =  array(
            'loop'    => array('attr'=>'table,id,typeid,userid,order,limit,as,field,key,page','level'=>5),
            );
        public function _loop($attr,$content) {
                    if(empty($attr['table'])) $attr['table']='type';
                    if(empty($attr['id'])) $attr['id']=0;
                    if(empty($attr['typeid'])) $attr['typeid']=0;
                    if(empty($attr['userid'])) $attr['userid']=0;
                    if(empty($attr['order'])) $attr['order']='id desc';
                    if(empty($attr['limit'])) $attr['limit']=10;
                    if(empty($attr['as'])) $attr['as']='$v';
                    if(empty($attr['field'])) $attr['field']=0;
                    if(empty($attr['key'])) $attr['key']='$i';
                    if(empty($attr['page'])) $attr['page']='$page';
					//limit条件
					if(stristr($attr['limit'],',')){
						$attr['limit'] = $attr['limit'];
					}
                    $str=<<<str
<?php
					{$attr['key']}=0;//自增长
                    \$where_c=array();
                    \$where_c=null;
                     if({$attr['id']}!=0){
                        \$where_c['id']={$attr['id']};
                     }
                     else{
                            if('{$attr['typeid']}')
                            {
                                \$Cat_c_s=M('type')->order('orderid asc')->select();
								if(stristr('{$attr['typeid']}',',')){
									\$data_a='{$attr['typeid']}';
								}else{
									\$data_a=getchidsid(\$Cat_c_s,{$attr['typeid']});//单ID获取子分类
									array_push(\$data_a,{$attr['typeid']});
								}
                                \$where_c['typeid']=array('in',\$data_a);
                            }
                            if("{$attr['field']}"!="0")
                            {
                                if(strpos("{$attr['field']}",'like')!=false){
                                     \$match_m = array(); 
                                    preg_match_all('/\|(.*?)\|/', "{$attr['field']}", \$match_m);
                                    if(\$match_m[0][0]){
                                        \$_string_tem=str_ireplace(\$match_m[0][0],\$\$match_m[1][0],"{$attr['field']}");
                                    }
                                    else{
                                        \$_string_tem="{$attr['field']}";
                                    }
                                    
                                    \$_string_tem=strtoupper(\$_string_tem);
                                    \$_string_array=explode('LIKE',\$_string_tem);
                                    \$where_c['_string']=\$_string_array[0]." LIKE '%".ltrim(\$_string_array[1])."%'";
                                }
                                else{
                                    \$match_m = array(); 
                                    preg_match_all('/\|(.*?)\|/', "{$attr['field']}", \$match_m);
                                    if(\$match_m[0][0]){
                                        \$_string=str_ireplace(\$match_m[0][0],"'".\$\$match_m[1][0]."'","{$attr['field']}");
                                    }
                                    else{
                                        \$_string="{$attr['field']}";
                                    }
                                    \$_string=str_ireplace(' eq '," = '",\$_string);
                                    \$_string=str_ireplace(' neq '," <> '",\$_string);
                                    \$_string=str_ireplace(' gt '," > '",\$_string);
                                    \$_string=str_ireplace(' egt '," >= '",\$_string);
                                    \$_string=str_ireplace(' lt '," < '",\$_string);
                                    \$_string=str_ireplace(' elt '," <= '",\$_string);
                                
                                    \$where_c['_string']=\$_string."'";
                                }
                            }							
                     }
                    if({$attr['userid']}!=0){
                        \$where_c['userid']={$attr['userid']};
                    }
					if(!stristr({$attr['limit']},',')){
						\$count= D('Content')->table(C('DB_PREFIX')."{$attr['table']}")->relation(true)->where(\$where_c)->count();
						\$Pages= new \Think\Page(\$count,{$attr['limit']});
						\$limits=\$Pages->firstRow .',' .\$Pages->listRows;
						{$attr['page']}=\$Pages->show();
						\$content_s=D('Content')->table(C('DB_PREFIX')."{$attr['table']}")->relation(true)->where(\$where_c)->order("{$attr['order']}")->limit(\$limits)->select();
					}else{
						\$content_s=D('Content')->table(C('DB_PREFIX')."{$attr['table']}")->relation(true)->where(\$where_c)->order("{$attr['order']}")->limit("{$attr['limit']}")->select();
					}
                    foreach(\$content_s as {$attr['as']}): 
						{$attr['key']}++;
?>
str;
                    $str .= $content;
                    $str .= '<?php endforeach;?>';
                    //清空变量
                    $attr['table']=null;
                    $attr['id']=null;
                    $attr['typeid']=null;
                    $attr['order']=null;
                    $attr['limit']=null;
                    $attr['as']=null;
                    $attr['field']=null;
                    $attr['key']=null;
                    $attr['page']=null;
                    return $str;
            }        
            
    }
?>