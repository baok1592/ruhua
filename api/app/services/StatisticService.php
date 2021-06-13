<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/30 0030
 * Time: 14:23
 */

namespace app\services;

use app\model\FxRecord;
use app\model\Goods as GoodsModel;
use app\model\Order as OrderModel;
use app\model\User as UserModel;

class StatisticService
{

    /**
     * 待处理事项统计
     * @return mixed
     */
    public static function remind()
    {
        $shipment = OrderModel::where(['state' => 0, 'payment_state' => 1, 'shipment_state' => 0])->field('count(order_id) as all_num')->find();
        $refund = OrderModel::where(['state' => -1, 'payment_state' => 1])->field('count(order_id) as all_num')->find();
        $goods_stock = GoodsModel::getGoodsStock();
        $data['shipment'] = $shipment['all_num'] ? $shipment['all_num'] : 0;
        $data['tui'] = $refund['all_num'] ? $refund['all_num'] : 0;
        $data['goods_stock'] = $goods_stock ? $goods_stock : 0;
        $data['total'] = 0;
        foreach ($data as $v) {
            $data['total'] += $v;
        }
        return app('json')->success($data);
    }

    /**
     * mcms订单统计
     * @return mixed
     */
    public static function getOrderNum()
    {
        $all = OrderModel::where(['state' => 1, 'payment_state' => 1])->field('count(order_id) as all_order,sum(order_money) as all_money')->find();
        $today = OrderModel::where(['state' => 1, 'payment_state' => 1])->whereDay('pay_time')->field('count(order_id) as today_order,sum(order_money) as today_money')->find();
        $yesterday = OrderModel::where(['state' => 1, 'payment_state' => 1])->whereDay('pay_time', 'yesterday')->field('count(order_id) as yesterday_order,sum(order_money) as yesterday_money')->find();
        $data['all_order'] = $all['all_order'] ? $all['all_order'] : 0;
        $data['all_money'] = $all['all_money'] ? $all['all_money'] : 0;
        $data['today_order'] = $today['today_order'] ? $today['today_order'] : 0;
        $data['today_money'] = $today['today_money'] ? $today['today_money'] : 0;
        $data['yesterday_order'] = $yesterday['yesterday_order'] ? $yesterday['yesterday_order'] : 0;
        $data['yesterday_money'] = $yesterday['yesterday_money'] ? $yesterday['yesterday_money'] : 0;
        return app('json')->success($data);
    }

    /**
     * cms首页数据统计
     * @return mixed
     */
    public static function getCmsIndexData()
    {
        $order = new OrderModel();
        $user = new UserModel();
        $shipment = $order->where(['state' => 0, 'payment_state' => 1, 'shipment_state' => 0])->field('count(order_id) as all_num')->find();
        $refund = $order->where(['state' => -1, 'payment_state' => 1])->field('count(order_id) as all_num')->find();
        $goods_stock = GoodsModel::getGoodsStock();
        $yesterday = $order->where(['state' => 1, 'payment_state' => 1])->whereDay('pay_time', 'yesterday')
            ->field('count(order_id) as yesterday_order,sum(order_money) as yesterday_money')->find();
        $month_order = $order->where(['state' => 1, 'payment_state' => 1])->whereMonth('pay_time')
            ->field('count(order_id) as month_order,sum(order_money) as month_money')->find();
        $today_user = $user->whereDay('create_time')->field('count(id) as all_user')->find();
        $month_user = $user->whereMonth('create_time')->field('count(id) as all_user')->find();

        $data['shipment'] = $shipment['all_num'] ? $shipment['all_num'] : 0;
        $data['tui'] = $refund['all_num'] ? $refund['all_num'] : 0;
        $data['goods_stock'] = $goods_stock ? $goods_stock : 0;
        $data['month_order'] = $month_order['month_order'] ? $month_order['month_order'] : 0;
        $data['month_money'] = $month_order['month_money'] ? $month_order['month_money'] : 0;
        $data['yesterday_order'] = $yesterday['yesterday_order'] ? $yesterday['yesterday_order'] : 0;
        $data['yesterday_money'] = $yesterday['yesterday_money'] ? $yesterday['yesterday_money'] : 0;
        $data['today_user'] = $today_user['all_user'] ? $today_user['all_user'] : 0;
        $data['month_user'] = $month_user['all_user'] ? $month_user['all_user'] : 0;
        return app('json')->success($data);
    }

    /**
     * cms图表统计
     * @return mixed
     */
    public static function getTableData()
    {
        $month = input('post.month');
        $time = self::getTime($month);
        $order = OrderModel::where('payment_state', 1)
            ->where('state', '>', '-1')
            ->whereMonth('pay_time', $time['time'])
            ->select();
        $user = UserModel::whereMonth('create_time', $time['time'])
            ->select();
        for ($i = 0; strtotime($time['time']) < strtotime($time['last_time']); $i++) {
            $data[$i]['day'] = $time['time'];
            $data[$i]['order_num'] = 0;
            $data[$i]['user_num'] = 0;
            $time['time'] = date('Y-m-d', strtotime('+1 day', strtotime($time['time'])));
        }
        foreach ($order as $k => $v) {
            $day = strtotime(date('Y-m-d', $v['pay_time']));
            foreach ($data as $key => $value) {
                if (strtotime($value['day']) == $day) {
                    $data[$key]['order_num'] += 1;
                }
            }
        }
        foreach ($user as $k => $v) {
            $day = strtotime(date('Y-m-d', strtotime($v['create_time'])));
            foreach ($data as $key => $value) {
                if (strtotime($value['day']) == $day) {
                    $data[$key]['user_num'] += 1;
                }
            }
        }
        return app('json')->success($data);
    }

    /**
     * 统计订单数据
     * @return mixed
     */
    public static function getOrderData()
    {
        $month = input('post.month');
        $time = self::getTime($month);
        $data['normal_order'] = 0;
        $data['discount_order'] = 0;
        $data['pt_order'] = 0;
        $order = OrderModel::where('payment_state', 1)
            ->where('state', '>=', '1')
            ->whereMonth('pay_time', $time['time'])
            ->select();
        foreach ($order as $k => $v) {
            if ($v['activity_type'] == '限时优惠') {
                $data['discount_order'] += 1;
            } else if ($v['activity_type'] == '拼团活动') {
                $data['pt_order'] += 1;
            } else {
                $data['normal_order'] += 1;
            }
        }
        return app('json')->success($data);
    }

    /**
     * cms图表统计订单
     * @return mixed
     */
    public static function getMoneyData()
    {
        $month = input('post.month');
        $time = self::getTime($month);
        $order = OrderModel::where('payment_state', 1)
            ->where('state', '>=', '1')
            ->whereMonth('pay_time', $time['time'])
            ->select();
        for ($i = 0; strtotime($time['time']) < strtotime($time['last_time']); $i++) {
            $data[$i]['day'] = $time['time'];
            $data[$i]['total_price'] = 0;
            $data[$i]['total_order'] = 0;
            $data[$i]['profit'] = 0;
            $time['time'] = date('Y-m-d', strtotime('+1 day', strtotime($time['time'])));
        }
        foreach ($order as $k => $v) {
            $day = strtotime(date('Y-m-d', $v['pay_time']));
            foreach ($data as $key => $value) {
                if (strtotime($value['day']) == $day) {
                    $data[$key]['total_price'] += $v['goods_money'];
                    $data[$key]['total_order'] += 1;
                }
            }
        }
        return app('json')->success($data);
    }

    private static function getTime($month)
    {
        if ($month) {
            $start = date('Y-m', $month);
            $end = date('Y-m', strtotime('+1 month', $month));
        } else {
            $start = date('Y-m', time());
            $end = date('Y-m', strtotime('+1 month', time()));
        }

        $date['time'] = $start;
        $date['last_time'] = $end;
        return $date;
    }

    /**
     * 分销佣金统计排名
     * @return mixed
     */
    public static function countFx()
    {
        $month = input('post.month');
        $time = self::getTime($month);
        $data = FxRecord::with(['agent', 'user'])->whereMonth('create_time', $time['time'])->select();
        $arr = [];
        $res = [];
        foreach ($data as $k => $v) {
            if (!$arr) {
                $arr['agent_id'] = $v['agent_id'];
                $arr['num'] = 1;
                $arr['all_money'] = $v['money'];
                $arr['nickname'] = $v['agent']['nickname'];
                $arr['headpic'] = $v['agent']['headpic'];
                $arr['finish_money'] = 0;
                $arr['money'] = 0;
                if ($v['status'] == 2) {
                    $arr['finish_money'] = $v['money'];
                } else {
                    $arr['money'] = $v['money'];
                }
                array_push($res, $arr);
                continue;
            }
            foreach ($res as $kk => $vv) {
                if ($v['agent_id'] == $vv['agent_id']) {
                    $res[$kk]['num'] += 1;
                    $res[$kk]['all_money'] += $v['money'];
                    if ($v['status'] == 2) {
                        $res[$kk]['finish_money'] += $v['money'];
                    }else {
                        $res[$kk]['money'] += $v['money'];
                    }
                    break;
                }
                if ($kk + 1 == count($res)) {
                    $arr['agent_id'] = $v['agent_id'];
                    $arr['num'] = 1;
                    $arr['all_money'] = $v['money'];
                    $arr['nickname'] = $v['agent']['user']['nickname'];
                    $arr['headpic'] = $v['agent']['user']['headpic'];
                    $arr['finish_money'] = 0;
                    $arr['money'] = 0;
                    if ($v['status'] == 2) {
                        $arr['finish_money'] = $v['money'];
                    } else {
                        $arr['money'] = $v['money'];
                    }
                    array_push($res, $arr);
                }
            }
        }
        for ($i = 0; $i < count($res); $i++) {
            for ($j = $i; $j < count($res); $j++) {
                if ($res[$i]['num'] < $res[$j]['num']) {
                    $t = $res[$j];
                    $res[$j] = $res[$i];
                    $res[$i] = $t;
                }
            }
        }
        return app('json')->success($res);
    }

}