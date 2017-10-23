<style type="text/css">

input,button{ border:none; padding:0px; margin:0px; display:inline;}
#form{ margin:0px; padding:0px;}
.lay { width:1100px;margin: 0 auto}
.hsbg{ background:url(_STATIC_/2015/landing/img/hsbg.jpg) repeat;}
.reg{ height:440px;}
.regk{ margin-left:75%;height:440px;  width:350px; background:url(_STATIC_/2015/landing/img/reg-bg.jpg) repeat  ;}
.h95{ height:95px;}
.jiang{ background:url(_STATIC_/2015/landing/img/jiang.jpg) no-repeat center; padding-top:16px; height:76px;}
.inputbox{ margin-left:28px;  }
.inputbox_name{ margin-left:28px; background:url(_STATIC_/2015/landing/img/name_bg.jpg) no-repeat; width:325px; height:62px; color:#666666; }
.inputbox_mima{ margin-left:28px; background:url(_STATIC_/2015/landing/img/mimabg.jpg) no-repeat; width:215px; height:62px;}
.inputbox_phone{ margin-left:28px; background:url(_STATIC_/2015/landing/img/phonebg.jpg) no-repeat; width:215px; height:62px;}
.inputbox_yz{ margin-left:28px; background:url(_STATIC_/2015/landing/img/yazhenbg.jpg) no-repeat; width:215px; height:62px;}
.login-reg-input{width:140px;height:40px;line-height:40px;}
#bbbj{}

.red{ color:#FF0000; font-size:13px; }

#name,#mima,#phone{margin-left:105px; height:40px; border:none; width:190px;}
#yzm{ width:130px;margin-left:35px; height:40px; border:none;}
#btn{height:40px; width:120px; color:#666666;}
#zc{ background:url(_STATIC_/2015/landing/img/zcbg.jpg) no-repeat center; width:293px; height:45px;}
#rad{ }
.clear{ clear:both;}

.reg-area{height:540px;background:#F6F6F6;padding-top:70px;padding-bottom:30px;}
.reg-container{height:450px;background:#FFF;border:1px solid #DADADA;}
.reg-form{width:360px;padding-left:10px;}
.reg-center{width:1080px;height:330px;margin:0 auto;border-top:1px dotted #DADADA;}
.reg-input{width:264px;height:40px;line-height:37px;padding-left:5px;margin-left:5px;border:1px solid #DADADA;float:right;}
.reg-welcome{width:100%;height:80px;line-height:80px;text-align:center;font-size:20px;color:#C29540;position:relative;}
.reg-welcome .reg-login{display:block;width:120px;height:20px;line-height:20px;position:absolute;font-size:12px;right:20px;bottom:5px;color:#CB7E03;}
.reg-welcome .reg-login a{color:#C29540;}
.reg-left-img{margin:30px 0 0 80px;}
.reg-label{color:#CB7E03;font-size:14px;padding-top:2px;line-height:18px;width:50px;height:40px;line-height:40px;float:left;}
.reg-div{width:100%;height:65px;}
.reg-div .labelwp{height:22px !important;line-height:22px !important;font-size:12px;padding-left:8px !important;color:#FF5400;}
.reg-div .get-smscode{display:inline-block;width:120px;height:40px;line-height:40px;text-align:center;background:#FF5400;text-decoration:none;color:#FFF;border-radius:3px;float:right;font-size:13px;}
.reg-btn{display:block;width:295px;height:42px;line-height:42px;background:#C29540;color:#FFF;text-align:center;}
.reg-btn:hover{text-decoration:none;}
.reg-forgetpwd{width:295px;height:40px;line-height:40px;color:#CB7E03;}
.reg-forgetpwd input[type="checkbox"]{vertical-align:middle;margin-top:-2px;}
.reg-agree,.reg-agree a{font-size:12px;color:#CB7E03}
.reg-agree input[type="checkbox"]{vertical-align:middle;margin-top:-2px;}
.reg-next-btn{width:150px;height:40px;line-height:40px;border-radius:3px;text-align:center;margin-top:12px;}
.reg-asterisk{color:red;float:left;height:40px;line-height:40px;display:inline-block;padding-top:4px;margin-right:3px;}

</style>
<div>
	<div style="background:url(_STATIC_/2015/landing/img/bannarbg.jpg) no-repeat center; height:600px;">
		<div class="lay">
			<div class="reg">
				<div class="h95"></div>
				<div class="regk">
					<div class="jiang"></div>
					<div class="textbox">
						<form class="ui-form ui-right reg-form">
							<fieldset>
								<div class="reg-div">
									<span class="reg-asterisk">*</span>
									<label class="reg-label">账&nbsp&nbsp&nbsp户</label>
									<input class="login-reg-input reg-input" type="text" placeholder="请输入用户名" name="username" rel="用户名不正确"/>
									<label class="labelwp"></label>
								</div>
								<div class="reg-div">
									<span class="reg-asterisk">*</span>
									<label class="reg-label">密&nbsp&nbsp&nbsp码</label>
									<input class="login-reg-input reg-input" type="password" placeholder="请输入密码" name="password" rel="密码不正确"/>
									<label class="labelwp"></label>
								</div>
								<div class="reg-div">
									<span class="reg-asterisk">*</span>
									<label class="reg-label">手机号</label>
									<input class="login-reg-input reg-input" type="text" placeholder="请输入您的手机号" name="mobile" rel="手机号不正确"/>
									<label class="labelwp"></label>
								</div>
								<div class="reg-div">
									<span class="reg-asterisk">*</span>
									<label class="reg-label">验证码</label>
									<a href="javascript:void(0)" style="margin-left:4px;height:40px;line-height:40px;" class="get-smscode ui-right" id="smsbutton">获取验证码</a>
									<input class="login-reg-input reg-input" type="text" name="smscode" style="width:140px" rel="验证码不正确"/>
									<label class="labelwp"></label>
								</div>
								<div class="reg-agree">
									<input type="checkbox" class="login-reg-input" checked="checked" valid="1" value="1" name="rigisteragree">
									<span>我已阅读并且同意 《<a href="/article/reg_protocal.html" target="_blank">智信创富金融服务协议(个人会员版)</a>》</span>
								</div>
								<div class="reg-div" style="text-align:center;">
									<a href="javascript:void(0)" class="ui-button ui-button-big ui-bg-brown reg-next-btn">提交注册</a>
								</div>
							</fieldset>
						</form>{__TOKEN__}
						<!-- <form id="form" action="" method="get">
							<div class="inputbox_name"><input name="name" type="text" class="reg-input"  id="name"/> 
							</div>
							<div class="inputbox_mima"><input name="password" type="password"  class="reg-input" id="mima"/></div>
							<div class="inputbox_phone"><input name="mobile" type="text"  class="reg-input" id="phone"/></div>
							<div class="inputbox_yz" style="width:296px;"><input name="mobile" type="text"  class="reg-input" id="yzm" value=""/> 
							<input name="" style="margin-left:5px; padding:0px;" type="button" id="btn" value="点击获取验证码" />
							</div> 
							<div class="inputbox" style="padding:5px; color:#666666; font-size:13px;" > <input name="" type="checkbox" id="rad" value="" checked /> 
							  我已同意<a href="#" style="color:#0084E2; font-style:normal; text-decoration:none;">《智信创富网站服务协议》</a></div>
							<div class="inputbox"> <input name="" type="submit" id="zc" value=" " align="middle" style="margin-top:10px" />
							</div>
							
							
						</form> -->
					</div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div style="background:url(_STATIC_/2015/landing/img/hsbg.jpg) repeat;">
		<div class="lay" id="bbbj">
			<div style="background:url(_STATIC_/2015/landing/img/why360.jpg) no-repeat center;"></div>
			<div style="background:#FFFFFF url(_STATIC_/2015/landing/img/bbbj.jpg) no-repeat center; height:485px;"></div>
		</div>
	</div>	
<div style=" background:#FFFFFF url(_STATIC_/2015/landing/img/tdjs.jpg) no-repeat center; height:113px;"></div>
<div style=" background:#FFFFFF url(_STATIC_/2015/landing/img/tdren.jpg) no-repeat center; height:687px;"></div>
<div style="background:url(_STATIC_/2015/landing/img/hsbg.jpg) repeat;">
<div style=" background:url(_STATIC_/2015/landing/img/gsry.jpg) no-repeat center; height:139px;"></div>
<div id="bbbj" style=" background:url(_STATIC_/2015/landing/img/gsryy.jpg) no-repeat center; height:393px;"></div></div>

</div>
<div></div>
<div></div>
<div class="clear"></div>
</div>
<script type="text/javascript" src="_STATIC_/2015/js/login-register.js?v=20150508"></script>
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
	$('form.ui-form').constract(true);
	$('form.ui-form a.reg-next-btn').submitfrom($('form.ui-form'));
	smsbutton.click(function() {
		getSmsCode();
	});
	$("input[name='rigisteragree']").click(function(){
		return false;
	});
})
</script>
