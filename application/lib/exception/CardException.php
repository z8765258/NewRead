<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/3
 * Time: 11:48
 */

namespace app\lib\exception;


class CardException extends BaseException
{
    public $code = 200;
    public $msg = '没有按时连续21天打卡';
    public $errorCode = 10001;
}