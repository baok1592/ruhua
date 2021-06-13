<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;


Route::get('', function () {
    echo '如花商城系统'.VAE_VERSION.'(商业版)';
});

Route::get('api_check', 'install.Check/index');

//系统安装
Route::group('install', function () {
    Route::get('', 'install.Index/step1');
    Route::get('step2', 'install.Index/step2');
    Route::get('step3', 'install.Index/step3');
    Route::post('create_data', 'install.Index/createData');
});
Route::get('test_s', 'install.UpDate/test');//验证权限
//系统更新
Route::group('update', function () {
    Route::get('get_rh_file', 'install.UpDate/getRhFile');//获取更新包
    Route::get('get_version', 'install.UpDate/getVersion');//获取版本信息
    Route::get('get_url', 'install.UpDate/getResource');//获取下载url
})->middleware('CheckCms');


//定时任务
Route::group('task', function () {
    Route::get('day_task', 'common.Task/getDayRefresh');  //1天定时任务
    Route::get('refresh', 'common.Task/getRefresh');  //3小时定时任务
    Route::get('order_task', 'common.Task/getOrderTask');  //关闭未支付订单、拼团失败订单定时任务
    Route::get('check_order_task', 'common.Task/checkLoopTask');  //查看系统自动关闭订单运行状态
    Route::get('loop_task', 'common.Task/getLoopTask');  //循环任务，关闭未支付订单、拼团失败订单
});

//微信授权获取token
Route::group('auth', function () {
    Route::get('wxcode_url', 'auth.Auth/wxcodeUrl');   //请求公众号code
    Route::post('get_xcx_token', 'auth.Auth/getXcxToken');   //小程序获取用户token
    Route::post('token_verify', 'auth.Auth/verifyToken');   //验证用户token
    Route::get('gzh_token', 'auth.Auth/getToken');   //异步接收公众号code,获取openid，返回token
    Route::post('upinfo', 'auth.Auth/xcx_upinfo');   //更新用户昵称、头像
    Route::post('get_app_token', 'auth.Auth/getAppToken');   //app获取用户token

    Route::post('get_login_code', 'auth.Mobile/getLoginCode');   //获取手机登录的验证码
    Route::post('get_mobile_token', 'auth.Mobile/getMobileToken');   //获取手机登录的token

    Route::post('get_code', 'user.UserMobile/getMobileCode');   //获取手机绑定的验证码
    Route::post('bind_mobile', 'user.UserMobile/addMobileBind');   //绑定用户

    Route::post('bind_wx_mobile', 'user.UserMobile/getWxMobile');   //获取微信的手机号并绑定
//    Route::get('test_get_token', 'auth.Auth/testGetToken');   //合并测试
});

//公共
Route::group('index', function () {


    Route::group('', function () {
        Route::post('/get_code_img', 'common.Common/gitCodeImg');   //生成二维码
        Route::post('/upload/img', 'cms.Common/uploadImg');   //上传图片
        Route::post('/upload/img_id', 'cms.Common/uploadImgID');   //上传图片返还ID
        Route::post('/upload/down_img', 'cms.Common/downImg');   //下载图片
        Route::get('get_file', 'common.Common/getFile');  //获取文件
    });

    //用户
    Route::group('user', function () {
        Route::get('sys_config', 'common.common/getSysConfig');//前端获取部分配置信息
    });

    //管理员
    Route::group('admin', function () {
        Route::post('login', 'cms.Admin/login');//管理员登录
        Route::get('check_login', 'cms.Common/checkLogin');//管理员检查是否登录
        Route::get('get_code', 'cms.Admin/getCode');//获取验证码


        Route::any('ue_uploads', 'cms.Common/ue_uploads');
    });
});

//文章、公告Article
Route::group('article', function () {
    //公共
    Route::group('', function () {
        Route::get('get_all_article', 'common.Article/getAllArticle');//获取所有的文章
        Route::get('get_article', 'common.Article/getArticle');//获取一篇公告
        Route::get('get_one_article', 'common.Article/getOneArticle');//获取文章详情
        Route::get('user_article', 'common.Article/userArticle');//用户获取文章
        Route::get('type_article', 'common.Article/getTypeArticle');//用户获取某个类型的文章
    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_all_article', 'cms.ArticleManage/adminGetAllArticle');//获取所有的文章
        Route::get('all_article_name', 'cms.ArticleManage/allArticleName');//获取所有的文章
        Route::post('add_article', 'cms.ArticleManage/addArticle');//添加文章
        Route::post('edit_article', 'cms.ArticleManage/editArticle');//修改文章
        Route::put('del_article', 'cms.ArticleManage/deleteArticle');//删除文章

    })->middleware('CheckCms');
});

//广告banner
Route::group('banner', function () {
    //公共
    Route::group('', function () {
        Route::get('get_banner', 'common.Banner/getBannerItem');//获取某个广告
        Route::get('get_all_banner', 'common.Banner/getAllBanner');//获取所有广告位
        Route::get('banner_all_item', 'common.Banner/banner_all_item');//获取所有广告
        Route::get('get_banner_content', 'common.Banner/get_banner_content');//获取所有广告详情
    });

    //管理员
    Route::group('admin', function () {
        Route::get('banner_all_item', 'cms.BannerManage/adminAllBanner');//cms获取所有广告
        Route::post('add_banner', 'cms.BannerManage/addBanner');//添加广告
        Route::post('edit_banner', 'cms.BannerManage/editBanner');//修改广告
        Route::put('del_banner', 'cms.BannerManage/deleteBanner');//删除广告
        Route::post('set_sort', 'cms.BannerManage/setSort');//更新广告排序

    })->middleware('CheckCms');
});

//分类Category
Route::group('category', function () {
    //公共
    Route::group('', function () {
        Route::get('get_category', 'common.Category/getCateLevel');//获取X级分类信息
        Route::get('all_category', 'common.Category/getAllCategory');//获取所有分类信息
        Route::get('category_cid', 'common.Category/getCateChildImg');//获取分类下所有子类与广告图
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_category', 'cms.CategoryManage/addCategory');//添加广告
        Route::post('edit_category', 'cms.CategoryManage/editCategory');//修改广告
        Route::put('del_category', 'cms.CategoryManage/deleteCategory');//删除广告
        Route::get('all_category', 'cms.CategoryManage/getCateSort');//cms 获取所有分类并排好序，包括隐藏
        Route::post('set_sort', 'cms.CategoryManage/setSort');//更新广告排序
    })->middleware('CheckCms');
});

//导航
Route::group('nav', function () {
    //公共
    Route::group('', function () {
        Route::get('get_nav', 'cms.NavManage/getNav');//获取X级分类信息

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_nav', 'cms.NavManage/addNav');//新增导航
        Route::post('edit_nav', 'cms.NavManage/editNav');//修改导航
        Route::put('del_nav', 'cms.NavManage/deleteNav');//删除导航
        Route::get('all_nav', 'cms.NavManage/getNav');//cms 获取所有导航
        Route::post('set_sort', 'cms.NavManage/setSort');//更新排序导航
    })->middleware('CheckCms');
});

//视频
Route::group('video', function () {
    //公共}
    //管理员
    Route::group('admin', function () {
        Route::post('add_video', 'cms.Common/uploadVideo');   //上传视频
        Route::get('get_all_video', 'cms.VideoManage/getAllVideo');//获取所有视频
        Route::post('edit_video', 'cms.VideoManage/editVideo');//隐藏视频
    })->middleware('CheckCms');
});


//图片
Route::group('img_category', function () {
    //公共
    Route::group('', function () {
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_category', 'cms.ImageManage/addImageCategory');//添加分类
        Route::put('del_category', 'cms.ImageManage/deleteImageCategory');//删除分类
        Route::get('get_category', 'cms.ImageManage/getImageCategory');//获取所有分类
        Route::get('get_all_img', 'cms.ImageManage/getAllImage');//获取所有图片
        Route::post('edit_image', 'cms.ImageManage/editImage');//隐藏图片
        Route::post('/upload/img', 'cms.Common/uploadImg');   //上传图片
    })->middleware('CheckCms');
});

//分组group
Route::group('group', function () {
    //公共
    Route::group('', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_group', 'cms.Group/addGroup');//添加文章
        Route::post('edit_group', 'cms.Group/editGroup');//修改文章
        Route::put('del_group', 'cms.Group/deleteGroup');//删除文章
        Route::get('get_all_group', 'cms.Group/getAllGroup');//获取所有的分组
        Route::get('get_one_group', 'cms.Group/getOneGroup');//获取分组详情
        Route::get('get_all_rule', 'cms.Group/getAllGroupRule');//获取分组详情

    })->middleware('CheckCms');
});

//收藏favorite
Route::group('favorite', function () {
    Route::post('/get_one_fav', 'user.UserFavorites/scFavGood'); //查询商品是否被某用户收藏,参数type=shop为查询收藏商铺
    Route::get('/get_all_fav', 'user.UserFavorites/getFavorite');   //查询某用户收藏的所有商品与商铺
    Route::post('/add_fav', 'user.UserFavorites/addFavorite');  //添加收藏商品或店铺，fav_id,type,price,img_id}
    Route::put('/del_fav', 'user.UserFavorites/deleteFavorite');  //删除收藏，参数为id
});

//评价Rate
Route::group('rate', function () {
    //公共
    Route::group('', function () {
        Route::get('goods_rate', 'user.UserRate/getGoodsRate');//获取某个商品的所有评价

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_rate', 'cms.RateManage/addRate');//添加评价
        Route::post('add_reply', 'cms.RateManage/addReply');//回复
        Route::put('del_rate', 'cms.RateManage/deleteRate');//删除评价
        Route::get('get_all_rate', 'cms.RateManage/getAllRate');//获取所有评价
    })->middleware('CheckCms');
});

//营销玩法
Route::group('play', function () {

    Route::group('', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::put('edit_play', 'cms.PointsPlayManage/editPlay');//修改玩法规则
        Route::get('get_all_play', 'cms.PointsPlayManage/getAllPlay');//获取所有的玩法
        Route::put('get_one_play', 'cms.PointsPlayManage/getOnePlay');//获取某个玩法的详情

    })->middleware('CheckCms');
});

//优惠券
Route::group('coupon', function () {

    Route::group('', function () {
        Route::get('get_coupon', 'user.UserCoupon/getCoupon');//用户查看优惠券
        Route::get('add_coupon', 'user.UserCoupon/addUserCoupon');//用户领取优惠券
        Route::get('get_coupon_goods', 'common.Product/getCouponProduct');//获取优惠券能使用的商品
    });

    //用户
    Route::group('user', function () {
        Route::get('get_coupon', 'user.UserCoupon/selectUserCoupon');//用户查看自己的优惠券
        Route::post('order_coupon', 'user.UserCoupon/selectStatusCoupon');//用户查看订单可用优惠券
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_coupon', 'cms.CouponManage/addCoupon');//创建优惠券
        Route::put('del_coupon', 'cms.CouponManage/deleteCoupon');//删除优惠券
        Route::get('get_coupon', 'cms.CouponManage/getCoupon');//获取优惠券

    })->middleware('CheckCms');
});

//积分
Route::group('points', function () {

    Route::group('', function () {
        Route::get('sign', 'common.Points/signAddPoints');//签到添加积分
    });

    //用户
    Route::group('user', function () {
        Route::get('get_points', 'user.UserPoints/getPointsNum');//签到添加积分
    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_record', 'cms.PointsManage/getPointsRecord');//获取所有用户积分详细

    })->middleware('CheckCms');
});

//会员
Route::group('vip', function () {

    Route::group('', function () {
        Route::get('get_tc', 'cms.VipManage/getVipTc');//获取会员套餐
        Route::post('create_vip_order', 'user.UserVip/createVipOrder');//购买vip 下单
    });

    //用户
    Route::group('user', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_tc', 'cms.VipManage/addVipTc');//添加会员套餐
        Route::put('del_tc', 'cms.VipManage/deleteVipTc');//删除会员套餐
    })->middleware('CheckCms');
});

//分销
Route::group('fx', function () {

    Route::group('', function () {
//        Route::post('add_bind', 'user.Agent/addBind');//添加临时绑定
        Route::get('get_goods', 'cms.FxManage/selectFxGoods');//user获取分销商品
    });

    //用户
    Route::group('user', function () {
        Route::post('add_agent', 'user.Agent/addAgent');//成为分销商
        Route::get('get_fx_money', 'user.UserFx/getFxData');//用户查看分销收入统计
        Route::get('get_fx_record', 'user.UserFx/getFxRecord');//用户查看分销收入明细
        Route::get('get_fx_rank', 'user.UserFx/getFxRank');//用户查看分销收入排名
        Route::get('get_bind_user', 'user.UserFx/getBindUser');//用户查看绑定下线
        Route::post('apply_tx', 'user.UserFx/applyTx');//用户提交提现申请
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_goods', 'cms.FxManage/addFxGoods');//设置分销商品
        Route::get('get_goods', 'cms.FxManage/getFxGoods');//cms获取分销商品
        Route::post('edit_goods', 'cms.FxManage/editFxGoods');//修改分销商品
        Route::put('del_goods', 'cms.FxManage/delFxGoods');//删除分销商品
        Route::get('get_record', 'cms.FxManage/getFxRecord');//获取分销记录
        Route::post('count_fx', 'cms.FxManage/countFx');//统计分销记录
        Route::post('edit_fx_record', 'cms.FxManage/editTxApply');//手动提现完成修改记录
    })->middleware('CheckCms');
});

//限时优惠
Route::group('discount', function () {

    Route::group('', function () {
        Route::get('get_discount_goods', 'cms.DiscountManage/getDiscountGoods');//获取限时优惠商品
    });

    //用户
    Route::group('user', function () {
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_discount', 'cms.DiscountManage/addDiscount');//设置限时优惠商品
        Route::post('edit_discount', 'cms.DiscountManage/editDiscount');//修改限时优惠商品
        Route::put('del_discount', 'cms.DiscountManage/deleteDiscount');//删除限时优惠商品
        Route::get('get_discount', 'cms.DiscountManage/getDiscount');//获取限时优惠活动
    })->middleware('CheckCms');
});

//拼团
Route::group('pt', function () {

    Route::group('', function () {
        Route::get('get_pt_goods', 'cms.PtManage/getPtGoods');//获取拼团商品
        Route::post('/create_pt_item', 'common.PtOrder/createPtItemOrder');//团长拼团下单
        Route::post('/create_pt', 'common.PtOrder/createPtOrder');//团员拼团下单
        Route::get('/get_item', 'common.PtOrder/getPtItem');//获取拼团队伍
        Route::get('/get_one_item', 'common.PtOrder/getOnePtItem');//获取拼团队伍
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_pt', 'cms.PtManage/addPt');//设置拼团商品
        Route::post('edit_pt', 'cms.PtManage/editPt');//修改拼团商品
        Route::put('del_pt', 'cms.PtManage/deletePt');//删除拼团商品
        Route::get('get_pt', 'cms.PtManage/getPt');//获取拼团活动
        Route::post('notify_pt', 'cms.PtManage/NotifyPtOrder');//支付成功回调测试

    })->middleware('CheckCms');
});

//运费模板
Route::group('delivery', function () {
    //公共
    Route::group('', function () {
        Route::get('get_region', 'cms.Delivery/getRegion');//获取所有地区
    });
    //手机管理员
    Route::group('mcms', function () {
        Route::get('get_delivery', 'cms.Delivery/selectDelivery');//获取所有模板
    })->middleware('CheckMCMS');

    //管理员
    Route::group('admin', function () {
        Route::post('add_delivery', 'cms.Delivery/addDelivery');//添加模板
        Route::post('edit_delivery', 'cms.Delivery/editDelivery');//修改模板
        Route::put('del_delivery', 'cms.Delivery/deleteDelivery');//删除模板
        Route::get('get_delivery', 'cms.Delivery/selectDelivery');//获取所有模板

    })->middleware('CheckCms');
});

//用户地址Address
Route::group('address', function () {
    Route::post('add_address', 'user.UserAddress/addAddress');//添加地址
    Route::post('edit_address', 'user.UserAddress/editAddress');//修改地址
    Route::put('del_address', 'user.UserAddress/deleteAddress');//删除地址
    Route::get('get_all_address', 'user.UserAddress/getAllAddress');//获取用户所有的地址
    Route::get('get_one_address', 'user.UserAddress/getOneAddress');//获取用户某个地址详情
    Route::get('get_default_address', 'user.UserAddress/getAddressDefault');//获取默认地址
    Route::post('set_default_address', 'user.UserAddress/setUserAddressDefault');//设置默认地址
});

//搜索
Route::group('search', function () {

    Route::group('', function () {
        Route::get('record', 'common.Search/getSearchRecord');//搜索记录

    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_record', 'common.Search/addSearchGoods');//新增
        Route::put('del_record', 'common.Search/deleteSearchGoods');//删除
    })->middleware('CheckCms');
});

//订单
Route::group('order', function () {

    Route::group('', function () {
        Route::post('/create', 'common.Order/CreateXcxOrder'); //微信商品下单
        Route::post('/create_cart', 'common.Order/CreateCartOrder');//公众号下单

        Route::post('/second_pay', 'common.Pay/gzhPaySecond');   //公众号第二次支付
        Route::post('/back', 'common.Pay/gzh_back'); //公众号支付回调

        Route::post('/pay/pre_order', 'common.Pay/getPreOrder');//小程序支付
        Route::post('/pay/notify', 'common.Pay/receiveNotify');//小程序支付回调

        Route::post('/pay/pre_app', 'common.Pay/getAppPayData');//app支付
        Route::post('/pay/app_notify', 'common.Pay/appNotify');//app支付回调

        Route::post('get_kd', 'user.UserOrder/getCourier');//快递查询
    });

    Route::group('user', function () {
        Route::post('/all_order', 'user.UserOrder/getUserOrderAll'); //获取我的所有订单信息
        Route::post('/order_date', 'user.UserOrder/getOrderDate'); //统计订单数据
        Route::get('/get_order_one', 'user.UserOrder/getUserOrderOne'); //获取用户指定的一条订单信息
        Route::put('/del_order', 'user.UserOrder/deleteOrder'); //删除一条自己未支付的订单
        Route::post('/search', 'user.UserOrder/SearchOrder'); //搜索订单
        Route::post('/set_pj', 'user.UserOrder/set_pj'); //提交评价
        Route::post('/tui_kuan', 'user.UserOrder/tuikuan_approve'); //提交退款申请
        Route::post('/receive', 'user.UserOrder/receive'); //确认收货
    });

    //手机管理员
    Route::group('mcms', function () {
        Route::post('/check_drive', 'cms.OrderManage/checkDrive'); //未发货订单提醒
        Route::post('/get_order', 'cms.OrderManage/mCmsGetOrder'); //CMS获取所有订单简要
        Route::post('/get_order_one', 'cms.OrderManage/GetOrderOne'); //获取指定订单详细--CMS
        Route::post('/edit_courier', 'cms.OrderManage/editCourier'); //更新订单配送信息
        Route::get('/get_order_num', 'cms.Statistic/getOrderNum'); //订单统计

    })->middleware('CheckMCMS');

    //管理员
    Route::group('admin', function () {
        Route::put('/del_order', 'cms.OrderManage/deleteOrder'); //cms删除订单
        Route::post('/get_order', 'cms.OrderManage/getOrderAll'); //CMS获取所有订单简要
        Route::post('/get_order_one', 'cms.OrderManage/GetOrderOne'); //获取指定订单详细--CMS
        Route::post('/edit_courier', 'cms.OrderManage/editCourier'); //更新订单配送信息
        Route::post('/edit_remark', 'cms.OrderManage/editRemark'); //添加订单备注信息
        Route::post('/edit_price', 'cms.OrderManage/edit_price'); //修改订单价格
        Route::get('/get_tui_all', 'cms.TuiManage/getTuiAll'); //cms 获取所有退款信息
        Route::post('/tui_money', 'cms.TuiManage/TuiMoney'); //微信退款
        Route::post('/tui_bohui', 'cms.TuiManage/TuiBoHui'); //退款申请驳回
        Route::put('/update', 'cms.Common/upValue');   //更新某模型下的某布尔字段,如上下级架
    })->middleware('CheckCms');
});

//商品
Route::group('product', function () {
    //用户
    Route::group('', function () {
        Route::get('get_product', 'common.Product/getProduct');//获取某商品详情
        Route::get('get_recent', 'common.Product/getRecent');//获取最新商品
        Route::get('get_shop_product', 'common.Product/getShopProduct');//获取某商家所有商品
        Route::get('get_cate_pros', 'common.Product/getCatePros');//获取某分类下所有商品
        Route::get('get_evaluate', 'common.Product/getEvaluate');//获取某个商品的所有评价
        Route::get('search', 'common.Search/searchGoods');//搜索商品
        Route::post('get_shipment_price', 'cms.Delivery/getShipmentPrice');//获取商品需要的运费
    });

    //手机管理员
    Route::group('mcms', function () {

        Route::post('add_product', 'cms.ProductManage/addProduct');//添加商品
        Route::post('edit_product', 'cms.ProductManage/mEditProduct');//修改商品
        Route::put('del_product', 'cms.ProductManage/deleteProduct');//删除商品
        Route::post('all_goods_info', 'cms.ProductManage/all_goods_info');//获取所有商品简略信息
    })->middleware('CheckMCMS');

    //采集商品
    Route::group('copy', function () {
        Route::post('get_info', 'cms.ProductManage/getCopyProductInfo');//采集商品
    });

    //管理员
    Route::group('admin', function () {
        Route::post('add_product', 'cms.ProductManage/addProduct');//添加商品
        Route::post('edit_product', 'cms.ProductManage/editProduct');//修改商品
        Route::put('del_product', 'cms.ProductManage/deleteProduct');//删除商品
        Route::post('set_sort', 'cms.ProductManage/setSort');//商品排序
        Route::post('get_product', 'cms.ProductManage/getProduct');//获取所有上架商品，包含分页
        Route::post('get_products_down', 'cms.ProductManage/getProductsDown');//获取所有下架商品，包含分页
        Route::post('all_goods_info', 'cms.ProductManage/all_goods_info');//获取所有商品简略信息
        Route::get('all_goods_name', 'cms.ProductManage/allGoodsName');//获取所有商品id+名称
        Route::get('get_normal_goods', 'cms.ProductManage/getNormalGoods');//获取所有未参加活动的商品
    })->middleware('CheckCms');
});

//用户
Route::group('user', function () {

    Route::group('', function () {
        Route::put('/login', 'user.User/userLogin'); //模拟用户登录
        Route::get('/info', 'user.User/getInfo'); //获取用户基础信息

        Route::get('/get_cpy', 'user.User/getCpy'); //获取用户发票信息
        Route::post('/edit_cpy', 'user.User/editCpy'); //修改用户发票信息

        Route::post('/get_xcx_code', 'user.User/getXcxCode'); //获取用户小程序码
        Route::post('/get_web_code', 'user.User/getWebCode'); //获取用户二维码
        Route::post('/get_all_code', 'user.User/getAllCode'); //获取3端码
    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_all_user', 'cms.UserManage/getAllUser');//获取所有用户信息
    })->middleware('CheckCms');
});

//统计
Route::group('statistic', function () {
    Route::group('', function () {

    });
    //用户
    Route::group('user', function () {

    });

    //管理员
    Route::group('admin', function () {
        Route::get('get_index_data', 'cms.Statistic/getCmsIndexData');//获取首页数据
        Route::post('get_table', 'cms.Statistic/getTableData');//获取首页图表数据
        Route::post('get_money', 'cms.Statistic/getMoneyData');//cms订单统计销售额
        Route::post('get_order', 'cms.Statistic/getOrderData');//cms统计订单数据
        Route::get('remind', 'cms.Statistic/remind');//获取首页图表数据
    })->middleware('CheckCms');
});

//备份
Route::group('backup', function () {
    Route::get('add_backup', 'cms.Backup/addBackup');//添加备份
    Route::put('del_backup', 'cms.Backup/deleteBackup');//添加备份
    Route::get('get_backup', 'cms.Backup/getBackup');//添加备份
})->middleware('CheckCms');;
//mcms手机管理员
Route::group('mcms', function () {

    Route::group('', function () {
        Route::put('/update', 'cms.Common/upValue');   //更新某模型下的某布尔字段,如上下级架
    });

    Route::group('admin', function () {

    });
})->middleware('CheckMCMS');

//cms管理员
Route::group('cms', function () {

    Route::group('', function () {
        Route::post('/get_config', 'cms.System/GetConfig');   //获取商城配置信息
        Route::post('/edit_config', 'cms.System/edit_config');  //修改配置信息
        Route::post('/edit_template', 'cms.System/editTemplate');  //修改配置信息
        Route::put('/update', 'cms.Common/upValue');   //更新某模型下的某布尔字段,如上下级架
    });

    Route::group('admin', function () {
        Route::post('edit_psw', 'cms.Admin/editPSW');//管理员修改密码
        Route::post('edit_admin', 'cms.AdminManage/editAdmin');//更新管理员
        Route::post('add_admin', 'cms.AdminManage/addAdmin');//添加管理员
        Route::get('get_all_admin', 'cms.AdminManage/getAdminAll');//获取所有管理员
        Route::put('del_admin', 'cms.AdminManage/deleteAdmin');//删除管理员
        Route::post('set_web_auth', 'cms.AdminManage/setWebAuth');//设置前端管理员
    });
})->middleware('CheckCms');
