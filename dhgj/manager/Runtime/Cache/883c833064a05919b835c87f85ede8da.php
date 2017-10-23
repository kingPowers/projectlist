<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台管理</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="__PUBLIC__/assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/assets/css/main-min.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
<script language="javascript">var OS = "__OS__", Public = "__PUBLIC__", STATIC='_STATIC_/2015/', APP = '__APP__';</script>
<script type="text/javascript" src="_STATIC_/2015/js/wdate/WdatePicker.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.box.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/public.js"></script> 
<script type="text/javascript" src="__PUBLIC__/assets/js/bui.js"></script> 
<script type="text/javascript" src="__PUBLIC__/assets/js/common/main.js"></script> 
<script type="text/javascript" src="__PUBLIC__/assets/js/config.js"></script> 
<!-- <script src="../../../.._STATIC_/2015/js/jquery-1.9.1.min.js" type="text/javascript"></script>-->
    <style type="text/css" >
        .str{
         height:27px;
         width:150px;
         margin-left:20px;
         background: #474646;
        }
        .sdelete{
         position:absolute;
         font-size:12px;
/*         color:#999;*/
         width:10px;
         font-weight:900;
         padding:0px;
         border:none;
         cursor:pointer;
        }
        .surl{
         display:block;
         height:22px;
         line-height:22px;
         overflow:hidden;
         color:white;
         text-align: center;
        }
        .mod_user{
           height:25px;
           text-align: center;
           float:left;
           line-height:10px;
           width:150px;
           margin-left:150px;
           z-index:9999;
           position:absolute;
           font-size:13px; 
            
        }
    </style>
    <script type="text/javascript" >
        function omover() {
//           var str = document.getElementById("mod_user");
//            str.style.background = "#d88f40";
             var listLink = document.getElementById("listLink");
            listLink.style.color = "red";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "block";
        }
        function omout() {
            var listLink = document.getElementById("listLink");
            listLink.style.color = "white";
            var mod_user = document.getElementById("mod_user");
            mod_user.style.background = "none";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "none";
        }
		
    </script>
</head>
<body>
    <div class="header" >
        <div class="dl-title" >后台管理系统  </div>
                <div class="mod_user" id="mod_user" onmouseover ="omover();" >	
		    <?php if(($is_show) == "1"): ?><p> <a class="list_hist" id="listLink" style="color:white;margin-left:20px;">全部(<?php echo ($notice_total); ?>)</a> </p>
                        <div id="lishistory" class="user_operate_list" style="display:none;"  onmouseout ="omout();">
                           <?php if(is_array($notice)): foreach($notice as $key=>$val): ?><div class="str">
					<a href="<?php echo ($val["href"]); ?>" target="_blank" class="surl"><?php echo ($val["title"]); ?></a>
				</div><?php endforeach; endif; ?>
                        </div><?php endif; ?>
                </div>
		
        <div class="dl-log">欢迎您，<span class="dl-log-user"><?php echo ($_SESSION['user']['username']); ?></span>
        <a href="javascript:void(0);" title="退出系统" onclick="return logout();" class="dl-log-quit">[退出]</a> </div>
    </div>
<div class="content">
  <div class="dl-main-nav">
    <div class="dl-inform">
      <div class="dl-inform-title"><s class="dl-inform-icon dl-up"></s></div>
    </div>
    <ul id="J_Nav"  class="nav-list ks-clear">
    </ul>
  </div>
  <script type="text/javascript">
	function getdata(){
		$('.mod_user').html("");
		$.post('/Index/index',{'is_ajax':1},function(result){
		var string_notice = "";
		if(result.status==1 && result.data.is_show==1){
			string_notice+='<p> <a class="list_hist" id="listLink" style="color:white;margin-left:20px;">全部('+result.data.notice_total+')</a> </p>';
           string_notice+=' <div id="lishistory" class="user_operate_list" style="display:none;"  onmouseout ="omout();">';
			$.each(result.data.notice,function(i,item){
				string_notice+='<div class="str">';
				string_notice+='<a href="'+item.href+'" target="_blank" class="surl">'+item.title+'</a>';
				string_notice+='</div> ';
			});
            string_notice+='</div>';
			$('.mod_user').html(string_notice);
		}
	},'json');	
}

$(function(){
	setInterval('getdata()',10000);
});
  </script>
<ul id="J_NavContent" class="dl-tab-conten"></ul>
</div>
<script language="javascript">
//设置菜单
$.getJSON("<?php echo U('Public/getJsMenu');?>",function( R ){
	console.log(R);
	var config = [], topmenu = '',B = true;
	for(var i in R ){
		var M={},N={},O=[],I=[];
		for(var j in R[i]['child']){
			var T={};
			$(T).attr('id',R[i]['child'][j]['id']);
			$(T).attr('text',R[i]['child'][j]['title']);
			$(T).attr('href','/'+R[i]['child'][j]['name'].replace('-','/')+'.html');
			I.push(T);
		}
		$(N).attr('text',R[i]['title']);
		$(N).attr('items',I);
		O.push(N);
		$(M).attr('menu',O);
		$(M).attr('id',R[i]['id']);
		if(I.length > 0){
			$(M).attr('homePage',I[0]['id']);
		}
		config.push(M);
		topmenu += '<li class="nav-item dl-selected">';
		topmenu += '<div class="nav-item-inner ';
		topmenu += B ? 'nav-home' : 'nav-order'; 
		topmenu += '">' + R[i]['title'] + '</div></li>';
	}
	$('ul#J_Nav').html( topmenu );
	BUI.use('common/main',function(){
		new PageUtil.MainPage({'modulesConfig':config});
	});
});

var resize = (function(){
	$(window).resize(function(){
		var w_h = $(window).height();
		var h_h = $('div#header').height();
		var n_h = $('div.dl-main-nav').height();
		var b_h = $('div.bui-nav-tab div.tab-nav-bar').height();
		$('li.dl-tab-item div.dl-second-nav').css('height',w_h-h_h-n_h+'px');
		$('div#J_28Tab div.bui-nav-tab').css('height',w_h-h_h-n_h+'px');
		$('div.bui-nav-tab div.tab-content-container').css('height',w_h-h_h-n_h-b_h+'px');
	})
}());

var reload = function()
{
	$('ul#J_NavContent > li.dl-tab-item').each(function(){
	var _self = $(this);
		if(!_self.hasClass('ks-hidden'))
		{
			_self.find('div.tab-content').each(function(){
				if( $(this).is(':visible') )
				{
					var reloadurl = $(this).find('iframe').contents().find("input[name='reloadurl']").val();
					$(this).find('iframe').attr('src',reloadurl);
				}
			})
		}
	})
}

var logout = function()
{
	jdbox.alert(2,'退出中');
	$.post("<?php echo U('Public/logout');?>",'',function(){
		window.location.href = '/login.html';
	})
}
</script>
</body>
</html>