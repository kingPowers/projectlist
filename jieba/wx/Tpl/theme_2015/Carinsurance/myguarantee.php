<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/carinsurance.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <title></title>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
	   $(function(){ 
	   		var _this=$(".order-con > img");
            $(_this).click(function(){
                $(".tk_bd,.tk_photo").show();
                $(".tk_photo").children("img").attr("src",$(this).attr("src"));
                 $(".tk_bd").click(function(){
                     $(".tk_bd,.tk_photo").hide(); 
                 });
            })
       })
    </script>
</head>
<body style="background: #efefef;">
<header class="headers">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>我的车险</h1>    
</header>   
<section class="tk_bd"></section>
<section class="tk_photo">
	<img src="_STATIC_/2015/image/yydai/insurance/bd.jpg">
</section>
<section id="page_con" class="mt40">
	<section class="order-con proh u_show">           
		<section class="order-one bgf">
			<h2><b class="bigfont">
               强制险
            </b>
            <a class="smallfont">保单编号：{$detail.bill_pic.force_bill_sn}</a></h2>	
			<section class="order-con">
				<img src="{$detail.bill_pic.force_bill_pic}" class="bd_pic" id="01">	
			</section>
		</section>
		<section class="order-one bgf">
			<h2><b class="bigfont">
               商业险
            </b>
            <a class="smallfont">保单编号：{$detail.bill_pic.business_bill_sn}</a></h2>	
			<section class="order-con">
				<img src="{$detail.bill_pic.business_bill_pic}" class="bd_pic" id="02">
			</section>
		</section>
	</section>	
</section>
</body>
<script type="text/javascript">
(function (doc, win) {
    var docEl = doc.documentElement,
    resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
    recalc = function () {
      var clientWidth = docEl.clientWidth;
      if (!clientWidth) return;
      docEl.style.fontSize = 100 * (clientWidth / 750) + 'px';
    };
  
    if (!doc.addEventListener) return;
       win.addEventListener(resizeEvt, recalc, false);
       doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);

</script>
</html>
{__NOLAYOUT__}   