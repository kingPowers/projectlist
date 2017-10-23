<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta content="telephone=no,email=no" name="format-detection">
		<meta content="yes" name="apple-touch-fullscreen">
		<meta content="yes" name="apple-mobile-web-app-capable">
		<meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<title>{$pageseo.title}</title>
		<link rel="stylesheet" href="_STATIC_/2015/member/css/reset.css" />
                <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
                <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
                <script type="text/javascript" src="_STATIC_/2015/member/js/reset.js"></script>
	</head>
        <body onload="ft()" bgcolor="#efefef">
            <div class="maxDiv"><!--大盒子-->
                <div class="headers"><!--头-->
                    <div class="rd">
                        <img src="_STATIC_/2015/member/image/recoverpwd/return.png" class="retrunImg"/>
                       <span class="fhwz"><a href="/member/recoverpwd" style="color:white;text-decoration:none;">返回</a></span>
                        <span class="zxwx">找回密码</span>
                    </div>
                </div>
                <div class="centerDiv"><!--中-->
                     <div class="center1"></div>
                     <div class="center2">
                     	<form action="reset" method="post" class="recover_reset_form">
                          <span class="phone">新 密 码</span>
                          <input name='setpwd' type="password" placeholder="请输入新密码"  class="input size" />
                          <img src="_STATIC_/2015/member/image/recoverpwd/solid.png" class="solidImg">
                          <span class="phone2">确认密码</span>
                          <input name='resetpwd' type="password"  placeholder="请再次输入新密码" class="input2 size" />
                          <input name="mobile" type="hidden" value="{$Think.request.mobile}"/>
                          <input name="recover_access_token" type="hidden" value="{$Think.request.recover_access_token}"/>
                         </form>
                     </div>
                     <div class="center3">
                          <img src="_STATIC_/2015/member/image/recoverpwd/confirm.png" class="confirmImg">
                     </div>

                </div>
            </div>
            
            <!-- 弹出层 黑色-->
             <div class="hbgDiv"></div>
<!--             弹出层内容-->
             <div class="centerinfomatinDiv">
                 <img src="_STATIC_/2015/member/image/recoverpwd/treset.png" alt="" class="tresetImg"/>
                 <p class="tswzinfo size">提示</p>
<!--                 <p class="infoconter tssize">{$error}</p>-->
                  <p class="infoconter tssize">密码已修改成功，请重新登录</p>
                 <a href="javascript:void(0)"  class='jump_url'><input type="button" value="确定" class="surebtn size"/></a>
                 
             </div>

        <script type="text/javascript">
        	function showWindow(message,url){
            	$('.infoconter').html(message);
        		$(".hbgDiv").css("display","block");
                $(".centerinfomatinDiv").css("display","block");
                url!=""?$('.jump_url').attr('href',url):'';
            }
            $(".confirmImg").click(function(){
               var setpwd = $("input[name='setpwd']").val();
               var resetpwd = $("input[name='resetpwd']").val();
               if(setpwd==''){
            	   showWindow('请输入新的密码',"javascript:void(0)");
            	   return false;
               }
               if(resetpwd==''){
            	   showWindow('请输入确认密码',"javascript:void(0)");
            	   return false;
               }
               if(setpwd!=resetpwd){
            	   showWindow('两次密码输入不一致',"javascript:void(0)");
            	   return false;
               }
               
               $(".recover_reset_form").submit();
            });
            $('.surebtn').click(function(){
            	$(".hbgDiv").css("display","none");
                $(".centerinfomatinDiv").css("display","none");
            });

            $(function(){
				var error = '{$error}';
				var status = '{$status}';
				var url = 1==status?"/member/login/":(error=='验证码失效，请重新获取手机验证码'?"/member/recoverpwd":"javascript:void(0)");
				if(error!='')showWindow(error,url);
            });
        </script>
	</body>
</html>   
{__NOLAYOUT__}