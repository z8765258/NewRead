<?php

namespace app\common\validate;
use think\Validate;

/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/2/27
 * Time: 13:56
 */
class AdminUser extends Validate
{
    protected $rule = [
        'username' => 'require|max:20',
        'password' => 'require|max:20'
    ];
}