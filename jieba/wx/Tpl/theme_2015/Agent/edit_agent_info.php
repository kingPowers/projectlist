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
    <script type="text/javascript">
      $(function(){
        $("#agent_sub").click(function(event){
             var P = check_form('#editForm');
            if(P){
             var formData = new FormData($( "#editForm" )[0]);
             $this = $(this);
             $this.attr("disabled","disabled");
             $.ajax({  
                  url: '/agent/edit_agent_info' ,  
                  type: 'POST',  
                  data: formData,  
                  async: false,  
                  cache: false,  
                  contentType: false,  
                  processData: false,  
                  success: function (F) {  
                      var F = eval("("+F+")");
                      if(F.status == 1){
                        alert(F.info);window.location.href = "/Agent/agent_account";
                      }else{
                        alert(F.info);
                        $this.removeAttr("disabled");
                      }
                      
                  },  
                  error: function (F) {  
                      var F = eval("("+F+")");
                        alert(F.info); 
                      $this.removeAttr("disabled"); 
                  }  
            });  
           }
         })
        $("input[name='pic_card2']").change(function(event){
            var file=document.getElementById("pic_card2")
                var src = window.URL.createObjectURL(file.files[0]);                                    
                $(".btn_up").attr("src",src);
                //window.URL.revokeObjectURL(src);
        });                          
    })
    </script>
</head>
<body style="background: #efefef;">
<header>
	<a href="/agent/agent_account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>个人信息修改</h1>
</header>
<form id="editForm" method="post" enctype="multipart/form-data" action="/agent/edit_agent_info">
<section class="mui-table-view">
	<section class="auth-list-li">
		<font>昵&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;称</font><span><input style="background-color: #fff" type="text" jschecktitle="用户昵称"  jscheckrule="null=0;maxlencn=4" placeholder="请填写用户昵称"  name="nickname" value="<empty name='agent_info.nickname'>{$agent_info['names']}<else/>{$agent_info['nickname']}</empty>" ></span>
	</section>
	<section class="auth-list-li">
		<font>公司全称</font><span><input name="company_full_name" jschecktitle="公司全称"  jscheckrule="null=0"  type="text"  placeholder="请填写公司全称" value="{$agent_info['company_full_name']}" style="width: 90%;"></span>
	</section>
	<section class="auth-list-li">
		<font>公司简称</font><span><input name="company_name" jschecktitle="公司简称" jscheckrule="null=0;" type="text" placeholder="请填写公司简称" value="{$agent_info['company_name']}"></span>
	</section>
	<section class="auth-list-li">
		<font>手机号码</font><p>{$agent_info['mobile']}</p>
	</section>	
	<section class="auth-list-li">
      <input type="hidden" name="_edit_mobile_" value="{$_edit_mobile_}">
	    <input type="hidden" name="_session_" value="{$_session_}">
		<font>验证号码</font><span><input type="text" jschecktitle="手机验证码" jscheckrule="null=0" placeholder="请输入手机验证码" name="verify_code" id="input_yzm"><a><input type="button" name="" id="apply_code"  value="获取验证码" ></a></span>
	</section>
	<section class="auth-list-li" id="pic">	 
		<font>上传名片</font>
		  <input  type="file" name="pic_card2" id="pic_card2" class="inp_up">
      <empty name="agent_info['pic_card2']"><img src="_STATIC_/2015/member/image/account/add_photo.png" class="btn_up"><else/>
		  <img src="{$agent_info['pic_card2']}" class="btn_up"></empty>
		  <span style="width: 43%; font-size:12px;">上传名片可加V</span>
	</section>		
</section>
<section class="w94">	
	<p>每月可修改一次</p>
	<input type="button" class="btn_sub" id="agent_sub" value="确认提交">
</section>
</form>
</body>
</html>
<script type="text/javascript">
 //获取验证码
 var sendsecond = 60;
 var smsbutton = $('.auth-list-li a input');
 var sendtimmer = null;
 $(function(){
   $('#apply_code').click(function () {               
        if($(this).attr('disabled'))return false;
        var myThis =$(this);       
        var data = {};
        $(data).attr("_edit_mobile_",$("input[name='_edit_mobile_']").val());
        $.post("/agent/send_edit_agent_sms",data,function(F){        	
        	if(F.status == 1){
            alert(F.info);
        	  myThis.attr("disabled",true).css({"background":"#ddd","color":"#666"});
              sendtimmer = setInterval('showTimemer()', 1000);
        	}else{
            alert(F.info);
        		if(F.info == "页面已失效，请刷新页面") window.location.href = F.data;
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