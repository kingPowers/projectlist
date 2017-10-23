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
<form class="definewidth m20" id="editForm">
  <table class="table table-bordered table-hover m10">
    <tr>
      <td width="10%" class="tableleft">用户名：</td>
      <td><input type="text" name="username" jschecktitle="用户姓名" jscheckrule="null=0"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">电话号码：</td>
      <td><input type="text" name="mobile" jschecktitle="电话号码" jscheckrule="null=0"></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">密码：</td>
      <td><input type="password" name="password" jschecktitle="用户密码" jscheckrule="null=0" ></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft">确认密码：</td>
      <td><input type="password" name="repassword" jschecktitle="确认密码" jscheckrule="null=0" ></td>
    </tr>
    <tr>
      <td width="10%" class="tableleft"></td>
      <td><button class="btn btn-primary" type="button" onclick="add();"> 添加 </button>
        <button type="button" class="btn btn-success" name="backid" onclick="javascript:window.location.href='/Member/receiveMember'">返回列表</button></td>
    </tr>
  </table>
</form>
<script type="text/javascript">
function add ()
{
   var P = check_form('#editForm');
   if(P)
   {
     var data = {};
     $(data).attr("username",$("input[name='username']").val());
     $(data).attr("mobile",$("input[name='mobile']").val());
     $(data).attr("password",$("input[name='password']").val());
     $(data).attr("repassword",$("input[name='repassword']").val());
     $.post("/Member/receiveToDb.html",data,function(F){
        console.log(F);
        top.jdbox.alert(F.status,F.info);
         if(F.status == 1){
                window.location.href = "/Member/receiveMember";
          }
     },'json')
   }
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