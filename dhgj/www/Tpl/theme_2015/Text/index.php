<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style type="text/css">
img{
    height: 100px;
    width: 100px;
}
</style>
<script type="text/javascript">
 $(function(){
     $(".addImg").click(function(){
       $formClass = $(this).parent("form").attr("class");
       var formData = new FormData($("."+$formClass)[0]);
       $.ajax({  
          url: '/text/uploadImg' ,  
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
             if(F.status == 1)
             {
                location.reload();
             }
             
          }  
        }); 
     })
     $(".addImgInput").click(function(){
        $name = $(this).prev("#fielArea").children("input").attr("name");
        $str = "<input type='file' multiple name='" + $name + "' class='as'>";
        //alert($(this).prev("input[type='file']").attr('name'));
        $(this).prev("#fielArea").append($str);
     })
    $(".sub").click(function(){
      //alert(1);
      var formData = new FormData($(".text")[0]);
      console.log(formData);
      $.ajax({  
          url: '/text/editOrder' ,  
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
   })
})     
    function deleteImg (_this){
        $file_name = $(_this).attr("name");
        $file_type = $(_this).attr("type");
        $order_id = {$order_id};
        var data = {};
        $(data).attr("file_name",$file_name);
        $(data).attr("file_type",$file_type);
        $(data).attr("order_id",$order_id);
        console.log(data);
        $.post("/text/deleteImg.html",data,function(F){
            console.log(F);
             //alert(typeof(F))
             //var F = eval("("+F+")") ;
             alert(F.info);
             if(F.status == 1)
             {
                location.reload();
             }
        },'json')

     }
 

</script>
<body>
<div style="width: 500px;float: left;background: #fff;">
<form class="user_pic">
<p>客户照片</p>
<foreach name="orderInfo['pic_url']['user_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="user_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="user_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="certi_pic">
<p>身份正面证照片</p>
<foreach name="orderInfo['pic_url']['certi_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="certi_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="certi_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
身份证反面照
<form class="certiBack_pic">
<p>身份证照片</p>
<foreach name="orderInfo['pic_url']['certiBack_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="certiBack_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="certiBack_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="loanContract_pic">
<p>贷款合同</p>
<foreach name="orderInfo['pic_url']['loanContract_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="loanContract_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="loanContract_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="mortContract_pic">
<p>抵押合同</p>
<foreach name="orderInfo['pic_url']['mortContract_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="mortContract_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="mortContract_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="travelLicense_pic">
<p>行驶证</p>
<foreach name="orderInfo['pic_url']['travelLicense_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="travelLicense_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="travelLicense_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="driveLicense_pic">
<p>驾驶证</p>
<foreach name="orderInfo['pic_url']['driveLicense_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="driveLicense_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="driveLicense_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="carRegistration_pic">
<p>车辆登记证</p>
<foreach name="orderInfo['pic_url']['carRegistration_pic']" item="vo">
   <img src="{$vo}"><a onclick="deleteImg(this)" class="deleteImg" type="carRegistration_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a>
</foreach>
<input type="hidden" name="order_id" value="{$order_id}">
<section id="fielArea">
<input type="file" multiple="" name="carRegistration_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
</div>
<div style="width: 500px;height: 500px;float: left;background: #fff;">
<form class="text">
<input type="hidden" name="order_id" value="{$order_id}">
<p>姓名：<input type="text" name="names" value="{$orderInfo['names']}"></p>
<p>性别：<label><input type="radio" name="sex" value="0" checked="">男</label><label><input type="radio" name="sex" value="1">女</label></p>
<p>身份证：<input type="text" name="certiNumber" value="{$orderInfo['certiNumber']}"></p>
<p>贷款城市：<input type="text" name="loan_city" value="{$orderInfo['loan_city']}"></p>
<p>贷款公司：<input type="text" name="loan_company" value="{$orderInfo['loan_company']}"></p>
<p>贷款金额：<input type="text" name="loan_money" value="{$orderInfo['loan_money']}"></p>
<p>抵押公司：<input type="text" name="mort_company" value="{$orderInfo['mort_company']}"></p>
<p>已还期数：<input type="text" name="return_num" value="{$orderInfo['return_num']}"></p>
<p>车辆型号：<input type="text" name="car_brand" value="{$orderInfo['car_brand']}"></p>
<p>车牌号码：<input type="text" name="plate_num" value="{$orderInfo['plate_num']}"></p>
<p>车架号码：<input type="text" name="frame_num" value="{$orderInfo['frame_num']}"></p>
<p>GPS账号：<input type="text" name="GPS_member" value="{$orderInfo['GPS_member']}"></p>
<p>GPS密码：<input type="text" name="GPS_password" value="{$orderInfo['GPS_password']}"></p>
<p>GPS地址：<input type="text" name="GPS_url" value="{$orderInfo['GPS_url']}"></p>
<p>拖车价格：<input type="text" name="trail_price" value="{$orderInfo['trail_price']}"></p>
<a href="javascript:void(0)" class="sub">提交</a>
</form>
</div>
</body>
<script type="text/javascript">
</script>
</html>