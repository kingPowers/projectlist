<?php
// 引入ThinkPHP入口文件
require_once(dirname(dirname(__FILE__)).'/www/config.inc.php');
//定义APP二级域名
define('DOMAIN_SUB','service');
define('FUND_CUSTODY',false);
define('_STATIC_','http://wxst.' . DOMAIN_ROOT);
define('_SERVICE_','http://service.' . DOMAIN_ROOT);
require FRAME_PATH.'/ThinkPHP.php';

?>
