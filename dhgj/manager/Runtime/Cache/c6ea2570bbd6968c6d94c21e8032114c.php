<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo SITE_NAME;?>后台管理系统</title>
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/bootstrap-responsive.css" />
<link rel="stylesheet" type="text/css" href="__PUBLIC__/Css/style.css" />
<link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
<script language="javascript">var STATIC = '_STATIC_/2015/',OS = '_OS_';</script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.box.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/ckform.js"></script>
<style type="text/css">
body {
	padding-top: 40px;
	padding-bottom: 40px;
	background-color: #819ac9;
}
.form-signin {
	max-width: 315px;
	padding: 19px 29px 29px;
	margin: 80px auto;
	background-color: #fff;
	border: 1px solid #e5e5e5;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
	-webkit-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
	-moz-box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
	box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
}
.form-signin .form-signin-heading,  .form-signin .checkbox {
	margin-bottom: 10px;
}
.form-signin input[type="text"],  .form-signin input[type="password"] {
	font-size: 16px;
	height: auto;
	margin-bottom: 15px;
	padding: 7px 9px;
}
.form-signin img{
	height: 36px;
	margin: 0px 5px;
	vertical-align: top;
	cursor: pointer;
}
</style>
</head>
<body onload="return topLogout();">
<div class="container">
  <form class="form-signin">
	<h2 class="form-signin-heading" style="font-size:30px;color:#207ac4;text-align:center"><?php echo SITE_NAME;?>后台管理系统</h2>
	<input type="text" name="username" class="input-block-level input" placeholder="账号">
	<input type="password" name="password" class="input-block-level input" placeholder="密码">
	<input type="text" name="verify" class="input-medium input" placeholder="验证码" style="width:200px">
	<img src="<?php echo U('Public/verifyCode');?>" class="img-verify" title="点击更换验证码"/>
	<p>
	  <button class="btn btn-large btn-primary" type="button" style="width:315px;">登录</button>
	</p>
  </form>
</div>

<script language="javascript">
var submitform = (function(){
	$('button.btn-primary').click(function(){
		var msg='',data={};
		$('input.input').each(function(){
			if($(this).val()==$(this).attr('placeholder') || !$(this).val()){
				msg = '请输入'+$(this).attr('placeholder');
				return false;
			}else{
				$(data).attr($(this).attr('name'),$(this).val());
			}
		});
		if(msg){
			return jdbox.alert(0,msg);;
		}
		jdbox.alert(2);
		$.post("<?php echo U('Public/dologin');?>",data,function(R){
			jdbox.alert(R.status,R.info);
			if(R.status){
				window.location.href = OS;
			}
		},'json')
	})
}())

var refreshverify = (function(){
	$('img.img-verify').click(function(){
		$(this).attr('src',"<?php echo U('Public/verifyCode');?>?"+Math.random())
	})
}())

var topLogout = function()
{
	if(typeof top.logout=='function'){
		top.location.href = '/login.html';
	}
}
</script>
</body>
</html>