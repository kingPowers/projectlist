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
	   
    </script>
</head>
<body style="background: #efefef;">
<header class="headers">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>分期明细</h1>    
</header>
<section class="bgf mt40">
	<section class="repay_tit">
		<span>本月剩余应还(元)</span>
        <h1>{$instalmentDetail.need_paymoney}</h1>
        <font>分期总额{$instalmentDetail.total_instalment_money}(含手续费{$instalmentDetail.total_period_fee})</font>
	</section>
</section>
<section class="m10 bgf">
    <a href="billDetail?order_id={$instalmentDetail.order_id}">
        <section class="mui-view-cell">
            <b>分期明细</b>
            <span class="center_span" style="font-size: 0.2rem">已还{$instalmentDetail.pay_periodes}期<br>
                <eq name="instalmentDetail.is_delay" value="1">
                    有逾期
                <else/>
                    无逾期
                </eq>
            </span>            
        </section>
    </a>
</section>
<section class="m10 bgf">
    <section class="mui-list-cell">
        <ul>
            <li><b>我的车险<font>(<eq name="instalmentDetail.car_type" value="1">个人车<else/>公司车</eq>)</font></b><span>编号:{$instalmentDetail.order_sn}</span></li>
            <li><b>订单金额</b><span><?php echo $instalmentDetail["business_money"]+$instalmentDetail["force_money"]?></span></li>
            <li><b>申请时间</b><span>{$instalmentDetail.timeadd}</span></li>            
        </ul>
    </section>
</section>
    <eq name="instalmentDetail.status" value="1">
        <input type="button" class="btn_sub m40" value="已结清" id="btn_stop">
    <else/>
    <a href='/allot/beginRepayment?instalmentid={$instalmentDetail.id}&&back_type={$_GET["back_type"]}'>
        <input type="button" class="btn_sub m40" value="确认还款">
    </a>
    </eq>


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