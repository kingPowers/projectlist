<?php
/**
 * 推送功能，个推
 *   IOS 进入APP存储devicetoken
 * 
 * 
 * */
$type = $this->v('type');
if(!in_array($this->_client_,array('IOS')))
	$this->error('CLIENT_ERROR',"设备类型错误");

if($type=='iosDeviceToken'){//添加devicetoken
	$this->need('deviceToken',"deviceToken");
	$devicetoken = $this->v("deviceToken");//cid
	$dev_info = M('getui_device')->where("devicetoken='{$devicetoken}'")->find();
	if(false==$dev_info){
		M('getui_device')->add(array('devicetoken'=>$devicetoken));
	}
	$this->data = "token添加成功";
    return true;
}


$this->error('TYPE_ERR','type类型错误');
?>