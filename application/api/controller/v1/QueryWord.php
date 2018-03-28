<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/22
 * Time: 16:41
 */

namespace app\api\controller\v1;
use app\api\model\QueryWord as QueryWordModel;

class QueryWord
{
    public function queryWord($word)
    {
        $word = new QueryWordModel($word);
        $result = $word->queryWord();
        return $result;
    }
}