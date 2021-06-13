<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/21 0021
 * Time: 11:57
 */

namespace app\controller\user;


use app\model\Order as OrderModel;
use app\services\CommonServices;
use app\services\TokenService;
use app\validate\IDPostiveInt;
use bases\BaseController;
use services\QyFactory;

class UserOrder extends BaseController
{
    /**
     * 获取我的所有订单信息
     * @return mixed
     */
    public function getUserOrderAll()
    {
        $order = (new QyFactory())->instance('UserService');
        $data = $order->get_order_list();
        return app('json')->success($data);
    }


    /**
     * 统计订单数据
     * @return mixed
     */
    public function getOrderDate(){
        $uid = TokenService::getCurrentUid();
        $data['no_payment'] = OrderModel::where(['user_id' => $uid,'state'=>0,'payment_state'=>0])->count();
        $data['no_shipment'] = OrderModel::where(['user_id' => $uid,'state'=>0,'payment_state'=>1,'	shipment_state'=>0])->count();
        $data['no_pj'] = OrderModel::where(['user_id' => $uid,'state'=>1])->count();
        return app('json')->success($data);
    }

    /**
     * 获取用户指定的一条订单信息
     * @param string $id
     * @return mixed
     */
    public function getUserOrderOne($id = "")
    {
        (new IDPostiveInt())->goCheck();
        $order = (new QyFactory())->instance('UserService');
        $data = $order->get_order_detail($id);
        return app('json')->success($data);
    }

    /**
     * 删除一条自己未支付的订单
     * @param string $id
     * @return int
     */
    public function deleteOrder($id = "")
    {
        (new IDPostiveInt)->goCheck();
        $uid = TokenService::getCurrentUid();
        return OrderModel::deleteOrder($id, $uid);
    }

    /**
     * 搜索订单
     * @param $name
     * @return mixed
     */
    public function SearchOrder($name='')
    {
        $post=input('post.');
        $this->validate($post, ['name' => 'require|max:10']);
        $uid = TokenService::getCurrentUid();
        $data = OrderModel::where('user_id', $uid)->where('goods_name', 'like', '%' . $name . '%')->select();
        return app('json')->success($data);
    }

    /**
     * 提交评价
     * @return mixed
     */
    public function set_pj()
    {
        $uid=TokenService::getCurrentUid();
        $rule=[
            'id' => 'require|number',
            'goods_id' => 'require',
            'rate' => 'require',
            'content' => 'require',
            'imgs' => 'min:0',
        ];
        $post = input('post.');
        $this->validate($post,$rule);
        return OrderModel::setPj($uid,$post);
    }

    /**
     * 查看退款金额
     * @return mixed
     */
    public function getTuiMoney(){
        $uid=TokenService::getCurrentUid();
        $post = input('post.');
        $this->validate($post,['order_id' => 'require|number', 'goods_id' => 'require',]);
        $res= (new OrderModel)->getTuiMoney($uid,$post['order_id'],$post['goods_id']);
        if(is_numeric($res)){
            return app('json')->success($res);
        }
        return app('json')->fail($res);
    }
    /**
     * 提交退款申请
     * @return mixed
     */
    public function tuikuan_approve()
    {
        $post = input('post.');
        $uid = TokenService::getCurrentUid();
        return (new OrderModel)->tuikuan_approve($uid,$post);

    }

    /**
     * 确认收货
     * @param $id
     * @return mixed
     */
    public function receive($id)
    {
        (new IDPostiveInt)->goCheck();
        $uid=TokenService::getCurrentUid();
        return OrderModel::receive($id,$uid);
    }

    /**
     * 物流快递查询
     * @param string $id
     * @return mixed
     */
    public function getCourier($id='') {
        (new IDPostiveInt)->goCheck();
        return CommonServices::getCourier($id);
    }

}