<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">  
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");                
            }
            else{
                $(".centerDiv").css("margin-top","10px");
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
    </script>
    <style type="text/css">
            .centerDiv{width: 100%; margin: 50px auto 0; height: auto; overflow: hidden;}
            .center1{width: 96%; margin: 10px auto 0; height: auto; overflow: hidden;}
            .center1 p{line-height: 25px;font-size: 14px; color: #666;}
            .center3{width: 96%; margin: 10px auto 0; display: block; }
    </style> 
</head>
<body>
    <header class="headers" style="display: none;">
        <a href="javascript:history.back();" class="btn_back">
            <img src="_STATIC_/2015/member/image/register/return.png">
            <font class="fl">返回</font>
        </a>
        <h1>联系我们</h1>    
    </header>
    <div class="centerDiv">        
	    <img src="_STATIC_/2015/member/image/about/bulid.jpg"/>
		<div class="center1">
		    <p>智信创富金融信息服务（上海）有限公司</p>
			<p>地址：上海市宝山区逸仙路2816号华滋奔腾大厦B座11F</p>
			<p>11F building block B baoshan district，NO 2816 yi xian road，shanghai</p>
			<p>联系电话：021-520009</p>
			<p>公司邮箱：InternetCenter@zxcfchina.com</p>			 
		</div>
		<div class="center3">
		     <iframe src="/member/map" frameborder=0 scrolling=no></iframe>
		</div>
    </div>

</body>
</html>   
{__NOLAYOUT__}