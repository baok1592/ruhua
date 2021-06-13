<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/1 0001
 * Time: 9:08
 */

namespace app\model;


use bases\BaseModel;

class FxBind extends BaseModel
{

    public function user()
    {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,headpic,mobile');
    }

    /**
     * 添加绑定
     * @param $sfm
     * @param $uid
     * @param $type
     * @return mixed
     */
    public static function addBind($sfm, $uid, $type)
    {
        if (!$sfm) {
            return app('json')->fail('无推荐人');
        }
        $res = self::where('user_id', $uid)->find();//有没有上线
        if ($res && $res['is_bind'] == 1) {
            return app('json')->fail('已绑定');
        }
        $agent = FxAgent::where('invite_code', $sfm)->find();
        if ($agent) {
            if ($res) {
                $res['pid'] = $agent['user_id'];
                $res['is_bind'] = $type;
                $res->save();
            } else {
                self::create(['user_id' => $uid, 'pid' => $agent['user_id'],'is_bind'=>$type]);
            }
            return app('json')->success();
        }
        return app('json')->fail('邀请码不存在');
    }

    /**
     * 支付成功确认绑定
     * @param $uid
     * @return mixed
     */
    public static function editBind($uid)
    {
        $res = self::where('user_id', $uid)->find();//有没有上线
        if (!$res || $res['is_bind'] == 1) {
            return app('json')->fail();
        }
        $res['is_bind'] = 1;
        $res->save();
    }

    /**
     * 用户查看分销统计
     * @param $agent_id
     * @return mixed
     */
    public static function getBindUser($agent_id)
    {
        $data = self::with('user')->where('pid', $agent_id)->where('is_bind', 1)->select();
        return app('json')->success($data);
    }
}