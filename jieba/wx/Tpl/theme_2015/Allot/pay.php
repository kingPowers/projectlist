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
     $(function(){
      var bus_discount = $("input[name='bus_discount']").val();
      var business_money = parseFloat("{$detail['business_money']}");  
      var force_money = parseFloat("{$detail['force_money']}");
       var total_money = force_money+business_money;
       //分期付款checkbox
        $("#stages_btn").click(function(){ 
            $(".total_money").html(parseFloat(force_money)+parseFloat(business_money));
            $(".business_money").html(business_money);              
            $("#stages_box").slideDown("slow");
            $(".fq").show();
            //全额付款checkbox
            $("#stages_none").click(function(){    
                $(".total_money").html(parseFloat(force_money)+parseFloat(bus_discount*business_money));
                $(".business_money").html(bus_discount*business_money);
                $("#stages_box").slideUp("slow"); 
                $(".fq").hide();
            })             
        });
            $("#xy_sq,#xy_fq").click(function(){
                var pay_type = $(this).attr("pay_type");//class='pay-type-radio pay-type2'allot-content
                $(".pay-type-radio").removeAttr("checked");
                $(".allot-content").hide();
                if(pay_type==1){
                    $(".pay-type1").attr("checked",true);
                }else{
                    $(".allot-content").show();
                    $(".pay-type2").attr("checked",true);
                }
                $(".tk_bd,.tk_xy").show();   
                $(".no").click(function(){
                    $("#xy_sq,#xy_fq").removeAttr("checked");
                     $(".tk_bd,.tk_xy").hide(); 
                 });      
            })
            $(".btn_sub").click(function(){
            	//$(".tk_bd,.pay_back").show();            	
            })
            $(".order-head").click(function(){
			   $(".tk_bd,.tk_photo").show();
			    $(".tk_photo").children("img").attr("src",$(this).attr("src"));
                 $(".tk_bd").click(function(){
                     $(".tk_bd,.tk_photo").hide(); 
                 });     
			})
       })
    </script>
</head>
<body style="background: #efefef;">
<header class="headers">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>我的车险</h1>    
</header>   
<section class="tk_bd"></section>
<section class="pay_back bgf pay-notice-success">
	<img src="_STATIC_/2015/image/yydai/insurance/ico_ture.png">
	<h1>支付成功</h1>
	<a class="inp_box btn_bb" href='/'>返回首页</a>
	<a class="inp_box btn_wl" href='/allot/'>查看订单</a>
</section>

<section class="pay_back bgf pay-notice-error">
	<img src="_STATIC_/2015/image/yydai/insurance/ico_error.png">
	<h1>支付失败</h1>
	<a class="inp_box btn_bb" href='/'>返回首页</a>
	<a class="inp_box btn_wl" href='/allot/'>查看订单</a>
</section>

<section class="tk_photo">
	<img src="{$detail.price_pic}">
</section>

<section class="tk_xy bgf">
  <h1>车辆保险授权协议</h1>
  <section class="xy_con">
    <p>委托人:{$member_info.names}</p>
    <p>身份证号:{$member_info.certiNumber}</p>
    <p>被委托人:智信创富金融信息服务（上海）有限公司</p>
    <p>本人现有一辆汽车，特委托智信创富金融信息服务（上海）有限公司作为我的合法代理人，购买车辆保险：{$detail.insurance_type}，对受托人在办理上述事项过程中所签署的有关文件，我均予以认可，并承担相应的法律责任。</p>
    <div class="allot-content" style="display: none;">
        <p>本人选择分期方式支付保险费用，费用支付方式为:</p>
        <p>首付（<?php echo date("Y.m.d");?>）：交强险费用{$detail.force_money}元+30%商业险费用<?php echo $detail['business_money']*0.3;?>元={$instalmentList.0.initial_money}元</p>
        <foreach name="instalmentList" item="vo" key="key">
            <p>第{$vo.period}期（{$vo.back_time|str_replace=['年','月','日'],['.','.',''],###}）:{$vo.pay_money}元</p>
        </foreach>
        <p>若委托人逾期未支付上述保险合同中的保险费用，则保险公司根据相关合同约定为委托人进行退保处理。</p>
    </div>
    <p>委托期限：自签字之日起至上述事项办完为止。</p>
    <p>委托人：{$member_info.names}</p>
    <p>被委托人：智信创富金融信息服务（上海）有限公司</p>
    <p>日期：<?php echo date("Y.m.d");?></p>
  </section>
  <section class="m10">
    <section class="order-con df">
      <input type="text" name="eqian_smscode" placeholder="请输入短信验证码" class="stages_yzm inp_box"><button  class="s_btn_yzm inp_box " id='eqian-sms-btn'>获取验证码</button>
    </section>
  </section>
  <section class="m10">
    <section class="order-con df">
      <input type="button" name="" value="同意" class="btn_blue agree  eqian-agree-btn">
      <input type="button" name="" value="不同意" class="btn_blue no">
    </section>
  </section>
</section>

<section class="bgf mt40">
	<section class="order-con">
		<section class="bot_box">
			<section class="order-head">
				<section class="ico_policy">
					<img src="_STATIC_/2015/image/yydai/insurance/ico_look.png" class="ico_look">
					<img src="{$detail.price_pic}" class="ico_policy">
				</section>		
			</section>			
			<section class="box_left">
        <input type="hidden" name="bus_discount" value="{$detail.bus_discount}">
				<font>申请险种:</font>               
				<p>
                                    <label><img src="_STATIC_/2015/image/yydai/insurance/ico_right.png">
                                      <?php echo str_replace(",","</label><label><img src='_STATIC_/2015/image/yydai/insurance/ico_right.png'>",$detail["insurance_type"]);?>
                                  </label>              
                                </p>               
                <span class="s_total" style="text-align: right;">
	                 总价：<font class="total_money">￥{$detail["force_money"]+$detail['business_money']*$detail['bus_discount']}</font><br>
                         <b>(<gt name="detail.business_money" value="0">商业险<label class="business_money">{$detail['business_money']*$detail['bus_discount']}</label></gt>
                             <gt name="detail.force_money" value="0">+交强险<label class="force_money">{$detail.force_money}</label></gt>
                             )</b>
	               
                </span>		
			</section>
		</section>
	</section>	
</section>
<section class="bgf m10">
	<section class="order-con">
		<section class="bot_box">
                    <label  class="payment" id="stages_none"><input type="radio" name="pay_type" value="1" <eq name='detail.eqian_quane' value='1'>checked</eq>  class='pay-type-radio pay-type1'> 全额付款 <font>(车险金额将从您的银行卡扣除)</font></label>
		</section>
	</section>
</section>
<eq name="detail.is_eqian" value="0">
<section class="xy_box sq">
    <label class="xy_sq"><input type="checkbox" id="xy_sq" pay_type='1'> 车辆保险授权协议</label>
</section>
</eq>
<section class="bgf m10">
    <section class="order-con">
            <section class="bot_box">
                <label  class="payment" id="stages_btn"><input type="radio" name="pay_type" value="2"  <eq name='detail.eqian_fenqi' value='1'>checked</eq>  class='pay-type-radio pay-type2'> 分期付款 <font>(车险金额将从您的银行卡扣除)</font></label>
            </section>
    </section>
</section>

<section class="bgf" id="stages_box">
<section id="stages_tit" style="line-height:1rem;">
	<section class="order-con">		
		<font>首付<b>(交强险全额 + 商业险 * 30%)</b></font><font style="float: right;">{$instalmentList.0.initial_money}元</font>
	</section>
</section>
	<section class="order-con">		
		<ul>
                    <foreach name="instalmentList" item="vo" key="key">
                        <li><font>{$vo.back_time}</font><font>{$vo.pay_money}元</font></li>
                    </foreach>
		</ul>
	</section>
</section>
<eq name="detail.is_eqian" value="0">
<section class="xy_box fq">
    <label class="xy_fq"><input type="checkbox" id="xy_fq" pay_type='2'> 车辆保险分期授权协议</label>
</section>
</eq>
<section class="m10">
	<section class="order-con df">
		<input type="text" name="sms_code" placeholder="请输入短信验证码" class="stages_yzm inp_box"><button  class="s_btn_yzm inp_box btn_byzm" >获 取 验 证 码</button>
	</section>
</section>
<input type="hidden" name="_payallot_mobile_" value="{$_payallot_mobile_}"/>  
<input type="hidden" name="order_id" value="{$detail.id}"/>
<button class="btn_sub add-edit-insurance-order">立 即 支 付</button>
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
var myurl = "/allot/pay";
$(".btn_sub").click(function(){
    if(!$("input[name='sms_code']").val()){
        alert('请输入验证码');
        return false;
    }
    
    var data = {};
    $(data).attr('sms_code',$("input[name='sms_code']").val());
    $(data).attr("pay_type",$("input[name='pay_type']:checked").val());
    $(data).attr("paySub",1);
    $(data).attr("order_id",$("input[name='order_id']").val());
    $(this).attr("disabled",true).css({"background":"#ddd"});
    $.post('/allot/pay',data,function(F){
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
               "_payallot_mobile_":"付款标识"
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
       $(data).attr("pay_type",$("input[name='pay_type']:checked").val());
       $(data).attr("order_id",$("input[name='order_id']").val());
       //alert($("input[name='pay_type']:checked").val());exit;
       if (is_check == 0)return false;
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer()', 1000);
       $.post('/allot/paySmsCode',data,function(F){
            var F = eval(F);
             if(F.status==0){
                alert(F.info);
                smsbutton.removeAttr("disabled").css({"background":"#63abe8"}).html("获取验证码");
                clearInterval(sendtimmer);
                if(F.info=="页面已失效，请刷新页面"){
                    location.href="/allot/pay?id={$_GET['id']}";
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

//获取验证码
$("#eqian-sms-btn").click(function(){
    if($(this).attr('disabled'))return false;
    var data = {};
       $(data).attr("pay_type",$("input[name='pay_type']:checked").val());
       $(data).attr("order_id",$("input[name='order_id']").val());
       //alert($("input[name='pay_type']:checked").val());exit;
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer1()', 1000);
       $.post('/allot/eqianSms',data,function(F){
            var F = eval(F);
             if(F.status==0){
                alert(F.info);
                $("#eqian-sms-btn").removeAttr("disabled").css({"background":"#63abe8"}).html("获取验证码");
                clearInterval(sendtimmer);
             }else{
                 alert(F.info);
                 
            }
         },'json');
     });
var showTimemer1 = function() {
    if (sendsecond >=0) {
        $("#eqian-sms-btn").html('重新发送( ' + sendsecond + ')');
        //smsbutton.css("color","#fff");
        sendsecond -= 1;
    } else {
        $("#eqian-sms-btn").html('获取验证码');
        clearInterval(sendtimmer);
        sendtimmer = null;
        sendsecond = 60;
        $("#eqian-sms-btn").removeAttr("disabled").css({"background":"#63abe8"});
    }
}

$(".eqian-agree-btn").click(function(){
    if(!$("input[name='eqian_smscode']").val()){
        alert('请输入验证码');
        return false;
    }
    
    var data = {};
    $(data).attr('eqian_smscode',$("input[name='eqian_smscode']").val());
    $(data).attr("pay_type",$("input[name='pay_type']:checked").val());
    $(data).attr("paySub",1);
    $(data).attr("order_id",$("input[name='order_id']").val());
    $(this).attr("disabled",true).css({"background":"#ddd"});
    $.post('/allot/eqianSubmit',data,function(F){
         var F = eval(F);
          $(".eqian-agree-btn").removeAttr("disabled").css({"background":"#63abe8"});
          if(F.status==0){
             alert(F.info);
             //$(".pay-notice-error").show();
          }else{
              alert(F.info);
              window.location.reload(true);
              $(".tk_bd,.tk_xy").hide();
              window.location.reload(true);
              //$(".pay-notice-success").show();
              //location.href="/allot/index";
         }
      },'json');
});

$("input:radio[name='pay_type']").click(function(){
    var quan = '{$detail.eqian_quane}';
    var fen = '{$detail.eqian_fenqi}';
    if(quan==1 && fen==0){
        $("input:radio[name='pay_type']").removeAttr("checked");
        $("input:radio[name='pay_type'][value=1]").attr("checked",true);  
    }
    if(quan==0 && fen==1){
        $("input:radio[name='pay_type']").removeAttr("checked");
        $("input:radio[name='pay_type'][value=2]").attr("checked",true);  
    }
    
});

</script>
</html>
{__NOLAYOUT__}