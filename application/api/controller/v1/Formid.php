<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/21
 * Time: 15:57
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\Token as TokenService;
use think\cache\driver\Redis;

class Formid extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'disposeFormId'],
    ];

    public function disposeFormId()
    {
        $openId = TokenService::getCurrentTokenVar('openid');
//        $cacheKey = md5('user_formId'.$openId);
//        $redis = new Redis();
//        $data = $redis->get($cacheKey);
//        halt($data);
//        halt($redis->rm($cacheKey));
        $formIds = input('post.formIds');
        if($formIds){
            $formIds = json_decode($formIds,true);
            return json(['saveRes' =>$this->_saveFormIdsArray($openId,$formIds)]);
        }
    }

    private function _saveFormIdsArray($openId,$arr){
        $res = $this->_get($openId);
        if($res){
            $new = array_merge($res,$arr);
            return $this->_save($openId,$new);
        }else{
            return $this->_save($openId,$arr);
        }
    }

    private function _save($openId,$data){
        $cacheKey = md5('user_formId'.$openId);
        $redis = new Redis();
        return $redis->set($cacheKey,json_encode($data),60*60*24*7);
    }

    private function _get($openId){
        $cacheKey = md5('user_formId'.$openId);
        $redis = new Redis();
//        $res = $redis->rm($cacheKey);
        $data = $redis->get($cacheKey);
//        halt($data);
        if($data){
            if(!is_array($data)){
                $data = json_decode($data,true);
                return $data;
            }else{
                return $data;
            }
        }else{
            return false;
        }
    }
}