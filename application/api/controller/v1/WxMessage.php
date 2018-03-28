<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/21
 * Time: 16:23
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxMessage as WxMessageService;
class WxMessage extends BaseController
{
//    protected $beforeActionList = [
//        'checkPrimaryScope' => ['only' => 'sendWxMessage'],
//    ];

    public function sendWxMessage()
    {
        $wxMessage = new WxMessageService();
        $wxMessage->readToSend();
    }
}