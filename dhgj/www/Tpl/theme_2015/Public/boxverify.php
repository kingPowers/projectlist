<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>验证码弹框</title>
<style>
* {margin:0;padding:0;}
body{font-size:12px;}
img {
    border: 0;
    vertical-align: bottom;
}
::-webkit-input-placeholder {
    color: #999;
}
:-moz-placeholder {
    color: #999;
}
::-moz-placeholder {
    color: #999;
}
:-ms-input-placeholder {
    color: #ccc;
}
* {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
*:before,*:after {
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box;
    box-sizing: border-box;
}
input{
    color: #595959;
    vertical-align: middle;
    *vertical-align: -5px;
    font-size: 12px;
    padding: 6px;
    border: solid 1px #ddd;
    width: 100%;
    height: 25px;
    line-height: 25px;
    border-radius: 5px;
}
input::-moz-focus-inner {
    padding: 0;
    border: 0;
}
input:focus{
    outline: none;
}
input::-ms-clear {
    display: none;
}
input:focus {
    border-color: #c29540;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1) 0s;
    box-shadow: 0 0 3px rgba(102,175,233,.6);
}
a {
    background: transparent;
    text-decoration: none;
    color: #555;
}
a:active,a:hover {
    outline: 0;
}
a:focus {
    outline: thin dotted;
    outline: 5px auto -webkit-focus-ring-color;
    outline-offset: -2px;
}
.ui-button {
    background: transparent;
    border-radius: 5px;
    font-size: 12px;
    padding: 6px 15px;
    margin: 0;
    display: inline-block;
    transition: all 1s cubic-bezier(0.175, 0.885, 0.32, 1) 0s;
    color: #fff;
    background-color: #c29540;
}
.ui-button:active {
    background-image: none;
    outline: 0;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1) 0s;
	text-decoration:none;
}
.ui-button:hover {
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1) 0s;
	text-decoration:none;
}
.ui-button:active,.ui-button:hover{
	border-color: #DAA520;
    background-color: #DAA520;
}
#wp {padding:10px 0;width:250px;height:auto;overflow:auto;margin:0 auto;}
#header,#box,#footer{width:100%;height:30px;overflow:hidden;}
#header{height:20px;color:#666;text-align:center;border-bottom: 1px dotted #dadada;margin-bottom: 8px;}
#box label,#box a,#box span{display:block;float:left;height:22px;line-height:22px;width:auto;overflow:hidden;}
#box img{cursor:pointer;width:50px;}
#box a{color:#1fc6b4;text-decoration:underline;margin:0 5px;}
#box span{color:#f00;}
#footer input,#footer a{float:left;}
#footer input{margin:0;width:140px;}
#footer a{display:block;margin:0 5px;height:24px;width:auto;overflow:hidden;}
</style>
</head>
<body>
<div><span>验证码确认</span></div>
<div id="wp">
  <div id="header">请填写图片验证码</div>
  <div id="box">
    <label><img id="box_veifycode" src="/public/verifycode.html?<?php echo rand(1000,9999);?>" onclick="return refreshverifycode('box_veifycode');" /></label>
    <a href="javascript:void(0);" onclick="return refreshverifycode('box_veifycode');">换一张</a>
    <span id="boxverify_notice">{$errnotice}</span></div>
  <div id="footer">
    <input type="text" id="veifycode" value=''/>
    <a href="javascript:void(0);" id="submit-button" onclick="return boxverify_submit();" class="ui-button">确认提交</a></div>
</div>
{__NOLAYOUT__} 
<script src="_STATIC_/2015/js/jquery.min.js?v=2013082716" type="text/javascript"></script> 
<script type="text/javascript">
function boxverify_submit(){
	var verifycode = $('#veifycode').val();
	var action = '{$action}';
	if(verifycode.length!=4){
		$('#boxverify_notice').html('验证码格式不正确');
		return false;
	}
	top.jdbox.iframe('/public/boxverifycheck.html',{data:top.genboxdata({verifycode:verifycode,action:action})});
}
function refreshverifycode(id){
	$('#'+id).attr('src','/public/verifycode.html?'+Math.random());
}
$(function(){
	$('#veifycode').focus();
});
</script>
</body>
</html>
