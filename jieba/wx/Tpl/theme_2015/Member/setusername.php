<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/setuname.css" />
    <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/setuname.js"></script>
</head>
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
            <a href="/member/accountCenter" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz">返回</span></a>
            <span class="zxwx">修改用户名</span>
        </div>
    </div>
    <div class="centerDiv"><!--中-->
        <div class="center1">
           <form action="" method="post" class="">
                <img src="_STATIC_/2015/member/image/account/idea.png" class="ideaImg">
                <span class="phone">用户名为2-8位汉字、字母、数字结合</span>
            </form>
        </div>
        <div class="center2">
            <form action="" method="post" class="">
                <span class="phone2">用户名</span>
                <input value="{$member_info.username}" autocomplete="off"  style="color:black"  name='username' class="input size" />
            </form>
        </div>
        <div class="center3">
            <img src="_STATIC_/2015/member/image/account/icon.png" class="setunameImg">
        </div>

    </div>
</div>
<div class="secondDiv"></div><!--弹出灰色弹窗-->
<div class="thirdDiv">
    <p class="notice tsize">用户名修改成功</p>
    <img src="_STATIC_/2015/member/image/account/border.png" class="borderImg">
    <a href="javascript:void(0)"  class='jump_url'>
    	<input type="button" value="确定" class="surebtn ssize"/>
    </a>
</div><!--弹出内容-->
 <script type="text/javascript">
   function showWindow(message,url){
     $('.notice').html(message);
     $(".secondDiv").css("display","block");
     $(".thirdDiv").css("display","block");
     $('.jump_url').attr('href',url);
   }

   $('input[name="username"]').focus(function(){
	   var username = '{$member_info.username}';
	   if($(this).val()==username)
		   $(this).val('');
	});
   $('input[name="username"]').blur(function(){
	   var username = '{$member_info.username}';
	   if($(this).val()=='')
		   $(this).val(username);
	});
	
    $(".setunameImg").click(function(){
               var username = $("input[name='username']").val();
               if(username==''){
            	   showWindow('用户名不能为空',"javascript:void(0)");
                }
               if(username!=''){
            	   $.ajax({
          				'type':'post',
          				'dataType':'json',
          				'url':"/member/setUsername",
          				"data":{'username':username},
          				success:function(json){
          					if(json.status==1)
        						showWindow(json.info,"/member/account/");
            				if(json.status==0)
        						showWindow(json.info,"javascript:void(0);");

          				}
          				
          			});
            	   return false;
               }
               
            });
   $('.surebtn').click(function(){
     $(".secondDiv").css("display","none");
     $(".thirdDiv").css("display","none");
   });
 </script>
</body>
</html>   
{__NOLAYOUT__}