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
</head>
<body>
<header >
	   <a href="javascript:history.back();" class="btn_back"  id="headers">
        <img src="_STATIC_/2015/wx/images/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>订单详情</h1>    
</header>
<section class="m40 proh bgf">
	<ul>
    <li>
      <font>姓名</font>
      <span>
      {$res.names}
      </span>
    </li>
	<li>
      <font>车牌</font>
      <span>
      {$res.car_number}
      </span>  
    </li>
    <li>
      <font>手机</font>
      <span>
      	 {$res.mobile}
      </span>
    </li>
    <li>
      <font>身份证</font>
      <span>
      	 {$res.certi_number}
      </span>
    </li>
  </ul>	
</section>
</body>
</html>
