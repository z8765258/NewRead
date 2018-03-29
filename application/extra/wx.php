<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/7/17
 * Time: 17:52
 */

return[
    'app_id' => 'wx64153a52844dd7d4',
//    'app_id' => 'wx9cf618d44eb33410',
    'app_secret' => 'a61b0ff6a1943a4709506304cb7cc7db',
//    'app_secret' => '3a9ffd47b70f5ce15e5dba004077cfbe',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s",
    'callBack_url' => 'https://riyubao.net/NewRead/public/index.php/api/v1/pay/notify'
];