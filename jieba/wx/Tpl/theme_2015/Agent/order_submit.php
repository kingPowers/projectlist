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
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/pinggu/css/pikaday.css"/>  
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/js/agent/pikaday.min.js"></script> 
    <script type="text/javascript" src="_STATIC_/2015/js/ckform.js"></script>          
    <script type="text/javascript">	  ;
		$(document).ready(function(){
			if($("input[name='full_money']").val() == '1'){
		  	   $(".btn_company").attr("jscheckrule"," ");
               $("#company").css("display","none");
               $("#btn_company").css("display","none");
		  	}else{
		  	   $(".btn_company").attr("jscheckrule","null=0;");
               $("#company").css("display","block");
               $("#btn_company").css("display","block");
               $("#btn_company").hide();		  		
		  	}
			$(".btn_job").click(function(){
			    $("#btn_job").slideToggle("slow");	
			  });			
			$(".btn_money").click(function(){
			    $("#btn_money").slideToggle("slow");
			  });
			$(".btn_company").click(function(){
			    $("#btn_company").slideToggle("slow");
			  });

			var picker = new Pikaday({
	            field: document.getElementById('datepicker'),
	            firstDay: 1,
	            minDate: new Date('1990-01-01'),
	            maxDate: new Date('2017-12-31'),
	            yearRange: [1990,2017]
	        }); 
	        $("#choose_country").click(function(){
					$("#choose_city,#tkLayBg").show();
					$("#tkLayBg").height($(document).height());
					$("#tk_close").click(function(){
						$("#tkLayBg").hide();
					})
			})
			$("#choose_city").click(function(){
					$("#choose_city1,#tkLayBg1").show();
					$("#tkLayBg1").height($(document).height());
					$("#tk_cancel1").click(function(){
						$("#tkLayBg1").hide();
					})
			})
			 //选择客户省、市
		   $("select[name='change_pro1']").change(function(){
			   var data = {};
			   $(data).attr('province_code',$(this).find("option:selected").attr('id'));
			   $province_name = $(this).find("option:selected").val();
			   $("input[name='userAddress']").val($province_name);
			   $.post("/agent/get_citys.html",data,function(F){
			   	console.log(F);
                  if(F.status == 1){
               	     F = eval(F.data);
               	     $("#change_city1").html("<option >请选择城市</option>");
                     for(var i=0;i<F.length;i++){
                  	//alert(F[i]['id']);
                     	var city_option = "<option id='" + F[i]['city_code'] + "'>" + F[i]['city_name'] + "</option>";
                     	$("#change_city1").append(city_option);
                     }      
                  }else{
               	     alert(F.info);
                  }
			   },'json')
		   })
		   $("#change_city1").change(function(){
               $city_name = $(this).find("option:selected").val();
               $name = $("input[name='userAddress']").val();
               $province_name = $name.split("-")['0'];
               //alert($province_name + "-" + $city_name);
               if($("input[name='userAddress']").val()){
                    $("input[name='userAddress']").val($province_name + "-" + $city_name);
               }else{
                    alert("请先选择省份");
               }
		   })
		   $("#tk_ture1").click(function(){
		   	//alert(1);
		   	 $(this).parents("#tkLayBg1").hide();
		   })
			 //选择车辆户籍
		   $("select[name='change_pro']").change(function(){
			   var data = {};
			   $(data).attr('province_code',$(this).find("option:selected").attr('id'));
			   $province_name = $(this).find("option:selected").val();
			   //alert($province_name);
			   $("input[name='carAddress']").val($province_name);
			   $.post("/agent/get_citys.html",data,function(F){
			   	console.log(F);
                  if(F.status == 1){
               	     F = eval(F.data);
               	     $("#change_city").html("<option >请选择城市</option>");
                     for(var i=0;i<F.length;i++){
                  	//alert(F[i]['id']);
                     	var city_option = "<option id='" + F[i]['city_code'] + "'>" + F[i]['city_name'] + "</option>";
                     	$("#change_city").append(city_option);
                     }      
                  }else{
               	     alert(F.info);
                  }
			   },'json')
		   })
		   $("#change_city").change(function(){
               $city_name = $(this).find("option:selected").val();
               $name = $("input[name='carAddress']").val();
               $province_name = $name.split("-")['0'];
               $("input[name='carAddress']").val(" ");
               //alert($province_name + "-" + $city_name);
               if($("input[name='carAddress']").val()){
                    $("input[name='carAddress']").val($province_name + "-" + $city_name);
               }else{
                    alert("请先选择省份");
               }
		   })
		   $("#tk_ture").click(function(){
		   	//alert(1);
		   	 $(this).parents("#tkLayBg").hide();
		   })
		  //选择职业
		  $("#btn_job li").click(function(){		  	
		  	//$jobs_code = $(this).attr("value");
		  	$jobs_name = $(this).html();
		  	//alert($jobs_code+$jobs_name);
		  	$("input[name='jobs']").val($jobs_name);
		  	$("#btn_job").slideUp("slow");
		  })
		  //是否全款
		  $("#btn_money li").click(function(){		  	
		  	$status = $(this).attr("value");
		  	$is_fullmoney = $(this).html();
		  	//alert($jobs_code+$jobs_name);
		  	$("input[name='is_fullmoney']").val($is_fullmoney);
		  	$("input[name='mort_company']").val("").attr("placeholder","请选择抵押单位");
		  	$("input[name='full_money']").val($status);
		  	if($status == "1"){
		  	   $(".btn_company").attr("jscheckrule"," ");
               $("#company").css("display","none");
               $("#btn_company").css("display","none");
		  	}else{
		  	   $(".btn_company").attr("jscheckrule","null=0;");
               $("#company").css("display","block");
               $("#btn_company").css("display","block");
               $("#btn_company").hide();		  		
		  	}
		  	$("#btn_money").slideUp("slow");
		  })
		  //抵押单位
		  $("#btn_company li").click(function(){		  	
		  	//$jobs_code = $(this).attr("value");
		  	$mort_company = $(this).html();
		  	//alert($jobs_code+$jobs_name);
		  	$("input[name='mort_company']").val($mort_company);
		  	$("#btn_company").slideUp("slow");
		  })	   	   
		});
    </script>
</head>
<body style="background: #efefef;">
<header>
	<a href="<?php echo ($_REQUEST['from']==1)?'/agent/agent_account':'/agent/order_lists';?>" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>
    <empty name="order_info.id">
    我要甩单
    <else/>
    修改订单
    </empty>
    </h1>
</header>
<!--默认备注-->
<section class="tkgrayBg" id="default_remark" style="display: none; height: 100%;">
	<section class="tkjin_bg">        
	    <h2>默认备注</h2>
	    <p id="default_content"></p>
	    <section class="fl btn_tkjin" id="tl_cancel2">去修改</section>  
	    <section class="fl btn_tkjin" id="tl_close2" url =" ">确认</section>        
	</section>
</section>
<!--默认备注-->
<form id="orderForm" method="post" >
<section class="mui-table-view">
	<section class="order-list-li">
		<font>客户姓名</font><span><input jschecktitle="客户姓名" jscheckrule="null=0" name="names" type="text" style="background-color: #fff" placeholder="请输入客户姓名" value="{$order_info['names']}"></span>
	</section>
	<section class="order-list-li">
		<font>客户年龄</font><span><input jschecktitle="客户年龄" jscheckrule="null=0;charset=num;" name="age" type="text" placeholder="请输入客户年龄" value="{$order_info['age']}"></span>
	</section>
	<section class="order-list-li acity">
		<font>客户城市</font><input jschecktitle="客户城市" jscheckrule="null=0;" type="text" style="width: 60%" name="userAddress" id="choose_city" placeholder="请选择客户城市" value="{$order_info['userAddress']}" readonly=""> 
	</section>
	<section class="tkLayBg" id="tkLayBg1" style="display: none;">
		<section class="choose_city" id="choose_city1" style="display: none;">
			<section class="tit_city" id="tit_city1">
				<img src="_STATIC_/2015/image/yydai/index/ico_dw.png">
				<span>选择城市</span>
			</section>
			<section class="con_city" id="con_city1">			
					<select name="change_pro1" id="change_pro1">
						<option>请选择省</option>
						<foreach name="provinces" item="vo">
						  <option id="{$vo['province_code']}">{$vo['province_name']}</option>
						</foreach>
					</select>
					<select style="margin-left: 4%;" class="change_city" id="change_city1">
						<option >请选择市</option>
					</select>			
			</section>
			<section class="close_city" id="close_city1">
				<a class="fl" style="color: #909090;" id="tk_cancel1">取消</a>
				<a class="fl" style="color: #5495e6; border-left: 1px solid #e3e3e3;" id="tk_ture1" >确定</a>
			</section>		
		</section>
	</section>
	<section class="order-list-li acity">
		<font>车辆户籍</font><input jschecktitle="车辆户籍" jscheckrule="null=0;" style="width: 70%" type="text" name="carAddress" id="choose_country" placeholder="请选择户籍城市" value="{$order_info['carAddress']}" readonly="">
	</section>
	<section class="tkLayBg" id="tkLayBg" style="display: none;">
		<section class="choose_city" id="choose_city" style="display: none;">
			<section class="tit_city" id="tit_city">
				<img src="_STATIC_/2015/image/yydai/index/ico_dw.png">
				<span>选择城市</span>
			</section>
			<section class="con_city" id="con_city">			
					<select name="change_pro" id="change_pro1">
						<option>请选择省</option>
						<foreach name="provinces" item="vo">
						  <option id="{$vo['province_code']}">{$vo['province_name']}</option>
						</foreach>
					</select>
					<select style="margin-left: 4%;" class="change_city" id="change_city">
						<option >请选择市</option>
					</select>			
			</section>
			<section class="close_city" id="close_city">
				<a class="fl" style="color: #909090;" id="tk_close">取消</a>
				<a class="fl" style="color: #5495e6; border-left: 1px solid #e3e3e3;" id="tk_ture" >确定</a>
			</section>		
		</section>
	</section>
	<section class="order-list-li acity">
		<font>客户职业</font><input jschecktitle="客户职业" jscheckrule="null=0;" style="width:70%;" type="text" name="jobs" class="btn_job" placeholder="请选择客户职业" value="{$order_info['jobs']}" readonly="">
	</section>
	<ul class="order-gray-li" id="btn_job">
		<li value="0">私营业主</li>
		<li value="1">上班族</li>
		<li value="2">公务员</li>
		<li value="3">在校生</li>
		<li value="4">其他</li>
	</ul>
	<section class="order-list-li">
		<font>品牌型号</font><span><input jschecktitle="品牌型号" jscheckrule="null=0;" type="text" name="brands" placeholder="请输入车辆品牌和型号" value="{$order_info['brands']}"></span>
	</section>
	<section class="order-list-li acity">
		<font>购车时间</font><span>	<input  type="text" name="buy_time" id="datepicker" class="datepicker" readonly placeholder="请输入上牌时间" jschecktitle="上牌时间" jscheckrule="null=0" value="{$order_info['buy_time']}"/></span>
		<div class="datepickerDiv" onclick=""></div>
	</section>
	<section class="order-list-li">
		<font>裸车价格</font><span><input name="car_price" jschecktitle="裸车价格" jscheckrule="null=0;charset=fl;minnum=1;maxnum=1000;" type="text" placeholder="请输入裸车价格" value="{$order_info['car_price']}"></span><b>万元</b>
	</section>	
	<section class="order-list-li">
		<font>行驶里程</font><span><input name="car_drive" jschecktitle="行驶里程" jscheckrule="null=0;charset=fl;minnum=0.01;maxnum=100;" type="text" placeholder="请输入行驶里程" value="{$order_info['car_drive']}"></span><b>万公里</b>
	</section>
	<section class="order-list-li">
		<font>车架号码</font><span><input name="car_frame_number" jschecktitle="车架号码" jscheckrule="null=0;len=17;charset=en,num;" maxlength="17" type="text" value="{$order_info['car_frame_number']}" placeholder="请输入车架号码"></span>
	</section>
	<section class="order-list-li acity">
		<font>是否全款</font><input jschecktitle="是否全款" jscheckrule="null=0;" style="width: 70%;" type="text" name="is_fullmoney" class="btn_money" placeholder="请选择是否全款" value="{$order_info['full_money_name']}"  readonly="">
		<input type="hidden" value="{$order_info['is_fullmoney']}" name="full_money">
	</section>
	<ul class="order-gray-li" id="btn_money">
		<li value="1">是</li>
		<li value="2">否</li>		
	</ul>
	<section class="order-list-li acity" id="company">
		<font>抵押单位</font><input jschecktitle="抵押单位" jscheckrule="null=0;" style="width: 70%;" type="text" name="mort_company" class="btn_company" placeholder="请抵押单位" value="{$order_info['mort_company']}" readonly="">
	</section>
	<ul class="order-gray-li" id="btn_company">
		<li>银行</li>
		<li>小贷公司</li>		
	</ul>
	<section class="order-list-li">
		<font>借款金额</font><span><input jschecktitle="借款金额" name="loanmoney" jscheckrule="null=0;charset=fl;minnum=1;maxnum=100;"   type="text" placeholder="请输入借款金额" value="{$order_info['loan_money']}"></span><b>万元</b>
	</section>
	<section class="order-list-li">
	    <notempty name="order_info.id"><input type="hidden" name="order_id" value="{$order_info['id']}"></notempty>
	    <input type="hidden" name="_order_session_" value="{$_order_session_}">
	    <input type="hidden" name="_editorder_session_" value="{$_editorder_session_}">
		<font>联系电话</font><span><input  jschecktitle="电话号码" jscheckrule="null=0;len=11;"  type="text" name="mobile" value="{$order_info['mobile']}" placeholder="请输入联系电话"></span>
	</section>
</section>
<p style="font-size: 12px;font-family:'微软雅黑';color: #999; width: 96%; margin:0 auto;">还可以输入<font id="could_num">110</font>个字</p>
<section class="bz_div" style="margin-top: 5px;">
	<textarea name="remark" jschecktitle="备注信息" name="loanmoney" jscheckrule="maxlencn=110;"  placeholder="请输入客户征信、负债、流水等基本信息(最多输入110个字)" >{$order_info['remark']}</textarea>
</section>
<section class="w94">	
	<p>请确保信息的真实性，如发现发布假单，<br/>
		帐号将被封禁，无法甩单！</p>
    <empty name="order_info.id">
	<input type="button" class="btn_sub" id="agent_sub" value="立即甩单">
	<else/>
	<input type="button" class="btn_sub" id="agent_sub" value="确认修改">
	</empty>
</section>
</form>
</body>
</html>
<script type="text/javascript">
$(function(){
        $('#agent_sub').removeAttr("disabled").css({"background":"#63abe8"});
	$("input[name='car_frame_number']").blur(function(){
		var reg = /^([a-zA-Z0-9]{17})$/;
		var frame = $(this).val();
		if(!(frame.match(reg))){
             alert("车架号为17位字母与数字组合");return false;
		}
	})
	$("#agent_sub").click(function(){
		var userAddress = $("input[name='userAddress']").val();
		var carAddress = $("input[name='carAddress']").val();
		var remark = $("textarea[name='remark']").val();
		var buy_time = $("input[name='buy_time']").val();
		var nowTime = new Date();var selectTime = new Date(buy_time);
		if(selectTime.getTime()>nowTime.getTime())
		{
			alert("请选择正确的购车时间");
			return false;
		}
		var P = check_form('#orderForm');
		if(!P) return false;
		if((userAddress.indexOf("-") < 0) || (carAddress.indexOf("-") < 0)){
			alert("请选择正确客户或车辆户籍");
			return false;
		}
		var data = new Array();
		 data['userCity'] = userAddress.split("-")[1];
		 data['jobs'] = $("input[name='jobs']").val();	 
		 data['loanmoney'] = $("input[name='loanmoney']").val();
		 data['brands'] = $("input[name='brands']").val();
		var full_money = $("input[name='full_money']").val();
		data['car_price'] = $("input[name='car_price']").val();
		if(full_money == '1'){
            data['is_fullmoney'] = "全款";
		}else{
			data['is_fullmoney'] = "按揭";
		}
		var names = $("input[name='names']").val();
		data['tmp_names'] = names[0];
		for(var i = 0;i<(names.length-1);i++){
			data['tmp_names'] += "*"; 
		}
		if(remark.length > 110){
			alert("备注信息超出限制");
			return false;
		}
		//alert(data['tmp_names']);
		
		if(remark.length <= 0){
			is_edit(data);
			return false;
		}
		if(P){
			sub_order();
		}
	})
	$("#could_num").html(110-($("textarea[name='remark']").val().length));
	$("textarea[name='remark']").keyup(function(){
	    $text_num = $(this).val().length;
	     $("#could_num").html(110-$text_num);		
	})
	$("#tl_cancel2").click(function(){
		$("#default_remark").hide();
	})

	$("#tl_close2").click(function(){
		sub_order();
		$("#default_remark").hide();
	})
	function is_edit(data){
		$("#default_remark").show();
		$("#default_content").html(" ");
		$default_content = data['userCity']+"的"+data['jobs']+data['tmp_names']+"欲借款"+data['loanmoney']+"万元,以"+data['brands']+data['is_fullmoney']+"车作抵押，购车价"+data['car_price']+"万元。";
        $("#default_content").html($default_content);
        $("textarea[name='remark']").val($default_content);
	}
	function sub_order(){
		var formData = new FormData($("#orderForm")[0]);
			//console.log(formData);
			//alert(typeof(formData))
           $('#agent_sub').attr("disabled",true).css({"background":"#ddd"});           
            $.ajax({  
                  url: '/Agent/order_submit' ,  
                  type: 'POST',  
                  data: formData,  
                  async: true,  
                  cache: false,  
                  contentType: false,  
                  processData: false,  
                  success: function (F) {
                      $('#agent_sub').removeAttr("disabled");
                      //alert(typeof(F)); 
                      //console.log(F);
                      var F = eval("("+F+")");
                        //alert(1);
                        alert(F.info);
                        if(F.status == 1){
                         window.location.href = "/Agent/my_order_lists";
                       }else{
                    	   $('#agent_sub').css({"background":"#63abe8"});
                       }
                  },  
                  error: function (F) {  
                      var F = eval("("+F+")");
                        //alert(F.info);  
                  }  
            });  
	}

})
</script>
{__NOLAYOUT__}