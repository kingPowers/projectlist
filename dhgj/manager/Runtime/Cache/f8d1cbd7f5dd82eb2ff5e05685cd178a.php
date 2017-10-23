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
    input[type='text']{width:300px;}
    textarea{width:700px;height:80px;}
    #content{height:500px;}
</style>
<form id='newsForm' class="definewidth m20">
<table class="table table-bordered table-hover m10">
    <tr>
        <td class="tableleft">新闻标题</td>
        <td><input type="text" name="title" value="<?php echo ($news['title']); ?>"/></td>
    </tr>
    <tr>
        <td class="tableleft">新闻关键字</td>
        <td>
            <textarea name="keys"><?php echo ($news['keys']); ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">新闻简要</td>
        <td>
            <textarea name="intro"><?php echo ($news['intro']); ?></textarea>
        </td>
    </tr>
    <tr>
        <td class="tableleft">新闻图片</td>
        <td>
            <input type="file" name="pic_urls"/>
        </td>
    </tr>
    <tr>
        <td class="tableleft">图片预览</td>
        <td>
            <img src="">
        </td>
    </tr>
    <tr>
        <td class="tableleft">排序</td>
        <td>
            <input type="text" name="sort" value="<?php echo ($news['sort']); ?>" /> （<span style="color:#A90000;">为数字，按从小到大排序(默认最高)</span>）
        </td>
    </tr>
    <tr>
        <td class="tableleft">是否加精</td>
        <td>
            <input type="radio" name="is_cream" value="0" checked="checked" />否
            <input type="radio" name="is_cream" value="1" />是
        </td>
    </tr>
    <tr>
        <td class="tableleft">内容</td>
        <td><textarea name="conten" id="content" style="width:845px;"><?php echo ($news['content']); ?>1234</textarea></td>
    </tr>
    <tr>
        <td class="tableleft"></td>
        <td>
            <button class="btn btn-primary" type="button"> 保存 </button>
            <button type="button" class="btn btn-success" name="backid" id="backid">返回列表</button>
        </td>
    </tr>
</table>
</form>
<script type="text/javascript" charset="utf-8" src="_STATIC_/2015/js/public.js"></script>


<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
<!--编辑器-->
<link rel="stylesheet" href="_STATIC_/2015/js/editor/themes/default/default.css" />
<link rel="stylesheet" href="_STATIC_/2015/js/editor/plugins/code/prettify.css" />
<script type="text/javascript" charset="utf-8" src="_STATIC_/2015/js/editor/kindeditor.js"></script>
<script type="text/javascript" charset="utf-8" src="_STATIC_/2015/js/editor/lang/zh_CN.js"></script>
<script type="text/javascript" charset="utf-8" src="_STATIC_/2015/js/editor/plugins/code/prettify.js"></script>

<script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
<script language="javascript">
    var id = "<?php echo $news['id']; ?>",cateid = "<?php echo $news['cateid']; ?>";
    var editor;
    KindEditor.ready(function(K) {
        var options = {
            uploadJson : OS+'/Public/imageUpload.html',
            allowFileManager : false
        };
        editor = K.create('#content', options);
    });
    $(function(){
        if(cateid > 0){
            $("select[name='cateid']").find('option').each(function(i,n){
                if($(this).attr('value') == cateid){
                    $(this).attr('selected','selected');
                }
            })
        }
        $('button.btn-primary').click(function(){
            var formData = new FormData($("#newsForm")[0]);
            formData.append("content",encodeURIComponent(editor.html()));
            var data={};
            $(data).attr('id',id);
            $(data).attr('title',$("input[name='title']").val());
            $(data).attr('code',$("input[name='code']").val());
            $(data).attr('sort',$("input[name='sort']").val());
            $(data).attr('cateid',$("select[name='cateid']").val());
            $(data).attr('keywords',$("textarea[name='keys']").val());
            $(data).attr('intro',$("textarea[name='intro']").val());
            $(data).attr('content',encodeURIComponent(editor.html()));
            if(!$(data).attr('title')){
                return top.jdbox.alert(0,'请输入标题');
            }
            if(!$(data).attr('keywords')){
                return top.jdbox.alert(0,'请输入关键字');
            }
            if(!$(data).attr('intro')){
                return top.jdbox.alert(0,'请输入描述');
            }
            if(!$(data).attr('content')){
                return top.jdbox.alert(0,'请输入内容');
            }
            top.jdbox.alert(2);
           $.ajax({  
              url: '/Media/edit_news' ,  
              type: 'POST',  
              data: formData,  
              async: false,  
              cache: false,  
              contentType: false,  
              processData: false,  
              success: function (F) {
                 console.log(F);
                 //alert(typeof(F))
                 var F = eval("("+F+")") ;
                 alert(F.info);     
              }  
            }); 
        });
        $('button#backid').click(function(){
            window.location.href= "<?php echo U('Cms/system');?>";
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