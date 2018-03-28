<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/21
 * Time: 11:54
 */

namespace app\api\service;


use app\api\model\Plan;
use think\cache\driver\Redis;
use think\Log;

class WxMessage
{
    private $sendUrl = "https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token=%s";
    private $touser;
    private $color = 'black';

    protected $tplID;
    protected $page;
    protected $formID;
    protected $data;
    protected $openID;
    protected $emphasisKeyWord;

    function __construct()
    {
        $accessToken = new AccessToken();
        $token = $accessToken->get();
        if(!empty($token['access_token'])){
            $this->sendUrl = sprintf($this->sendUrl,$token['access_token']);
        }
        $this->tplID = 'wfsnoba5DCUyfTMO7YKBOmG7IKLG5PlcEzfy1yczyVg';
        $this->page = 'pages/index/index';
    }

    private function getDate($time)
    {

        return date('H:i',$time);
    }

    private function getFormId($openId)
    {
        $res = $this->_get($openId);
        if($res){
            if(!count($res)){
                return false;
            }
            $newData = array();
            $result = false;
            for ($i=0;$i<count($res);$i++){
                if($res[$i]['expire'] >= time()){
                    $result = $res[$i]['formId'];//得到一个可用的formId
                    for ($j = $i+1;$j<count($res);$j++){
                        array_push($newData,$res[$j]);
                    }
                    break;
                }
            }
            $this->_save($openId,$newData);
            return $result;
        }else{
            return false;
        }
    }

    public function readToSend()
    {

        $date = $this->getDate(time());
        $plan = new Plan();
        $sends = $plan->where('settime','=',$date)->select();
        $sendArr = [];
        for ($i=0;$i<count($sends);$i++){
            $send['openid'] = $sends[$i]['openid'];
            $send['formId'] = $this->getFormId($sends[$i]['openid']);
            $send['settime'] = $sends[$i]['settime'];
            array_push($sendArr,$send);
        }

        foreach ($sendArr as $k => $v){
            if(is_array($v)){
                $this->formID = $v['formId'];
                $this->openID = $v['openid'];
                $data = $this->jointData($v['settime']);
                $this->data = $data;
                if(!empty($v['formId'])){
                    $this->sendMessage();
                }
            }
        }
    }

    private function jointData($time)
    {
         $data = [
             'keyword1' => [
                 'value' => '别忘了，当初为何出发',
                 'color' => '#274088'
             ],

             'keyword2' => [
                 'value' => '拍照打卡',
                 'color'=> '#274088'
             ],
             'keyword3' => [
                 'value' => 1,
                 'color'=> '#274088'
             ],
             'keyword4' => [
                 'value' => '留影记录和猫咪共度的瞬间。记录有ta陪伴的每一天',
                 'color'=> '#274088'
             ],
             'keyword5' => [
                 'value' => '每日撸猫',
                 'color'=> '#274088'
             ]
         ];
         return $data;
    }


    public function sendMessage()
    {
        $data = [
            'touser' => $this->openID,
            'template_id' => $this->tplID,
            'page' => $this->page,
            'form_id' => $this->formID,
            'data' => $this->data,
            'emphasis_keyword' => $this->emphasisKeyWord
        ];
        $result = curl_post($this->sendUrl,$data);
        $result = json_decode($result,true);
        if($result['errcode'] == 0){
            echo '发送成功';
            return true;
        }else{
            //屏蔽报错
            // throw new Exception('发送失败，'.$result['errmsg']);
            Log::init([
                'type' => 'File',
                'path' => LOG_PATH,
                'level' => ['error']
            ]);
            Log::record($result['errmsg'],'error');
            return true;
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
        $data = $redis->get($cacheKey);
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