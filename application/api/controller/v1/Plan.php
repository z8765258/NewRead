<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 11:26
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Plan as PlanModel;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\TimeValidate;

class Plan extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getUpdateUserPlan,setRemindTime,getUserPlan'],
    ];

    /**
     * @param $id
     * @return int 用户学习进度的天数。
     * 如果用户上次学习时间不是当天进度就更新一天 否则进度就不更新
     */
    public function getUpdateUserPlan($id = 0)
    {
        (new IDMustBePositiveInt())->goCheck();
        $planday = PlanModel::getUpdateUserPlan($id);
        return json(['planNum' => $planday]);
    }

    /**
     * @param string $time  传入一个时间（05：00、06：10）
     * @param int $id 传入一个课程类型id
     */
    public function setRemindTime($time = '',$id = '')
    {
        (new TimeValidate())->goCheck();
        $res = PlanModel::setRemindTime($time,$id);
        return json(['setTime' => $res]);
    }

    public function getUserPlan($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $plan = PlanModel::getPlanInfo($id);
        return $plan;
    }

}