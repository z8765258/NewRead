<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 14:13
 */

namespace app\api\service;

use app\api\model\Order as OrderModel;
use think\Exception;

class Order
{
    protected $uid;
    protected $products;
    function __construct()
    {
    }

    /**
     * @param $uid int 用户id
     * @param $products array 商品信息
     */
    public function place($uid,$products)
    {
        $this->products = $products;
        $this->uid = $uid;
        $status = self::createOrder();
        return $status;
    }

    private function createOrder()
    {
        try{
            $orderNo = self::makeOrderNo();
            $order = new OrderModel();
            $order->user_id = $this->uid;
            $order->order_no = $orderNo;
            $order->price = $this->products['price'];
            $order->typename = $this->products['coursename'];
            $order->typeid = $this->products['id'];
            $order->status = 1;
            $order->save();
            $orderID = $order->id;
            $create_time = $order->create_time;
            return[
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch (Exception $e){
            throw $e;
        }
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


}