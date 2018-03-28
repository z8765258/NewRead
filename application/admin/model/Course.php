<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 11:13
 */

namespace app\admin\model;


class Course extends Base
{
    public function getCourse($data = [])
    {
        $order = ['id'=>'desc'];
        $result = $this->order($order)->paginate();
        return $result;
    }
}