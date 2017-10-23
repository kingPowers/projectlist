<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>智信创富接口测试</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type">
<script src="http://static.jiedai.cn/jquery/jquery-1.7.0.min.js?v=2013071701" type="text/javascript" charset="utf-8"></script>
<script src="http://static.jiedai.cn/js/jquery-cookie.js?v=2013071701" type="text/javascript" charset="utf-8"></script>
<style type="text/css">
html {overflow-y:none;}
body {font-family:'宋体';font-size:12px;padding:0px;margin: 0px;}
table.tableleft td{font-size:12px;font-family: Arial;line-height: 250%;}
table.tableleft td.left{text-align: right;padding-right: 2px;color:#09c;}
table.tableleft td.right{text-align: left;}
table.tableleft td input,table.tableleft td select{font-family: Arial;color:#888;padding:5px;}
ul#params{font-family: Arial;color:#999;}
ul#params li span.pkey{color:#09c;font-family: Arial;}
ul#params li span.pvalue{color:#f30;font-family: Arial;}
ul#params li span.premove{text-decoration: underline;color:#ccc;cursor: pointer;}
#debuginfo .debugline{color:#999;border-bottom: 1px dotted #ccc;line-height: 150%;font-family:Arial;padding-left:5px;}
</style>
</head>
<body>
	<div style="height:60px;width:100%;overflow:hidden;border-bottom:1px solid #ccc;">
		<h1 style="padding-left:10px;">智信创富接口测试</h1>
	</div>
	<div>
		<div style="float:left;width:300px;padding-top:10px;padding-left:10px;">
		<form id="form">
			<table class="tableleft">
				<tr>
					<td class="left" style="color:#090;text-align:left;">系统参数</td>
					<td class="right">&nbsp;</td>
				</tr>
				<tr>
					<td class="left">_deviceid_</td>
					<td class="right"><select id="_deviceid_" name="_deviceid_" style="width:175px;"><option value="Browser20131231001">Browser20131231001</option></select></td>
				</tr>
				<tr>
					<td class="left">_client_</td>
					<td class="right"><select id="_client_" name="_client_" style="width:175px;"><option value="BROWSER">BROWSER</option></select></td>
				</tr>
				<tr>
					<td class="left">_sign_</td>
					<td class="right"><input id="_sign_" name="_sign_" value="******" readonly="readonly" style="background-color:#f5f5f5;color:#ccc;border:1px solid #ccc;width:165px;" /></td>
				</tr>
				<tr>
					<td class="left">_token_</td>
					<td class="right"><input id="_token_" name="_token_" value="******" readonly="readonly" style="background-color:#f5f5f5;color:#ccc;border:1px solid #ccc;width:165px;" /></td>
				</tr>
				<tr>
					<td class="left">_cmd_</td>
					<td class="right"><input id="_cmd_" name="_cmd_" value="ping" style="color:#f60;background-color:#fff;border:1px solid #ccc;font-weight:bold;width:165px;" /></td>
				</tr>
			</table>
			<div id="form_extend"></div>
		</form>
		</div>
		<div style="float:left;width:300px;padding-top:10px;">
			<table class="tableleft">
				<tr>
					<td class="left" style="color:#090;text-align:left;">业务参数</td>
					<td class="right">&nbsp;</td>
					<td class="right">&nbsp;</td>
				</tr>
				<tr>
					<td class="left"><input id="key" type="text" style="width:60px;" value="key"></td>
					<td class="right"><input id="value" type="text" style="width:80px;" value="value"></td>
					<td class="right"><input type="button" id="addbtn" value="增加"></td>
				</tr>
			</table>
			<div>
				<ul id="params"></ul>
			</div>
			<div style="padding-left:2px;padding-top:10px;"><input id="runbtn" type="button" value="Service Test Run" style="width:160px;height:30px;color:#090;font-family:Arial;"></div>
		</div>
		<div style="float:left;border-left:1px solid #ccc;width:500px;height:500px">
			<iframe name="doing" id="doing" style="border:0px;width:100%;height:500px;" scrolling="auto"></iframe>
		</div>
	</div>
	<div id="debuginfo" style="border-top:1px solid #ccc;width:100%;background-color:#fff;clear:both;"></div>
	<script type="text/javascript">
		$(function(){
			$('#debuginfo').css({'height':$(window).height()-565+'px','overflow':'auto'});
			$('#doing').parent().css({'width':$(window).width()-620+'px'});
			$('#key').focus(function(){
				if($(this).val()=='key'){
					$(this).val('');
				}
			}).blur(function(){
				if($(this).val()==''){
					$(this).val('key');
				}
			});
			$('#value').focus(function(){
				if($(this).val()=='value'){
					$(this).val('');
				}
			}).blur(function(){
				if($(this).val()==''){
					$(this).val('value');
				}
			});
			$('#params li .premove').live('click',function(){
				if(confirm('确定要删除')){
					$(this).parent().remove();
				}
			});
			$('#addbtn').click(function(){
				var key=$('#key').val();
				var value=$('#value').val();
				if(!key || key=='key' || !value || value=='value'){
					alert('请正确输入key或value');
				}else{
					var params = '<li><span class="pkey">'+key+'</span>&nbsp;=&nbsp;<span class="pvalue">'+value+'</span>&nbsp;&nbsp;<span class="premove">删除</span></li>'
					$('#params').append(params);
					$('#key').val('key');
					$('#value').val('value');
				}
				$('#key').focus();
			});
			$('#runbtn').click(function(){
				$('#debuginfo').html('');
				var html='';
				for(i=0;i<$('#params li').size();i++){
					var key = $('#params li:eq('+i+')').find('span.pkey').html();
					var value = $('#params li:eq('+i+')').find('span.pvalue').html();
					html += '<input type="hidden" name="'+key+'" value="'+value+'">'; 
				}
				$('#form_extend').html(html);
				$('#form').attr({'action':'/sandbox/submit.html','target':'doing','method':'post'}).submit();
			});
		});
		function debugline(str){
			$('#debuginfo').append('<div class="debugline">'+str+'</div>');
		}
		function set_sign_(sign){
			$('#_sign_').val(sign);
		}
		function set_token_(token){
			$('#_token_').val(token).css({'background-color':'#090','color':'#fff'});
		}
	</script>
</body>
</html>