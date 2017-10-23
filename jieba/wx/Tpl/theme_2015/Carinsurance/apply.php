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
    <script type="text/javascript">var get_store = "{:U('/Carinsurance/getStore')}";</script>
    <script type="text/javascript" src="_STATIC_/2015/carinsurance/apply.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
    <style type="text/css">
	.search-div{border:1px solid #bbb;width:70%;margin-top: 0px;position: absolute;top:35px;left: 20%;z-index: 100;background: #fff;border-radius: 5px;font-size: 15px;display: none;}
	.search-div p{cursor: pointer;margin: 0 auto;padding: 5px 10px;}
	.search-div p:hover{background: #ccc}
	.close_search{display: inline-block;float: right;font-size: 5px;color: #666;padding-right: 10px;}
    </style>
    <script type="text/javascript">
       $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }
            else{
                $(".top-banner").css("margin-top","0");
            }
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
	    $(function(){    	
	    	$(".show").click(function(){
				$(".show").attr("id","blue-bg");
				$(".hide").removeAttr("id");
				$(".u_none").hide();
				$(".u_show").show();
			})
			$(".hide").click(function(){
				$(".hide").attr("id","blue-bg");
				$(".show").removeAttr("id");				
				$(".u_none").show();
				$(".u_show").hide();
			})
	    })
    function preview(file,id)  
    {
        if(!file.value=="")
        {
            var prevDiv = document.getElementById('btn_up');
            if (file.files && file.files[0])
            {
                var reader = new FileReader();
                reader.onload = function(evt){
                    //prevDiv.innerHTML = '<img src="' + evt.target.result + '" style="width:80px;height:80px;"/>';
                    $("#"+id).attr("src",evt.target.result).css({"width":"100px"});
                }
            }
         reader.readAsDataURL(file.files[0]);  
        }
        else
        {  
            prevDiv.innerHTML = '<div class="imgs" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';  
        }  
    }        
    </script>
</head>
<body style="background: #efefef;">
<header class="headers" style="display: none;">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>我的车险</h1>    
</header>
<section class="top-banner proh">
	<img src="_STATIC_/2015/image/yydai/insurance/banner.jpg">
</section>
<section class="middle-div proh bgf">
	<section class="button-div">
		<a id="blue-bg" class="show" car_type="2">个人车</a>
		<a class="hide" car_type="1">公司车</a>
	</section>	
</section>

    

<section class="con">
    <form action="apply.php" method="post" enctype="multipart/form-data" class="personal_form">
        <input type="hidden" name="car_type" value="2"/>
        <input type="hidden" name="_applyInsurance_mobile_" value="{$_applyInsurance_mobile_}"/>  
	<ul class="proh bgf u_show">
<!--		<li>
			<font>申请门店:</font>
			<input type="text" name="store_name" placeholder="请输入您的申请门店(若无门店则填“无”)" style="width: 72%;">
			<div class="search-div"></div>
		</li>-->
                <li>
			<font style="width:100px;">推荐人手机号:</font>
			<input type="text"  name="recMobile"  placeholder="请输入您推荐人手机号"/>
		</li>    
		<li>
			<font>真实姓名:</font>
			<input type="text" disabled="" value="{$apply_info['names']}">
		</li>
		<li>
			<font>身份证号:</font>
			<input type="text" disabled="" value="{$apply_info['certiNumber']}">
		</li>
		<section class="in_class">
			<font>申请险种</font>
			<p>
          <foreach name="apply_info.insurance" item="vo">
              <eq name="vo" value="50万">(</eq>
              <label <?php if(in_array($vo,["50万","100万"])) echo "style='font-size:0.2rem'";?>><input type="checkbox" name="insurance_type[]" value="{$vo}"> {$vo}</label>
              <eq name="vo" value="100万">)</eq>
          </foreach>
			</p>
		</section>
		<section class="add_list">
			<font>联系地址:</font>
                        <input type="hidden" name="address">
			<input type="text" name="province">省<input type="text" name="city">市<input type="text" name="area">区/县
			<textarea rows="2" placeholder="请填写详细地址" name="address_detail"></textarea>
		</section>
		<li>
			<font>验证号码:</font>
			<input type="text" placeholder="请输入手机验证码" name="sms_code">
			<a class="btn_byzm" style="cursor: point;">获取验证码</a>
		</li>
		<li id="bg_sfz">
			<font>身份证</font>		
			<img src="_STATIC_/2015/image/yydai/insurance/ico_photo.png" class="btn_up" id="certinumber-pic">
			<input type="file" class="img_up" name="certinumber_pic" onchange="preview(this,'certinumber-pic')">
			<span class="case">
				<img src="_STATIC_/2015/image/yydai/insurance/ico_sfz.jpg" >
				<p>请上传身份证正面照片</p>
			</span>
		</li>
		<li id="bg_sfz">
			<font>行驶证</font>
			<img src="_STATIC_/2015/image/yydai/insurance/ico_photo.png" class="btn_up" id="drive-license-pic">
                        <input type="file" class="img_up" name="drive_license_pic" onchange="preview(this,'drive-license-pic')">
			<span class="case">
				<img src="_STATIC_/2015/image/yydai/insurance/ico_jsz.jpg" >
				<p>请上传行驶证正面照片</p>
			</span>
		</li>
	</ul>
    </form>
    <form action="apply.php" method="post" enctype="multipart/form-data"  class="company_form">
        <input type="hidden" name="car_type" value="1"/>
        <input type="hidden" name="_applyInsurance_mobile_" value="{$_applyInsurance_mobile_}"/>  
	<ul class="proh bgf u_none">
		<!--		<li>
			<font>申请门店:</font>
			<input type="text" name="store_name" placeholder="请输入您的申请门店(若无门店则填“无”)" style="width: 72%;">
			<div class="search-div"></div>
		</li>-->
                <li>
			<font style="width:100px;">推荐人手机号:</font>
			<input type="text"  name="recMobile"  placeholder="请输入您推荐人手机号"/>
		</li>  
		<section class="in_class">
			<font>申请险种</font>
			<p>
                <foreach name="apply_info.insurance" item="vo">
                    <eq name="vo" value="50万">(</eq>
                    <label <?php if(in_array($vo,["50万","100万"])) echo "style='font-size:0.2rem'";?>><input type="checkbox" name="insurance_type[]" value="{$vo}"> {$vo}</label>
                    <eq name="vo" value="100万">)</eq>
                </foreach>
			</p>
		</section>
    <section class="add_list">
      <font>联系地址:</font>
      <input type="hidden" name="address">
      <input type="text" name="province">省<input type="text" name="city">市<input type="text" name="area">区/县
      <textarea rows="2" placeholder="请填写详细地址" name="address_detail"></textarea>
    </section>
		<li>
			<font>验证号码:</font>
			<input type="text" placeholder="请输入手机验证码" name="sms_code">
			<a class="btn_byzm" style="cursor: point;">获取验证码</a>
		</li>
		<li id="bg_sfz">
			<font>营业证</font>		
			<img src="_STATIC_/2015/image/yydai/insurance/ico_photo.png" class="btn_up" id="service-license-pic">
			<input type="file" class="img_up" name="service_license_pic" onchange="preview(this,'service-license-pic')">
			<span class="case">
				<img src="_STATIC_/2015/image/yydai/insurance/ico_yyz.jpg" >
				<p>请上传营业证正面照片</p>
			</span>
		</li>
		<li id="bg_sfz">
			<font>行驶证</font>
			<img src="_STATIC_/2015/image/yydai/insurance/ico_photo.png" class="btn_up" id="drive-license-pic1">
			<input type="file" class="img_up" name="drive_license_pic" onchange="preview(this,'drive-license-pic1')">
			<span class="case">
				<img src="_STATIC_/2015/image/yydai/insurance/ico_jsz.jpg" >
				<p>请上传行驶证正面照片</p>
			</span>
		</li>

	</ul>
    </form>
</section>
<p class="remarks">注：提供有效证件,仅平台方可见，以便保险资格审查。同一手机号码，每天最多申请不超过两次。</p>
<a class="btn_sub add-edit-insurance-order">立 即 提 交</a>
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

$(function () {
//  $("input[value='交强险']").click(function () {
//    var is_checked = $(this).attr("checked");
//    var car_node = $(this).parents(".proh");
//    if (!is_checked) {
//      car_node.find(".add_list").hide().find("input").val("");
//      car_node.find("textarea[name='adress_detail']").val("");
//    } else {
//      car_node.find(".add_list").show();
//    }
//  })
})
var sendtimmer = null;
var sendsecond = 60;
var smsbutton = $('.btn_byzm');

//获取验证码
smsbutton.click(function(){
    if($(this).attr('disabled'))return false;
    var data = {};
    var input_name = {
               //'store_name': '申请门店',
               "_applyInsurance_mobile_":"绑定标识"
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
       $(this).attr("disabled",true).css({"background":"#ddd"});
       sendtimmer = setInterval('showTimemer()', 1000);
       $.post('applySms',data,function(F){
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
//整合地址
var addressIntegration = function (formClass)
{
   var province = $("." + formClass).find("input[name='province']").val();
   var city = $("." + formClass).find("input[name='city']").val();
   //if(city!="")city+="市";
   var area = $("." + formClass).find("input[name='area']").val();
   //if(area!="")area+="区";
   var address_detail = $("." + formClass).find("textarea[name='address_detail']").val();
   var address_str = province  + city  + area  + address_detail;
   $("." + formClass).find("input[name='address']").val(address_str);
}


$(".add-edit-insurance-order").click(function(){
                var _this = this;
                var car_type = $("#blue-bg").attr("car_type");
                if(car_type==2){
                    var formClass = "personal_form";
                }else{
                    var formClass = "company_form";
                }
                addressIntegration(formClass); 
                if($(_this).attr("padding")==1)return false;
		var options = {
                url: "/Carinsurance/apply",
                type: "post",
                dataType: "json",
                beforeSend:function(){
                    $(_this).css({"background":"#ccc"}).attr("padding",1);
                },
                success: function(o) {
                    alert( o.info);
                    $(_this).css({"background":"#5495e6"}).removeAttr("padding");
                    if (o.status == 0) {
                        
                    } else {
                   	location.href="/Carinsurance/myCarInsurance";
                    }
                }
            };
            $("."+formClass).ajaxSubmit(options);
  });

</script>
</html>
{__NOLAYOUT__}