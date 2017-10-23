<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/carinsurance.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <title></title>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
	   
    </script>
</head>
<body style="background: #efefef;">
<header class="headers">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>还款</h1>    
</header>
<section class="tk_bd"></section>
<section class="pay_back bgf pay-notice-success">
    <img src="_STATIC_/2015/image/yydai/insurance/ico_ture.png">
    <h1>支付成功</h1>
    <a class="inp_box btn_bb" href='/allot/'>返回首页</a>
    <a class="inp_box btn_wl" href='/allot/repaymentList'>查看订单</a>
</section>

<section class="pay_back bgf pay-notice-error">
    <img src="_STATIC_/2015/image/yydai/insurance/ico_error.png">
    <h1>支付失败</h1>
    <a class="inp_box btn_bb" href='/allot/'>返回首页</a>
    <a class="inp_box btn_wl" href='/allot/repaymentList'>查看订单</a>
</section>

<section class="bgf mt40">
	<section class="repay_tit">
		<span>还款</span>
        <h1>{$backInfo.back_money}</h1>
        <font>可还{$backInfo.back_money}元</font>
	</section>
</section>

<section class="m10">
    <section class="order-con df">
        <input type="text" name="sms_code" placeholder="请输入短信验证码" class="stages_yzm inp_box"><button  class="s_btn_yzm inp_box btn_byzm" >获 取 验 证 码</button>
    </section>
    <input type="hidden" name="_payallot_instalment_mobile_" value="{$_payallot_instalment_mobile_}"/>  
    <button class="btn_sub add-edit-insurance-order">确 认 还 款</button>
</section>
</body>
<script type="text/javascript">
(function (doc, win) {
    var docEl = doc.documentElement,
    resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
    recalc = function () {
      var clientWidth = docEl.clientWidth;
      if (!clientWidth) return;
      docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';
    };
  
    if (!doc.addEventListener) return;
       win.addEventListener(resizeEvt, recalc, false);
       doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);




var sendtimmer = null;
var sendsecond = 60;
var smsbutton = $('.btn_byzm');
var returnurl = "{$returnurl}";
var back_type="{$_GET['back_type']}";
var instalmentid = "{$_GET['instalmentid']}";
$(".btn_sub").click(function(){
    if(!$("input[name='sms_code']").val()){
        alert('请输入验证码');
        return false;
    }
    
    var data = {};
    $(data).attr('back_smscode',$("input[name='sms_code']").val());
    $(data).attr("back_type",back_type);
    $(data).attr("paySub",1);
    $(data).attr("instalmentid",instalmentid);
    $(this).attr("disabled",true).css({"background":"#ddd"});
    $.post('/allot/beginRepayment',data,function(F){
         var F = eval(F);
          if(F.status==0){
             alert(F.info);
             $(".btn_sub").removeAttr("disabled").css({"background":"#63abe8"});
             $(".pay-notice-error").show();
          }else{
             // alert(F.info);
              $(".pay-notice-success").show();
              //location.href="/allot/index";
         }
      },'json');
});


//获取验证码
smsbutton.click(function(){
    if($(this).attr('disabled'))return false;
    var data = {};
    var input_name = {
               "_payallot_instalment_mobile_":"付款标识"
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
       $(data).attr("back_type",back_type);
       $(data).attr("instalmentid",instalmentid);
       if (is_check == 0)return false;
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer()', 1000);
       $.post('/allot/beginRepaymentSms',data,function(F){
            var F = eval(F);
             if(F.status==0){
                alert(F.info);
                smsbutton.removeAttr("disabled").css({"background":"#63abe8"}).html("获取验证码");
                clearInterval(sendtimmer);
                if(F.info=="页面已失效，请刷新页面"){
                    location.href="/allot/beginRepayment?back_type="+back_type+"&instalmentid="+instalmentid;
                }
             }else{
                 alert(F.info);
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
</html>
{__NOLAYOUT__}