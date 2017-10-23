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
            $("#look").click(function(){               
//                $(".tk_bd,.tk_photo").show();
//                $(".tk_bd").click(function(){
//                    $(".tk_bd,.tk_photo").hide();
//                });
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
<section class="tk_photo"><img src="{$detail.price_pic}"></section>
<section class="bgf mt40">
	<section class="order-con">
		<section class="bot_box">
			<section class="order-head">
				<img src="<notempty name="member_info.avatar">_STATIC_/Upload/avatar/{$member_info.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" class="ico_car" style="margin:0 auto;">
				<span class="bigfont">{$member_info.username}</span>
			</section>			
			<section class="box_left">
				<font style="margin: 0.2rem 0; display: block;">申请险种:</font>
                <notempty name="detail['insurance_type']">
				<p style="margin: 0.3rem 0;">
                    <label><img src="_STATIC_/2015/image/yydai/insurance/ico_right.png">
                        <?php echo str_replace(",","</label><label><img src='_STATIC_/2015/image/yydai/insurance/ico_right.png'>",$detail["insurance_type"]);?>
                    </label>
				</p>
                </notempty>			
			</section>
		</section>
	</section>	
</section>
<section class="proh bgf" style="margin-top: 10px;">
	<section class="order-tit">
		<h2 class="bigfont">车险详情</h2>
	</section>
	<ul class="part">
            <foreach name="detail.process" item="vo" key="key">
                <?php if($key==0):?>
                    <li id="font-blue"><b id="font-blue">&bull;</b>{$vo.timeadd}{$vo.message}</li>
                <?php else:?>
                    <li><b>&bull;</b>{$vo.timeadd}{$vo.message}</li>
                <?php endif;?>
                
            </foreach>
	 	
	</ul>
</section>	
<section class="btn_div">
    <eq name="detail.is_instalment" value="1">
        <a  style=""<notempty name='detail.price_pic'>id="look" href="/allot/index"</notempty>>已分期</a>
    <else/>
        <a  style="" <if condition="$detail['status'] eq 1" > <if condition="$detail['status_process'] eq 3"> <notempty name='detail.price_pic'>id='look' href="/allot/pay?id={$detail.id}"</notempty></if></if>>报价付款</a>
    </eq>
	
        
	<a <?php if($detail["status"]==1 && $detail["status_process"]<4): ?>id="cancel" onclick='cancle("{$detail.id}")'<?php endif;?>>取消订单</a>
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
function cancle(id){
    if(confirm("您确定要取消订单吗？")){
        location.href="orderCancle?id="+id;
    }else{
        return false;
   }
}
</script>
</html>
{__NOLAYOUT__}