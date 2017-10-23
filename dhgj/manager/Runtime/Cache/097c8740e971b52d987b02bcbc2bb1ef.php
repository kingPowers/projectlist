<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>内页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/Css/bootstrap.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/bootstrap-responsive.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/Css/style.css" rel="stylesheet" type="text/css" />
<script language="javascript">var OS = "_OS_", Public = "__PUBLIC__", STATIC = '_STATIC_/2015/', APP = '__APP__';</script>
<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/bootstrap.js"></script>
<script type="text/javascript" src="__PUBLIC__/Js/ckform.js"></script>
</head>
<body>
<link rel="stylesheet" href="_STATIC_/2015/system/css/statistics.css" type="text/css" charset="utf-8" />
<style>
.plot_div{width:480px;height:200px;float:left;margin:0 30px;}
.plot{width:480px;height:200px;float:left;}
.highlight{color:#CC3300}
.select_time{width:480px;height:100px;float:left;margin:30px 0 0 0px}
.select_time div{float:left;width:100px;height:50px;font-size:16px;color:#333333}
.username{width:100px}
.sub{width:100px;height:30px;float:left;margin:30px 0 0 30px}
.greybg{background:#E5E5E5}
td{ white-space: nowrap;}
.blue{color:#0081CD}
div.select_time ul,div.select_time li{list-style-type:none;margin:0;padding:0;}
div.select_time ul{clear:both;overflow:hidden;}
div.select_time li{float:left;height:27px;padding-right:5px;}
div.select_time input[type=text]{width:135px;font-family:Arial;padding:0;margin-top:1px;}
div.select_time input[type=button]{height:24px;margin:0;cursor:pointer;}
table{margin-top:10px;}
table,table th,table td{padding:0;}
table th,table td{height:30px;line-height:30px;}
table th div.table-title{font-size:15px;font-weight:bold;height:30px;line-height:30px;padding-left:20px;text-align:left;}
div.scroll-wp{float:right;width:1062px;height:180px;overflow-y:scroll;}
div.scroll-wp ul,div.scroll-wp li{list-style-type:none;margin:0;padding:0;overflow:hidden;}
div.scroll-wp ul{clear:both;width:100%;height:auto;border-bottom:1px solid #ccc;}
div.scroll-wp li{float:left;height:30px;line-height:30px;border-right:1px solid #ccc;}
</style>
<div style="width:1150px;height:auto;overflow:hidden;margin:10px auto;">
 <div class="table_div" style="margin-bottom:10px;">
    <div class="tr_div">
      <div class="reg_left bg"></div>
      <div class="reg_mid bg">
        <div class="reg_today"> 今日注册<span style="font-size:26px;color:#2299EC"> 
        	<span class="t_num" id="today_login">
          		<input type="hidden" id="cur_num" value="">
          	</span><?php echo ($data["today_reg"]); ?></span>人
         </div>
         
        
        
        <div class="reg_other" style="width:517px;border-left:1px solid #CCCCCC;padding-left:0">
          <div class="reg_other" style="float:left;width:1px;border-left:1px solid #FFFFFF;"></div>
          	共注册<span class="small_int" style="font-size:16px;"><?php echo ($data["total_reg"]); ?></span>人
        </div>
      </div>
      
      <div class="reg_right bg"></div>
    </div>   
</div>
</div>

<div style="width:1150px;height:300px;margin:0 auto;clear:both;">
    <div class="plot_div">
      <div id="credit" class="plot"><img src="_STATIC_/2015/system/img/highcharts-no-result.png"/></div>
      <div class="select_time">
        <ul>
          <li>开始时间：</li>
          <li>
            <input type="text" id="tender_begin" class="timeinput" value="<?php echo ($params["timebegin"]); ?>" >
          </li>
          <li>结束时间：</li>
          <li>
            <input type="text" id="tender_end" class="timeinput" value="<?php echo ($params["timeend"]); ?>" >
          </li>
          <li>
            <input type="button" value=" 统 计 " id="sub_tender" onclick="return credit_info();">
          </li>
        </ul>
      </div>
    </div>
</div>


<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script> 
<script type="text/javascript" src="_STATIC_/2015/system/js/animateBackground-plugin.js"></script> 
<script language="javascript" src="_STATIC_/2015/js/highcharts.js"></script> 
<script type="text/javascript" language="javascript">
var highchartsNoResult = '<img src="'+STATIC+'/system/img/highcharts-no-result.png"/>',
highchartsSearch = '<img src="'+STATIC+'/system/img/highcharts-search.png"/>';
function getdata(){
	return false;
	$.ajax({
	    url: '/Index/rolling_data',
		type: 'POST',
		dataType: "json",
		cache: false,
		timeout: 10000,
		error: function(){},
		success: function(data){
			/*if(typeof(visitnum) == 'undefined'||visitnum < data.data.visit.today){
				visitnum = typeof(visitnum) == 'undefined'?'0':visitnum;
				show_num(data.data.visit.today,'visit_num','count');
			}
			visitnum = data.data.visit.today;
			show_num(data.data.visit.online,'online_num','count');*/
			show_num(data.data.today_reg,'today_login','count');
			if(data.data.reg_dif <0 ){
				$('#login_inc_logo').attr('class','bluedec bg');
				$('[name=login_num]').attr('id','login_num');
				show_num(-data.data.reg_dif,'login_num','count');
			}else{
				$('#login_inc_logo').attr('class','blueinc bg');
				$('[name=login_num]').attr('id','login_num_red');
				show_num(data.data.reg_dif,'login_num_red','count');
			}
      show_num(data.data.recharge_count,'recharge_count','count');
			show_num(data.data.recharge_amount,'recharge_amount','amount');
			show_num(data.data.tender_count,'tender_count','count');
			show_num(data.data.tender_amount,'tender_amount','amount');
//			show_num(data.data.tender_sum,'tender_sum','amount');
			show_num(data.data.today_login_count,'today_login_count','count');
			show_num(data.data.today_login_amount,'today_login_amount','count');
			show_num(data.data.member_avai_amount,'member_avai_amount','amount');
	    }
   	});
}
function show_num(n,id,type){
	var it = $("#"+id+" i");
	var len = String(n).length;
	//金钱位数变化的时候重新填入<i></i>
	if((it.length != 0 && it.length < len && type == 'amount')||it.length > len){ 
		$("#"+id).html('');
		it.length = 0;
	};

	for(var i=0;i<len;i++){
		if(it.length<=i){
			if((len-i-1)%3 == 0 && i != len-1 && type == "amount"){
				$("#"+id).append("<i></i><span style='font-size:28px;color:#D78A1D'>,</span>");
			}else{
				$("#"+id).append("<i></i>");
			}
		}
		var num = String(n).charAt(i);
		var y = -parseInt(num)*36;
		var obj = $("#"+id+" i").eq(i);
			
		$("#"+id+" i").eq(i).animate({"background-position-x":'0',
			"background-position-y":String(y)+"px"});
	}	
	if(id=='recharge_amount' && n!=$('#'+id).attr('data')){
		$('.refresh').click();
	}
	$('#'+id).attr('data',n);
}
function login_info(){
	$('#login').html(highchartsSearch);
	$.post('/Index/get_stat_data_login',{'begin':$('#login_begin').val(),'end':$('#login_end').val()},function(result){
		result = result.data;
		var day = [];
		var num = [];
		for(var i in result.day){
			if(typeof result.day[i] === 'string' ){
				day.push(result.day[i]);
			}
		}
		for(var i in result.num){
			if(typeof result.day[i] === 'string' ){
				num.push(parseFloat(result.num[i]));
			}
		}
		if(day == '' || num == ''){
			$('#login').html(highchartsNoResult);
			return false;
		}
		var op = $.extend({title: {text:'',x: -20},subtitle: {text: '',x: -20},yAxis: {title: {text: ''}},
		legend: {layout: 'vertical',align: 'right',verticalAlign: 'middle',borderWidth:0}},
		{tooltip:{valueSuffix:'人'},xAxis:{categories:day},	series:[{name:'注册统计',color:'#058DC7',data:num}]});
		$('#login').highcharts(op);
	},'json');	
}
function recharge_info(){
	$('#recharge').html(highchartsSearch);
	$.post('/Index/get_stat_data_recharge',{'begin':$('#recharge_begin').val(),'end':$('#recharge_end').val()},function(result){
		result = result.data;
		var day = [];
		var num = [];
		for(var i in result.day){
			if(typeof result.day[i] === 'string' ){
				day.push(result.day[i]);
			}
		}
		for(var i in result.num){
			if(typeof result.day[i] === 'string' ){
				num.push(parseFloat(result.num[i]));
			}
		}
		if(day == '' || num == ''){
			$('#recharge').html(highchartsNoResult);
			return false;
		}
		var op = $.extend({title: {text:'',x: -20},subtitle: {text: '',x: -20},yAxis: {title: {text: ''}},
		legend: {layout: 'vertical',align: 'right',verticalAlign: 'middle',borderWidth:0}},
		{tooltip:{valueSuffix:'元'},xAxis:{categories:day},	series:[{name:'充值统计',color:'#ED561B',data:num}]});
		$('#recharge').highcharts(op);
	},'json');	
}




function credit_info(){
	$('#credit').html(highchartsSearch);
	$.post('/Index/get_stat_data_credit',{'begin':$('#tender_begin').val(),'end':$('#tender_end').val()},function(result){
		result = result.data;
		var res_total = result.credit_total || 0;
		var day = [];
		var num = [];
		for(var i in result.day){
			if(typeof result.day[i] === 'string' ){
				day.push(result.day[i]);
			}
		}
		for(var i in result.num){
			if(typeof result.day[i] === 'string' ){
				num.push(parseFloat(result.num[i]));
			}
		}
		if(day == '' || num == ''){
			$('#credit').html(highchartsNoResult);
			return false;
		}
		var op = $.extend({title: {text:'',x: -20},subtitle: {text: '',x: -20},yAxis: {title: {text: ''}},
		legend: {layout: 'vertical',align: 'right',verticalAlign: 'middle',borderWidth:0}},
		{tooltip:{valueSuffix:'元'},xAxis:{categories:day,title:{text:'统计：'+res_total}},	series:[{name:'现金贷统计',color:'#6AF9C4',data:num}]});
		$('#credit').highcharts(op);
	},'json');	
}
function cashout_info(){
	$('#cashout').html(highchartsSearch);
	$.post('/Index/get_stat_data_cashout',{'begin':$('#view_begin').val(),'end':$('#view_end').val()},function(result){
		result = result.data;
		var day = [];
		var num = [];
		for(var i in result.day){
			if(typeof result.day[i] === 'string' ){
				day.push(result.day[i]);
			}
		}
		for(var i in result.num){
			if(typeof result.day[i] === 'string' ){
				num.push(parseFloat(result.num[i]));
			}
		}
		if(day == '' || num == ''){
			$('#cashout').html(highchartsNoResult);
			return false;
		}
		var op = $.extend({title: {text:'',x: -20},subtitle: {text: '',x: -20},yAxis: {title: {text: ''}},
		legend: {layout: 'vertical',align: 'right',verticalAlign: 'middle',borderWidth:0}},
		{tooltip:{valueSuffix:'元'},xAxis:{categories:day},	series:[{name:'提现统计',color:'#64E572',data:num}]});
		$('#cashout').highcharts(op);
	},'json');	
}
function updateinfo(){
	cashout_info();
	login_info();
	recharge_info();
	credit_info();
}
getdata();
setInterval('getdata()',60000);
setTimeout('updateinfo()',3000);
$(function(){
	$('#sub_all').click(function(){
		var all_begin = $('#all_begin').val();
		var all_end = $('#all_end').val();
		$('#view_begin').val(all_begin);
		$('#login_begin').val(all_begin);
		$('#recharge_begin').val(all_begin);
		$('#tender_begin').val(all_begin);
		$('#view_end').val(all_end);
		$('#login_end').val(all_end);
		$('#recharge_end').val(all_end);
		$('#tender_end').val(all_end);
		updateinfo();
	})
	
	$('#sub_this_month').click(function(){
		var d =new Date();
		var year = d.getFullYear();
		var month = d.getMonth()+1;
		var day = d.getDate();
		if(month < 10){
			month = '0'+month;
		}
		var this_month_begin = year+'-'+month+'-01';
		var today = year+'-'+month+'-'+day;

		$('#all_begin').val(this_month_begin);
		$('#view_begin').val(this_month_begin);
		$('#login_begin').val(this_month_begin);
		$('#recharge_begin').val(this_month_begin);
		$('#tender_begin').val(this_month_begin);
		$('#all_end').val(today);	
		$('#view_end').val(today);
		$('#login_end').val(today);
		$('#recharge_end').val(today);
		$('#tender_end').val(today);
		updateinfo();
	})
	
	$('#sub_prev_month').click(function(){
		var d =new Date();
		d.setDate(1);
		d.setMonth(d.getMonth());
		var ld = new Date(d.getTime()-1000*3600*24);
		var year = ld.getFullYear();
		var month = ld.getMonth()+1;
		if(month < 10){
			month = '0'+month;
		}
		var day = ld.getDate();
		var prev_month_begin = year+'-'+month+'-01';
		var prev_month_end = year+'-'+month+'-'+day;
		$('#all_begin').val(prev_month_begin);
		$('#view_begin').val(prev_month_begin);
		$('#login_begin').val(prev_month_begin);
		$('#recharge_begin').val(prev_month_begin);
		$('#tender_begin').val(prev_month_begin);
		$('#all_end').val(prev_month_end);	
		$('#view_end').val(prev_month_end);
		$('#login_end').val(prev_month_end);
		$('#recharge_end').val(prev_month_end);
		$('#tender_end').val(prev_month_end);
		updateinfo();
	});
	
	/*
		日历控件
	*/
	var  wdatepickers = new Array("all","recharge","login","tender","view");
	
	for(var i=0;i<wdatepickers.length;i++){
		$('input#'+wdatepickers[i]+"_begin").focus(function(){
				var ids =  $(this).attr('id').split("_");
	        	var endtime_val = $('input#'+ids[0]+"_end").val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
		});
		
		$('input#'+wdatepickers[i]+"_end").focus(function(){
			var ids =  $(this).attr('id').split("_");
        	var starttime_val = $('input#'+ids[0]+"_begin").val();
        	if(''!=starttime_val){
        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
        	}else{
        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
        	}
		});
	
	}
	
	
})
</script> 
<script type="text/javascript">
	$(function(){
		
		 $('input#starttime').focus(function(){
	        	var endtime_val = $('#endtime').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#endtime').focus(function(){
	        	var starttime_val = $('#starttime').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#start_time').focus(function(){
	        	var endtime_val = $('#end_time').val();
	        	if(''!=endtime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',maxDate:endtime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	        $('input#end_time').focus(function(){
	        	var starttime_val = $('#start_time').val();
	        	if(''!=starttime_val){
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',minDate:starttime_val});
	        	}else{
	        		WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'});
	        	}
	        });
	        
	});
</script>
<input type="hidden" name="reloadurl" value="__SELF__"/>
<div style="height:20px;width:100%;"></div>
</body>
</html>