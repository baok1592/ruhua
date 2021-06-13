<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/22 0022
 * Time: 12:59
 */

namespace app\model;


use bases\BaseModel;
use think\facade\Db;

class Pt extends BaseModel
{
    protected $type = [
        'start_time' => 'timestamp',
        'end_time' => 'timestamp'
    ];
    protected $hidden = ['create_time', 'update_time'];

    public function ptGoods()
    {
        return $this->hasMany('PtGoods', 'pt_id', 'id');
    }

    /**
     * 添加团购活动
     * @param $post
     * @return mixed
     */
    public static function addPt($post)
    {
        $res = self::where('name', $post['name'])->find();
        if ($res) {
            return app('json')->fail('活动已存在');
        }
        Db::startTrans();
        try {
            $res = self::create($post);
            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();

                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                $pt_goods = PtGoods::where('goods_id', $v['goods_id'])->find();
                $discount_goods = DiscountGoods::where('goods_id', $v['goods_id'])->find();
                if ($pt_goods || $discount_goods) {
                    return app('json')->fail('商品在其他活动中');
                }
                if (($goods['price'] - $v['price']) < 0.01) {
                    return app('json')->fail('价格不能高于商品价');
                }
                PtGoods::create([
                    'goods_id' => $v['goods_id'],
                    'pt_id' => $res['id'],
                    'price' => $v['price'],
                ]);
            }
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * 修改团购活动
     * @param $post
     * @return mixed
     */
    public static function editPt($post)
    {
        $res = self::where('id', $post['id'])->find();
        Db::startTrans();
        try {
            $res->save($post);
            PtGoods::where('pt_id', $post['id'])->delete();
            foreach ($post['goods_json'] as $k => $v) {
                $goods = Goods::where('goods_id', $v['goods_id'])->find();
                if (!$goods) {
                    return app('json')->fail('商品不存在');
                }
                $pt_goods = PtGoods::where('goods_id', $v['goods_id'])->find();
                $discount_goods = DiscountGoods::where('goods_id', $v['goods_id'])->find();
                if ($pt_goods || $discount_goods) {
                    return app('json')->fail('商品在其他活动中');
                }
                PtGoods::create([
                    'goods_id' => $v['goods_id'],
                    'pt_id' => $res['id'],
                    'price' => $v['price'],
                ]);
            }
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail($e->getMessage());
        }
    }

    /**
     * 删除优惠活动
     * @param $id
     * @return mixed
     */
    public static function deletePt($id)
    {
        Db::startTrans();
        try {
            self::where('id', $id)->delete();
            PtGoods::where('pt_id', $id)->delete();
            Db::commit();
            return app('json')->success();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->fail();
        }
    }

}