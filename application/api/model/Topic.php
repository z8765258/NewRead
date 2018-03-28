<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 17:31
 */

namespace app\api\model;


class Topic extends BaseModel
{
    protected $hidden = ['cid','id'];

    public function itemAns()
    {
        return $this->hasOne('Answer','tid','id');
    }

    public function itemOpt()
    {
        return $this->hasMany('Options','tid','id');
    }


}