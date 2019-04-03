<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use think\facade\Config;
use think\facade\Log;

/**
 * RSA解密
 * @param $data
 * @return mixed
 */
function rsa_decode($data)
{
    $private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICWwIBAAKBgQCbtLA7lMfUvpBgfgzouiPgcnbLDnEcuCK0gMub/EAEqmr82sl+
9tH1iQb1w/hgQLptVRxAuUOa03XqlnG3wkAegtQt4Q5ZtHSSomE8/5FXJvQfGTCz
5RARyM0MiLTMZJGhLdVT6O8uCYIrPRQq7u6NVLs96YDmtzX2do/sTsWCAwIDAQAB
AoGAfnO3zCuAPp6k0jiMc1T4XgeXwkDwS8qfJMiUkxHBTAi66q8khSAeU7H9HQsS
Y9ktji1YzJeo98xULzgPEpWHS/uhA8VZa16TLy9Yfadn2t+wpWpEJ9ZA4jjEqfQj
DDxcUc/pEv5siaE/bU8uls4o2nAiuWnI2n5FGrQa2OziGUECQQDPOh3KD2AOZtEF
p7i0yxYXe4dCKwenfw5q7l933RgqMXsVR1EAGzAUdIs71hTye6ibhva+eJRfndoV
Jq2IHjOdAkEAwFpOZR8j3Cl4zEk/9D9WEnSa8VWLe76vb7DfgfwkSAhs/f2MNF1I
zy9W5tPHRiMzaHNgPBFX9tw2u5QzsgOqHwJAPl3zUTjHZA41okoUIPVuNKsMzjE9
IH/wyuXq/ZwhBbHWpVTNYAbOtZlNvjh0HXZyDDzWTgTkQtKzK+J0H59XUQJARukD
vYOdVKx1O9pFGWW/9U3HUPCYWyYQxrwNqX2qYmO4ymmOJj+9d6OcBbxM2i5f5UGj
WIGMTBUimEQqSpXPQQJAIkHC2GknUv8HaBRLXxYTIAjj78a0pQT2bYlI6R04AwUZ
ljBaUGvvdYJ3CGZ32Xk12Te2fMJj5h/yLyEr8uzpzw==
-----END RSA PRIVATE KEY-----';
    $pi_key = openssl_pkey_get_private($private_key);
    openssl_private_decrypt(base64_decode($data), $decrypted, $pi_key);//私钥解密
    return $decrypted;
}

/**
 *  RSA加密
 * @param $data
 * @return string
 */
function rsa_encode($data)
{
    $public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCbtLA7lMfUvpBgfgzouiPgcnbL
DnEcuCK0gMub/EAEqmr82sl+9tH1iQb1w/hgQLptVRxAuUOa03XqlnG3wkAegtQt
4Q5ZtHSSomE8/5FXJvQfGTCz5RARyM0MiLTMZJGhLdVT6O8uCYIrPRQq7u6NVLs9
6YDmtzX2do/sTsWCAwIDAQAB
-----END PUBLIC KEY-----';
    $pu_key = openssl_pkey_get_public($public_key);
    openssl_public_encrypt($data, $encrypted, $pu_key);
    return base64_encode($encrypted);
}

/**
 * 本地Redis
 * @return \Redis
 */
function local_redis()
{
    return \redis\BaseRedis::local();
}

/**
 * 驼峰法转下划线
 * @param $str
 * @return string
 */
function tf_to_xhx($str)
{
    return trim(preg_replace_callback('/([A-Z]{1})/', function ($matches) {
        return '_' . strtolower($matches[0]);
    }, $str), '_');
}

/**
 * 数字转字母 （Excel列标）
 * @param Int $index 索引值
 * @param Int $start 字母起始值
 * @return String 返回字母
 */
function int_to_chr($index, $start = 65)
{
    $str = '';
    if (floor($index / 26) > 0) {
        $str .= int_to_chr(floor($index / 26) - 1);
    }
    return $str . chr($index % 26 + $start);
}

/**
 * 生成随机字符
 * @param int $length
 * @return string
 */
function rand_char($length = 6)
{
    // 密码字符集，可任意添加你需要的字符
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!',
        '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_',
        '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',',
        '.', ';', ':', '/', '?', '|');
    $keys = array_rand($chars, $length);
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        // 将 $length 个数组元素连接成字符串
        $password .= $chars[$keys[$i]];
    }
    return $password;
}

/**
 * 创建一个小写的随机字符串
 * @param int $length 40
 * @return string
 */

function get_rand_string($length = 16)
{
    $chars = "0123456789abcdefghijklmnopqrstuvwxyz9876543210ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return strtolower($str);
}

/**
 * 生成毫秒时间戳
 */
function msectime()
{
    list($msec, $sec) = explode(' ', microtime());
    $msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

/**
 * Curl 请求
 * @param $url
 * @param string $method
 * @param array $data
 * @return mixed
 * @throws Exception
 */
function curl_response($url, $method = 'get', $data = [])
{
    $regx = '@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@';
    if (!preg_match($regx, $url)) {
        throw new Exception('curl请求地址错误(' . $url . ')');
    }
    //初始化
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    if (strtolower($method) == 'post') {
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
    //设置post数据
    $post_data = $data;
    Log::debug('请求数据(' . $url . ')：' . json_encode($post_data));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $result = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    //关闭URL请求
    curl_close($curl);
    if ($httpCode != 200) {
        throw new Exception('请求失败-' . $httpCode, $httpCode);
    }
    //显示获得的数据
    Log::debug('返回数据：' . $result);
    return $result;
}

/**
 * @param string $modelName 模型名称
 * @param int $id 主键id
 * @return string
 */
function get_cache_key($modelName, $id)
{
    return strtolower($modelName) . ':' . $id;
}

/**
 * 支付方式权重
 * @param $data
 * @return mixed
 */
function payment_weight($data)
{
    try {
        $weight = 0;
        $temp = array();
        foreach ($data as $v) {
            $weight += $v['weight'];
            for ($i = 0; $i < $v['weight']; $i++) {
                $temp[] = $v;//放大数组
            }
        }
        $randNum = mt_rand(0, $weight - 1);//获取一个随机数
        $result = $temp[$randNum];
        return $result;
    } catch (\think\Exception $e) {
        Log::error('[支付方式] 权重异常 ' . $e->getMessage());
        return [];
    }
}

/**
 * 判断是否是微信
 * @return bool
 */
function is_weixin()
{
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    }
    return false;
}

function is_mobile()
{
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    if (isset ($_SERVER['HTTP_VIA'])) {
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
}

/**
 * Curl post请求
 * @param $url
 * @param array $data
 * @return mixed
 */
function curl_post($url, $data = [])
{
    //初始化
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    //设置抓取的url
    curl_setopt($curl, CURLOPT_URL, $url);
    //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 0);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
    //设置post数据
    $post_data = $data;
    Log::info('[接口请求数据] (' . $url . ')：' . json_encode($post_data));
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    //执行命令
    $result = curl_exec($curl);
    //关闭URL请求
    curl_close($curl);
    //显示获得的数据
    Log::info('[接口返回数据] ' . $result);
    return $result;
}

/**
 * 获取请求token
 * @return bool|mixed|string
 */
function get_request_token()
{
    $request = request();
    $token = $request->param('token');
    if (empty($token)) {
        if (empty($request->header('token'))) {
            return false;
        } else {
            $token = $request->header('token');
        }
    }
    return $token;
}

/**
 * 支付宝实例
 * @param $appId int 应用ID
 * @param $private_key string 应用密钥
 * @param $public_key string 支付宝公钥
 * @return AopClient
 */
function alipay_client($appId, $private_key, $public_key)
{
    require_once \think\facade\Env::get('app_path') . 'common/library/alipay-sdk/AopSdk.php';
    $client = new \AopClient();
    $client->gatewayUrl = "https://openapi.alipay.com/gateway.do";
    $client->appId = $appId;
    $client->format = "json";
    $client->charset = "UTF-8";
    $client->signType = "RSA2";
    $client->rsaPrivateKey = $private_key;
    $client->alipayrsaPublicKey = $public_key;
    return $client;
}

/**
 * 获取随机商品颜色
 * @return string
 */
function get_rand_goods_name()
{
    $faker = \Faker\Factory::create($locale = 'zh_CN');
    return $faker->colorName . '-' . $faker->safeColorName;
}

function get_rand_name()
{
    $faker = \Faker\Factory::create($locale = 'zh_CN');
    return $faker->name;
}

function get_rand_faker_mark()
{
    $faker = \Faker\Factory::create($locale = 'zh_CN');
    return $faker->name . '=' . $faker->city . '(' . $faker->colorName . $faker->creditCardExpirationDateString . ')';
}

/**
 * @desc: redis-think-queue 任务队列
 * @author: Tinywan(ShaoBo Wan)
 * @time: 2019/3/29 15:46
 * @param $taskType
 * @param $data
 * @return bool
 */
function redis_queue($taskType, $data)
{
    if (empty($taskType) || !is_numeric($taskType) || empty($data)) {
        Log::error('redis-think-queue 任务队列参数错误');
        return false;
    }
    switch ($taskType) {
        case \app\common\queue\RedisTaskQueue::MERCHANT_NOTIFY: // 商户通知
            $className = \app\common\queue\RedisTaskQueue::class . "@merchantNotify";
            $queueName = "redis-think-queue";
            break;
        case \app\common\queue\RedisTaskQueue::TRANSFER_NOTIFY: // 转账通知
            $className = \app\common\queue\RedisTaskQueue::class . "@transferNotify";
            $queueName = "redis-think-queue";
            break;
        case \app\common\queue\RedisTaskQueue::QUEUE_EVENT: // 队列事件
            $className = \app\common\queue\RedisTaskQueue::class . "@queueEvent";
            $queueName = "redis-think-queue";
            break;
    }
    $isPushed = \think\Queue::push($className, $data, $queueName);
    if ($isPushed) return true;
    return false;
}


/**
 * 服务器发送邮件
 * @param array|string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
 * @param string $subject 标题
 * @param string $content 内容
 * @return array 返回状态吗和提示信息
 * @throws \PHPMailer\PHPMailer\Exception
 */
function send_email($address, $subject, $content)
{
    $email = config('email.config_email');
    $email_smtp_host = $email['SMTP_HOST'];
    $email_username = $email['SMTP_USERNAME'];
    $email_password = $email['SMTP_PASSWORD'];
    $email_from_name = $email['FROM_NAME'];
    $email_host = $email['FROM_NAME'];
    if (empty($email_smtp_host) || empty($email_username) || empty($email_password) || empty($email_from_name)) {
        return ["errorCode" => 1, "msg" => '邮箱请求参数不全，请检测邮箱的合法性'];
    }
    $phpMailer = new \PHPMailer\PHPMailer\PHPMailer();
    $phpMailer->SMTPDebug = 0;
    $phpMailer->IsSMTP();
    $phpMailer->SMTPAuth = true;
    $phpMailer->SMTPSecure = 'ssl';
    $phpMailer->Host = $email_smtp_host;
    $phpMailer->Port = 465;
    $phpMailer->Hostname = $email_host;
    $phpMailer->CharSet = 'UTF-8';
    $phpMailer->FromName = $email_username;
    $phpMailer->Username = $email_username;
    $phpMailer->Password = $email_password;
    $phpMailer->From = $email_username;
    $phpMailer->IsHTML(true);
    if (is_array($address)) {
        foreach ($address as $addressv) {
            if (false === filter_var($address, FILTER_VALIDATE_EMAIL)) {
                return ["errorCode" => 1, "msg" => '邮箱格式错误'];
            }
            $phpMailer->AddAddress($addressv, $address . '的[' . $subject . ']');
        }
    } else {
        if (false === filter_var($address, FILTER_VALIDATE_EMAIL)) {
            return ["errorCode" => 1, "msg" => '邮箱格式错误'];
        }
        $phpMailer->AddAddress($address, $address . '的[' . $subject . ']');
    }
    $phpMailer->Subject = $subject;
    $phpMailer->Body = $content;
    if (!$phpMailer->Send()) {
        return ["errorCode" => 1, "msg" => $phpMailer->ErrorInfo];
    }
    return ["errorCode" => 0];
}

/**
 * 邀请码生成
 * @mch_id int  商户id
 * @return string 返回生成的邀请码
 * */
function invitationMake()
{
    return substr(base_convert(md5(uniqid(md5(microtime(true)), true)), 16, 10), 0, 6);
}

/**
 * 验证权限
 * @param string $role
 * @return bool
 */
function check_role($role = '')
{
    $admin_info = get_admin_info();
    if ($admin_info['id'] == 1) {
        return true;
    }
    $auth = new \app\common\library\Auth();
    return $auth->check($role, $admin_info['id']);
}

/**
 * 获取后台用户登录信息
 * @return mixed
 */
function get_admin_info()
{
    return \think\facade\Session::get('admin_info');
}

function responseJson($success, $code = 0, $message = '', $data = [])
{
    if (empty($message)) {
        $message = '未知信息';
    }
    if (empty($data)) {
        $data = '';
    }

    $result = [
        'success' => $success,
        'message' => $message,
        'code' => $code,
        'data' => $data,
    ];
    Log::debug('前台输出：' . json_encode($result));

    $response = \think\Response::create($result, 'json');
    $response->send();
    exit();
}

/**
 * 添加操作日志
 * @param string $remark 备注
 * @param string $type admin 后台  shop 商户
 */
function add_operateLogs($remark, $type = 'admin')
{
    if ($type == 'admin') {
        $user = Session::get('admin_info');
    } elseif ($type == 'shop') {
        $user = Session::get('shop_info');
    }

    $data = [
        'uid' => $user['id'],
        'remark' => $remark,
        'ip' => request()->ip(),
        'created_at' => date('Y-m-d H:i:s', time()),
        'type' => 1,
        'content' => json_encode(request()->param())
    ];

    if ($type == 'admin') {
        $data['from'] = 'admin';
    } else {
        $data['from'] = 'shop';
    }

    \app\common\model\AdminOperateLogs::create($data);
}


/**
 * 发送验证码
 * @param int $phone_num 电话号码
 * @param int $code 验证码
 * @return array
 * @throws \AlibabaCloud\Client\Exception\ClientException
 */
function send_code($phone_num, $code)
{
    if (empty($phone_num) || empty($code)) {
        return ["errorCode" => 1, "msg" => '请求参数不全或者参数不能为空'];
    }
    $accessKeyId = config('aliyun.sms.accessKeyId');
    $accessSecret = config('aliyun.sms.accessKeySecret');
    $SignName = config('aliyun.sms.signName');
    $TemplateCode = config('aliyun.sms.templateCodeSms');
    AlibabaCloud\Client\AlibabaCloud::accessKeyClient($accessKeyId, $accessSecret)
        ->regionId('cn-hangzhou')
        ->asGlobalClient();
    try {
        $result = AlibabaCloud\Client\AlibabaCloud::rpcRequest()
            ->product('Dysmsapi')
            // ->scheme('https') // https | http
            ->version('2017-05-25')
            ->action('SendSms')
            ->method('POST')
            ->options([
                'query' => [
                    'RegionId' => 'cn-hangzhou',
                    'PhoneNumbers' => $phone_num,
                    'SignName' => $SignName,
                    'TemplateCode' => $TemplateCode,
                    'TemplateParam' => json_encode(['code' => $code])
                ],
            ])->request();
        $resArr = $result->toArray();
        if ($resArr['Code'] == 'OK') {
            return ["errorCode" => 0, "msg" => 'success'];
        }
        return ["errorCode" => 1, "msg" => $resArr['Message']];
    } catch (AlibabaCloud\Client\Exception\ClientException $e) {
        return ["errorCode" => 1, "msg" => $e->getErrorMessage()];
    } catch (AlibabaCloud\Client\Exception\ServerException $e) {
        return ["errorCode" => 1, "msg" => $e->getErrorMessage()];
    }
}


/**
 * 后台json输出
 * @param $success
 * @param $message
 * @param array $data
 * @param int $code
 * @param int $http_code
 * @return \think\response\Json
 */
function rJson($success, $message, $data = [], $code = 0, $http_code = 200)
{
    if (empty($message)) {
        $message = '未知信息';
    }
    if (empty($data)) {
        $data = (object)[];
    }

    $result = [
        'success' => $success,
        'message' => $message,
        'code' => $code,
        'data' => $data,
    ];

    Log::debug('前台输出：' . json_encode($result));

    return json($result, $http_code);
}

/**
 * 获取会员等级
 * @param int $point 会员分数
 * @return mixed
 */
function get_member_level($point)
{
    $level = [
        0 => ['max' => 1000, 'min' => 0, 'name' => '普通会员', 'level' => 1],
        1 => ['max' => 10000, 'min' => 1000, 'name' => '银卡会员', 'level' => 2],
        2 => ['max' => 20000, 'min' => 10000, 'name' => '黄金会员', 'level' => 3],
        3 => ['max' => 50000, 'min' => 20000, 'name' => '铂金会员', 'level' => 4],
        4 => ['max' => 100000, 'min' => 50000, 'name' => '钻石会员', 'level' => 5]
    ];
    foreach ($level as $value) {
        if (($point >= $value['min']) && ($point < $value['max'])) {
            return $value;
        }
    }
}

/**
 * 返回json数据
 * @param int $code
 * @param string $msg
 * @param array $data
 * @param int $http_code
 * @param bool $is_object
 * @return \think\response\Json
 */
function jsonResponse($code = -1, $msg = '', $data = [], $http_code = 200, $is_object = true)
{
    if (empty($data) && $is_object) {
        $data = (object)$data;
    }
    $result = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    \think\facade\Log::debug('[网关] 接口返回JSON数据：' . json_encode($result));
    return json($result, $http_code);
}

/**
 * 获取银行卡信息
 */
function get_bankcard_info($card)
{
    return BankCard::info($card);
}

/**
 * 自动生成订单号  生成订单15位
 * @param int $ord 传入参数为0 或者1   0为本地  1为线上订单
 * @return string
 */
function get_auto_new_order($ord = 0)
{
    srand(time());
    if ($ord == 0) {
        $str = '00' . time() . mt_rand(100000, 999999); //00 本地订单
    } else {
        $str = '11' . time() . mt_rand(100000, 999999);  //11 线上订单
    }
    return $str;
}

/**
 * @desc: 操作日志
 * @author: Tinywan(ShaoBo Wan)
 * @time: 2019/4/2 17:04
 * @param null $desc
 */
function add_operation_log($desc = null)
{
    $request = \think\facade\Request::instance();
    \app\common\model\Logs::create([
        'account' => get_admin_info()['username'],
        'module' => $request->module(),
        'controller' => $request->controller(),
        'action' => $request->action(),
        'event_type' => '普通',
        'url' => $request->url(),
        'ipaddr' => $request->ip(),
        'addtime' => $request->time(),
        'query_string' => json_encode($request->param()),
        'desc' => $desc,
    ]);
}
