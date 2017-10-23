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
    <h1>借吧居间服务协议</h1>    
</header>
<section class="content_div">
    <span id="sp1">借吧居间服务协议</span>
    <h2>编号：{$data.order_sn}</h2>
    <section id="contxt">
        <p class="sp">甲方(借款人)：{$member_info.names}</p>
        <p class="sp">身份证号码：{$member_info.certiNumber}</p>
        <p class="sp">手机号码：{$member_info.mobile}</p>
        <p class="sp">乙方（居间方）:智信创富金融信息服务（上海）有限公司（以下简称“智信创富”）</p>
        <p class="sp">住所地:上海市宝山区逸仙路2816号华滋奔腾大厦B座11F</p>
        <p>根据《中华人民共和国合同法》及有关法律法规的规定，甲乙双方在平等自愿、协商一致的基础上，就甲方通过智信创富网络平台“借吧”提供的信息服务及撮合向出借人进行借款，乙方为甲方提供服务事宜达成如下协议，以兹双方共同遵守。</p>
        <p class="sp">一、甲方借款事项</p>
        <p>1.1甲方通过“借吧”撮合借款事项为：向合适的出借人借款，该借款协议编号为:{$data.order_sn}</p>
        <p>1.2甲方拟借款金额：人民币 <notempty name='data.loanmoney'>{$data.loanmoney}<else/>{$_REQUEST['money']}</notempty>元（小写），    <notempty name='data.loanmoney'>{$data.loanmoney|cny}<else/>{$_REQUEST['money']|cny}</notempty>元整（大写）。</p>
        <p>1.3甲方借款期限为7天，借款合同到期日为   <?php if(!empty($data['back_time']))echo date("Y年m月d日",strtotime($data['back_time'])); else echo date("Y年m月d日",strtotime("+7 days")); ?>，具体以借款协议约定为准。</p>
        <p>1.4甲方还款日期及金额可在“借吧”平台页面查询。</p>
        <p class="sp">二、乙方服务内容</p>
        <p>2.1在乙方协助甲方与出借人签订借款协议及相关借款事宜。</p>
        <p>2.2乙方依据借款协议的约定向甲方、出借人等提供居间服务（具体以借款协议约定为准）。</p>      
        <p class="sp">三、平台管理费、手续费收取方式</p>
        <p>3.1 平台管理费收费标准及支付方式</p>
        <p>3.1.1平台管理费定义：作为乙方向甲方提供的借款管理服务的报酬，甲方同意乙方成功撮合借款后向乙方支付平台管理费。平台管理费收费标准为借款总金额的<?php echo $setting['plat_fee'];?>%，即平台管理费总金额为:<?php echo $setting['plat_fee'];?>%，即平台管理费总金额为:<?php if($_REQUEST['money']) echo $_REQUEST['money']*0.01*$setting['plat_fee'];else echo $data['loanmoney']*0.01*$setting['plat_fee'];?>元。</p>      
        <p>3.1.2平台管理费支付方式：甲方同意，在借款协议生效的同时，甲方即不可撤销地授权乙方委托相应的第三方支付机构及监管银行等合作机构，在按照借款协议约定，将出借人提供的借款划转至甲方有关账户时，平台管理费可从借款金额中预先扣除。</p>
        <p>3.2手续费收费标准及支付方式</p>
		<p>	3.2.1手续费定义：作为乙方向甲方提供的第三方支付平台转账、还款服务收取的手续费，手续费收费标准为每笔成功借款支付{$setting.fee}元。</p>
		<p>	3.2.2手续费支付方式：甲方同意，在借款协议生效的同时，甲方即不可撤销地授权乙方委托相应的第三方支付机构及监管银行等合作机构，在按照借款协议约定，将出借人提供的借款划转至甲方有关账户时，手续费可从借款金额中预先扣除。</p>
        <p class="sp">四、甲乙双方的义务</p>
        <p>4.1甲方的义务</p>
        <p>4.1.1甲方承诺根据乙方提供借款服务所需，如实向乙方提供本人个人信息、财产状况、信贷记录、担保记录等,并保证向乙方提供的所有资料是真实、准确、完整和有效的。</p>      
        <p>4.1.2如甲方向乙方提供的资料、信息发生变更，应当在变更后2个工作日内通知乙方，并提供变更后的资料、信息。</p>
        <p>4.1.3在乙方提供借款服务的过程中，甲方须尽可能为乙方工作提供必要的支持和便利。</p>      
        <p>4.1.4与乙方寻找同意向其提供借款的出借人、愿意受出借人委托办理维权事务的第三方签署有关借款协议、抵押协议等，并保证按照借款协议、抵押协议的约定履行其义务。</p>
        <p>4.1.5按照本协议的约定保守商业机密。</p>      
        <p>4.1.6甲方承诺按照本协议约定向乙方支付借款服务费等一切有关费用。</p>
        <p>4.2乙方的义务</p>      
        <p>4.2.1按照本协议的约定为甲方提供借款服务。</p>
		<p>4.2.2按照本协议的约定保守商业秘密。</p> 
		<p class="sp">五、甲乙双方的陈述与保证</p> 
		<p>5.1甲方的陈述与保证</p>      
        <p>5.1.1甲方具有签署并履行本协议的资格和能力。</p>
        <p>5.1.2甲方知悉、理解、同意本协议的全部内容，签署本协议是甲方的真实意思表示。</p>      
        <p>5.1.3本协议构成甲方的合法、有效和有约束力的义务，该义务依本协议之条款可强制执行。</p>
        <p>5.1.4对于出借人的受托人所为的维权行为，甲方保证不以主体不适格为由提出抗辩。</p>      
        <p>5.2乙方的陈述与保证</p>
        <p>5.2.1乙方是按照中国法律合法注册并有效存续的一家公司。</p>      
        <p>5.2.2乙方知悉、理解、同意本协议的全部内容，签署本协议是乙方的真实意思表示。</p>  
        <p>5.2.3本协议构成了乙方的合法、有效和有约束力的义务，该义务依本协议之条款可强制执行。</p> 
        <p class="sp">六、保密</p>   
        <p>6.1双方确认，其就本协议而交换的任何口头或书面资料均属机密资料。每一方均应对所有该等资料予以保密，而在未得到另一方书面同意前，其不得使用或向任何第三方披露任何有关资料，除下列情况外（a）公众知悉或将会知悉该等资料（但这并非由于接受资料之一方向公众披露所致）；（b）适用法律要求披露之资料；（c）乙方成员对该项目进行例行跟踪、审核，或者由任何一方就本协议项下所规定的交易需向其法律顾问或财务顾问披露之材料，而该成员、法律顾问或财务顾问亦需受与本条规定义务相类似之保密义务约束。任何一方或其所雇佣的工作人员或机构对任何保密资料披露均应被视为该第一方对该等保密资料的披露，该一方应对违反本协议承担法律责任。</p>
        <p>6.2为保障出借人基本的知情权，甲方同意乙方选取甲方的部分信息（包括但不限于甲方部分基本信息、情况介绍、征信情况、涉诉情况及部分财务数据）向出借人展示，此等展示不视为违反保密义务。</p>
        <p>6.3双方同意，不论本协议是否更改、废除或终止，本条应继续有效。</p>
        <p class="sp">七、特别约定</p>
        <p>就乙方为甲方和出借人之间借款事项提供的居间服务，甲方在此知悉并确认乙方并非借款人和出借人之间借款法律关系的一方，乙方对于甲方和出借人之间的借款事项不承担任何法律责任。</p>
        <p class="sp">八、违约责任</p>
        <p>一方不履行其在本协议项下义务或者履行本协议项下义务不符合约定的，守约方有权要求违约方按照本协议的约定承担继续履行、采取补救措施或者赔偿损失等违约责任。</p>
        <p class="sp">九、争议解决</p>
        <p>9.1本协议的签订、履行、终止、解释均适用中国法律。</p>
        <p>9.2双方约定，乙方在本协议中写明的手机号码、电子邮件等联系方式，均为各方之间涉诉纠纷相关材料有效送达地址，仲裁委员会可以短信或者电子邮件等形式将文书等送至约定邮箱或者手机号码。双方特别约定在仲裁过程中衢州仲裁委员会可通过短信链接的方式进行仲裁文书电子送达，短信发送即视为送达，短信接收方可点击链接进行查看或登陆衢州仲裁委员会网络仲裁平台（http://odr.qzzcwyh.com）查看。</p>
        <p>9.3如一方需变更联络邮箱或者手机号码的，应当以书面通知并得到其他方确认，否则发送至原约定邮箱或手机号码，发送即视为送达。</p>
        <p>9.4双方同意，凡因执行本协议或本协议有关的一切争议，均提请衢州仲裁委员会仲裁，并适用其网络仲裁规则（包括适用互联网金融仲裁特别规定条款），衢州仲裁委员会的裁决为终局裁决，对双方具有法律约束力。</p>
        <p class="sp">十、其他事项</p>
        <p>10.1如果本协议有一条或多条规定根据任何法律或法规在任何方面被裁定为无效、不合法或不可强制执行，则本协议其余规定的有效性、合法性或可强制执行性不应在任何方面受到影响或损害。双方应通过诚意磋商，争取以法律许可以及双方期望的最大限度内有效的规定取代该等无效、不合法或不可强制执行的规定，而该等有效的规定所产生的经济效果应尽可能与该等无效、不合法或不可强制执行的规定所产生的经济效果相似。</p>
        <p>10.2对本协议作出的任何修改或补充均应为书面形式。双方签署的与本协议有关的修改协议和补充协议应是本协议不可分割的组成部分，并应具有与本协议同等的法律效力。</p>
        <p class="sp">10.3特别提醒：在借款人确认同意本合同之前，借款人应已清楚知悉并充分理解申请借款所带来的法律后果，借款人同意向出借人申请借款。一旦借款人在“借吧”网站或其他与智信创富相关的客户端点击或以其他方式（包括但不限于签字或盖章确认等方式）接受本合同，即意味着借款人已阅读本合同所有条款，并对本合同条款的含义及相应的法律后果已全部通晓并充分理解，同意接受本合同约束并承担相关风险。</p>
	
	
	
        <p class="sp" style="padding: 30px 0;">甲方(盖章)：<notempty name='data.person_img'><span style="background: url(_STATIC_/Upload/eqian_pic/{$data.memberid}.png) no-repeat; width: 30%; height: 70px; background-size:cover;background-position-y:50%;" class="img_seal"></span></notempty></p>        
        <p class="sp" style="padding: 10px 0;"><font>乙方(盖章)：</font>          
            <img src="_STATIC_/Upload/eqian_pic/company-logo.png" class="img_seal">
        </p>
        <p class="sp" style="float: right;">日期:<?php if(!empty($data['pay_time'])) echo date("Y年m月d日",strtotime($data['pay_time']));else echo date("Y年m月d日"); ?></p>
        </section>     
</section>
</body>
</html>
{__NOLAYOUT__}