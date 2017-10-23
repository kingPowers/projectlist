<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">  
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"> 
            $(function(){            
                if(isWeixin()) {
                    $(".headers").css("display","block");                
                }
                else{
                    $(".centerDiv").css("margin-top","10px");
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
            .centerDiv{width: 96%; margin: 50px auto 0; height: auto; overflow: hidden;}
            .centerDiv img{width: 33%; float: left;}
        </style>    
</head>
<body>
    <header class="headers" style="display: none;">
        <a href="javascript:history.back();" class="btn_back">
            <img src="_STATIC_/2015/member/image/register/return.png">
            <font class="fl">返回</font>
        </a>
        <h1>合作伙伴</h1>    
    </header>
        <div class="centerDiv">
            <img src="_STATIC_/2015/member/image/about/picture7.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture8.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture3.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture4.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture5.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture1.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture6.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture2.jpg"/>
            <img src="_STATIC_/2015/member/image/about/picture9.jpg"/>
        </div>
    </div>
</body>
</html>   
{__NOLAYOUT__}