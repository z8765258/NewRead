<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 14:25
 */

namespace app\api\model;


use app\api\service\Token;

class Order extends BaseModel
{
    public static function getOrderFind($uid,$typeID,$starttime,$stoptime)
    {
        $whereData['user_id'] = $uid;
        $whereData['typeid'] = $typeID;
        $whereData['starttime'] = $starttime;
        $whereData['stoptime'] = $stoptime;
        return self::where($whereData)->find();
    }

    public static function getUserPay()
    {
        $uid = Token::getCurrentUid();
        $whereData['user_id'] = $uid;
        $whereData['status'] = 2;
        $res = self::where($whereData)->field(['status','typeid','typename'])->select();
//        halt($res);
//        $res->hidden(['unlocks','prepay_id','update_time','create_time','user_id','delete_time','id','order_no','price']);
        return $res;
    }
}