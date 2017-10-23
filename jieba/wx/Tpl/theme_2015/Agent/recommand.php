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
        .w100{background: #efefef; padding:4% 0; width:100%;}
        .w100 img{width: 12%; display: inline-block; vertical-align: middle;}
        .w40{width: 40%; margin: 0 auto;}
        .w40 span{text-align: center;}
   		.lv_table{width: 100%; background: #fff; text-align: center; }
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
    <h1>邀请记录</h1>    
</header>
<section class="content_div">
<section class="w100">
    <section class="w40">
        <img src="_STATIC_/2015/member/image/account/idea.png">
        <span>已累计邀请{$recommand.total}人</span>
    </section>  
</section>
<table class="lv_table" cellpadding="0" cellspacing="0">
	<tr><th>用户名</th><th>认证时间</th></tr>
	<foreach name='recommand.recommandList' item='vo'>
    	<tr><td>{$vo.mobile}</td><td>{$vo.timeadd}</td></tr>
    </foreach>
</table>
</section>
</body>
<script type="text/javascript">
	$(function(){
		page = 2; is_loading = 1;
		$(window).scroll(function(){
			if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
				$.ajax({
					'type':'post',
					'dataType':'json',
					'url':"/agent/recommand",
					"data":{'page':page++,'is_ajax':1},
					success:function(json){
						if(json.status==1){
							var str = "";
							$.each(json.data,function(i,item){
								str+='<tr><td>'+item.mobile+'</td><td>'+item.timeadd+'</td></tr>';
							});
							$('.lv_table').append(str);
						}else{
							is_loading=0;
						}
					},
					
				});
			}
		});
		$(window).scroll();
	});
</script>
</html>
{__NOLAYOUT__}