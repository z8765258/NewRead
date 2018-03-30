<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/30
 * Time: 15:57
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\model\Foreshow as ForeshowModel;
class Foreshow extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getForeshow'],
    ];

    public function getForeshow($id = '')
    {
        (new IDMustBePositiveInt())->goCheck();
        return ForeshowModel::getForeshow($id);
    }
}