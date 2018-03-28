<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 14:54
 */

namespace app\admin\model;


class Content extends Base
{
    public function getContent($data = [])
    {
        $order = ['id'=>'desc'];
        $result = $this->order($order)->paginate();
        return $result;
    }
}