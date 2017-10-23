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
	    	/*var ary = $(".nav > li > a").click(function () {
	            $(this).parent().find("a.show").addClass("hide").removeClass("show"); 
	            $("a.show").removeAttr("id");
	            $(this).addClass("show").attr("id","blue-line").removeClass("hide");              
	            $("#page_con > section.u_show").addClass("u_none").removeClass("u_show"); 
	            $("#page_con > section:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
	                return "u_show";   
	            }).removeClass("u_none");  
	        }).toArray();*/
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
    <a style="right:8px;" href='/credit/help' class="btn_help"><img src="_STATIC_/2015/image/yydai/index/ico_help.png"></a>   
</header>
<nav class="bgf">
	<ul class="nav">
            <li><a href="myCarInsurance" class="show" <empty name="_GET['status']">id="blue-line"</empty>>全部</a></li>
                <li><a href="myCarInsurance?status=1&is_pay=0" <eq name="_GET['is_pay']" value='0'>id="blue-line"</eq> class="hide">审核中</a></li>
                <li><a href="myCarInsurance?status=1&is_pay=1" <eq name="_GET['is_pay']" value='1'>id="blue-line"</eq> class="hide">待付款</a></li> 
		<li><a href="myCarInsurance?status=2" <eq name="_GET['status']" value='2'>id="blue-line"</eq> class="hide">已成交</a></li>
		<li><a href="myCarInsurance?status=3" <eq name="_GET['status']" value='3'>id="blue-line"</eq> class="hide">未成交</a></li>
	</ul>
</nav>
<section id="page_con">
	<section class="order-con proh u_show">
            <foreach name="list" item="vo">
		<section class="order-one bgf">
			<h2><b class="bigfont">
                                <if condition="$vo.status eq 1">订单申请中
                                    <elseif condition="$vo.status eq 2"/>订单成功
                                    <elseif condition="$vo.status eq 3"/>订单未成交
                                </if>
                            </b>
                           <a href="insuranceInfo?id={$vo.id}" class="smallfont">查看详情>></a></h2>	
			<section class="order-con">
				<section class="order-tit">
					<span class="bigfont fl">我的车险</span>
                                        <font class="smallfont fl"><eq name="vo.car_type" value="2">(个人车)<else/>(公司车)</eq></font>
				</section>
				<section class="bot_box">
					<img src="_STATIC_/2015/image/yydai/insurance/ico_car.png" class="ico_car">
					<section class="box_left">
						<font>申请险种</font>
						<p>
                                                    <label><img src="_STATIC_/2015/image/yydai/insurance/ico_right.png">
                                                        <?php echo str_replace(",","</label><label><img src='_STATIC_/2015/image/yydai/insurance/ico_right.png'>",$vo["insurance_type"]);?>
                                                    </label>
						</p>
						<time>申请时间: {$vo.timeadd|strtotime|date="Y-m-d H:i",###}</time>
                        <a class="look_btn" <notempty name="vo.bill_pic">href='myGuarantee?id={$vo.id}'<else/>style="background:#ddd;"</notempty>>查看保单</a>
					</section>
				</section>	
			</section>
		</section>	
             </foreach>   
                
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