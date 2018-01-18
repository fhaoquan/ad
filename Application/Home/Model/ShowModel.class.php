<?php
/**
 * Created by PhpStorm.
 * User: heavon
 * Date: 2016/12/27
 * Time: 下午5:56
 */

namespace Home\Model;
use Think\Log;
use Think\Model\RelationModel;
class ShowModel extends RelationModel{
    public $_link=array(
        'localization'=>array(
            'mapping_type'=>self::BELONGS_TO,
            'class_name'=>'localization',
            'mapping_name'=>'localization',
            'foreign_key'=>'localization',
            'mapping_fields'=>'name',
            'as_fields'=>'name:localization',
        ),
    );

    //招商状态判定
    public function checkInvestment($show){
        $status = '';
        if(!empty($show['start_date']) || !empty($show['end_date'])){
            $flag1 = $flag2 = false;

            if($today_date = $this->checkFormat($show['start_date'])){
                if($today_date < $show['start_date']){
                    $status = '未招商';
                }else{
                    $flag1 = true;
                }
            }

            if($today_date = $this->checkFormat($show['end_date'])){
                if($show['end_date'] < $today_date){
                    $status = '招商结束';
                }else{
                    $flag2 = true;
                }
            }

            if($flag1 && $flag2){
                $status = '招商中';
            }
        }

        //如果招商状态不符则更新状态
        if(!empty($status) && $show['investment_status'] != $status){
            $res = M('Show')->save(array('id'=>$show['id'], 'investment_status'=>$status));
            if(!$res) Log::error('ID为'.$show['id'].'的节目招商状态更新失败');
        }

        $status = !empty($status) ? $status : '待定';

        return $status;
    }

    //检查时间格式
    protected function checkFormat($date){
        $formats = ['Y-m-d', 'Y-m'];
        $today_date = false;
        foreach($formats as $format){
            if(date($format,strtotime($date)) == $date){
                $today_date = date($format);
                break;
            }
        }
        $preg = '/(\d{4})-Q([1|2|3|4])/';   //季度时间格式 , 例如 ‘2017-Q2’
        if(preg_match($preg, $date)){
            $season = ceil(date('n') / 3);  //当前季度
            $today_date = date('Y').'-Q'.$season;
        }
        return $today_date;
    }

}