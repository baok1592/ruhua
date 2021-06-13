<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/11 0011
 * Time: 15:42
 */

namespace app\model;


use bases\BaseModel;
use exceptions\BaseException;

class DiscountGoods extends BaseModel
{
    public function discount()
    {
        return $this->belongsTo('Discount', 'discount_id', 'id');
    }

    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'goods_id');
    }

    /**
     * 获取限时优惠商品，排序
     * @return \think\response\Json
     */
    public static function getAllDiscountGoods()
    {
        $res = self::with(['discount', 'goods.imgs'])->select()->toArray();
        $discount_time = SysConfig::where('key', 'discount_start_time')->value('value');
        for ($i = 0; $i < count($res); $i++) {
            $res[$i]['start_time'] = strtotime("-" . $discount_time . "hours", strtotime($res[$i]['discount']['start_time']));
            $res[$i]['end_time'] =  strtotime($res[$i]['discount']['end_time']);
            if ($res[$i]['discount']['time_rule'] == 1) {
                foreach ($res[$i]['discount']['day_json'] as $key => $value) {
                    $start_time = strtotime("-" . $discount_time . "hours", strtotime($value['start_time'], strtotime("+" . $value['day'] - date("w") . "day")));
                    $end_time = strtotime($value['end_time'], strtotime("+" . $value['day'] - date("w") . "day"));
                    if ($start_time < time() && time() < $end_time) {
                        $res[$i]['start_time'] = $start_time;
                        $res[$i]['end_time'] = $end_time;
                        break;
                    }
                    if (count($res[$i]['discount']['day_json']) - 1 == $key) {
                        $res[$i]['start_time'] = $start_time;
                        $res[$i]['end_time'] = $end_time;
                    }
                }
            }
            for ($j = $i; $j < count($res); $j++) {
                $res[$j]['start_time'] = strtotime("-" . $discount_time . "hours", strtotime($res[$j]['discount']['start_time']));
                $res[$j]['end_time'] = strtotime($res[$j]['discount']['end_time']);
                if ($res[$j]['discount']['time_rule'] == 1) {
                    foreach ($res[$j]['discount']['day_json'] as $key => $value) {
                        $start_time = strtotime("-" . $discount_time . "hours", strtotime($value['start_time'], strtotime("+" . $value['day'] - date("w") . "day")));
                        $end_time = strtotime($value['end_time'], strtotime("+" . $value['day'] - date("w") . "day"));
                        if ($start_time < time() && time() < $end_time) {
                            $res[$j]['start_time'] = $start_time;
                            $res[$j]['end_time'] = $end_time;
                            break;
                        }
                        if (count($res[$j]['discount']['day_json']) - 1 == $key) {
                            $res[$j]['start_time'] = $start_time;
                            $res[$j]['end_time'] = $end_time;
                        }
                    }
                }
                if ($res[$i]['start_time'] > $res[$j]['start_time']) {
                    $a = $res[$i];
                    $res[$i] = $res[$j];
                    $res[$j] = $a;
                }
            }
        }
        foreach ($res as $k => $v) {
            if (time() < $v['start_time'] || time() > $v['end_time'] || !$v['goods']['state']) {
                unset($res[$k]);
            }
            if(!$v['goods']||!$v['goods']['state']){
                unset($res[$k]);
            }
        }
        return $res;
    }

    /**
     * 获取限时折扣商品关联
     * @param $id
     * @return array|null|string|\think\Model
     */
    public static function getDiscountGoods($id)
    {
        $goods = self::with('discount')->where('goods_id', $id)->find();

        if (!$goods) {
            return '[]';
        }

        $discount_time = SysConfig::where('key', 'discount_start_time')->value('value');
        $start_time = strtotime("-" . $discount_time . "hours", strtotime($goods['discount']['start_time']));
        $end_time = strtotime($goods['discount']['end_time']);
        if (time() < $start_time || time() > $end_time) {
            return '非活动时间';
        }
        if ($goods['discount']['time_rule'] == 1) {
            foreach ($goods['discount']['day_json'] as $key => $value) {
                $start_time = strtotime("-" . $discount_time . "hours", strtotime($value['start_time'], strtotime("+" . $value['day'] - date("w") . "day")));
                $end_time = strtotime($value['end_time'], strtotime("+" . $value['day'] - date("w") . "day"));
                if ($start_time < time() && time() < $end_time) {
                    $goods['discount']['start_time']=strtotime($value['start_time'], strtotime("+" . $value['day'] - date("w") . "day"));
                    $goods['discount']['end_time']=$end_time;
                    return $goods;
                }
                if (count($goods['discount']['day_json']) - 1 == $key) {
                    return '非活动日期';
                }
            }
        }
        return $goods;
    }
}