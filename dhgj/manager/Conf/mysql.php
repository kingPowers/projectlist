<?php

return array(
    'DB_TYPE' => 'mysql',
    'DB_HOST' => MYSQL_SERVER,
    'DB_PORT' => '3306',
    'DB_NAME' => 'daihou',
    'DB_USER' => 'jxcf',
    'DB_PWD' => MYSQL_PASSWORD,
    'DB_PREFIX' => '',
    'AUTH_CONFIG' => array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'auth_group_access', //用户组明细表
        'AUTH_RULE' => 'auth_rule' //权限规则表
    ),
);
