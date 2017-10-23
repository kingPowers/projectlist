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
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>账户充值</h1>    
</header>
<form action='/credit/carry' method='post' id='credit_carry'>
<section class="mui-repay-view bgf">
	<input type="text" name="money" value='{$_REQUEST['money']}' placeholder="请输入充值金额" class="input_carry">
</section>
<input type="hidden" name='returnurl' value='{$returnurl}'/>
<input type="button" name="" class="btn_sub m40" id="btn_carry" value="确认充值">
</form>
</body>
<script type="text/javascript">
$(function(){
	$('#btn_carry').removeAttr("disabled");
	//点击确定按钮，提交form表单
	$("#btn_carry").click(function(){
		var money = $("input[name='money']").val();
		money = Math.floor(money);
		money = money.toFixed(2);
		if(money<=0){
			alert("请输入充值金额");
			return false;
		}
		if($(this).attr('disabled'))return false;
		$(this).attr("disabled",true).css({"background":"#ddd"});
		$("#credit_carry").submit();
		
	});

	$("input[name='money']").bind('input propertychange', function () {		
		var money = $("input[name='money']").val();
		if (isNaN(money)) money = '';
		$("input[name='money']").val(money);
	});
	
});
</script>
</html>
{__NOLAYOUT__}