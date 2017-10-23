<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <title>{$pageseo.title}</title>
    <style type="text/css">
        .mui-content{padding-top:40px; width: 96%; margin:0 auto; overflow: hidden; height: auto; font-family: "微软雅黑";}
        .ts_title{font-size: 18px; color: #000; line-height: 40px;}
        .ts_time{font-size: 12px; color: #999; line-height: 20px;}
        .ts_con{font-size: 12px; color: #000;}
    </style>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");                
            }else{
                $(".mui-content").css("padding-top","0px");
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
</head>
<body style="background: #fff;">
<header class="headers" style="display: none;">
    <a href="/index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">详细信息</h1>      
</header>
<div class="mui-content">
     <p class="ts_title">{$systemlist.title}</p>
     <p class="ts_time">{$systemlist.timeadd }</p>
     <div class="ts_con">{$systemlist.content}</div>
</div>
</body>
</html>  
{__NOLAYOUT__} 