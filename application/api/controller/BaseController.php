<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 10:53
 */

namespace app\api\controller;


use app\api\controller\v1\Content;
use app\api\service\Token;
use think\Controller;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
       Token::needPrimaryScope();
    }
}