<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/24 0024
 * Time: 13:42
 */

namespace app\model;


use bases\BaseModel;
use exceptions\BaseException;
use think\facade\Db;

class Delivery extends BaseModel
{

    /**
     * 添加新记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function addDelivery($data)
    {
        Db::startTrans();
        try{
            $res=self::create($data);
            DeliveryRule::createDeliveryRule($data['rule'],$res['id']);
            Db::commit();
            if($res){
             return app('json')->success($res['id']);
            }else{
                return app('json')->fail();
            }
        }catch (\Exception $e){
            Db::rollback();
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
    }

    /**
     * 编辑记录
     * @param $data
     * @return bool|int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function editDelivery($data) {
        if (!isset($data['rule']) || empty($data['rule'])) {
            return app('json')->fail('请选择可配送区域');
        }
        Db::startTrans();
        try{
            $delivery=self::where('id',$data['id'])->find();
            if(!$delivery){
                return app('json')->fail('id错误');
            }
            $res=$delivery->save($data);
            DeliveryRule::createDeliveryRule($data['rule'],$data['id']);
            Db::commit();
            if($res){
                return app('json')->success($res['id']);
            }else{
                return app('json')->fail();
            }
        }catch (\Exception $e){
            Db::rollback();
            throw new BaseException(['msg'=>$e->getMessage()]);
        }
    }

    /**
     * 删除模板
     * @param $id
     * @return mixed
     */
    public static function deleteDelivery($id)
    {
//         判断是否存在商品
        if ($goodsCount = Goods::where(['delivery_id' => $id])->count()) {
            $msg= '该模板被' . $goodsCount . '个商品使用，不允许删除';
            return app('json')->fail($msg);
        }
        $res=self::where('id',$id)->delete();
        DeliveryRule::where('delivery_id',$id)->delete();
        if($res){
            return app('json')->success();
        }else{
            return app('json')->fail();
        }
    }

    /**
     * 查看快递模板
     * @return int
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */

    public static function selectDelivery(){
        $res= self::with('rule')->select();
        return $res;
    }


    /**
     * 关联配送模板区域及运费
     * @return \think\model\relation\HasMany
     */
    public function rule()
    {
        return $this->hasMany('DeliveryRule','delivery_id','id');
    }


    /**
     * 运费模板计算快递费
     * @param $region_id
     * @param $data
     * @return int
     * @throws BaseException
     */
    public static function computeShipping($region_id, $data)
    {
        $arr = [];
        $shipping = 0;
        foreach ($data as $k => $v) {
            $goods = Goods::with('delivery.rule')->where('goods_id', $v['goods_id'])->field('goods_id,delivery_id')->find();
            if(!$goods){
                throw new BaseException(['msg'=>'商品不存在']);
            }

            foreach ($goods['delivery']['rule'] as $key => $value) {
                if (in_array($region_id, $value['region'])) {
                    if (!$arr) {
                        $value['num'] = $v['num'];
                        array_push($arr, $value);
                    } else {
                        $state = 0;
                        foreach ($arr as $key1 => $item) {
                            if ($value['rule_id'] == $item['rule_id']) {
                                $arr[$key1]['num'] += $v['num'];
                                $state = 1;
                            }
                            if ((count($arr) - 1) == $key1) {
                                if ($state == 0) {
                                    $value['num'] = $v['num'];
                                    array_push($arr, $value);
                                }
                            }
                            unset($arr[$key1]['region']);
                        }
                    }
                }
            }
        }
        foreach ($arr as $k => $v) {
            if ($v['additional'] && $v['num'] > $v['first']) {
                $money = $v['first_fee'] + ceil(($v['num'] - $v['first']) / $v['additional']) * $v['additional_fee'];
            } else {
                $money = $v['first_fee'];
            }
            $shipping += $money;
        }
        return $shipping;
    }
}