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
    <h1>我的账单</h1>    
</header>
<section class="bgf mt40">
	<nav class="bgf">
		<ul class="bill_nav">
	        <li><a href="hasBill">已出账</a></li>
                <li><a href="noBill" id="blue-line">未出账</a></li>
		</ul>
	</nav>	
</section>
<section class="m10 bgf" id="bill_box">	
	<span>{$unoutBillInfo.date}剩余应还</span>
	<h1>{$unoutBillInfo.unpayment_money}</h1>
	<font>请按时还款，避免逾期！</font>
</section>
<section class="m10 bgf">
    <a href="repaymentList?type=noBill">
            <section class="mui-view-cell">
                    <b>本月已还款金额</b>
                    <span>{$unoutBillInfo.paid_money}</span>
            </section>
	</a>
</section>
<section class="center_d">
	次月待还款车险账单共{$unoutBillInfo.unpayment_num}笔，{$unoutBillInfo.unpayment_money}元
</section>
<section class="m10 bgf">
    <foreach name="unoutBillInfo.unpay_instalment" item="vo">
            <a href="/allot/instalmentDetail?instalId={$vo.id}&back_type=4">
                <section class="mui-view-cell">
                        <b>
                        我的车险<font> (编号:{$vo.instalment_sn})</font><br>
                        <time>最后还款日{$vo.back_time|strtotime|date="Y年m月d号",###}</time>
                        </b>
                        <span class="center_span">{$vo.pay_money}</span>			
                </section>
            </a>
    </foreach>
	
</section>
<section class="center_d">
	已结清车险分期账单共{$unoutBillInfo.paid_num}笔，{$unoutBillInfo.paid_money}元
</section>
<section class="bgf">
	<foreach name="unoutBillInfo.paid_instalment" item="vo">
            <a href="/allot/instalmentDetail?instalId={$vo.id}&back_type=4">
                <section class="mui-view-cell">
                        <b>
                        我的车险<font> (编号:{$vo.instalment_sn})</font><br>
                        <time>最后还款日{$vo.back_time|strtotime|date="Y年m月d号",###}</time>
                        </b>
                        <span class="center_span">{$vo.back_money}</span>			
                </section>
            </a>
    </foreach>
</section>
    <eq name="unoutBillInfo.is_allow_pay" value="1">
    <a href='/allot/beginRepayment?back_type=3'><input type ="button" value="提前还款" class="btn_sub" style="border: 0;"></a>
    <else/>
        <input type ="button" value="提前还款" class="btn_sub" style="border: 0;background:#ddd;">
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