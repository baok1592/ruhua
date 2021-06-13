<?php
/**ssss
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/1/10 0010
 * Time: 16:51
 */

namespace app\services;


use app\model\FxBind as FxBindModel;
use app\model\FxRecord as FxRecordModel;
use app\model\Order as OrderModel;
use app\model\User as UserModel;
use app\model\UserAddress as UserAddressModel;
use app\model\UserCoupon as UserCouponModel;
use app\model\VipUser as VipUserModel;
use think\facade\Log;

class MergeService
{
    /**
     * 合并两个用户
     * @param $main_uid --主账号
     * @param $field    --xcx,gzh,app,oponid字段名
     * @param $openid   --需要合并删除的oponId。
     * @param $type --1手机关联，2平台uniobid关联
     * @return string
     */
    public function mergeUser($main_uid, $field, $openid, $type)
    {
        $where[$field] = $openid;
        if ($type == 1) {
            $where['mobile'] = null;
        } else if ($type == 2) {
            $where['unionid'] = null;
        }
        $res = UserModel::where($where)->find();
        if (!$res) {
            return '';
        }
        //会员
        $vip_state = $this->mergeVip($main_uid, $res['id']);
        if (!$vip_state) {
            return '状态错误';
        }
        //积分
        $points = $this->mergePoints($main_uid, $res['id']);
        //优惠券
        $coupon = $this->mergeCoupon($main_uid, $res['id']);
        //订单
        $order = $this->mergeOrder($main_uid, $res['id']);
        //分销
        $fx = $this->mergeFx($main_uid, $res['id']);
        //绑定下线
        $bind = $this->mergeBind($main_uid, $res['id']);
        //删除地址
        $address = $this->mergeAddress($main_uid, $res['id']);
        Log::channel('mergeLog')->write($res['id'] . '合并为' . $main_uid . ($points ? '，积分：' . $points : '') . ($coupon ? ' 优惠券：' .
                $coupon : '') . ($order ? ' 订单：' . $order : '') . ($fx ? ' 分销：' . $fx : '') . ($bind ? ' 下线：' . $bind : '') . ($address ? ' 删除地址' : ''));
//        $res->delete();
    }

    public function mergeVip($main_uid, $uid)
    {
        $main_uid_vip = VipUserModel::where('user_id', $main_uid)->find();
        $uid_vip = VipUserModel::where('user_id', $uid)->find();
        //两端都拥有会员并且未过期：不能合并
        if ($main_uid_vip && $uid_vip && $main_uid_vip['end_time'] > time() && $uid_vip['end_time'] > time()) {
            return 0;
        }
        if ($uid_vip['end_time'] > time()) {
            $uid_vip['user_id'] = $main_uid;
            $uid_vip->save();
            $main_uid_vip->delete();
        }
        return 1;
    }


    public function mergePoints($main_uid, $uid)
    {
        $main_uid_points = UserModel::where('id', $main_uid)->find();
        $uid_points = UserModel::where('id', $uid)->find();
        $main_uid_points['points'] += $uid_points['points'];
        $main_uid_points->save();
        return $uid_points['points'];
    }

    public function mergeCoupon($main_uid, $uid)
    {
        $uid_coupon = UserCouponModel::where(['user_id' => $uid, 'status' => [0, 1]])->select();
        $arr = '';
        foreach ($uid_coupon as $k => $v) {
            UserCouponModel::where('id', $v['id'])->update(['user_id' => $main_uid]);
            if ($k == 0) {
                $arr = $v['id'];
            } else {
                $arr = ',' . $v['id'];
            }
        }
        return $arr;
    }

    public function mergeOrder($main_uid, $uid)
    {
        $uid_order = OrderModel::where(['user_id' => $uid])->select();
        $arr = '';
        foreach ($uid_order as $k => $v) {
            OrderModel::where(['order_id' => $v['order_id']])->update(['user_id' => $main_uid]);
            if ($k == 0) {
                $arr = $v['order_id'];
            } else {
                $arr = ',' . $v['order_id'];
            }
        }
        return $arr;
    }


    public function mergeFx($main_uid, $uid)
    {
        $uid_fx_record = FxRecordModel::where(['user_id' => $uid])->select();
        $arr = '';
        foreach ($uid_fx_record as $k => $v) {
            FxRecordModel::where('id', $v['id'])->update(['user_id' => $main_uid]);
            if ($k == 0) {
                $arr = $v['id'];
            } else {
                $arr = ',' . $v['id'];
            }
        }
        return $arr;
    }

    public function mergeBind($main_uid, $uid)
    {
        $uid_fx_bind = FxBindModel::where(['user_id' => $uid])->select();
        $arr = '';
        foreach ($uid_fx_bind as $k => $v) {
            FxBindModel::where('id', $v['id'])->update(['user_id' => $main_uid]);
            if ($k == 0) {
                $arr = $v['id'];
            } else {
                $arr = ',' . $v['id'];
            }
        }
        return $arr;
    }

    public function mergeAddress($main_uid, $uid)
    {
        $uid_address = UserAddressModel::where(['user_id' => $uid])->delete();
        return $uid_address ? 1 : 0;
    }
}