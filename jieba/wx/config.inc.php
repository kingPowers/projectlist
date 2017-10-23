<?php
/*
 * 此配置文件设置公用常量，供前台和后台一起使用，请注意数据库、memcache、redis配置
 */
define('DOMAIN_ROOT', 'jieba360.com');
define('WWW_DOMAIN_ROOT', 'jieba360.com');

define('SITE_NAME', '借吧');
define('DEFAULT_MODULE', 'Index');
define('THEME_NAME', 'theme_2015');
define('_SHOW_INDEX_FILE_', false);

define('CACHE_SERVER', '10.46.71.179:11211');


define('REDIS_SERVER','10.46.64.67');
define('REDIS_PORT','6379');


define('MYSQL_SERVER', 'zxcfchina.mysql.rds.aliyuncs.com');
define('MYSQL_PASSWORD', 'ZxcfChina20160726');

define('WXAPPSECRED','79eded765c0753d72323a323a26a9a26');

define('WXAPPID','wxff92401bd29044f8');


define('APP_DEBUG', true);//调试模式
define('APP_TEST', true);//测试模式 短信，充值
define('APP_STATUS', 'dev');//跟踪日志 上线改为 dev
define("FRAME_PATH", dirname(dirname(__FILE__)) . '/ThinkPHP/');


ini_set('session.cookie_domain', '.' . DOMAIN_ROOT);
define("UPLOADPATH", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'Upload'.DIRECTORY_SEPARATOR);
