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
<form class="form-inline definewidth m20" action="/Order/orderApply/" method="get">
    <select name="k" class="short" id="k">
        <?php if(is_array($keys)): foreach($keys as $key=>$v): ?><option value='<?php echo ($key); ?>' <?php if($_GET['k']!='' && $_GET['k'] == $key): echo 'selected="selected"'; endif;?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
    </select>&nbsp;
    <input type="text" name="v" id="v" class="abc input-default" placeholder="" value="<?php echo ($_GET['v']); ?>">&nbsp;&nbsp;
    <span>开始时间：</span>
    <input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>">&nbsp;&nbsp;
    <span>结束时间：</span>
    <input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>">&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    <a id="download_data" class="btn btn-primary" onclick="export_data(this);">导出订单申请列表数据</a>
</form>
<table class="table table-bordered table-hover definewidth m10">
    <thead>
    <tr>
        <th>申请用户名</th>
        <th>申请人手机</th>
        <th>客户姓名</th>
        <th>客户性别</th>
        <th>身份证号</th>
        <th>借款城市</th>
        <th>借款公司</th>
        <th>借款金额</th>
        <th>抵押公司</th>
        <th>返还期数</th>
        <th>车辆型号</th>
        <th>车牌号</th>
        <th>车架号</th>
        <th>GPS信息</th>
        <th>拖车价格</th>
        <th>查看图片</th>
        <th>申请时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo['username']); ?></td>
            <td><?php echo ($vo['mobile']); ?></td>
            <td><?php echo ($vo['names']); ?></td>
            <td>
               <?php if($vo['sex'] == 0): ?>男
               <?php else: ?>
                 女<?php endif; ?>
            </td>
            <td><?php echo ($vo['certiNumber']); ?></td>
            <td><?php echo ($vo['loan_city']); ?></td>
			<td><?php echo ($vo['loan_company']); ?></td>
			<td><?php echo ($vo['loan_money']); ?></td>
			<td><?php echo ($vo['mort_company']); ?></td>
			<td><?php echo ($vo['return_num']); ?></td>
			<td><?php echo ($vo['car_brand']); ?></td>
			<td><?php echo ($vo['plate_num']); ?></td>
			<td><?php echo ($vo['frame_num']); ?></td>
			<td><a href="javascript:void(0)" onclick="GPS_info(<?php echo ($vo['id']); ?>)">查看</a></td>
			<td><?php echo ($vo['trail_price']); ?></td>	
            <td><a href="javascript:void(0)" onclick="pic_info(<?php echo ($vo['id']); ?>)">查看图片</a></td>
			<td><?php echo ($vo['timeadd']); ?></td>
            <td>
               <a href="javascript:void(0)" onclick="distribute(<?php echo ($vo['id']); ?>)">发单</a> | 
               <a href="javascript:void(0)" onclick="refuse(<?php echo ($vo['id']); ?>)">打回修改</a>
               </if>
            </td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
<script type="text/javascript">
function export_data (_this)
{
	 $(_this).attr('href',"/Order/export_apply_list.html?k="+$('#k').val()+"&v="+$('#v').val()+"&starttime="+$('#starttime').val()+"&endtime="+$('#endtime').val());
}
function pic_info(id)
{
	var url = "<?php echo U('Order/pic_info');?>?id=";
	if(!id)
	{
		return top.jdbox.alert(0,'订单ID错误');
	}
    return top.jdbox.iframe(url+id);
}
function distribute(id)
{
	if(!id)
	{
		return top.jdbox.alert(0,'订单ID错误');
	}
	var url = "<?php echo U('Order/distribute');?>?id=";
	return top.jdbox.iframe(url+id);
}
function refuse(id)
{
	if(!confirm("是否打回"))return false;
	if(!id)
	{
		return top.jdbox.alert(0,'订单ID错误');
	}
	$.post("<?php echo U('Order/refuse');?>",{"order_id":id,"is_ajax":1},function(F){
        top.jdbox.alert(F.status,F.info);
        if(F.status==1){window.location.reload();}
	},'json')
}
function GPS_info(id)
{
    if(!id)
    {
        return top.jdbox.alert(0,'订单ID错误');
    }
    var url = "<?php echo U('Order/GPS_info');?>?id=";
    return top.jdbox.iframe(url+id);
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