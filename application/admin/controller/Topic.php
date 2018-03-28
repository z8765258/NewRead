<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 17:06
 */

namespace app\admin\controller;
use app\admin\model\Options;
use app\admin\model\Topic as TopicModel;
use app\admin\model\Options as OptionsModel;
use think\Exception;

class Topic extends BaseController
{
    public function index()
    {
        $topic = new TopicModel();
        $toList = $topic->getTopic();
        return $this->fetch('',[
            'topic' => $toList,
        ]);
    }

    public function edit()
    {
        $topic = new TopicModel();
        if(request()->isPost()){
            $data = input('post.');
            $save = $topic->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $find = $topic->getTopicFind(input('id'));
        return $this->fetch('',[
            'res' => $find,
        ]);
    }

    public function editOpt()
    {
        $topic = new OptionsModel();
        if(request()->isPost()){
            $data = input('post.');
//            halt($data);
            $save = $topic->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $options = Options::get(input('id'));
        return $this->fetch('',[
            'res' => $options,
        ]);
    }

    public function optionList()
    {
        $option = new OptionsModel();
        $toList = $option->getOptionFind(input('id'));
        return $this->fetch('',[
            'options' => $toList,
        ]);
    }

    public function delete($id = 0)
    {
        $statement = new \app\admin\model\Topic();
        try{
            $res = $statement->where('id','=',$id)->delete();
            db('options')->where('tid',$id)->delete();
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