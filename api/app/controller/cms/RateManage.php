<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 11:12
 */

namespace app\controller\cms;


use app\model\Rate as RateModel;
use app\services\TaskService;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use bases\BaseController;

class RateManage extends BaseController
{
    /**
     * cms 获取所有评价
     * @return \think\Collection
     */
    public function getAllRate(){
        $data = RateModel::with(['user','goods'])->order('create_time desc')->select();
        return app('json')->success($data);
    }

    /**
     * cms 删除评论
     * @param $id
     * @return int
     */
    public function deleteRate($id)
    {
        (new IDPostiveInt())->goCheck();
        $result= RateModel::where('id',$id)->delete();//软删除
        if(!$result){
            return app('json')->fail();
        }
        return app('json')->success();
    }

    /**
     * 回复
     * @return mixed
     */
    public function addReply(){
        $aid=TokenService::getCurrentAid();
        $rule=['id'=>'require|number','reply_content'=>'require',];
        $post=input('post.');
        $this->validate($post,$rule);
        return RateModel::addReply($post,$aid);
    }

    /**
     * cms添加评价
     * @return mixed
     */
    public function addRate(){
        $rule=[
            'goods_id'=>'require|number',
            'rate'=>'require|number',
        ];
        $post=input('post.');
        $this->validate($post,$rule);
        return RateModel::addRate($post);
    }

}