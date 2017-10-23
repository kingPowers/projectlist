<?php
/**
 * 推送功能，个推
 * 
 * 
 * */
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();

if(!in_array($this->_client_,array('ANDROID','IOS')))
	$this->error('CLIENT_ERROR',"设备类型错误");

if($type=='bindCid'){
	$this->need('cid',"设备cid");
	$cid = $this->v("cid");//cid
	$cid_info = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	$getui_array = unserialize($cid_info['getui_cid']);
	/*if(''!=$getui_array[$this->_client_]){
		$this->error("CID_EXISTS",'您已绑定过设备CID');
	}*/
	$getui_array[strtolower($this->_client_)] = $cid;
	//绑定个推
	$bindCid = M('member_info')->where("memberid='{$member_info['id']}'")->save(array('getui_cid'=>serialize($getui_array)));
	if(false==$bindCid){
		$this->error("GETUI_ERROR",'绑定失败');
	}
	$this->data = "设备id绑定成功";
    return true;
}


$this->error('TYPE_ERR','type类型错误');
?>