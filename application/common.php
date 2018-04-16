<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

function curl_get($url,&$httpCode = 0)
{
    $this_header = array(
        "charset=UTF-8"
    );
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
//    curl_setopt($ch,CURLOPT_HTTPHEADER,$this_header);
    //不做证书校验，部署在Linux环境下改为true;
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
    $file_contents = curl_exec($ch);
//    halt($file_contents);
    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

function getRandChar($lenght)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;
    for($i = 0; $i < $lenght; $i++){
        $str .= $strPol[rand(0,$max)];
    }
    return $str;
}


function curl_post_raw($url, $rawData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: text'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function curl_post($url, array $params = array())
{
    $data_string = json_encode($params);
//    halt($data_string);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
//    halt($data);
    return ($data);
}


function curl_post2($url, array $params = array())
{
    $data_string = json_encode($params);
//    halt($data_string);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content: image/png'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
//    halt($data);
    return ($data);
}



function verifyPlan($startTime,$stopTime)
{
        $startTimeDay = date('d',$startTime);
        $stopTimeDay = date('d',$stopTime);
        if($startTimeDay == $stopTimeDay){
            return true;
        }
        return false;
}

function show($status,$msg,$data =[],$httpCode = 200)
{
    $result = [
        'status' => $status,
        'message' => $msg,
        'data' => $data
    ];
    return json($result,$httpCode);
}

function pagination($obj)
{
    if(!$obj){
        return '';
    }
    $params = request()->param();
    return '<div class="imooc-app">'.$obj->appends($params)->render().'</div>';
}

/**
 * @param $courseID
 * 获取课程名字
 */
function getCourseName($courseID)
{
    if(!$courseID){
        return '';
    }
//    halt($courseID);
    $course = \app\admin\model\Course::all();
    foreach ($course as $k => $v){
        if($course[$k]['id'] == $courseID){
            return $course[$k]['coursename'];
        }
    }
}

/**
 * @param $tID
 * @return string
 * 获取内容标题
 */
function getContentTitle($tid)
{
    if(!$tid){
        return '';
    }
//    halt($courseID);
//    $course = \app\admin\model\Course::all();
    $content = \app\api\model\Content::get($tid);
    if($content){
        return $content['title'];
    }else{
        return '';
    }
}

function getUserNickname($uid)
{
    if(!$uid){
        return '';
    }
//    halt($courseID);
//    $course = \app\admin\model\Course::all();
    $content = \app\api\model\User::get($uid);
    if($content['nickName']){
        return $content['nickName'];
    }else{
        return '空白昵称';
    }

}

