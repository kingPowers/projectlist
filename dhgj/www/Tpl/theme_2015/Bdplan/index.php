<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="{$pageseo.title}">
    <meta name="description" content="{$pageseo.title}">
    <title>{$pageseo.title}</title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/style.css">
	<link rel="stylesheet" type="text/css" href="_STATIC_/2015/bdplan/index/css/style.css">
    <link href="_STATIC_/2015/box/wbox.css" type="text/css" rel="stylesheet" />
    <link href="_STATIC_/2015/js/lightbox/css/lightbox.css" rel="stylesheet" type="text/css" />
	<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/pintuer.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="_STATIC_/2015/js/lightbox/lightbox-2.6.min.js"></script>
</head>
<body>
<div class="ui-header">
	<div class="ui-header-wp ui-width">
		<div class="ui-logo ui-left"></div>
		<a href="http://www.zhifu360.com/member.html"><div class="now-login ui-left" style="margin:20px 0 0 20px">立即登录</div></a>
		<div class="ui-service ui-right ui-text-right"><strong>{:C('SERVICE_TELEPHONE')}</strong><br/>服务时间:（9:00~18:00）</div>
	</div>
</div>
<div class="ui-wp ui-clearfix">
	<div class="ui-slide-box {:ACTION_NAME}">
		<div class="ui-tip-box">
			<?php if($_SESSION['member']['id']){ ?> 
			<div class="ui-logsuc">
				<span class="ui-logsuc-msg">恭喜您，注册成功！</span>
				<div class="ui-logsuc-username">
					<span style="color:#FD4015;padding-left:5px">账号：</span>{$_SESSION['member']['username']}
				</div>
				<div class="ui-tip-start">
					<p>3月25号开放充值，3月27号10点开始抢标</p>
					<p>千万不要错过哦！</p>
				</div>
			</div>	
			<?php }else{ ?>
			<div class="ui-tip-shade"></div>
			<div class="ui-tip-content">
				<div class="ui-tip-content-wp">
				<form class="ui-form ui-right reg-form">
					<h2>5秒快速注册</h2>
					<p>
						<span class="activity-icon username"></span>
						<input type="text" placeholder="请输入用户名" name="username" value="" class="ui-input login-reg-input ui-right" rel="用户名不正确"/>
						<label class="labelwp ui-text-red"></label>
					</p>
					<p>
						<span class="activity-icon password"></span>
						<input type="password" placeholder="请输入密码" name="password" value="" class="ui-input login-reg-input ui-right" rel="密码不正确"/>
						<label class="labelwp ui-text-red"></label>
					</p>
					<p>
						<span class="activity-icon mobile"></span>
						<input type="text" placeholder="请输入手机号" name="mobile" value="" class="ui-input login-reg-input ui-right" rel="手机号不正确"/>
						<label class="labelwp ui-text-red"></label>
					</p>
					<p>
						<span class="activity-icon smscode"></span>
						<input type="text" placeholder="请输入验证码" name="smscode" value="" class="ui-input login-reg-input ui-left short" rel="验证码不正确"/>
						<a href="javascript:void(0)" class="ui-button ui-right ui-getsms" id="smsbutton">点击获取</a>
						<label class="labelwp ui-text-red"></label>
					</p>
					<input  placeholder="请输入手机号" type="hidden" name="url" value="http://www.zhifu360.com" class="login-reg-input" rel="跳转地址"/>
					<p class="ui-text-left"><input type="checkbox" class="login-reg-input" checked="checked" valid="1" value="1" name="rigisteragree" style="width:auto;height:auto;"><span>我已阅读并且同意 <a href="/article/reg_protocal.html" target="_blank" class="ui-text-brown">《服务协议》</a></span></p>
					<p><a href="javascript:;" class="ui-button ui-button-big ui-bg-brown reg-next-btn">立即注册</a></p>
				</form>{__TOKEN__}
				</div>
			</div>
			<?php } ?>
		</div>
	</div>


	<div class="ui-activity-step ui-activity-step2 bdplan">
		<span class="step"></span>
		<div class="ui-activity-step-wp ui-width">
			<h3 class="ui-text-center">智信创富大礼包 <strong class="ui-text-orange">送不停</strong></h3>
			<div class="ui-activity-register ui-width"></div>
		</div>
	</div>

	<div class="ui-activity-step ui-activity-step3 bdplan">
		<span class="step"></span>
		<div class="ui-activity-step-wp ui-width">
			<h3 class="ui-text-center"><strong>资金安全保障</strong></h3>
			<div class="ui-activity-safe"></div>
		</div>
	</div>

	<div class="ui-activity-step ui-activity-step4">
		<span class="step"></span>
		<h3 class="ui-text-center"><strong class="ui-text-orange">公司介绍</strong></h3>
		<div class="ui-activity-aboutus"></div>
		<div class="ui-activity-photo-bg">
			<div class="ui-activity-photo ui-width">
				<a href="javascript:;" class="ui-activity-photo-button ui-activity-photo-back unclick">后退</a>
				<div class="ui-activity-photo-wp">
					<ul>
						<li>
							<a href="javascript:;"><img src="_STATIC_/2015/team/activity-yanzhonghai.jpg" /></a>
							<div class="info">
								<h3>严中海 公司创始人</h3>
								<span>香港大学SPACE商业学院金融经济管理 硕士 <br/>
								香港大学财菁汇智俱乐部董事&上海主席<br/>
								新加坡上海商业协会理事<br/>
								上海经信委信息化理事<br/>
								香港玖龙纸业【代码：2689】战略合作伙伴<br/>
								新华社评“履行2014年度社会责任十大杰出人物”<br/>
								新华社评“2014年度经济人物”</span>
							</div>
						</li>
						<li>
							<a href="javascript:;"><img src="_STATIC_/2015/team/activity-hezhihao.jpg" /></a>
							<div class="info">
								<h3>何智浩 行政管理副总裁</h3>
								<span>新加坡国立大学机械工程一级荣誉学士<br/>
								新加坡国立大学机电工程硕士<br/>
								中欧国际工商学院工商管理国际EMBA<br/>
								曾担任：<br/>
								新丰国际控股再生能源投资有限公司（新加坡总部）营运总监<br/>
								新加坡国立大学创业计划海外学院（美国硅谷分院）院长<br/>
								新加坡国立大学企业部创业孵化器（美国硅谷分部）区域总监<br/>
								捷克摩拉维亚信息技术环球集团（南京大中华总部）执行总裁<br/>
								在新、中、美以及东南亚各地有多年的项目、营运及全面企业管理经验</span>
							</div>
						</li>
						<li>
							<a href="javascript:;"><img src="_STATIC_/2015/team/activity-liuqianyu.jpg" /></a>
							<div class="info">
								<h3>刘前雨 风险管理总监</h3>
								<span>北京工商学院工商管理本科  中共党员<br/>
								从业经历15年，在银行工作9年，8年的管理经验<br/>
								擅长于风险管控和销售团队的管理</span>
							</div>
						</li>
						<li>
							<a href="javascript:;"><img src="_STATIC_/2015/team/activity-daishuqin.jpg" /></a>
							<div class="info">
								<h3>戴书琴 首席财务官</h3>
								<span>复旦大学会计学本科，上海财经大学会计硕士（MPACC）<br/>
								在金融领域拥有10余年的丰富经验<br/>
								从事集团性财务管理15年<br/>
								擅长于连锁集团企业的财务核算管理工作</span>
							</div>
						</li>
						<li>
							<a href="javascript:;"><img src="_STATIC_/2015/team/activity-wangsongzhe.jpg" /></a>
							<div class="info">
								<h3>王松哲 互联网运营总监</h3>
								<span>悉尼科技大学互联网理学硕士学位<br/>
								多年从事互联网领域相关团队管理工作，对网络运维服务，网络<br/>
								安全服务，网络质量服务，网络规划设计，网络平台开发以及云<br/>
								平台服务等互联网相关技术拥有丰富的经验</span>
							</div>
						</li>
					</ul>
				</div>
				<a href="javascript:;" class="ui-activity-photo-button ui-activity-photo-prev">前进</a>
			</div>
		</div>
	</div>
	<!-- <div class="ui-activity-step ui-activity-step5" style="height:340px;">
		<ul class="ui-width">
			<li><a href="_STATIC_/2015/about/img/YY.jpg" class="example-image-link" data-lightbox="example-set" title="营业执照" style="display:block;overflow:hidden;"><img src="_STATIC_/2015/about/img/YY.jpg" style="height:200px;margin-left:55px;"></a></li>
			<li><a href="_STATIC_/2015/about/img/SW.jpg" class="example-image-link" data-lightbox="example-set" title="税务登记证" style="display:block;overflow:hidden;"><img src="_STATIC_/2015/about/img/SW.jpg" style="width:252px;margin-top:10px;"></a></li>
			<li><a href="_STATIC_/2015/about/img/ZZ.jpg" class="example-image-link" data-lightbox="example-set" title="组织机构代码证" style="display:block;overflow:hidden;"><img src="_STATIC_/2015/about/img/ZZ.jpg" style="width:252px;margin-top:8px;"></a></li>
			<li style="margin-right:0"><a href="_STATIC_/2015/about/img/map.jpg" class="example-image-link" data-lightbox="example-set" title="{$vo['title']}" style="display:block;overflow:hidden;"><img src="_STATIC_/2015/about/img/map.jpg" style="width:252px;margin-top:10px;"></a></li>
			<div style="clear:both;"></div>
		</ul>
	</div> -->
</div>
<script src="_STATIC_/2015/js/login-register.js?v=20150508" type="text/javascript" charset="utf-8"></script>
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
	$('.ui-logo').click(function(){ window.location.href='http://www.zhifu360.com/';});
	$('form.ui-form').constract(true);
	$('form.ui-form a.reg-next-btn').submitfrom($('form.ui-form'));
	smsbutton.click(function() {
		getSmsCode();
	});
	$("input[name='rigisteragree']").click(function(){
		return false;
	});
	var numberLi = 0,
		defaultSize = $('.ui-activity-photo-wp li').outerWidth(),
		setbuttonstatus = function(){
		if(numberLi >= ($('.ui-activity-photo-wp li').size() - 1)){
			$('a.ui-activity-photo-prev').addClass('unclick');
		}else{
			$('a.ui-activity-photo-prev').removeClass('unclick');
		}
		if(numberLi<=0){
			$('a.ui-activity-photo-back').addClass('unclick');
		}else{
			$('a.ui-activity-photo-back').removeClass('unclick');
		}
	};
	$('a.ui-activity-photo-prev').click(function(){
		if(numberLi >= ($('.ui-activity-photo-wp li').size() - 1)){
			return false;
		}
		numberLi++;
		setbuttonstatus();
		$('.ui-activity-photo-wp ul').animate({'left':'-'+numberLi*defaultSize+'px'},'slow');
	});
	$('a.ui-activity-photo-back').click(function(){
		if(numberLi<=0){
			return false;
		}
		numberLi--;
		setbuttonstatus();
		$('.ui-activity-photo-wp ul').animate({'left':'-'+numberLi*defaultSize+'px'},'slow');
	});
})
</script>
<div style="position:fixed;right:20px;top:48%;">
	<a href="http://crm2.qq.com/page/portalpage/wpa.php?uin={:C('SERVICE_QQ')}&aty=0&a=0&ty=1" target="_blank" class="ui-icon fqq"></a>
</div>
<div id="symantecSeal" style="text-align:center;" title="单击即可验证 - 该站点选择 Symantec SSL 实现安全的电子商务和机密通信">
<script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=www.zhifu360.com&amp;size=L&amp;use_flash=YES&amp;use_transparent=YES&amp;lang=zh_cn"></script>
</div>
<p class="ui-text-center"><a href="http://www.miitbeian.gov.cn/" target="_blank">沪ICP备13036559号-4</a> <?php if(strpos($httphost,DOMAIN_ROOT) <= 0):?> 合作支持：北京众鑫联合投资有限公司<?php endif;?></p>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?649cc7995121f6b1f18f35f6101a7766";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body></html>
{__NOLAYOUT__}