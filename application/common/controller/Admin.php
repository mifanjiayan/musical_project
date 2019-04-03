<?php

namespace app\common\controller;

use app\common\library\Auth;
use think\Db;
use think\facade\Cache;
use think\facade\Session;

class Admin extends BaseController
{
    protected static $admin;
    protected $auth;
    protected $admin_info;
    protected $sidebar;

    /**
     * Base constructor.
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function initialize()
    {
        parent::initialize();
        //验证登录状态
        if (!$this->checkLogin()){
            $this->redirect('/admin/login');
        }

        $this->auth = new Auth();
        $this->admin_info = get_admin_info();
        // 验证权限(排除id为1的系统管理员)
        if ($this->admin_info['id'] != 1 && !$this->checkRole()){
            if ($this->request->isAjax()){
                responseJson(false, -1, '权限不足！');
            }else{
                $this->error('权限不足！');
            }
        }
        $this->assign('system_config', $this->getSystemConfig());
    }

    /**
     * 获取系统配置
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getSystemConfig()
    {
        if (Cache::has('system_config')) {
            return Cache::get('system_config');
        } else {
            $system_config = [];
            $list = Db::name('system_config')->select();
            foreach ($list as $item) {
                $system_config[$item['config_name']] = $item;
            }
            Cache::set('system_config', $system_config);
            return $system_config;
        }
    }

    /**
     * 验证登录
     * @return bool
     */
    private function checkLogin()
    {
        if (Session::has('admin_info'))
        {
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证权限
     * @param string $role
     * @return bool
     */
    protected function checkRole($role = '')
    {
        $uid = $this->admin_info['id'];
        $module = tf_to_xhx(request()->module());
        $controller = tf_to_xhx(request()->controller());
        $action = tf_to_xhx(request()->action());

        if ($action == 'excelexportdata'){
            return true;
        }
        $routeName = $module.'/'.$controller.'/'.$action;
        //排除权限
        if (in_array($routeName, config('auth.public'))){
            return true;
        }
        if ($role == ''){
            $role = $controller;
        }

        switch ($routeName) {
            case 'admin/'.$role.'/index':
                $permission = 'admin/'.$role.'/index'; break;
            case 'admin/'.$role.'/create':
                $permission = 'admin/'.$role.'/create'; break;
            case 'admin/'.$role.'/save':
                $permission = 'admin/'.$role.'/create'; break;
            case 'admin/'.$role.'/edit':
                $permission = 'admin/'.$role.'/edit'; break;
            case 'admin/'.$role.'/update':
                $permission = 'admin/'.$role.'/edit'; break;
                break;
            case 'admin/'.$role.'/delete':
                $permission = 'admin/'.$role.'/delete'; break;
                break;
            default:
                $permission = $routeName;
                break;
        }
        if (!$this->auth->check($permission, $uid)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 清除缓存
     * @return bool
     */
    protected function rmCache()
    {
        if (Cache::clear()){
            return true;
        }else{
            return false;
        }
    }
}
