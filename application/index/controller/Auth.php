<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-4
 * Time: 上午11:33
 */

namespace app\index\controller;

use app\common\controller\BaseController;
use think\Session;

class  Auth extends BaseController
{
    /**
     * 管理员后台登陆
     * @return array|\think\response\View
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function adminLogin()
    {
//        if (Session::has('admin_info')) {
//            $this->redirect('admin/index/index');
//        }
//        if ($this->request->isPost()) {
//            $data = request()->post();
//            if (empty($data['username']) || empty($data['password'])) {
//                return responseJson(false, -1, '请输入用户名或密码');
//            }
//            if (empty($data['captcha']) || !captcha_check($data['captcha'])) {
//                return responseJson(false, -1, '验证码错误');
//            };
//            $admin = Admin::where(['username' => $data['username']])->find();
//            if (empty($admin)) {
//                return responseJson(false, -1, '账号或密码错误');
//            }
//            $user = Admin::where([
//                'username' => $data['username'],
//                'password' => md5(md5($data['password']) . md5($admin->salt))
//            ])->find();
//
//            if (empty($user)) {
//                return responseJson(false, -1, '账号或密码错误');
//            }
//            $ip = request()->ip();
//
//            $user->login_ip = $ip;
//            $user->login_time = time();
//            $user->save();
//
//            AdminOperateLogs::create([
//                'uid' => $user->id,
//                'remark' => '用户[' . $user->username . ']登录',
//                'ip' => $ip,
//                'created_at' => date('Y-m-d H:i:s', time()),
//                'type' => 2,
//                'from' => 'admin'
//            ]);
//            Session::set('admin_info', $user->toArray());
//            return responseJson(true, 0, '登录成功');
//        }
        return view('admin_login');
    }
}