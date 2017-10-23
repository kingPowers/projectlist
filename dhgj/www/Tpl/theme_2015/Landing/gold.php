<!DOCTYPE html>
<html lang="en">

<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="{$pageseo.keywords}">
    <meta name="description" content="{$pageseo.description}">
    <title>{$pageseo.title}</title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/style.css?20150521">
    <link href="_STATIC_/2015/box/wbox.css" type="text/css" rel="stylesheet" />
    <link href="_STATIC_/2015/index/index.css" type="text/css" rel="stylesheet" />
	<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/pintuer.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/index/index.js" type="text/javascript" charset="utf-8"></script>
	
	<link rel="stylesheet" href="_STATIC_/2015/landing/experience/css/gold.css?20150609">
	<!--[if IE]>
		<style>
		     .gold-span-left{position:relative;top:-1px;}
		</style>
	<![endif]--> 
	<script>
		if (/*@cc_on!@*/false) {
    	document.documentElement.className+='ie'+document.documentMode;
		}
		if (/*@cc_on!@*/true) {
    	document.documentElement.className+='ie'+document.documentMode;
		}
	</script>
	<style>
		   .ie10  .gold-span-left{position:relative;top:-1px;}
		   .ie11  .gold-span-left{position:relative;top:-1px;}
		   </style>
	<script>
		$(function(){
	$(".tip-top-content").click(function(){
		if($(this).index()===0){
			$('form.reg-form').constract(true);
			$('form.reg-form a.reg-next-btn').submitfrom($('form.reg-form'));
		}else{
			$('form.login-form').constract(false);
			$('form.login-form a.login-btn').submitfrom($('form.login-form'));
}
		$(this).addClass("top-now").siblings().removeClass("top-now");
		$(".gold-tip-bottom").eq($(this).index()).show().siblings(".gold-tip-bottom").hide();
	})
})
$(function(){
	$(window).on("scroll",function(){
		var navH=$(".ui-header").height()+$(".gold-banner").height();
		if($(this).scrollTop()>navH){
			$(".gold-nav").css({position:"fixed",top:0});
		}else{
			$(".gold-nav").css({position:"static"});
		}
	})
	$(".gold-nav-center>a").click(function(event){
		event.preventDefault();
		var conT=$(".gold-offset-content").eq($(this).index()).offset().top;
		$("body,html").animate({scrollTop:conT-50});
	})
	$(".get-gold").click(function(){
		$("body,html").animate({scrollTop:0});
	})
})
	</script>
</head>
<body>
<!--header-->
	<div class="ui-header">
		<div class="ui-top">
			<div class="ui-top-wp">
				<div class="ui-account ui-left">
					<a href="/login.html" class="ui-account-login">登录</a><a href="/register.html" class="ui-account-reg">注册</a>
				</div>
				<div class="ui-serviceinfo ui-right">
					<span class="ui-icon service-phone" style="margin-left:10px;"></span>
					服务热线：400-86-360-86（9:00~21:00）
				</div>
			</div>
		</div>
		<div class="ui-header-container ui-clearfix">
		<a href="/">
			<div class="ui-logo ui-ring-hover ui-left"></div>
		</a>
		<div class="gold-account ui-right">
			<span class="gold-listed">官方QQ群: <b>246408510</b></span>
			<span class="gold-listed">上股交代码: <b>202730</b></span>
			<a href="/">
				<div class="gold-return"><span class="return-bg"></span> 返回首页</div>
			</a>
		</div>
		</div>
	</div>
	<div class="gold-banner">
		<div class="gold-banner-center">
			<div class="gold-tip-center ui-right">
				<div class="gold-tip-border"></div>
				<div class="gold-tip-top">
					<div class="tip-top-content ui-left top-now">快速注册</div>
					<div class="tip-top-content ui-left">会员登录</div>
				</div>
<!--注册-->
			
				<div class="gold-tip-bottom" style="display:block">
				<form class="ui-form ui-right reg-form">
				<fieldset>
					<div class="tip-bottom-content">
						<span class="gold-span">
						<span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/user.png" alt=""></span>
						<input class="login-reg-input reg-input" type="text" placeholder="请输入用户名" name="username" rel="用户名不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/password.png" alt=""></span>
						<input class="login-reg-input reg-input" type="password" placeholder="请输入密码" name="password" rel="密码不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/tel.png" alt=""></span>
						<input class="login-reg-input reg-input" type="text" placeholder="请输入您的手机号" name="mobile" rel="手机号不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/code.png" alt=""></span>
						<input class="gold-text login-reg-input reg-input" type="text" name="smscode"  rel="验证码不正确"/>
						<input type="button" value="点击获取验证码" class="gold-button" id="smsbutton" style="position:relatice;top:1px;">
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/code.png" alt=""></span>
						<input allownull="1" class="login-reg-input reg-input" type="text" placeholder="如果您有邀请码,请输入" name="invitecode" id="invitecode" rel="邀请码不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="reg-agree">
						<input type="checkbox" class="login-reg-input" checked="checked" valid="1" value="1" name="rigisteragree" style="position:relative;top:4px"/>
						我已阅读并且同意 《<a href="/article/reg_protocal.html" target="_blank">智信创富金融服务协议(个人会员版)</a>》
						</span>
						<span class="gold-span" style="margin-top:10px;"><a href="javascript:void(0)" class="reg-next-btn"><input type="button" value="注册领取体验金" class="button"></a></span>
					</div>
				</form>{__TOKEN__}
				</div>
<!--登录-->
				<div class="gold-tip-bottom">
				<form class="ui-form ui-right login-form">
				<fieldset>
					<div class="tip-bottom-content">
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/user.png" alt=""></span>
						<input class="login-reg-input login-input" type="text" placeholder="请输入用户名" name="username" rel="用户名不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><span class="gold-span-left"><img src="_STATIC_/2015/landing/experience/img/password.png" alt=""></span>
						<input class="login-reg-input login-input" type="password" placeholder="请输入密码" name="password" rel="密码不正确"/>
						<label class="labelwp"></label>
						</span>
						<span class="gold-span"><a href="javascript:void(0)" class="login-btn"><input type="button" value="立即登录" class="button"></a></span>
						<a href="/public/forgetpwd.html" target="_blank" class="ui-text-yellow ui-right">忘记密码？</a>
					</div>
					</fieldset>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="gold-nav">
		<div class="gold-nav-center">
			<a href="">收益率</a>
			<a href="">资金安全保障</a>
			<a href="">运营模式如何安全保障</a>
			<a href="">为何借款人不找银行借款</a>
			<a href="">公司背景</a>
		</div>
	</div>
	<div class="index-content">
		<div class="index-container-center">
			<div class="index-safeguard"><a href="/article/deposit.html" target="_blank"><span class="money"></span><h4>风险保障金</h4><p>先行垫付风险保证金托管到银行</p><p>100%本息保障</p></a></div>
			<div class="index-safeguard"><a href="/monitor.html" target="_blank"><span class="gps"></span><h4>GPS全球定位</h4><p>实时掌握车辆状态及借款人动向</p><p>行业最严风控执行标准</p></a></div>
			<div class="index-safeguard"><a href="/d-142726809878.html" target="_blank"><span class="market"></span><h4>上股交上市机构</h4><p>携手上海经信委共建安全的金融</p><p>信息服务平台</p></a></div>
			<div class="index-safeguard"><a href="/about.html"  target="_blank"><span class="exp"></span><h4>10年行业经验</h4><p>拥有数百家线下理财和贷款门店</p><p>10年以上行业经验</p></a></div>
		</div>
		<div class="index-text-money">
			<div class="index-money-center">
				<div class="money-text"><p class="safe-text">{:number_format($countmember,0)}</p><p>在智信创富注册人数</p></div>
				<div class="money-text"><p class="safe-text">18%</p><p>最高化年利率</p></div>
				<div class="money-text"><p class="safe-text">{:number_format($sumtender,2)}</p><p>累计理财金额</p></div>
				<div class="money-text"><p class="safe-text">{:number_format($sumrate,2)}</p><p>已为智慧投资人创收</p></div>
			</div>
		</div>
	</div>	
	<div class="gold-content-odd gold-offset-content">
		<div class="gold-center" style="padding:90px 0 64px">
			<img src="_STATIC_/2015/landing/experience/img/statistics.png" alt="" class="ui-left" width="324px" height="282px">
			<div class="gold-center-left ui-right">
				<h2>高收益</h2>
				<p class="gold-first-p">1、1月短期标收益率为14%，3-6月标为15%-18% 是银行理财的数十倍。</p>
				<p>2、理财周期短，投资灵活。当月投资当月即可收回成本！</p>
				<p>3、提现周期一般为1个正常工作日。</p>
				<input type="button" value="领取体验金" class="gold-center-button get-gold">
			</div>
		</div>
	</div>
	<div class="gold-content-even gold-offset-content">
		<div class="gold-center">
			<div class="gold-center" style="padding:58px 0;text-align:center">
				<h2>如何保障资金安全？</h2>
				<div class="gold-fund">
					<img src="_STATIC_/2015/landing/experience/img/fund_1.png" alt="" width="182px" height="182px">
					<h4>风险保障金</h4>
					<p>先行垫付风险保障金 托管到银行
100%本息保障</p>
				</div>
				<div class="gold-fund">
					<img src="_STATIC_/2015/landing/experience/img/fund_2.png" alt="" width="183px" height="182px">
					<h4>金额变现容易</h4>
					<p>如果发生借款人不还款，抵押物
为车辆，金额小变现容易</p>
				</div>
				<div class="gold-fund">
					<img src="_STATIC_/2015/landing/experience/img/fund_3.png" alt="" width="182px" height="182px">
					<h4>数百家线下门店</h4>
					<p>线下有10年以上的全国数百家
门店理财和贷款从业经验</p>
				</div>
				<a href="/article/deposit.html" target="_blank"><input type="button" value="查看详情" class="gold-center-button gold-center-button-left"></a>
				<input type="button" value="领取体验金" class="gold-center-button get-gold">
			</div>
		</div>
	</div>
	<div class="gold-content-odd gold-offset-content">
		<div class="gold-center">
			<div class="gold-center" style="padding:52px 0 55px">
				<img src="_STATIC_/2015/landing/experience/img/run.png" alt="" class="ui-left" width="343px" height="307px">
				<div class="gold-center-left ui-right">
					<h2>运营模式安全</h2>
					<p class="gold-first-p">1、所有标均为抵押物担保，标和抵押物一一对应，可实时追踪抵押车辆状况。</p>
					<p>2、所有产品来源均为各地门店提供，线下项目和线上一一对应，投资人投资 资金对应线下抵押借款。
					</p>
					<p>3、平台方先行垫付投资人资金保障金，保障金远超单项目借款金额数十倍。</p>
					<input type="button" value="领取体验金" class="gold-center-button get-gold">
				</div>
			</div>
		</div>
	</div>
	<div class="gold-content-even gold-offset-content">
		<div class="gold-center">
			<div class="gold-center" style="padding:52px 0 55px">
				<img src="_STATIC_/2015/landing/experience/img/bank.png" alt="" class="ui-right" style="margin-right:64px" width="391px" height="276px">
				<div class="gold-center-left ui-left">
					<h2>借款人为何不找银行贷款？</h2>
					<p class="gold-first-p">1、短期贷款周转时间短，银行贷款手续繁琐。</p>
					<p>2、利率在法定民间借款利率范围内和借款人承受范围内。</p>
					<p>3、公司行业资质齐备，从业时间久，贷款人认可度高。</p>
					<input type="button" value="领取体验金" class="gold-center-button get-gold">
				</div>
			</div>
		</div>
	</div>
	<div class="gold-content-odd gold-offset-content">
		<div class="gold-center">
			<div class="gold-center" style="padding:67px 0">
				<img src="_STATIC_/2015/landing/experience/img/company.png" alt="" class="ui-left" width="448px" height="314px">
				<div class="gold-center-left ui-right gold-p-bg" style="width:550px">
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2014年荣获上海经信委信息化理事单位</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;新华社评2014年度履行社会责任优秀企业</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2014年荣获中国汽车流通协会会员单位</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2014年荣获中国互联网络信息中心可信单位</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2014年度华尊奖-中国互联网金融最佳诚信平台服务奖</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2014年度上海宝山区爱心企业</p>
					<p>&nbsp;&nbsp;&nbsp;&nbsp;2013中国小额信贷行业信用信息共享服务平台会员机构</p>
					<a href="/about.html" target="_blank"><input type="button" value="查看详情" class="gold-center-button gold-center-button-left"></a>
					<input type="button" value="领取体验金" class="gold-center-button get-gold">
				</div>
			</div>
		</div>
	</div>
<script src="_STATIC_/2015/js/login-register.js?v=20150526" type="text/javascript" charset="utf-8"></script>

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
			mobileobj.parent().addClass('gold-span-error');
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
	$('form.reg-form a.reg-next-btn').submitfrom($('form.reg-form'));
	smsbutton.click(function() {
		getSmsCode();
	});
	$("input[name='rigisteragree']").click(function(){
		return false;
	});


})


</script>
	<include file="Public:pageFooter" />	

{__NOLAYOUT__}