<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22 0022
 * Time: 13:00
 */

namespace app\model;


use bases\BaseModel;

class PtGoods extends BaseModel
{
    public function goods()
    {
        return $this->belongsTo('Goods', 'goods_id', 'goods_id');
    }

    public function pt()
    {
        return $this->belongsTo('Pt', 'pt_id', 'id');
    }

    /**
     * 获取拼团商品，
     * @param $type 1普通拼团，2新用户拼团
     * @return \think\response\Json
     */
    public static function getAllPtGoods($type)
    {
        $res = self::with(['pt', 'goods.imgs'])->select()->toArray();
        foreach ($res as $k => $v) {
            $res[$k]['sort_time'] = strtotime('-12 hours', strtotime($v['pt']['start_time']));
        }
        for ($i = 0; $i < count($res); $i++) {
            for ($j = $i; $j < count($res); $j++) {
                if ($res[$i]['sort_time'] > $res[$j]['sort_time']) {
                    $a = $res[$i];
                    $res[$i] = $res[$j];
                    $res[$j] = $a;
                }
            }
        }
        $arr = [];
        foreach ($res as $k => $v) {
            if (time() < $res[$k]['sort_time'] || time() > strtotime($v['pt']['end_time']) || !$v['goods']['state']) {
                unset($res[$k]);
            }
            if ($type == 1) {
                if ($v['pt']['is_cou_tuan'] == 0) {
                    array_push($arr, $v);
                }
            } else if ($type == 2) {
                if ($v['pt']['is_cou_tuan'] == 1 && $v['pt']['is_new_user'] == 0) {
                    array_push($arr, $v);
                }
            } else if ($type == 3) {
                if ($v['pt']['is_cou_tuan'] == 1 && $v['pt']['is_new_user'] == 1) {
                    array_push($arr, $v);
                }
            }
        }
        return $arr;
    }

    /**
     * 获取拼团商品关联
     * @param $id
     * @return array|null|string|\think\Model
     */
    public static function getPtGoods($id)
    {
        $goods = self::with('pt')->where('goods_id', $id)->find();
        if (!$goods) {
            return '[]';
        }
        if (time() < strtotime("-" . $goods['pt']['visible_time'] . "hours", strtotime($goods['pt']['start_time'])) || time() > strtotime($goods['pt']['end_time'])) {
            return '[]';
        }
        return $goods;
    }
}