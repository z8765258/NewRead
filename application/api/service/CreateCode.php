<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/10
 * Time: 10:00
 */

namespace app\api\service;


class CreateCode
{
    private $url = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=%s";

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
//        halt($token);
        if(!empty($token['access_token'])){
            $this->url = sprintf($this->url,$token['access_token']);
        }
    }

    public function sendCreateCode()
    {
        $uid = Token::getCurrentUid();
        $data = [
            'scene' => $uid,
//            'page' => "pages/choose-lesson/choose-lesson",
            'width' => 250,
        ];
        $result =  curl_post2($this->url,$data);
        return $result;
    }
}