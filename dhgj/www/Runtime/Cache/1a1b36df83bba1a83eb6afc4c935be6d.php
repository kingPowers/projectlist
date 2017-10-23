<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="_STATIC_/2015/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <meta name="baidu-site-verification" content="uz6erIygdi" />
    <meta name="360-site-verification" content="9cedb3175aaa0271d72ae048045eacba" />
    <meta name="keywords" content="<?php echo ($pageseo["keywords"]); ?>">
    <meta name="description" content="<?php echo ($pageseo["description"]); ?>">
    <title><?php echo ($pageseo["title"]); ?></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
    <script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?980a2d3e99e048a8622eb266b479c3d9";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <style type="text/css" >
        .str{
         height:27px;
         width:75px;
         margin-left:20px;
    background: #474646;
        }
        .sdelete{
         position:absolute;
         font-size:12px;
/*         color:#999;*/
         width:10px;
         font-weight:900;
         padding:0px;
         border:none;
         cursor:pointer;
        }
        .surl{
         display:block;
         height:22px;
         line-height:22px;
         overflow:hidden;
         color:white;
         text-align: center;
        }
    </style>
    <script type="text/javascript" >
        function omover() {
//           var str = document.getElementById("mod_user");
//            str.style.background = "#d88f40";
             var listLink = document.getElementById("listLink");
            listLink.style.color = "gold";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "block";
        }
        function omout() {
            var listLink = document.getElementById("listLink");
            listLink.style.color = "gold";
            var mod_user = document.getElementById("mod_user");
            mod_user.style.background = "none";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "none";
        }
         
    </script>
</head>
<body>
<!--header-->
<div class="header">
    <div class="header_t">
        <div class="center">
            <ul class="head_t_l">
                <li class="head_t_tel">欢迎来到上海贷后管家服务有限公司</li>               
            </ul>
         
            <div class="head_t_r">
                <?php if(empty($_SESSION['member'])): ?><span class="t_login"><a href="/member/login">登录</a></span>
                      <span class="t_register"><a href="/member/register">注册</a></span>
                <?php else: ?>
                <div class="myzh">
                        <div class="mod_user" id="mod_user" onmouseover ="omover();" onmouseout ="omout();" style="height:25px;text-align: center;float:left;line-height:15px;width:100px;margin-left:50px;z-index:100;position: relative; font-weight:normal; font-style:normal; font-size:13px;">
                            <p > <a class="list_hist" id="listLink" style="color:gold;margin-left:20px;top:5px;" href="/member/account">我的账户</a></p>
                            <p style="height:10px;"></p>
                            <div id="lishistory" class="user_operate_list" style="display: none">
                                 
                            </div>
                        </div>
                        <span class="t_register"><a href="/member/account"><?php echo ($_SESSION['member']['username']); ?></a></span>
                        <span class="t_login"><a href="javascript:void(0)" class="ui-account-reg logout">退出</a></span>
                </div><?php endif; ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div class="header_b">
        
        <div class="center">
            <div class="head_b_l">
                <a href="/" class="a c_left"></a>                
            </div>           
            <ul class="head_b_r">
                <li><a href="/" <?php $module = array('order');$action=array('neworderlist');if((strtolower(MODULE_NAME)=='index')||(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action))) echo 'class="curr"';?> >首页</a></li>
                <li><a href="/company/index" <?php if(strtolower(MODULE_NAME)=='company') echo 'class="curr"';?>>公司介绍</a></li>
                <li><a href="/media/index" <?php if(strtolower(MODULE_NAME)=='media') echo 'class="curr"';?> >媒体公告</a></li>
                <li class="acitve"><a href="/member/account" <?php $module = array('order');$action=array('my_order_list');if((strtolower(MODULE_NAME)=='member')||(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action))) echo 'class="curr"';?> >我的账户</a></li>
            </ul>
            <div style="clear:both;"></div>
        </div>
    </div>
    <script>
        $(function(){
            var lopic = 0;
            var lolength = $(".logo_r_ul>li").length-1;
            var loheight = $(".logo_r_ul>li").eq(0).height();
            setTimeout(function(){
                lopic++;
                if(lopic>lolength){
                    $(".logo_r_ul").stop().animate({top:0});
                    lopic = 0;
                }else{
                    $(".logo_r_ul").stop().animate({top:-loheight});
                }
                var _this=arguments.callee;
                setTimeout(_this,5000);
            },5000)
        })
    </script>
</div>

<div class="ui-wp <?php echo strtolower(MODULE_NAME.'-'.ACTION_NAME);?>-wp">
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
                <input type="hidden" name="_edit_session_" value="<?php echo ($_edit_session_); ?>"  class="input_or">
				<input type="hidden" name="oid" value="<?php echo ($order_info['id']); ?>" class="input_or">
				<div class="w898">					
					<ul>
						<li>
							<span><em>*</em> 姓&nbsp;&nbsp;&nbsp;&nbsp; 名:</span>
							<input type="text" name="names" placeholder="请输入客户的姓名" needCheck="need" remind="姓名" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['names']); ?>"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 身份证号:</span>
							<input type="text" name="certiNumber" placeholder="请输入客户身份证号码" needCheck="need" remind="身份证" check_rule="certiNumber" valid="0" class="input_or" value="<?php echo ($order_info['certiNumber']); ?>"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 性&nbsp;&nbsp;&nbsp;&nbsp; 别:</span>
							<label><input type="radio" name="sex" <?php if($order_info['sex'] != 1): ?>checked=""<?php endif; ?> value="0"> 男 </label>
							<label><input type="radio" name="sex" <?php if($order_info['sex'] == 1): ?>checked=""<?php endif; ?> value="1"> 女 </label>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 借款城市:</span>
							<input type="text" name="loan_city" placeholder="请输入客户借款城市" needCheck="need" remind="借款城市" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['loan_city']); ?>"><small class="input_r"></small>
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
									 <?php if(!empty($order_info['pic_url']['user_pic'])): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="user_pic" src="<?php echo ($order_info['pic_url']['user_pic'][0]); ?>" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['user_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endif; ?>
							       <span class="ta">客户照片</span>
							</div>
							<div class="z_photo upimg-div fl" >			   
								 		   
									 <section class="z_file fl" <?php if(!empty($order_info['pic_url']['certi_pic']))echo "style='display:none;'";?>>
										<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
										<input type="file" name="certi_pic" id="certi_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp"  />
									 </section>
									 <?php if(!empty($order_info['pic_url']['certi_pic'])): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="certi_pic" src="<?php echo ($order_info['pic_url']['certi_pic'][0]); ?>" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['certi_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endif; ?>
								 <span class="ta">身份证正面</span>
							</div>
							<div class="z_photo upimg-div fl" >			   
								 		   
									 <section class="z_file fl" <?php if(!empty($order_info['pic_url']['certiBack_pic']))echo "style='display:none;'";?>>
										<img src="_STATIC_/2015/member/image/ico_upp.png" class="add-img">
										<input type="file" name="certiBack_pic" id="certiBack_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp"  />
									 </section>
									 <?php if(!empty($order_info['pic_url']['certiBack_pic'])): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="certiBack_pic" src="<?php echo ($order_info['pic_url']['certiBack_pic'][0]); ?>" up_name="<?php echo array_pop(explode("/",$order_info['pic_url']['certiBack_pic'][0]));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endif; ?>
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
							<input type="text" name="loan_company" placeholder="请输入客户的借款公司" needCheck="need" remind="借款公司" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['loan_company']); ?>"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 抵押公司:</span>
							<input type="text" name="mort_company" placeholder="请输入客户的抵押公司" needCheck="need" remind="抵押公司" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['mort_company']); ?>"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 借款金额:</span>
							<div class="k_or">
								<input type="text" name="loan_money" placeholder="请输入客户借款金额" needCheck="need" remind="借款金额" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['loan_money']); ?>">元<small class="input_r"></small>
							</div>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 借款期数:</span>
							<div class="k_or">
								<input type="text" name="return_num" placeholder="请输入客户已还期数"  needCheck="need" remind="已还期数" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['return_num']); ?>">期<small class="input_r"></small>
							</div>
						</li>
						<li>
							<span><em>*</em> 已还期数:</span>
							<div class="k_or">
								<input type="text" name="return_num" placeholder="请输入客户已还期数"  needCheck="need" remind="已还期数" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['return_num']); ?>">期<small class="input_r"></small>
							</div>
						</li>
					</ul>
					<div class="ht_div mb30">
						<span style="line-height: 90px;"><em>*</em> 借款合同:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <?php if(is_array($order_info['pic_url']['loanContract_pic'])): foreach($order_info['pic_url']['loanContract_pic'] as $key=>$vo): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="loanContract_pic" src="<?php echo ($vo); ?>" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endforeach; endif; ?>
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
							    <?php if(is_array($order_info['pic_url']['mortContract_pic'])): foreach($order_info['pic_url']['mortContract_pic'] as $key=>$vo): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="mortContract_pic" src="<?php echo ($vo); ?>" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endforeach; endif; ?>
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
							<input type="text" name="car_brand" placeholder="请输入客户的车辆型号" needCheck="need" remind="车辆型号" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['car_brand']); ?>"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 车牌号码:</span>
							<input type="text" name="plate_num" placeholder="请输入客户的车牌号码"  needCheck="need" remind="车牌号码" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['plate_num']); ?>"><small class="input_r"></small>
						</li>
						<li>
							<span><em>*</em> 车架号码:</span>
							<input maxlength="17" type="text" name="frame_num" placeholder="请输入客户的车架号码"  needCheck="need" remind="车架号码" check_rule="frame_num" valid="0" class="input_or" value="<?php echo ($order_info['frame_num']); ?>"><small class="input_r"></small>
						</li>						
					</ul>
					<div class="ht_div mb30">
						<span style="line-height: 90px;"><em>*</em> 行&nbsp;&nbsp;驶&nbsp;&nbsp;证:</span>
						<div class="fl">
							<div class="z_photo upimg-div clear" style="width:898px; padding: 0 0 0 92px;">	
							    <?php if(is_array($order_info['pic_url']['travelLicense_pic'])): foreach($order_info['pic_url']['travelLicense_pic'] as $key=>$vo): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="travelLicense_pic" src="<?php echo ($vo); ?>" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endforeach; endif; ?>
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
							    <?php if(is_array($order_info['pic_url']['driveLicense_pic'])): foreach($order_info['pic_url']['driveLicense_pic'] as $key=>$vo): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="driveLicense_pic" src="<?php echo ($vo); ?>" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endforeach; endif; ?>
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
							    <?php if(is_array($order_info['pic_url']['carRegistration_pic'])): foreach($order_info['pic_url']['carRegistration_pic'] as $key=>$vo): ?><section class="up-section fl ml"><span class="up-span"></span><img class="close-upimg" src="_STATIC_/2015/uploadpicture/img/a7.png"><img class="up-img" type="carRegistration_pic" src="<?php echo ($vo); ?>" up_name="<?php echo array_pop(explode("/",$vo));?>"><p class="img-name-p">0A02279CA2246FCA967CCB29C78F18A4 - 副本.jpg</p><input id="taglocation" name="taglocation" value="" type="hidden"><input id="tags" name="tags" value="" type="hidden"><input type="text" style="display:none;" name="undefined" value="undefined"></section><?php endforeach; endif; ?>
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
							<input type="text" name="GPS_member" placeholder="请输入登录帐号" needCheck="need" remind="账号" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['GPS_member']); ?>"><small class="input_r"></small>
						</li>
						<li style="margin-left: 220px;">
							<span><em>*</em> 登录密码:</span>
							<input type="text" name="GPS_password" placeholder="请输入客户的登录密码" needCheck="need" remind="登录密码" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['GPS_password']); ?>"><small class="input_r"></small>
						</li>
						<li style="width: 500px;">
							<span><em>*</em> 平台地址:</span>
							<input type="text" name="GPS_url" placeholder="请输入平台地址" class="input_or" needCheck="need" remind="平台地址" check_rule="null" valid="0" style="width: 400px;" value="<?php echo ($order_info['GPS_url']); ?>"><small class="input_r" style="left:20%"></small>
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
								<input type="text" name="trail_price" placeholder="请输入拖车报价" needCheck="need" remind="报价" check_rule="null" valid="0" class="input_or" value="<?php echo ($order_info['trail_price']); ?>">元<small class="input_r" ></small>
							</div>
						</li>										
					</ul>					
				</div>
				<a class="btn_upo"><?php if($is_edit == 1): ?>修改订单<?php else: ?>上传订单<?php endif; ?></a>
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
      url:"<?php echo U('order/add_img');?>",
      success: function(data) {
      	// alert(data.data.name);
      	//$(".up-img").attr("src",data.data);
      },
      error: function(err) {
      },
});
})
</script>
    <div class="ui-clearfix"></div>
</div>
<div class="footer">
    <div class="center">
        <div class="footer_t">
            <ul class="c_fot_ul c_left">
                <li>
                    <h3 style="cursor: pointer;" onclick="window.location.href='/order/neworderlist'">最新订单</h3> 
                    <h3 style="cursor: pointer;"  onclick="window.location.href='/company/index'">公司介绍</h3>                    
                </li>
                <li>
                    <h3 style="cursor: pointer;" onclick="window.location.href='/order/neworderlist'">历史订单</h3> 
                    <h3 style="cursor: pointer;" onclick="window.location.href='/media/index'">媒体公告</h3>                    
                </li> 
            </ul>
            <div class="fot_t_r c_right">                
                <div class="fot_t_r_r c_right">
                    <div class="fot_t_tel">                       
                        <div class="fot_t_ra">
                            <div>工作日9 00-18:00</div>
                            <div class="fot_t_ra_ff">节假日9:00-16:00</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div class="footer_m">
        <div class="center">
            <p><span class="c_left" style="margin-left:-60px;">友情链接:</span>
                <a href="http://www.cnfol.com/" target="_blank">中金在线</a>
                <a href="http://www.huagu.com/" target="_blank">华股财经</a>
                <a href="http://www.afinance.cn/" target="_blank">第一金融网</a>
                <a href="http://www.beelink.com/" target="_blank">百灵网</a>
                <a href="javascript:;" target="_blank">广州万隆网贷投资理财</a>
                <a href="javascript:;" target="_blank">保险财经</a>
                <a href="http://www.91jucai.com/" target="_blank">聚财网</a>
                <a href="http://www.southfi.com/index.html" target="_blank">南方财经网</a>
                <a href="http://www.p2peye.com/" target="_blank">网贷天眼</a>
                <a href="http://www.hifly.tv/" target="_blank">金鹰网</a>
                <a href="http://www.wdtianhe.com/" target="_blank">网贷天和</a>
                <a href="http://www.wangdaiclub.com/" target="_blank">网贷俱乐部</a>
                <a href="http://www.01p2p.net/" target="_blank">第一网贷</a>
                <a href="http://www.wangdaijiamen.com/" target="_blank">网贷家门</a>               
            </p>
        </div>
    </div>
    <div class="footer_b">
        <div class="center">
             <p class="footer_b_p"  style="margin:0;"><span>上海峡古科技有限公司|</span>Copyright © changsheng360.com<a href="http://www.miitbeian.gov.cn" target="_blank" style="color:#fff;">沪ICP备16049012号-1</a></p>
        </div>
        
    </div>
</div>
<script>
//   $('.calcIn').click(function(){
//      $(".syCalc").css("display","block");
//   });
//   $('.calcIn').mouseout(function(){
//      $(".syCalc").css("display","none");
//   });
//   
    $(function(){
        $('.c_plen').each(function(index){
            var speW = $(this).siblings('.num').text();
            $(this).children('.speed').width(speW);
        })

        $('.img_min_list li').on('click',function(){
            var self = $(this),
                idx = self.index(),
                src = self.data('linksrc'),
                bigimg = self.data('bigimg');
            self.addClass('current').siblings().removeClass('current');
            $('.news_b .img_holder').attr('href',src);
            $('.news_b .img_holder img').attr('src',bigimg);
            $('.news_b .ir dl').eq(idx).show().siblings().hide();
        });

        $('a.logout').click(function() {
            jdbox.alert(2, '退出中，请等候...');
            $.post('/member/loginOut.html',null, function(result) {
                if(result.data){
                    var jsobj = $(result.data),mvl = document.createElement('script');
                    mvl.type = 'text/javascript';
                    mvl.src = jsobj.attr('src');
                    var oHead = document.getElementsByTagName('HEAD').item(0);
                    oHead.appendChild(mvl);
                }
                setTimeout(function(){ referpage(); },1000);
            },'json');
        });
        $('.scroll-calculator').click(function(){
            return jdbox.iframe('/public/calculator.html');
        })
        
        var tiptext;
        $("#leftsead a").hover(function(){
            tiptext = $(this).attr('data-text');
            $(this).html(tiptext);
        },function(){
            $(this).html('');
        })
        
        $(".scrollT").click(function(){
            $("body,html").animate({scrollTop:0})
        })


    })
</script>



</body>
</html>