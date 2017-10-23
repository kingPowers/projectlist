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
        <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $("#headers").css("display","block");
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
        .join {position: absolute; width: 30%; height:1.3rem; font-size: 0.4rem; background:#ffff00; color: #000; left: 50%; margin:-15% 0 0 -15%; border-radius: 5px; text-align: center; line-height:1.3rem; display: block; box-shadow: 0px 0px  2px 1px #fe6c31}
        .join_again{position: absolute; width: 20%; height:1.3rem; font-size: 0.4rem; background:#ffff00; color: #000; left: 50%; margin-left: 0%; text-align: center; line-height:1.3rem; display: block; box-shadow: 0px 0px  2px 1px #fe6c31; bottom: 1%; border-radius: 10px;}
    </style>
</head>
<body bgcolor="#fe6c31">
<header id="headers" style="display: none;">
    <a href="/credit/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>信用贷</h1>
    <a style="right:8px;" href='/credit/help' class="btn_help"><img src="_STATIC_/2015/image/yydai/index/ico_help.png"></a>
</header>
  
<!--头结束-->
<div class="con_div">
    <img src="_STATIC_/2015/activity/image/money/ac_01.jpg"/>
    <img src="_STATIC_/2015/activity/image/money/ac_02.jpg"/>
       <a href="../Carinsurance/apply" class="join">点击参与</a>
    <img src="_STATIC_/2015/activity/image/money/ac_03.jpg"/>
    <img src="_STATIC_/2015/activity/image/money/ac_04.jpg"/>
    <img src="_STATIC_/2015/activity/image/money/ac_05.jpg"/>
       <a href="../Carinsurance/apply" class="join_again">点击参与</a>
</div>
</body>
<script type="text/javascript">
    /*$(".now_apply").click(function(){
        window.location.href="/Credit/index?jieba=xjd&token=";
    });*/
</script>
</html>
{__NOLAYOUT__}