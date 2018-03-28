<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/20
 * Time: 9:34
 */

namespace app\admin\model;


class Card extends Base
{
    public static function getCard($data = [])
    {
        $order = ['id'=>'desc'];
        $result = self::order($order)->paginate();
        return $result;
    }

    /**
     * 根据条件来获取数据
     * @param array $param
     */
    public static function getCardByCondition($condition = [] ,$from,$size)
    {
        $order = ['id'=>'desc'];
        $result = self::where($condition)->limit($from,$size)->order($order)->select();
        return $result;
    }

    /**
     * 根据条件获取列表数据的总数
     * @param array $param
     */
    public static function getCardCountByConditon($param = [])
    {
        return self::count();
    }
}