<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>   
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/member/js/acenter.js" /></script>
    <style type="text/css">
        html, body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td { margin: 0; padding: 0; }
        a{text-decoration:none;}
        .fl{float:left;}
        .fr{float:right;}
        .dbl{display: block;}
        img {width:100%; display:block;}
        body {font: normal 100% Helvetica, Arial, sans-serif; font-family:'微软雅黑';}
        header{width:100%; height: 40px; position:fixed; top:0; left: 0; background-color:#5495e6; z-index:10;  display: -webkit-box;}
        header a{position: absolute; z-index: 11; color: #fff;}
        header img{width:22%; height:20px; display:block; vertical-align:middle; overflow:hidden; padding:10px 0 0 8px; position:absolute;}
        header font{height: 40px; line-height: 40px; display: block; margin-left: 28px;}
        header h1{color:#fff; display:block; text-align:center; height:40px; line-height:40px; -webkit-box-flex: 1.0; font-size:18px; overflow:hidden; font-weight:normal; font-family:'微软雅黑'; position: relative; width: 100%;}
        .mui-table-view{ position:relative; background:#fff; margin-top: 50px;}  
        .mui-table-view-cell{ position:relative; border-bottom:1px #e3e3e3 solid; padding:15px 10px; background:url(_STATIC_/2015/member/image/account/right.jpg) no-repeat center right; background-origin:content-box; background-size: 10px 15px;}
        .mui-table-view-cell font{ color: #4c4c4c;}
        .mui-table-view-cell span{ font-size: 14px; color: #b7b7b7; float: right; margin-right: 8%;}
        .btn_exit{width: 94%; position: absolute; background: #63abe8; border-radius: 5px; text-align: center; letter-spacing: 1px; font-size: 18px; display: block; height: 40px; line-height: 40px; left: 50%; margin: 10% 0 0 -47%; color: #fff; -webkit-appearance:none;}
    </style>
</head>
<body onload="ft()" style="background: #efefef;">
<header>
    <a href="/member/account">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>账号中心</h1>
</header>
<ul class="mui-table-view">
    <a href="/member/setUsername"><li class="mui-table-view-cell"><font>修改用户名</font></li></a>
    <a href="/member/setmobile"><li class="mui-table-view-cell"><font>修改手机号</font></li></a>
    <a href="/member/setpwd"><li class="mui-table-view-cell"><font>修改密码</font></li></a>
    <a href="/member/autherphone"><li class="mui-table-view-cell"><font>手机认证</font> <span>已认证</span></li></a>
    <a href='/credit/bindCard?returnurl=<?php echo urlencode('/member/accountcenter');?>'><li class="mui-table-view-cell"><font>绑定银行卡</font> <notempty name='openfuyou'><span>已绑定</span></notempty></li></a>
    <a href="/member/recommend"><li class="mui-table-view-cell"><font>更改邀请码</font></li></a>        
</ul>
<a class="btn_exit" href="/member/loginout">退出当前帐号</a> 
</body>
</html>   
{__NOLAYOUT__}