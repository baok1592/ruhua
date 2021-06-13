<?php
// 事件定义文件
return [
    'bind' => [
//        'CreateOrder' => 'events\OrderEvent',
    ],

    'listen' => [
        'AppInit' => ['app\\behavior\\CORS'],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        //创建订单
        'CreateOrder' => [
            'events\listens\CheckStyle',  //检测是否有虚拟商品
            'events\listens\CheckStock',  //库存是否满足
            'events\listens\CheckCoupon',   //优惠券是否符合满减
           // '',  //更新优惠券为使用中
        ],
        //商品订单支付成功
        'PayOrder' => [
            '',  //减库存
            '',  //模板消息通知用户
            '',  //模板消息通知管理员
        ],
        //发货或核销
        'DeliverProduct' => [
            '',  //模板消息通知用户
        ],
        //订单完成
        'EndOrder' => [
            'events\listens\editCoupon',  //优惠券：使用中变已使用
            'events\listens\editSales',  //商品销量+1
            'events\listens\addPoints',  //增加积分
//            '',  //分销商抽佣
//            '',  //企业微信付款到零钱
        ],
        //订单评价
        'AppraisesOrder' => [
            '',  //更新商品评分
        ],
        //退款
        'BackOrder' => [
            '',  //库存+1
            '',  //更新优惠券为待使用
            '',  //模板消息通知用户
        ],
        //关闭订单
        'ShutOrder' => [
            '',  //库存+1
            '',  //更新优惠券为待使用
        ],
        //'UserLogin' => ['app\listener\OrderListen', ''],
//        'CheckFx'=>[]
    ],

    'subscribe' => [
        subscribes\FxSubscribes::class,
        subscribes\OrderSubscribes::class,
    ],
];
