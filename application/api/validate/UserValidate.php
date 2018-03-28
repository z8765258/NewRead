<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 10:46
 */

namespace app\api\validate;


class UserValidate extends BaseValidate
{
    protected $rule = [
        'infoStr' => 'require|is_json',
    ];

    protected $message = [
        'infoStr' => '请传入用户信息的json字符串'
    ];


    public function is_json($string)
    {
        if(is_numeric($string)){
           return false;
        }
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}