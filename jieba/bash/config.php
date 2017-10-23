<?php

/*
 * 相关配置文件
 * Nydia editor.
 */
define('APP_TEST', true);//测试不发短信
define('SITE_NAME','借吧');
define('WWW_DOMAIN_ROOT', 'wx.jieba360.com');
//【目录配置】
define('ROOT_PATH', dirname(__FILE__)); //根目录
define('INC_PATH', ROOT_PATH . '/inc/'); //类库目录
define('LOG_PATH', ROOT_PATH . '/log/'); //日志目录
define('LOG_PATH_PDO', LOG_PATH . '/pdo/'); //PDO日志
define('LOG_PATH_START', LOG_PATH . '/start/'); //开标日志
define('LOG_PATH_AUTO', LOG_PATH . '/auto/'); //自动投标日志
define('LOG_PATH_TENDER', LOG_PATH . '/tender/'); //投标日志
define('LOG_PATH_TRIALRATEMONEY', LOG_PATH . '/trialratemoney/'); //体验金收益日志
define('LOG_PATH_SMS', LOG_PATH . '/sms/'); //短信日志
define('LOG_PATH_REDPACKET', LOG_PATH . '/redpacket/'); //红包日志
define('LOG_PATH_ADTENDER', LOG_PATH . '/adtender/'); //投标成功广告日志
define('LOG_PATH_ADDAMOUNT', LOG_PATH . '/addamount/'); //赠送10元


//【日志名字】
define('LOG_NAME_START', 'start'); //开标日志
define('LOG_NAME_AUTO', '{loansn}'); //开始自动投标日志
define('LOG_NAME_TENDER', '{loansn}'); //投标日志
define('LOG_NAME_TRIALRATEMONEY', '{date}'); //体验金收益日志
define('LOG_NAME_SMS', '{loansn}'); //短信日志
define('LOG_NAME_REDPACKET', '{loansn}'); //红包日志
define('LOG_AD_TENDER', 'tender'); //投标成功广告日志
//【REDIS队列名】
/*define('QUEUE_NAME_START', 'zxcf-start'); //开标队列
define('QUEUE_NAME_AUTO', 'zxcf-auto'); //自动投标队列
define('QUEUE_NAME_TENDER', 'zxcf-tender'); //投标队列
define('QUEUE_NAME_SMS', 'zxcf-sms'); //发短信队列
define('QUEUE_NAME_REDPACKET', 'zxcf-redpacket'); //发短信队列
define('LOAN_PREFIX', 'loan-{loansn}'); //标开启状态
define('LOAN_STATUS_PREFIX', 'loan-status-{loansn}'); //招标状态
define('AD_TENDER','ad-tender');//投标成功统计
//【REDIS配置】
define('REDIS_SERVER_HOST', '127.0.0.1');
define('REDIS_SERVER_PORT', '6379');*/
//【数据库配置】
define('DB_HOST', 'zxcfchina.mysql.rds.aliyuncs.com');
define('DB_USER', 'jieba360');

define('DB_PASS', 'ZxcfChina20160726');

define('DB_PORT', '3306');
define('DB_NAME', 'jieba360');
define('DB_PDO', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8');
 
