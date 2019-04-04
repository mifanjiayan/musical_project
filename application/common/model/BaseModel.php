<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-4
 * Time: 上午11:33
 */

namespace app\common\model;

use think\facade\Cache;
use think\facade\Log;
use think\facade\Request;
use think\facade\Session;
use think\Model;

class BaseModel extends Model
{
    protected static function init()
    {
        self::beforeUpdate(function ($model) {
            self::recordOperateLogs($model, '更新之前');
        });
        self::afterUpdate(function ($model) {
            $cacheName = strtolower($model->name) . ':' . $model[$model->pk];
            if (Cache::has($cacheName)) {
                Log::debug("[更新] 删除缓存KEY " . $cacheName);
                Cache::rm($cacheName);
            }
        });

        self::beforeDelete(function ($model) {
            self::recordOperateLogs($model, '删除之前');
        });
        self::afterDelete(function ($model) {
            $cacheName = strtolower($model->name) . ':' . $model[$model->pk];
            if (Cache::has($cacheName)) {
                Log::debug("[删除] 删除缓存KEY " . $cacheName);
                Cache::rm($cacheName);
            }
        });
    }

    /**
     * @desc: 获取图片url地址
     * @author: Tinywan(ShaoBo Wan)
     * @time: 2019/3/29 11:06
     * @param $value
     * @param $data
     * @return string
     */
    protected function prefixImgUrl($value, $data)
    {
        $finalUrl = $value;
        if ($data['from'] == 1) {
            $finalUrl = config('setting.img_prefix') . $value;
        }
        return $finalUrl;
    }

    /**
     * @desc: 记录操作日志
     * @author: Tinywan(ShaoBo Wan)
     * @time: 2019/3/29 11:09
     * @param $model
     * @return mixed
     */
    protected static function recordOperateLogs($model, $event_type)
    {
        $base_name = basename(str_replace('\\', '/', get_called_class()), '.php');
        $request = Request::instance();
        $module = $request->module();
        $controller = $request->controller();
        $action = $request->action();
        $data["account"] = Session::get('admin_info')['username'];
        $data['event_type'] = $event_type;
        $data['url'] = $request->url();
        $data['module'] = $module;
        $data['controller'] = $controller;
        $data['action'] = $action;
        $data['model'] = $base_name;
        $data["query_string"] = json_encode($request->param());
        $data["ipaddr"] = $request->ip();
        $data["addtime"] = $request->time();
        $data["desc"] = $request->query();
        $model->name('logs')->insert($data);
    }
}