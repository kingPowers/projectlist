<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>   
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title>  
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }else{
                $(".content").css("padding-top","0px");
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
        ul, ol, li, dl, dt, dd, h1, h2, h3, h4, h5, p{margin: 0;padding: 0; list-style: none;}
        .content{padding-top: 40px;}
        .page_con{width: 100%; margin:0 auto;}
        .ck_header{height: 40px; border-bottom: 1px solid #ddd;  line-height: 40px;  width: 100%; margin: 0 auto;  text-align: center;  font-size: 14px;  color: #b4b4b4; background: #fff;}        
        .liStyle{width: 50%; float: left; height: 40px; list-style: none; border-right: 1px solid #ddd;}
        .liStyle:nth-child(2){border:0; width: 49%;}
        .blue_css{color: #5495e6;}
        .piao_one{width: 96%; margin:10px auto;}
        .bg_t{background: url(_STATIC_/2015/activity/image/activity41/bg_old_20.png); background-size:100% 100%; width: 100%; color: #fff; font-size: 12px;}
        .bg_f{background: url(_STATIC_/2015/activity/image/activity41/bg_old_41.png); background-size:100% 100%; width: 100%; color: #fff; font-size: 12px;}
        @media (min-width: 320px){.piao_table_t{height: 150px;} .piao_con{width: 70%; position: absolute; padding: 27% 0 0 18%;} }
        @media (min-width: 375px){.piao_table_t{height: 180px;} .piao_con{width: 70%; position: absolute; padding: 28% 0 0 18%;} }
        @media (min-width: 414px){.piao_table_t{height: 195px;} .piao_con{width: 70%; position: absolute; padding: 30% 0 0 18%;} }        
        .piao_con span{width: 100%; display: block; line-height: 20px;}
    </style>
</head>

<body style="background: #efefef;">
<header class="headers" style="display: none;">
    <a href="/member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">活动专区</h1>      
</header>
<section class="content">
    <section class="page_con">
        <ul class="ck_header">
            <li class="liStyle" onclick="javascript:window.location.href='/member/activity'">今日礼券</li>
            <li class="liStyle blue_css">已过期礼券</li>
        </ul>
    </section>
    <foreach name="over_ticket" item="vo">
    <section class="piao_one" onclick="javascript:window.location.href='/member/activity_result/ticket_id/{$vo.id}'">
        <section class="piao_table_t bg_t">
            <section class="piao_con">
                <span>成功邀请好友自动提额</span>
                <span>仅限借吧车友贷申请用户</span>
                <span>有效期至<?php echo date("Y年m月d日",strtotime($vo['over_time']))?></span>
            </section>
        </section>        
    </section>
    </foreach>
</section>
</body>
</html>
{__NOLAYOUT__}