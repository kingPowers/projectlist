<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/wx/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/wx/css/order.css"> 
    <script type="text/javascript" src="_STATIC_/2015/wx/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/wx/js/font.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
</head>
<body>
<header >
	   <a href="javascript:history.back();" class="btn_back"  id="headers">
        <img src="_STATIC_/2015/wx/images/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>创建订单</h1>    
</header>
<!--弹框-->
<section class="tk_bd"></section>
<section class="pay_back bgf pay-notice-success">
  <img src="_STATIC_/2015/wx/images/ico_ture.png">
  <h1>申请成功</h1>
  <!-- <a class="inp_box btn_bb" onclick="btnindex();">返回首页</a> -->
  <!-- <a class="inp_box btn_wl" onclick="btnfind();">查看订单</a> -->
  <a class="inp_box btn_bb" >返回首页</a>
  <a class="inp_box btn_wl" value=''>查看订单</a>

</section> 

<section class="m40 proh bgf">
	<ul>
    <li>
      <font>姓名:</font>
      <input jschecktitle="真实姓名"  name="names" type="text" placeholder="请输入客户姓名">    
    </li>
		<li>
      <font>身份证:</font><input jschecktitle="身份证号码"  name="certi_number" type="text" placeholder="请输入身份证号码">  
    </li>
    <li>
      <font>手机号:</font><input type="text" name="mobile" placeholder="请输入手机号" >
    </li>
    <li>
      <font>车牌号:</font><input type="text" name="car_number" placeholder="请输入车牌号" >
    </li>
     <input name="_register_mobile_" type="hidden" value="{$_register_mobile_}"/>
  </ul>	
</section>
<section class="m10">
  <section class="order-con df">
    <input type="text" name="sms_code" placeholder="请输入短信验证码" class="stages_yzm inp_box">
    <!-- <button  class="s_btn_yzm inp_box btn_byzm" onclick="sendCode(this)" >获 取 验 证 码</button> -->
    <input type="button" class=" s_btn_yzm inp_box btn_byzm"  value="获 取 验 证 码"  />
  </section>
</section>
<section class="w94">			
	<input type="button" class="btn_sub" id="agent_sub" value="提交">	
</section>
</body>
<!-- 点击获取验证码后倒计时 -->
 <script type="text/javascript">

 function timer(){
          var clock = '';
          var nums = 60;
          var btn;
          sendCode();
          function sendCode()
          {   
              btn = $('.s_btn_yzm');
              btn.attr('disabled','true') ;
              btn.disabled = true;
              btn.attr('color',"#cccccc") ;
              btn.val ("重新发送(" + nums + ")");
              clock = setInterval(doLoop, 1000);
          }
          function doLoop()
          {
              nums--;
              if (nums > 0) {
                  btn.val("重新发送(" + nums + ")");
              } else {
                  clearInterval(clock);
                   btn.attr('disabled',false) ;
                  btn.val ( '获 取 验 证 码');
                  nums =60;
                 btn.attr('color',"#fff") ;
              }
          }
  
 }
      </script>
<script type="text/javascript">
    $(function(){ 
      //提交
      $('#agent_sub').click(function(){
       
         var data={};
         var url = "{:url('order/addOrder')}";
        data.mobile=$('input[name="mobile"]').val();
        
        data.names=$('input[name="names"]').val();
        data.certi_number=$('input[name="certi_number"]').val();
        data.car_number=$('input[name="car_number"]').val();
        data.sms_code=$('input[name="sms_code"]').val();
        // alert(data.sms_code);
             if (data.mobile == '' || data.mobile == '请输入手机号码') {
                  alert("请输入手机号码");
                  return false;
              }
              if (!/^1\d{10}$/.test(data.mobile)) {
                  alert("请正确输入手机号");
                  return false;
              }
              if (data.sms_code == '') {
                  alert("请输入验证码");
                  return false;
              }
               if (data.sms_code == '') {
                  alert("请输入手机验证码");
                  return false;
              }
     
        $.ajax({  
             url:url,
             type: 'POST',  
             data: data,  
             success: function (msg) {  
              if(msg.status==1){
                    $(".pay-notice-success,.tk_bd").show();
                    $('.btn_wl').attr('value',msg.data);
                 }else{
                   alert(msg.msg);
                 }
            } 
           });  
      });
     //发验证码 
     $('.btn_byzm').click(function () {
                var mobile = $("input[name='mobile']").val();
                var token = $("input[name='_register_mobile_']").val();
                if (mobile == '' || mobile == '请输入手机号码') {
                    alert("请输入手机号码");
                    return false;
                }
                if (!/^1\d{10}$/.test(mobile)) {
                    alert("请正确输入手机号");
                    return false;
                }
                $.ajax({
                    'type': 'post',
                    'dataType': 'json',
                    'url': "/Order/sendSms",
                    "data": {'mobile': mobile, '_register_mobile_': token},
                    success: function (msg) {
                        if (msg.status == 1) {
                             alert(msg.msg);
                              timer();

                        } else {
                           alert(msg.msg);
                            location.reload();
                        }

                    }

                });

            });
//点击返回首页
$(".btn_bb").click(function(){
   window.location.href="/Order/myOrderList?token="+"{$token}";
})

//查看订单
$(".btn_wl").click(function(){
   var id=$('.btn_wl').attr('value');
   window.location.href="/Order/orderInfo?id="+id;
})

})
</script>
</html>
