<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 11:41
 */

namespace app\api\validate;


class isTypeParam extends BaseValidate
{
    protected $rule = [
        'type' => 'require|isNotEmpty'
    ];

    protected $message=[
        'type' => '传入的类型只能是正整数'
    ];
}