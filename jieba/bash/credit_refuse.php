<?php 

	/*
	 * 现金贷，审核通过后且未签约的用户   24小时后自动放弃订单
	 * 
	 * **/
	require_once('init.php');
	define('LOG_NAME_PDO','order_upload');
	$Log = Logger::getLogger(date('YmdHis'), LOG_PATH, FAlSE);
	$Db = pdo_connect();
	if ($Db == FALSE) {
		echo "PDO连接失败\n"; 
		return;
	}
	$start = date("Y-m-d H:i:s",strtotime("-1 day "));
	$end = date("Y-m-d H:i:s",(strtotime("-1 day")+60*60));
	$sql = "select o.id,o.mobile,o.names,o.timeadd,o.loanmoney from  `order`  o,order_process p,order_credit c  where o.id=p.order_id and o.id=c.order_id and o.status=1 and o.order_type=3 and p.customer_status=1  and c.pass_time between '".$start."' and '".$end."'";
	$creditpdo = $Db->query($sql);
	while($results = $creditpdo->fetch(PDO::FETCH_ASSOC)) {
		$order_sql = "update `order` set status=3 where id='{$results['id']}'";
		$Db->query($order_sql);
		$process_sql = "update order_process set customer_status=2 and customer='24小时未签约，系统自动放弃订单' and customer_time=NOW(),customer_remark='24小时未签约，系统自动放弃订单' where order_id='{$results['id']}'";
		$Db->query($process_sql);
		
	}
	

?>