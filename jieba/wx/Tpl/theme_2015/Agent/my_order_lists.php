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
				$.post("/agent/my_order_lists",{'page':page++,'is_ajax':1},function(F){
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
								str += "<section class='order_one'>";
								//alert(item.avatar);
								if(typeof(item.error_code_pic) != 'undefined'){
								    str += "<img src='" + item.error_code_pic + "' style='width: 30%; position: absolute; margin:18% 0 0 50%;'>";
							    }
								str += "<section value='"+ item.id +"' onclick='get_info(this);'><section class='order_otit'><section class='order_header'><span style='display: inline-block; border-radius: 50%; background: url("+ $avatar +")no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;'></span></section><section class='order_info'><p>"+item.nickname;
							    if(item.is_vip == 1){
                                   str += "<img src='" + item.vip_picurl + "'>";   
							    }
								str += "<font>";
								str += item.company_name + "</font></p>";
								str += "<p style='margin-top: 1%;'><span> 车贷 </span><span>" + item.is_fullmoney + "</span><span>" + item.jobs + "</span></p></section>";
								str += "<section class='order_tag "+ item.status_bg +"'>" + item.status_name + "</section></section>";
								str += "<section class='order_con' >" + item.remark + "</section>";
								str += "<section class='order_footer'><font>浏览" + item.scanCount + "次</font><font>解锁" + item.unlockCount + "次</font><span><font>" + item.city + "</font>" + item.timeadd + " </span></section></section>";
								str += "<section class='order_choose'>";
								for(var key in item.operate){
                                      //alert(item.operate[key]);
                                      if(key == "is_delete"){
                                      	$id = "del_order";
                                      }
                                      str += "<a id='"+ $id +"'' href='javascript:void(0)' calss='operate' name='" + key + "' order_id='" + item.id + "' onclick='operate(this);'>" + item.operate[key] + "</a>"
								}
								str += "</section></section>";	
							});
							$('.order_content').append(str);
                       }else{
                       	    $('#hint').html("没有更多订单了...");
                       	    is_loading=0;
                       }
				},'json')
			}
		});
		$(window).scroll();
		/*$(".liStyle").click(function(){
			 window.location.href = "/agent/my_unlocked_order";
		})*/
		
	});

	</script>
</head>
<body style="background: #efefef;">
<header>
	<a href="/agent/agent_account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>甩单列表</h1>
</header>
<section class="order_content">
	<section id="page_con">
		<ul class="ck_header">
			<li class="liStyle blue_css">我的甩单</li>
			<li class="liStyle" onclick="window.location.href='/agent/my_unlocked_order'">已解锁订单</li>
		</ul>
	</section>
	<empty name="order_lists"><section class="order_none">您还未发布订单!</section></empty>
	<foreach name="order_lists" item="vo">
	<section class="order_one">
	    <notempty name="vo.error_code_pic">
		<img src="{$vo.error_code_pic}" style="width: 30%; position: absolute; margin:18% 0 0 50%;">
		</notempty>
	   <section value="{$vo.id}" onclick="get_info(this);">
		<section class="order_otit" >			
			<section class="order_header">
			  <empty name="vo.avatar">
				<span style="display: inline-block; border-radius: 50%; background: url('_STATIC_/2015/image/yydai/agent/ico_header.png')no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;"></span>
			  <else/>
			    <span style="display: inline-block; border-radius: 50%; background: url({$vo.avatar})no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;"></span> 
			  </empty>
			</section>
			<section class="order_info">
				<p>{$vo['nickname']}<if condition='$vo.is_vip eq 1'><img src="{$vo['vip_picurl']}"></if><font>{$vo['company_name']}</font></p>
				<p style="margin-top: 1%;"><span>车贷</span><span>{$vo['is_fullmoney']}</span><span>{$vo['jobs']}</span></p>
		    </section>
		    <section class="order_tag {$vo['status_bg']}">{$vo['status_name']}</section>		   
		</section>
		<section class="order_con">{$vo['remark']}</section>
	    <section class="order_footer">
	    	<font>浏览{$vo['scanCount']}次</font>
	    	<font>解锁{$vo['unlockCount']}次</font>
	    	<span><font>{$vo['city']}</font>{$vo['timeadd']}</span>
	    </section>
	   </section>
	    <section class="order_choose" name="1">
	    <foreach name="vo.operate"  item="v">
	   		<a <?php if($key == 'is_delete') echo "id='del_order'";?> href="javascript:void(0)" class="operate" order_id="{$vo['id']}" onclick="operate(this);"  name="{$key}">{$v}</a>
	    </foreach>
	    </section>		
	</section>
	</foreach>
</section> 
<if condition="$more eq 1">
<center id="hint" style='color:#999;font-size: 15px;'>上拉获取更多订单...</center>
<else/>
<notempty name="order_lists">
<center id="hint" style='color:#999;font-size: 15px;'>没有更多记录了...</center>
</notempty>
</if>
</body>
</html>
<script type="text/javascript">
      function operate(_this){
			var operate = $(_this).attr('name');
			var order_id = $(_this).attr("order_id");
			var data = {};
			$(_this).removeAttr("onclick");
			if( operate == 'is_delete'){
                 if(confirm("是否删除订单")){
                    $.post("/agent/delete_my_order",{'id':order_id},function(F){
                         //console.log(F);
                         alert(F.info);
                         if(F.status == 1){
                             window.location.href = "/agent/my_order_lists";
                         }
                     },'json')
                 }else{
                 	$(_this).attr("onclick","operate(this)");
                 }
		 	 }else if(operate == 'is_edit'){
		 	 	window.location.href = "/agent/order_submit/id/"+order_id;
		 	 }else if(operate == 'is_again'){
                 if(confirm("是否重新上架")){
                    $.post("/agent/passed_again",{'id':order_id},function(F){
                         //console.log(F);
                         alert(F.info);
                         if(F.status == 1){
                             window.location.href = "/agent/my_order_lists";
                         }
                     },'json')
                 }else{
                 	$(_this).attr("onclick","operate(this)");
                 }
		 	 	
		 	 }else if(operate == 'is_finish'){
                 if(confirm("是否确定成功")){
                    $.post("/agent/order_success",{'id':order_id},function(F){
                         //console.log(F);
                         alert(F.info);
                         if(F.status == 1){
                             window.location.href = "/agent/my_order_lists";
                         }
                     },'json')
                 }else{
                 	$(_this).attr("onclick","operate(this)");
                 }		 	 	 
		 	 }
	  }
	function get_info(_this){
      $order_id = $(_this).attr("value");
      window.location.href = "/agent/my_order_info/order_id/"+$order_id;
	}
</script>
{__NOLAYOUT__}