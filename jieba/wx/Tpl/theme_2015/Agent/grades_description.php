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
    <style type="text/css">
   		.lv_table{width: 100%; margin-top:10px; background: #fff; text-align: center; }
    	.lv_table th{background: #f7f7f7; font-size: 14px; height: 45px; line-height: 45px; font-weight: normal;  border-bottom: 1px #ddd solid;}
    	.lv_table td{font-size: 14px; height: 45px; line-height: 45px; border-bottom: 1px #ddd solid; }
    	.lv_table img{width: 38%; margin: 0 auto;}    	
    </style>
</head>
<body style="background: #efefef;">
<header id="headers">
	<a href="/agent/agent_account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>等级说明</h1>    
</header>
<section class="content_div">
<table class="lv_table" cellpadding="0" cellspacing="0">
	<tr><th>等级</th><th>达成条件</th><th>状态</th></tr>
    <foreach name='grades_list' item="vo">
    <tr><td><img src="{$vo.levelPic}"></td><td>{$vo.levelTitle}</td><td style="color: <?php echo ($vo['statusName'] == "未完成")?"#b4b4b4":"#5495e6";?>;">{$vo.statusName}</td></tr>
    </foreach>
</table>
</section>
</body>
</html>
{__NOLAYOUT__}