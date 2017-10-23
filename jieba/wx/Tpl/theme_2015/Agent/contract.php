<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>    
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
	<a href="/member/account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>借吧“金牌经纪人”服务合同</h1>    
</header>
<section class="content_div">
    <span id="sp1">借吧“金牌经纪人”服务合同</span>
    <h2>编号：{$data.order_sn}</h2>
    <section id="contxt">
        <p class="sp">甲方:{$member_info.names}</p>
        <p class="sp">身份证号码：{$member_info.certiNumber}</p>
        <p class="sp">手机号码:{$member_info.mobile}</p>
        <p class="sp">乙方:上海峡谷科技有限公司</p>
        <p>经友好协商，甲乙双方达成如下协议：</p>
        <p class="sp">（一）服务内容及各方权利义务：</p>
        <p>1. 甲方通过注册成为乙方借吧“金牌经纪人”会员，可以享有在乙方借吧“金牌经纪人”模块下提供的转出客户借款申请、受让客户借款申请的居间服务。</p>
        <p>2. 按乙方要求，甲方需在注册时向乙方提供甲方的身份证原件扫描件、注册手机号码、工牌扫描件等真实身份信息。如需转出客户借款申请的，还需及时向乙方提供借款客户的借款金额、借款期限、客户车辆信息等借款真实基本信息（具体详见乙方借吧“金牌经纪人”资料上传要求），如甲方无法及时提供相关信息而导致转出客户借款申请不能及时发布，乙方将不承担任何责任。</p>
        <p>3. 甲方注册成为乙方会员后，可在乙方借吧“金牌经纪人”模块下进行客户借款申请转出搜索，寻找到合适的“转出客户借款申请”，并点击确认受让该“转出客户借款申请”后，即可获得发布该“转出客户借款申请”的其他乙方会员的信息，甲方与该其他乙方会员对该“转出客户借款申请”的单子进行沟通，对转出、受的让方式及佣金分成等具体内容由甲方与该其他乙方会员自行另行签订协议约定，乙方对甲方与该其他乙方会员自行另行签订的协议内容不参与、不保证、不审核、不担责，因此发生的任何纠纷由甲方与该其他乙方会员自行解决。</p>
        <p>4. 甲方应保证所提供信息的真实、有效、合法性。其中，甲方提供的转出客户借款申请信息为（1）虚假、夸大、伪造等不真实信息的；（2）发布广告或宣传内容的；（3）涉及或使用无第三方书面授权的商标、商号、品牌等或包含工商、公安等相关部门禁止性、限制性内容的；（4）与客户借款申请转出无关的其他任意信息的。若甲方发布的信息有任意以上内容，首次发布违规内容的，乙方通过向甲方注册手机发送警告短信并在乙方“借吧”上进行公示的方式警告，经乙方通知后拒不改正或再次发布违规内容的，乙方将通过对甲方的账号封号并在乙方“借吧”上进行公示的方式停止提供服务，同时视作甲方提前终止本合同，且放弃本合同中未享之权利。</p>
        <p>5. 甲方须保证发布的“转出客户借款申请”中的内容不侵犯任何第三方的知识产权和其他合法权益，否则，由此造成对方的任何损失，均由甲方承担全部赔偿责任。</p>
        <p>6. 任何一方不得将对方的信息用作任何除本合同目的以外的其他商业目的，但为增加转出、受让的居间成功率，乙方将甲方发布的转出客户借款申请内的信息用于与乙方合作的媒体上除外。</p>
        <p>7. 甲方注册成为乙方会员之后，即有权在乙方借吧“金牌经纪人”模块下进行客户借款申请转出搜索、发布转出客户借款申请，受让客户借款申请等，但禁止利用此项权利进行与转出、受让客户借款申请以外的其他用途，否则，因此对他人造成的任何损失，由甲方承担全部责任。          
        </p>
        <p class="sp">（二）费用及付款：</p>
        <p>1. 甲方在注册成为乙方会员时，需向乙方支付终身会员费人民币{$agent_setting.pay_money}元。</p>
        <p>2. 甲方在乙方借吧“金牌经纪人”模块下搜索到合适的“转出客户借款申请”，并点击确认受转让时，需另外向乙方支付受让费用{$agent_setting.unlock_money}元/单。若甲方发现其受让的“转出客户借款申请”内容虚假，则需在付款后的24小时内向乙方投诉，乙方核实后，将向甲方返还确认受让的费用{$agent_setting.unlock_money}元/单。</p>      
        <p>3. 甲方在乙方借吧“金牌经纪人”模块下发布的“转出客户借款申请”若有其他乙方会员点击确认受转让时，乙方需向甲方支付转出成功费用<?php echo (floatval($agent_setting['unlock_money']))/2;?>元/单。</p>
        <p>4. 若甲方未能按照合同约定及时支付上述费用，将使甲方不能正常使用乙方所提供的服务，且乙方有权随时终止本合同。</p>      
        <p class="sp">（三）争议的解决：</p>
        <p>本合同履行过程中如发生争议，应友好协商解决，协商不成，双方同意向上海仲裁委员会提起仲裁。</p>      
        <p class="sp">注：甲方一旦在借吧客户端点击接受本合同，即意味着甲方已阅读本合同所有条款，并对本合同条款的含义及相应的法律后果已全部通晓并充分理解，同意接受本合同约束。</p>	
	
	
        <p class="sp" style="padding: 30px 0;">甲方(盖章)：<notempty name='data.person_img'><span style="background: url(_STATIC_/Upload/eqian_pic/{$data.memberid}.png) no-repeat; width: 30%; height: 70px; background-size:cover;background-position-y:50%;" class="img_seal"></span></notempty></p>        
        <p class="sp" style="padding: 10px 0;"><font>乙方(盖章)：</font>          
            <img src="_STATIC_/Upload/eqian_pic/xiagu_logo.png" class="img_seal">
        </p>
        <p class="sp" style="float: right;">日期:<?php if(empty($data['timeadd'])) echo date("Y年m月d日",time()); ?></p>
        </section>     
</section>
</body>
</html>
{__NOLAYOUT__}