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
use app\lib\exception\WxRefundException;
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

    /**
     * @return \think\response\Json
     * @throws WxRefundException
     * 申请退款
     */
    public function refund()
    {
        if($this->checkOrderUser()){
            $refundData = $this->makeWxRefund();
            $result = \WxPayApi::refund($refundData);
            halt($result);
            if($result['result_code'] != 'SUCCESS' || $result['return_code'] != 'SUCCESS'){
                Log::record($result,'error');
                Log::record('退款失败','error');
                throw new WxRefundException();
            }
            return json([
                'refund_fee'=>$result['refund_fee'],
                'return_msg'=>$result['return_msg'],
                'errorCode' => 0
            ]);
        }
    }

    /**
     * @return array
     * 支付
     */
    public function Pay()
    {
        if($this->checkOrderValid()){
            return $this->makeWxPreOrder();
        }
    }

    public function queryRefund()
    {
        if($this->checkOrderUser()){
            $query = new \WxPayRefundQuery();
            $query->SetOut_refund_no($this->products['refund_no']);
            $result = \WxPayApi::refundQuery($query);
            if($result['result_code'] != 'SUCCESS' || $result['return_code'] != 'SUCCESS'){
                Log::init([
                    'type' => 'File',
                    'path' => LOG_PATH,
                    'level' => ['error']
                ]);
                Log::record($result,'error');
                Log::record('查询退款进度失败','error');
                throw new WxRefundException('查询退款进度失败');
            }
            return json([
                'refund_recv_accout' => $result['refund_recv_accout_0'],
                'total_fee' => $result['total_fee'],
                'cash_fee' => $result['cash_fee'],
                'refund_fee' => $result['refund_fee'],
//                'return_msg' => $result['return_msg'],
                'refund_status' => $result['refund_status_0'],
                'refund_success_time' => $result['refund_success_time_0'],
                'msg' => 'OK'
            ]);
//            halt($result);
        }
    }


    private function makeWxRefund()
    {
        $refund = new \WxPayRefund();
        $refund->SetOut_trade_no($this->products['order_no']);
        $refund->SetOut_refund_no($this->products['refund_no']);
        $refund->SetTotal_fee($this->products['price'] * 100);
        $refund->SetRefund_fee($this->products['price'] * 100);
        $refund->SetOp_user_id('1459841202');
        return $refund;
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

    private function checkOrderUser()
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
        if($order->status != 2){
            throw new OrderException([
                'msg' => '此订单还没有支付不能申请退款',
                'errorCode' => 80004,
                'code' => 400
            ]);
        }
//        $this->orderNo = $order->order_no;
        $this->products = $order;
        return true;
    }

}