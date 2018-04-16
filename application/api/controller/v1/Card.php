<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/16
 * Time: 15:07
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\model\Card as CardModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\CardException;

class Card extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createCardRecord,getCardList,getCardRanking,verifyCard,getToDayCard'],
    ];

    /**
     * @param $id 课程类型id
     * 添加一条用户打卡记录
     */
    public function createCardRecord($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $res = CardModel::createCardRecord($id);
        return $res;
    }

    /**
     * @param $id 课程类型id
     *  获取此用户某课程下的所有打卡记录
     */
    public function getCardList($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $list = CardModel::getCardList($id);
        return $list;
    }

    public function getToDayCard($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        return CardModel::isHaveRecord($id);
    }

    /**
     * @param $id
     * @return \think\response\Json
     * 获取用户今日打卡的排名
     */
    public function getCardRanking($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $rank = CardModel::getCardRank($id);
        return json($rank);
    }

    public function verifyCard($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $status = CardModel::verifyCards($id);
        if(!$status){
            throw new CardException();
        }
        return json(['isComplete' => $status,'errorCode' => 0]);
    }
}