<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/15 0015
 * Time: 16:39
 */

namespace app\model;


use bases\BaseModel;
use exceptions\BaseException;
use exceptions\OrderException;
use think\facade\Db;
use think\facade\Log;
use think\Request;

class UserAddress extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'user_id'];


    /**
     * 创建用户地址
     * @param $uid
     * @param $post
     * @return mixed
     * @throws BaseException
     */
    public static function CreateOne($uid, $post)
    {
        $AddressModel = new UserAddress;
        $count = $AddressModel->where('user_id', $uid)->count();
        if ($count >= 5) {
            return app('json')->fail('最多只能创建5个地址');
        } else {
            Db::startTrans();
            try {
                $post['user_id'] = $uid;
                $post['is_default'] = 1;
                $region=Db::name('region')->where('name',$post['county'])->where('level',3)->find();
                if(!$region){
                    return app('json')->fail('地区不存在');
                }
                $post['region_id']=$region['pid'];
                Log::error(json_encode($post));
                $AddressModel->save($post); //直接通过关联模型来新增
                self::setOnlyAddress($AddressModel->id, $AddressModel->user_id);//将指定地址设为唯一
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();// 回滚事务
                throw new BaseException(['msg' => $e->getMessage()]);
            }
            return app('json')->success();
        }
    }

    /**
     * 修改地址
     * @param $id
     * @param $uid
     * @param $data
     * @return int
     */
    public static function editAddress($id, $uid, $data)
    {
        $res = self::where(['id' => $id, 'user_id' => $uid])->find();
        unset($data['id']);
        if (!$res) {
            return app('json')->fail('地址不存在');
        }
        $status = $res->save($data);
        if (!$status) {
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 获取指定地址
     * @param $id
     * @param $uid
     * @return array|null|\think\Model
     */
    public static function getAddressOne($id, $uid)
    {
        $data = self::where(['id' => $id, 'user_id' => $uid])->find();
        if (!$data) {
            return app('json')->fail('地址不存在');
        }
        return app('json')->success($data);
//        return $data;
    }

    /**
     * 将指定地址设为唯一
     * @param $id
     * @param $uid
     * @return static
     */
    public static function setOnlyAddress($id, $uid)
    {
        self::where("user_id", $uid)->where("id", "<>", $id)->update(["is_default" => 0]);
        return self::where("user_id", $uid)->where("id", $id)->update(["is_default" => 1]);
    }

    /**
     * 默认地址
     * @param $uid
     * @return array
     * @throws BaseException
     */
    public static function getUserInfo($uid)
    {

        $data = [];
        $address = self::where('user_id', $uid)->where('is_default', 1)->find();
        if (!$address) {
            throw new OrderException(['msg' => '地址错误']);
        }
        $data['receiver_name'] = $address['name'];
        $data['receiver_mobile'] = $address['mobile'];
        //$data['receiver_province'] = $address['province'];
        //$data['receiver_district'] = $address['country'];
        $data['receiver_city'] = $address['province'] . $address['city'] . $address['county'];
        $data['receiver_address'] = $address['detail'];
        $data['region_id'] = $address['region_id'];
        return $data;
    }
}