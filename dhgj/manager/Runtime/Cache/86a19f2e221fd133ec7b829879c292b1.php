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

<form class="form-inline definewidth m20">
    <button type="button" class="btn btn-success" id="addnew">新增菜单</button>
    <button type="button" class="btn btn-sort">保存排序</button>
</form>

<table class="table table-bordered table-hover definewidth m10">
    <thead>
        <tr>
            <th>菜单标题</th>
            <th>分组</th>
            <th>MODEL</th>
            <th>ACTION</th>
            <th>状态</th>
            <th>管理操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr forpid="pid_<?php echo ($vo['id']); ?>" class="parent">
			<td><a><?php echo ($vo['title']); ?></a></td>
            <td><?php echo ($vo['title']); ?></td>
            <td><?php echo ($vo['name']); ?></td>
            <td>--</td>
            <td><?php echo ($vo['status']); ?></td>
            <td>
            	<a href="<?php echo U('Menu/edit',array('id'=>$vo['id']));?>">编辑</a>
            </td>
        </tr>
        <?php if(is_array($vo['child'])): foreach($vo['child'] as $key=>$v): $arr=explode('-',$v['name']); ?>
        <tr id="id_<?php echo ($v['id']); ?>" pid="pid_<?php echo ($vo['id']); ?>">
            <td>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo ($v['title']); ?></td>
            <td><?php echo ($vo['title']); ?></td>
            <td><?php echo ($arr[0]); ?></td>
            <td><?php echo ($arr[1]); ?></td>
            <td><?php echo ($v['status']); ?></td>
            <td>
            	<a href="<?php echo U('Menu/edit',array('id'=>$v['id']));?>">编辑</a>
                <a href="javascript:void(0);" class="removeT">上移</a>
                <a href="javascript:void(0);" class="removeB">下移</a>
            </td>
        </tr><?php endforeach; endif; endforeach; endif; ?>
     </tbody>
</table>
<form class="form-inline definewidth"></from>

<script language="javascript">
$(function(){
	$('#addnew').click(function(){
		window.location.href="<?php echo U('Menu/add');?>";
	});
	$('a.removeT').live('click',function(){
		var currentTr = $(this).parent().parent();
		var beforeTr = currentTr.prev();
		if( typeof beforeTr.attr('pid')  == 'undefined'){
			return top.jdbox.alert(0,'该项已成为第一项');
		}
		var html = "<tr id='" + currentTr.attr('id') + "' pid='" + currentTr.attr('pid') + "'>";
		html += currentTr.html() + "</tr>"; 
		beforeTr.before(html);
		currentTr.slideUp().remove();
	});
	$('a.removeB').live('click',function(){
		var currentTr = $(this).parent().parent();
		var afterTr = currentTr.next();
		if( typeof afterTr.attr('pid') == 'undefined' ){
			return top.jdbox.alert(0,'该项已成为最后一项');
		}
		var html = "<tr id='" + currentTr.attr('id') + "' pid='" + currentTr.attr('pid') + "'>";
		html += currentTr.html() + "</tr>"; 
		afterTr.after(html);
		currentTr.slideUp().remove();
	});
	$('button.btn-sort').click(function(){
		var sortData = [];
		$('table.table tbody tr.parent').each(function(){
			var pid = $(this).attr('forpid'); 
			$("table.table tbody tr[pid='"+pid+"']").each(function(i,n){
				var st = $(this).attr('id').replace('id_','') + ':' + (i+1);
				sortData.push(st);
			});
		});
		top.jdbox.alert(2);
		$.post('/Menu/sort.html',{'string':sortData.join('|')},function(F){
			top.jdbox.alert(F.status,F.info);
		},'json');
	})
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