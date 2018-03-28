<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/21
 * Time: 15:22
 */

namespace app\api\service;


use app\lib\exception\OrderException;

class DeliveryMessage
{
    const DELIVEBY_MSG_ID = '模板ID';

    public function sendDeliveryMessage($order,$tplJumpPage = '')
    {
        if(!$order){
            throw new OrderException();
        }
        $this->tplID = self::DELIVEBY_MSG_ID;
        $this->formID = $order->prepay_id;
        $this->page = $tplJumpPage;
        $this->prepareMessageData($order);
        $this->emphasisKeyWord = 'da';
        return parent::sendMessage($this->getUserOpenID($order->user_id));
    }

    private function prepareMessageData()
    {
        $dt = new \DateTime();
        $data = [
            'keyword1' => [
                'value' => '日语口语练习',
                'color' => '#274088'
            ],
            // 'keyword2' => [
            // 'value' => "今日练习任务《".$content['title']."》",
            // 'color'=> '#274088'
            // ],
            'keyword2' => [
                'value' => 'das',
                'color'=> '#274088'
            ],
            'keyword3' => [
                'value' => '口语练习',
                'color'=> '#274088'
            ]
        ];
        $this->data = $data;
    }
}