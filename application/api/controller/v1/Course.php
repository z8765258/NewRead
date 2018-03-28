<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 10:23
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\Course as CourseModel;
use app\lib\exception\MissException;
use app\api\model\Order as OrderModel;
class Course extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getUserTypePay,getAllType'],
    ];
    public function getAllType()
    {
        $status = CourseModel::getTypesPay();
        if(empty($status)){
            throw new MissException([
                'msg' => '该程序还没有开设课程',
                'errorCode' => 5000
            ]);
        }
        return $status;
    }

    public function getUserTypePay()
    {

//        return $status;
    }
}