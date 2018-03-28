<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/21
 * Time: 11:55
 */

namespace app\api\service;


use think\Exception;

class AccessToken
{
    private $tokenUrl;
    const TOKEN_CACHED_KEY = 'access';
    const TOKEN_EXPIRE_IN = 7000;

    function __construct()
    {
        $url = config('wx.access_token_url');
        $url = sprintf($url,config('wx.app_id'),config('wx.app_secret'));
        $this->tokenUrl = $url;
    }

    /**
     * 用户规模小的时候每次直接访问微信服务器获取最新token
     * 但是微信access_token接口获取限制为2000次/天
     */
    public function get()
    {
        $token = $this->getFromCache();
        if(!$token){
            return $this->getFromWxServer();
        }else{
            return $token;
        }
    }

    /**
     * 从缓存中获取token
     */
    private function getFromCache()
    {
        $token = cache(self::TOKEN_CACHED_KEY);
        if($token){
            return $token;
        }
        return null;
    }

    private function getFromWxServer()
    {
        $token = curl_get($this->tokenUrl);
        $token = json_decode($token,true);
        if(!$token){
            throw new Exception('获取Access异常');
        }
        if(!empty($token['errcode'])){
            throw new Exception($token['errmsg']);
        }
        $this->saveToCache($token);
        return $token;
    }

    private function saveToCache($token){
        cache(self::TOKEN_CACHED_KEY,$token,self::TOKEN_EXPIRE_IN);
    }


}