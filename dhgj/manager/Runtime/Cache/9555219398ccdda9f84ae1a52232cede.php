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
<style>
	#content{width:500px;padding:10px 0;padding-right:20px;font-size:12px;color:#ccc;min-height:200px;}
	table.table td{color:#666;height: 100%; margin: 20%,auto;}
	table.table td.value{color:#0081A1;}
	input[name='selectMember']{width: 20px;height: 10px;line-height: 10px;display: inline-block;}
	ul{list-style: none;}
	li{float: left;height: 30px;width: 120px;border: 2px solid #fff;line-height: 30px;background: #E9E9E9;border-radius: 5px;color: #000;cursor: pointer;}
</style>
<script type="text/javascript">
$(function(){
	$(".memberLi").click(function(){
		$(".memberLi").css("color","#000");
		$(this).css("color","#FF0000").find("input").attr("checked","checked");
	})
})
</script>
<div><span>发单</span></div>
<div id="content">
<form>
	<table class="table table-bordered table-hover definewidth m10">
		<tr>
			<td valign="middle" align="center">选择接单人</td>
            <td class="value" align="center" valign="middle">
               <ul>	
	             <?php if(is_array($receiveMember)): foreach($receiveMember as $key=>$vo): ?><li class="memberLi"><input type="radio" name="selectMember" value="<?php echo ($vo['id']); ?>"><?php echo ($vo['username']); ?></li><?php endforeach; endif; ?>
	           </ul>
            </td>
		</tr>
		tr>
			<td valign="middle" align="center">接单报价</td>
            <td class="value" align="center" valign="middle">
              <input type="text" name="dis_price" style="margin:5px 0 0 50px;">元
            </td>
		</tr>
		<tr>
			<td valign="middle" align="center"></td>
            <td class="value" align="center" valign="middle"><input type="hidden" name="order_id" value="<?php echo ($order_id); ?>"><a href="javascript:void(0);" class="btn" onclick="operate();">确定</a><a style="margin-left: 10px;" href="javascript:void(0);" class="btn" onclick="return top.jdbox.close();">取消</a></td>
		</tr>
	</table>
</form>
</div>
<script type="text/javascript">
function operate()
{
	var memberid = $("input[name='selectMember']:checked").val();
	var order_id = $("input[name='order_id']").val();
	var dis_price = $("input[name='dis_price']").val();
	if(!memberid)
	{
		return top.jdbox.alert(0,'请选择接单人');
	}
	if(!order_id)
	{
		return top.jdbox.alert(0,'订单id错误');
	}
	if(!dis_price)
	{
		return top.jdbox.alert(0,'请填写接单报价');
	}
	if(!confirm("接单报价"+dis_price+"元,是否确认"))return false;
	$.post("<?php echo U('Order/distribute');?>",{"memberid":memberid,"is_ajax":1,"order_id":order_id},function(F){
        console.log(F);
        if(F.status==1)
        {
        	parent.window.reload();
    		return top.jdbox.alert(1,F.info);
    		return top.jdbox.close();
        }else{
        	return top.jdbox.alert(0,F.info);
        }
	},'json')
}
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