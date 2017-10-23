<?php
/*
 * 用户积分--现金贷
 * 
 * */
$module = 'Bash';//区分大小写
$action = $_SERVER['argv'][1];
$params = isset($_SERVER['argv'][2])?$_SERVER['argv'][2]:'';
$params = str_replace(array('&','='),array('/','/'),$params);
$gateway= 'http://wx.jieba360.com/'.$module.'/'.$action.($params?('/'.$params):'');
$context = stream_context_create(array('http'=>array('timeout' => 600)));
echo file_get_contents($gateway,0,$context);
?>
