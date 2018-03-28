<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 11:33
 */

namespace app\api\model;


use app\api\service\Token;
use app\lib\exception\MissException;

class Plan extends BaseModel
{
    protected $autoWriteTimestamp = true;
    public static function getUpdateUserPlan($tid)
    {

        $plan = self::getPlanInfo($tid);
        if(!$plan){
            throw new MissException([
                'msg' => '用户未购买，获取不到学习进度'
            ]);
        }

        if($plan->planday == 21){
            return $plan->planday;
        }
        $times = verifyPlan(strtotime($plan->update_time),time());
        /**
         * 计算时间差如果不是当天就去更新一天的内容
         */
        if(!$times){
            $uid = Token::getCurrentUid();
            $where = ['tid' => $tid, 'uid' => $uid];
             if(self::updateUserPlan($where)){
                return $plan->planday + 1;
             }
        }
        return $plan->planday;
    }

    public static function getPlanInfo($tid)
    {
        $uid = Token::getCurrentUid();
        $where = ['tid' => $tid, 'uid' => $uid];
        return self::where($where)->find();
    }

    public static function updateUserPlan($where)
    {
        $res = self::where($where)->inc('planday')->update(['update_time' => time()]);
        return $res;
    }

    public static function setRemindTime($time , $tid)
    {
        $uid = Token::getCurrentUid();
        $whereData['tid'] = $tid;
        $whereData['uid'] = $uid;
        return self::where($whereData)->update(['settime'=>$time]);
    }
}