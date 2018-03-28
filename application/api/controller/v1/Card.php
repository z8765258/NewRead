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

class Card extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createCardRecord,getCardList,getCardRanking'],
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

    public function getCardRanking($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $rank = CardModel::getCardRank($id);
        return json($rank);
    }
}