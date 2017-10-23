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
<form id="roleForm" class="definewidth m20">
  <table class="table table-bordered table-hover definewidth m10">
    <tr>
      <td width="10%" class="tableleft">角色名称</td>
      <td><input type="text" name="title" jschecktitle="角色名称" jscheckrule="null=0" value="<?php echo ($role['title']); ?>"/></td>
    </tr>
    <tr>
      <td class="tableleft">状态</td>
      <td><input type="radio" name="status" value="1" <?php if(($role['status']) == "1"): ?>checked="checked"<?php endif; ?>/> 启用 
      <input type="radio" name="status" value="0" <?php if(($role['status']) == "0"): ?>checked="checked"<?php endif; ?>/> 禁用 </td>
    </tr>
    <tr>
      <td class="tableleft">权限</td>
      <td>
      <ul>
          <?php if(is_array($list)): foreach($list as $key=>$vo): ?><li>
          	<label class='checkbox inline'><input type='checkbox' disabled="disabled" /><?php echo ($vo['title']); ?></label>
            <ul>
            <?php if(is_array($vo['child'])): foreach($vo['child'] as $key=>$v): ?><li><label class='checkbox inline'><input type='checkbox'  name='node[]' value="<?php echo ($v['id']); ?>" <?php if(in_array(($v['id']), is_array($role['rules'])?$role['rules']:explode(',',$role['rules']))): ?>checked="checked"<?php endif; ?>/><?php echo ($v['title']); ?></label></li><?php endforeach; endif; ?>
            </ul>
          </li><?php endforeach; endif; ?>
      </ul>
      </td>
    </tr>
    <tr>
      <td class="tableleft"></td>
      <td><button class="btn btn-primary" type="button">保存</button>
        <button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button></td>
    </tr>
  </table>
</form>
<script language="javascript">
var rid = "<?php echo ($role['id']); ?>";
$(function(){
	$('button.btn-primary').click(function(){
		var data={},check=[];
		var p = check_form('#roleForm');
		if(p){
			$("input[name='node[]']").each(function(){
				if( $(this).is(':checked') ){
					check.push($(this).val());
				}
			});
		}
		$(data).attr('rid',rid);
		$(data).attr('title',$("input[name='title']").val());
		$(data).attr('status',$("input[name='status']:checked").val());
		$(data).attr('rules',check.join(','));
		top.jdbox.alert(2);
		$.post('/Role/dataToDb.html',data,function(F){
			top.jdbox.alert(F.status,F.info);
			if(F.status){
				window.location.reload();
			}
		},'json');
	})
	$('button#backid').click(function(){
		window.location.href= "<?php echo U('Role/index');?>";
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