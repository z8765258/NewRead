<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/14
 * Time: 13:44
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use app\lib\exception\TokenException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck()
    {
        //获取http传入的参数
        //对参数进行校验
        $request = Request::instance();
        $param = $request->param();

        $result = $this->batch()->check($param);

//        halt($param);
        if(!$result){
            $e = new ParameterException();
            $e->msg = $this->error;
            throw $e;
        }else{
            return true;
        }
    }


    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;

        }else{
            return false;
//            return $field.'必须是正整数?';
        }
    }

    protected function isNotEmpty($value,$rule='',$data='',$field='')
    {
//        var_dump($value);
        if(empty($value)){

            return false;

        }else{
            return true;
//            return $field.'必须是正整数?';
        }
    }
}