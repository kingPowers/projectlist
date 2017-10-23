<link rel="stylesheet" href="_STATIC_/2015/member/css/account.css">
<include file="Public:accountMenu"/>
<div class="aright_div">	
	<table>
	    <tr>
			<th style="width: 240px;">订单编号</th><th style="width: 80px;">姓名</th><th style="width: 80px;">性别</th><th>借款城市</th><th>车辆型号</th><th>车牌号码</th><th>车架号码</th><th>订单状态</th>
		</tr>
		<empty name="list"><tr><td colspan="8">暂无订单</td></tr></empty>
	    <foreach name="list" item="vo">
	      <tr>
              <td class="order_num" onclick="javascript:window.location.href='/order/order_info/oid/{$vo[id]}'"><img src="_STATIC_/2015/index/image/icon_tc_b.png">{$vo['contract_num']}</td><td>{$vo['names']}</td><td>{$vo['sex']}</td><td>{$vo['loan_city']}</td><td>{$vo['car_brand']}</td><td>{$vo['plate_num']}</td><td>{$vo['frame_num']}</td>
              <td><a <?php echo (in_array($vo['status'],array(1,3,4,5)))?"class='btn_yjd'":"class='btn_jd'";?> onclick="operate(this);" status="{$vo['status']}" oid="{$vo['id']}">{$vo['status_name']}</a></td>
	      </tr>
	    </foreach>
	</table>
		<div>{$page}</div>
</div>
<script type="text/javascript">
function operate (_this)
{
	var status = $(_this).attr("status");
	var oid = $(_this).attr("oid");
   switch(status)
   {
   	  case "2":edit(oid);break;
   	  case "7":receive(oid);break;
   	  default :return false;
   }
}
function edit(oid)
{
	if(!oid){jdbox.alert(0,"订单id错误");return false;}
	window.location.href = "/order/edit_order/oid/"+oid;
}
function receive(oid)
{
	if(!oid){jdbox.alert(0,"订单id错误");return false;}
	if(!confirm("是否确认接单"))return false;
	jdbox.alert(2);
	$.post("{:U('/order/receive_order')}",{"is_ajax":1,"order_id":oid},function(F){
        jdbox.alert(F.status,F.info);
        if(F.status==1)window.location.reload();
	},'json')
}
</script>
<include file="Public:accountFooter"/>