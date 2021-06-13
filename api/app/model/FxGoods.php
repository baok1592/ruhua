<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/31 0031
 * Time: 16:56
 */

namespace app\model;


use bases\BaseModel;

class FxGoods extends BaseModel
{
    /**
     * 批量设置分销商品
     * @param $data
     * @return mixed
     */
    public static function addGoods($data)
    {
        foreach ($data as $k => $v) {
            $goods = Goods::where('goods_id', $v['goods_id'])->find();
            if (!$goods) {
                continue;
            }
            $res = self::where('goods_id', $v['goods_id'])->find();
            if ($res) {
                continue;
            }
            self::create(['goods_id' => $v['goods_id'], 'price' => $v['price']]);
        }
        return app('json')->success();
    }

    /**
     * 获取商品
     * @param $type
     * @return array|\think\Collection
     */
    public static function getGoods($type)
    {
        $arr = [];
        if ($type == 1) {
            $res = self::with(['goodsInfo.imgs'])->select()->toArray();
            foreach ($res as $k => $v) {
                if ($v['goods_info'] && $v['goods_info']['state']) {
                    array_push($arr, $v);
                }
            }
        }
        if ($type == 0) {
            $goods_ids = self::column('goods_id');
            $res = Goods::with(['imgs'])->field('goods_id,img_id,price,goods_name')->select();
            foreach ($res->toArray() as $k => $v) {
                if (!in_array($v['goods_id'], $goods_ids)) {
                    array_push($arr, $v);
                }
            }
        }
        return app('json')->success($arr);
    }

    /**
     * 修改分销价格
     * @param $id
     * @param $price
     * @return mixed
     */
    public static function editPrice($id, $price)
    {
        $res = self::where('id', $id)->update(['price' => $price]);
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 删除分销商品
     * @param $id
     * @return mixed
     */
    public static function deleteFxGoods($id)
    {
        $res = self::where('id', $id)->delete();
        if ($res) {
            return app('json')->success();
        } else {
            return app('json')->fail();
        }
    }

    /**
     * 关联商品
     * @return $this
     */
    public function goodsInfo()
    {
        return $this->hasOne('Goods', 'goods_id', 'goods_id')
            ->field('goods_id,img_id,market_price,price,goods_name,state,sales');
    }

    /**
     * 获取商品
     * @return array|\think\Collection
     */
    public static function selectFxGoods()
    {
        $ids = self::column('goods_id');
        $res=Goods::with(['imgs','fxGoods'])->where('state',1)->where(['goods_id'=>$ids])->select();
        for ($i=0;$i<count($res);$i++){
            for ($j=$i;$j<count($res);$j++){
                if($res[$i]['fx_goods']<$res[$j]['fx_goods']){
                    $t=$res[$i];
                    $res[$i]=$res[$j];
                    $res[$j]=$t;
                }
            }
        }
        return app('json')->success($res);
    }

    /**
     * 查询是否为分销商品
     */
    public static function selectIsFx($id)
    {
        $res=self::where('goods_id',$id)->find();
        return $res?1:0;
    }

}