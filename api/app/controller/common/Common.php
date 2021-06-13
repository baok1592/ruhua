<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/6 0006
 * Time: 15:31
 */

namespace app\controller\common;

use app\model\SysConfig as SysConfigModel;
use app\services\CommonServices;
use bases\BaseController;

class Common extends BaseController
{
    /**
     * 返回二维码
     * @return string
     */
    public function gitCodeImg()
    {
        $post = input('post.');
        $this->validate($post, ['path' => 'require']);
        return CommonServices::getCodeImg($post);
    }


    /**
     * 获取文件
     * @param $type
     * @return array|false|int
     */
    public function getFile($type)
    {
        $file = [];
        if ($type == 1) {
            $file = file_get_contents("./files/服务协议.txt", "r");
        }
        if ($type == 2) {
            $file = file_get_contents("./files/隐私政策.txt", "r");
        }
        if ($type == 3) {
            $file = file_get_contents("./files/授权信息.txt", "r");
        }
        return $file;
    }

    /**
     * 前端获取部分配置信息
     */
    public function getSysConfig()
    {
        // 购物车16、客服18、分销25、会员36、登录方式37、发票38
        $res = SysConfigModel::where(['id' => [16, 18, 25, 36, 37,38]])->field('key,desc,value')->select();
        return app('json')->success($res);
    }
}