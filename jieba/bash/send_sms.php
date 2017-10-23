<?php 
	require_once('init.php');
	define('LOG_NAME_PDO','order_upload');
	$Log = Logger::getLogger(date('YmdHis'), LOG_PATH, FAlSE);
	$Db = pdo_connect();
	if ($Db == FALSE) {
		echo "PDO连接失败\n";
		return;
	}
	
	//还款日前一天短信通知
	if(date('H:i')>='12:00' && date('H:i')<'12:02'){
		$tomorrow = date("Y-m-d",strtotime("+1 day"));
		$end = date("Y-m-d 23:59:59",strtotime("+1 day"));
		$sql = "select `order`.mobile,`order`.names,`order`.timeadd,`order`.loanmoney,order_credit.back_time from order_credit LEFT JOIN `order` on order_credit.order_id=`order`.id where  `order`.status=2 and  order_credit.status=0 and order_credit.back_time between '".$tomorrow."' and '".$end."'";
		$loanpdo = $Db->query($sql);
		while($results = $loanpdo->fetch(PDO::FETCH_ASSOC)) {
			if(!empty($results['mobile'])){
	            $year = date('Y年m月d日',strtotime($results['timeadd']));
	            $back_time = date('m月d日',strtotime($results['back_time']));
	            $message = $results['names'].'（先生/女生）您好：您在'.$year.'申请的'.$results['loanmoney'].'元车友贷借款即将到期。请您于'.$back_time.'24:00前尽快还清借款，避免产生逾期费用。还款方式：登录借吧-车友贷-去还款，或者点击wx.jieba360.com/credit还款。详见借吧官网，或拨打借吧客服热线：400-663-9066进行咨询。【借吧】';
	            $rs = api_tong($results['mobile'], $message);
                    add_record($results['mobile'],$message,$rs,'cred','tong');
			}
		}
	}
	
	//还款日当天也短信通知  --10:00
	if(date('H:i')>='11:00' && date('H:i')<'11:02'){
		$today = date("Y-m-d");
		$tonight = date("Y-m-d 23:59:59");
		$sql = "select `order`.mobile,`order`.names,`order`.timeadd,`order`.loanmoney,order_credit.back_time from order_credit LEFT JOIN `order` on order_credit.order_id=`order`.id where `order`.status=2  and  order_credit.status=0 and order_credit.back_time between '".$today."' and '".$tonight."'";
		$loanpdo = $Db->query($sql);
		while($results = $loanpdo->fetch(PDO::FETCH_ASSOC)) {
			if(!empty($results['mobile'])){
				$year = date('Y年m月d日',strtotime($results['timeadd']));
				$back_time = date('m月d日',strtotime($results['back_time']));
				$message = $results['names'].'（先生/女生）您好：您在'.$year.'申请的'.$results['loanmoney'].'元车友贷借款即将到期。请您于'.$back_time.'24:00前尽快还清借款，避免产生逾期费用。还款方式：登录借吧-车友贷-去还款，详见借吧官网，或拨打借吧客服热线：400-663-9066进行咨询。【借吧】';
				$rs = api_tong($results['mobile'], $message);
				add_record($results['mobile'],$message,$rs,'cred','tong');
			}
		}
	}
	
	//逾期还款短信通知  --10:00
	if(date('H:i')>='10:30' && date('H:i')<'10:32'){
		$today = date("Y-m-d");
		$tonight = date("Y-m-d 23:59:59");
		$is_late =" case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 if(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)>5,
        					 (round((ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)-5)*0.02*o.loanmoney,2)+
        					 round(5*0.01*o.loanmoney,2)),
        					 round(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)*0.01*o.loanmoney,2)
        					 ) 
        					 when order_credit.status=1 then order_credit.late_fee
        					 end as late_fee,
        					case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)
        		             when   order_credit.status=1 then order_credit.late_days
        					 end as late_days";
		
		$sql = "select o.mobile,o.names,date_format(NOW(),'%Y年%m月%d日') as date,o.loanmoney,order_credit.back_time,{$is_late} from order_credit LEFT JOIN `order` o on order_credit.order_id=o.id where o.status=2  and  order_credit.status=0 and order_credit.back_time<NOW()";
		$loanpdo = $Db->query($sql);
		while($results = $loanpdo->fetch(PDO::FETCH_ASSOC)) {
			if(!empty($results['mobile'])){
				$message = "{$results['names']}（先生/女生）您好：截止至{$results['date']}，您在借吧平台申请的{$results['loanmoney']}元借款已逾期{$results['late_days']}天，按照协议规定，我司将加收逾期费用{$results['late_fee']}元，合计".($results['late_fee']+$results['loanmoney'])."元，请您尽快还款。还款方式：登录借吧-车友贷-去还款，或者点击还款wx.jieba360.com/credit。【借吧】";
				$rs = api_tong($results['mobile'], $message);
				add_record($results['mobile'],$message,$rs,'cred','tong');
			}
		}
	}
	
	
	function add_record($mobile = 0, $content = '', $result = '',$sms_type_code,$sms_platform) {
		global $Db;
		$status =  $result == "Success:ok" ?1  : 0;
		$content = $sms_platform.":".$content;
		$SQL = "INSERT INTO sms_record (`mobile`,`content`,`type`,`extend`,`timeadd`,`status`) ";
		$SQL.= "VALUE ('{$mobile}','{$content}','{$sms_type_code}','{$result}',NOW(),'{$status}')";
		$r = $Db->exec($SQL);
		return ($r > 0) ? $r : FALSE;
	}
	//短信通
	function api_tong($mobile, $message){
		$url = "http://esms100.10690007.net/sms/mt?command=MT_REQUEST&spid=9853&sppassword=WoQhbr0z&da=86{$mobile}&dc=15&sm=";
		$message = str_replace("【借吧】","",$message);
		$url.=bin2hex(mb_convert_encoding($message,"GBK","UTF-8"));
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		$arr_result = explode('&',$result);
		$k_v = array();
		foreach ($arr_result as $v){
			$val = explode('=',$v); 
			$k_v[$val[0]] = $val[1];
		}
		if($k_v['mterrcode']=='000')return "Success:ok";
		return "mtstat={$k_v['mtstat']}&mterrcode={$k_v['mterrcode']}";
	}
	function api_luosimao($mobile, $message) {
		$post_data = array();
        $post_data['userid'] = 1471;
        $post_data['account'] = 'ZXCF';
        $post_data['password'] = '123456';
        $post_data['content'] = urlencode($message); //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobile;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://115.29.242.32:8888/sms.aspx?action=send';
		$o='';
		foreach ($post_data as $k=>$v)
		{
			$o.="$k=".$v.'&';
		}
		$post_data=substr($o,0,-1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		$result = curl_exec($ch);
		curl_close($ch);
		$xml = (array)simplexml_load_string($result);
		return $xml['returnstatus'] . ':' . $xml['message'];
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
		return "对不起，短信没发出去(code:{$resultArr[0]})";
		 
	}

?>