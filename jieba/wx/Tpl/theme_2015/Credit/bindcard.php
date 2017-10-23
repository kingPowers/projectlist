<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <title>{$pageseo.title}</title>   
</head>
<body style="background: #efefef;">
	<header>
		<a href="javascript:history.back();" class="btn_back">
	        <img src="_STATIC_/2015/member/image/register/return.png">
	        <font class="fl">返回</font>
	    </a>
	    <h1>我绑定的银行卡</h1>    
	</header>
	<section class="mui-table-view">
		<section class="card-list-li">
			<font>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</font><span>{$card_info.names}</span>
		</section>
		<section class="card-list-li">
			<font>开户银行</font><span>{$card_info.bank_name}</span>
		</section>
<!-- 		<section class="card-list-li"> -->
<!-- 			<font>开户支行</font><span>{$card_info.bank_nm}</span> -->
<!-- 		</section> -->
		<section class="card-list-li">
			<font>银行卡号</font><span>{$card_info.capAcntNo}</span>
		</section>		
	</section>
	<a href='/credit/bindBaofu' style="float:right;margin-top:20px;margin-right:100px;">换绑银行卡</a>
</body>
</html>
{__NOLAYOUT__}