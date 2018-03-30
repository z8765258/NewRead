<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/30
 * Time: 15:37
 */

namespace app\admin\model;


class Foreshow extends Base
{
    public function getCourse($data = [])
    {
        $order = ['id'=>'desc'];
        $result = $this->order($order)->paginate();
        return $result;
    }
}