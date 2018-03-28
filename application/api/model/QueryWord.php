<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/22
 * Time: 16:41
 */

namespace app\api\model;


class QueryWord extends BaseModel
{
    private $url = "http://nadouread.ixunke.cn/api/public/search_word?keyword=%s";

    public function __construct($word)
    {
        $this->url = sprintf($this->url,urlencode($word));
    }

    public function queryWord()
    {

        $result = curl_get($this->url);
        $result = json_decode($result,true);
        $arr = $this->packageArr($result);
        return $arr;
    }

    private function packageArr($result)
    {
        $arr = [];
        if(count($result['data']) < 3){
            $arr = $result['data'];
            return $arr;
        }
        for ($i=0;$i<3;$i++){
            array_push($arr,$result['data'][$i]);
        }
        return $arr;
    }
}