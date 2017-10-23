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
    <style type="text/css">
    	.agree{position: relative; margin-top: 10px; width: 100%; color: #b6b6b6; text-align: center; height: 23px; line-height: 23px;}
    	.agree input{margin-right: 5px; width: 20px; height: 20px; display: inline-block; vertical-align: middle;}
    	.middle_div{position: relative; margin-top: 50px; color: #000; text-align: center;}
    	.middle_div font{color: #5495e6;}
        a{color: #000;}
    </style> 
    <script type="text/javascript">
    	$(function () {
    		$("#agree").attr('checked',false);
    		$("#agree").change(function(){
    			$(".middle_div").toggle();
    		})
    	})
    </script> 
</head>
<body style="background: #efefef;">
<header>
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>签署合约</h1>   
</header>
<section class="commend_div">
	<section class="mui-repay-view-cell" style="text-align: center; color: #5495e6;">
		<a href="/credit/creditagreement/order_id/{$_REQUEST['order_id']}">个人借款协议</a>
	</section>
	<section class="mui-repay-view-cell" style="text-align: center; color: #5495e6;">
		<a href="/credit/creditagreement2/order_id/{$_REQUEST['order_id']}">居间服务协议</a>
	</section>
    <section class="mui-repay-view-cell" style="text-align: center; color: #5495e6;">
        <a href="/credit/agree_supple/order_id/{$_REQUEST['order_id']}">补充协议</a>
    </section>
	<eq name='is_staff' value='1'><section class="mui-repay-view-cell" style="text-align: center; color: #5495e6;">
		<a href="/credit/creditagreement4/order_id/{$_REQUEST['order_id']}">还款承诺书</a>
	</section></eq>
</section>
<section class="agree">
	<input type="checkbox" name="agree" id="agree"/>同意贷款相关合同（勾选之后才可签约）
</section>

<section class="middle_div" style="display:none;">
	<p>我们将发送<font>验证码</font>到您的手机</p>
	<p><?php echo substr($member_info['mobile'],0,3)."****".substr($member_info['mobile'],-4);?></p>
	<section class="w94">	
	<section class="btn_div">
		<input type="text" id="text_yzm" name="text_yzm"  maxlength='6' placeholder="请输入短信验证码" class="input_yzmb fl">
		<input type="button" name=""  value="获取验证码" class="btn_yzmb fl" id="send_ems">
	</section>
	</section>
</section>
<input type="hidden" value="{$order_ids}" id="order_id">
<input type="hidden" value='{$_eqianbao_mobile_}' name='_eqianbao_mobile_'/>
<input type="button" class="btn_sub credit_apply m40" value="确定" id="commit">
</body>
</html>
{__NOLAYOUT__}
<script type="text/javascript">
	var sendtimmer = null;
	var sendsecond = 60;
	var smsbutton = $('#send_ems');
	$("#send_ems").removeAttr("disabled").css({"background":"#63abe8"});
	$("#commit").removeAttr("disabled").css({"background":"#63abe8"});
	
    $("#send_ems").click(function(){
        var data = {};
        if($(this).attr('disabled'))return false;
        $(this).attr("disabled",true).css({"background":"#ddd"});
        
        $(data).attr('_eqianbao_mobile_',$("input[name='_eqianbao_mobile_']").val());
        sendtimmer = setInterval('showTimemer()', 1000);
        
        $.post('/Credit/send_sms.html',{'data':data},function(result){
            if(result.status==1){
            	alert(result.info);
             }else{
            	 $(".btn_yzmb").removeAttr("disabled").css({"background":"#63abe8"}).val("获取验证码");
                 clearInterval(sendtimmer);
            	 alert(result.info);
            	 if(result.info=="页面已失效，请刷新页面")location.href="/credit/creditAgreement3/order_id/{$_REQUEST['order_id']}";
             }
            
        },'json');
    })
    
    
    $("#commit").click(function(){
        var data = {};
        $(data).attr('code',$("#text_yzm").val());
        $(data).attr('order_id',$("#order_id").val());
        $(data).attr('agree',$("#agree").val());
        if(!$("#agree").is(':checked')){
        	alert('请先同意贷款相关合同');
            return false;
         }
        if(!$("#text_yzm").val()){
            alert('请输入验证码');
            return false;
        }
        $(this).attr("disabled",true).css({"background":"#ddd"}).val("签约中，请稍后…");
        
        $.post('/Credit/creditAgreement3.html',{'data':data},function(result){
			if(result.status==1){
				 alert(result.info);
				 window.location.href="{:U('Credit/index')}";
			}else{
				alert(result.info);
				$("#commit").removeAttr("disabled").css({"background":"#63abe8"}).val("确定");
			}
            
           
           
        },'json');
    })
    
    
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