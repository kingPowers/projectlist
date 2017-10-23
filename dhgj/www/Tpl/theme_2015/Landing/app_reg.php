<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>着陆页</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=0.1,  user-scalable=no">
	<link rel="stylesheet" href="_STATIC_/2015/landing/app/css/landing-reg.css">
    <link href="_STATIC_/2015/box/wbox.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
	<style type="text/css">
	.labelwp {display:block;}
	</style>
</head>
<body>
	<div class="land-banner"><img src="_STATIC_/2015/landing/app/img/reg-banner.jpg" alt="" width="100%"></div>
	<div class="content">
	<form class="reg-form">
	    <div>
		<input type="text" placeholder="用户名" class="margin login-reg-input" name="username" rel="用户名不正确">
		<label class="labelwp"></label></div>
		<div>
		<input type="password" placeholder="密码" class="margin login-reg-input" name="password" rel="密码不正确">
		<label class="labelwp"></label></div>
		<div>
		<input type="text" placeholder="手机号码" class="margin login-reg-input" name="mobile" rel="手机号不正确">
		<label class="labelwp"></label></div>
		<div>
		<input type="text" placeholder="短信验证码" class="margin input-left login-reg-input" name="smscode" rel="验证码不正确"><input type="button" class="button-right" id="smsbutton" value="点击获取验证码"/>
		<label class="labelwp"></label>
		</div>
		<div>
		<input type="text" allownull="1"  placeholder="邀请码(选填)" class="margin login-reg-input" style="display:block" name="invitecode" rel="邀请码不正确">
		<label class="labelwp"></label></div>
		<input type="checkbox" class="chexd login-reg-input" id="checkbox" checked="checked" valid="1" value="1" name="rigisteragree"><label for="checkbox">我已同意<a href="http://service.zhifu360.com/protocol" class="a-agr" target="_BLANK">《智信创富网站服务协议》</a></label>
		<input type="button" class="button-reg" value="注册领取体验金"/>
		<input type="hidden" class="login-reg-input" name="juxiaoid" value="m-161818-0"/>
		</form>{__TOKEN__}
	</div>
	<script type="text/javascript">
var _mvq = window._mvq || []; 
window._mvq = _mvq;
_mvq.push(['$setAccount', 'm-161818-0']);

_mvq.push(['$logConversion']);
(function() {
	var mvl = document.createElement('script');
	mvl.type = 'text/javascript'; mvl.async = true;
	mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(mvl, s); 
})();	

</script>
</body>
</html>
<script src="_STATIC_/2015/js/login-register.js?v=20150630" type="text/javascript" charset="utf-8"></script>
<script language="javascript">
//发验证码操作
var sendsecond = 59,
	sendtimmer = null,
	smsbutton = $('#smsbutton'),
	defaultHtml = smsbutton.html(),
	mobileobj = $("input[name='mobile']");
var getSmsCode = function() {
		if (sendtimmer != null) {
			return false;
		}
		var val = mobileobj.val();
		if (val.length != 11 || val.indexOf(1) > 0) {
			mobileobj.parent().find('label.labelwp').removeClass('valid').addClass('error').html('<i class="ui-icon fail"></i>手机号码格式不正确');
			return false;
		}
		if(parseInt(mobileobj.attr('valid')) != 1){
			return false;
		}
		box_verify('', 'top.sendSms');
	}
var sendSms = function() {
		var val = mobileobj.val();
		if (val.length != 11 || val.indexOf(1) > 0) {
			mobileobj.parent().find('label.labelwp').removeClass('valid').addClass('error').html('<i class="ui-icon fail"></i>手机号码格式不正确');
			return false;
		}
		jdbox.alert(2);
		var data = {}
		$.post('/public/verifysms.html', {
			mobile: val,
			notmember: true
		}, function(result) {
			$('input[name="smscode"]').focus();
			if (result.status) {
				clearInterval(sendtimmer);
				sendsecond = 59;
				sendtimmer = setInterval('showTimemer()', 1000);
				return jdbox.alert(1, result.info);
			} else {
				return jdbox.alert(0, result.info);
			}
		}, 'json');
	}
var showTimemer = function() {
		if (sendsecond > 0) {
			smsbutton.html('重新发送（' + sendsecond + '）');
			sendsecond -= 1;
		} else {
			smsbutton.html(defaultHtml);
			clearInterval(sendtimmer);
			sendtimmer = null;
		}
	}
$(function() {
	$('form.reg-form').constract(true);
	$('form.reg-form input.button-reg').submitfrom($('form.reg-form'));
	smsbutton.click(function() {
		getSmsCode();
	});

})
</script>
{__NOLAYOUT__}