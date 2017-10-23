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
	<ul class="bill-d-list">
		<li class="bg_sli">
			<section class="detail_con"><p>首付款</p><font>交易日 {$orderInstalmentInfo.initial_time}</font></section>
			<section class="detail_con"><p>{$orderInstalmentInfo.initial_money}</p><font>已还清</font></section>
		</li>
                <foreach name="orderInstalmentInfo.instalment" item="vo">
                    <li <eq name="vo.status_name" value="已还清">class="bg_sli"</eq>>
                        <section class="detail_con"><p>{$vo.period}/10期</p><font>还款日 {$vo.back_time}</font></section>
                        <section class="detail_con"><p>{$vo.periode_money}(含手续费{$vo.period_fee})</p><font>{$vo.status_name}</font></section>
                    </li>
                </foreach>       
        
       
	</ul>
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