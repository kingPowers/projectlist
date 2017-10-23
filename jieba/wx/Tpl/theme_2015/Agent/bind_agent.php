<!DOCTYPE html>
<html>
<head>
    <head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css"> 
    <script type="text/javascript" src="_STATIC_/2015/js/ckform.js"></script> 
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title> 
    
</head>
<body style="background: #efefef;">
<header>	
</header>
<section class="mui-table-view">	
	<section class="auth-list-li">
		<font>手机号码</font><input type="text" style="background: #fff" name="mobile" disabled="" value="{$mobile}" ></input>
	</section>
	<section class="auth-list-li">
        <input type="hidden" name="trueRecintcode" value="{$trueRecintcode}">
		<font>邀请号码</font><input type="text" style="background: #fff" name="agentRecintcode" disabled="" value="{$agentRecintcode}"></input>
	</section>
    <if condition="$is_bind eq 0">		
    	<section class="auth-list-li">
          <input type="hidden" name="_bind_mobile_" value="{$_bind_mobile_}">
    	    <input type="hidden" name="_bind_" value="{$_bind_}">
    		<font>验证号码</font><span><input type="text" jschecktitle="手机验证码" jscheckrule="null=0" placeholder="请输入手机验证码" name="verify_code" id="input_yzm"><a><input type="button" name="" id="apply_code"  value="获取验证码" ></a></span>
    	</section>
    </if>		
</section>
<section class="w94">	
   <if condition="$is_bind eq 0">
	 <input type="button" class="btn_sub" id="bind_sub" value="确认提交">
   </if>
</section>
</body>
<script type="text/javascript">
$(function(){
    $("#apply_code").click(function(){
        $mobile = $("input[name='mobile']").val();
        $_bind_mobile_ = $("input[name='_bind_mobile_']").val();
         var myThis=$(this);
        if(!$mobile){alert("请填写手机号码");return false;}
        $.post("/agent/send_bind_sms",{"mobile":$mobile,"_bind_mobile_":$_bind_mobile_},function(F){
            alert(F.info);
            if(F.status == 1){
                myThis.attr("disabled",true).css({"background":"#ddd","color":"#666"});
              sendtimmer = setInterval('showTimemer()', 1000);
            }
        },"json")
    })
    $("#bind_sub").click(function(){
        $mobile = $("input[name='mobile']").val();
        $agentRecintcode = $("input[name='trueRecintcode']").val();
        $verify_code = $("input[name='verify_code']").val();
        if(!$verify_code){alert('请输入手机验证码');return false;}
        $bind = $("input[name='_bind_']").val();
        $.post("/agent/submit_bind",{"mobile":$mobile,"agentRecintcode":$agentRecintcode,"verify_code":$verify_code,"_bind_":$bind},function(F){
          alert(F.info);
          if(F.info == '页面已失效，请刷新页面')window.location.reload();
          if(F.status == 1)window.location.reload();
        },"json")
    })
})
 var sendsecond = 60;
 var smsbutton = $('#apply_code');
 var sendtimmer = null;
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
    smsbutton.removeAttr("disabled").css({"background":"none","color":"#5495e6"});
}
}
</script>
</html>
{__NOLAYOUT__}