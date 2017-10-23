<?php
/*
 * 开通金牌经纪人权限
 * */
$type = $this->v('type');
//检测用户是否登录
if($type!="orderList")
    $member_info = $this->token_check_force();
//$this->error("ERR","对不起，系统正在升级");
import("Think.ORG.Util.GoldAgent");
$this->gold = new GoldAgent();
//配置
if($type=='setting'){
	$setting_type = $this->v('setting_type');
	$setting = $this->gold->getSetting($setting_type);
	$setting['agent_about'] = _WWW_."/agent/about";//关于甩单
	$setting['agent_agreement'] = _WWW_."/agent/contract?token=";//认证金牌经纪人协议
	$this->data = $setting;
	return true;
}
//开通经纪人认证
elseif($type=='openAgent'){
	//未支付金额
	if(false==$this->gold->isPayGold($member_info['id'])){
		$data = [];
		$data['memberid'] = $member_info['id'];
		if(false==($openResult = $this->gold->openGold($data))){
			$this->error('OPEN_ERROR',$this->gold->getError());
		}
		$this->data = "支付成功";
		return true;
	}else{
		$this->need('sms_code','手机验证码');
		if(md5($this->v('sms_code'))!=$this->token('agent_smscode') || $this->token('agent_smsmobile')!=md5($member_info['mobile'].$this->token('agent_smscode'))){
			return $this->error('VERIFY_ERROR','验证码不正确');
		}
		$this->token('sms_code',null);
		$this->token('agent_smsmobile',null);
		$data = [];
		$data['memberid'] = $member_info['id'];
		$data['province'] = $this->v('province');
		$data['city'] = $this->v('city');
		$data['company_name'] = $this->v('company_name');
		$data['company_full_name'] = $this->v('company_full_name');
		$data['nickname'] = mb_substr($member_info['bindinfo']['names'],0,1,"UTF-8")."经理";
		if(false==($openResult = $this->gold->openGold($data))){
			$this->error('OPEN_ERROR',$this->gold->getError());
		}
		$this->data = "恭喜您，认证成功";
		return true;
	}
	
}
//经纪人认证发送手机验证码
elseif($type=='openAgentSendSms'){
	$mobile = $member_info['mobile'];
	if($this->token('agent_smssend_flag')==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
		 $this->error('MOBILE_ERROR','手机号不正确');
	}
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		 $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token('agent_smscode',$smscode,600);
	$this->token('agent_smsmobile',md5($mobile.$smscode),600);
	$this->token('agent_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
}
//我的订单列表
elseif($type=='myOrderList'){
	$this->need('page','分页page');
	$page = $this->v('page');
	$number = $this->v('number')?$this->v('number'):10;
	if(false===($list = $this->gold->myOrderList($member_info['id'],$page,$number))){
		$this->error("LIST_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
//金牌经济人--添加甩单
elseif($type=='addOrder'){
	$data = [];
	$data['memberid'] = $member_info['id'];
	$_REQUEST['client'] =  $this->_client_;
	$data = array_merge($data,$_REQUEST);
	if(false==$this->gold->addOrder($data))$this->error("ORDER_ERROR",$this->gold->getError());
	$this->data = "甩单成功，请等待审核";
	return true;
}
//金牌经纪人--修改订单数据详情
elseif($type=='editOrder'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	$editInfo = $this->gold->editOrder($order_id);
	if(false===$editInfo)$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = $editInfo;
	return true;
}
//提交修改订单的数据，完成修改
elseif($type=='submitEditOrder'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	$_REQUEST['memberid'] = $member_info['id'];
	if(false===$this->gold->submitEditOrder($order_id,$_REQUEST))$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = "修改成功";
	return true;
}
//删除我的订单
elseif($type=='deleteMyOrder'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	if(false===$this->gold->deleteMyOrder($member_info['id'],$order_id))$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = "删除成功";
	return true;
}
//完成我的订单
elseif($type=='finishMyOrder'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	if(false===$this->gold->finishMyOrder($member_info['id'],$order_id))$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = "恭喜您，成单了";
	return true;
}
//订单列表
elseif($type=='orderList'){
	$this->need('page','分页page');
	$page = $this->v('page');
	$number = $this->v('number')?$this->v('number'):10;
	$_where = [];
	if($status = $this->v('status'))$_where['status'] = $status;
	if($is_fullmoney = $this->v('is_fullmoney'))$_where['is_fullmoney'] = $is_fullmoney;
	if($jobs = $this->v('jobs'))$_where['jobs'] = $jobs;
        if($province = $this->v('province')){$province = rtrim(rtrim($province,"省"),"市");$_where['province'] = array('like',"%{$province}%");}
        if($city = $this->v('city')){$city = rtrim($city,"市");$_where['city'] = array('like',"%{$city}%");}
	
	if(false===($list = $this->gold->orderList($member_info['id'],$_where,$page,$number))){
		$this->error("LIST_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
//订单详情--查看别人订单详情
elseif($type=='orderDetail'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	$detailInfo = $this->gold->orderDetail($member_info['id'],$order_id);
	if(false===$detailInfo)$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = $detailInfo;
	return true;
}
//订单解锁
elseif($type=='addUnlock'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	$unLockInfo = $this->gold->addUnlock($member_info['id'],$order_id);
	if(false===$unLockInfo)$this->error("EDIT_ERROR",$this->gold->getError());
	$setting = $this->gold->getSetting("unlock_money");
	$this->data = "解锁成功,已从您账户扣除{$setting}元";
	return true;
}
//重新上架
elseif($type=='passedAgain'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	$passedAgain = $this->gold->passedAgain($member_info['id'],$order_id);
	if(false===$passedAgain)$this->error("EDIT_ERROR",$this->gold->getError());
	$this->data = "上架成功";
	return true;
}
//已解锁订单列表
elseif($type=='lockedOrderList'){
	$this->need('page','分页page');
	$page = $this->v('page');
	$number = $this->v('number')?$this->v('number'):10;
	if(false===($list = $this->gold->lockedOrderList($member_info['id'],$page,$number))){
		$this->error("LIST_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
//经纪人账户--个人信息修改
elseif($type=='editAgentInfo'){
	$this->need('sms_code','手机验证码');
	$this->need('nickname','昵称');
	$this->need('company_name','公司简称');
	$this->need('company_full_name','公司全称');
	//$this->need('pic_card2','个人名片');
	if(md5($this->v('sms_code'))!=$this->token('editagent_smscode') || $this->token('editagent_smsmobile')!=md5($member_info['mobile'].$this->token('editagent_smscode'))){
		$this->error('VERIFY_ERROR','验证码不正确');
	}
	$this->token('editagent_smscode',null);
	$this->token('editagent_smsmobile',null);
	
	$key = "editAgentInfo_{$member_info['mobile']}";
	if(intval(S($key))>500){
		$this->error("AGENT_EXIST_NUM","对不起，每个月只可修改一次");
	}
	if(false===$this->gold->editAgentInfo($member_info['id'],$_REQUEST))$this->error("EDIT_ERROR",$this->gold->getError());
	S($key, intval(S($key)) + 1,60*60*24*30);
	$this->data = "修改成功";
	return true;
	
}
//经纪人账户--个人账户修改，获取验证码
elseif($type=='editAgentSendSms'){
	$mobile = $member_info['mobile'];
	if($this->token('editagent_smssend_flag')==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
		return $this->error('MOBILE_ERROR','手机号不正确');
	}
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token('editagent_smscode',$smscode,600);
	$this->token('editagent_smsmobile',md5($mobile.$smscode),600);
	$this->token('editagent_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
}
//账户记录列表
elseif($type=='transList'){
	$this->need('page','分页page');
	$page = $this->v('page');
	$number = $this->v('number')?$this->v('number'):10;
	if(false===($list = $this->gold->transList($member_info['id'],$page,$number))){
		$this->error("LIST_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
//提交举报我解锁的订单
elseif($type=='addReportOrder'){
	$data = [];
	$this->need('order_id','订单id');
	$this->need('transfer_id','解锁id');
	
	$this->need('content','举报内容');
	$data['order_id'] = $this->v('order_id');
	$data['content'] = $this->v('content');
	$data['client'] = $_REQUEST['client'] =  $this->_client_;
	if(false==($add_id = $this->gold->addReportOrder($member_info['id'],$data)))$this->error("LOCKED_ERROR",$this->gold->getError());
	$this->data =['message'=>"举报成功！",'add_id'=>$add_id];
	return true;
}
//我举报订单的内容
elseif($type=='reprotOrderInfo'){
	$this->need('order_id','订单id');
	$this->need('transfer_id','解锁id');
	$order_id = $this->v('order_id');
	$transfer_id = $this->v('transfer_id');
	if(false===($list = $this->gold->reprotOrderInfo($member_info['id'],$order_id,$transfer_id))){
		$this->error("LOCKED_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
//删除我已解锁的订单
elseif($type=='deleteMyLockedOrder'){
	$this->need('order_id','订单id');
	$order_id = $this->v('order_id');
	if(false===($list = $this->gold->deleteMyLockedOrder($member_info['id'],$order_id))){
		$this->error("LOCKED_ERROR",$this->gold->getError());
	}
	$this->data = "删除成功";
	return true;
}
//等级说明
elseif($type=='levelDes'){
    if(false===($list = $this->gold->levelDes($member_info['id']))){
            $this->error("LIST_ERROR",$this->gold->getError());
    }
    $this->data = $list;
    return true;
}
//邀请记录
elseif($type=='recommandList'){
	$this->need('page','分页page');
	$page = $this->v('page');
	$number = $this->v('number')?$this->v('number'):10;
	if(false===($list = $this->gold->recommandList($member_info['id'],$page,$number))){
		$this->error("LIST_ERROR",$this->gold->getError());
	}
	$this->data = $list;
	return true;
}
$this->error('TYPE_ERR','type类型错误');
?>