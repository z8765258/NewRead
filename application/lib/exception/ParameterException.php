<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 13:53
 */

namespace app\lib\exception;

/**
 * Class ParameterException
 * @package app\lib\exception
 * 通用参数类异常错误
 */
class ParameterException extends BaseException
{
    public $code = 400;
    public $msg = "参数错误";
    public $errorCode = 10000;
}