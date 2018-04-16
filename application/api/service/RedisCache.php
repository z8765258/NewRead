<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/13
 * Time: 14:37
 */

namespace app\api\service;
use think\cache\driver\Redis;

class RedisCache
{
    private static $_instance = null;
    protected static $redis = null;
    private function __construct()
    {
        self::$redis = new Redis();
    }

    public static function beginReids()
    {
        if(is_null(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function getRedisCode()
    {
        $cacheKey = md5('user_InCode');
        return self::$redis->rm($cacheKey);
        $data = self::$redis->get($cacheKey);
        if($data){
            if(!is_array($data)){
                $data = json_decode($data,true);
                return $data;
            }else{
                return $data;
            }
        }else{
            return false;
        }
    }

    public function saveCodesArray($arr)
    {
        $res = self::getRedisCode();
        if($res){
            $new = array_merge($res,$arr);
            return self::_save($new);
        }else{
            return self::_save($arr);
        }
    }

    public function _save($data){
        $cacheKey = md5('user_InCode');
        $redis = new Redis();
        return $redis->set($cacheKey,json_encode($data));
    }


    public function createCode($length = 10)
    {
//        self::$redis->rm(md5('user_InCode'));
//        die;
        $arr = [];
        for ($i=0;$i<$length;$i++){
            array_push($arr,self::generate_password());
        }
//        halt($arr);
        return $arr;
    }

    private static function generate_password( $length = 12 ) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ( $i = 0; $i < $length; $i++ )
        {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
//        $data = array();
        $data = ['scope' => 1,'code' => $password];
        return $data;
    }

}