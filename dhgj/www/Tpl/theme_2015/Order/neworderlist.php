<link rel="stylesheet" href="_STATIC_/2015/css/order.css">
<div class="content">
	<div class="center_div">
		<div class="d_nav_box">
			<p>
				当前位置
				<a href="/">贷后管家首页</a>
				> <a href="/order/neworderlist">查看更多</a>
			</p>
		</div>
		<div class="b_order_titer">
			<ul>
				<li style="width: 328px;">订单编号</li>
				<li style="width: 120px;">姓名</li>
				<li style="width: 120px;">性别</li>
				<li style="width: 148px;">借款城市</li>
				<li style="width: 148px;">车辆型号</li>
				<li style="width: 148px;">车牌号码</li>
				<li style="width: 148px;">车架号码</li>
			</ul>
		</div>
		<foreach name="list" item="vo">
		<div class="b_order_one">
			<ul>
				<li style="width: 328px;"><img src="_STATIC_/2015/index/image/icon_tc_b.png">{$vo['contract_num']}</li>
				<li style="width: 120px;">{$vo['names']}</li>
				<li style="width: 120px;">{$vo['sex']}</li>
				<li style="width: 148px;">{$vo['loan_city']}</li>
				<li style="width: 148px;">{$vo['car_brand']}</li>
				<li style="width: 148px;">{$vo['plate_num']}</li>
				<li style="width: 148px;">{$vo['frame_num']}</li>
			</ul>
		</div>
		</foreach>
		<div>{$page}</div>
	</div>
</div>