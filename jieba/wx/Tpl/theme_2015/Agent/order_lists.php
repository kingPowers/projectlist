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
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	  <script type="text/javascript">
	$(function(){
            if($("input[name='province']").val() != ""){
                 $province_code = $("select[name='change_pro']").find("option:selected").attr('id');
                 //alert($province_code);
                $post_city = $("input[name='city']").val();
                $.post("/agent/get_citys.html",{"province_code":$province_code},function(F){
                console.log(F);
                  if(F.status == 1){
                     F = eval(F.data);
                     $(".change_city").html("<option >请选择城市</option>");
                     for(var i=0;i<F.length;i++){
                        var city_option = "<option id='" + F[i]['city_code'] + "'";
                        var s = new RegExp(F[i]['city_name'].trim());
                        var r = s.test($post_city.trim());
                        if(r){
                            city_option += "selected=''";
                        }
                        city_option += ">" + F[i]['city_name'] + "</option>";
                        $(".change_city").append(city_option);
                     }      
                  }
               },'json')
            }
			page = 2; is_loading = 1;
			$(window).scroll(function(){
				if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
           var data= {};
           $(data).attr("province",$("input[name='province']").val());
           $(data).attr("city",$("input[name='city']").val());
           $(data).attr("status",$("input[name='status']").val());
           $(data).attr("is_fullmoney",$("input[name='is_fullmoney']").val());
           $(data).attr("jobs",$("input[name='jobs']").val());
           $(data).attr("page",page++);
           $(data).attr("is_ajax",1);
					$.post("/agent/order_lists",data,function(F){
	           console.log(F);
	           if(F.status == 1){
	              var str = "";
								$.each(F.data,function(i,item){	
								  if(item.avatar == ''){
									  $avatar = "_STATIC_/2015/image/yydai/agent/ico_header.png";
								  }else{
									  $avatar = item.avatar;
								  }			
									str += "<section class='order_one' value='"+ item.id +"' onclick='get_info(this);' id='"+ item.memberid +"'><section class='order_otit'><section class='order_header'><span style='display: inline-block; border-radius: 50%; background: url("+ $avatar +")no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;'></span></section><section class='order_info'><p>"+item.nickname;
								  if(item.is_vip == 1){
	                    str += "<img src='"+ item.vip_picurl +"'>";   
								    }
									str += "<font>";
									str += item.company_name + "</font></p>";
									str += "<p style='margin-top: 1%;'><span> 车贷 </span><span>" + item.is_fullmoney + "</span><span>" + item.jobs + "</span></p></section>";
									str += "<section class='order_tag "+ item.status_bg +"'>" + item.status_name + "</section></section>";
									str += "<section class='order_con'>" + item.remark + "</section>";
									str += "<section class='order_footer'><font>浏览" + item.scanCount + "次</font><font>解锁" + item.unlockCount + "次</font><span><font>" + item.city + "</font>" + item.timeadd + " </span></section>";
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
		});
	 $(function () {            
        var ary = $(".order_icon > a").click(function () {
        	$("#bg_option").show();
            $(".option_div").show();
            $(this).parent().find("a.show").addClass("hide").removeClass("blue_css");   
            $(this).addClass("blue_css");                
            $(".option_div > ul.ulShow").addClass("ulHide").removeClass("ulShow"); 
            $(".option_div > ul:eq(" + $.inArray(this, ary) + ")").addClass(function () {  
                return "ulShow";   
            }).removeClass("ulHide");  
        }).toArray();
       $("#bg_option").click(function(){
          $("#bg_option,.option_div").hide();
       })
    	$(".add_div").click(function(){
			$(".choose_city,#bg_option").show();
			$("#bg_option").css("z-index","10");
			$("#tk_cancel").click(function(){
				$(".choose_city,#bg_option").hide();
			})
		})
		 $("select[name='change_pro']").change(function(){
			   var data = {};
			   $(data).attr('province_code',$(this).find("option:selected").attr('id'));
			   $province_name = $(this).find("option:selected").val();
			   $("input[name='province']").val($province_name);
               //$post_city = $("input[name='city']").val();
			   $.post("/agent/get_citys.html",data,function(F){
			   	console.log(F);
                  if(F.status == 1){
               	     F = eval(F.data);
               	     $(".change_city").html("<option >请选择城市</option>");
                     for(var i=0;i<F.length;i++){
                     	var city_option = "<option id='" + F[i]['city_code'] + "'";
                        city_option += ">" + F[i]['city_name'] + "</option>";
                     	$(".change_city").append(city_option);
                     }      
                  }else{
               	     alert(F.info);
                  }
			   },'json')
		   })
           $("select[name='change_city']").change(function(){
               $city = $(this).find("option:selected").val();
               $("input[name='city']").val($city);
           })
		   $("#tk_ture").click(function(){
              $("#orderForm").submit();
		   })
           $("#status li").click(function(){
              $val = $(this).attr("value");
              //alert($val)
              $html = $(this).html();
              if($val == "0"){
                $("#top_status").html("订单状态");
              }else{
                $("#top_status").html($html);   
              }
              $("input[name='status']").val($val);   
              $("#status li").removeClass("blue_css");
              $(this).addClass("blue_css");
              $("#bg_option").hide();
              $(".option_div").hide();
              $("#orderForm").submit();    
           }) 
           $("#is_fullmoney li").click(function(){
              $val = $(this).attr("value");
              $html = $(this).html();
              if($val == "0"){
                $("#top_is_fullmoney").html("车辆类型");
              }else{
                $("#top_is_fullmoney").html($html);
                
              }
              $("input[name='is_fullmoney']").val($val);
              $("#is_fullmoney li").removeClass("blue_css");
              $(this).addClass("blue_css");
              $("#bg_option").hide();
              $(".option_div").hide(); 
              $("#orderForm").submit();
           }) 
           $("#jobs li").click(function(){
              $val = $(this).attr("title");
              $html = $(this).html();
              if($val == "全部"){
                $("#top_jobs").html("职业");
                $("input[name='jobs']").val(" ");
              }else{
                $("#top_jobs").html($html);
                $("input[name='jobs']").val($val);
              }
              $("#jobs li").removeClass("blue_css");
              $(this).addClass("blue_css");
              $("#bg_option").hide();
              $(".option_div").hide(); 
              $("#orderForm").submit();
           }) 

    });
    String.prototype.trim=function() {
    return this.replace(/(^\s*)|(\s*$)/g,'');
   } 
	</script>
</head>
<body style="background: #efefef;">
<form id="orderForm" action="/agent/order_lists" method="post">
<header>
	<a href="/index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <span class="add_div">
    	<img src="_STATIC_/2015/image/yydai/agent/ico_add.png">
    	<input type="hidden" name="province" value="{$params['province']}">
    	<input type="hidden" name="city" value="{$params['city']}">
    	<empty name="params['city']">
    	<font id="location" value=""> 定位中... </font>
    	<else/>
    	 <font id="location" value="{$params['province']}{$params['city']}">{$params['province']}-{$params['city']}</font>
    	</empty> 	
    </span>
    <a href="/agent/order_submit" class="btn_go">去甩单</a>
</header>

<section class="choose_city" style="display: none;">
	<section class="tit_city">
		<img src="_STATIC_/2015/image/yydai/index/ico_dw.png">
		<span>选择城市</span>
	</section>
	<section class="con_city">			
			<select name="change_pro" id="change_pro">
				<option>请选择省</option>
				<foreach name="provinces"  item="vo">
				  <option id="{$vo['province_code']}" <?php $str = trim($vo['province_name']);  if(preg_match("/($str)/is",$params['province']) !== 0) echo "selected=''";?>  >{$vo['province_name']}                
                  </option>
				</foreach>
			</select>
			<select style="margin-left: 4%;" name="change_city" class="change_city">
				<option >请选择市</option>
			</select>			
	</section>
	<section class="close_city">
		<a class="fl" style="color: #909090;" id="tk_cancel">取消</a>
		<a class="fl" style="color: #5495e6; border-left: 1px solid #e3e3e3;" id="tk_ture" >确定</a>
	</section>		
</section>

<section class="order_icon">
	<input type="hidden" name="status" readonly="" class="show" value="{$params['status']}" ></input>
    <input type="hidden" name="is_fullmoney" readonly=""  class="show" value="{$params['is_fullmoney']}"></input>
    <input type="hidden" name="jobs" readonly=""  class="show" value="{$params['jobs']}" ></input>
    <a class="show" id="top_status">
    <if condition="$params.status eq 0" >
    订单状态
    <else/>
    <php>echo $status[$params['status']];</php>
    </if> 
    </a>
	<a class="show" id="top_is_fullmoney">
    <if condition="$params.is_fullmoney eq 0" >
    车辆类型
    <else/>
    <php>echo $is_fullmoney[$params['is_fullmoney']];</php>
    </if> 
    </a>
	<a class="show" id="top_jobs">
    <empty name="params.jobs" >
    职业
    <else/>
    <php>echo $params['jobs'];</php>
    </empty> 
    </a>
    </a>
	<!-- <input type="button" name="select_order" value="确认筛选"> -->
</section>
<section style="background: #000; opacity: 0.5; width:100%; height: 100%; z-index: 9; display: none; position: fixed;" id="bg_option"></section>
<section class="option_div">
	<ul class="ulHide" id="status">
		<foreach name="status" item="v">
		<li class="<?php if($key == $params['status']) echo 'blue_css';?>"  value="{$key}" title="{$v}">{$v}</li>
		</foreach>
	</ul>
	<ul class="ulHide" id="is_fullmoney">
		<foreach name="is_fullmoney" item="v">
		<li class="<?php if($key == $params['is_fullmoney']) echo 'blue_css';?>" value="{$key}" title="{$v}">{$v}</li>
		</foreach>
	</ul>
	<ul class="ulHide" id="jobs">
		<foreach name="jobs" item="v">
		<li class="<?php if($v == $params['jobs']){echo 'blue_css';}elseif($v == '全部' && empty($params['jobs'])){echo 'blue_css';} ?>" value="{$key}" title="{$v}">{$v}</li>
		</foreach>
	</ul>
</section>
<section class="order_content" style="padding-top:89px;">
<empty name="order_lists"><section class="order_none">没有此类订单!</section></empty>
<foreach name="order_lists" item="vo">		
	<section class="order_one" value="{$vo['id']}" id="{$vo['memberid']}" onclick='get_info(this);'>
		<section class="order_otit">			
			<section class="order_header">
			  <empty name="vo.avatar">
        <span style="display: inline-block; border-radius: 50%; background: url('_STATIC_/2015/image/yydai/agent/ico_header.png')no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;"></span>
			  <else/>
			    <!-- <img src="{$vo.avatar}"> -->
          <span style="display: inline-block; border-radius: 50%; background: url({$vo.avatar})no-repeat; width: 45px; height: 45px; background-size:cover;background-position-y:50%;"></span> 
			  </empty>
			</section>
			<section class="order_info">
				<p>{$vo['nickname']}<if condition='$vo.is_vip eq 1'><img src="{$vo['vip_picurl']}"></if><font>{$vo['company_name']}</font></p>
				<p style="margin-top: 1%;"><span>车贷</span><span>{$vo['is_fullmoney']}</span><span>{$vo['jobs']}</span></p>
		    </section>
		    <section class="order_tag {$vo['status_bg']}">{$vo['status_name']}</section>		   
		</section>
		<section class="order_con"  >{$vo['remark']}</section>
	    <section class="order_footer">
	    	<font>浏览{$vo['scanCount']}次</font>
	    	<font>解锁{$vo['unlockCount']}次</font>
	    	<span><font>{$vo['city']}</font>{$vo['timeadd']}</span>
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
</form> 
</body>
</html>
<script type="text/javascript">
	function get_info(_this){
      $order_id = $(_this).attr("value");
      $memberid = $(_this).attr("id");
      window.location.href = "/agent/order_info/order_id/"+$order_id+"/order_affiliation/"+0;
	}
    // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
    wx.config({
        debug: false,
        appId: '{$signPackage.appId}',
        timestamp: '{$signPackage.timestamp}',
        nonceStr: '{$signPackage.nonceStr}',
        signature: '{$signPackage.signature}',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onRecordEnd',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });

    wx.error(function (res) {
        //alert(res);
    });
    var wx_location_config = function () {}
    wx_location_config.prototype.lat = '0';//维度
    wx_location_config.prototype.long = '0';//经度
    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.getLocation({
            type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
            success: function (res) {
                var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                var speed = res.speed; // 速度，以米/每秒计
                var accuracy = res.accuracy; // 位置精度
                wx_location_config.prototype.lat = latitude;
                wx_location_config.prototype.long = longitude; 
               if($("#location").attr("value") == ""){             
                $.ajax({
                    'type': 'post',
                    'url': '/index/locationByGPS',
                    'dataType': 'json',
                    'data': {'lng': longitude, 'lat': latitude, 'is_ajax': 1},
                    success: function (json) {
                    	console.log(json);
                        if (json.status == 1)
                            $('#location').html(json.data.province+"-" + json.data.city).attr("value",json.data.province +"-"+ json.data.city);
                    	       document.location.href = "/agent/order_lists/province/"+ json.data.province +"/city/"+json.data.city;
                    }
                });
               }
            }
        })
     });
</script>
<!-- 百度定位 -->
<eq name='isWeixin' value='0'>
<empty name="params['city']">
<div id="allmap" style="width:400px; height:400px; overflow:hidden; margin:0;display:none"></div>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4itF2ygdKkIfshFlQggs7DZA"></script>
<script type="text/javascript">
    var map = new BMap.Map('allmap');              //创建Map实例
    map.centerAndZoom("上海市", 12);              //初始化地图(设置城市名和地图缩放级别)
    map.addControl(new BMap.NavigationControl());  //添加平移缩放控件
    map.addControl(new BMap.ScaleControl());       //添加比例尺控件
    map.addControl(new BMap.OverviewMapControl()); //添加缩略地图控件
    map.enableScrollWheelZoom();                   //启用滚轮放大缩小

    var geolocation = new BMap.Geolocation();
    geolocation.getCurrentPosition(function (r) {        
        if (this.getStatus() == BMAP_STATUS_SUCCESS) {
            var mk = new BMap.Marker(r.point);
            map.addOverlay(mk);
            map.panTo(r.point); 
           if($("#location").attr("value") == ""){
            $.ajax({
                'type': 'post',
                'url': '/agent/locationByGPS',
                'dataType': 'json',
                'data': {'lng': r.point.lng, 'lat': r.point.lat, 'is_ajax': 1},
                success: function (json) {
                	console.log(json);
                    if (json.status == 1){
                    	$('#location').html(json.data.province +"-"+ json.data.city).attr("value",json.data.province + json.data.city);
                    	document.location.href = "/agent/order_lists/province/"+ json.data.province +"/city/"+json.data.city;
                    } 	
                }
            });
          }
            //document.getElementById('lnglat').innerHTML = '1当前坐标：' + r.point.lng + ", " + r.point.lat;
        } else {
            new BMap.LocalCity().get(function (result) {
                console.log('IP定位获取当前城市：' + result.name);
                map.setCenter(result.name);
            });
        }
    }, {enableHighAccuracy: true});



</script>
</empty>
</eq>
{__NOLAYOUT__}