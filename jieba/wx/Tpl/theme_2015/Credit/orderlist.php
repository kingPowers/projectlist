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
	<a href="/credit/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>借款记录</h1>    
</header>
<notempty name='orderlist'>
<ul class="mui-table-view">
	<foreach name='orderlist' item='vo'>
		<neq name='vo.order_status_name' value='未通过'>
			<a href='/credit/orderlistdetail?id={$vo.id}'><li class="mui-table-view-cell"><font>{$vo.loanmoney}</font><p>{$vo.timeadd}借</p><span>{$vo.order_status_name}</span></li></a>
		   <else/>
		   <!-- 审核未通过 -->
		   <li class="mui-table-view-cell1"><font>{$vo.loanmoney}</font><p>{$vo.timeadd}借</p><span>{$vo.order_status_name}</span></li>
		</neq>
	</foreach>
</ul>
<else/>
	<div style="color:#b7b7b7; position:relative; top:80px; text-align:center;">暂无借款记录</div>
</notempty>
</body>
</html>
{__NOLAYOUT__}	