<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 15:32
 */

namespace app\api\service;



use app\api\model\User;
use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\WxChatException;
use think\Exception;

class UserToken extends Token
{
    protected $code;
    protected $wxLoginUrl;
    protected $wxAppID;
    protected $wxAppSecret;

    function __construct($code)
    {
        $this->code = $code;
        $this->wxAppID = config('wx.app_id');
        $this->wxAppSecret = config('wx.app_secret');
        $this->wxLoginUrl = sprintf(config('wx.login_url'), $this->wxAppID, $this->wxAppSecret, $this->code);
    }

    public function get()
    {
        $result = curl_get($this->wxLoginUrl);

        $wxResult = json_decode($result,true);
//        halt($wxResult);
        if(empty($wxResult)){
            throw new Exception('获取session_key和openID时异常，微信内部错误');
        }else{
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }


    private function grantToken($wxResult){
        /*
         * 拿到openid查询数据库如果不存在添加一条user记录
         * 生成令牌，准备缓存数据，写入缓存
         * 返回令牌到客户端
         * key:就是用户令牌
         * value:$wxResult,用户记录的id就是uid,权限scope
         */

        $openid = $wxResult['openid'];
        $user = User::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
//        $openid = $wxResult['openid'];
//        $user = User::getByOpenID($openid);
//        if($user){
//            $uid = $user->id;
//        }else{
//            $uid = $this->newUser($openid);
//        }
//        $cachedValue = $this->prepareCachedValue($wxResult,$uid);
//        $token = $this->saveToCache($cachedValue);
//        return $token;
    }

    private function saveToCache($cachedValue)
    {
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('sets.token_expire_in');
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult,$uid)
    {
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = ScopeEnum::User;
//        halt($cachedValue);
        return $cachedValue;
    }

    private function newUser($openid)
    {
        $user = User::create([
            'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult)
    {
        throw new WxChatException([
           'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}