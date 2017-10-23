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
<form method="post" action="/agent/is_register">
<section class="mui-table-view">
	<section class="auth-list-li">
        <input type="hidden" name="agentRecintcode" value="{$agentRecintcode}">
		<font>手机号码</font><span><input type="text" name="mobile" placeholder="请输入手机号码" >
		</span>
	</section>		
</section>
<section class="w94">
</form>	
	<input type="button" class="btn_sub" value="下一步">
</section>

</body>
<script type="text/javascript">
$(function(){
    $(".btn_sub").click(function(){
        $mobile = $("input[name='mobile']").val();
        $agentRecintcode = $("input[name='agentRecintcode']").val();
        if(!$mobile){alert("请输入手机号码");return false;}
        if(!$agentRecintcode){alert("邀请码不正确");return false;}
        $("form").submit();
        // $.post("/agent/not_login",{"mobile":$mobile,"is_ajax":1,"agentRecintcode":$agentRecintcode},function(F){
        //     //var F = eval("(" + F + ")");
        //   console.log(F);
           
        //     if(F.status==1)
        //     {
        //         window.location.href = "/agent/bind_agent/mobile/"+$mobile+"/agentRecintcode/"+$agentRecintcode;
        //     }else{
        //          alert(F.info);
        //         window.location.href = "/member/register?mobile="+$mobile+"&redirecturl="+'<?php echo urlencode("/agent/bind_agent/agentRecintcode/".$agentRecintcode."/mobile/".$mobile);?>'
        //     }
        // },"json")
    })
})
</script>
</html>
{__NOLAYOUT__}