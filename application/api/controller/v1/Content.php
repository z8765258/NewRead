<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 17:22
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Content as ContentModel;
use app\api\validate\DayMustBeInt;
use app\api\validate\isTypeParam;
use app\lib\exception\MissException;

class Content extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getTodayContent,getTypeContentNums']
    ];

    /**
     * @param int $day int $type
     * @return \think\response\Json
     * 根据类型获取某天所有内容（新闻，重点句子，音频，题目）
     */
    public function getTodayContent($day = 0 , $type = 1)
    {
        (new DayMustBeInt())->goCheck();
        $content = ContentModel::getDayContent($day,$type);
        return $content;
    }

    public function getTypeContentNums($type)
    {
        (new isTypeParam())->goCheck();
        $num = ContentModel::getTypeContentNums($type);
        if(!$num){
            throw new MissException([
                'msg' => '当前课程没有上传内容'
            ]);
        }
        return json(['typeNum' => $num]);
    }
}