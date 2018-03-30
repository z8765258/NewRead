<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/30
 * Time: 15:58
 */

namespace app\api\model;


class Foreshow extends BaseModel
{
    public static function getForeshow($id)
    {
        return self::where('cid',$id)->find();
    }
}