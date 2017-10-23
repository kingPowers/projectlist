<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/><link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css"> 
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
    	$(document).ready(function(){
    		if(isWeixin()) {
                $("#headers").css("display","block");
            }
    		$("#choose_bank").click(function(){
			    $("#btn_bank").slideToggle("slow");	
			  });
			  $("#btn_bank").children("li").click(function(){
				  $("#btn_bank").slideToggle("slow");
				  $('input[name="bank_name"]').val($(this).html());
			  });
    	})
    	//判断是否在微信中打开
        function isWeixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
    </script>
</head>
<body style="background: #efefef;">
<header >
	<a href="javascript:history.back();" class="btn_back"  id="headers" style="display: none;">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>{$cardInfo.title}</h1>    
</header>
<!--默认备注-->
<form id="orderForm" method="post" >
<section class="mui-table-view">
	<section class="order-list-li">
		<font>真实姓名</font><span>
		<input jschecktitle="真实姓名"  name="names" type="text" style="background-color: #fff" placeholder="请输入您姓名" value="{$cardInfo['names']}" <eq name='cardInfo.isBind' value='1'>readonly="readonly"</eq>>
		</span>
	</section>
	<section class="order-list-li">
		<font>身份证号</font><span><input jschecktitle="身份证号码"  name="id_card" type="text" placeholder="请输入您的身份证号码" value="{$cardInfo['id_card']}"  <eq name='cardInfo.isBind' value='1'>readonly="readonly"</eq>></span>
	</section>
	<section class="order-list-li">
		<font>银行卡号</font><input  style="width:70%;" type="text" name="acc_no" class="btn_job" placeholder="请输入您的银行卡号" value="{$cardInfo['acc_no']}">
	</section>
	<section class="order-list-li acity">
		<font>开户银行</font>
		<input  type="text" style="width: 60%" name="bank_name" id="choose_bank" placeholder="请选择您的开户银行" value="{$cardInfo['bank_name']}" readonly="readonly"> 
	</section>
	<ul class="order-gray-li" id="btn_bank" style="display: none;">
		<foreach name='bank_info' item='vo'>
			<li value="{$key}">{$vo}</li>
		</foreach>
		
	</ul>
	<section class="order-list-li">
		<font>手机号码</font><span><input type="text" name="mobile" placeholder="请输入银行预留手机号" value="{$cardInfo['mobile']}"></span>
	</section>
	<section class="order-list-li">
		<font>验证号码</font><span><input  type="text" id = "text_yzm" name="sms_code" placeholder="请输入验证号码" value=""   autocomplete="off" ></span>
		<a class="btn_byzm" style="cursor: point;">获取验证码</a>
	</section>		
</section>
<section class="w94">	
	<p>此验证码由宝付发送</p>
	
	<input type="hidden" name="_bindbaofu_mobile_" value="{$_bindbaofu_mobile_}"/>  	
	<input type="button" class="btn_sub" id="agent_sub" value="确认提交">	
</section>
</form>
</body>
</html>
<script type="text/javascript">
var sendtimmer = null;
var sendsecond = 60;
var smsbutton = $('.btn_byzm');
var returnurl = "{$returnurl}";
var myurl = "{$myurl}";
$(".btn_sub").click(function(){
    if(!$("#text_yzm").val()){
        alert('请输入验证码');
        return false;
    }
    
    var data = {};
    $(data).attr('sms_code',$("#text_yzm").val());
    $(data).attr("sub",1);
    $(this).attr("disabled",true).css({"background":"#ddd"});
    $.post('/credit/bindBaofu',data,function(F){
         var F = eval(F);
          if(F.status==0){
             alert(F.info);
             $(".btn_sub").removeAttr("disabled").css({"background":"#63abe8"});
          }else{
              alert(F.info);
              location.href=returnurl;
         }
      },'json');
});


//获取验证码
smsbutton.click(function(){
    if($(this).attr('disabled'))return false;
    var data = {};
    var input_name = {
               'names': '真实姓名',
               'id_card': '身份证号',
               'acc_no': '银行卡号',
               'bank_name': '开户银行',
               'mobile': '银行预留手机号',

               "_bindbaofu_mobile_":"绑定标识"
        };
       var is_check = 1;
       $.each(input_name, function (i, val) {
           $(data).attr(i, $('input[name="' + i + '"]').val());
           if ($(data).attr(i) == '' || undefined == $(data).attr(i)) {
           alert(val + "不能为空");
           is_check = 0;
            return false;
         }
       });
       if (is_check == 0)return false;
       if (!/^1\d{10}$/.test($(data).attr("mobile"))) {
           alert("请正确输入手机号");
           return false;
       }
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer()', 1000);
       $.post('/credit/bindBaofuSms',data,function(F){
            var F = eval(F);
             if(F.status==0){
                alert(F.info);
                smsbutton.removeAttr("disabled").css({"background":"#63abe8"}).html("获取验证码");
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
        smsbutton.html('重新发送( ' + sendsecond + ')');
        //smsbutton.css("color","#fff");
        sendsecond -= 1;
    } else {
        smsbutton.html('重新获取');
        clearInterval(sendtimmer);
        sendtimmer = null;
        sendsecond = 60;
        smsbutton.removeAttr("disabled").css({"background":"#63abe8"});
    }
}
</script>
{__NOLAYOUT__}