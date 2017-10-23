<?php var_dump($_FILES);?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title>上传图片插件</title>

<link href="_STATIC_/2015/uploadpicture/css/common.css" type="text/css" rel="stylesheet"/>
<link href="_STATIC_/2015/uploadpicture/css/index.css" type="text/css" rel="stylesheet"/>

</head>
<body>
<aside class="mask works-mask">
	<div class="mask-content">
		<p class="del-p ">您确定要删除作品图片吗？</p>
		<p class="check-p"><span class="del-com wsdel-ok">确定</span><span class="wsdel-no">取消</span></p>
	</div>
</aside>
<form action="./index.php" method="post" enctype="multipart/form-data">
<div class="img-box full">
	<section class=" img-section">
		<p class="up-p">作品图片：<span class="up-span">最多可以上传5张图片，马上上传</span></p>
		<div class="z_photo upimg-div clear" >
			   
				 <section class="z_file fl">
					<img src="_STATIC_/2015/uploadpicture/img/a11.png" class="add-img">
					<input type="file" name="certi_pic" id="certi_pic"  class="file" value="" accept="image/jpg,image/jpeg,image/png,image/bmp" multiple />
				 </section>
		 </div>
	 </section>
</div>
</form>
<aside class="mask works-mask">
	<div class="mask-content">
		<p class="del-p ">您确定要删除作品图片吗？</p>
		<p class="check-p"><span class="del-com wsdel-ok">确定</span><span class="wsdel-no">取消</span></p>
	</div>
</aside>
<script src="_STATIC_/2015/uploadpicture/js/jquery.js"></script>
<!--<script src="js/imgUp.js"></script>-->
<script src="_STATIC_/2015/uploadpicture/js/imgPlugin.js"></script>
<script type="text/javascript">
var is_delay = 0;
var delay = setInterval(function(delay){if(is_delay==1)clearInterval(delay);is_delay = 1;},1000);
$(function(){
	var name = $("input[name='file']").attr("addid");
$(".file").takungaeImgup({
      formData: {
          "name": 'user_pic'
      },
      url:"/text/upload.html",
      success: function(data) {
      	alert(data.data.name);
      	//$(".up-img").attr("src",data.data);
      },
      error: function(err) {
      },
});
// $.ajax({
// 		type : 'POST',
// 		url : "/text/upload",
// 		data : {"is_ajax":1},
// 		async: false,  
//         cache: false,  
//         contentType: false,  
//         processData: false,
// 		dataType : 'json',
// 		success : function(data) {
//              alert(data.info);
			
// 		},
// 		error : function(e) {
// 			console.log(e);
// 			var err = "上传失败，请联系管理员！";
// 			$("#imguploadFinish").val(false);
// 			alert(err);
// 		}
// 	});
})
</script>
</body>
</html>
