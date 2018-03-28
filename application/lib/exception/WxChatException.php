<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/7/18
 * Time: 9:09
 */

namespace app\lib\exception;


class WxChatException extends BaseException
{
    public $code = 404;
    public $msg = '制定主题不存在,';
    public $errorCode = 30000;
}