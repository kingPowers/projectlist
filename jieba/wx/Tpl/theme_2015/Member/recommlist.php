<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/inrecord.css" />    
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <style type="text/css">
    	.peo_div{width: 100%; background:#fff; overflow: hidden; margin-top: 130px;}
    	.peo_one{width: 100%; margin: 0 auto; padding: 3% 0; overflow: hidden; font-size: 14px;}
    	.peo_one span {width: 33%; text-align: center; float: left; display: inline-block; height: 20px; line-height: 20px;}
    </style>
</head>
<body onload="ft()" bgcolor="#efefef">
<div class="maxDiv"><!--大盒子-->
    <div class="headers"><!--头-->
        <div class="rd">
            <a href="/member/account" style="color:white;text-decoration:none;">
	            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
	            <span class="fhwz">返回</span>
	            <span class="zxwx">邀请记录</span>
            </a>
        </div>
    </div>
        <div class="center1">
                <img src="_STATIC_/2015/member/image/account/idea.png" class="ideaImg">
                <span class="phone">已累计邀请{$recommend.count}人</span>
        </div>
        <div class="center2">
                <span class="phone2">用户名</span>
                <span class="phone3">借款时间</span>
                <span class="phone7">借款金额</span>  
        </div>
   
        <div class="peo_div">
        	<foreach name='recommend.recommlist' item='vo'>
	        	<div class="peo_one">
	        		<span>{$vo.mobile}</span>
	        		<span>{$vo.timeadd| mb_substr = 0,10}</span>
	        		<span>{$vo.loanmoney|default='0.00'}</span>
	        	</div>
        	</foreach>
        </div>
        <!-- ajax加载 -->
    </div>
</div>
</body>
<script type="text/javascript">
	$(function(){
		page = 2; is_loading = 1;
		$(window).scroll(function(){
			if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
				$.ajax({
					'type':'post',
					'dataType':'json',
					'url':"/member/recommList",
					"data":{'page':page++,'is_ajax':1},
					success:function(json){
						if(json.status==1){
							var str = "";
							$.each(json.data,function(i,item){
								if(item.timeadd==undefined)item.timeadd='';
								if(item.loanmoney==undefined || item.loanmoney=='')item.loanmoney='0.00';
								str+='<div class="peo_one" >';
								str+='<span>'+item.mobile+'</span><span>'+item.timeadd+'</span><span>'+item.loanmoney+'</span>  ';							
								str+='</div>';
							});
							$('.peo_div').append(str);
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