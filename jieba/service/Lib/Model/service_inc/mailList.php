<?php
/*
 * 通讯录
 * 
 * */
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();
if($type=='addPhone'){
	
	file_put_contents('./phone.txt',print_r($_POST,true));
	
	if(strtolower($this->_client_)=='ios'){
		$list = json_decode(str_replace(array("\r\n", "\r", "\\n"),'',$_POST['list']),true);
		if(!empty($list)){
			$count = 0;$total = count($total);
			foreach($list as $k=>$v){
				$add_data = array();
				$add_data['fullName'] = $v['baseInformation']['lastName'].$v['baseInformation']['firstName'];//姓名
				$add_data['cellPhone'] = arr2string($v['cellPhones'],'phoneNumber');//电话号码
				$add_data['address'] = arr2string($v['addresses'],'details');//地址
				$add_data['email'] = arr2string($v['emails'],'emailAddress');//地址
				$add_data['memberid'] = $member_info['id'];
				$add_data['remark'] =$v['baseInformation']['remark'];
				$add_data['client'] = 'ios';
				if(empty($add_data['cellPhone']))continue;
				if(M('mail_list')->where("cellPhone='{$add_data['cellPhone']}'")->find())continue;
				if(M('mail_list')->add($add_data))$count++;
			}
		}
		
	}elseif(strtolower($this->_client_)=='android'){
		$list = json_decode($_POST['list'],true);
		if(!empty($list)){
			$count = 0;$total = count($total);
			foreach($list as $k=>$v){
				$add_data = array();
				$add_data['fullName'] = $v['baseInformation']['firstName'].$v['baseInformation']['lastName'];//姓名
				$add_data['cellPhone'] = arr2string($v['cellPhones'],'phoneNumber');//电话号码
				$add_data['address'] = arr2string($v['addresses'],'details');//地址
				$add_data['email'] = arr2string($v['emails'],'emailAddress');//地址
				$add_data['memberid'] = $member_info['id'];
				$add_data['remark'] =$v['baseInformation']['remark'];
				$add_data['client'] = 'android';
				if(empty($add_data['cellPhone']))continue;
				if(M('mail_list')->where("cellPhone='{$add_data['cellPhone']}'")->find())continue;
				if(M('mail_list')->add($add_data))$count++;
			}
		}
	}
	$this->data = "成功导入{$total}/{$count}条通讯记录";
	return true;
}

$this->error('TYPE_ERR','type类型错误');

//数组转换为字符串
function arr2string($arr,$key='phoneNumber'){
	$str = '';$str_arr = array();
	if(empty($arr))return $str;
	if(is_array($arr)){//去除空值、去除重复
		$str = implode('|',array_unique(array_filter(array_column($arr,$key))));
	}
	return $str;
}

?>