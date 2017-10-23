<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{$pageseo.title}</title>
</head>
<body>
<style type="text/css">
* {margin:0;padding:0;}
body {
    min-height: 100%;
    min-width: 1080px;
    font-family: "Microsoft YaHei","Lucida Sans Unicode","Myriad Pro","Hiragino Sans GB","Heiti SC",Verdana,simsun;
    background: #fff;
    color: #000;
    font-size: 12px;
	line-height: 28px;
    -webkit-text-size-adjust: none;
}
a{color: #00648d;}
a:hover {color: #2498c4;}
h1, h2, h3, h4, h5, h6 {
    font-family: "Microsoft YaHei","Lucida Sans Unicode","Myriad Pro","Hiragino Sans GB","Heiti SC",Verdana,simsun;
    font-weight: normal;
    color: #414141;
    margin: 0;
    padding: 0;
}
h1 small, h2 small, h3 small, h4 small, h5 small, h6 small {font-size: 0.8em;}
h1 {font-size: 36px;line-height: 40px;}
h2 {font-size: 22px;}
h3 {font-size: 18px;line-height: 32px;}
h4 {font-size: 16px;line-height: 30px;margin-bottom: 5px;}
h5 {font-size: 14px;line-height: 26px;}
h6 {font-size: 12px;line-height: 24px;}
p {	margin-bottom: 3px;}
ul, ol {margin: 0;list-style: none outside;}
ul li {	line-height: 28px;}
hr {margin: 20px 0;border: 0;border-top: 1px solid #eeeeee;border-bottom: 1px solid #ffffff;}
.wall {
	width:1100px;
	height:auto;overflow:hidden;
    margin:30px auto;
    border: 1px solid #E3E3E3;
    background: #FFF;
    padding: 20px;
    text-shadow: 0px 1px 0px #fff;
	border-radius: 4px;
    box-sizing: border-box;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -ms-box-sizing: border-box;
}
.header-title {min-width: 120px;display: inline-block; *display: inline; *zoom: 1;clear: both;margin-left: 0px;}
.underline {
    border-bottom: 1px solid #333;
    padding: 1px;
    margin-bottom: -2px;
    display: inline-block;
    *display: inline;
    *zoom: 1;
}
br{clear:both;}
a.btn{display:block;float:right;padding: 4px 12px;font-size: 14px;line-height: 20px;color: #333333;text-align: center;text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
vertical-align: middle;cursor: pointer;background: #E3E3E3;border: none;}
table {
  max-width: 100%;
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
}

.table {
  width: 100%;
  margin-bottom: 20px;
}

.table th,
.table td {
  padding: 8px;
  line-height: 20px;
  text-align: left;
  vertical-align: top;
  border-top: 1px solid #dddddd;
}

.table th {
  font-weight: bold;
}

.table thead th {
  vertical-align: bottom;
}

.table caption + thead tr:first-child th,
.table caption + thead tr:first-child td,
.table colgroup + thead tr:first-child th,
.table colgroup + thead tr:first-child td,
.table thead:first-child tr:first-child th,
.table thead:first-child tr:first-child td {
  border-top: 0;
}

.table tbody + tbody {
  border-top: 2px solid #dddddd;
}

.table .table {
  background-color: #ffffff;
}

.table-condensed th,
.table-condensed td {
  padding: 4px 5px;
}

.table-bordered {
  border: 1px solid #dddddd;
  border-collapse: separate;
  *border-collapse: collapse;
  border-left: 0;
  -webkit-border-radius: 4px;
     -moz-border-radius: 4px;
          border-radius: 4px;
}

.table-bordered th,
.table-bordered td {
  border-left: 1px solid #dddddd;
}

.table-bordered caption + thead tr:first-child th,
.table-bordered caption + tbody tr:first-child th,
.table-bordered caption + tbody tr:first-child td,
.table-bordered colgroup + thead tr:first-child th,
.table-bordered colgroup + tbody tr:first-child th,
.table-bordered colgroup + tbody tr:first-child td,
.table-bordered thead:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child td {
  border-top: 0;
}

.table-bordered thead:first-child tr:first-child > th:first-child,
.table-bordered tbody:first-child tr:first-child > td:first-child,
.table-bordered tbody:first-child tr:first-child > th:first-child {
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-topleft: 4px;
}

.table-bordered thead:first-child tr:first-child > th:last-child,
.table-bordered tbody:first-child tr:first-child > td:last-child,
.table-bordered tbody:first-child tr:first-child > th:last-child {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -moz-border-radius-topright: 4px;
}

.table-bordered thead:last-child tr:last-child > th:first-child,
.table-bordered tbody:last-child tr:last-child > td:first-child,
.table-bordered tbody:last-child tr:last-child > th:first-child,
.table-bordered tfoot:last-child tr:last-child > td:first-child,
.table-bordered tfoot:last-child tr:last-child > th:first-child {
  -webkit-border-bottom-left-radius: 4px;
          border-bottom-left-radius: 4px;
  -moz-border-radius-bottomleft: 4px;
}

.table-bordered thead:last-child tr:last-child > th:last-child,
.table-bordered tbody:last-child tr:last-child > td:last-child,
.table-bordered tbody:last-child tr:last-child > th:last-child,
.table-bordered tfoot:last-child tr:last-child > td:last-child,
.table-bordered tfoot:last-child tr:last-child > th:last-child {
  -webkit-border-bottom-right-radius: 4px;
          border-bottom-right-radius: 4px;
  -moz-border-radius-bottomright: 4px;
}

.table-bordered tfoot + tbody:last-child tr:last-child td:first-child {
  -webkit-border-bottom-left-radius: 0;
          border-bottom-left-radius: 0;
  -moz-border-radius-bottomleft: 0;
}

.table-bordered tfoot + tbody:last-child tr:last-child td:last-child {
  -webkit-border-bottom-right-radius: 0;
          border-bottom-right-radius: 0;
  -moz-border-radius-bottomright: 0;
}

.table-bordered caption + thead tr:first-child th:first-child,
.table-bordered caption + tbody tr:first-child td:first-child,
.table-bordered colgroup + thead tr:first-child th:first-child,
.table-bordered colgroup + tbody tr:first-child td:first-child {
  -webkit-border-top-left-radius: 4px;
          border-top-left-radius: 4px;
  -moz-border-radius-topleft: 4px;
}

.table-bordered caption + thead tr:first-child th:last-child,
.table-bordered caption + tbody tr:first-child td:last-child,
.table-bordered colgroup + thead tr:first-child th:last-child,
.table-bordered colgroup + tbody tr:first-child td:last-child {
  -webkit-border-top-right-radius: 4px;
          border-top-right-radius: 4px;
  -moz-border-radius-topright: 4px;
}

.table-striped tbody > tr:nth-child(odd) > td,
.table-striped tbody > tr:nth-child(odd) > th {
  background-color: #f9f9f9;
}

.table-hover tbody tr:hover > td,
.table-hover tbody tr:hover > th {
  background-color: #f5f5f5;
}

table td[class*="span"],
table th[class*="span"],
.row-fluid table td[class*="span"],
.row-fluid table th[class*="span"] {
  display: table-cell;
  float: none;
  margin-left: 0;
}

.table td.span1,
.table th.span1 {
  float: none;
  width: 44px;
  margin-left: 0;
}

.table td.span2,
.table th.span2 {
  float: none;
  width: 124px;
  margin-left: 0;
}

.table td.span3,
.table th.span3 {
  float: none;
  width: 204px;
  margin-left: 0;
}

.table td.span4,
.table th.span4 {
  float: none;
  width: 284px;
  margin-left: 0;
}

.table td.span5,
.table th.span5 {
  float: none;
  width: 364px;
  margin-left: 0;
}

.table td.span6,
.table th.span6 {
  float: none;
  width: 444px;
  margin-left: 0;
}

.table td.span7,
.table th.span7 {
  float: none;
  width: 524px;
  margin-left: 0;
}

.table td.span8,
.table th.span8 {
  float: none;
  width: 604px;
  margin-left: 0;
}

.table td.span9,
.table th.span9 {
  float: none;
  width: 684px;
  margin-left: 0;
}

.table td.span10,
.table th.span10 {
  float: none;
  width: 764px;
  margin-left: 0;
}

.table td.span11,
.table th.span11 {
  float: none;
  width: 844px;
  margin-left: 0;
}

.table td.span12,
.table th.span12 {
  float: none;
  width: 924px;
  margin-left: 0;
}

.table tbody tr.success > td {
  background-color: #dff0d8;
}

.table tbody tr.error > td {
  background-color: #f2dede;
}

.table tbody tr.warning > td {
  background-color: #fcf8e3;
}

.table tbody tr.info > td {
  background-color: #d9edf7;
}

.table-hover tbody tr.success:hover > td {
  background-color: #d0e9c6;
}

.table-hover tbody tr.error:hover > td {
  background-color: #ebcccc;
}

.table-hover tbody tr.warning:hover > td {
  background-color: #faf2cc;
}

.table-hover tbody tr.info:hover > td {
  background-color: #c4e3f3;
}
.contract-logo{height:48px;line-height:48px;}
.contract-logo img{float:left;margin-right:210px;}
</style>
<div class="wall">
<h2 class="contract-logo"> <img src="_STATIC_/2015/img/ui-logo.png" alt="{:SITE_NAME}"/> 借款协议 <eq name="hidebut" value="0"><a class="btn" href="/invest/downpact/{$loan['loansn']}/download.html">下载</a></eq></h2>
<hr>
<p>借款协议号：：<b>LR{:str_pad($loan['id'],10,0,STR_PAD_LEFT)}</b></p>
<br>
<p><span class="header-title">借款人：</span> <span class="underline" style="min-width: 200px"> {$userinfo['name']}</span> </p>
<p><span class="header-title">借款人证件号：</span> <span class="underline" style="min-width: 200px"> {$userinfo['certinumber']}</span> </p>
<p><span class="header-title">出借人：</span> <span class="underline" style="min-width: 200px">详见本协议第一条 </span> </p>
<notempty name="userinfo['certinumber']"><p> <span class="header-title">身份证号/注册号：</span> <span class="underline" style="min-width: 200px"> {:$userinfo['certinumber']}</span> </p></notempty>
<p> 借款人通过智信创富金融信息服务（上海）有限公司（ http://www.zhifu360.com，以下简称智信创富金融）,就有关借款事项与各出借人达成如下协议: </p>
<br>
</ul>
<br>
<p>出借人： </p>
<table class="table table-bordered table-should-responsive table-responsive" id="table-responsive-1">
  <thead>
	<tr>
	  <th>出借人（用户名）</th>
	  <th class="text-right">出借金额（元）</th>
	  <th class="text-right">借款期限（<eq name="loan['isloanday']" value="1">天<else/>月</eq>）</th>
	  <th class="text-right">全部应收（元）</th>
	</tr>
  </thead>
  <tbody>
  <foreach name="tenderlist" item="vo">
	<tr>
	  <td> {:hide_username($vo['username'])}<?php if($member['id']==$vo['memberid']):?><?php if($member['memberInfo']['nameStatus']==1):?><br/>姓名：{$member['memberInfo']['names']}；身份证号：{$member['memberInfo']['certiNumber']}<?php endif;endif;?></td>
	  <td class="text-right">{:number_format($vo['money'],2)}</td>
	  <td class="text-right"><eq name="loan['isloanday']" value="1">{$loan['loanday']}<else/>{$loan['loanmonth']}</eq></td>
	  <td class="text-right">{:number_format($vo['money']+$vo['ratemoney'],2)}</td>
	</tr>
  </foreach>
  </tbody>
</table>
<p> <span class="header-title">居间服务人：</span> <b><span class="underline" style="min-width: 350px">西安律信商务信息咨询有限公司</span></b> </p>
<p> <span class="header-title">住所:</span><span class="underline" style="min-width: 350px"> 西安市雁塔区南二环路西段88号老三届世纪星1幢1单元11005室（10层E座）</span> </p>
<br>
<h4>鉴于：</h4>
<p> 1.居间服务人是一家在中国西安市合法成立并有效存续的有限公司，拥有并运营律信智投www.{:DOMAIN_ROOT}网站（以下简称“网站”，本协议中凡提及该网站者，所指向的权利义务主体均系居间服务人）的经营权，向网站注册用户提供信用咨询及相关信息服务。</p>
<p> 2.借款人和出借人均已在网站注册或经授权录入，同意网站的《注册服务协议》，并自愿根据《注册服务协议》达成并签订本借款协议。本协议使用借款人和出借人事先已充分阅读并认可的网站提供的借款协议样本。</p>
<p> 3.借款人和出借人均已承诺其提供给居间服务人的信息真实、完整、有效。</p>
<p> 4.借款人具有合法的借款需求，出借人自愿以其自有的合法资金向借款人提供借款。出借人以本协议为依据与借款人形成真实、合法、有效的债权。</p>
<br>
<p> 借款人和出借人基于平等自愿原则，经居间服务人的居间介绍，就有关借款事项达成如下协议，以兹共守：</p>
<h4>第一条 借款基本信息</h4>
<p> 出借人同意通过居间服务人平台向出借人借款如下，出借人同意通过居间服务人平台向借款人发放该笔借款： </p>
<table class="table table-bordered">
  <tbody>
	<tr>
	  <td>借款本金数额</td>
	  <td> {:number_format($loan['loanmoney'],2)}元</td>
	</tr>
	<tr>
	  <td>借款年化利率 </td>
	  <td> {:number_format($loan['yearrate'],2)}%</td>
	</tr>
	<tr>
	  <td>还款数额 </td>
	  <td>详情见还款计划 </td>
	</tr>
	<tr>
	  <td>还款方式 </td>
	  <td> {$repayment[$loan['repayment']]}</td>
	</tr>
	<tr>
	  <td>借款开始日 </td>
	  <td>出借人指定账户划付本协议项下借款之日 </td>
	</tr>
	<tr>
	  <td>借款到期日 </td>
	  <td>开始后的第N（N为上表中借款期限）个自然月（不满一个月的按约定的天数计算）的借款开始日当日，节假日不顺延(如当月不存在该借款开始日的，则应为当月的最后一日) </td>
	</tr>
	<tr>
	  <td>还款日 </td>
	  <td>自第二个月起每个自然月的借款开始日当日，节假日不顺延(如当月不存在该借款开始日的，则应为当月的最后一日)</td>
	</tr>
  </tbody>
</table>
<h4>还款计划：</h4>
<table class="table table-bordered table-should-responsive table-responsive" id="table-responsive-2">
  <thead>
	<tr>
	  <th>编号</th>
	  <th>还款截至日</th>
	  <th>还款金额</th>
	</tr>
	<foreach name="issuelist" item="vo">
	<tr>
		<td>{:(substr($vo['issuesn'],-2)-10)}</td>
		<td>{:substr($vo['endtime'],0,16)}</td>
		<td>{:number_format($vo['money'],2)}元</td>
	</tr>
	</foreach>
  </thead>
  <tbody>
  </tbody>
</table>
<h4>第二条 借款的支付</h4>
<p> 2.1出借人在同意向借款人出借相应款项时，已委托网站在本协议生效时将本协议项下的借款直接划付至借款人账户。</p>
<p> 2.2借款人已委托网站将还款直接划付至出借人账户。</p>
<p> 2.3借款人和出借人均同意上述网站接受委托的行为所产生的法律后果均由相应委托方承担。</p>
<h4>第三条 借款的偿还</h4>
<p> 3.1借款人承诺按照本协议第一条约定的时间和金额按期足额向出借人还款。</p>
<p> 3.2借款人将主动根据本协议规定之还款日及还款数额按月还款至网站指定账户并委托网站划付至出借人账户。</p>
<p> 3.3借款人的最后一期还款必须包含全部剩余本金、利息及所有根据本协议产生的其他费用等。</p>
<p> 3.4如借款人还款不足以偿还约定的本金、利息及违约金的，在根据本协议第六条规定扣除账户管理费用后，出借人同意各自按照其借出款项比例收取还款及利息。</p>
<h4>第四条 借款的转让</h4>
<p> 4.1借款人知晓并同意出借人于本协议履行过程中可能将其享有的债权的全部或部分转让给不特定的第三人，且转让次数无限定。</p>
<p> 4.2出借人将其债权全部或部分转让给第三人的，应当以信件、邮件或短信等书面形式通知借款人。本协议双方同意该债权转让自出借人书面通知发出之日起对借款人发生效力。</p>
<p> 4.3出借人将其债权全部或部分转让给第三人的，本借款协议中对应权利及义务一并转让给债权受让人，包括但不限于主张罚息、利息、解除合同等权利及支付中介服务费用的义务。</p>
<h4>第五条 通知</h4>
<p> 5.1本协议任何一方因履行本协议做出的通知和/或文件均应以书面形式做出，通过专人送达、挂号邮递、特快专递、短信及邮件等方式传送。</p>
<p> 5.2通知在下列日期视为送达：<br/>
（1）专人递送的通知，在专人递送之交付对方日为有效送达；<br/>
（2）以挂号信（付清邮资）发出的通知，在寄出（以邮戳为凭）后的五个工作日内为有效送达；<br/>
（3）以特快专递（付清邮资）发出的通知，在寄出（以邮戳为凭）后的三个工作日内为有效送达；<br/>
（4）以短信方式发出的通知，短信成功发出即为有效送达；<br/>
（5）以邮件方式发出的通知，邮件发送成功时即为有效送达。</p>
<p> 5.3各方在网站或借款申请材料中填写的联系信息即为其有效的通讯方式。</p>
<p> 5.4协议各方有权在任何时候更改其联系信息，但应按本协议约定的送达方式在变更后三个工作日内向其他方送达通知。否则变更方应当承担由此造成的送达不能产生的法律风险及责任。</p>
<h4>第六条 借款居间服务费</h4>
<p> 6.1居间服务费是指因居间服务人为借款人与出借人提供交易信息、信用咨询、评估、还款提醒、账户管理、还款特殊情况沟通等系列相关服务而由借款人与出借人分别支付给居间服务人的报酬。</p>
<p> 6.2借款人同意在借款成功时根据借款类型和期限的不同向居间服务人支付借款额的<span class="underline" style="min-width: 50px; text-align: center;">0.5-2%</span>作为居间服务费，此笔费用借款人委托居间服务人在借款成功时从借款本金中直接扣除。</p>
<p> 6.3出借人同意在借款成功后按月向居间服务人支付本次借款所得利息的 0 %作为居间服务费，此笔费用出借人委托居间服务人在借款人支付借款利息时从利息中直接扣除。</p>
<h4>第七条 提前还款</h4>
<p> 7.1在支付本协议第7.3条规定的违约金后，借款人可在借款期间任何时候提前偿还剩余借款。但借款人应当在提前还款的5个工作日前向网站提出申请，网站收到申请后与借款人确定提前还款的具体情况并通知出借人。</p>
<p> 7.2借款人提前偿还借款的，提前还款部分按本协议第一条约定的利息和实际借款期限计收利息。若实际借款期限非自然月的整数倍的，则扣除自然月的整数倍后的其余天数的借款利息仍应当按天计算。</p>
<p> 7.3借款人提前偿还借款的，除支付本协议第7.2条规定的借款利息外，还应额外向出借人支付相当于提前偿还借款部分1个月的利息作为提前还款违约金。</p>
<p> 7.4借款人提前偿还借款的，借款人与出借人已支付的居间服务费不予退还。</p>
<h4>第八条 逾期还款</h4>
<p> 8.1借款人应严格履行还款义务，借款人未在借款协议规定的还款日前足额还款的，应按照下列公式计算并向出借人支付逾期罚息，且逾期本金的正常利息不停止计算。借款人还清全部本金、利息、罚息之前，罚息计算不停止。<br/>罚息总额 = 逾期本息总额×对应罚息利率×逾期天数；<br/>逾期天数 自还款日之次日起算 <br/>罚息利率 千分之一</p>
<p> 8.2若借款人逾期支付任何一期还款，网站有权将借款人信息通过网站或其他途径进行公布，并/或记入相关合作方的信用记录以及国家和地方的征信系统；由于信息公布产生的一切相关法律责任及后果概由借款人自负，与出借人及网站无涉。</p>
<p> 8.3若借款人逾期支付任何一期还款超过30天或借款人在逾期后出现逃避、拒绝沟通或拒绝承认欠款事实等恶意行为，经居间服务人确认，本协议项下的全部借款本息视同提前到期，借款人应立即清偿本协议项下尚未偿付的全部本金、利息、罚息及根据本协议产生的其他全部费用。</p>
<p> 8.4若借款人逾期支付任何一期还款超过30天，或借款人在逾期后出现逃避、拒绝沟通或拒绝承认欠款事实等恶意行为，经居间服务人确认，出借人与居间服务人均可将借款人违约失信的相关信息及借款人其他信息向微博等媒体、用人单位、公安机关、检查机关、法律机关等披露，并有权将借款人提交或居间服务人自行收集的借款人的个人资料和信息与任何第三方进行数据共享，以便出借人、居间服务人和第三方催收逾期借款及对用户的其他申请进行审核之用，由此造成的损失，出借人与居间服务人不承担任何责任。</p>
<p> 8.5为集中维护各出借人权利，如借款人出现逾期还款30天，或借款人在逾期后出现逃避、拒绝沟通或拒绝承认欠款事实等恶意行为的，全体出借人一致同意可将本协议项下债权转让给居间服务人，由居间服务人统一向借款人追索。</p>
<p> 8.6当借款人出现本协议8.5陈述的恶意行为时，如居间服务人认定借款人系疑似欺诈，且居间服务人同意先行支付本金给出借人的，出借人同意将债权不可撤销地转让给居间服务人。</p>
<p> 8.7借款人的个人信息或工作情况发生重大变动，可能影响借款人按时还款的，借款人应于发生前述变更后的五个工作日内书面通知出借人与居间服务人该种逾期可能。</p>
<h4>第九条 违约责任</h4>
<p> 9.1本协议各方均应严格履行协议义务，任何一方违约，违约方应承担因违约使其他各方产生的费用和损失，包括但不限于调查费、诉讼费、律师费等。</p>
<p> 9.2各方同意，若出现如下任何一种情况，本协议项下的全部借款本息自动提前到期，借款人应立即清偿本协议项下尚未偿付的全部本金、利息、罚息及根据本协议产生的其他全部费用：<br/>
（1）借款人因任何原因逾期30天支付任何一期还款的；<br/>
（2）借款人个人信息或工作情况发生重大变动后的五个工作日内未书面通知出借人与居间服务人的；<br/>
（3）借款人在逾期偿还借款后出现逃避、拒绝沟通或拒绝承认欠款事实等恶意行为的；<br/>
（4）借款人违反本协议第十条规定的。</p>
<p> 9.3若借款人逾期30天支付任何一期还款的，借款人应当按照借款余额的20%支付违约金。</p>
<p> 9.4借款人的每期还款均应按照如下顺序清偿：<br/>
（1）根据本协议产生的除本款（2）-（6）项之外的其他全部费用；<br/>
（2）罚息；<br/>
（3）拖欠的利息；<br/>
（4）拖欠的本金；<br/>
（5）正常的利息；<br/>
（6）正常的本金。</p>
<h4>第十条 承诺条款</h4>
<p> 10.1未经居间服务人与全部出借人的一致同意，借款人不得以任何理由和任何形式将获得的借款用于前述借款用途（以借款申请材料内容记载为准）之外的任何其他用途。借款人擅自改变借款用途将承担违约责任。借款人不得将所借款项用于任何违法活动（包括但不限于赌博、吸毒、贩毒、卖淫嫖娼等）及生产经营和消费以外的范畴（包括但不限于股票、基金、期货等金融产品的投资、房地产及房地产信托投资、二次借贷、彩票等）。否则，一经发现，出借人有权提前收回全部借款，并立即向公安等司法机关举报，构成犯罪的，追究借款人的刑事责任。</p>
<p> 10.2借款人承诺向出借人提交的一切文本、图文、个人信息等资料等均为真实、有效。</p>
<p> 10.3出借人承诺对本协议所涉的借款具有完全的支配能力，且为出借人的合法收入。</p>
<p> 10.4借款人和出借人承诺任何发自于借款人和出借人注册时提供的邮箱、手机的电子邮件与短信均为借款人和出借人的真实意思表示。</p>
<h4>第十一条 适用法律及争议解决</h4>
<p> 11.1本协议的履行地为居间服务人实际营业地为西安市雁塔区。</p>
<p> 11.2本协议的签订、履行、终止、解释均适用中华人民共和国法律，并由协议履行地西安市雁塔区人民法院管辖。</p>
<h4>第十二条 其他</h4>
<p> 12.1本协议采用电子文本形式制成，经各方在网站以点击确认/书面确认的形式签订。并在居间服务人网站上保留存档，各方均认可该形式协议的法律效力。</p>
<p> 12.2借款人及出借人同意居间服务人根据具体情况对本借款协议进行更新。</p>
<p> 12.3如果本协议中的任何一条或多条违反适用的法律法规，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。</p>
<p> 12.4出借人通过该借款协议所获得的收益应自行申报并缴纳税款。</p>
<p> 12.5居间服务人接受借款人和出借人的委托所产生的法律后果由相应委托方承担。如因借款人或出借人或其他第三方（包括但不限于技术问题）造成的延误或错误，居间服务人不承担任何责任。</p>
<p> 本协议最后更新版本：2014年12月</p>
</div>
</body>
</html>{__NOLAYOUT__}