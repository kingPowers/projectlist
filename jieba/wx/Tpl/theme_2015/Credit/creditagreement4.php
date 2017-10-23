<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title>
    <style type="text/css">
        #sp1{text-align: center; display: block; margin: 0 auto; height: 40px; line-height: 40px;}
        #contxt{font-size: 12px; width: 96%; margin: 0 auto; padding-bottom: 20px;}
        #contxt p{line-height: 20px;}
        .sp{color: #333333; margin: 10px 0;} 
        .ti2{text-indent: 2em;}      
        .img_seal{width: 30%; display: inline-block; vertical-align: middle;}
        .font_blue{display: inline-block; color: #5495e6; text-indent: 0em;}
    </style>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }
            else{
                $(".content_div").css("top","20px");
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
</head>
<body style="background: #fff;">
<header class="headers" style="display: none;">
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>还款承诺书</h1>    
</header>
<section class="content_div">
    <span id="sp1">还款承诺书</span>    
    <section id="contxt">
        <p class="sp ti2">本人姓名<font class="font_blue">{$data.names}</font> ，就职于智信创富金融信息服务（上海）有限公司（以下简称智信创富）。</p>
        <p class="sp ti2">本人于<font class="font_blue">{$data.date}</font>通过智信创富的居间服务向出借人借款 {$data.loanmoney}元，借款期限七天，借款协议编号为：{$data.order_sn}。</p>
        <p class="sp ti2">为及时还清借款，保持个人的良好信誉，本人郑重做出如下承诺：</p>
        <p class="sp ti2">1、本人保证按照《借款协议》的约定还清所借全部款项。如果本人没有按照约定还清所借全部款项，则本人同意智信创富直接从本人当月应发工资中扣除本人所借全部款项和滞纳金，当月工资不够扣除的，本人仍需承担还款责任，并应于3日内还清所借全部款项和滞纳金。此承诺一经做出，不得撤销。</p>
        <p class="sp ti2">2、本人保证若不按照《借款协议》约定及时归还借款，愿承担一切法律后果及赔偿出借人的所有经济损失（包括但不限于利息、滞纳金、违约金、诉讼费、律师费、差旅费等实现债权的费用）。</p>
        <p>特此承诺！</p> 
        <p class="sp" style="padding: 30px 0;"><font class="font_blue">承诺人(盖章)：</font><notempty name='data.person_img'><span style="background: url(_STATIC_/Upload/eqian_pic/{$data.memberid}.png) no-repeat; width: 30%; height: 70px; background-size:cover;background-position-y:50%;" class="img_seal"></span></notempty></p>        
        <p class="sp" style="padding: 10px 0;"><font class="font_blue">身份证号码：{$data.certiNumber}</font>         
        </p>
        <p class="sp" style="padding: 10px 0;"><font class="font_blue">联系电话：{$data.mobile}</font>         
        </p>
        <p class="sp" style="padding: 10px 0;"><font class="font_blue" style="float: right;">日期：{$data.date}</font>         
        </p>
    </section>
</section>
</body>
</html>
{__NOLAYOUT__}