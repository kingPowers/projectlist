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
<style type="text/css">
.m_pic{position: absolute;top:0px;left:200px;display: none;max-width: 500px;}
</style>
<form class="form-inline definewidth m20" action="/Media/index/" method="get">
    <select name="k" class="short" id="k">
        <?php if(is_array($keys)): foreach($keys as $key=>$v): ?><option value='<?php echo ($key); ?>' <?php if($_GET['k']!='' && $_GET['k'] == $key): echo 'selected="selected"'; endif;?>><?php echo ($v); ?></option><?php endforeach; endif; ?>
    </select>&nbsp;
    <input type="text" name="v" id="v" class="abc input-default" placeholder="" value="<?php echo ($_GET['v']); ?>">&nbsp;&nbsp;
    <span>开始时间：</span>
    <input type="text" name="starttime" id="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['starttime']); ?>">&nbsp;&nbsp;
    <span>结束时间：</span>
    <input type="text" name="endtime" id="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?php echo ($_GET['endtime']); ?>">&nbsp;
    <button type="submit" class="btn btn-primary">查询</button>
    <a id="download_data" class="btn btn-primary" onclick="export_data(this);">导出新闻信息</a>
</form>
<div class="excel_div form-inline definewidth" style="margin-top:5px;">
    <a href="javascript:window.location.href='/Media/edit_news'" type="button" class="btn btn-success" >添加新闻</a>
    <b>注：修改审核通过后 前台才会显示</b>
</div>
<table class="table table-bordered table-hover definewidth m10" style="overflow: visible;">
    <thead>
    <tr>
        <th>新闻ID</th>
        <th>新闻标题</th>
        <th>新闻关键字</th>
        <th style="max-width: 200px;">新闻简介</th>
        <th>新闻图片</th>          
        <th>添加时间</th>
        <th>修改时间</th>
        <th>排序</th>
        <th>是否是精华</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php if(is_array($list)): foreach($list as $key=>$vo): ?><tr>
            <td><?php echo ($vo['id']); ?></td>
            <td><?php echo ($vo['title']); ?></td>
            <td><?php echo ($vo['keys']); ?></td>
            <td style='max-width: 200px;'><?php echo ($vo['intro']); ?></td>
            <td  style="position: relative;"><img class="s_pic" src="<?php echo ($vo['s_pic_urls']); ?>"><img class="m_pic" src="<?php echo ($vo['m_pic_urls']); ?>"></td>
			<td><?php echo ($vo['timeadd']); ?></td>
			<td><?php echo ($vo['lasttime']); ?></td>
			<td><?php echo ($vo['sort']); ?></td>
			<td>         
                <select name="is_cream" nid="<?php echo ($vo['id']); ?>" class="short">
                   <?php if(is_array($cream_status)): foreach($cream_status as $ki=>$vi): ?><option value="<?php echo ($ki); ?>" <?php if($ki == $vo['is_cream']): ?>selected='selected'<?php endif; ?>><?php echo ($vi); ?></option><?php endforeach; endif; ?> 
                </select>
			</td>
			<td>
			  <select name="status" nid="<?php echo ($vo['id']); ?>" class="short">
                   <?php if(is_array($status)): foreach($status as $k=>$v): ?><option value="<?php echo ($k); ?>" <?php if($k == $vo['status']): ?>selected='selected'<?php endif; ?>><?php echo ($v); ?></option><?php endforeach; endif; ?> 
              </select>
            </td>
            <td>
              <a href="javascript:void(0)" onclick="edit_info(<?php echo ($vo['id']); ?>)">编辑</a> | 
              <a href="javascript:void(0)" onclick="news_info(<?php echo ($vo['id']); ?>)">查看</a>
            </td>
        </tr><?php endforeach; endif; ?>
    </tbody>
</table>
<div><?php echo ($page); ?></div>
<script type="text/javascript">
function export_data (_this)
{
     $(_this).attr('href',"/Media/export_news_list.html?k="+$('#k').val()+"&v="+$('#v').val()+"&starttime="+$('#starttime').val()+"&endtime="+$('#endtime').val());
}
$(function(){
    $("select[name='is_cream']").change(function(){
        $cream_status = $(this).find("option:selected").val();
        $news_id = $(this).attr("nid");
        if(!$news_id)
        {
            return top.jdbox.alert(0,"新闻ID错误");
        }
        $.post("<?php echo U('Media/is_cream');?>",{"is_cream":$cream_status,"is_ajax":1,"news_id":$news_id},function(F){
            top.jdbox.alert(F.status,F.info);
            if(F.status == 1)
            {
                window.location.reload();
            }
        },'json')
    })
    $("select[name='status']").change(function(){
        $status = $(this).find("option:selected").val();
        $news_id = $(this).attr("nid");
        if(!$news_id)
        {
            return top.jdbox.alert(0,"新闻ID错误");
        }
        $.post("<?php echo U('Media/changeStatus');?>",{"status":$status,"is_ajax":1,"news_id":$news_id},function(F){
            top.jdbox.alert(F.status,F.info);
            if(F.status == 1)
            {
                window.location.reload();
            }
        },'json')
    })
    $(".s_pic").hover(
        function()
        {
           $m_pic = $(this).attr("name");
           $(this).next(".m_pic").css("display","inline-block");
        },
        function()
        {
           $(".m_pic").css("display","none");
        }
    )
})
function edit_info(id)
{
    if(!id)
    {
        return top.jdbox.alert(0,"新闻ID错误");
    }
    window.location.href = "<?php echo U('Media/edit_news');?>?id='"+id+"'";
}
function news_info(id)
{
    if(!id)
    {
        return top.jdbox.alert(0,"新闻ID错误");
    }
    $url = "<?php echo U('Media/news_info');?>?id=";
    return top.jdbox.iframe($url+id);
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