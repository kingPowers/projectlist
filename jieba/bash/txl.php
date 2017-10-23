<?php 
	require_once('init.php');
	define('LOG_NAME_PDO','order_upload');
	$Log = Logger::getLogger(date('YmdHis'), LOG_PATH, FAlSE);
	$Db = pdo_connect();
	if ($Db == FALSE) {
		echo "PDO连接失败\n";
		return;
	}
	//$id = 103535;
	//通讯录
// 	while(true){
// 		$sql = "select * from mail_list where provinceCity is null or provinceCity='' order by id asc limit 1";
// 		$pdo = $Db->query($sql);
// 		$arr = $pdo->fetch(PDO::FETCH_ASSOC);
// 		if(empty($arr))break;
// 		$isBreak = 0;
// 		$arr['cellPhone'] = str_replace(["-","+"," "],"",$arr['cellPhone']);
// 		$arr_val = explode("|",$arr['cellPhone']);
// 		$cellPhoneAddress = [];
// 		foreach($arr_val as $k=>$v){
// 			if(false!=($ml = get_mobile_location_jh(ltrim($v,"86"))))
// 				$cellPhoneAddress[] = $ml;
// 			if($ml===null)$isBreak = 1;
// 		}
// 		if(!empty($cellPhoneAddress)){
// 			$str = implode("|",$cellPhoneAddress);
// 			$s = "update mail_list set provinceCity='{$str}' where id='{$arr['id']}'";
// 			$Db->query($s);
// 		}
// 		if($isBreak)break;
// 		sleep(0.1);
// 	}

	//while(true){
		$sql = "select * from sendsms_tmp where status=0 and mobile='13651833527' order by id asc limit 1";
		$pdo = $Db->query($sql);
		$arr = $pdo->fetch(PDO::FETCH_ASSOC);
		$result = "未发送该短信";
		if(empty($arr))break;
		if (!preg_match('/^1[0-9]{10}/',$arr["names"])){
			$result = api_taike($arr["mobile"],$arr["message"]."【借吧】");
		}
		$updateSql = "update sendsms_tmp set status=1,lasttime=NOW(),result='{$result}' where id='{$arr["id"]}'";
		$Db->query($updateSql);
		sleep(0.1);	
	//}
	
function get_mobile_location($mobile) {
	if (!preg_match('/^1[0-9]{10}/',$mobile)) {
		return false;
	}
	$return = array();
	$api = "http://apis.juhe.cn/mobile/get?phone={$mobile}&key=731d01b93483e73751cfeb35f1972f9f";
	$string = iconv('gb2312', 'utf-8', file_get_contents($api));
	$string = str_replace(array("\r", "\n", "\r\n", "'"), array('', '', '', ''), $string);
	$string = substr($string, strpos($string, '{') + 1);
	$string = substr($string, 0, strpos($string, '}'));
	$array = explode(',', $string);
	foreach ($array as $var) {
		$ex = explode(':', $var);
		$key = trim($ex[0]);
		$val = trim($ex[1]);
		$return[$key] = $val;
	}
	return $return['province'] . '-' . $return['catName'];
}
//聚合api获取手机号归属地  eg:江苏-南京
function get_mobile_location_jh($mobile) {
	if (!preg_match('/^1[0-9]{10}/',$mobile)) {
		return false;
	}
	$api = "http://apis.juhe.cn/mobile/get?phone={$mobile}&key=731d01b93483e73751cfeb35f1972f9f";
	$arr = json_decode(file_get_contents($api),true);
	if($arr['resultcode']!='200')return null;
	return "{$arr['result']['province']}-{$arr['result']['city']}";
}
//泰克泰勒短信平台
function api_taike($mobile, $message){
	$url = "http://114.215.196.145/sendSmsApi?username=zxcf&password=zxcf888&mobile={$mobile}&xh=&content={$message}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
	$result = curl_exec($ch);
	curl_close($ch);
	$resultArr = explode(",",$result);
	if($resultArr[0]=='1')return "Success:ok{$resultArr[1]}";
	return "对不起，短信没发出去(code:{".json_encode($resultArr)."})";
		
}
	
?>