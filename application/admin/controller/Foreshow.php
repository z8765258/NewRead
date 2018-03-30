<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/30
 * Time: 15:36
 */

namespace app\admin\controller;
use app\admin\model\Foreshow as ForeshowModel;
use app\common\lib\Upload;
use think\Exception;

class Foreshow extends BaseController
{
    public function index()
    {
        $course = new ForeshowModel();
        $res = $course->getCourse();
        return $this->fetch('',[
            'course' => $res
        ]);
    }

    public function add()
    {
        if(request()->isPost()) {
            $course = new ForeshowModel();
            $data = input('post.');
            // 数据需要做检验 validate机制小伙伴自行完成
            $audio = Upload::files('audio');
            if(!$audio){
                explode('文件上传失败',400);
            }
            $data['audio'] = config('qiniu.audio_url').'/'.$audio;
            //入库操作
            try {
                $id = $course->add($data);
            }catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            if($id) {
//                return $this->result(['jump_url' => url('orale/index')], 1, 'OK');
                return $this->success('新增成功','foreshow/index');
            } else {
                return $this->error('新增失败');
            }
        }else {
            $course = \app\admin\model\Course::all();
            return $this->fetch('',[
                'course' => $course
            ]);
        }
    }

    public function edit()
    {
        $course = new ForeshowModel();
        if(request()->isPost()){
            $data = input('post.');
            $audio = Upload::files('audio');
            if($audio){
                $data['audio'] = config('qiniu.audio_url').'/'.$audio;
            }

            $save = $course->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $find = $course->where('id','=',input('id'))->find();
        $course = \app\admin\model\Course::all();
        return $this->fetch('',[
            'res' => $find,
            'course' => $course
        ]);
    }

    public function delete($id = 0)
    {
        $course = new ForeshowModel();
        try{
            $res = $course->where('id','=',$id)->delete();
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