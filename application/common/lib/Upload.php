<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/12
 * Time: 16:59
 */

namespace app\common\lib;

use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use think\Loader;
Loader::import('Qiniu.php-sdk.autoload',EXTEND_PATH,'.php');
class Upload
{
    public static function files($fileName)
    {
        if(empty($_FILES[$fileName]['file']['tmp_name'])){
            explode('提交的文件不合法',404);
        }

        $filePath = $_FILES[$fileName]['tmp_name'];
        $ext = explode('.',$_FILES[$fileName]['name']);
        $ext = $ext[1];
        $config = config('qiniu');
        $auth = new Auth($config['ak'],$config['sk']);

        //生成上传的token
        $token = $auth->uploadToken($config['bucket']);
        //上传到七牛后保存的文件名
        $key = date('Y')."/".date('m')."/".substr(md5($filePath),0,5).date('YmdHis').rand(0,9999).".".$ext;
        $uploadMgr = new UploadManager();
        list($ret,$err) = $uploadMgr->putFile($token,$key,$filePath);
        if($err !== null){
            return null;
        }else{
            return $key;
        }

    }




//    public static function image()
//    {
//        if(empty($_FILES['oralesound']['file']['tmp_name']) || empty($_FILES['extendsound']['file']['tmp_name'])){
//            explode('提交的文件不合法',404);
//        }
//
//        $filePathO = $_FILES['oralesound']['tmp_name'];
//        $filePathE = $_FILES['extendsound']['tmp_name'];
//        $extO = explode('.',$_FILES['oralesound']['name']);
//        $extE = explode('.',$_FILES['extendsound']['name']);
//        $extO = $extO[1];
//        $extE = $extE[1];
//        $config = config('Qiniu');
////UploadManager::
//
//        $auth = new Auth($config['ak'],$config['sk']);
////       $auth = new Auth();
//
//        //生成上传的token
//        $token = $auth->uploadToken($config['bucket']);
//        //上传到七牛后保存的文件名
//        $keyO = date('Y')."/".date('m')."/".substr(md5($filePathO),0,5).date('YmdHis').rand(0,9999).".".$extO;
//        $keyE = date('Y')."/".date('m')."/".substr(md5($filePathE),0,5).date('YmdHis').rand(0,9999).".".$extE;
//
//        $uploadMgr = new UploadManager();
//        list($retO,$errO) = $uploadMgr->putFile($token,$keyO,$filePathO);
//        list($retE,$errE) = $uploadMgr->putFile($token,$keyE,$filePathE);
//        if($errO !== null && $errE !== null){
//            return null;
//        }else{
//            $audios = ['oralesound'=>$keyO,'extendsound'=>$keyE];
//            return $audios;
//        }
//
//    }

}