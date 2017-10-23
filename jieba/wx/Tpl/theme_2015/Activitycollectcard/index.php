<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/activity/css/card.css" />
 	 <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script src="_STATIC_/2015/activity/js/jQueryRotate.2.2.js" type="text/javascript" charset="utf-8"></script> 
    <script type="text/javascript">     
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }else{
                $(".big_div").css("padding-top","0px");
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
		var img = $("nav>a").click(function(){			
				$(".card > img.show").addClass("hide").removeClass("show"); 
	            $(".card > img:eq(" + $.inArray(this, img) + ")").addClass(function () {  
	                return "show";   
	            }).removeClass("hide");      
			})
		})
    </script>
    <style type="text/css"></style>
</head>
<body style="background: #efefef;">
<header class="headers" style="display: none;">
    <a href="/index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">活动专区</h1>      
</header>
<section class="mt_50 top_div">
	<span class="radius">{$activityInfo['successMember']}人集卡成功</span>
	<h1>
		<font>新任务“取真金”</font>
		<p>集齐师徒四人卡，得<b>{$activityInfo['money']}</b>元现金</p>
	</h1>
</section>
<img src="_STATIC_/2015/activity/image/card/bg_card.jpg" class="bg_card">
<section class="card">
    <foreach name="myCard" item="vo">
        <img src="{$vo['img_url']}" class="{$vo['class']}">
    </foreach>
</section>
<nav class="center">
	<foreach name="myCard" item="v">
        <a><i>{$v['count']}</i><img src="{$v['img_url']}" <empty name="v['count']">class="none"</empty>></a>
    </foreach>
    <span class="clear"></span>	
</nav>
<eq name="activityInfo.surplus_member" value="0">
    <a class="radius look" style="background: #ddd;color:#999;">活动已结束</a>
    <else/>
    <a class="radius look" href="/credit/index">立即收集</a>
</eq>

<span class="w50">剩余名额: <font>{$activityInfo['surplus_member']}</font> 人</span>
<section class="rule">
	<font>活动规则:</font>
	<ol style="list-style-position: inside">
		<li>1.活动时间：{$activityInfo['startDate']}——{$activityInfo['endDate']}，共计{$activityInfo['spot_number']}个名额，名额送完活动可提前结束</li>
		<li>2.该活动只针对借吧车友贷新老用户（车贷正常还款两期及以上，且尚未结清的用户）</li>
		<li>3.客户在活动周期内每正常还款一次即可获得一张卡片，提前还款也可获得。活动周期内，借款还款次数不限。</li>
		<li>4.集齐四张不一样的卡片便能获得{$activityInfo['money']}元现金奖励，四张卡包括：唐僧卡、孙悟空卡、猪八戒卡、沙僧卡。{$activityInfo['money']}元现金将直接打入您注册借吧时绑定的银行卡内。</li>
		<li>5.集卡活动只在活动期内有效，靠借吧客户通过正常借款、还款自行获取，不可交换和买卖，最终解释权归借吧所有。</li>
	</ol>
</section>
<img src="_STATIC_/2015/activity/image/card/bg_bottom.png">
</body>
</html>
{__NOLAYOUT__}
