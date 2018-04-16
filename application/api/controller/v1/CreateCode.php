<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/4/10
 * Time: 9:59
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\CreateCode as CreateCodeService;

class CreateCode extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'code'],
    ];
    public function code()
    {

        $filePath = 'image/'.$this->imageName() . '.jpg';
        if(file_exists($filePath)) {
//            echo '存在文件';
            return 'https://riyubao.net/NewRead/public/'.$filePath;
        }
        $code = new CreateCodeService();
        $basecode = $code->sendCreateCode();
        $src = $this->chuliBaseCode($basecode,'image/png');
        return 'https://riyubao.net/NewRead/public/'.$src;
    }

    private function imageName(){

        $uid = \app\api\service\Token::getCurrentUid();
        $hat = md5(config('app.password_pre_halt'));
        return $uid.$hat;
    }

    private function chuliBaseCode($contents)
    {
        $imgDir = 'image/';
        $filename=$this->imageName().'.jpg';
        $xmlstr = $contents;
        $jpg = $xmlstr;//得到post过来的二进制原始数据
        if(empty($jpg)) {
            echo 'nostream';
            exit();
        }
        $file = fopen($imgDir.$filename,"w");//打开文件准备写入
        fwrite($file,$jpg);//写入
        fclose($file);//关闭
        $filePath =$imgDir.$filename;
        return $filePath;
    }
}