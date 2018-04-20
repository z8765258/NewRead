<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/17
 * Time: 17:44
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use \app\api\service\Tixian as TixianService;
class Tixian extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'tixian'],
    ];
    //封装提现方法
    public function tixian()
    {
        $mchid = '1459841202';
        $appid = 'wxf7863f45ec239427';
        $Key = 'zhengzhouqingkongwenhuachuanbo11';
        $trueName = input('post.name');
        $payAmount = input('post.money');
        $tixian = new TixianService($mchid,$appid,$Key,$trueName,$payAmount);
        $result = $tixian->createJsBizPackage();
        return $result;
    }
}