<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/13
 * Time: 16:26
 */

namespace app\admin\controller;


use app\api\service\RedisCache;

class Onsale extends BaseController
{
    public function index()
    {
        $res = RedisCache::beginReids()->getRedisCode();
        return $this->fetch('',[
            'cache' => $res
        ]);
    }

    public function payout($code = '')
    {
        $data = RedisCache::beginReids()->getRedisCode();
        $settle = $this->cacheDataSettle($data,$code);
        $result = RedisCache::beginReids()->_save($settle);
        if($result){
            echo "<script> alert('{该邀请码已经派发}') </script>";
        }

    }

    public function createCode()
    {
        $code = RedisCache::beginReids()->createCode();
        return RedisCache::beginReids()->saveCodesArray($code);
    }
    private function cacheDataSettle($data,$code)
    {
        for ($i=0;$i<count($data);$i++){
            if($data[$i]['code'] == $code){
                $data[$i]['scope'] = 2;
                break;
            }
        }
        return $data;
    }
}