<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/autherphone.css" />
    <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/autherphone.js"></script>
</head>
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
            <a href="/member/accountCenter" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz">返回</span></a>
            <span class="zxwx">手机认证</span>
        </div>
    </div>
    <div class="centerDiv"><!--中-->
        <div class="center1">
            <!-- <input type="checkbox" name="checkbox" class="checkbox" id="checkbox" checked="checked"> -->
            <span class="phone">已认证，如需修改请联系 <a style="color:#5495e6;text-decoration:none; font-weight:bold;" class="waiter">客服</a></span>
        </div>
        <div class="center2">
            <form action="" method="post" class="">
                <span class="phone2">手 机</span>
                <input value="{$member_info.mobile}"  name='mobile' class="input size" />
            </form>
        </div>
    </div>
</div>
<div class="secondDiv"></div><!--弹出灰色弹窗-->
<div class="thirdDiv">
    <a href='/member/customer_list/from_setmobile/1'><p class="notice tsize">在线客服</p></a>
    <img src="_STATIC_/2015/member/image/account/solid.png" class="waietrImg">
    <a href='tel:4006639066'><p class="notice2 tsize">客服电话：400 663 9066</p></a>

    <a href="javascript:void(0)"  class='jump_url'><input type="button" value="取消" class="surebtn ssize"/></a>
</div><!--弹出内容-->
<div class="fourDiv">
    <p class="notice3 tsize">400 663 9066</p>
    <img src="_STATIC_/2015/member/image/account/border.png" class="borderImg">
    <img src="_STATIC_/2015/member/image/register/solid.png" class="solidImg">
    <img src="_STATIC_/2015/member/image/register/tosolid.png" class="tosolidImg">
    <a href="javascript:void(0)"  class='jump_url'><input type="button" value="取消" class="surebtn2 ssize"/><input type="button" value="确定" class="surebtn3 ssize"/></a>
</div><!--弹出内容-->
<script type="text/javascript">
    $(".waiter").click(function(){
        $(".secondDiv").css("display","block");
        $(".thirdDiv").css("display","block");
    });
    /*$(".notice2").click(function(){
        $(".secondDiv").css("display","block");
        $(".fourDiv").css("display","block");
        $(".thirdDiv").css("display","none");
    });*/
    $('.surebtn').click(function(){
        $(".secondDiv").css("display","none");
        $(".thirdDiv").css("display","none");
    });
    $('.surebtn2').click(function(){
        $(".secondDiv").css("display","none");
        $(".fourDiv").css("display","none");
    });
    $('.surebtn3').click(function(){
        $(".secondDiv").css("display","none");
        $(".fourDiv").css("display","none");
    });
</script>
</body>
</html>
{__NOLAYOUT__}