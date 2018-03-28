<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 16:44
 */

namespace app\api\validate;


class TimeValidate extends BaseValidate
{
    protected $rule = [
        'time' => 'require|date',
        'id' => 'require|isNotEmpty'
    ];

    protected $message=[
        'time' => '必须传入一个时间格式（10：02）',
        'id' => 'id是正整数'
    ];
}