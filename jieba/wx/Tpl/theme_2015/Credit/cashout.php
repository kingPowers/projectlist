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
<body style="background: #efefef;">
<header>
	<a href="/credit/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1></h1>    
</header>
<section class="mui-repay-view bgf">
	<section class="mui-cashout-cell"><font>提现银行卡</font> <span>{$fuyou_info.bank_name}</span></section>
	<section class="mui-cashout-cell" style="border:0;display:none;"><font>可提现金额(元)</font><b>{$fuyou_info.balance}</b></section>	
</section>
<form action='/credit/cashout' method='post' id='credit_cashout'>
<section class="txmiddlegray m30">
	<input type="text" name="money" autocomplete='off' placeholder="请输入提现金额" class="input_cashout bgf">元
	<input type="button" name="all_money" value="全提" class="btn_all fr" id='btn_all'>
</section>
<section class="quren"> 	
	<p>
		<span class="wz">实际到账金额：</span>
		<span class="sz" id="jine">0.00</span>元
	</p>
</section>
<input type="hidden" name='returnurl' value='{$returnurl}'/>
<input type="button" name="" class="btn_sub" id="btn_cashout" value="确认提现">
</form>
<section class="scroll_text">
	<div>
        <h2>温馨提示：</h2>
        <p>1. 提现资金均在个人存管账户中进行，如有疑问请致电富友客服95138，或微信搜索富友金账户客服；</p>
        <p>2. 在发起提现后，提现到账时间预计为2小时到账（取决于入账银行的处理时间）请您注意查收。</p>       
        <p>3. 由于第三方支付公司限制，每笔取现限额为100万元，当日无限额;</p>
        <p>4. 为正常使用快速提现功能，请先前往第三方支付平台点击“系统管理”，授权管理”勾选“委托提现”后启用委托提现服务；</p>
        <p>5. 禁利用借吧进行套现、洗钱、匿名转账，对于频繁的非正常投资为目的的资金充提行为，一经发现，借吧将通过原充值渠道进行资金清退，已收取手续费将不予返还。
；</p>
        <p>6.<a style="color:#5a95dd" href="https://jzh.fuiou.com/help/help.html">富友存管账户常见问题。</a></p>
    </div>
</section>
</body>

<script type="text/javascript">
$(function(){
	$('#btn_cashout').removeAttr("disabled");
	var max_money = '{$fuyou_info.balance}';
	var fee = 0;
	//点击确定按钮，提交form表单
	$("#btn_cashout").click(function(){
		var money = $("input[name='money']").val();
		money = Math.floor(money);
		money = money.toFixed(2);
		if(money<=0){
			alert("请输入提现金额");
			return false;
		}
		if($(this).attr('disabled'))return false;
		$(this).attr("disabled",true).css({"background":"#ddd"});
		$("#credit_cashout").submit();
	});

	$("input[name='money']").bind('input propertychange', function () {		
		var money = parseInt($("input[name='money']").val());
		if (isNaN(money)) money = '';
		if(money>max_money)money=max_money;
		$("input[name='money']").val(money);
		var ac_money = money-fee>0?money-fee:0;
		$('#jine').text(ac_money);
	});
	
	$("#btn_all").click(function(){return false;
		$("input[name='money']").val(max_money);
		var ac_money = max_money-fee>0?max_money-fee:0;
		$('#jine').text(ac_money);
	});
	
});
</script>

</html>
{__NOLAYOUT__}