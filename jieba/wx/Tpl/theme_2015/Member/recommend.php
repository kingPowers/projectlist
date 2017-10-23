<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
  	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
  	<script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
    <title>{$pageseo.title}</title> 
    <style type="text/css">
        html, body, div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, fieldset, input, textarea, p, blockquote, th, td { margin: 0; padding: 0; }
        a{text-decoration:none;}
        .fl{float:left;}
        .fr{float:right;}
        .dbl{display: block;}
        img {width:100%; display:block;}
        body {font: normal 100% Helvetica, Arial, sans-serif; font-family:'微软雅黑';}
        header{width:100%; height: 40px; position:fixed; top:0; left: 0; background-color:#5495e6; z-index:10;  display: -webkit-box;}
        header a{position: absolute; z-index: 11; color: #fff;}
        header img{width:22%; height:20px; display:block; vertical-align:middle; overflow:hidden; padding:10px 0 0 8px; position:absolute;}
        header font{height: 40px; line-height: 40px; display: block; margin-left: 28px;}
        header h1{color:#fff; display:block; text-align:center; height:40px; line-height:40px; -webkit-box-flex: 1.0; font-size:18px; overflow:hidden; font-weight:normal; font-family:'微软雅黑'; position: relative; width: 100%;}
        .commend_div{position: relative; margin-top: 50px; background: #fff; overflow: hidden;}
        .commend_div .table{width: 96%; float: right; overflow: hidden;}
        .commend_div .table .tr{width: 100%; border-bottom: 1px #ddd solid; height: 50px; line-height: 50px;}
        .commend_div .table .tr font{ display: inline-block;}
        .commend_div .table .tr input{border:0; outline: 0; font-size: 14px; display: inline-block; height: 20px; width: 50%;}
        .commend_div .table .photo{padding: 15px 0; width: 100%; border-bottom: 1px #ddd solid;}       
        .commend_div .table .photo span{color: #b7b7b7; margin-left: 10px;}
        #text_tel{margin-left: 10%;}
        #text_yzm{margin-left: 20%; width: 30%;}
        #text_ytel{margin-left: 5%;}
        #btn_up{width: 23%; display: inline-block; vertical-align: middle; margin-left: 10px;}
        .btn_uppic{opacity: 0; display: inline-block; width: 23%; height: 65px; position: absolute; margin-left: -23%;}
        .btn_yzm{border-left: 1px solid #ddd; display: inline-block; color: #018be7; float: right; width: 32%; text-align: center;}
        .w96{width: 94%; margin:0 auto; padding: 15px 0;}
        .btn_sub{width: 94%; position: absolute; background: #63abe8; border-radius: 5px; text-align: center; letter-spacing: 1px; font-size: 18px; display: block; height: 40px; line-height: 40px; left: 50%; margin: 0 0 0 -47%; color: #fff; border:0; -webkit-appearance:none;  font-family:'微软雅黑';}
    </style>
</head>
<body style="background: #efefef;">
<header>
    <a href="/member/accountCenter">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>修改邀请码</h1>    
</header>
<form id="form_logo" action="dorecommend" target="doingframe" method="post" enctype="multipart/form-data">
<section class="commend_div">
	<section class="table">
		<section class="tr">
			<font>注册手机号</font>&nbsp;&nbsp;{$member_info.mobile}
		</section>
		<section class="tr">
			<font>验证码</font><a class="btn_yzm" style="cursor: point;">获取验证码</a><input type="text" placeholder="请输入验证码"  name='valid_code' id="text_yzm">
		</section>
		<section class="photo">
			<font>手持身份证照片</font>
			<img id="btn_up" img src="_STATIC_/2015/member/image/account/add_photo.png"><input type="file"  name="logo" id="logo" class="btn_uppic"  onchange="preview(this)" />
			<span class='span_add_pic'>请添加图片</span>
		</section>
		<section class="tr">
			<font>邀请人手机号</font><input type="text" name='invite_mobile' placeholder="请输入邀请人手机号" id="text_ytel">
		</section>
	</section>	
</section>
<section class="w96">
	<input name="_changerecommend_" value='{$_changerecommend_}' type="hidden"/>
	<input type="checkbox" name='rigisteragree' style="margin-right: 10px;">本人同意并已确认更换邀请人
</section>
<input type="button" class="btn_sub" value="提交">
</form>
</body>

<script>

    var sendtimmer = null;
    var sendsecond = 60;
    var smsbutton = $('.btn_yzm');
     //上传图片部分
    function preview(file)  
    {
        if(!file.value=="")
        {
            var prevDiv = document.getElementById('btn_up');
            if (file.files && file.files[0])
            {
                var reader = new FileReader();
                reader.onload = function(evt){
                    //prevDiv.innerHTML = '<img src="' + evt.target.result + '" style="width:80px;height:80px;"/>';
                    $("#btn_up").attr("src",evt.target.result).css({"width":"80px"});
                    $(".span_add_pic").hide();
                }
            }
         reader.readAsDataURL(file.files[0]);  
        }
        else
        {  
        	$("#btn_up").attr("src","_STATIC_/2015/member/image/account/add_photo.png").css({"width":"80px"});
        	$(".span_add_pic").show();
            //prevDiv.innerHTML = '<div class="imgs" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';  
        }  
    }
    $(function(){
        $(".btn_yzm").click(function(){
            if($(this).attr('disabled'))return false;
            var data = {};
            $.post('/Member/sendSmsChangeRecommend.html',{'_changerecommend_':$("input[name='_changerecommend_']").val()},function(F){
                var F = eval(F);
                if(F.status==0){
                   alert(F.info);
                }else{
                    $(".btn_yzm").attr('disabled',"disabled");
                    sendtimmer = setInterval('showTimemer()', 1000);
                    alert(F.data.msg);
                }
            },'json');
        });
        $(".btn_sub").click(function(){
            if(!$("input[name='rigisteragree']:checked").val()){
                alert('请先同意更换邀请人');
                return false;
            }
            if(!$("#text_yzm").val()){
                alert('请输入验证码');
                return false;
            }
            if(!$("#text_ytel").val()){
                alert('请输入新邀请人手机号');
                return false;
            }
            //var data = {};
           // $(data).attr('valid_code', $("#text_yzm").val());
            //$(data).attr('invite_mobile', $("#text_ytel").val());
            var options = {
                url: "/Member/dorecommend",
                type: "post",
                dataType: "json",
                //data:data,
                success: function(o) {
                    if (o.status == 0) {
                        alert( o.info);
                        $(".btn_sub").css({'background':'#63abe8','color':'#fff'}).removeAttr('disabled');
                    } else {
                   	 	alert( o.info);
                   	 	location.href="/member/accountCenter";
                    }
                }
            };
            $(this).attr('disabled','disabled').css({'background':'#ddd','color':'#000'});
            $("#form_logo").ajaxSubmit(options);
        });
    })
    var showTimemer = function() {
        if (sendsecond > 0) {
            smsbutton.html('重新发送( ' + sendsecond + ')');
            smsbutton.css("color","#ddd");
            sendsecond -= 1;
        } else {
            smsbutton.html('获取验证码');
            clearInterval(sendtimmer);
            sendtimmer = null;
            sendsecond = 60;
            smsbutton.removeAttr('disabled');
        }
    }
</script>
</html>   
{__NOLAYOUT__}