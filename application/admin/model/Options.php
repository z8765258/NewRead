<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 17:48
 */

namespace app\admin\model;


class Options extends Base
{
    public static function getOptionFind($id)
    {
        return self::where('tid','=',$id)->paginate();
    }

    public static function getOption($id)
    {
        return self::where('tid','=',$id)->select();
    }
}