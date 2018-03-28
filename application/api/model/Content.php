<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 17:24
 */

namespace app\api\model;


class Content extends BaseModel
{
    protected $hidden = ['tid','id','btotype','create_time','delete_time','update_time'];
    public function itemsSta()
    {
        return $this->hasMany('Statement','cid','id');
    }

    public function itemsTop()
    {
        return $this->hasMany('Topic','cid','id');
    }

    public static function getDayContent($day,$type)
    {
        $whereArr = [];
        $whereArr['btoday'] = $day;
        $whereArr['btotype'] = $type;
        $content = self::with(['itemsSta','itemsTop','itemsTop.itemOpt'])->where($whereArr)->find();
        return $content;
    }

    public static function getTypeContentNums($type)
    {
        $num = self::where('btotype',$type)->count();
        return $num;
    }
}