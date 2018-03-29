<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 16:46
 */

namespace app\api\service;


use app\api\model\Order as OrderModel;
use app\api\model\Plan as PlanModel;
use app\lib\exception\OrderException;
use app\lib\exception\PlanException;
use think\Db;
use think\Exception;
use think\Log;

class WxNotify extends \WxPayNotify
{
    public function NotifyProcess($data, &$msg)
    {
        if($data['result_code'] == 'SUCCESS'){
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try{
                $order = OrderModel::where('order_no','=',$orderNo)->find();
                if($order->status == 1){
                    $this->updateOrderStatus($order->id);
                    $this->createPlan($order->user_id,$order->typeid,$order->openid,$order->starttime,$order->stoptime);
                }
                Db::commit();
            }catch (Exception $e){
                Db::rollback();
                Log::error($e);
                return false;
            }
        }
        return true;
    }

    private function updateOrderStatus($orderID)
    {
        $res = OrderModel::where('id','=',$orderID)->update(['status' => 2 , 'unlocks' => 1]);
        if(!$res){
            throw new OrderException([
                'msg' => '更新订单状态失败'
            ]);
        }
    }

    private function createPlan($uid,$tid,$openid,$starttime,$stoptime)
    {
        $res = PlanModel::create(['uid'=>$uid,'tid'=>$tid,'planday'=>1,'openid'=>$openid,'timestart'=>$starttime,'timeend'=>$stoptime]);
        if(!$res){
            throw new PlanException();
        }
    }
//    private function updateUserStatus($uid)
//    {
//       $res = UserModel::where('id','=',$uid)->update(['starts' => 1]);
//       if(!$res){
//           throw new Exception('更新状态失败');
//       }
//    }
}