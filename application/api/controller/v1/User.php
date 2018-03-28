<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 9:01
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\UserValidate;
use app\api\model\User as UserModel;

class User extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'updateUserInfo'],
    ];

    /**
     * @param string $infoStr 小程序端获取的用户json字符串信息
     * @return $this
     * 更新用户信息
     *
     */
    public function updateUserInfo($infoStr = '')
    {
        (new UserValidate())->goCheck();
        $res = UserModel::updateUserInfo($infoStr);
        return $res;
    }
}