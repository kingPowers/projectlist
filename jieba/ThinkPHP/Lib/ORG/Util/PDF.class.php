<?php
class PDF
{
   static public function makenew( $data,$dest='F'){
   	$date = date("Y-m-d");
$domainRoot = DOMAIN_ROOT;
Vendor('tcpdf.zhifu');
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('借吧');
$pdf->SetTitle("出借人咨询服务协议");
$pdf->SetSubject("出借人咨询服务协议");
$pdf->SetKeywords("出借人咨询服务协议");
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('cid0cs','',12);
$html = <<<EOD
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
    <title>借款协议</title>
    <style type="text/css">
        #sp1{text-align: center; display: block; margin: 0 auto; height: 40px; line-height: 40px;}
        #contxt{font-size: 12px; width: 96%; margin: 0 auto; padding-bottom: 20px;}
        #contxt p{line-height: 20px;}
        .sp{color: #000000; font-weight: bold; margin: 10px 0;}
        .content_div h2{margin:0 auto; font-weight: normal; font-size: 16px; text-align: right; width: 96%;}
    </style>
</head>
<body style="background: #fff;">
<header>
    <h1 style="text-align:center;">借款协议</h1>
</header>
<section class="content_div">
    <p style="text-align:right;color:red;">编号：{$data['order_sn']}</p>
    <section id="contxt">
        <p class="sp">第一部分：借款明细</p>
        <p>甲方（出借人）:李建富</p>
        <p>身份证号:320923198412254539</p>
        <p>乙方（借款人）:{$data['names']}</p>
        <p>身份证号:{$data['certiNumber']}</p>
        <p>手机号码：{$data['mobile']}</p>
        <p>丙方（信息服务方）：智信创富金融信息服务(上海)有限公司（以下简称“智信创富”）</p>
        <p>借款基本信息</p>
        <p>借款金额    {$data['loanmoney']}元</p>
        <p>借款期限    7天</p>
        <p>周利率 0.6%</p>
        <p>借款开始日   {$data['pay_time']}</p>
        <p>借款到期日   {$data['back_time']}</p>
        <p>还款方式    先息后本</p>
        <p>还款日 自借款出借之日起第7日（如遇节假日，不顺延）</p>
        <p>合同签订地:上海市宝山区</p>
        <p class="sp">第二部分：借款通用条款</p>
        <p class="sp">一、借款协议</p>
        <p>1.《借款协议》（“本协议”）由两部分组成：第一部分为“借款明细”，该明细中所列借款利率等内容会根据第二部分的相关内容做相应的调整；第二部分为“借款通用条款”。</p>
        <p>2.借吧是指智信创富金融信息服务(上海)有限公司负责运营的，为借款客户提供借款信息的网络信息中介平台。</p>
        <p>3.宝付是指宝付网络科技（上海）有限公司，为根据中国法律成立的，具有支付业务许可证的提供代收款、代付款及相关支付服务的平台服务商。</p>
        <p>4.借款人是指“借款明细”中列明的借款人，为符合中华人民共和国法律（“中国法律”，仅为本协议之目的，不包括香港特别行政区、澳门特别行政区和台湾省的法律法规）规定的具有完全民事权利能力和民事行为能力，独立行使和承担本协议项下权利义务的自然人。借款人为“智信创富金融信息服务（上海）有限公司”（即“借吧”）的注册用户。</p>
        <p>5.出借人是指“借款明细”中列明的出借人，为符合中国法律规定的具有完全民事权利能力和民事行为能力，独立承担本协议项下权利义务的自然人。</p>
        <p> 6.信息服务方是指“借款明细”中列明的信息服务公司，为根据中国法律成立的、提供金融信息咨询及相关服务的居间平台服务商。</p>
        <p>7.本协议项下借款人、出借人和居间平台单独称“一方”，合称“各方”。</p>
        <p class="sp">二、借款</p>
        <p>1.借款金额是指“借款明细”中列明的借款本金金额。借款币种为人民币。</p>
        <p>2.借款期限是指自丙方交易撮合成功日起至最终到期日（全部借款到期日）止的期间。</p>
        <p>3.借款利率是指“借款明细”中列明的借款利率。</p>
        <p>4.借款开始日是指“借款明细”中列明的借款开始日，为出借人之交易撮合成功日。</p>
        <p>5.出借人通过在“借吧”注册同时在宝付上绑定银行卡，将本合同第一部分“借款明细”中约定的出借资金划转至丙方结算账户，并委托丙方将出借资金全额划转至借款人在“借吧”注册同时在宝付上绑定的收款银行账户，划转完毕即视为交易撮合。</p>
        <p class="sp">三、还款</p>
        <p>1.借款人应承担如下还款义务：</p>
        <p>（1）借款人应按时足额向出借人支付本金和利息；</p>
        <p>（2）如发生逾期还款，借款人需按本协议约定向出借人支付逾期滞纳金；</p>
        <p>（3）如发生提前还款，借款人需按本协议约定向出借人支付全部本金。</p>
        <p>借款人应归还的上述款项统称为“应付款项”。</p>
        <p>2.本协议项下借款的还款方式：先息后本。</p>
        <p>3.借款人应在还款日支付当期应付款项。</p>
        <p>4.借款人应保证绑定宝付的银行账户付款功能未受限制，并在每个还款日24:00（北京时间，下同）之前，将应还本金足额存入该银行账户，并点击“还款”选项，且借款人同意智信创富通过宝付系统在还款日自借款人绑定宝付的银行账户中划转应还本金至出借人绑定宝付的银行账户和/或相应费用（如有）至智信创富绑定宝付的银行账户。一旦借款人绑定宝付的银行账户付款功能受到限制，借款人应及时通知智信创富变更还款账户，以免逾期。借款人的还款时间以出借人绑定宝付的银行账户实际收到借款人还款的时间为准，还款金额以出借人绑定宝付的银行账户实际收到借款人的还款金额为准。</p>
        <p>5.借款人不可撤销地授权智信创富委托宝付支付机构于还款日从借款人绑定宝付的银行账户中将借款人的还款资金划转至出借人绑定宝付的银行账户；当借款人绑定宝付的银行账户中的资金余额不足时，智信创富有权对借款人绑定宝付的银行账户的余额进行全额扣款或视为委托还款不成功。为避免委托还款不成功，借款人应至少在还款日前一日，将资金存入绑定宝付的银行账户中；因借款人未足额存入资金导致委托还款不成功的，后果由借款人自行承担。</p>
        <p class="sp">四、逾期还款</p>
        <p>1.还款日24点前，借款人未足额支付应付款项的，则视为逾期。</p>
        <p>2.{$data['delay_text']}</p>
        <p>3.借款人在发生逾期后又进行还款的，如借款人的还款金额不足以足额清偿全部到期应付款项的，借款人应按如下顺序支付应付款项：当借款人仅有一期应付款项未按时全额支付时，其还款资金支付顺序依次为：A. 截止到该还款日的逾期罚息； B. 本金。</p>
        <p>4.借款人不可撤销地授权宝付支付平台在借款人未能按时足额支付应付款项的情况下，在借款人全部到期应付款项的范围内，随时划扣借款人绑定宝付的银行账户中的资金用于归还借款人到期应付款项，该等划扣无需借款人另行同意。</p>
        <p>5.若借款人任何一期应付款项逾期的，经催讨后依然未还的，借款人明确理解并同意智信创富有权向公众披露其逾期情况和相关信息，包括但不限于姓名、性别、身份证号（生日部分以星号代替）、剩余未还本金、逾期天数等信息，对因披露前述信息可能造成的损失，智信创富不承担任何责任。</p>
        6.若因借款人逾期而出借人提起诉讼的，则借款人需承担相应的实现债权的费用，包括但不限于诉讼费、律师费、公告费等。</p>
        <p class="sp">五、提前还款</p>
        <p>1.借款人可通过“借吧”向出借人申请提前偿还全部应付款项。借款人不得提前偿还部分应付款项。</p>
        <p>2.借款人提前还款的，应事先在绑定宝付的银行账户中存入全额借款本金。</p></p>
        <p class="sp">六、承诺及保证</p>
        <p>1.借款人和出借人各自在此确认为具有完全民事权利能力和完全民事行为能力的自然人，有权签订并履行本协议。</p>
        <p>2.出借人保证：出借人向借款人提供的资金来源均合法。</p>
        <p>3.借款人应根据智信创富的要求如实向出借人、智信创富提供个人情况（包括但不限于姓名、身份证号、学历、联系方式、联系地址、职业信息、联系人信息等）以及借款用途等相关信息。借款人承诺并保证其向出借人、智信创富提供的所有信息均为真实、完整和有效的。</p>
        <p>4.借款人承诺：如发生任何影响或者可能影响借款人经济状况、信用状况、还款能力的事由，包括但不限于借款人的工作单位、职位、工作地点、薪酬等事项的变化，借款人应于前述变更发生之日起2个工作日内通知智信创富。</p>
        <p>5.借款人承诺根据本协议列明的借款用途使用借款资金，并保证不挪用借款资金或将借款资金用于以下目的和用途：</p>
        <p>（1）以任何形式进入证券市场，或用于股本权益性投资；</p>
        <p>（2）用于房地产项目开发；</p>
        <p>（3）用于国家明令禁止或限制的经营活动。</p>
        <p>6.各方承诺，各方不会利用“借吧”进行信用卡套现、洗钱、非法集资或其他不正当交易行为，否则应依法独立承担法律责任。</p>
        <p>7.各方确认，借款人和出借人授权和委托智信创富根据本协议所采取的全部行动和措施的法律后果均归属于借款人和出借人本人。</p>
         {$data['contract_str']}
        <p class="sp">七、违约</p>
        <p>1.借款人违反本协议“借款通用条款”第六条第3款约定的，或未经出借人同意，擅自转让本协议借款债务的，该转让行为对出借人不产生法律效力。</p>
        <p>2.发生下列任何一项或几项情形的，视为借款人违约：</p>
        <p>（1）借款人违反其在本协议所做的任何承诺和保证的；</p>
        <p>（2）借款人的任何财产遭受没收、征用、查封、扣押、冻结等可能影响其履约能力的不利事件，且不能及时提供有效补救措施的；</p>
        <p>（3）借款人的财务状况出现影响其履约能力的不利变化，且不能及时提供有效补救措施的。</p>
        <p>3.若借款人违约或出借人合理判断借款人可能发生违约事件的，出借人（委托智信创富）有权采取下列任何一项或几项救济措施：</p>
        <p>（1）立即暂缓、取消发放全部或部分借款；</p>
        <p>（2）宣布已发放借款全部提前到期，借款人应立即偿还所有应付款项；</p>
        <p>（3）解除本协议；</p>
        <p>（4）采取法律、法规以及本协议约定的其他救济措施。</p>
        <p class="sp">八、保密条款</p>
        <p>1.本协议签署后, 除非事先得到另两方的书面同意, 本协议各方均应承担以下保密义务：</p>
        <p>（1）任何一方不得向非本协议方披露本协议以及本协议项下的事宜以及与此等事宜有关的任何文件、资料或信息（“保密信息”）；</p>
        <p>（2）任何一方只能将保密信息和其内容用于本协议项下的目的, 不得用于任何其他目的。本款的约定不适用于下列保密信息：</p>
        <p>A.从披露方获得时，已是公开的；</p>
        <p>B.从披露方获得前，接受方已经获知的；</p>
        <p>C.从有正当权限并不受保密义务制约的第三方获得的；</p>
        <p>D.非依靠披露方披露或提供的信息独自开发的。</p>
        <p>2.本协议各方因下列原因披露保密信息，不受前款的限制：</p>
        <p>（1）向本协议各方的董事、监事、高级管理人员和雇员及其聘请的会计师、律师、咨询公司披露；</p>
        <p>（2）因遵循可适用的法律、法规的强制性规定而披露；</p>
        <p>（3）依据其他应遵守的法规向审批机构和/或权力机构进行的披露。</p>
        <p>3.借款人和出借人提供给智信创富和宝付支付平台的信息及借款人和出借人享受智信创富或宝付支付平台服务产生的信息（包括本协议签署之前提供和产生的），可由智信创富和宝付支付平台共享，法律禁止的除外。</p>
        <p>4.本协议任何一方应采取所有其他必要、适当和可以采取的措施，以确保保密信息的保密性。</p>
        <p>5.本协议各方应促使其向之披露保密信息的人严格遵守本条约定。</p>
        <p>6.各方在本条项下的权利和义务应在本协议终止或到期后继续有效。</p>
        <p class="sp">九、通知</p>
        <p>1.本协议任何一方根据本协议约定做出的通知和/或文件均应以书面形式做出，可由专人送达、挂号邮递、特快专递或通过“借吧”发布等方式传送。</p>
        <p>2.通知在下列日期视为送达：</p>
        <p>（1）专人递送的通知，在专人递送之交付日为有效送达；</p>
        <p>（2）以挂号信（付清邮资）发出的通知，在寄出（以邮戳为凭）后的五（5）个工作日内为有效送达；</p>
        <p>（3）以特快专递（付清邮资）发出的通知，在寄出（以邮戳为凭）后的三个（3）工作日内为有效送达；</p>
        <p>（4）以传真或电子邮件发出的通知，在发出之日为有效送达；</p>
        <p>（5）通过“借吧”发布的方式通知的，在“借吧”发布之日为有效送达。</p>
        <p>3．三方约定，乙方在本协议中写明的手机号码、电子邮件等联系方式，均为各方之间涉诉纠纷相关材料有效送达地址，仲裁委员会可以短信或者电子邮件等形式将文书送至约定邮箱或者手机号码。三方特别约定在仲裁过程中衢州仲裁委员会可通过短信链接的方式进行仲裁文书电子送达，短信发送即视为送达，短信接收方可点击链接进行查看或登陆衢州仲裁委员会网络仲裁平台（http://odr.qzzcwyh.com）查看。</p>
        <P>4．如一方需变更联络邮箱或者手机号码的，应当以书面通知并得到其他方确认，否则发送至原约定邮箱或手机号码，发送即视为送达。</p>
        <p class="sp">十、法律适用和管辖</p>
        本协议的签订、履行、终止、解释均适用中国法律。各方同意，凡因执行本协议或本协议有关的一切争议，均提请衢州仲裁委员会仲裁，并适用其网络仲裁规则（包括适用互联网金融仲裁特别规定条款），衢州仲裁委员会的裁决为终局裁决，对各方具有法律约束力。</p>
        <p class="sp">十一、其他</p>
        <p>借款人和出借人均同意并确认，借款人和出借人通过其绑定宝付的银行账户采取的行为所产生的法律效果和法律责任归属于借款人和出借人本身；借款人和出借人授权和委托智信创富和宝付支付平台根据本协议所采取的全部行动和措施的法律后果均归属于借款人和出借人本身，与智信创富或宝付支付平台无关，智信创富或宝付支付平台也不因此承担任何责任。借款人和出借人同意，智信创富有权就其为借款人和出借人提供的平台服务收取服务费。</p>
        <p>2.本协议中部分条款根据相关法律法规等的规定成为无效，或部分无效时，该等无效不影响本协议项下其他条款的效力，各方仍应履行其在本协议项下的义务。</p>
        <p>3.本协议项下的附件和补充协议构成本协议不可分割的一部分。</p>
        <p class="sp">4. 特别提醒：在出借人、借款人确认同意本合同之前，出借人、借款人应已清楚知悉并充分理解出借、借款所带来的法律后果，出借人同意向借款人出借资金，借款人同意向出借人申请借款。一旦出借人、借款人在“借吧”网站或其他与智信创富相关的客户端点击或以其他方式（包括但不限于签字或盖章确认等方式）接受本合同，即意味着出借人、借款人已阅读本合同所有条款，并对本合同条款的含义及相应的法律后果已全部通晓并充分理解，同意接受本合同约束并承担相关风险。</p>
        <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
         <p class="sp">甲方(盖章)：</p>
         <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
        <p class="sp">乙方(盖章)：</p>
        <h>&nbsp;&nbsp; </h><h>&nbsp;&nbsp; </h><h>&nbsp;&nbsp; </h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
        <p class="sp">丙方(盖章)：</p>
        <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
        <p class="sp" style="text-align:right;">日期:{$date}</p>
        
    </section>
</section>
</body>
</html>
EOD;
$pdf->AddPage();
$pdf->writeHTML($html, true, false, true, false, '');
//$pdf->Image(K_PATH_IMAGES.'company-logo.png', 145, 200, 50);
$filename = "{$data['order_sn']}_1.pdf";
$pdf->lastPage();
$pdf->Output($filename,$dest);
return $filename;
	}

//生成第二个pdf
static public function makenew1( $data,$dest='F'){
		$date = date("Y-m-d");
		//服务费
    	$fee = 25;//round($data['loanmoney']*2*0.01,2);
    	//平台服务费
    	$plat_fee = round($data['loanmoney']*0.01*$data['rate'],2);
		$domainRoot = DOMAIN_ROOT;
		Vendor('tcpdf.zhifu');
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('借吧');
		$pdf->SetTitle("借吧居间服务协议");
		$pdf->SetSubject("借吧居间服务协议");
		$pdf->SetKeywords("借吧居间服务协议");
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('cid0cs','',12);
		$html = <<<EOD

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="http://wxst.lingqianzaixian.com/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="http://wxst.lingqianzaixian.com/2015/css/yydai/style.css">
    <script type="text/javascript" src="http://wxst.lingqianzaixian.com/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>借吧</title>
    <style type="text/css">
        #sp1{text-align: center; display: block; margin: 0 auto; height: 40px; line-height: 40px;}
        #contxt{font-size: 12px; width: 96%; margin: 0 auto; padding-bottom: 20px;}
        #contxt p{line-height: 20px;}
        .sp{color: #000000; font-weight: bold; margin: 10px 0;}
        .content_div h2{margin:0 auto; font-weight: normal; font-size: 14px; text-align: right; width: 96%;}
    </style>   
</head>
<body style="background: #fff;">
<section class="content_div">
    <h2 id="sp1" style="text-align:center;">借吧居间服务协议</h2>
    <p style="text-align:right;color:red;">编号：{$data['order_sn']}</p>
    <section id="contxt">
        <p class="sp"><b>甲方(借款人)：</b>{$data['names']}</p>
        <p class="sp"><b>身份证号码：</b>{$data['certiNumber']}</p>
        <p class="sp"><b>手机号码：</b>{$data['mobile']}</p>
        <p class="sp"><b>乙方（居间方）:</b>智信创富金融信息服务（上海）有限公司（以下简称“智信创富”）</p>
        <p class="sp">住所地:上海市宝山区逸仙路2816号华滋奔腾大厦B座11F</p>
        <p>根据《中华人民共和国合同法》及有关法律法规的规定，甲乙双方在平等自愿、协商一致的基础上，就甲方通过智信创富网络平台“借吧”提供的信息服务及撮合向出借人进行借款，乙方为甲方提供服务事宜达成如下协议，以兹双方共同遵守。</p>
        <strong>一、甲方借款事项</strong>
        <p>1.1甲方通过“借吧”撮合借款事项为：向合适的出借人借款，该借款协议编号为{$data['order_sn']}  </p>
        <p>1.2甲方拟借款金额：人民币 {$data['loanmoney']}元（小写），   {$data['big_loanmoney']}（大写）。</p>
        <p>1.3甲方借款期限为7天，借款合同到期日为 {$data['back_time']}，具体以借款协议约定为准。</p>
        <p>1.4甲方还款日期及金额可在“借吧”平台页面查询。</p>
        <p class="sp">二、乙方服务内容</p>
        <p>2.1在乙方协助甲方与出借人签订借款协议及相关借款事宜。</p>
        <p>2.2乙方依据借款协议的约定向甲方、出借人等提供居间服务（具体以借款协议约定为准）。</p>      
        <p class="sp">三、服务费收取方式</p>
        <p>3.1 平台管理费收费标准及支付方式</p>
		<p>3.1.1平台管理费定义：作为乙方向甲方提供的借款管理服务的报酬，甲方同意乙方成功撮合借款后向乙方支付平台管理费。平台管理费收费标准为借款总金额的{$data['rate']}%，即平台管理费总金额为：{$setting["plat_fee_money"]}元。</p>
		<p>3.1.2平台管理费支付方式：甲方同意，在借款协议生效的同时，甲方即不可撤销地授权乙方委托相应的第三方支付机构及监管银行等合作机构，在按照借款协议约定，将出借人提供的借款划转至甲方有关账户时，平台管理费可从借款金额中预先扣除。</p>
		<p>3.2手续费收费标准及支付方式</p>
		<p>3.2.1手续费定义：作为乙方向甲方提供的第三方支付平台转账、还款服务收取的手续费，手续费收费标准为每笔成功借款支付{$setting["fee"]}元。</p>
		<p>3.2.2手续费支付方式：甲方同意，在借款协议生效的同时，甲方即不可撤销地授权乙方委托相应的第三方支付机构及监管银行等合作机构，在按照借款协议约定，将出借人提供的借款划转至甲方有关账户时，手续费可从借款金额中预先扣除。</p>
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
        <p>9.3．如一方需变更联络邮箱或者手机号码的，应当以书面通知并得到其他方确认，否则发送至原约定邮箱或手机号码，发送即视为送达。</p>
        <p>9.4双方同意，凡因执行本协议或本协议有关的一切争议，均提请衢州仲裁委员会仲裁，并适用其网络仲裁规则（包括适用互联网金融仲裁特别规定条款），衢州仲裁委员会的裁决为终局裁决，对双方具有法律约束力。</p>
        <p class="sp">十、其他事项</p>
        <p>10.1如果本协议有一条或多条规定根据任何法律或法规在任何方面被裁定为无效、不合法或不可强制执行，则本协议其余规定的有效性、合法性或可强制执行性不应在任何方面受到影响或损害。双方应通过诚意磋商，争取以法律许可以及双方期望的最大限度内有效的规定取代该等无效、不合法或不可强制执行的规定，而该等有效的规定所产生的经济效果应尽可能与该等无效、不合法或不可强制执行的规定所产生的经济效果相似。</p>
        <p>10.2对本协议作出的任何修改或补充均应为书面形式。双方签署的与本协议有关的修改协议和补充协议应是本协议不可分割的组成部分，并应具有与本协议同等的法律效力。</p>
        <p class="sp">10.3特别提醒：在借款人确认同意本合同之前，借款人应已清楚知悉并充分理解申请借款所带来的法律后果，借款人同意向出借人申请借款。一旦借款人在“借吧”网站或其他与智信创富相关的客户端点击或以其他方式（包括但不限于签字或盖章确认等方式）接受本合同，即意味着借款人已阅读本合同所有条款，并对本合同条款的含义及相应的法律后果已全部通晓并充分理解，同意接受本合同约束并承担相关风险。</p>
         <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
         <p class="sp" style="padding: 30px 0;">甲方(盖章)：</p>
          <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
          <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> 
          <p>&nbsp;</p><h2>&nbsp;&nbsp;</h2><h3>&nbsp;&nbsp;</h3>
        <p class="sp" style="padding: 30px 0;">乙方(盖章)：</p>  
         <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
        <p class="sp" style="text-align:right;">日期:{$date}</p> 
        
</section>
</body>
</html>
EOD;
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		//$pdf->Image(K_PATH_IMAGES.'company-logo.png', 145, 200, 50);
		$filename = "{$data['order_sn']}_2.pdf";
		$pdf->lastPage();
		$pdf->Output($filename,$dest);
		return $filename;
	}
	
	/*
	 * 生成第三个pdf--还款承诺书
	 *   @parm  order_id 订单id
	 * */
	static public function makenew2($order_id,$dest='F'){
		$date = date("Y-m-d");
		$data = M()->table('`order` o,order_credit c,member m,member_info i')
				   ->join(" staff s on s.certiNumber=i.certiNumber")
				   ->where("o.id='{$order_id}' and o.id=c.order_id and  o.memberid=i.memberid and i.memberid=m.id ")
				   ->field("o.names,date_format(o.timeadd,'%Y年%m月%d日') as date,o.loanmoney,c.order_sn,m.mobile,i.certiNumber,if(s.id,'1','0') as is_staff")
				   ->find();
		if(false==$data || $data['is_staff']==0)return false;
		
		$domainRoot = DOMAIN_ROOT;
		Vendor('tcpdf.zhifu');
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('借吧');
		$pdf->SetTitle("还款承诺书");
		$pdf->SetSubject("还款承诺书");
		$pdf->SetKeywords("还款承诺书");
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('cid0cs','',12);
		$html = <<<EOD
	<html>
		<meta charset="utf-8" />
	    <meta content="telephone=no,email=no" name="format-detection">
	    <meta content="yes" name="apple-touch-fullscreen">
	    <meta content="yes" name="apple-mobile-web-app-capable">
	<body style="background: #fff;">
	<header>
	    <h1 style="text-align:center;">还款承诺书</h1>    
	</header>
	<section class="content_div">
	    <span id="sp1" style="text-align:center;">还款承诺书</span>    
	    <section id="contxt">
	        <p class="sp">本人姓名：{$data['names']}，就职于智信创富金融信息服务（上海）有限公司（以下简称智信创富）。</p>
	        <p class="sp">本人于{$data['date']}通过智信创富的居间服务向出借人借款{$data['loanmoney']}元，借款期限七天，借款协议编号为:{$data['order_sn']}。</p>
	        <p class="sp">为及时还清借款，保持个人的良好信誉，本人郑重做出如下承诺：</p>
	        <p class="sp">1、本人保证按照《借款协议》的约定还清所借全部款项。如果本人没有按照约定还清所借全部款项，则本人同意智信创富直接从本人当月应发工资中扣除本人所借全部款项和滞纳金，当月工资不够扣除的，本人仍需承担还款责任，并应于3日内还清所借全部款项和滞纳金。此承诺一经做出，不得撤销。</p>
	        <p class="sp">2、本人保证若不按照《借款协议》约定及时归还借款，愿承担一切法律后果及赔偿出借人的所有经济损失（包括但不限于利息、滞纳金、违约金、诉讼费、律师费、差旅费等实现债权的费用）。</p>
	        <p>特此承诺！</p> 
	        <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>
	        <p class="sp" style="padding: 30px 0;">承诺人(盖章)：</p>    
	        <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h>    
	        <p class="sp" style="padding: 10px 0;"><font>身份证号码：{$data['certiNumber']}</font>         
	        </p>
	        <p class="sp" style="padding: 10px 0;"><font>联系电话：{$data['mobile']}</font>         
	        </p>
	        <p class="sp" style="padding: 10px 0;text-align:right;"><font>日期：{$date}</font>         
	        </p>
	</section>
	</body>
	</html>
EOD;
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');
			//$pdf->Image(K_PATH_IMAGES.'company-logo.png', 145, 200, 50);
			$filename = "{$data['order_sn']}_3.pdf";
			$pdf->lastPage();
			$pdf->Output($filename,$dest);
			return $filename;
	}
	
	//生成现金贷补充协议
	static public function makenew3( $order_id,$dest='F'){
            $date = date("Y-m-d");
            $data = M()->table('`order` o,order_credit c,member m,member_info i')
                        ->join(" staff s on s.certiNumber=i.certiNumber")
                        ->where("o.id='{$order_id}' and o.id=c.order_id and  o.memberid=i.memberid and i.memberid=m.id ")
                        ->field("o.names,o.memberid,date_format(o.timeadd,'%Y年%m月%d日') as date,o.loanmoney,c.order_sn,m.mobile,i.certiNumber,if(s.id,'1','0') as is_staff")
                        ->find();
                $member_info = M('member_info')->where("memberid='{$data['memberid']}'")->find();
		
		$domainRoot = DOMAIN_ROOT;
		Vendor('tcpdf.zhifu');
		$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('借吧');
		$pdf->SetTitle("补充协议");
		$pdf->SetSubject("补充协议");
		$pdf->SetKeywords("补充协议");
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->SetFont('cid0cs','',12);
		$html = <<<EOD
<body style="background: #fff;">
<section class="content_div">
    <h1 id="sp1"  style="text-align:center;">补充协议</h1>
    <p class="sp" style="float: right;text-align:right;">编号：{$data['order_sn']}</p>
    <section id="contxt">
        <p class="sp">甲方：{$member_info["names"]}</p>
        <p class="sp">身份证号码：{$member_info["certiNumber"]}</p>
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
			
	<h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h>
        <p class="sp" style="padding: 30px 0;">甲方(盖章)：</p>
         <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h>  <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h> 
        <p class="sp" style="padding: 10px 0;"><font>乙方(盖章)：</font>          
            <img src="http://static.jieba360.com/Upload/eqian_pic/company-logo.png" class="img_seal"  width="100">
        </p>
        <p class="sp" style="float: right;text-align:right;">日期:{$date}</p>
        </section>     
</section>
</body>
EOD;
		$pdf->AddPage();
		$pdf->writeHTML($html, true, false, true, false, '');
		//$pdf->Image(K_PATH_IMAGES.'company-logo.png', 145, 200, 50);
		$filename = "{$data['order_sn']}_4.pdf";
		$pdf->lastPage();
		$pdf->Output($filename,$dest);
		return $filename;
	}
        
        /*
         * 分期协议
         */
        public static function allot($data,$dest='F'){
            $date = date("Y-m-d");
            $allotContent = "";
            if($data["pay_type"]==2){
                $allotContent .= "<p>本人选择分期方式支付保险费用，费用支付方式为：</p>"
                        . "<p>首付（".date("Y.m.d")."）：交强险费用{$data["force_money"]}元+20%商业险费用{$data["business_money"]}元={$data["list"]["0"]["initial_money"]}元</p>";
                foreach($data["list"] as $vo){
                    $allotContent.="<p>第{$vo["period"]}期（{$vo["back_time"]}）：{$vo["pay_money"]}元</p>";
                }
            }
            
            
            $domainRoot = DOMAIN_ROOT;
            Vendor('tcpdf.zhifu');
            $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('借吧');
            $pdf->SetTitle("车辆保险授权委托书");
            $pdf->SetSubject("车辆保险授权委托书");
            $pdf->SetKeywords("车辆保险授权委托书");
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            $pdf->SetFont('cid0cs','',12);
$html = <<<EOD
        <h1 id="sp1"  style="text-align:center;">车辆保险授权委托书</h1>
        <p>委托人：{$data["names"]}，身份证号：{$data["certiNumber"]}</p>
        <p>被委托人: 智信创富金融信息服务（上海）有限公司</p>
        本人现有一辆汽车，特委托智信创富金融信息服务（上海）有限公司作为我的合法代理人，购买车辆保险：{$data["insurance_type"]}，对受托人在办理上述事项过程中所签署的有关文件，我均予以认可，并承担相应的法律责任。
        {$allotContent}
        <p>委托期限：自签字之日起至上述事项办完为止</p>


        <p>委托人(委托人盖章)：</p>
        <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h>  <h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h><h>&nbsp;&nbsp;</h> <h>&nbsp;&nbsp;</h> 
      
        <p>被委托人(公司盖章)：</p>
        <p  style="float: right;text-align:right;">{$date}</p>
EOD;
            $pdf->AddPage();
            $pdf->writeHTML($html, true, false, true, false, '');
            //$pdf->Image(K_PATH_IMAGES.'company-logo.png', 145, 200, 50);
            if($data["pay_type"]==1){
                $filename = "../allot/{$data['order_id']}_1.pdf";
            }else{
                $filename = "../allot/{$data['order_id']}_2.pdf";
            }
            $pdf->lastPage();
            $pdf->Output($filename,$dest);
            return $filename;
        }
}