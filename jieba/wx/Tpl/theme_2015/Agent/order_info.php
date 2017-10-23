<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">  
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
</head>
<body style="background: #efefef;">
	<header>
		<a href="/agent/order_lists/return/info" class="btn_back">
	        <img src="_STATIC_/2015/member/image/register/return.png">
	        <font class="fl">返回</font>
	    </a>
	    <h1>订单详情</h1>    
	</header>
	<section class="tkgrayBg" style="display: none; height: 100%;">
    <section class="tkjin_bg">        
        <h2>提示</h2>
        <p style=" margin: 0,auto; ">1111</p>
        <section class="fl btn_tkjin" id="tl_cancel">取消</section>  
        <section class="fl btn_tkjin" id="tl_go" url =" ">去充值</section>        
    </section>
  </section>
	<section class="mui-table-view">
		<section class="order_one">
			<section class="order_otit">			
				<section class="order_header">
				  <empty name="order_info.avatar">
					<img src="_STATIC_/2015/image/yydai/agent/ico_header.png">
				  <else/>
		    	    <span style="display: inline-block; border-radius: 50%; background: url({$order_info.avatar})no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;"></span>
				  </empty>
				</section>
				<section class="order_info">
					<p>{$order_info['nickname']}<if condition='$order_info.is_vip eq 1'><img src="{$order_info['vip_picurl']}"></if><font>{$order_info['company_name']}</font></p>
					<p style="margin-top: 1%;"><span>车贷</span><span>{$order_info['is_fullmoney']}</span><span>{$order_info['jobs']}</span></p>
			    </section>
			    <section class="order_tag {$order_info['status_bg']}">{$order_info['status_name']}</section>		   
			</section>
			<section class="order_con" id="font_black">{$order_info['remark']}</section>
			<section class="order_blue">
				<p>借款人信息</p>
				<span>客户姓名:<font>{$order_info['names']}</font></span>
				<span>客户年龄:<font>{$order_info['age']}岁</font></span>
				<span>借款金额:<font>{$order_info['loan_money']}万元</font></span>	
				<span>客户职业:<font>{$order_info['jobs']}</font></span>				
				<span>贷款城市:<font>{$order_info['city']}</font></span>
				<span>车辆户籍:<font>{$order_info['car_city']}</font></span>
				<p style="margin-top: 20px;">车辆信息</p>
				<span>品牌型号:<font>{$order_info['brands']}</font></span>
				<span>购车时间:<font>{$order_info['buy_time']}</font></span>
				<span>裸车价格:<font>{$order_info['car_price']}万元</font></span>
				<span>是否全款:<font>{$order_info['is_fullmoney']}</font></span>
				<span>行驶公里:<font>{$order_info['car_drive']}万公里</font></span>			
				<if condition="$order_info['isFullMoney'] neq 1">
				  <span>抵押单位:<font>{$order_info['mort_company']}</font></span>
				</if>			
				<span style="width: 100%;">车架号码:<font>{$order_info['car_frame_number']}</font></span>
			</section>
		    <section class="order_footer">
		    	<font>浏览{$order_info['scanCount']}次</font>
		    	<font>解锁{$order_info['unlockCount']}次</font>
		    	<span><font>{$order_info['city']}</font>{$order_info['timeadd']}</span>
		    </section>
		</section>
	</section><if condition="$order_info['isMyLockedOrder'] eq 0">
	<section class="w94"><p>解锁电话需扣除金账户余额{$unlockmoney}元</p></section></if>
	<section class="order_tel">
		<img src="_STATIC_/2015/image/yydai/agent/ico_tel.png">
		<span>{$order_info['mobile']}</span>
		<if condition="$order_info['isMyLockedOrder'] eq 0">
		   <a href="javascript:void(0);" id="get_mobile" name="1" value="{$order_info['id']}">解锁电话</a>
		 <else/>
		   <a href="tel:{$order_info['mobile']}" id="dial">拨打电话</a>
		</if>
	</section>
</body>
</html>
<script type="text/javascript">
$(function(){
$("#tl_cancel").click(function(){
	$("#get_mobile").attr("name","1");
	$(".tkgrayBg").hide();
})	

$("#get_mobile").click(function(){
	if($(this).attr("name") == "0") return false;
	if(!(confirm("是否解锁电话"))) return false;
	$order_id = $(this).attr("value");
	$myThis = $(this);
	$(this).attr("name","0");
	$.post("/agent/get_mobile",{"order_id":$order_id,"is_ajax":1},function(F){ 
	console.log(F);      
        if(F.status == 0){
        	var R = eval("(" + F.data + ")");
        	//alert(R.show)
        	if(R.state === 1){
                $(".tkgrayBg").show();
                $(".tkgrayBg p").html(R.message);
                $("#tl_go").html(R.confirm).attr("onclick","javascript:window.location.href='"+R.url+"'");
        	}else{
        		$myThis.attr("name","1");
        		alert(F.info);
        	}
        }else{
        	alert(F.info);
        	window.location.reload();
        }
          
	},'json')
})
}) 
</script>
{__NOLAYOUT__}