<?php

/*
 * 初始化加载配置并设置目录
 * wangmengmian editor.
 */
header('Content-Type: text/html; charset=UTF-8');
date_default_timezone_set('Asia/Shanghai');
set_time_limit(0);
session_start();
//引入文件
require 'config.php';
require INC_PATH . 'Logger.class.php';
require INC_PATH . 'Db.class.php';

//检测创建目录
function check_dir() {
    $runpath = array(LOG_PATH_PDO, LOG_PATH_START, LOG_PATH_AUTO, LOG_PATH_TENDER, LOG_PATH_SMS, LOG_PATH_REDPACKET);
    foreach ($runpath as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0775);
        }
    }
}

//REDIS连接
function redis_connect() {
    static $redis = NULL;
    $connect = FALSE;
    if (empty($redis)) {
        $redis = new Redis();
        $connect = $redis->pconnect(REDIS_SERVER_HOST, REDIS_SERVER_PORT);
    } else {
        $connect = TRUE;
    }
    return $connect ? $redis : FALSE;
}

//PDO连接
function pdo_connect() {
    $pdo = Db::getPDO(DB_PDO, DB_USER, DB_PASS);
    return empty($pdo) ? FALSE : $pdo;
}

//REDIS 返回信息
function redis_set_return_message($key = '', $message = '', $status = 2) {
    $redis = redis_connect();
    if (empty($key) || $redis == FALSE) {
        return FAlSE;
    }
    $return = array('status' => $status, 'message' => $message);
    return $redis->setex($key, 60 * 60 * 12, serialize($return));
}

//生成订单号
function create_sn($code = null, $extends = '') {
    $codearr = array('loan' => 1, 'issue' => 2, 'tender' => 3, 'allot' => 4, 'detail' => 5, 'cashin' => 6, 'cashout' => 7, 'redpacket' => 8, 'sina' => 9,'ratecoupon'=>10, 'transfer' => 12, 'refund' => 13, 'goldingot' => 14, 'borrowloan' => 15, 'borrowrepay' => 16);
    $code = strtolower($code);
    if (empty($codearr[$code])) {
        return;
    }
    $sn = $codearr[$code] . (($code == 'loan') ? time() : (microtime(true) * 10000));
    $sn = empty($extends) ? $sn . rand(100, 999) : $sn . $extends;
    return $sn;
}

function curl_post($url,$data){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}


check_dir();
