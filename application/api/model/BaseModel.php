<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/7/15
 * Time: 18:12
 */

namespace app\api\Model;


use think\Model;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
    protected function prefixImgUrl($value,$data){
        $finaUrl = $value;
        if($data['from'] == 1){
            $finaUrl =  config('setting.img_prefix').$value;
        }
        return $finaUrl;
    }
}