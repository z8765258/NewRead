<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/7/18
 * Time: 9:09
 */

namespace app\lib\exception;


class WxRefundException extends BaseException
{
    public $code = 400;
    public $msg = '退款失败,请稍后重试';
    public $errorCode = 10006;
}