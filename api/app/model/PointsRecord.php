<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/29 0029
 * Time: 15:41
 */

namespace app\model;


use bases\BaseModel;
use think\facade\Db;

class PointsRecord extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('User', 'uid', 'id')->field('id,nickname,headpic,points');
    }

    /**
     * 签到获取积分
     * @param $uid
     * @return mixed
     *
     */
    public function sign($uid)
    {
        $res = User::where('id', $uid)->find();
        $time = $res['sign_time'] -strtotime(date('Y-m-d'));
        if ($time > 0) {
            return app('json')->fail('今天已经签到过');
        }
        $last_sign = intval((strtotime(date('Y-m-d', time())) - $res['sign_time']) / (60 * 60 * 24));
        if (!$last_sign && $res['sign_day'] < 7) {
            $day =$res['sign_day'] + 1;
            $name= $this->numeral($res['sign_day']);
        } else {
            $day = 1;
            $name= $this->numeral(0);
        }
        $points=PlayAward::where('name',$name)->value('award');
        $data['points'] = $res['points'] + $points;
        $data['sign_time'] = time();
        $data['sign_day'] = $day;
        try {
            Db::startTrans();
            $res->save($data);
            self::addRecord($uid, $points, '签到');//积分记录
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return app('json')->success();
        }
        return app('json')->success();
    }


    /**
     * 添加积分变动
     * @param $uid
     * @param $num
     * @param $from
     * @return mixed
     */
    public static function addRecord($uid, $num, $from)
    {
        $data['uid'] = $uid;
        $data['credittype'] = 'points';
        $data['num'] = $num;
        $data['module'] = 'qy2019_shop';
        $data['clerk_type'] = 1;
        $data['remark'] = $from . '获得积分，增加' . $num . '积分';
        self::create($data);
    }

    /**
     * 使用积分变动
     * @param $uid
     * @param $num
     * @param $from
     * @return mixed
     */
    public static function reduce($uid, $num, $from)
    {
        $data['uid'] = $uid;
        $data['credittype'] = 'points';
        $data['num'] = -$num;
        $data['module'] = 'qy2019_shop';
        $data['clerk_type'] = 1;
        $data['remark'] = '使用积分' . $from . '，减少' . $num . '积分';
        $res = self::create($data);
        if (!$res) {
            return app('json')->fail();
        }
        return app('json')->success($res['id']);
    }

    //阿拉伯转中文数字
     private function numeral($num)
    {
        $china = array('第一天', '第二天', '第三天', '第四天', '第五天', '第六天', '第七天');
        return $china[$num];
    }
}