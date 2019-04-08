<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\AdminSidebar;
use app\common\model\AuthAdminGroupAccess;
use think\Db;
use think\facade\Log;
use think\facade\Session;


class Index extends Admin
{
    public function index()
    {
        $sidebar = $this->getSidebar();
        if ($this->admin_info['id'] == 1) {
            $group_title = '超级管理员';
        } else {
            $group_id = AuthAdminGroupAccess::get(['uid' => $this->admin_info['id']]);
            $group_info = \app\common\model\AuthAdminGroup::get(['id' => $group_id->group_id]);
            $group_title = $group_info->title;
        }
        $this->assign('group_title', $group_title);
        $this->assign('admin_info', $this->admin_info);
        $this->assign('sidebar', $sidebar);
        return view();
    }

    /**
     * 获取侧边栏
     * @return array|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    private function getSidebar()
    {
        $side = AdminSidebar::where([
            'tid' => 0,
            'status' => 1
        ])->order('sort')->select();
        $side_child = AdminSidebar::where('status',1)
            ->where('tid','<>',0)
            ->order('sort')
            ->select();
        $list = [];
        foreach ($side as $key => $value) {
            $list[$key] = $value->toArray();
            $child = array();
            foreach ($side_child as $_key => $_value) {
                if ($_value['tid'] == $value['id']) {
                    if ($this->admin_info['id'] == 1) {
                        $child[] = $_value;
                    } else {

                        if ($this->auth->check($_value['url'], $this->admin_info['id'])) {
                            $child[] = $_value;
                        }
                    }
                    unset($side_child[$_key]);
                }
            }
            if (!empty($child)) {
                $list[$key]['child'] = $child;
            } else {
                unset($list[$key]);
            }
        }
        return $list;
    }

    /**
     * 获取图表数据
     * @return array
     */
    private function getOrderCount()
    {
        $end_time = strtotime(date('Y-m-d'));
        $star_time = $end_time - (3600 * 24 * 13);

        $sql = 'SELECT
	a.date AS every_day, UNIX_TIMESTAMP(a.date) as every_day_time 
FROM
	cl_calendar a
WHERE UNIX_TIMESTAMP(a.date) >= ' . $star_time . ' AND UNIX_TIMESTAMP(a.date) <= ' . $end_time;

        $calendar = Db::query($sql);

        $list = [
            'date' => [],
            'num' => [],
            'total_fee' => []
        ];

        foreach ($calendar as $item) {
            $count = Db::query('SELECT
			count(id) as sum, SUM(total_fee) AS total_fee 
		FROM
			cl_order
		WHERE
			UNIX_TIMESTAMP(FROM_UNIXTIME(pay_time, \'%Y-%m-%d\')) = ' . $item['every_day_time'] . ' AND `status` = 1');
            $num = $count[0]['sum'];
            $total_fee = $count[0]['total_fee'];

            $list['date'][] = $item['every_day'];
            $list['num'][] = $num;
            $total = 0;
            if ($total_fee > 0) {
                $total = $total_fee;
            }
            $list['total_fee'][] = $total;
        }

        return $list;
    }

    /**
     * 获取统计数据
     * @return array
     */
    private function getCount()
    {
        $now_day_star = strtotime(date('Y-m-d'));
        $now_day_end = strtotime(date('Y-m-d')) + (3600 * 24) - 1;

        $now_where = [
            ['status', 'eq', 1],
            ['pay_time', 'between', $now_day_star . ',' . $now_day_end]
        ];
        $total_where = [
            ['status', 'eq', 1]
        ];

        $data = [
            'transaction' => [
                'now' => Db::name('order')->where($now_where)->sum('total_fee'),
                'total' => Db::name('order')->where($total_where)->sum('total_fee'),
            ],
            'order_num' => [
                'now' => Db::name('order')->where($now_where)->count(),
                'total' => Db::name('order')->where($total_where)->count(),
            ],
            'recharge' => [
                'now' => 0,//Db::name('merchant_recharge')->where($now_where)->count(),
                'total' => 0,//Db::name('merchant_recharge')->where($total_where)->count(),
            ]
        ];

        return $data;
    }


    public function welcome()
    {

        if (request()->isPost()) {
            $params = request()->param();
            $type = $params['type'];
            if ($type == 'chart_data') {
                return rJson(true, '获取成功', $this->getOrderCount());
            }
        }

        $list = [
            'date' => [],
            'num' => [],
            'total_fee' => []
        ];
        return view()->assign([
            'chart_data' => $list,
            'count' => $this->getCount()
        ]);
    }

    /**
     * @desc: 修改密码
     * @author: Tinywan(ShaoBo Wan)
     * @time: 2019/3/29 10:16
     * @return \think\response\Json
     */
    public function updatePassword()
    {
        $admin_id = Session::get('admin_info')['id'];
        Log::info('修改密码: '.$admin_id);
        $data = request()->post('password');
        if (empty($data)) return rJson(false, '请输入新密码！');
        $salt = rand_char();
        $password = md5(md5($data) . md5($salt));

        $admin = \app\common\model\Admin::get($admin_id);
        if (empty($admin)) {
            return rJson(true, '用户不存在！');
        }
        $result = $admin->isUpdate(true)->save([
            'password' => $password,
            'salt' => $salt
        ]);

        if ($result) {
            return rJson(true, '修改成功！');
        } else {
            return rJson(false, '修改失败！');
        }
    }

}
