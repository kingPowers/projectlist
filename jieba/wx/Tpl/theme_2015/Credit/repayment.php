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
    <h1>{$data.title}</h1>    
</header>
<form action='{$data.btn_url}' method='post' id='credit_repayment'>
	<section class="mui-repay-view">
		<section class="mui-repay-view-cell"><font>还款金额</font><span>{$data.back_money}<b>元</b></span></section>
		<!-- <section class="mui-repay-view-cell" style="border:0;"><font>金账户余额</font><span>{$data.ca_balance}<b>元</b></span></section> -->
	</section>
	<section class="mui-gray-view-cell">
		<p>还款金额将从您的银行卡内扣除</p>
	</section>
	<eq name='data.is_late' value='1'>
		<section class="mui-repay-view" style="margin-top:0px;">
			<section class="mui-repay-view-cell" style="border:0;"><font>滞纳金</font><span>{$data.late_fee}<b>元</b></span><b class="fr">已逾期<span>{$data.late_days}</span>天</b></section>
		</section>
		<section class="mui-gray-view-cell">
			<p>滞纳金收取1%/天</p>
		</section>
		<else/>
		<!--提前还款-->
		<section class="mui-repay-view" style="margin-top:0px;">
			<section class="mui-repay-view-cell" style="border:0;"><font>{$data.timeadd} 借款</font><span>{$data.loanmoney}<b>元</b></span><b class="fr">剩余<span>{$data.adv_days}</span>天</b></section>
		</section>
		<!--提前还款-->
	</eq>
	
	<section class="btn_div">
		<input type="text" id="text_yzm" name="sms_code"  maxlength='6' placeholder="请输入短信验证码" class="input_yzmb fl">
		<input type="button" name=""  value="获取验证码" class="btn_yzmb fl" id="send_ems">
	</section>
	
	<input name="order_id" type="hidden" value="{$data.order_id}"/>
	<input name="repayment" type="hidden" value="1"/>
	<input name="_bindbaofu_mobile_" type="hidden" value="{$_bindbaofu_mobile_}"/>
	<input type="button" autocomplete="off"  class="btn_sub credit_repayment" value="确定" style="margin-top:30px;">
</form>
</body>
<script type="text/javascript">
$(function(){
	$('.credit_repayment').removeAttr("disabled");
	//点击确定按钮，提交form表单
	$(".credit_repayment").click(function(){
		if($("input[name='sms_code']").val()==""){
			alert("请输入验证码");
			return false;
		}
		if($(this).attr('disabled'))return false;
		$(this).attr("disabled",true).css({"background":"#ddd"});
		$("#credit_repayment").submit();
		
	});
	
});
</script>
<script type="text/javascript">
var sendtimmer = null;
var sendsecond = 60;
var smsbutton = $('.btn_yzmb');
var returnurl = "{$returnurl}";

//获取验证码
$(".btn_yzmb").click(function(){
       if($(this).attr('disabled'))return false;
       var data = {};
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer()', 1000);
       $.post('/credit/repaymentSms',{'_bindbaofu_mobile_':$("input[name='_bindbaofu_mobile_']").val()},function(F){
            var F = eval(F);
             if(F.status==0){
                alert(F.info);
                $(".btn_yzmb").removeAttr("disabled").css({"background":"#63abe8"}).val("获取验证码");
                clearInterval(sendtimmer);
             }else{
                 alert(F.info);
                 if(F.info=="页面已失效，请刷新页面")location.href=myurl;
            }
         },'json');
     });
//验证码倒计时
var showTimemer = function() {
    if (sendsecond >=0) {
        smsbutton.val('重新发送( ' + sendsecond + ')');
        //smsbutton.css("color","#fff");
        sendsecond -= 1;
    } else {
        smsbutton.val('重新获取');
        clearInterval(sendtimmer);
        sendtimmer = null;
        sendsecond = 60;
        smsbutton.removeAttr("disabled").css({"background":"#63abe8"});
    }
}
</script>
</html>
{__NOLAYOUT__}