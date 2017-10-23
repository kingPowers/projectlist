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

//手机号后四位隐蒧
function replace_mobile($mobile) {
    //return preg_replace('/^(1\d{2})(\d{4})(\d{4})$/', '$1$2****', $mobile);
    return substr($mobile, 0, count($mobile) - 5) . '****';
}

//获取http状态码
function getHttpStatus($url, $timeout = 5) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_NOBODY, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $data = curl_exec($curl);
    $result = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    return $result;
}

/**
 * @brief 计算标进度
 * @param $money 总金额
 * @param $tender当前借款金额
 * @return Number $speed
 */
function compare_speed($money = 0, $tender = 0) {
    $speed = 0;
    if ($money > 0 && $tender > 0) {
        $speed = intval($tender / $money * 100);
    }
    return $speed;
}

//截取用户名,显示首尾
function hide_username($username = '') {
    $length = mb_strlen($username, 'UTF8');
    if ($length <= 1) {
        return '*';
    } elseif ($length == 2) {
        return msubstr($username, 0, 1, '**');
    } else {
        return msubstr($username, 0, 1, '**') . msubstr($username, -1, 1);
    }
}

//截取用户名,显示首尾
function hide_mobile($mobile = '') {
    $length = strlen($mobile);
    if ($length <= 1) {
        return '未知';
    } else {
        return substr_replace($mobile, '****', 3, 4);
    }
}

//获取IP地址
function get_area_detail_by_ip($ip) {
    $data = array('province' => '', 'city' => '', 'district' => '');
    $json = iconv('GBK', 'UTF-8', file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?callback=testJson&ip=' . $ip));
    $json = str_replace("if(window.testJson) {testJson(", "", $json);
    $json = str_replace(");}", "", $json);
    $arr = json_decode($json, true);
    $data['province'] = $arr['pro'];
    $data['city'] = $arr['city'];
    $data['district'] = $arr['region'];
    return $data;
}

//获取手机信息 return eg. 上海-中国移动
function get_mobile_location($mobile) {
    if (!isMobile($mobile)) {
        return false;
    }
    $return = array();
    $api = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel={$mobile}";
    $string = iconv('gb2312', 'utf-8', file_get_contents($api));
    $string = str_replace(array("\r", "\n", "\r\n", "'"), array('', '', '', ''), $string);
    $string = substr($string, strpos($string, '{') + 1);
    $string = substr($string, 0, strpos($string, '}'));
    $array = explode(',', $string);
    foreach ($array as $var) {
        $ex = explode(':', $var);
        $key = trim($ex[0]);
        $val = trim($ex[1]);
        $return[$key] = $val;
    }
    return $return['province'] . '-' . $return['catName'];
}

function HmacMd5($data, $key) {
// RFC 2104 HMAC implementation for php.
// Creates an md5 HMAC.
// Eliminates the need to install mhash to compute a HMAC
// Hacked by Lance Rushing(NOTE: Hacked means written)
//需要配置环境支持iconv，否则中文参数不能正常处理
    $key = iconv("GB2312", "UTF-8", $key);
    $data = iconv("GB2312", "UTF-8", $data);

    $b = 64; // byte length for md5
    if (strlen($key) > $b) {
        $key = pack("H*", md5($key));
    }
    $key = str_pad($key, $b, chr(0x00));
    $ipad = str_pad('', $b, chr(0x36));
    $opad = str_pad('', $b, chr(0x5c));
    $k_ipad = $key ^ $ipad;
    $k_opad = $key ^ $opad;

    return md5($k_opad . pack("H*", md5($k_ipad . $data)));
}

//获取积分图标，积分 = 金额/100
function member_intergral_icon($intergral = 0){
	//百万用金标
	$type = $intergral > 10000 ? 'gold':'silver';
	$number = 0;
	if($intergral > 50000){//金标数
		$number = 5;
	}elseif($intergral > 40000){
		$number = 4;
	}elseif($intergral > 30000){
		$number = 3;
	}elseif($intergral > 20000){
		$number = 2;
	}elseif($intergral > 10000){
		$number = 1;
	}elseif($intergral > 8000){//银标数
		$number = 5;
	}elseif($intergral > 6000){
		$number = 4;
	}elseif($intergral > 4000){
		$number = 3;
	}elseif($intergral > 2000){
		$number = 2;
	}else{
		$number = 1;
	}
	$html = '';
	for($i = 0;$i<$number;$i++){
		$html.= "<em class=\"ui-icon {$type}\"></em>";
	}
	return "<span class=\"member-intergral-icon\">{$html}</span>";
}

function fromMobile(){
	$user_agent = $_SERVER["HTTP_USER_AGENT"];
	return stripos($user_agent , "iPhone") !== false || stripos($user_agent , "iPad") !== false || stripos($user_agent , "Android") !== false || stripos($user_agent , "Windows Phone") !== false;
}