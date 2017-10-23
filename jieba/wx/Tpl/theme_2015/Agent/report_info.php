<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta content="telephone=no,email=no" name="format-detection">
	<meta content="yes" name="apple-touch-fullscreen">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
	<link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
	<link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css"> 
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
</head>
<body style="background: #efefef;">
<header>
	<a href="/agent/my_unlocked_order" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>举报详情</h1>
</header>
<form id="reportForm">
<section class="report_div">
	<textarea name="content" id="content" placeholder="请输入您的举报内容" value="{$report_info.content}" readonly="" >{$report_info.content}</textarea>
<foreach name="report_info.pic_urls" item="vo">
<img src="{$vo}" class="btn_up">
</foreach>
</section>
<section class="report_div" style="border-top:1px solid #ededed; margin-top: 0;">
  <section class="report_blue">
    <span>举报结果</span>
    <p>{$report_info.remark}</p>
  </section>
</section>
<section class="reoprt_tel">400-663-9066</section>
</form>
</body>
</html>
{__NOLAYOUT__}