<?php

/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/2/27
 * Time: 16:07
 */
namespace app\common\lib;
use think\Cache;

class IAuth
{
    public static function setPassword($data)
    {
        return md5($data.config('app.password_pre_halt'));
    }

    public static function setSign($data=[])
    {
        /*
         * 生成每次请求的Sign
         * 1.按字段排序
         * 2.拼接字符串数据&
         * 3.通过aes来加密
         * 4.加密后的字符转为大写
         */
        ksort($data);
        $string = http_build_query($data);
        $aes = new Aes();
        $string = $aes->encrypt($string);
//        $string = strtoupper($string);
        return $string;
    }

    public static function checkSignPass($data)
    {
        $aes = new Aes();
        $str = $aes->decrypt($data['sign']);
        if(empty($str)){
            return false;
        }
        parse_str($str,$arr);
        if(!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did']){
            return false;
        }
        if(!config('app_debug')){
            if((time() - ceil($arr['time'] / 1000)) > config('app.app_sign_time')){
                return false;
            }
            if(Cache::get($data['sign'])){
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $phone
     * @return string
     * 设置登录token
     */
    public static function setAppLoginToken($phone = '')
    {
        $str =  md5(uniqid(md5(microtime(true)),true));
        $str = sha1($str,$phone);
        return $str;
//        return '';
    }
}









