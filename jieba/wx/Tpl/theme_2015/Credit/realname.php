<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title>   
</head>
<body style="background: #efefef;">
<header>
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1><?php echo ($_REQUEST['from']=='agent')?'认证金牌经纪人需':'';?>实名认证</h1>    
</header>
<eq name='member_info.nameStatus' value='0'>
<section class="mui-table-view">
	<section class="mui-list-li">
		<font>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</font><input type="text" name="names" value="{$member_info.names}"  placeholder="请输入您的姓名">
	</section>
	<section class="mui-list-li">
		<font>身份证号</font><input type="text" name="certiNumber" value="{$member_info.certiNumber}" placeholder="请输入您的身份证号码">
	</section>
</section>
<input type="button" class="btn_sub credit_realname m40"  autocomplete="off" value="<?php echo ($_REQUEST['from']=='agent')?'下一步(金账户开户)':'确定';?>">
<else/>
	<section class="mui-table-view">
	<section class="mui-list-li">
		<font>姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</font>{$member_info.names}
	</section>
	<section class="mui-list-li">
		<font>身份证号</font><?php echo substr($member_info['certiNumber'],0,3)."*******".substr($member_info['certiNumber'],-8);?>
	</section>
</section>
</eq>
</body>
<script type="text/javascript">
$(function(){
	var returnurl = "{$returnurl}";
	$('.credit_realname').removeAttr("disabled");
	//点击确定，提交form表单
	$('.credit_realname').click(function(){
		var data = {};
		var input_name = {
                'names': '姓名',
                'certiNumber': '身份证号',
            };
		if($(this).attr('disabled'))return false;
        var is_check = 1;
        $.each(input_name, function (i, val) {
            $(data).attr(i, $('input[name="' + i + '"]').val());
            if ($(data).attr(i) == '' || undefined == $(data).attr(i)) {
                 alert(val + "不能为空");
                 is_check = 0;
                 return false;
              }
           });
          if (is_check == 0)return false;
          $(this).attr("disabled",true).css({"background":"#ddd"});
          $.ajax({
              'type': 'post',
              'dataType': 'json',
              'url': "/Credit/realName",
              "data": {'data': data},
              success: function (json) {
                  if (json.status == 1) {
                      location.href = returnurl;
                  } else {
                      alert(json.info);
                      $('.credit_realname').removeAttr('disabled').css({"background":'#63abe8'});
                  }

              },
          });   
		
		
            
	});
	
});
</script>
</html>
{__NOLAYOUT__}