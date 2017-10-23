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
</head>
<body style="background: #efefef;">
<header>
	<a href="/credit/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font> 
    </a>
    
    <h1>车友贷</h1>
    <a style="right:8px;" href='/credit/help' class="btn_help"><img src="_STATIC_/2015/image/yydai/index/ico_help.png"></a>
</header>
<form action="/credit/apply" method="post" id="credit_apply">
	<section class="commend_div">
		<section class="table">
			<section class="tr">
				<font>借多少</font><input type="text" name='loanmoney' placeholder="<gt name='order_info.maxMoney' value='0'>最多可借款{$order_info.maxMoney}元<else/>请输入金额金额</gt>" class="text_money">
			</section>
			<section class="tr">
				<font>借多久</font><span><b>7</b>天</span>
			</section>
			<section class="tr">
				<font>怎么还</font><span><h2>先息后本</h2></span>
			</section>
			<section class="tr">
				<font>周利息</font><span><font id="font_zlx">0.00</font>元</span>
			</section>
			<section class="tr">
				<font>手续费</font><span><font id="font_sxf">{$setting.fee}</font>元</span>
			</section>
			<section class="tr">
				<font>平台管理费</font><span><font id="font_glf">0.00</font>元</span>
			</section>
			<section class="tr">
				<font>总利息</font><span><font id="font_lixi">0.00</font>元</span>
			</section>
		</section>
	</section>
	<section class="w94" >
		<p style="text-align:left;">可提前还款，手续费{$setting.fee}元/笔</p>
		<div class='is_show_agree'>
			<p style="text-align:left;">同意
				<a onClick="tk()">贷款相关合同</a>
			</p>
		</div>
	</section>
	<input name="apply" type="hidden" value='1'/>
	<input type="button" autocomplete="off"  class="btn_sub credit_apply" value="确定">
</form>
<section class="tkLayBg" style="display:none;">
	<section class="tkdiv_bg" style="display:none;">
		<a href="/credit/creditagreement" my_href = "/credit/creditagreement" class='p_x'>个人借款协议</a>
		<a href="/credit/creditagreement2" my_href="/credit/creditagreement2" class='z_x'>居间服务协议</a>
		<a href="/credit/agree_supple" my_href="/credit/agree_supple">补充协议</a>   
		<eq name='is_staff' value='1'><a href="/credit/creditagreement4" my_href="/credit/creditagreement4" class='z_x1'>还款承诺书</a></eq>
		<a id="btn_cancel">取消</a>
	</section>
</section>
</body>
<script type="text/javascript">
function tk(){
	$(".tkdiv_bg,.tkLayBg").show();
	$(".p_x").attr('href',$(".p_x").attr('my_href')+"?money="+$("input[name='loanmoney']").val());
	$(".z_x").attr('href',$(".z_x").attr('my_href')+"?money="+$("input[name='loanmoney']").val());
	$(".tkLayBg").height($(document).height());
	$("#btn_cancel").click(function(){
		$(".tkdiv_bg,.tkLayBg").hide();
	});	
}


$(function(){
	$('.credit_apply').removeAttr("disabled");
	$('.is_show_agree').hide();
	//点击确定按钮，提交form表单
	$(".credit_apply").click(function(){
		var money = $("input[name='loanmoney']").val();
		var max_m = '{$order_info.maxMoney}';
		money = Math.floor(money);
		money = money.toFixed(2);
		if(money<=0){
			alert("请输入借款金额");
			return false;
		}

		if(max_m!='' && max_m<1){
			alert("亲,您的信用积分不足,暂不能申请此项目,请坚持登录或签到,增加信用积分！");
			location.href="/Index/index";
			return false;
		}
		if($(this).attr('disabled'))return false;
		$(this).attr("disabled",true).css({"background":"#ddd"});
		$("#credit_apply").submit();
		
	});
});
$(function(){
	var jiekuan;
	var lilv;
	var sxf;
	var glf;
	changelv();

	function changelv(){
		var maxmoney = '<gt name='order_info.maxMoney' value='0'>{$order_info.maxMoney}<else/>10000</gt>';
		jiekuan = parseInt($(".text_money").val());
		if (isNaN(jiekuan)) jiekuan = '';
			
		if(jiekuan>maxmoney){
			jiekuan=maxmoney;
		}
		//借款协议是否显示
		if(jiekuan>=1)
			$('.is_show_agree').show();
		else
			$('.is_show_agree').hide();	
			
		$('.text_money').val(jiekuan);
		if(jiekuan=='')jiekuan=0;
		jiekuan = jiekuan*100000;//借款金额
                var week_percent="{$setting.week_percent}";
		lilv = (jiekuan*week_percent)/10000000;//利息
		sxf = parseInt("{$setting.fee}");//手续费
		var plat_fee=parseInt("{$setting.plat_fee}");
		glf =(jiekuan*0.01*plat_fee)/100000;//平台管理费		
		lilv = lilv.toFixed(2);
		sxf = sxf.toFixed(2);
		glf = glf.toFixed(2);
		var zonglixi = lilv*1 + sxf*1 + glf*1;
		zonglixi = zonglixi.toFixed(2);

		$("#font_zlx").text(lilv);
		$("#font_sxf").text(sxf);		
		$("#font_lixi").text(zonglixi);
		$("#font_glf").text(glf);

	}
	$('.text_money').bind('input propertychange', function () {		
		changelv();
	});
})
</script>
</html>
{__NOLAYOUT__}