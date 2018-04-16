<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 14:53
 */

namespace app\admin\controller;
use app\admin\model\Content as ContentModel;
use app\admin\model\Statement as StatementModel;
use app\common\lib\Upload as UploadLib;
use think\Exception;

class Content extends BaseController
{
    public function index()
    {

        $content = new ContentModel();
        $res = $content->getContent();
//        halt($res);
        return $this->fetch('',[
            'content' => $res
        ]);
    }

    public function add()
    {
        if(request()->isPost()){
            $content = new ContentModel();
            $data = input('post.');
            $audio = UploadLib::files('leaaudio');
            $detailAudio = UploadLib::files('detailaudio');
            if($audio){
                $data['leaaudio'] = config('qiniu.audio_url').'/'.$audio;
            }
            if($detailAudio){
                $data['detailaudio'] = config('qiniu.audio_url').'/'.$detailAudio;
            }

            try{
                $id = $content->add($data);
            }catch (\Exception $e){
                return $this->error('添加失败');
            }
            if($id){
                return $this->success('添加成功！','index');
            }
        }else{
            $course = \app\admin\model\Course::all();
            return $this->fetch('',[
                'course' => $course
            ]);
        }
    }

    public function delete($id = 0)
    {
        $content = new ContentModel();
        try{
            $res = $content->where('id','=',$id)->delete();
//            db('statement')->where('cid','=',$id)->delete();
//            db('topic')->where('cid','=',$id)->delete();
        }catch (Exception $e){
            explode($e->getMessage(),400);
        }
        if($res){
            $this->success('删除成功,!',url('index'));
        }else{
            $this->error('删除失败');
        }
    }

    public function edit()
    {
        $content = new ContentModel();
        if(request()->isPost()){
            $data = input('post.');
            $image = UploadLib::files('cardimg');
            $audio = UploadLib::files('leaaudio');
            if($image){
                $data['cardimg'] = config('qiniu.audio_url').'/'.$image;
            }
           if($audio){
               $data['leaaudio'] = config('qiniu.audio_url').'/'.$audio;
           }

            $save = $content->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $find = $content->where('id','=',input('id'))->find();
        $course = \app\admin\model\Course::all();
        return $this->fetch('',[
            'res' => $find,
            'course' => $course
        ]);
    }

    public function addSta($id = 0)
    {
        if(request()->isPost()){
            $statement = new StatementModel();
            $data = input('post.');
            $audio = UploadLib::files('staaudio');
            if(!$audio){
                explode('文件上传失败',400);
            }
            $data['staaudio'] = config('qiniu.audio_url').'/'.$audio;
//            halt($data);
            try{
                $id = $statement->add($data);
            }catch (\Exception $e){
                return $this->error('添加失败');
            }
            if($id){
                return $this->success('添加成功！','index');
            }
        }else{

            return $this->fetch('',[
                'id' => $id
            ]);
        }
    }

    public function addTop($id = 0)
    {
        if(request()->isPost()){
            $topic = new \app\admin\model\Topic();
            $data = input('post.');
            $options = $data['options'];
            unset($data['options']);
            $id = $topic->add($data);
            $this->addOption($id,$options);
            if($id){
                return $this->success('添加成功！','index');
            }else{
                return $this->error('添加失败！');
            }
        }else{

            return $this->fetch('',[
                'id' => $id
            ]);
        }
    }

    private function addOption($id,$options)
    {
        $data = [
                ['tid'=>$id,'options' => $options[0],'num'=>'A'],
                ['tid'=>$id,'options' => $options[1],'num'=>'B'],
                ['tid'=>$id,'options' => $options[2],'num'=>'C'],
                ['tid'=>$id,'options' => $options[3],'num'=>'D']
        ];
        db('options')->insertAll($data);
//        return true;
    }
}