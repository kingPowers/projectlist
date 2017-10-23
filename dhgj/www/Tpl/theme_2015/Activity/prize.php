<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="keywords" content="{$pageseo.keywords}">
    <meta name="description" content="{$pageseo.description}">
    <title>智信创富抽奖活动</title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/style.css?20150602">
    <link href="_STATIC_/2015/box/wbox.css" type="text/css" rel="stylesheet" />
	<script src="_STATIC_/2015/js/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
	<script src="_STATIC_/2015/js/pintuer.js" type="text/javascript" charset="utf-8"></script>
	<link rel="stylesheet" href="_STATIC_/2015/index/index.css?20150609">
	<link rel="stylesheet" href="_STATIC_/2015/activity/prize/zhifu-active.css">
	<script src="_STATIC_/2015/index/index.js"></script>
	<script type="text/javascript" src="_STATIC_/2015/activity/prize/awardRotate.js"></script>
</head>
<body>
	<div class="ui-header">
		<div class="ui-top">
			<div class="ui-top-wp">
				<div class="ui-left">
					<span class="zf-top-span" style="width:160px;margin-top:0;">官方QQ群：<b>246408510</b></span>
				</div>
				<div class="ui-serviceinfo ui-right">
					<span class="ui-icon service-phone" style="margin-left:10px;"></span>
					服务热线：400-86-360-86（9:00~21:00）
				</div>
			</div>
		</div>
		<div class="ui-header-container ui-clearfix">
		<a href="/"><div class="ui-logo ui-ring-hover ui-left"></div></a>
		<div class="zf-account ui-right">
			<span class="zf-listed">上股交代码: <b>202730</b></span>
			<a href="/login.html" class="zf-account-login">登陆</a>
			<a href="/register.html" class="zf-account-register">注册</a>
		</div>
		</div>
	</div>
	<div class="zf-banner"></div>
	<div class="ui-wp index-index-wp">
		<div class="active-content">
			<div class="active-center">
				<foreach name="tenders" item="vo">
					<div class="invest-content"><a href="/zf-{$vo['loansn']}.html" target="_blank"><img src="_STATIC_/{$vo.slt_path}"></a><div class="invest-content-right"><a href="/zf-{$vo['loansn']}.html" target="_blank"><h2>{$vo['title']}</h2></a><p><span>年化收益率：<b>{$vo['yearrate']}%</b></span><span>借款期限：<b>{$vo['loanmonth']}</b>个月</span><span>借款金额：<b>{:number_format($vo['loanmoney'],2)}元</b></span><span>投资进度：<b class="speed-num">{$vo['tenderspeed']}%</b></span><span class="plan"><span class="speed"></span></span></p></div><button class="invest-button" onclick="javascript:window.open('/zf-{$vo['loansn']}.html')">立即投标</button></div>
				</foreach>
			</div>
		</div>
		<div class="rule-content">
			<div class="rule-center">
				<h5 style="margin-left:10px;"><img src="_STATIC_/2015/activity/prize/img/rule.png" alt=""></h5><br>
				<p class="content-p">1、活动期间，单笔投资500元即获得一次抽奖机会，享受一次“幸运大转盘”的机会，多充多得。举例：小杨投资了2万元，即可获得40次抽奖机会。</p>
				<p class="content-p">2、“幸运大转盘”分为10档，奖品分别为：iphone 6、ipad mini、apple watch、小米note、小米平板、红米note、100元智信创富红包、50元智信创富红包、10元智信创富红包、感谢参与。</p>
			</div>
		</div>
		<div class="lottery">
			<div class="lottery-center">
				<div style="width:500px;text-align:center;font-size:20px;color:#a26802;" id="note">
				<if condition="$cnt gt 0">您还有<b style="color:#ff6767;">{$cnt}</b>次抽奖机会！
				<elseif condition="$cnt eq 0"/>先投标才有抽奖机会哦！
				<else />请先登录再进行抽奖！</if></div>
				<div class="lottery-left"><img src="_STATIC_/2015/activity/prize/img/1_06.png" alt="" style="width:499px;height577px;"><img id="rotate" src="_STATIC_/2015/activity/prize/img/1_09.png" alt="" class="award"><img src="_STATIC_/2015/activity/prize/img/1_12.png" alt="" class="pointer"></div>
				<div class="lottery-right">
				<div class="right-top"></div>
				<div class="right-bottom">
					<ul class="lottery-ul">
						<foreach name="prizes" item="vo">
							<li>恭喜投资者&nbsp;&nbsp;{:hide_username($vo['username'])}&nbsp;&nbsp;&nbsp;抽中了&nbsp;&nbsp;{$vo.prize}</li>
						</foreach>
					</ul>
				</div></div>
			</div>
		</div>
		<div class="bid"><span onclick="window.location.href='/zf.html'"></span></div>
	</div>
	<script src="_STATIC_/2015/js/jquery.SuperSlide.2.1.1.js"></script>
	<script type="text/javascript">
		jQuery(".right-bottom").slide({mainCell:".lottery-ul",autoPlay:true,effect:"topMarquee",vis:5,interTime:40});
	</script>
	<include file="Public:pageFooter" />
<script>
	$(function (){

		var bRotate = false;
		var rotateFn = function ( angles, txt , cnt){
			bRotate = !bRotate;
			$('#rotate').stopRotate();
			$('#rotate').rotate({
				angle:0,
				animateTo:angles+1800,
				duration:15000,
				callback:function (){
					var reload = false;
					if (txt!='感谢参与') {
						txt = '恭喜您获得'+txt;
						//reload = true;
					}
					jdbox.alert(1,txt+'!','');
					bRotate = !bRotate;
					var note = '您还有<b style="color:#ff6767;">'+cnt+'</b>次抽奖机会！';
					if (cnt==0) {
						note = '您已用完所有抽奖机会，请继续投标！';
					}
					$('#note').html(note);
				}
			})
		};
		
		$('.pointer').click(function (){

			if(bRotate)return;
			
			$.ajax({
				url : '/activity/prizeSubmit.html',
				cache : false,
				async : false,
				type : "GET",
				dataType : 'json',
				timeout : 30000,
				success : function (result){
					if(result.status){
						rotateFn(result.data.angle,result.data.prize,result.data.cnt);	
					}else{
						return jdbox.alert(0,result.info,'');
					}
				},
 				complete: function(obj,status){
					if (status == 'timeout'){
						jdbox.alert(0,'网络超时，请检查网络设置！','');
					}
				} 
			});
		});
	});
	</script>
{__NOLAYOUT__}