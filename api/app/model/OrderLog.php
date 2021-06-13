<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/10/22 0022
 * Time: 11:02
 */

namespace app\model;


use bases\BaseModel;

class OrderLog extends BaseModel
{
    protected $insert = ['ip', 'operator'];
    protected $hidden = ['id', 'order_id'];

    protected function setIpAttr()
    {
        return request()->ip();
    }

    protected function setOperatorAttr()
    {
        try {
            $name = TokenService::getCurrentTokenVar('username');
        } catch (\Exception $e) {

        }
        if (!empty($name)) {
            return $name;
        } else {
            return '其他';
        }
    }

    /**
     * 写入发票信息
     * @param $order
     * @return string
     */
    public static function addInvoiceLog($order)
    {
        if ($order['invoice'] != 1 && $order['invoice'] != 2) {
            return '';
        }
        $invoice = User::getCpyInfo($order['user_id']);
        $invoice_data = [];
        if ($order['invoice'] == 1 && $invoice) {
            $invoice_data['type_name'] = '个人发票';
            $invoice_data['content'] = '姓名：' . $invoice['user_name'] . '，邮箱：' . $invoice['email'];
        } else if ($order['invoice'] == 2 && $invoice) {
            $invoice_data['type_name'] = '单位发票';
            $invoice_data['content'] = '发票抬头：' . $invoice['cpy_name'] . '，税号：' . $invoice['cpy_num'] . '，邮箱：' . $invoice['email'];
        }
        if ($invoice_data) {
            $invoice_data['order_id'] = $order['order_id'];
            self::create($invoice_data);
        }
    }

}