<!DOCTYPE html>
<html>
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
    <script type="text/javascript">
    
    function get_agent_status(){
      var is_get = 1;
      $.post("/agent/get_agent_status",{'is_get':is_get},function(F){
          if(F.status == 1){
             //alert(F.data);
             var R = eval("(" + F.data + ")");
             //alert(R.state + "-" + R.message + "-" + R.url + "-" + R.confirm);
             if(R.state){
                 $(".tkjin_bg p").html("");
                 $(".tkjin_bg p").html(R.message);
                 $("#tl_close").html("");
                 $("#tl_close").html(R.confirm);
                 $("#tl_close").attr("url",R.url);
                 $(".tkgrayBg").css("display","block");
             }
          }else{
               alert(F.info)
          }
      },'json')
    }
    $(function () {
      $("#tl_close").click(function(){
        window.location.href = $(this).attr("url");
      })
     $("#tl_cancel").click(function(){
        window.location.href = '/member/account';
      })
      get_agent_status();
      //confirm("确认"); 
    	$("#choose_city").click(function(){
			$(".choose_city,.tkLayBg").show();
			$(".tkLayBg").height($(document).height());
			$("#tk_cancel").click(function(){
				$(".choose_city,.tkLayBg").hide();
			})
		}) 
         //选择省、市
		$("select[name='change_pro']").change(function(){
			var data = {};
			$(data).attr('province_code',$(this).find("option:selected").attr('id'));
			$.post("/agent/get_citys.html",data,function(F){
               if(F.status == 1){
               	  F = eval(F.data);
               	  $(".change_city").html("<option >请选择城市</option>");
                  for(var i=0;i<F.length;i++){
                  	//alert(F[i]['id']);
                  	var city_option = "<option id='" + F[i]['city_code'] + "'>" + F[i]['city_name'] + "</option>";
                  	$(".change_city").append(city_option);
                  }
                  
               }else{
               	  alert(F.info);
               }
			},'json')
		})
		var city_name = '';
		$(".change_city").change(function(){
            city_name = $(this).find("option:selected").val();
		})
		//点击确定省份城市内容
		$("#tk_ture").click(function(){	
			var province_name = $("select[name='change_pro']").find("option:selected").val();
			//alert($province_name+" "+city_name);			
			$(".choose_city,.tkLayBg").hide();
            $("#chooseCity").val(province_name+"-"+city_name);
		})
		//点击提交表单内容
		 $('#agent_sub').click(function(){
		  $full_city = $("#chooseCity").val();
   	      // $province = $("select[name='change_pro']").find("option:selected").val();
   	       $province = $full_city.split("-")['0'];
   	       $city = $full_city.split("-")['1'];
   	       $company_name = $("#com_name").val();
   	       $comany_full_name = $("#com_full_name").val();
   	       $verify_code = $("#input_yzm").val();
   	       if($province && $city){
               var P = check_form('#agentForm');
               if(P){
                  var data = {};
                  $(data).attr("province",$province);
                  $(data).attr("city",$city);
                  $(data).attr("company_name",$company_name);
                  $(data).attr("comany_full_name",$comany_full_name);
                  $(data).attr("verify_code",$verify_code);
                  $('#agent_sub').attr("disabled","disabled");
                  $.post("/agent/submit_authentication",data,function(F){    
                     if(F.status == 0){
                         $('#agent_sub').removeAttr("disabled");
                         alert(F.info);
                     }else{
                     	alert(F.info);
                     	window.location.href="/Agent/agent_account";
                     }
                  },'json')
               }else{
                  return false;
               }
   	       }else{
              alert("请选择具体所在城市");
   	       }
        })
    }) 
    </script>
    <title>{$pageseo.title}</title>   
</head>
<body style="background: #efefef;">
	<header>
		<a href="/member/account" class="btn_back">
	        <img src="_STATIC_/2015/member/image/register/return.png">
	        <font class="fl">返回</font>
	    </a>
	    <h1>经纪人认证</h1>    
	</header>
<form id="agentForm" class="agent" method="post" >
	<section class="tkLayBg" style="display: none;">
		<section class="choose_city" style="display: none;">
			<section class="tit_city">
				<img src="_STATIC_/2015/image/yydai/index/ico_dw.png">
				<span>选择城市</span>
			</section>
			<section class="con_city">			
					<select name="change_pro" id="change_pro">
						<option>请选择省</option>
						<foreach name="provinces" item="vo">
						  <option id="{$vo['province_code']}">{$vo['province_name']}</option>
						</foreach>
					</select>
					<select style="margin-left: 4%;" class="change_city">
						<option >请选择市</option>
					</select>			
			</section>
			<section class="close_city">
				<a class="fl" style="color: #909090;" id="tk_cancel">取消</a>
				<a class="fl" style="color: #5495e6; border-left: 1px solid #e3e3e3;" id="tk_ture" >确定</a>
			</section>		
		</section>
  </section>
 <!--金账户验证-->
  <section class="tkgrayBg" style="display: none; height: 100%;">
    <section class="tkjin_bg">        
        <h2>提示</h2>
        <p>请您开通金账户</p>
        <section class="fl btn_tkjin" id="tl_cancel">取消</section>  
        <section class="fl btn_tkjin" id="tl_close" url =" ">确认</section>        
    </section>
  </section>

	<section class="mui-table-view">
		<section class="auth-list-li acity">
			<font>所在城市</font><span id="choose_city"><input style="background-color: #fff" type="text"  disabled="disabled" id="chooseCity" placeholder="请选择所在城市"></span>
		</section>
		<section class="auth-list-li">
			<font>用户姓名</font><span><input style="background-color: #fff" type="text" value="{$names}" disabled="disabled"></span>
		</section>
		<section class="auth-list-li">
			<font>身份证号</font><span><input style="background-color: #fff" type="text" value="{$certiNumber}" disabled="disabled"></span>
		</section>
		<section class="auth-list-li">
			<font>公司简称</font><span><input jschecktitle="公司简称" jscheckrule="null=0;" type="text" id="com_name" placeholder="请输入您所在公司的简称"></span>
		</section>
		<section class="auth-list-li">
			<font>公司全称</font><span><input jschecktitle="公司全称" jscheckrule="null=0" type="text" id="com_full_name" placeholder="请输入您所在公司的全称"></span>
		</section>
		<section class="auth-list-li">
			<font>手机号码</font><p>{$mobile}</p>
		</section>
		<section class="auth-list-li">
		    <input type="hidden" name="_agent_mobile_" value="{$_agent_mobile_}">
        <input type="hidden" name="agent_status" value="{$agent_status}">
			<font>验证码</font><span><input type="text" jschecktitle="手机验证码" jscheckrule="null=0" placeholder="请输入手机验证码" id="input_yzm"><a><input type="button" name="" id="apply_code"  value="获取验证码" ></a></span>
		</section>		
	</section>
	<section class="w94">
		<input type="button" class="btn_sub" id="agent_sub" value="立即认证">
	</section>
</form>
</body>
</html>
<script type="text/javascript">
 //申请验证码
 var sendsecond = 60;
 var smsbutton = $('.auth-list-li a input');
 var sendtimmer = null;
 $(function(){
   $('#apply_code').click(function () {               
        if($(this).attr('disabled'))return false;
        var myThis =$(this);       
        var data = {};
        $(data).attr("_agent_mobile_",$("input[name='_agent_mobile_']").val());
        $.post("/agent/sen_authentication_sms",data,function(F){
        	alert(F.info);
        	if(F.status == 1){
        	  myThis.attr("disabled",true).css({"background":"#ddd","color":"#666"});
              sendtimmer = setInterval('showTimemer()', 1000);
        	}else{
        		if(F.info = "页面已失效，请刷新页面") window.location.href = F.data;
        	}           
        },'json')

    }); 
}) 
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

{__NOLAYOUT__}