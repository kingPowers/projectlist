<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/setpwd.css" />
    <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/setpwd.js"></script>
</head>
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
           <a href="/member/accountCenter" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz">返回</span>
            </a>
            <span class="zxwx">修改密码</span>
        </div>
    </div>
    <div class="centerDiv"><!--中-->
        <div class="center1"></div>
        <div class="center2">
            <form action="" method="post" class="">
                <span class="phone" >原始密码</span>
                <input type="password" placeholder="请输入原始密码" name='old_pwd' class="input size" />
                <img src="_STATIC_/2015/member/image/register/solid.png" class="solidImg">
                <span class="phone2">新 密 码</span>
                <input  type="password" placeholder="请输入新密码" name='setpwd' class="input2 size" />
                <img src="_STATIC_/2015/member/image/register/solid.png" class="solid2Img">
                <span class="phone3">确认密码</span>
                <input  type="password" placeholder="请输入确认密码" name='resetpwd' class="input3 size" />
            </form>
        </div>
        <div class="center3">
            <img src="_STATIC_/2015/member/image/account/icon.png" class="iconImg">
        </div>

    </div>
</div>
<div class="secondDiv"></div><!--弹出灰色弹窗-->
<div class="thirdDiv">
    <img src="_STATIC_/2015/member/image/account/border.png" class="borderImg">
    <p class="info tsize">两次输入密码不同,请重新输入</p>
    <a href="javascript:void(0)"  class='jump_url'><input type="button" value="确定" class="surebtn ssize"/></a>
</div><!--弹出内容-->
 <script type="text/javascript">
   function showWindow(message,url){
     $('.info').html(message);
     $(".secondDiv").css("display","block");
     $(".thirdDiv").css("display","block");
     $('.jump_url').attr('href',url);
   }
    $(".iconImg").click(function(){
    		   var old_pwd = $("input[name='old_pwd']").val();
               var setpwd = $("input[name='setpwd']").val();
               var resetpwd = $("input[name='resetpwd']").val();
               if(old_pwd=="" || old_pwd=='请输入原始密码'){
            	   showWindow('请输入原始密码',"javascript:void(0)");
            	   return false;
               }
               if(setpwd=="" || setpwd=='请输入新密码'){
            	   showWindow('请输入新密码',"javascript:void(0)");
            	   return false;
               }
               if(resetpwd=="" || resetpwd=='请输入确认密码'){
            	   showWindow('请输入确认密码',"javascript:void(0)");
            	   return false;
               }
               if(setpwd!=resetpwd){
            	   showWindow('两次密码输入不同，请重新输入',"javascript:void(0)");
            	   return false;
               }

               $.ajax({
    				'type':'post',
    				'dataType':'json',
    				'url':"/member/setpwd",
    				"data":{'old_pwd':old_pwd,'new_pwd':setpwd,'renew_pwd':resetpwd},
    				success:function(json){
        				if(json.status==1)
    						showWindow(json.info,"/member/loginout/");
        				if(json.status==0)
    						showWindow(json.info,"javascript:void(0)");
    				},
    				
    			});
               
            });
   $('.surebtn').click(function(){
     $(".secondDiv").css("display","none");
     $(".thirdDiv").css("display","none");
   });
 </script>
</body>
</html>   
{__NOLAYOUT__}