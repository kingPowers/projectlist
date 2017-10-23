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
		<a href="/agent/my_unlocked_order" class="btn_back">
	        <img src="_STATIC_/2015/member/image/register/return.png">
	        <font class="fl">返回</font>
	    </a>
	    <h1>订单详情</h1>    
	</header>
	<section class="mui-table-view">
		<section class="order_one">
			<section class="order_otit">			
				<section class="order_header">
				  <empty name="order_info.avatar">
					<img src="_STATIC_/2015/image/yydai/agent/ico_header.png">
				  <else/>
				    <img src="{$order_info.avatar}">
				  </empty>
				</section>
				<section class="order_info">
					<p>{$order_info['nickname']}<if condition='$order_info.is_vip eq 1'><img src="{$order_info['vip_picurl']}"></if><font>智信创富</font></p>
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
		    <section class="order_choose">
               <notempty name='order_info.operate'>
                 <a  href="javascript:void(0)" class="operate" is_report="{$order_info.reportStatus}" class="operate" value="{$order_info['id']}" trans_id='{$order_info.transfer_id}' onclick="report(this);" >{$order_info.operate}</a>
              </notempty>
	    	  <a id="del_order" href="javascript:void(0)" class="operate" value="{$order_info['id']}"  onclick="deleteOrder(this);">删除订单</a>
	        </section>
		</section>
	</section> 
	<section class="order_tel">
		<img src="_STATIC_/2015/image/yydai/agent/ico_tel.png">
		<span>{$order_info['mobile']}</span>
		   <a href="tel:{$order_info['mobile']}" id="dial">拨打电话</a>
	</section>	
</body>
</html>
<script type="text/javascript">
function deleteOrder(_this) {
	 $(_this).removeAttr("onclick");
	 if(!(confirm("是否删该解锁订单"))){
	 	$(_this).attr("onclick","deleteOrder(this)"); 
	 	return false;
	 } 
      $order_id = $(_this).attr("value");
      $mythis = $(_this);
      $.post("/agent/delete_my_unlock",{"order_id":$order_id},function(F){
              alert(F.info);
         if(F.status == 1){
            $mythis.parents(".order_one").hide();
        }
      },'json')
	}

	function report(_this) {
	  $is_report = $(_this).attr("is_report");
      $transfer_id = $(_this).attr("trans_id");
	  $order_id = $(_this).attr("value");
      if($order_id){
      	$url = ($is_report == '1')?"order_report":"report_info";
      	window.location.href = "/agent/"+ $url +"/order_id/"+$order_id+"/transfer_id/"+$transfer_id;
      }else{
      	alert("订单信息有误");
      }
	}
</script>
{__NOLAYOUT__}