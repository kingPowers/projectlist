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
                $(".tk_bd,.tk_photo").show();
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
    <h1>分呗</h1> 
    <a href="../Credit/help" class="more">
	   <img src="_STATIC_/2015/image/yydai/insurance/ico_more.png">
    </a>   
</header>
<section class="bgb mt40">
	<span>剩余应还(元)</span>
	<h1>{$userInstalment.total_unpayment}</h1>
	<span>先保障  后交费  0费率</span>
</section>
<section class="bgf bill_box">
    <a href="/allot/hasBill" class="bill_sbox">
		<h3>我的账单</h3>
                <?php
                    if($userInstalment["curmonth_unpayment"]["money"]>0){
                        echo "<font>".$userInstalment['curmonth_unpayment']['month']."月应还</font>"
                        ."<span>".$userInstalment['curmonth_unpayment']['money']."</span>";
                    }elseif($userInstalment["nextmonth_unpayment"]["money"]>0){
                        echo "<font>".$userInstalment['nextmonth_unpayment']['month']."月应还</font>"
                        ."<span>".$userInstalment['nextmonth_unpayment']['money']."</span>";
                    }elseif($userInstalment["nexttwomonth_unpayment"]["money"]>0){
                        echo "<font>".$userInstalment['nexttwomonth_unpayment']['month']."月应还</font>"
                        ."<span>".$userInstalment['nexttwomonth_unpayment']['money']."</span>";
                    }else{
                        echo "<font><br/> </font><span>0</span>";
                    }
                ?>
	</a>
	<a href="/allot/totalBill" class="bill_sbox" style="border-right: 0;">
		<h3>总账单</h3>
		<font>剩余应还</font>
		<span>{$userInstalment.total_unpayment}</span>
	</a>
</section>
<section class="banner_box">
	<img src="_STATIC_/2015/image/yydai/insurance/banner_01.jpg">
	<img src="_STATIC_/2015/image/yydai/insurance/banner_02.jpg">
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