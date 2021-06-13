
<?php

return [
    'psw_code'=>'rus5w2fr168',
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
    'wx_login_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",//小程序登陆，携带code换取openid
    'soft_del' => '', //取消未支付的团单或订单，true为真实删除
    'token_expire_in' => 7200,  //token过期时间，单位秒

    //snsapi_userinfo或snsapi_base
    'gzh_code_url' => "https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect",
    'gzh_login_url' =>'https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=authorization_code', //公众号登陆，携带code换取openid

    'is_business'=>'1'
];