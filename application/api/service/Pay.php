<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 14:46
 */

namespace app\api\service;

use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Loader;
use app\api\model\Order as OrderModel;
use think\Log;

Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
class Pay
{
    private $orderNo;
    private $orderID;
    private $products;

    function __construct($orderID)
    {
        if(!$orderID){
            throw new Exception('订单ID不能为空');
        }
        $this->orderID = $orderID;
    }

    public function Pay()
    {
        if($this->checkOrderValid()){
            return $this->makeWxPreOrder();
        }
    }

    /**
     * 构建微信支付订单信息
     */
    private function makeWxPreOrder()
    {
        $openid = Token::getCurrentTokenVar('openid');
        if(!$openid){
            throw new TokenException();
        }
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderNo);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($this->products['price'] * 100);
//        String spbill_create_ip = request.getRemoteAddr();
//        $wxOrderData->SetSpbill_create_ip('115.60.163.103');
//        $wxOrderData->SetSpbill_create_ip('123.57.63.202');
        $wxOrderData->SetBody($this->products['typename']);
        $wxOrderData->SetOpenid($openid);
        $wxOrderData->SetNotify_url(config('wx.callBack_url'));  //微信支付回调地址
        return $this->getPaySignature($wxOrderData);
    }

    /**
     * @param $wxOrderData
     * 向微信请求订单号并生成签名
     */
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['return_code'] != 'SUCCESS'){
            Log::record($wxOrder,'error');
            Log::record('获取预支付订单失败','error');
            throw new Exception('获取预支付订单失败');
        }

        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    /**
     * @param $wxOrder
     * 修改订单表中prepay_id值
     */
    private function recordPreOrder($wxOrder)
    {
        OrderModel::where('id','=',$this->orderID)->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    //签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wx.app_id'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time().mt_rand(0,1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    private function checkOrderValid()
    {
        $order = OrderModel::where('id','=',$this->orderID)->find();
        if(!$order){
            throw new OrderException();
        }
        if(!Token::isValidOperate($order->user_id)){
            throw new TokenException([
                'msg' => '此订单和此用户不匹配',
                'errorCode' => 10003
            ]);
        }
        if($order->status == 2){
            throw new OrderException([
                'msg' => '此订单已支付过',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        $this->orderNo = $order->order_no;
        $this->products = $order;
        return true;
    }

}