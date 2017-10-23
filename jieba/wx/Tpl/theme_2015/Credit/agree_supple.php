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
        .sp{color: #333333; font-weight: bold; margin: 10px 0;}
        .content_div h2{margin:0 auto; font-weight: normal; font-size: 14px; text-align: right; width: 96%;}
        .img_seal{width: 30%; display: inline-block; vertical-align: middle;}
        .sp font{display: inline-block;}|
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
<header  class="headers" style="display: none;">
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>补充协议</h1>    
</header>
<section class="content_div">
    <span id="sp1">补充协议</span>
    <h2>编号：{$data.order_sn}</h2>
    <section id="contxt">
        <p class="sp">甲方：{$member_info.names}</p>
        <p class="sp">身份证号码：{$member_info.certiNumber}</p>
        <p class="sp">乙方: 智信创富金融信息服务（上海）有限公司（以下简称“智信创富”）</p>
        <p class="sp">甲方是乙方的汽车金融信息服务稳定客户，为更好服务存量客户，并维持长期良好客户关系，同时为公司长远发展考虑，实现共赢，乙方根据甲方的需求，愿意为甲方提供增值服务，就此补充约定如下：</p>
		<p class="sp">
			1、甲方在日常生产和生活中有短期的资金需求时，甲方可以选择向乙方求助，为其融通资金。<br>
		    2、甲方作为合格、稳定的乙方客户，须满足是乙方为其提供服务超过三个月，未能达到三个月的客户，不视为合格的客户，不享受增值服务。<br>
		    3、在选择乙方帮助时，乙方应积极寻找愿意提供资金的借款人，按照甲方的具体需求，通过乙方的资源和能力，进行风控审核，撮合居间，寻找合适匹配的出借款人，为甲方完成借款。<br>
		    4、甲方须通过乙方借吧平台的车友贷项目，按照车友贷的项目流程进行借款申请，甲方借款的条件、数额、期限以及出借人的条件和要求，以双方签订《借款协议》内容确定权利义务内容为准。<br>
		    5、甲方声明，选择乙方提供居间撮合是基于乙方的服务质量、专业能力、可靠的资源，签署《借款协议》后，将严格按照协议约定履行。<br>
			6、乙方声明，充分利用自身优势和专业服务能力，遵循合法合规原则，为甲方提供增值服务。
		</p>
			
	
        <p class="sp" style="padding: 30px 0;">甲方(盖章)：<notempty name='data.person_img'><span style="background: url(_STATIC_/Upload/eqian_pic/{$data.memberid}.png) no-repeat; width: 30%; height: 70px; background-size:cover;background-position-y:50%;" class="img_seal"></span></notempty></p>        
        <p class="sp" style="padding: 10px 0;"><font>乙方(盖章)：</font>          
            <img src="_STATIC_/Upload/eqian_pic/company-logo.png" class="img_seal">
        </p>
        <p class="sp" style="float: right;">日期:<?php if(!empty($data['pay_time'])) echo date("Y年m月d日",strtotime($data['pay_time'])); ?></p>
        </section>     
</section>
</body>
</html>
{__NOLAYOUT__}