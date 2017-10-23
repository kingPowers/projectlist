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
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <title>{$pageseo.title}</title>  
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }else{
                $(".center").css("margin-top","0px");
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
        $(function(){
            $("#btn_fx").click(function(){
                $(".tk_fx").show();
            })
            $(".tk_fx,.tkLayBg").click(function(){
                $(".tk_fx,.tkLayBg,#pic_lay").hide();
            })
            $("#btn_yq").click(function(){
               $(".tkLayBg,#pic_lay").show();
               $(".tkLayBg").height($(document).height());               
            })
        })
    </script>
       <style type="text/css">
        ul, ol, li, dl, dt, dd, h1, h2, h3, h4, h5, p{margin: 0;padding: 0; list-style: none;}
        .center{width:100%;height:auto;overflow: hidden;}
        .center img{width: 100%; display: block; overflow: hidden;}
        .btn_div{width: 80%;  position: absolute; left: 50%; margin:24% 0 0 -40%;}
        .btn_div img{width: 45%; display: inline-block;}
        .tk_fx{width: 100%; position: fixed; z-index: 10; display: none;}   
        #pic_lay{display: none; width:88%; left: 50%; margin: 20% 0 0 -44%; position: absolute;}    
    </style>
</head>

<body style="background:#372d52">
<header class="headers" style="display: none;">
    <a href="/index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">活动专区</h1>      
</header>
<img src="_STATIC_/2015/activity/image/activity41/bg_fx.png" class="tk_fx">
<section class="tkLayBg">
    <img src="{$card_name}" id="pic_lay">
</section>
<section class="center" style="margin-top:40px">
    <img src="_STATIC_/2015/activity/image/activity41/ac_01.jpg">
    <img src="_STATIC_/2015/activity/image/activity41/ac_02.jpg">
    <section class="btn_div">
        <img src="_STATIC_/2015/activity/image/activity41/btn_yq.png" id="btn_yq">
        <img src="_STATIC_/2015/activity/image/activity41/btn_fx.png" style="margin-left: 8%" id="btn_fx">
    </section>
    <img src="_STATIC_/2015/activity/image/activity41/ac_03.jpg"> 
</section>
</body>
</html>
<script type="text/javascript">
 // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
    wx.config({
        debug: false,
        appId: '{$signPackage.appId}',
        timestamp: '{$signPackage.timestamp}',
        nonceStr: '{$signPackage.nonceStr}',
        signature: '{$signPackage.signature}',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });

    wx.error(function (res) {
        //alert(res);
    });

    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareTimeline({
            title: '借吧--注册', // 分享标题
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareWeibo({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

    });
  
</script>
{__NOLAYOUT__}