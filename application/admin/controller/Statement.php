<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 17:06
 */

namespace app\admin\controller;
use app\admin\model\Statement as StatementModel;
use app\common\lib\Upload as UploadLib;
use think\Exception;

class Statement extends BaseController
{
    public function index()
    {
        $statement = new StatementModel();
        $staList = $statement->getStatement();
        return $this->fetch('',[
            'statement' => $staList
        ]);
    }

    public function edit()
    {
        $statement = new StatementModel();
        if(request()->isPost()){
            $data = input('post.');
            $audio = UploadLib::files('staaudio');
            $data['staaudio'] = config('qiniu.audio_url').'/'.$audio;

            $save = $statement->save($data,['id' => input('id')]);
            if($save !== false){
                $this->success('修改成功！',url('index'));
            }else{
                $this->error('修改失败！');
            }
        }
        $find = $statement->where('id','=',input('id'))->find();
        return $this->fetch('',[
            'res' => $find,

        ]);
    }

    public function delete($id = 0)
    {
        $statement = new StatementModel();
        try{
            $res = $statement->where('id','=',$id)->delete();
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