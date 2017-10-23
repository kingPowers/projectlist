<?php
/*
 * 买车贷-经销商(android)
 * 
 * */
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();
if($type=='list'){
	$dealers = M('dealer')->where("status=1")->order("sort desc,lasttime desc,id desc")->select();
	if(empty($dealers))$dealers = array();
	$this->data = array('list'=>$dealers);
	return true;
}

$this->error('TYPE_ERR','type类型错误');

?>