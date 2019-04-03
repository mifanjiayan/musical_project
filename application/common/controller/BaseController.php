<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2019/3/23 10:33
 * |  Mail: 756684177@qq.com
 * |  Desc: 控制器基类
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\controller;

use GatewayWorker\Lib\Gateway;
use think\Controller;

class BaseController extends Controller
{
    protected $middleware = ['auth'];

    // 短信验证码KEY
    const SMS_CODE_KEY = 'SMS_CODE:';
    const CHECK_PAY_PASSWD_KEY = 'CHECK_PAY_PASSWD:';

    /**
     * 错误代码列表
     */
    const errorList = [
        0 => '请求成功',
        -1 => '系统繁忙',
        40001 => '请求参数里面必须要的参数',
        40002 => '请求方法不存在',
        40003 => '签名错误',
        40004 => '账户不存在',
        40005 => '账户已被停用',
        40006 => '账户资料未认证',
        40007 => '子商户不存在',
        43001 => 'Token验证失败',
        43002 => 'Token长时间未使用而过期，需重新登陆',
        43004 => 'Token不能为空',
        43005 => 'APP_TOKEN不存在，或者已过期',
        45009 => '接口调用超过限制,请稍后重试',
        46001 => '账户余额不足',
        45002 => '接口调用超过限制',
        50001 => '服务器内部错误',
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
