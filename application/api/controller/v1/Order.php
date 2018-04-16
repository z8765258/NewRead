<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 13:55
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\RedisCache;
use app\api\validate\IDMustBePositiveInt;
use app\api\service\Token;
use app\api\service\Order as OrderService;
use app\api\model\Order as OrderModel;
use app\api\model\Course as CourseModel;
class Order extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'placeOrder'],
    ];

    /**
     * 下单
     * @url /order
     * @http POST
     * @param int id   下单只需传入课程分类的id
     */
    public function placeOrder()
    {

        (new IDMustBePositiveInt())->goCheck();
        $typeID = input('post.id');
        $starttime = input('post.starttime');
        $stoptime = input('post.stoptime');
        $uid = Token::getCurrentUid();
        $result = new OrderService();
        $res = $result->verifyCode();
        if(!$res){
            $find = OrderModel::getOrderFind($uid,$typeID,$starttime,$stoptime);
            if($find){
                return[
                    'order_no' => $find->order_no,
                    'order_id' => $find->id,
                    'create_time' => $find->create_time
                ];
            }
        }
        $products = CourseModel::get($typeID);
        $order = new OrderService();
        $status = $order->place($uid,$products,$starttime,$stoptime);
        return $status;
    }

    public function createCode()
    {
//        $result = new OrderService();
//        halt($result->verifyCode());
        $code = RedisCache::beginReids()->createCode();
        $res = RedisCache::beginReids()->saveCodesArray($code);
        return $res;
    }

    public function getCode()
    {
        $code = RedisCache::beginReids()->getRedisCode();
        return $code;
    }

}