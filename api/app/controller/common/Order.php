<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/18 0018
 * Time: 15:42
 */

namespace app\controller\common;


use app\services\OrderService;
use app\services\TokenService;
use app\validate\ShoppingValidate;
use bases\BaseController;
use exceptions\BaseException;
use think\facade\Log;

class Order extends BaseController
{
    /**
     * 普通商品下单 - 公众号
     * @return \GzhPay\json数据，可直接填入js函数作为参数|string
     */
    public function CreateCartOrder()
    {
        (new ShoppingValidate())->goCheck();
        $post=input('post.');
        $res = (new OrderService)->createGzhOrder($post);//创建订单
        Log::error('end');
        return app('json')->success($res);
    }

    /**
     * 普通商品下单 - 小程序
     * @return mixed
     */
    public function CreateXcxOrder()
    {
        (new ShoppingValidate())->goCheck();
        $post=input('post.');
        $uid = TokenService::getCurrentUid();
        $data =  (new OrderService)->CreateCartOrder($post, $uid);//创建订单
        return app('json')->success($data['id']);

    }


}