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
    <h1>全部账单</h1>    
</header>
    <?php $i=0;?>
<foreach name="billMonth" item="vo">
    <?php $i++;?>
    <section class="month_tit mt40" <gt name="i" value="1">style="margin-top:0px;"</gt>>
            {$vo.pay_year}
    </section>
    <section class="bgf">
            <ul class="bill-d-list">
                <foreach name="vo.month" item="cvo">
                    <li>
                        <section class="detail_con"><p>{$cvo.month_name}月账单</p><font>{$cvo.pay_money}</font></section>
                        <time><eq name="cvo.status" value="1">已结清</eq>
                               <eq name="cvo.status" value="0">未结清</eq>
                        </time>
                    </li>
                </foreach>
            </ul>
    </section>
</foreach>
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