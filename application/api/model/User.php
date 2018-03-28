<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 15:52
 */

namespace app\api\model;
use app\api\service\Token;

class User extends BaseModel
{
    protected $readonly = ['nickName','avatarUrl'];
    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }

    public static function updateUserInfo($infoStr)
    {
        $uid = Token::getCurrentUid();
        $infoStr = json_decode($infoStr,true);
        $newArr['nickName'] = $infoStr['nickName'];
        $newArr['avatarUrl'] = $infoStr['avatarUrl'];
        $res = self::where('id','=',$uid)->update($newArr);
        return $res;
    }
}