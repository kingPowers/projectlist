<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title>   
</head>
<script type="text/javascript">
	$(function(){
		$(".tkLayBg").height($(document).height()).hide();

		$('.go_repayment').click(function(){
			$(".tkLayBg").show();
			location.href='/credit/repayment';
		});
	})
</script>
<body style="background: #efefef;">
<section class="tkgrayBg" style="display:none;">
	<section class="tkdiv_bg" style="display:none;">
		<a href="/credit/creditagreement/order_id/{$_REQUEST['id']}" target="_blank">个人借款协议</a>
		<a href="/credit/creditagreement2/order_id/{$_REQUEST['id']}" target="_blank">居间服务协议</a>
		<a href="/credit/agree_supple/order_id/{$_REQUEST['id']}" target="_blank">补充协议</a>
		<eq name='is_staff' value='1'><a href="/credit/creditagreement4/order_id/{$_REQUEST['id']}" target="_blank">还款承诺书</a></eq>
		<a id="btn_cancel">取消</a>
	</section>
</section>
<header>
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>借款详情</h1>    
</header>

<section class="tkLayBg">
	<section class="pic_waiting">
		<img src="_STATIC_/2015/image/yydai/index/sw_wx_load.gif">
	</section>
</section>
<!--审核中开始-->
<eq name='order_info._order_status_' value='2'>
	<section class="content_div bgf" >
		<section class="forder_div">
			<b>审核中金额（元）</b>
			<h2>{$order_info.loanmoney}</h2>
			<p>请等待审核结果</p>
		</section>	
	</section>
</eq>
<!--审核中结束-->
<!--已还款开始-->
<eq name='order_info._order_status_' value='4'>
	<section class="content_div bgf" >
		<section class="forder_div">
			<b>已还款金额（元）</b>
			<h2>{$order_info.loanmoney}</h2>		
		</section>	
	</section>

	<section class="forder_details">
		<section class="forder-view-cell">
			<span>借款金额</span>
			<font>{$order_info.loanmoney}</font>
		</section>
		<section class="forder-view-cell">
			<span>合同期限</span>
			<font>{$order_info.pay_time}至{$order_info.back_date}</font>
		</section>
		<section class="forder-view-cell">
			<span>还款方式</span>
			<font>先息后本</font>
		</section>
		<section class="forder-view-cell">
			<span>借款合同</span>
			<a onclick="tk();">查看</a>
		</section>
	</section>
</eq>
<!--已还款结束-->
<!--逾期中开始-->
<eq name='order_info._order_status_' value='5'>
	<section class="content_div bgf">
		<section class="forder_div">
			<b>逾期中金额（元）</b>
			<h2>{$order_info.loanmoney}</h2>		
		</section>
		<section class="mui-repay-view" style="margin-top:0px;">
			<section class="mui-repay-view-cell" style="border-top:1px #e3e3e3 solid; border-bottom:0;"><font>滞纳金</font><span>{$order_info.late_fee}<b>元</b></span><b class="fr">已逾期<span>{$order_info.late_days}</span>天</b></section>
		</section>
		<section class="mui-table-view-cell  go_repayment" style="border-top:1px #e3e3e3 solid;">去还款</section>
	</section>
</eq>
<!--逾期中结束-->
<!--去还款开始-->
<eq name='order_info._order_status_' value='1'>
	<section class="content_div bgf">
		<section class="forder_div">
			<b>使用中金额（元）</b>
			<h2>{$order_info.loanmoney}</h2>		
		</section>
		<section class="mui-table-view-cell go_repayment" style="border-top:1px #e3e3e3 solid;" >去还款</section>
	</section>

	<section class="forder_details">
		<section class="forder-view-cell">
			<span>借款记录</span>
			<font>{$order_info.loanmoney}</font>
		</section>
		<section class="forder-view-cell">
			<span>合同期限</span>
			<font>{$order_info.pay_time}至{$order_info.back_date}</font>
		</section>
		<section class="forder-view-cell">
			<span>还款方式</span>
			<font>先息后本</font>
		</section>
		<section class="forder-view-cell">
			<span>借款合同</span>
			<a onclick="tk();">查看</a>
		</section>
	</section>
</eq>
<!--去还款结束-->
<section class="bottom_div">
	<a href='tel:4006639066'>联系我们</a>
	<font>400-663-9066</font>
</section>
</body>
</html>
<script type="text/javascript">
	function tk(){
	$(".tkdiv_bg,.tkgrayBg").show();
	$(".tkgrayBg").height($(document).height());
	$("#btn_cancel").click(function(){
		$(".tkdiv_bg,.tkgrayBg").hide();
	});	
}
</script>
{__NOLAYOUT__}	