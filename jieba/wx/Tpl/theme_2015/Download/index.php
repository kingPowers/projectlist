<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
</head>
<script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
<link rel="stylesheet" href="_STATIC_/2015/member/css/account.css" />
<script type="text/javascript" src="_STATIC_/2015/member/js/jquery.zclip.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<style type="text/css">
	html, body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td { margin: 0; padding: 0; }
	body { font-family: "微软雅黑"; font-size: 12px; color: #000000; word-wrap: break-word;}
	ul, li { list-style: none; margin: 0; padding: 0; }
	h1, h2, h3, h4, h5, h6 { font-weight: normal; font-size: 100%; }
	img { border: none;  vertical-align: middle; display: block;}
	a { text-decoration: none; outline: none; }
	p { margin: 0; padding: 0; }
	input, select, textarea { vertical-align: middle; *font-size:100%;}
	.download_bg{width: 100%; height: auto; overflow: hidden;}
	.download_div{position: fixed; width: 60%; left: 50%; margin-left: -30%; bottom: 5%;}
	.tkLayBg{background:url(_STATIC_/2015/download/img/bg_ts.png) repeat-y; width: 100%; position: fixed; left: 0px; top: 0px; z-index: 19;}
	.tkdiv_bg{width:96%; position:fixed; z-index:20; left:50%; margin:2% -48% 0;}
</style>
<script type="text/javascript">
$(function(){            
    if(isWeixin()) {
      $(".tkdiv_bg,.tkLayBg").show();
	  $(".tkLayBg").height($(document).height());
    }
})
function isWeixin() {
    var ua = navigator.userAgent.toLowerCase();
    if (ua.match(/MicroMessenger/i) == "micromessenger") {
        return true;
    } else {
        return false;
    }
}
function download(){	
	var ua = navigator.userAgent.toLowerCase();	
	if (/iphone|ipad|ipod/.test(ua)) {
		window.location.href="https://itunes.apple.com/cn/app/jie-ba/id1179727160?mt=8";		
	}else if (/android/.test(ua)) {
		window.location.href="_STATIC_/2015/download/借吧.apk";	
	}else{
		window.location.href="_STATIC_/2015/download/借吧.apk";
	}
}	
</script>
<body>
	<section class="tkLayBg" style="display:none;">
 		<section class="tkdiv_bg">
        	<img src="_STATIC_/2015/download/img/ico_tishi.png" style="width: 100%;">            
        </section>
    </section>
	<img src="_STATIC_/2015/download/img/bg_app.jpg" class="download_bg">
	<img src="_STATIC_/2015/download/img/btn_down.png" class="download_div" onclick="download()">
</body>
</html>
<script>

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
            title: '借吧--APP下载', // 分享标题
            desc: '\"全透明、好掌握、易操作\"的新一代网络借贷工具', // 分享描述
            link: '_WWW_/Download/index/', // 分享链接
            imgUrl: '_STATIC_/2015/download/img/link_img.png', // 分享图标
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
            title: '借吧--APP下载', // 分享标题
            desc: '\"全透明、好掌握、易操作\"的新一代网络借贷工具', // 分享描述
            link: '_WWW_/Download/index/', // 分享链接
            imgUrl: '_STATIC_/2015/download/img/link_img.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({
            title: '借吧--APP下载', // 分享标题
            desc: '\"全透明、好掌握、易操作\"的新一代网络借贷工具', // 分享描述
            link: '_WWW_/Download/index/', // 分享链接
            imgUrl: '_STATIC_/2015/download/img/link_img.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareWeibo({
            title: '借吧--APP下载', // 分享标题
            desc: '\"全透明、好掌握、易操作\"的新一代网络借贷工具', // 分享描述
            link: '_WWW_/Download/index/', // 分享链接
            imgUrl: '_STATIC_/2015/download/img/link_img.png', // 分享图标
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