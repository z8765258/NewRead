<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/15
 * Time: 11:28
 */

namespace app\api\model;


class Course extends BaseModel
{
    protected $hidden = ['create_time','delete_time','update_time'];
    public  function getTypesPay()
    {
        $allType = self::all();
        $sta = Order::getUserPay();
        $status = self::assembly($allType,$sta);
        return $status;
    }

    private static function assembly($allType,$sta)
    {
        for ($i=0;$i<count($allType);$i++){
            $allType[$i]['isPay'] = false;
        }

        for ($i=0;$i<count($sta);$i++){
            if($allType[$i]['id'] == $sta[$i]['typeid']){
                $allType[$i]['isPay'] = true;
            }else{
                $allType[$i]['isPay'] = false;
            }
        }
        return $allType;
    }
}