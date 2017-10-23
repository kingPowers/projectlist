<?php
/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function msubstr($str, $start = 0, $length, $suffix = false, $charset = "utf-8") {
	if (function_exists("mb_substr")) {
		if ($suffix && strlen($str) > $length)
			return mb_substr($str, $start, $length, $charset) . $suffix;
		else
			return mb_substr($str, $start, $length, $charset);
	}
	elseif (function_exists('iconv_substr')) {
		if ($suffix && strlen($str) > $length)
			return iconv_substr($str, $start, $length, $charset) . $suffix;
		else
			return iconv_substr($str, $start, $length, $charset);
	}
	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);
	$slice = join("", array_slice($match[0], $start, $length));
	if ($suffix)
		return $slice . $suffix;
	return $slice;
}

//获取http状态码
function getHttpStatus($url,$timeout=5)
{
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
	curl_setopt($curl, CURLOPT_HEADER, 1);
	curl_setopt($curl,CURLOPT_NOBODY,true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($curl);
	$result = curl_getinfo($curl,CURLINFO_HTTP_CODE);
	curl_close($curl);
	return $result;
}

//截取用户名,显示首尾
function hideusername($username='')
{
	if($username=='沈华国'){
		 return '沈**';
	}
	
	$length = mb_strlen($username,'UTF8');
	if($length<=1){
		return '*'; 
	}elseif($length==2){
		return msubstr($username,0,1,'**');
	}else{
		return msubstr($username,0,1,'**').msubstr($username,-1,1);
	}
}

//用户密码加密函数
function gen_password($original){
	return md5($original.C('SECURE_KEY'));
}
//用户密码安全度
//$pwd :用户原始密码
function pwd_safety($pwd)
{
	$level = 0;
	if(empty($pwd))return $level;
	$level += preg_match("/[0-9]+/",$pwd)?1:0;
	$level += preg_match("/[0-9]{3,}/",$pwd)?1:0;
	$level += preg_match("/[a-z]+/",$pwd)?1:0;
	$level += preg_match("/[a-z]{3,}/",$pwd)?1:0;
	$level += preg_match("/[A-Z]+/",$pwd)?1:0;
	$level += preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$pwd)?1:0;
	$level += preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$pwd)?1:0;
	$level += preg_match("/[A-Z]{3,}/",$pwd)?1:0;
	$level += (strlen($pwd) >= 10)?1:0;
	return $level;
}
?>
