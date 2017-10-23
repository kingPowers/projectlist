<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/aboutus.css" />
    <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/aboutus.js" /></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
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
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
            <a href="/member/more" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz">返回</span>
            </a>
            <span class="zxwx">关于我们</span>
        </div>
    </div>
    <div class="centerDiv"><!--中-->
        <div class="center1"></div>
        <div class="center2">
                <a href="/member/jieba" style="text-decoration:none;">
				<div class="user">
				<span class="phone">公司简介</span>
                <img src="_STATIC_/2015/member/image/about/us.png" class="usImg"/>
                <img src="_STATIC_/2015/member/image/account/right.jpg" class="rightImg"/>
                <img src="_STATIC_/2015/member/image/account/solid.png" class="solidImg">
				</div>
				</a>
                <a href="/member/aboutpartner" style="text-decoration:none;">
				<div class="mobile"> 
				<span class="phone2">合作伙伴</span>
                <img src="_STATIC_/2015/member/image/about/company.png" class="companyImg"/>
                <img src="_STATIC_/2015/member/image/account/right.jpg" class="right2Img"/>
                <img src="_STATIC_/2015/member/image/account/solid.png" class="solid2Img">
				</div>
				</a>
				<a href="/member/aboutcontact" style="text-decoration:none;">
				<div class="pwd">
				<span class="phone3">联系我们</span>
                <img src="_STATIC_/2015/member/image/about/conect.png" class="conectImg"/>
                <img src="_STATIC_/2015/member/image/account/right.jpg" class="right3Img"/>
				</div>
				</a>			
        </div>
    </div>
</div>
</body>
</html>   
{__NOLAYOUT__}