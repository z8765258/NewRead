<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 13:52
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Pay as PayService;
use app\api\model\Order as OrderModel;
use app\api\model\Card as CardModel;
use app\lib\exception\CardException;

class Pay extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getPreOrder,refund,queryRefundPlan'],
    ];

    public function getPreOrder($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $pay = new PayService($id);
        $payParam = $pay->Pay();
        return $payParam;
    }

    public function receiveNotify()
    {
        $notify = new WxNotify();
        $notify->Handle();
    }

    /**
     * 申请退款
     */
    public function courseRefund($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        if(!CardModel::verifyCards($id)){
            throw new CardException();
        }

        $order = OrderModel::getPayOrder($id);
        $pay = new PayService($order->id);
        $refund = $pay->refund();
        return $refund;
    }

    /**
     * 查询退款进度
     */
    public function queryRefundPlan($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        $order = OrderModel::getPayOrder($id);
        $Pay = new PayService($order->id);
        $result = $Pay->queryRefund($id);
        return $result;
    }
}