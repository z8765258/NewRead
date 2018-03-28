<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 17:27
 */

namespace app\api\validate;


class DayMustBeInt extends BaseValidate
{
    protected $rule = [
        'day' => 'require|isPositiveInteger|isNotEmpty',
        'type' => 'require|isPositiveInteger|isNotEmpty',
    ];
}