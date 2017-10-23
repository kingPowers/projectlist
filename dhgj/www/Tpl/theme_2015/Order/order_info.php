<link rel="stylesheet" href="_STATIC_/2015/css/order.css">
<link rel="stylesheet" href="_STATIC_/2015/css/swiper.min.css">
<script src="_STATIC_/2015/js/jquery.tinycarousel.min.js"></script>
<div class="content">
	<div class="center_div">
		<div class="d_nav_box">
			<p>
				当前位置
				<a href="/">贷后管家首页</a> > <a href="/order/my_order_list">我的订单</a>
				> <a>订单详情</a>
			</p>
		</div>
		<div class="w1198 bdb">
			<h2>订单详情</h2>
			<div class="w1118">
				<div class="b_line">
					<span class="b_tit">基本信息</span>
				</div>
				<div class="w918">
					<ul>
						<li>姓名：{$orderInfo['names']}</li>
						<li>身份证号：{$orderInfo['certiNumber']}</li>
						<li>性别：{$orderInfo['sex']}</li>
						<li>借款城市： {$orderInfo['loan_city']}</li>
						<li>借款公司： {$orderInfo['loan_company']}</li>
						<li>抵押公司： {$orderInfo['mort_company']}</li>
						<li>借款金额： {$orderInfo['loan_money']} 元</li>
						<li>已还期数： {$orderInfo['return_num']}</li>
						<li>车辆型号：  {$orderInfo['car_brand']}</li>
						<li>车牌号码： {$orderInfo['plate_num']}</li>
						<li>车架号码：  {$orderInfo['frame_num']}</li>
						<li>拖车报价：
						<empty name="orderInfo['dis_price']" >
						{$orderInfo['trail_price']}
						   <else/>$orderInfo['dis_price']}
						</empty>元</li>
						<li>登录帐号：  {$orderInfo['GPS_member']}</li>
						<li>登录密码： {$orderInfo['GPS_password']}</li>
						<li style="width: 100%;">平台地址：  {$orderInfo['GPS_url']}</li>
					</ul>
				</div>
			</div>
			<div class="ba_min">
				<div class="w1138">
					<ul class="ck_header">  
			           <li class="liStyle show blue_bg">客户照片</li>  
			           <li class="liStyle hide">身份证正面</li>  
			           <li class="liStyle hide">身份证反面</li>  
			           <li class="liStyle hide">借款合同</li>  
			           <li class="liStyle hide">抵押合同</li>  
			           <li class="liStyle hide">行驶证</li>
			           <li class="liStyle hide">驾驶证</li>  
			           <li class="liStyle hide">车辆登记证</li>  
				    </ul>
				    <div class="photo_info">
				    	<div class="div_show">
				    		<div class="photo_li">
				    		  <foreach name="orderInfo['pic_url']['user_pic'][0]" item="vo">
				    			<div class="photo_css mga">
				    				<img src="{$vo}" class="bdb">
				    				<a href="{$vo}" download="img.png">下载</a>
				    			</div>
				    		  </foreach>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="photo_li">
				    		  <foreach name="orderInfo['pic_url']['certi_pic'][0]" item="vo">
				    			<div class="photo_css mga">
				    				<img src="{$vo}" class="bdb">
				    				<a href="{$vo}" download="img.png">下载</a>
				    			</div>
				    		  </foreach>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="photo_li">
				    		  <foreach name="orderInfo['pic_url']['certiBack_pic'][0]" item="vo">
				    			<div class="photo_css mga">
				    				<img src="{$vo}" class="bdb">
				    				<a href="{$vo}" download="img.png">下载</a>
				    			</div>
				    		  </foreach>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <foreach name="orderInfo['pic_url']['loanContract_pic']" item="vo">
						    		   <li class="photo_li">
						    		     <foreach name="vo" item="v" key="key">
							    			 <div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="{$v}" class="bdb">
							    				<a href="{$vo}" download="img.png">下载</a>
							    			 </div>
						    			 </foreach>
						    		   </li>
					    		   </foreach>
					    		</ul>
								</div>
					    		<a class="next">right</a>
				    		</div>	    			
				    	</div>
				    	<div class="div_hide">
				    	   <div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <foreach name="orderInfo['pic_url']['mortContract_pic']" item="vo">
						    		   <li class="photo_li">
						    		     <foreach name="vo" item="v" key="key">
							    			 <div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="{$v}" class="bdb">
							    				<a href="{$v}" download="img.png">下载</a>
							    			 </div>
						    			 </foreach>
						    		   </li>
					    		   </foreach>
					    		</ul>
								</div>
					    		<a class="next">right</a> 
				    		</div>
				    	</div>	    			
				    	<div class="div_hide">
                            <div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <foreach name="orderInfo['pic_url']['travelLicense_pic']" item="vo">
						    		   <li class="photo_li">
						    		     <foreach name="vo" item="v" key="key">
							    			 <div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="{$v}" class="bdb">
							    				<a href="{$v}" download="img.png">下载</a>
							    			 </div>
						    			 </foreach>
						    		   </li>
					    		   </foreach>
					    		</ul>
								</div>
					    		<a class="next">right</a>
				    		</div>	    			
				    	</div>
				    	<div class="div_hide">
                            <div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <foreach name="orderInfo['pic_url']['driveLicense_pic']" item="vo">
						    		   <li class="photo_li">
						    		     <foreach name="vo" item="v" key="key">
							    			 <div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="{$v}" class="bdb">
							    				<a href="{$v}" download="img.png">下载</a>
							    			 </div>
						    			 </foreach>
						    		   </li>
					    		   </foreach>
					    		</ul>
								</div>
					    		<a class="next">right</a>
				    		</div>	    			
				    	</div>
				    	<div class="div_hide">
                           <div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <foreach name="orderInfo['pic_url']['carRegistration_pic']" item="vo">
						    		   <li class="photo_li">
						    		     <foreach name="vo" item="v" key="key">
							    			 <div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="{$v}" class="bdb">
							    				<a href="{$v}" download="img.png">下载</a>
							    			 </div>
						    			 </foreach>
						    		   </li>
					    		   </foreach>
					    		</ul>
								</div>
					    		<a class="next">right</a> 
				    		</div>	    			
				    	</div>
				    </div>
				</div>
				<notempty name="orderInfo['is_edit']">
				      <if condition="$orderInfo['is_edit'] eq 1">
				  		<a class="btn_upo" href="/order/edit_order/oid/{$orderInfo['id']}" style="margin-top: 0;">修改订单</a>
				  	  </if>
				  <else/>
				  <notempty name="zipUrl">
						<a class="btn_upo" href="{$zipUrl}" style="margin-top: 0;">一键下载</a>
				  </notempty>  
			    </notempty>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">  
    $(function () {            
        var ary = $(".w1138 > ul > li").click(function () {
            $(this).parent().find("li.show").addClass("hide").removeClass("show blue_bg");   
            $(this).addClass("show blue_bg").removeClass("hide");   
            $(".w1138  > div > div.div_show").addClass("div_hide").removeClass("div_show"); 
            $(".w1138  > div > div:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
                return "div_show";   
            }).removeClass("div_hide");  
        }).toArray();
    });
	$(document).ready(function(){
		$('.slider1').tinycarousel();	
	});
</script>
<style type="text/css">
	/* Tiny Carousel */  
.slider1 {  
height: 1%;  
overflow: hidden; 
width: 958px; 
margin:0 auto;
}  
.slider1 .viewport {
width: 854px;  
height: 240px;  
overflow: hidden;  
position: relative;
margin: 0 auto;
float: left;    
}  
.slider1 .buttons {  
background: url("_STATIC_/2015/member/image/btn_gd.png") no-repeat scroll 0 0 transparent;  
display: block;
text-indent: -999em;
width: 42px;  
height: 119px;  
overflow: hidden;  
position: relative;
margin: 40px 10px 0 0; 
float: left;  
}  
.slider1 .next { 
width: 42px;  
height: 119px;
display: block;
text-indent: -999em;
overflow: hidden;  
position: relative;
float: left;  
background: url("_STATIC_/2015/member/image/btn_gded.png") no-repeat scroll 0 0 transparent;
margin: 40px 0 0 10px; 
}  
.slider1 .disable {  
visibility: hidden; 
}  
.slider1 .overview {  
list-style: none;  
position: absolute;  
padding: 0;  
margin: 0;  
width: 240px;  
left: 0 top: 0;  
}  
.slider1 .overview li {  
float: left;
}  
</style>

