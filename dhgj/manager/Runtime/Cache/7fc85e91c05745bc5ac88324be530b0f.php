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
<script type="text/javascript" src="_STATIC_/2015/js/myfocus-2.0.1.min.js"></script>
<style>
	#content{width:800px;height:auto;padding:10px 0;padding-right:20px;font-size:12px;color:#ccc;min-height:200px;margin: 10px auto;}
	table.table td{color:#666;height: 100%; margin: 20%,auto;}
	table.table td.value{color:#0081A1;}
	.enlarge{height: 550px;width: 600px;}
	b{display: inline-block;width: 100px;height: 40px;line-height: 40px;color:#FFF;border: 1px solid #ccc;text-align: center;background:#322C2C;cursor: pointer;border-radius: 5px; }
	#name_li{margin:10px 30px;}
	#contentBody{margin:20px 50px;max-height: 600px;width: auto;}
	.imgContent{overflow: hidden;margin:0;padding: 0;float: left;margin-bottom: 20px;text-align: center;position: relative;}
	.imgBody{position: absolute;}
	.Img{position:relative;}
</style>
<script type="text/javascript">
   $(function(){
		$(".pic_name").click(function(){
			$("div[name='contentBody']").css("display","none");
			$className = $(this).attr("name");//alert($className);
			$("."+$className).css("display","inline-block");
		})	
   })
   var pic_key1 = "<?php echo ($pic_key); ?>";
   var pic_keys = pic_key1.split("|");
   for(var i=0;i<pic_keys.length;i++){
   	myFocus.set({
	    id:pic_keys[i],
	    pattern:'mF_pithy_tb',//'mF_games_tb',
        width:600,
        height:600,
        auto:false,
        //autoZoom:true
        //txtHeight:20,
     });
   }

</script>
<div><span>图片详情</span></div>
<div id="content">
	<div id="name_li">
	  <?php if(is_array($pic_info['pic_name'])): foreach($pic_info['pic_name'] as $key=>$vo): ?><b class="pic_name" name="<?php echo ($key); ?>"><?php echo ($vo); ?></b><?php endforeach; endif; ?>
	</div>
	<?php if(is_array($pic_info['pic_url'])): foreach($pic_info['pic_url'] as $k1=>$v): if($k1 == 'user_pic'): ?><div id="contentBody" name="contentBody" class="<?php echo ($k1); ?>"  style="display: inline-block;">
		 <?php else: ?>
		 <div id="contentBody" name="contentBody" class="<?php echo ($k1); ?>"  style="display: none;"><?php endif; ?>    
	         <div id="<?php echo ($k1); ?>">
			    <div class="loading"><img src="_STATIC_/2015/image/loading.gif" alt="请稍候..." /></div>
				<div class="pic">
					<ul>
					    <?php if(is_array($v)): foreach($v as $k2=>$vo): ?><li><a href=""><img class="enlarge" alt="第<?php echo $k2+1; ?>张" src="<?php echo ($vo); ?>"></a></li><?php endforeach; endif; ?>
					</ul>
				</div>
		    </div>
        </div><?php endforeach; endif; ?>
</div>
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