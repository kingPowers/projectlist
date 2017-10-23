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
		<a href="/credit/apply" class="btn_back">
	        <img src="_STATIC_/2015/member/image/register/return.png">
	        <font class="fl">返回</font>
	    </a>
	    <h1>身份验证</h1>    
	</header>
	<section class="w94" style="margin-top:40px;">
	<p>我们将发送<font>验证码</font>到您的手机</p>
	<p>{$member_info.mobile}</p>
	<section class="btn_div">
		<input type="text" id="text_yzm" name="text_yzm"  placeholder="请输入短信验证码" class="input_yzmb fl">
		<input type="button" name="" value="获取验证码" class="btn_yzmb fl">
	</section>	
	<input name="_credit_mobile_" value='{$_credit_mobile_}' type="hidden"/>	
	<input type="button" class="btn_sub credit_apply m40" value="下一步">
</section>
</body>
<script type="text/javascript">
var sendtimmer = null;
var sendsecond = 60;
var smsbutton = $('.btn_yzmb');
var returnurl = "/credit/apply_success/id/";
$('.btn_sub').removeAttr("disabled")
$(".btn_sub").click(function(){
    if(!$("#text_yzm").val()){
        alert('请输入验证码');
        return false;
    }
    
    var data = {};
    $(data).attr('sms_code',$("#text_yzm").val());
    
    $(this).attr("disabled",true).css({"background":"#ddd"});
    $.post('/credit/apply_submit',{'data':data},function(F){
         var F = eval(F);
          if(F.status==0){
             alert(F.info);
             $(".btn_sub").removeAttr("disabled").css({"background":"#63abe8"});
          }else{
              //alert(F.info);
              location.href=returnurl+F.data.add_id;
         }
      },'json');
});

//获取验证码
$(".btn_yzmb").click(function(){
   if($(this).attr('disabled'))return false;
   var data = {};
   $(this).attr("disabled",true).css({"background":"#ddd"});
   sendtimmer = setInterval('showTimemer()', 1000);
   $.post('/credit/creditSendSms',{'_credit_mobile_':$("input[name='_credit_mobile_']").val()},function(F){
        var F = eval(F);
         if(F.status==0){
            alert(F.info);
            $(".btn_yzmb").removeAttr("disabled").css({"background":"#63abe8"}).val("获取验证码");
            clearInterval(sendtimmer);
         }else{
          //alert(F);
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