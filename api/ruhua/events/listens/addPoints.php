<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 13:24
 */

namespace events\listens;


use app\model\PointsConfig;
use app\model\PointsRecord;
use app\model\SysConfig;
use app\model\User;

class addPoints
{
    public function handle($event)
    {
        $post = $event;
        $this->addPoints($post);
        return '';
    }

    /**
     * 添加积分
     * @param $data
     * @return int
     * @throws OrderException
     */
    public function addPoints($data)
    {
        if($data['payment_type']=='wx'){
            $state=SysConfig::where(['key'=>'exchang_points'])->value('value');//是否开启积分

            if($state==1){
                $points_con=SysConfig::where(['key'=>'money_to_points'])->value('value');
                $points=floor ($data['order_money']/$points_con);
                PointsRecord::addRecord($data['user_id'],$points,'购买商品');
                $user=User::where('id',$data['user_id'])->find();
                $user['points']+=$points;
                $user->save();
            }
        }
    }
}