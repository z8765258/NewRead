<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 17:09
 */

namespace app\admin\model;


class Topic extends Base
{
    public function itemOpt()
    {
        return $this->hasMany('Options','tid','id');
    }

    public function getTopic($data = [])
    {
        $order = ['id'=>'desc'];
        $result = $this->with('itemOpt')->order($order)->paginate();
        return $result;
    }

    public function getTopicFind($id)
    {
        $result = $this->with('itemOpt')->find($id);
        return $result;
    }


}