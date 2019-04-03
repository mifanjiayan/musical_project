<?php
/**
 * Created by IntelliJ IDEA.
 * User: ldc
 * Date: 19-4-3
 * Time: 上午10:02
 */

return [
    'type' => 'File',
    // 日志保存目录
    'path' => Env::get('ROOT_PATH') . '/logs/admin',
    // 日志记录级别，使用数组表示，设置只记录需要的日志
    'level' => ['info', 'error', 'sql']
];