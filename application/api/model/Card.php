<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 15:13
 */

namespace app\api\model;


use app\api\service\Token;

class Card extends BaseModel
{
    protected $hidden = ['delete_time','update_time','id','tid','uid'];

    public function cardCourse()
    {
        return $this->hasOne('Course','id','tid');
    }

    public static function createCardRecord($tid)
    {
        $isRecord = self::isHaveRecord($tid);
        if($isRecord){
            return false;
        }
        $ispass = self::isPass($tid);
        $res = self::create(['tid'=>$tid,'uid'=>Token::getCurrentUid(),'ispass'=> $ispass]);
        return $res;
    }

    /**
     * @param $tid
     * @return bool
     * 查询该用户在该课程下的所有打卡记录，如果有今天的打卡记录就不记录，如果没有今天的就去数据库记录今天的打卡。
     */
    private static function isHaveRecord($tid)
    {
        $uid = Token::getCurrentUid();
        $plan = self::where(['uid' => $uid,'tid'=>$tid])->select();
        if($plan){
            for ($i=0;$i<count($plan);$i++){
                if(verifyPlan(strtotime($plan[$i]->create_time),time())){
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    public static function getPlan($tid)
    {
        $plan = Plan::getPlanInfo($tid);
        return $plan;
    }


    public static function isPass($tid)
    {
        $plan = self::getPlan($tid);
        $plan = self::getTimeScope($plan->timestart,$plan->timeend);
        if($plan){
            return 1;
        }
        return 0;
    }

    /**
     * @param $timeStart
     * @param $timeEnd
     * @return bool
     * 计算用户打卡时间是否是自己设定的时间范围 判断用户是否完成今天的打卡
     */
    private static function getTimeScope($timeStart,$timeEnd)
    {
        $checkDayStr = date('Y-m-d ',time());
        $timeBegin1 = strtotime($checkDayStr.$timeStart.":00");
        $timeEnd1 = strtotime($checkDayStr.$timeEnd.":00");
        $curr_time = time();
        if($curr_time >= $timeBegin1 && $curr_time <= $timeEnd1)
        {
            return true;
        }

        return false;
    }

    public static function getCardList($tid)
    {

        $list = self::with('cardCourse')->where('tid','=',$tid)->select();
        return $list;
    }

    private static function isHorizon($arr)
    {
        $toDay = date('Y-m-d',time());
        $newArr = [];
        for ($i=0;$i<count($arr);$i++){
            if($toDay == date('Y-m-d',strtotime($arr[$i]['create_time']))){
                array_push($newArr,$arr[$i]);
            }
        }
        return count($newArr);
//        halt($toDay);
    }

    private static function uidCardNums($cards)
    {
        $uid = Token::getCurrentUid();
        $arr = [];
        for ($i=0;$i<count($cards);$i++){
            if($cards[$i]['uid'] == $uid){
                array_push($arr,$cards[$i]);
            }
        }
        halt(count($arr) + 1);
        return count($arr) + 1;
    }

    public static function getCardRank($id)
    {
        $pays = Order::where('typeid','=',$id)->count();
        $cards = self::where('tid','=',$id)->select();
        $card = self::isHorizon($cards);

        $arr['surpass'] = self::getNum($card,$pays);
        $arr['cardNum'] = self::uidCardNums($cards);
        return $arr;
    }

    private static function getNum($num1,$num2)
    {
        $num = false;
        if(is_numeric($num1) && is_numeric($num2)) {
            $num = ( $num1 / $num2 ) * 100 ."%";
            $num = 100 - $num . "%";
            return $num;
        } else {
            return $num;
        }
    }
}