<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-4
 * Time: 上午11:33
 */

namespace app\common\controller;

use think\Controller;

class BaseController extends Controller
{
//    protected $middleware = ['Auth'];

    /**
     * 错误代码列表
     */
    const errorList = [
        0 => '请求成功',
        -1 => '系统繁忙',
    ];

    /**
     * @var array 错误
     */
    protected $error = [
        'success' => false,
        'msg' => '未知错误',
        'errorCode' => 0
    ];

    /**
     * 设置错误信息
     * @param $success
     * @param $message
     * @param int $code
     * @return mixed
     */
    protected function setError($success, $message, $code = 0)
    {
        $this->error = [
            'success' => $success,
            'msg' => $message,
            'errorCode' => $code
        ];
        return $success;
    }

    /**
     * 获取错误信息
     * @return array
     */
    protected function getError()
    {
        return $this->error;
    }


}
