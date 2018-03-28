<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 13:41
 */

namespace app\api\controller\v1;


use app\api\service\UserToken;
use app\api\validate\TokenGet;
use app\lib\exception\ParameterException;
use app\api\service\Token as TokenService;

class Token
{
    /**
     * 用户获取令牌（登录）
     * @url /token
     * @POST code
     * @note 虽然查询应该用get，但为了稍微增加安全性，所以使用POST
     *
     */
    public function getToken($code = '')
    {
        (new TokenGet())->goCheck();
        $ut = new UserToken($code);
        $token = $ut->get();
        return json(['token' => $token]);
    }

    public function verifyToken($token = '')
    {
        if(!$token){
            throw new ParameterException([
                'token不能为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return json(['isValid' => $valid]);

    }
}