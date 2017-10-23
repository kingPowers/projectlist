<?php
//框架路径
define("FRAME_PATH",dirname(dirname(__FILE__))."/ThinkPHP");
//定义APP二级域名
define('DOMAIN_SUB','service');
//主域名 
define('DOMAIN_ROOT','jieba360.com');
//开启调试
define('APP_DEBUG',true);
//生成URL是否带index.php字符
define('_SHOW_INDEX_FILE_',false);
//缓存服务地址
define('CACHE_SERVER','10.46.71.179:11211');
//模版主题
define('THEME_NAME','theme_2014');

define('FUND_CUSTODY',false);
define('APP_TEST', true);

define('REDIS_SERVER','10.46.64.67');
define('REDIS_PORT','6379');

define('_STATIC_','http://static.' . DOMAIN_ROOT);
define('_WWW_','http://wx.' . DOMAIN_ROOT);
define('_SERVICE_','http://service.' . DOMAIN_ROOT);

define("UPLOADPATH", dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR.'static'.DIRECTORY_SEPARATOR.'Upload'.DIRECTORY_SEPARATOR);
define('WXAPPSECRED','79eded765c0753d72323a323a26a9a26');

define('WXAPPID','wxff92401bd29044f8');

define('_STATIC_','http://static.' . DOMAIN_ROOT);
// 引入ThinkPHP入口文件
require FRAME_PATH.'/ThinkPHP.php';
?>
