<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
    img {
       width: 50px;
       height: 50px;
       margin-left: 5px;
       border-radius: 5px;
    }
    img:hover {
    	transition: all,5s,linear,1s;
    	transform: scale(2);
    	transform: rotate(780deg);
    }
    div {
    	background: #fff;
    }
    b {
    	color: green;
    	width: 100px;
    	display: inline-block;
    }
	</style>
</head>
<body>
<foreach name="list" item='vo'>
<div style="width: 400px;float: left;">
<p>姓名：<span style="color: red;">{$vo['names']}</span></p>
<p>性别：<span style="color: red;"><eq name="vo['sex']" value='0'>男<else/>女</eq></span></p>
<p>身份证：<span style="color: red;">{$vo['certiNumber']}</span></p>
<p>贷款城市：<span style="color: red;">{$vo['loan_city']}</span></p>
<p>贷款公司：<span style="color: red;">{$vo['loan_company']}</span></p>
<p>贷款金额：<span style="color: red;">{$vo['loan_money']}</span></p>
<p>抵押公司：<span style="color: red;">{$vo['mort_company']}</span></p>
<p>已还期数：<span style="color: red;">{$vo['return_num']}</span></p>
<p>车辆型号：<span style="color: red;">{$vo['car_brand']}</span></p>
<p>车牌号码：<span style="color: red;">{$vo['plate_num']}</span></p>
<p>车架号码：<span style="color: red;">{$vo['frame_num']}</span></p>
<p>GPS账号：<span style="color: red;">{$vo['GPS_member']}</span></p>
<p>GPS密码：<span style="color: red;">{$vo['GPS_password']}</span></p>
<p>GPS地址：<span style="color: red;">{$vo['GPS_url']}</span></p>
<p>拖车价格：<span style="color: red;">{$vo['trail_price']}</span></p>
</div>
<notempty name="vo['pic_url']">
<div style="float: left;">
<p><b>客户照片：</b><foreach name="vo['pic_url']['user_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>身份证照片：</b><foreach name="vo['pic_url']['certi_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>贷款合同：</b><foreach name="vo['pic_url']['loanContract_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>抵押合同：</b><foreach name="vo['pic_url']['mortContract_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>行驶证：</b><foreach name="vo['pic_url']['travelLicense_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>驾驶证：</b><foreach name="vo['pic_url']['driveLicense_pic']" item='v'><img src="{$v}"></foreach></p><hr/>
<p><b>车辆登记证：</b><foreach name="vo['pic_url']['carRegistration_pic']" item='v'><img src="{$v}"></foreach></p>
</div>
</notempty>
</foreach>
</body>
</html>