<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/12/31 0031
 * Time: 11:28
 */

namespace app\controller\cms;


use app\model\Video;
use bases\BaseController;

class VideoManage extends BaseController
{
    /**
     * 获取所有视频
     * @return mixed
     */
    public function getAllVideo(){
        $res=Video::where('is_visible',1)->order('id','desc')->select();
        return app('json')->success($res);
    }

    /**
     * 删除视频
     * @param $ids
     * @return mixed
     */
    public function editVideo($ids){
        $res=Video::where(['id'=>$ids])->update(['is_visible'=>0]);
        if($res){
            return app('json')->success();
        }else{
            return app('json')->fail();
        }
    }
}