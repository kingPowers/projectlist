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
						<li>姓名：<?php echo ($orderInfo['names']); ?></li>
						<li>身份证号：<?php echo ($orderInfo['certiNumber']); ?></li>
						<li>性别：<?php echo ($orderInfo['sex']); ?></li>
						<li>借款城市： <?php echo ($orderInfo['loan_city']); ?></li>
						<li>借款公司： <?php echo ($orderInfo['loan_company']); ?></li>
						<li>抵押公司： <?php echo ($orderInfo['mort_company']); ?></li>
						<li>借款金额： <?php echo ($orderInfo['loan_money']); ?> 元</li>
						<li>已还期数： <?php echo ($orderInfo['return_num']); ?></li>
						<li>车辆型号：  <?php echo ($orderInfo['car_brand']); ?></li>
						<li>车牌号码： <?php echo ($orderInfo['plate_num']); ?></li>
						<li>车架号码：  <?php echo ($orderInfo['frame_num']); ?></li>
						<li>拖车报价：
						<?php if(empty($orderInfo['dis_price'])): echo ($orderInfo['trail_price']); ?>
						   <?php else: ?>$orderInfo['dis_price']}<?php endif; ?>元</li>
						<li>登录帐号：  <?php echo ($orderInfo['GPS_member']); ?></li>
						<li>登录密码： <?php echo ($orderInfo['GPS_password']); ?></li>
						<li style="width: 100%;">平台地址：  <?php echo ($orderInfo['GPS_url']); ?></li>
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
				    		  <?php if(is_array($orderInfo['pic_url']['user_pic'][0])): foreach($orderInfo['pic_url']['user_pic'][0] as $key=>$vo): ?><div class="photo_css mga">
				    				<img src="<?php echo ($vo); ?>" class="bdb">
				    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
				    			</div><?php endforeach; endif; ?>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="photo_li">
				    		  <?php if(is_array($orderInfo['pic_url']['certi_pic'][0])): foreach($orderInfo['pic_url']['certi_pic'][0] as $key=>$vo): ?><div class="photo_css mga">
				    				<img src="<?php echo ($vo); ?>" class="bdb">
				    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
				    			</div><?php endforeach; endif; ?>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="photo_li">
				    		  <?php if(is_array($orderInfo['pic_url']['certiBack_pic'][0])): foreach($orderInfo['pic_url']['certiBack_pic'][0] as $key=>$vo): ?><div class="photo_css mga">
				    				<img src="<?php echo ($vo); ?>" class="bdb">
				    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
				    			</div><?php endforeach; endif; ?>
				    		</div>
				    	</div>
				    	<div class="div_hide">
				    		<div class="slider1" style="height: 30%">
				    			<a class="buttons prev">left</a>
				    			<div class="viewport">
								<ul class="overview"> 				    		
					    		   <?php if(is_array($orderInfo['pic_url']['loanContract_pic'])): foreach($orderInfo['pic_url']['loanContract_pic'] as $key=>$vo): ?><li class="photo_li">
						    		     <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="<?php echo ($v); ?>" class="bdb">
							    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
							    			 </div><?php endforeach; endif; ?>
						    		   </li><?php endforeach; endif; ?>
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
					    		   <?php if(is_array($orderInfo['pic_url']['mortContract_pic'])): foreach($orderInfo['pic_url']['mortContract_pic'] as $key=>$vo): ?><li class="photo_li">
						    		     <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="<?php echo ($v); ?>" class="bdb">
							    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
							    			 </div><?php endforeach; endif; ?>
						    		   </li><?php endforeach; endif; ?>
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
					    		   <?php if(is_array($orderInfo['pic_url']['travelLicense_pic'])): foreach($orderInfo['pic_url']['travelLicense_pic'] as $key=>$vo): ?><li class="photo_li">
						    		     <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="<?php echo ($v); ?>" class="bdb">
							    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
							    			 </div><?php endforeach; endif; ?>
						    		   </li><?php endforeach; endif; ?>
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
					    		   <?php if(is_array($orderInfo['pic_url']['driveLicense_pic'])): foreach($orderInfo['pic_url']['driveLicense_pic'] as $key=>$vo): ?><li class="photo_li">
						    		     <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="<?php echo ($v); ?>" class="bdb">
							    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
							    			 </div><?php endforeach; endif; ?>
						    		   </li><?php endforeach; endif; ?>
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
					    		   <?php if(is_array($orderInfo['pic_url']['carRegistration_pic'])): foreach($orderInfo['pic_url']['carRegistration_pic'] as $key=>$vo): ?><li class="photo_li">
						    		     <?php if(is_array($vo)): foreach($vo as $key=>$v): ?><div class="photo_css fl <?php echo ($key==0)?"":"ml20"?>">
							    				<img src="<?php echo ($v); ?>" class="bdb">
							    				<a href="<?php echo ($vo); ?>" download="img.png">下载</a>
							    			 </div><?php endforeach; endif; ?>
						    		   </li><?php endforeach; endif; ?>
					    		</ul>
								</div>
					    		<a class="next">right</a> 
				    		</div>	    			
				    	</div>
				    </div>
				</div>
				<?php if(!empty($orderInfo['is_edit'])): if($orderInfo['is_edit'] == 1): ?><a class="btn_upo" href="/order/edit_order/oid/<?php echo ($orderInfo['id']); ?>" style="margin-top: 0;">修改订单</a><?php endif; ?>
				  <?php else: ?>
				  <?php if(!empty($zipUrl)): ?><a class="btn_upo" href="<?php echo ($zipUrl); ?>" style="margin-top: 0;">一键下载</a><?php endif; endif; ?>
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


    <div class="ui-clearfix"></div>
</div>
<div class="footer">
    <div class="center">
        <div class="footer_t">
            <ul class="c_fot_ul c_left">
                <li>
                    <h3>最新订单</h3> 
                    <h3>公司介绍</h3>                    
                </li>
                <li>
                    <h3>历史订单</h3> 
                    <h3>媒体公告</h3>                    
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