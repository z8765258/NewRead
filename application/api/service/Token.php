<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 15:32
 */

namespace app\api\service;


use app\lib\enum\ScopeEnum;
use app\lib\exception\TokenException;
use app\lib\exception\ForbiddenException;
use think\Cache;
use think\Exception;
use think\Request;

class Token
{
    public static function generateToken()
    {
        //令牌就是一组无意义的字符串
        $randChars = getRandChar(32);
        $timestamp = $_SERVER['REQUEST_TIME'];
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key)
    {
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw new TokenException();
        }else{
            if(!is_array($vars)){
                $vars = json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取Token变量不存在');
            }
        }
    }


    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param $checkedUid
     * @return bool
     * @throws Exception
     * 检查登录的用户和这个订单用户是一致的
     */
    public static function isValidOperate($checkedUid)
    {
        if(!$checkedUid){
            throw new Exception('检查uid时必须传入一个被检查的uid');
        }
        $currentOperateUid = self::getCurrentUid();
        if($currentOperateUid == $checkedUid){
            return true;
        }
        return false;
    }

    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope == ScopeEnum::User){
                return true;
            }else{
                throw new ForbiddenException();
            }
        }else{
            throw new TokenException();
        }
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        $scope = self::getCurrentTokenVar('scope');
        if($scope == ScopeEnum::User){
            return $uid;
        }else{
            throw new ForbiddenException();
        }
    }


}