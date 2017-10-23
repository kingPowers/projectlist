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
	<!-- jQuery1.7以上 或者 Zepto 二选一，不要同时都引用 -->
	<!--<script src="js/zepto.min.js"></script>-->
	<script>
	$(function(){
		page = 2; is_loading = 1;
		$(window).scroll(function(){
			if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
				$.post("/agent/my_unlocked_order",{'page':page++,'is_ajax':1},function(F){
                       console.log(F);
                       //alert(F.data);
                       if(F.status == 1){
                           var str = "";
                          // var F = eval("(" + F.data + ")");
                           //alert(F.info);
							$.each(F.data,function(i,item){	
							//alert(item.avatar);	
							if(item.avatar == ''){
								$avatar = "_STATIC_/2015/image/yydai/agent/ico_header.png";
							}else{
								$avatar = item.avatar;
							}			
								str += "<section class='order_one'><section value='"+ item.id +"' onclick='get_info(this);' is_report='"+ item.reportStatus +"' transfer_id='"+ item.transfer_id +"'><section class='order_otit'><section class='order_header'><img src='" + $avatar + "'></section><section class='order_info'><p>"+item.nickname;
							    if(item.is_vip == 1){
                                   str += "<img src='"+ item.vip_picurl +"'>";   
							    }
								str += "<font>";
								str += item.company_name + "</font></p>";
								str += "<p style='margin-top: 1%;'><span> 车贷 </span><span>" + item.is_fullmoney + "</span><span>" + item.jobs + "</span></p></section>";
								str += "<section class='order_tag " + item.status_bg + "'>" + item.status_name + "</section></section>";
								str += "<section class='order_con' >" + item.remark + "</section>";
								str += "<section class='order_footer'><font>浏览" + item.scanCount + "次</font><font>解锁" + item.unlockCount + "次</font><span><font>" + item.city + "</font>" + item.timeadd + " </span></section></section>";
								str += "<section class='order_choose'>";
                                      //alert(item.operate[key]); 
                                if(item.report_operate != ""){
                                   str += "<a href='javascript:void(0)' is_report='" + item.reportStatus + "'  calss='operate' value='" + item.id + "' onclick='report(this);' trans_id='" + item.transfer_id + "'>" + item.report_operate + "</a>" ;
                                }   
                                str += "<a id='del_order' href='javascript:void(0)' calss='operate'  value='" + item.id + "' onclick='deleteOrder(this);'>删除订单</a>";  	
								str += "</section></section>";	
							});
							$('.order_content').append(str);
                       }else{
                       	    $('#hint').html("没有更多记录了...");
                       	    is_loading=0;
                       }
				},'json')
			}
		});
		$(window).scroll();
		/*$(".liStyle").click(function(){
			 window.location.href = "/agent/my_order_lists";
		}) */  
	});
	</script>
</head>
<body style="background: #efefef;">
<header>
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>解锁列表</h1>
</header>
<section class="order_content">
	<section id="page_con">
		<ul class="ck_header">
			<li class="liStyle" onclick="window.location.href = '/agent/my_order_lists'">我的甩单</li>
			<li class="liStyle blue_css">已解锁订单</li>
		</ul>
	</section>
	<empty name="order_lists"><section class="order_none">您还未解锁订单!</section></empty>
	<foreach name="order_lists" item="vo">
	<section class="order_one">
	  <section value="{$vo.id}" is_report='{$vo.reportStatus}' transfer_id='{$vo.transfer_id}' onclick="get_info(this);">
		<section class="order_otit">			
			<section class="order_header">
			  <empty name="vo.avatar">
				<img src="_STATIC_/2015/image/yydai/agent/ico_header.png">
			  <else/>
			    <span style="display: inline-block; border-radius: 50%; background: url({$vo.avatar})no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;">
			  </empty>
			</section>
			<section class="order_info">
				<p>{$vo['nickname']}<if condition='$vo.is_vip eq 1'><img src="{$vo['vip_picurl']}"></if></empty><font>{$vo['company_name']}</font></p>
				<p style="margin-top: 1%;"><span>车贷</span><span>{$vo['is_fullmoney']}</span><span>{$vo['jobs']}</span></p>
		    </section>
		    <section class="order_tag {$vo['status_bg']}">{$vo['status_name']}</section>		   
		</section>
		<section class="order_con" value="{$vo.id}" onclick="get_info(this);">{$vo['remark']}</section>
	    <section class="order_footer">
	    	<font>浏览{$vo['scanCount']}次</font>
	    	<font>解锁{$vo['unlockCount']}次</font>
	    	<span><font>{$vo['city']}</font>{$vo['timeadd']}</span>
	    </section>
	  </section>
	    <section class="order_choose">
	        <a id="del_order" href="javascript:void(0)" class="operate" value="{$vo['id']}" onclick="deleteOrder(this);">删除订单</a>
	        <notempty name='vo.report_operate'>
              <a href="javascript:void(0)" is_report="{$vo.reportStatus}" class="operate" value="{$vo['id']}" trans_id='{$vo.transfer_id}' onclick="report(this);" >{$vo.report_operate}</a>
            </notempty>
	    	
	    </section>		
	</section>
	</foreach>
</section>
<if condition="$more eq 1">
<center id="hint" style='color:#999;font-size: 15px;'>上拉获取更多记录...</center>
<else/>
<notempty name="order_lists">
<center id="hint" style='color:#999;font-size: 15px;'>没有更多记录了...</center>
</notempty>
</if> 
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
      	window.location.href = "/agent/"+ $url +"/order_id/"+$order_id+"/transfer_id/"+$transfer_id+"/";
      }else{
      	alert("订单信息有误");
      }
	}
	function get_info(_this){
	  $transfer_id = $(_this).attr("transfer_id");
	  $reportStatus = $(_this).attr("is_report");
      $order_id = $(_this).attr("value");
      window.location.href = "/agent/order_info/order_id/"+$order_id+"/order_affiliation/"+1+"/transfer_id/"+$transfer_id+"/reportStatus/"+$reportStatus;
	}

</script>
{__NOLAYOUT__}