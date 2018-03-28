<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 11:40
 */

namespace app\lib\exception;


class PlanException extends BaseException
{
    public $code = 400;
    public $msg = '添加用户学习进度失败';
    public $errorCode = 70001;
}