<?php
/*
 * 此配置文件设置公用常量，供前台和后台一起使用，请注意数据库、memcache、redis配置
 */
define('DOMAIN_ROOT', 'lingqianzaixian.com');
define('WWW_DOMAIN_ROOT', 'zxcf.cn');

define('SITE_NAME', '吉祥果');
define('DEFAULT_MODULE', 'Index');
define('THEME_NAME', 'theme_2015');
define('_SHOW_INDEX_FILE_', false);


define('CACHE_SERVER', '139.196.106.250:11211');


define('REDIS_SERVER','127.0.0.1');
define('REDIS_PORT','6379');

define('MYSQL_SERVER', '139.196.106.250');
define('MYSQL_PASSWORD', 'jxc123456');


define('WXAPPSECRED','87e0cf7a65b6fb01bc86a6c8cdabe960');

define('WXAPPID','wx713f7c915e5634bb');

define('APP_DEBUG', true);//调试模式
define('APP_TEST', true);//测试模式 短信，充值
define('APP_STATUS', 'dev');//跟踪日志 上线改为 dev
define("FRAME_PATH", dirname(dirname(__FILE__)) . '/ThinkPHP/');


//ini_set('session.cookie_domain', '.' . DOMAIN_ROOT);
define("UPLOADPATH", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'Upload'.DIRECTORY_SEPARATOR);
