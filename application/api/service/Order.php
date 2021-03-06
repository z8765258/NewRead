<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 14:13
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use think\cache\driver\Redis;
use think\Exception;

class Order
{
    protected $uid;
    protected $products;
    protected $starttime;
    protected $stoptime;
    protected $scene;
    function __construct()
    {
    }

    /**
     * @param $uid int 用户id
     * @param $products array 商品信息
     */
    public function place($uid,$products,$starttime,$stoptime,$scene)
    {
        $this->products = $products;
        $this->starttime = $starttime;
        $this->stoptime = $stoptime;
        $this->scene = $scene;
        $this->uid = $uid;
        $status = self::createOrder();
        return $status;
    }

    private function createOrder()
    {
        $isOnSale = $this->verifyCode();
        try{
            $orderNo = self::makeOrderNo();
            $redundNo = self::makeRefundNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            if(!$isOnSale){
                $order->price = $this->products['price'];
            }else{
                $order->activitycode = $isOnSale;
                $order->price = $this->products['preprice'];
            }
            $order->typename = $this->products['coursename'];
            $order->scene = $this->scene;
            $order->typeid = $this->products['id'];
            $order->status = 1;
            $order->refund_no = $redundNo;
            $order->starttime = $this->starttime;
            $order->stoptime = $this->stoptime;
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            return[
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'refund_no' => $redundNo,
                'create_time' => $create_time
            ];
        }catch (Exception $e){
            throw $e;
        }
    }

    public function verifyCode()
    {
        $InvitationCode = Token::getCurrentInvitationCode();
        if(!$InvitationCode){
            return false;
        }
        $res = RedisCache::beginReids()->getRedisCode();
        if($res){
//            $newData = array();
            $result = false;
            for ($i=0;$i<count($res);$i++){
                if($res[$i]['code'] == $InvitationCode){
//                    array_merge();
                    $result = $res[$i]['code'];
                    break;
                }
            }
            return $result;
        }
        return false;




    }


    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    public static function makeRefundNo()
    {
        while (true) {
            $order_date = date('Y-m-d');
            $order_id_main = date('YmdHis') . rand(10000000, 99999999);
            //订单号码主体长度
            $order_id_len = strlen($order_id_main);
            $order_id_sum = 0;

            for ($i = 0; $i < $order_id_len; $i++) {

                $order_id_sum += (int)(substr($order_id_main, $i, 1));

            }
            //唯一订单号码（YYYYMMDDHHIISSNNNNNNNNCC）
            $order_id = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100, 2, '0', STR_PAD_LEFT);
            return $order_id;
        }
    }


}