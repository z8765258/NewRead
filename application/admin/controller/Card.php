<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/20
 * Time: 9:32
 */

namespace app\admin\controller;
use app\admin\model\Card as CardModel;
use think\Exception;

class Card extends BaseController
{
    public function index()
    {
        $data = input('param.');
        $query = http_build_query($data);
        $whereData = [];
//        $whereData['page'] = !empty($data['page']) ? $data['page'] : 1;
//        $whereData['size'] = !empty($data['size']) ? $data['size'] : config('paginate.list_rows');
        if(!empty($data['start_time']) && !empty($data['end_time']) && $data['end_time'] > $data['start_time']){
            $whereData['create_time'] = [
                ['gt', strtotime($data['start_time'])],
                ['lt', strtotime($data['end_time'])],
            ];
        }

        $this->getPageAndSize($data);
        $card = CardModel::getCardByCondition($whereData,$this->from,$this->size);
        $total = CardModel::getCardCountByConditon($whereData);
        //结合总数+size = 有多少页
        $pageTotal = ceil($total/$this->size);   //1.1=>2
        return $this->fetch('',[
            'card' => $card,
            'pageTotal' => $pageTotal,
            'curr' => $this->page,
            'start_time' => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time' => empty($data['end_time']) ? '' : $data['end_time'],
            'query' => $query
        ]);
    }

    public function delete($id = 0)
    {
        $card = new CardModel();
        try{
            $res = $card->where('id','=',$id)->delete();
//            db('jp_article')->where('oid','=',$id)->delete();
        }catch (Exception $e){
            explode($e->getMessage(),400);
        }
        if($res){
            $this->success('删除成功,!',url('index'));
        }else{
            $this->error('删除失败');
        }
    }
}