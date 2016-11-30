<?php
/**
 * 全局方法文件
 * 2016-2-29 17:09:21
 */
function p($data){
	print_r($data);
}
//组合多维数组
function node_merge($node,$pid=0){
		$arr=array();
		foreach($node as $v){
			if($v['pid']==$pid){
				$v['child']=node_merge($node,$v['id']);
				$arr[]=$v;
			}
	}
	return $arr;
}
//查找所有父级分类(一维数组，排序:高->低)
function getparents($node,$id){
	$arr=array();
	foreach($node as $v)
	{
		if($v[id]==$id){
			$arr[]=$v;
			$arr=array_merge(getparents($node,$v['pid']),$arr);
		}
	}
	return $arr;
}
//查找所有后代分类id
function getchidsid($node,$pid){
	$arr=array();
	foreach($node as $v){
		if($v['pid']==$pid){
			$arr[]=$v['id'];
			$arr=array_merge($arr,getchidsid($node,$v['id']));
		}
	}
	return $arr;
}
//查找所有后代分类
function getchids($node,$pid){
	$arr=array();
	foreach($node as $v){
		if($v['pid']==$pid){
			$arr[]=$v;
			$arr=array_merge($arr,getchids($node,$v['id']));
		}
	}
	return $arr;
}

//冒泡排序（只支持二维数组）
function sort_bubble($arr, $key = null){
    $len = count($arr);
    for($i = 1; $i < $len; ++$i) {// 外层循环 数组个数-1
        for ($k = 0, $klen = $len - $i; $k < $klen; ++$k) {// 内层循环，比较两个数组元素
            if(is_array($arr[$k])){
                if($key){
                    if ($arr[$k][$key] > $arr[$k + 1][$key]) {
                        swap($arr[$k][$key], $arr[$k + 1][$key]);
                    }
                }else{
                    if ($arr[$k][$key] > $arr[$k + 1][$key]) {
                        swap(count($arr[$k]), count($arr[$k + 1]));
                    }
                }
            }else{
                if ($arr[$k] > $arr[$k + 1]) {
                    swap($arr[$k], $arr[$k + 1]);
                }
            }
        }
    }
    return $arr;
}
//交换顺序
function swap(&$a, &$b){
    $temp = $a;
    $a = $b;
    $b = $temp;
}

/******************************支付宝相关*******************************/
//生成订单
function generate_order($parameter){
    //生成订单
    $Ord = M('orderlist');
    $data['out_trade_no']   =$parameter['out_trade_no'];
    $data['pid']            =$parameter['pid'];
    $data['model']          =$parameter['model'];
    $data['userid']         =$parameter['userid'];
    $data['ctime']          =time();
    $data['subject']        =$parameter['subject'];
    $data['payment_type']   =$parameter['payment_type'];
    $data['status']         = 0;
    $res = $Ord->add($data);
    if($res){
        return true;
    }else{
        return false;
    }
}

//检查订单状态
function check_order_status($out_trade_no){
    $Ord=M('Orderlist');
    $ordstatus=$Ord->where('out_trade_no='.$out_trade_no)->getField('status');
    if($ordstatus==1){
        return true;
    }else{
        return false;
    }
}

//处理订单函数
function orderhandle($parameter){
    $out_trade_no           =$parameter['out_trade_no'];
    $Ord=M('Orderlist');
    $order = $Ord->where(array('out_trade_no'=>$out_trade_no))->find();
    $notice = array();
    if($order['type'] == '1'){//升级会员
        $group = M($order['model'])->find($order['pid']);
        $data['gid'] = $group['id'];
        $data['endtime'] = time() + $group['timeline'];
        $res = M('user')->where(array('id'=>$order['userid']))->save($data);
        if(!$res){
            return false;
        }
        $notice['pid'] = $group['id'];
        $notice['model'] = $order['model'];
        $notice['puserid'] = $order['userid'];
        $notice['userid'] = 0;
        $notice['type'] = 'charge';
    }
    //更新订单状态
    $ord['status']         = 1;
    $res = $Ord->where('out_trade_no='.$out_trade_no)->save($ord);
    if($res){
        //发送全站通知
        pm($notice['pid'],$notice['model'],$notice['puserid'],$notice['userid'],$notice['type']);
        return true;
    }else{
        return false;
    }
}

//获取一个随机且唯一的订单号
function generate_ordcode(){
    $Ord=M('Orderlist');
    $numbers = range (10,99);
    shuffle ($numbers);
    $code=array_slice($numbers,0,4);
    $ordcode=$code[0].$code[1].$code[2].$code[3];
    $oldcode=$Ord->where("out_trade_no='".$ordcode."'")->getField('out_trade_no');
    if($oldcode){
        generate_ordcode();
    }else{
        return $ordcode;
    }
}
/*********************************END********************************/

?>