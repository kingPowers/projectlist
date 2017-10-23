<!--container-->
<link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/member.css?v=20150514">
<div class="ui-container-fluid login-area">
	<div class="ui-container login-container">
		<div class="ui-container login-center">
			<a href="/activity.html" target="_blank"><img src="_STATIC_/2015/member/img/login-left.jpg" class="ui-left"></a>
			<form class="ui-form ui-right login-form">
				<fieldset>
					<div class="login-welcome">欢迎登陆<?php echo SITE_NAME;?></div>
					<div class="login-div">
						<label class="login-label">账户</label>
						<input class="login-reg-input login-input" type="text" placeholder="请输入用户名" name="username" rel="用户名不正确"/>
						<label class="labelwp"></label>
					</div>
					<div class="login-div">
						<label class="login-label">密码</label>
						<input class="login-reg-input login-input" type="password" placeholder="请输入密码" name="password" rel="密码不正确"/>
						<label class="labelwp"></label>
					</div>
					<div class="login-div" style="margin-bottom:0;">
						<a href="javascript:void(0)" class="login-btn">立即登录</a>
					</div>
					<div class="login-forgetpwd">
						<a href="/public/forgetpwd.html" target="_blank" class="ui-text-yellow ui-right">忘记密码？</a>
					</div>
					<!-- <div class="ui-form-item ui-other-login">
						<label>使用合作方账号登录</label>
						<a href="" class="ui-icon qq"></a>
						<a href="" class="ui-icon sina"></a>
						<a href="" class="ui-icon neteasy"></a>
						<a href="" class="ui-icon renren"></a>
					</div> -->
				</fieldset>
			</form>{__TOKEN__}
		</div>
	</div>
</div>
<script src="_STATIC_/2015/js/login-register.js?v=20150508" type="text/javascript" charset="utf-8"></script>
<script language="javascript">
$(function(){
	if(top.location.href != location.href){
		parent.location.reload();
	}
	$('form.ui-form').constract(false);
	$('form.ui-form a.login-btn').submitfrom($('form.ui-form'));
});
</script>
