<?php

/**
 * Description of app.functions.php
 * 前后台公用函数库
 * @author Nydia
 */
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
//转换为大写人民币
function cny($ns) {
	static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
	$cnyunits=array("圆","角","分"),
	$grees=array("拾","佰","仟","万","拾","佰","仟","亿");
	list($ns1,$ns2)=explode(".",$ns,2);
	$ns2=array_filter(array($ns2[1],$ns2[0]));
	$ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
	$ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
	return str_replace(array_keys($cnums),$cnums,$ret);
}
//转换为大写人民币
function _cny_map_unit($list,$units)
{
	$ul = count($units);
	$xs = array();
	foreach (array_reverse($list) as $x)
	{
		$l = count($xs);
		if($x!="0" || !($l%4))
		{
			$n=($x=='0'?'':$x).($units[($l-1)%$ul]);
		}
		else
		{
			$n=is_numeric($xs[0][0]) ? $x : '';
		}
		array_unshift($xs, $n);
	}
	return $xs;
}


//检测手机
function isMobile($mobile = '') {
    return preg_match("/^1[3|4|5|7|8|9][0-9]{9}$/", $mobile) ? true : false;
}

//发送短信
/*
 * 添加短信类型 zwd
 * 2016/06/07
 */
function sendsms($mobile, $content,$sms_type) {
    if (!isMobile($mobile) || empty($content)) {
        return false;
    }
    import('Think.ORG.Util.SMS');
    return SMS::send($mobile, $content,$sms_type);
}

//发送验证码
/*
 * 添加短信类型 zwd
 * 2016/06/07
 */
function sendverify($mobile,$type=1,$content=false,$sms_type) {
    if (!isMobile($mobile)) {
        return false;
    }
    import('Think.ORG.Util.SMS');
    return SMS::buildverify($mobile,$content,$type,$sms_type);
}

//检测邮箱
function isEmail($email = '') {
    return preg_match('/^[a-z0-9_\.]{1,}@[a-z0-9-\.]{1,}\.[a-z]{2,}$/', $email) ? true : false;
}

//发送邮件
function sendemail($email, $title, $content) {
    if (empty($email) || empty($title) || empty($content)) {
        return false;
    }
    if (!isEmail($email)) {
        return false;
    }
    import('Think.ORG.Util.Mail');
    return Mail::send($email, $title, $content);
}
