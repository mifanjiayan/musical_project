<?php

return [
    'config' => [
        'auth_on'           => true,                // 认证开关
        'auth_type'         => 1,                   // 认证方式，1为实时认证；2为登录认证。
        'auth_group'        => 'auth_admin_group',        // 用户组数据表名
        'auth_group_access' => 'auth_admin_group_access', // 用户-用户组关系表
        'auth_rule'         => 'auth_rule',         // 权限规则表
        'auth_user'         => 'admin'             // 用户信息表
    ],
    // 不验证权限节点
    'public' => [
        'admin/index/index', //后台框架
        'admin/index/welcome', // 后台主页
        'admin/system/cleancache', //清除缓存
        'admin/system/updatepassword', //修改密码
        'admin/system/upload', //上传文件
    ],
];