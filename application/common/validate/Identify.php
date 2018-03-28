<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/8
 * Time: 13:55
 */

namespace app\common\validate;


use think\Validate;

class Identify extends Validate
{
    protected $rule = [
        'phone' => 'require|number|length:11',
    ];
}