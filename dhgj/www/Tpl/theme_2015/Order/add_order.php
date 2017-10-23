<link rel="stylesheet" href="_STATIC_/2015/css/order.css">
<link href="_STATIC_/2015/uploadpicture/css/common.css" type="text/css" rel="stylesheet"/>
<link href="_STATIC_/2015/uploadpicture/css/index.css" type="text/css" rel="stylesheet"/>
<script type="text/javascript">var static = '_STATIC_';</script>
<script src="_STATIC_/2015/uploadpicture/js/imgPlugin.js"></script>
<script src="_STATIC_/2015/js/order.js"></script>
<style type="text/css">
.w898 ul li{position: relative;}
.input_r{position: absolute;bottom: 5%;left: 30%;color: red;display: inline-block;width:100%;font-size: 10px}
</style>
<aside class="mask works-mask">
	<div class="mask-content">
		<p class="del-p ">您确定要删除作品图片吗？</p>
		<p class="check-p"><span class="del-com wsdel-ok">确定</span><span class="wsdel-no">取消</span></p>
	</div>
</aside>
<form action="/order/add_order" method="post">
<div class="content">
	<div class="center_div">
		<div class="d_nav_box">
			<p>
				当前位置
				<a href="/">贷后管家首页</a>
				> <a href="/order/neworderlist">上传订单</a>
			</p>
		</div>
		<div class="w1198 bdb">
			<h2>填写客户信息</h2>
			<div class="w1118">
				<div class="b_line">
					<span class="b_tit">个人信息</span>
				</div>
                <input type="hidden" name="_edit_session_" value="{$_edit_session_}"  class="input_or">
				<input type="hidden" name="oid" value="{$order_info['id']}" class="input_or">
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 姓&nbsp;&nbsp;&nbsp;&nbsp; 名:</span>
							<input type="text" name="names" placeholder="请输入客户的姓名" needCheck="need" remind="姓名" check_rule="null" valid="0" class="input_or" value="{$order_info['names']}"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 身份证号:</span>
							<input type="text" name="certiNumber" placeholder="请输入客户身份证号码" needCheck="need" remind="身份证" check_rule="certiNumber" valid="0" class="input_or" value="{$order_info['certiNumber']}"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 性&nbsp;&nbsp;&nbsp;&nbsp; 别:</span>
							<label><input type="radio" name="sex" <if condition="$order_info['sex'] neq 1"> checked="" </if> value="0"> 男 </label>
							<label><input type="radio" name="sex" <if condition="$order_info['sex'] eq 1"> checked="" </if> value="1"> 女 </label>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 借款城市:</span>
							<input type="text" name="loan_city" placeholder="请输入客户借款城市" needCheck="need" remind="借款城市" check_rule="null" valid="0" class="input_or" value="{$order_info['loan_city']}"><small class="input_r"></small>
						</li>
					</ul>
					<div>
						<span><em>*</em> 照&nbsp;&nbsp;&nbsp;&nbsp; 片:</span>
						<section class="photo_div bdb">
						   
								<div class="z_photo upimg-div fl" >									   
									 <section class="z_file fl" <?php if(!empty($order_info['pic_url']['user_pic']))echo "style='display:none;'";?>>
										<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
										<input type="file" name="user_pic" id="user_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp"  />
									</section>
									 <notempty name="order_info['pic_url']['user_pic']">
                                     <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="user_pic" src="{$order_info['pic_url']['user_pic'][0]}" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['user_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							       </notempty>
							       <span class="ta">客户照片</span>
							</div>
							<div class="z_photo upimg-div fl" >			   
								 		   
									 <section class="z_file fl" <?php if(!empty($order_info['pic_url']['certi_pic']))echo "style='display:none;'";?>>
										<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
										<input type="file" name="certi_pic" id="certi_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp"  />
									 </section>
									 <notempty name="order_info['pic_url']['certi_pic']">
                                     <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="certi_pic" src="{$order_info['pic_url']['certi_pic'][0]}" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['certi_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							       </notempty>
								 <span class="ta">身份证正面</span>
							</div>
							<div class="z_photo upimg-div fl" >			   
								 		   
									 <section class="z_file fl" <?php if(!empty($order_info['pic_url']['certiBack_pic']))echo "style='display:none;'";?>>
										<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
										<input type="file" name="certiBack_pic" id="certiBack_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp"  />
									 </section>
									 <notempty name="order_info['pic_url']['certiBack_pic']">
                                     <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="certiBack_pic" src="{$order_info['pic_url']['certiBack_pic'][0]}" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['certiBack_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							       </notempty>
								 <span class="ta">身份证反面</span>
							</div>
						</section>
					</div>
				</div>
				<div class="b_line">
					<span class="b_tit">借款信息</span>
				</div>
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 借款公司:</span>
							<input type="text" name="loan_company" placeholder="请输入客户的借款公司" needCheck="need" remind="借款公司" check_rule="null" valid="0" class="input_or" value="{$order_info['loan_company']}"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 抵押公司:</span>
							<input type="text" name="mort_company" placeholder="请输入客户的抵押公司" needCheck="need" remind="抵押公司" check_rule="null" valid="0" class="input_or" value="{$order_info['mort_company']}"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 借款金额:</span>
							<div class="k_or">
								<input type="text" name="loan_money" placeholder="请输入客户借款金额" needCheck="need" remind="借款金额" check_rule="null" valid="0" class="input_or" value="{$order_info['loan_money']}">元<small class="input_r"></small>
							</div>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 借款期数:</span>
							<div class="k_or">
								<input type="text" name="return_num" placeholder="请输入客户已还期数"  needCheck="need" remind="已还期数" check_rule="null" valid="0" class="input_or" value="{$order_info['return_num']}">期<small class="input_r"></small>
							</div>
						</li>
						<li>
							<span><em>*</em> 已还期数:</span>
							<div class="k_or">
								<input type="text" name="return_num" placeholder="请输入客户已还期数"  needCheck="need" remind="已还期数" check_rule="null" valid="0" class="input_or" value="{$order_info['return_num']}">期<small class="input_r"></small>
							</div>
						</li>
					</ul>
					<div class="ht_div mb30">
						<span style="line-height: 90px;"><em>*</em> 借款合同:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <foreach name="order_info['pic_url']['loanContract_pic']" item="vo">
							    <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="loanContract_pic" src="{$vo}" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							    </foreach>
								<section class="z_file fl">
									<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
									<input type="file" name="loanContract_pic" id="loanContract_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
								</section>							
							</div>
						</div>																
					</div>
					<div class="ht_div">
						<span style="line-height: 90px;"><em>*</em> 抵押合同:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <foreach name="order_info['pic_url']['mortContract_pic']" item="vo">
							    <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="mortContract_pic" src="{$vo}" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							    </foreach>
								<section class="z_file fl">
									<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
									<input type="file" name="mortContract_pic" id="mortContract_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
								</section>	
						</div>								
					</div>
				</div>
			</div>
				<div class="b_line">
					<span class="b_tit">车辆信息</span>
				</div>
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 车辆型号:</span>
							<input type="text" name="car_brand" placeholder="请输入客户的车辆型号" needCheck="need" remind="车辆型号" check_rule="null" valid="0" class="input_or" value="{$order_info['car_brand']}"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 车牌号码:</span>
							<input type="text" name="plate_num" placeholder="请输入客户的车牌号码"  needCheck="need" remind="车牌号码" check_rule="null" valid="0" class="input_or" value="{$order_info['plate_num']}"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 车架号码:</span>
							<input maxlength="17" type="text" name="frame_num" placeholder="请输入客户的车架号码"  needCheck="need" remind="车架号码" check_rule="frame_num" valid="0" class="input_or" value="{$order_info['frame_num']}"><small class="input_r"></small>
						</li>						
					</ul>
					<div class="ht_div mb30">
						<span style="line-height: 90px;"><em>*</em> 行&nbsp;&nbsp;驶&nbsp;&nbsp;证:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <foreach name="order_info['pic_url']['travelLicense_pic']" item="vo">
							    <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="travelLicense_pic" src="{$vo}" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							    </foreach>
								<section class="z_file fl">
									<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
									<input type="file" name="travelLicense_pic" id="travelLicense_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
								</section>	
						    </div>			
						</div>							
					</div>
					<div class="ht_div mb30">
						<span style="line-height: 90px;"><em>*</em> 驾&nbsp;&nbsp;驶&nbsp;&nbsp;证:</span>	
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <foreach name="order_info['pic_url']['driveLicense_pic']" item="vo">
							    <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="driveLicense_pic" src="{$vo}" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							    </foreach>
								<section class="z_file fl">
									<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
									<input type="file" name="driveLicense_pic" id="driveLicense_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
								</section>	
						    </div>	
						</div>							
					</div>
					<div class="ht_div">
						<span style="width: 100px; line-height: 90px;"><em>*</em> 车辆登记证:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <foreach name="order_info['pic_url']['carRegistration_pic']" item="vo">
							    <section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="carRegistration_pic" src="{$vo}" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section>
							    </foreach>
								<section class="z_file fl">
									<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
									<input type="file" name="carRegistration_pic" id="carRegistration_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
								</section>	
						    </div>	
						</div>							
					</div>
				</div>
				<div class="b_line">
					<span class="b_tit">GPS信息</span>
				</div>
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 登录帐号:</span>
							<input type="text" name="GPS_member" placeholder="请输入登录帐号" needCheck="need" remind="账号" check_rule="null" valid="0" class="input_or" value="{$order_info['GPS_member']}"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 登录密码:</span>
							<input type="text" name="GPS_password" placeholder="请输入客户的登录密码" needCheck="need" remind="登录密码" check_rule="null" valid="0" class="input_or" value="{$order_info['GPS_password']}"><small class="input_r"></small>
						</li>
						<li style="width: 500px;">
							<span><em>*</em> 平台地址:</span>
							<input type="text" name="GPS_url" placeholder="请输入平台地址" class="input_or" needCheck="need" remind="平台地址" check_rule="null" valid="0" style="width: 400px;" value="{$order_info['GPS_url']}"><small class="input_r" style="left:20%"></small>
						</li>						
					</ul>					
				</div>
				<div class="b_line">
					<span class="b_tit">拖车信息</span>
				</div>
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 拖车报价:</span>
							<div class="k_or">
								<input type="text" name="trail_price" placeholder="请输入拖车报价" needCheck="need" remind="报价" check_rule="null" valid="0" class="input_or" value="{$order_info['trail_price']}">元<small class="input_r" ></small>
							</div>
						</li>										
					</ul>					
				</div>
				<a class="btn_upo"><if condition="$is_edit eq 1">修改订单<else/>上传订单</if></a>
			</div>
		</div>
	</div>
</div>
</form>
<script type="text/javascript">
var is_delay = 0;
var delay = setInterval(function(delay){if(is_delay==1)clearInterval(delay);is_delay = 1;},1000);
$(function(){
$("form").check(true);
$("form").submitOrder(true);
var oid = $("input[name='oid']").val();
$(".file").takungaeImgup({
      formData: {
          "oid":oid
      },
      url:"{:U('order/add_img')}",
      success: function(data) {
      	// alert(data.data.name);
      	//$(".up-img").attr("src",data.data);
      },
      error: function(err) {
      },
});
})
</script>