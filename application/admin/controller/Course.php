<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 11:05
 */

namespace app\admin\controller;
use app\admin\model\Course as CourseModel;
use app\common\lib\Upload as UploadLib;
use think\Exception;

class Course extends BaseController
{
    public function index()
    {
        $course = new CourseModel();
        $res = $course->getCourse();
        return $this->fetch('',[
            'course' => $res
        ]);
    }

    public function add()
    {
        if(request()->isPost()) {
            $course = new CourseModel();
            $data = input('post.');
            // 数据需要做检验 validate机制小伙伴自行完成
            $image = UploadLib::files('img');
            if(!$image){
                explode('文件上传失败',400);
            }
            $data['img'] = config('qiniu.audio_url').'/'.$image;
            //入库操作
            try {
                $id = $course->add($data);
            }catch (\Exception $e) {
                return $this->error($e->getMessage());
            }
            if($id) {
//                return $this->result(['jump_url' => url('orale/index')], 1, 'OK');
                return $this->success('新增成功','course/index');
            } else {
                return $this->error('新增失败');
            }
        }else {
            return $this->fetch();
        }
    }

    public function edit()
    {
        $course = new CourseModel();
        if(request()->isPost()){
            $data = input('post.');
            $image = UploadLib::files('img');
            $data['img'] = config('qiniu.audio_url').'/'.$image;
            $save = $course->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $find = $course->where('id','=',input('id'))->find();
        return $this->fetch('',[
            'res' => $find
        ]);
    }

    public function delete($id = 0)
    {
        $course = new CourseModel();
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