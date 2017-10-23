<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>  
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $("#headers").css("display","block");
            }else{
                $(".con_div").css("margin-top","0px");
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
    </script> 
    <style type="text/css">
        *{margin:0; padding: 0;}
        img{display: block; width: 100%; height: 100%;}
        .con_div{position:relative; width: 100%; margin-top: 40px; overflow: hidden;}
        .now_apply {position: absolute; width: 40%; height: 40px; font-size: 16px; background:#f68224; color: #fff; left: 50%; margin-left: -20%; border-radius: 5px; text-align: center; line-height: 40px; display: block; bottom:1%; }
    </style>
</head>
<body bgcolor="#fdf7df">
<header id="headers" style="display: none;">
    <a href="/credit/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>车友贷</h1>
    <a style="right:8px;" href='/credit/help' class="btn_help"><img src="_STATIC_/2015/image/yydai/index/ico_help.png"></a>
</header>
  
<!--头结束-->
<div class="con_div">
    <img src="_STATIC_/2015/image/yydai/index/xydl/xydl_01.jpg"/>
    <img src="_STATIC_/2015/image/yydai/index/xydl/xydl_02.jpg"/>
    <img src="_STATIC_/2015/image/yydai/index/xydl/xydl_03.jpg"/>
    <img src="_STATIC_/2015/image/yydai/index/xydl/xydl_04.jpg"/>
    <img src="_STATIC_/2015/image/yydai/index/xydl/xydl_05.jpg"/> 
    <a href="/Credit/index?jieba=xjd" class="now_apply">
        立即申请
    </a>     
</div>
</body>
</html>
{__NOLAYOUT__}