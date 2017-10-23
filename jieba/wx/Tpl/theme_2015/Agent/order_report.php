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
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
</head>
<body style="background: #efefef;">
<header>
	<a href="/agent/my_unlocked_order" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>举报订单</h1>
</header>
<form id="reportForm">
<section class="report_div" style="margin-top: 40px;">
	<textarea name="content" id="content" placeholder="请输入您的举报内容" ></textarea>
	<input type="file" name="pic1" id="pic1" class="inp_up"><img src="_STATIC_/2015/image/yydai/agent/add_photo.png" class="btn_up">
	<input type="file" name="pic2" id="pic2" class="inp_up"><img src="_STATIC_/2015/image/yydai/agent/add_photo.png" class="btn_up">
	<input type="file" name="pic3" id="pic3" class="inp_up"><img src="_STATIC_/2015/image/yydai/agent/add_photo.png" class="btn_up">
</section>
<section class="w94" style="padding-top: 40px;">
    <input type="hidden" name="order_id" value="{$order_id}">
    <input type="hidden" name="transfer_id" value="{$transfer_id}">
    <input type="hidden" name="_report_session_" value="{$_report_session_}">
	<input type="button" class="btn_sub" id="agent_sub" value="确认举报">
</section>
</form>
</body>
</html>
<script type="text/javascript">
$(function(){
	$('#agent_sub').removeAttr("disabled").css({"background":"#63abe8"});
   $("#agent_sub").click(function(){
   	   if($("#content").val() == ""){alert("请输入您的举报内容");return false;} 
   	   var formData = new FormData($("#reportForm")[0]);
       $order_id = $("input[name='order_id']").val();
       $transfer_id = $("input[name='transfer_id']").val();
   	   //console.log(formData);
   	   //alert(typeof(formData))
      $('#agent_sub').attr("disabled",true).css({"background":"#ddd"});      
   	   $.ajax({  
                  url: '/agent/order_report' ,  
                  type: 'POST',  
                  data: formData,  
                  async: false,  
                  cache: false,  
                  contentType: false,  
                  processData: false,  
                  success: function (F) {  
                      var F = eval("("+F+")");
                      $('#agent_sub').removeAttr("disabled");
                      if(F.status == 1){
                      	alert(F.info);
                      	window.location.href = "/agent/report_info/order_id/"+$order_id+"/transfer_id/"+$transfer_id;
                      }else{
                      	alert(F.info);
                      	$('#agent_sub').css({"background":"#63abe8"});
                      }                      
                  },  
                  error: function (F) {
                	  $('#agent_sub').css({"background":"#63abe8"});  
                      var F = eval("("+F+")");
                        alert(F.info);
                          
                  }  
            });  
   })
   $("input[type='file']").change(function(){
        $id = $(this).attr("id");
       var file=document.getElementById($id);
       var src = window.URL.createObjectURL(file.files[0]);                                    
       $(this).next(".btn_up").attr("src",src);
   })
})
</script>
{__NOLAYOUT__}