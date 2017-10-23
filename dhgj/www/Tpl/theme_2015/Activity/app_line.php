      <style>		
        body,html{min-width:1100px;background:#fff;font-size:"微软雅黑";}
		.app-banner{height:627px;background:url(_STATIC_/2015/activity/app_line/img/app-online-banner.jpg)no-repeat 50% 0;}
		.center{width:1100px;margin:0 auto;position:relative;}
		.app-banner button{width:220px;height:60px;border:none;font-size:22px;margin-top:413px;color:#fff;border-radius:12px;opacity:0.5;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50)9;}
		.app-banner button:hover{opacity:1;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=100)9;}
		.button-left{margin-left:417px;background:url(_STATIC_/2015/activity/app_line/img/Android.png)no-repeat 22px #81511c;}
		.button-right{margin-left:100px;background:url(_STATIC_/2015/activity/app_line/img/iPhone.png)no-repeat 36px #81511c;}
		.app-content{background:#fcfdec;height:737px;}
		.app-content .center{padding-top:80px;}
		.app-div-left{float:left;padding:40px 0 0 61px;text-align:center;}
		.app-div-right{float:right;padding-right:60px;}
		.app-Android{margin:50px 0 40px 0;height:50px;line-height:50px;text-align:center;color:#fff;background:#c0c202;border-radius:12px;font-size:19px;}
		.app-phone{width:280px;height:544px;background:url(_STATIC_/2015/activity/app_line/img/phone.png)no-repeat;padding:51px 10px 54px;}
		.app-scroll{overflow:hidden;width:262px;height:431px;}
		.app-scroll ul{width:2000px;height:431px;position:relative;}
		.app-scroll li{float:left;}
		.app-phone img{width:262px;height:431px;}
		.app-radius{margin-top:28px;margin-left:104px;}
		.app-radius li{width:10px;height:10px;float:left;background:#dddddd;border-radius:100%;cursor:pointer;}
		.app-radius li~li{margin-left:10px;}
		.app-radius .now{background:#c0c202;}
        .alert{position:absolute;left:370px;z-index:20;display:none;width:388px;height:222px;background:url(_STATIC_/2015/activity/app_line/img/app_store.jpg);}
        .close1{background-image:url(_STATIC_/2015/box/box-icon.png);background-repeat:no-repeat;background-position:0 0;height:22px;width:22px;cursor:pointer;position:absolute;top:11px;opacity:1;right:11px;}
        .close1:hover{opacity:0.5;}
	</style>
	<script>
		var app_time;
		var nowpicture=1;
		function app_scroll(){
			$(".app-scroll>ul").animate({left:nowpicture*-262});
			$(".app-radius>li").eq(nowpicture).addClass("now").siblings().removeClass("now");
			nowpicture++;
			if(nowpicture>=$(".app-radius>li").length){nowpicture=0;}
		}
		app_time=setInterval(app_scroll,2000);
		$(function(){
			$(".app-radius>li").click(function(){
				nowpicture=$(this).index();
				app_scroll();
			}).mouseover(function(){
				clearInterval(app_time);}).mouseout(function(){
				app_time=setInterval(app_scroll,2000);})
		})
	</script>
	<!--banner-->
	<div id="overlay" style="opacity: 0.5; height: 1886px; width: 1349px;position:absolute;top:0;z-index:10;background:#fff;display:none"></div>
	<div class="app-banner"><div class="center"><button class="button-left" onclick="javascript:window.location.href='http://www.zhifu360.com/download/zhifu360.apk'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Android下载</button><button class="button-right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;iphone下载</button><div class="alert"><div class="close1"></div></div></div></div>
	<!--content-->
	    <script>
        $(function(){
            var scrtop=0;
            $(".button-right").click(function(){
                if($("body").scrollTop()<150){
                    scrtop=150;
                } else{
                    scrtop=$("body").scrollTop();
                }
                $(".alert").css({display:"block",top:scrtop});
                $("#overlay").css({display:"block"});
            })
            $(".close1").click(function(){
                $(".alert").css({display:"none"});
                $("#overlay").css({display:"none"});
            })
        })
    </script>
	<div class="app-content">
		<div class="center">
			<div class="app-div-left"><img src="_STATIC_/2015/activity/app_line/img/Android-code.png?20150730" alt=""><div class="app-Android">手机APP下载二维码</div><img src="_STATIC_/2015/activity/app_line/img/App-online-text.png" alt=""></div>
			
			<div class="app-div-right"><div class="app-phone"><div class="app-scroll"><ul>
				<li><img src="_STATIC_/2015/activity/app_line/img/001.jpg" alt=""></li>
				<li><img src="_STATIC_/2015/activity/app_line/img/002.jpg" alt=""></li>
				<li><img src="_STATIC_/2015/activity/app_line/img/003.jpg" alt=""></li>
				<li><img src="_STATIC_/2015/activity/app_line/img/004.jpg" alt=""></li>
			</ul></div></div>
			<ul class="app-radius">
			<li class="now"></li>
			<li></li>
			<li></li>
			<li></li></ul></div>
			
		</div>
	</div>
