<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 17:09
 */

namespace app\admin\model;


class Statement extends Base
{
    public function getStatement($data = [])
    {
        $order = ['id'=>'desc'];
        $result = $this->order($order)->paginate();
        return $result;
    }
}