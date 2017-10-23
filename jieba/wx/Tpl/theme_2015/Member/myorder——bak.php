<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/myorder.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/myorder.js" /></script>
</head>
<body style="background:#efefef;">
    <div class="maxDiv">
        <!--顶部 开始-->
        <div class="headers">
            <div class="rd">
                <a href="/member/login" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/></a>
                <span class="fhwz size2"><a href="/member/login" style="color:white;text-decoration:none;">返回</a></span>
                <span class="orderwx size2">我的订单</span>
            </div>
        </div>
        <!--顶部结束-->
        <div class="d2">
            <p class="allP"><span class="all size2">全部</span></p>
            <p class="shingP"><span class="shing size2">审核中</span></p>
            <p class="shsuccP"><span class="shsucc size2">审核成功</span></p>
            <p class="shsadP"><span class="shsad size2">审核失败</span></p>
        </div>
        <!--审核中  审核失败  内容层  开始-->
        <div class="d3">
            <div class="d3top">
                <p class="skimgwz size3">审核中</p>
            </div>
            <div class="d3center">
                <div class="d3center-top">
                    <p class="mcdwz size3">买车贷</p>
                </div>
                <div class="d3center-center">
                    <img src="_STATIC_/2015/member/image/myorder/bucars.png" alt="" class="bucarsImg"/>
                    <span class="money3swz size4">借款金额:50000.00</span><span class="timesswz size4">申请时间:2016-02-15</span>
                    <span class="jdwz size4">订单完成<span>50%</span></span>
                </div>
            </div>
        </div>
        <!--审核成功 内容层  开始-->
        <div class="d4">
            <div class="d4op">
                <p class="sk4imgwz size3">交易成功</p>
                <a href="#"><p class="xqimgwz size5">查看详情>></p></a>
            </div>
            <div class="d4center">
                <div class="d4center-top">
                    <p class="mcd4wz size3">买车贷</p>
                </div>
                <div class="d4center-center">
                    <img src="_STATIC_/2015/member/image/myorder/bucars.png" alt="" class="bucars4Img"/>
                    <span class="moneys4wz size4">借款金额：<span>50000.00</span></span><span class="timess4wz size4">申请时间：<span>2016-02-15</span></span>
                    <span class="htnum4wz size4">合同编号:<span>123456</span></span>
                    <span class="jd4wz size4">订单已完成</span>
                </div>
                <div class="d4bottom">
                    <img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>
                </div>
            </div>
        </div>
        <!--审核成功 内容层  结束-->
        <div class="d4">
            <div class="d4op">
                <p class="sk4imgwz size3">交易成功</p>
                <a href="#"><p class="xqimgwz size5">查看详情>></p></a>
            </div>
            <div class="d4center">
                <div class="d4center-top">
                    <p class="mcd4wz size3">买车贷</p>
                </div>
                <div class="d4center-center">
                    <img src="_STATIC_/2015/member/image/myorder/bucars.png" alt="" class="bucars4Img"/>
                    <span class="moneys4wz size4">借款金额：<span>50000.00</span></span><span class="timess4wz size4">申请时间：<span>2016-02-15</span></span>
                    <span class="htnum4wz size4">合同编号:<span>123456</span></span>
                    <span class="jd4wz size4">订单已完成</span>
                </div>
                <div class="d4bottom">
                    <img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>
                </div>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript">
    $(".allP").hover(function () {
        $(".all").css("border-bottom", "2px solid #5495e6");
        $(".shing").css("border-bottom", "none");
        $(".shsucc").css("border-bottom", "none");
        $(".shsad").css("border-bottom", "none");
        $(".all").css("color", "#5495e6");
    });
    $(".shingP").hover(function () {
        $(".shing").css("border-bottom", "2px solid #5495e6");
        $(".all").css("border-bottom", "none");
        $(".shsucc").css("border-bottom", "none");
        $(".shsad").css("border-bottom", "none");
        $(".all").css("color", "black");
    });
    $(".shsuccP").hover(function () {
        $(".shsucc").css("border-bottom", "2px solid #5495e6");
        $(".all").css("border-bottom", "none");
        $(".shing").css("border-bottom", "none");
        $(".shsad").css("border-bottom", "none");
        $(".all").css("color", "black");
    });
    $(".shsadP").hover(function () {
        $(".shsad").css("border-bottom", "2px solid #5495e6");
        $(".all").css("border-bottom", "none");
        $(".shsucc").css("border-bottom", "none");
        $(".shing").css("border-bottom", "none");
        $(".all").css("color", "black");
    });
</script>
</html>   
{__NOLAYOUT__}