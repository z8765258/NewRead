<?php
/**
 * Learn PHP to marry Aq.
 * User: PVer
 * Date: 2018/3/19
 * Time: 10:01
 */

namespace app\admin\controller;


use app\admin\model\AdminUser;
use app\common\lib\IAuth;

class Login extends BaseController
{
    public function _initialize()
    {

    }

    public function index()
    {
        $isLogin = $this->isLogin();
        if($isLogin){
            return $this->redirect('index/index');
        }else{
            return $this->fetch();
        }
    }

    public function check() {
        if(request()->isPost()) {
            $data = input('post.');
            $adminUser = new AdminUser();
//            var_dump($data);die;
            if (!captcha_check($data['code'])) {
                $this->error('验证码不正确');
            }
            // 判定 username password
            // validate机制 小伙伴自行完成
            try {
                $user = $adminUser->get(['username' =>$data['username']]);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }

            if (!$user || $user->status != config('code.status_normal')) {
                $this->error('改用户不存在');
            }

            // 再对密码进行校验
            if (IAuth::setPassword($data['password']) != $user['password']) {
                $this->error('密码不正确');
            }

            // 1 更新数据库 登录时间 登录ip
            $udata = [
                'last_login_time' => time(),
                'last_login_ip' => request()->ip(),
            ];
            try {
                $adminUser->save($udata,['id' => $user->id]);
            }catch (\Exception $e) {
                $this->error($e->getMessage());
            }
            // 2 session
            session(config('admin.session_user'), $user, config('admin.session_user_scope'));
            $this->success('登录成功', 'index/index');

            //halt($user);
        }else {
            $this->error('请求不合法');
        }
    }

}