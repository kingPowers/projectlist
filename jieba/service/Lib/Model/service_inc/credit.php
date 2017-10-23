<?php 
//车友贷
import("Think.ORG.Util.Credit");
$setting = array(
    //车友贷配置        
    'credit'=>Credit::getSetting(["changeFeeTime"=>time()]),
);

$member_info = $this->token_check_force();
$type = $this->v('type');
//车友贷配置
if($type=='setting'){
	$this->data = $setting;
	return true;
}
//车友贷验证码
elseif($type=='creditSendSms'){
	//$this->need('mobile','手机号');
	$mobile = $member_info['mobile'];
	
	if($this->token('credit_smssend_flag')==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
		return $this->error('MOBILE_ERROR','手机号不正确');
	}
	if(false==D('Member')->where(array("mobile"=>$mobile))->field('id')->find()){
		return $this->error('ACCOUNT_NOT_EXIST','手机号未注册');
	}
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token('credit_smscode',$smscode,600);
	$this->token('credit_smsmobile',md5($mobile.$smscode),600);
	$this->token('credit_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
	//车抵贷配置参数
}
//添加车友贷订单
elseif($type=="credit_add"){
	$this->need("loanmoney","贷款金额");
	$data = array();
	$data['loanmoney'] = $this->v("loanmoney");
	$data['order_type'] = 3;
	//车友贷金额判断
	$setting['credit']['loanmoney']['max'] = getMaxMoney($member_info['id']);
	$setting['credit']['loanmoney']['max']<=0?($setting['credit']['loanmoney']['min'] = 0):'';
	$order_info = M('order')
	->table("`order` o,order_process p,order_credit c")
	->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
	->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
	->order("id desc")->find();
	if(!(false==$order_info || $order_info['order_status']==3 || $order_info['credit_status']==1)){
		//未申请过、拒单、完成还款  允许贷款
		$this->error('ORDER_EXISTS','您有未处理完的订单，请稍后再提交！');
	}
	if($this->v("check_money")){
		if($data['loanmoney']<$setting['credit']['loanmoney']['min']){
			$this->error("ERR","贷款金额应至少{$setting['credit']['loanmoney']['min']}元");
		}
		if($data['loanmoney']>$setting['credit']['loanmoney']['max']){
			$this->error("ERR","贷款金额应最多{$setting['credit']['loanmoney']['max']}元");
		}
		return true;
	}
	
	if($data['loanmoney']<$setting['credit']['loanmoney']['min']){
		$this->error("ERR","贷款金额应至少{$setting['credit']['loanmoney']['min']}元");
	}
	if($data['loanmoney']>$setting['credit']['loanmoney']['max']){
		$this->error("ERR","贷款金额应最多{$setting['credit']['loanmoney']['max']}元");
	}
	
	//实名认证
// 	$realname = M('member_info')->where("memberid='{$member_info['id']}'")->find();
// 	if(0==$realname['nameStatus']){
// 		$this->error("NO_REALNAME",'用户未实名认证');
// 	}
	//开户注册
// 	import("Think.ORG.Util.Fuyou");
// 	$fuyou = new Fuyou();
// 	if(false==$fuyou->FuyouStatus($member_info['id'])){
// 		$this->error("NO_OPENFUYOU",'用户未开户注册');
// 	}
	//绑定金账户
// 	if(empty($realname['fuyou_login_id'])){
// 		$this->error("NO_BINDFUYOU",'未绑定金账户');
// 	}
	
	
	
	$this->need('sms_code','验证码');
	$mobile = $member_info['mobile'];

	//传输此码，可以不验证手机验证码
	if(!($this->v('allow_no_sms_key')==md5($setting['borrow_setting']['allow_no_sms_key'])) && !(md5($this->v('sms_code'))==$this->token('credit_smscode') && $this->token('credit_smsmobile')==md5($mobile.$this->token('credit_smscode')))){
		return $this->error('VERIFY_ERROR','验证码不正确');
	}
	
	//清除缓存
	$this->token('credit_smscode',null);
	$this->token('credit_smsmobile',null);
	
	$names = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	$data['loanmonth'] = 7;
	$data['memberid'] = $member_info['id'];
	$data['names'] = $names['names'];
	$data['mobile'] = $member_info['mobile'];
	$data['backtotalmoney'] = $data['loanmoney'];
	$data['resource'] = $this->_client_;
	if($add_id = M('order')->add($data)){
		M('order_credit')->where("order_id='{$add_id}'")->save(array('department'=>getJobs($member_info['id']),'contract_num' =>getContractNum($member_info['id'])));
		/*
		 * 成功申请车友贷，回调此公共方法
		 * */
		import("Think.ORG.Util.RunCommon");
		RunCommon::runApplyCreditSuccess(['order_id'=>$add_id,'memberid'=>$member_info['id']]);
		$this->data = array('add_id'=>$add_id);
		return true;
	}
	$this->error("SYS_ERR",'系统错误稍后重试');
}
//绑定富友金账户手机号验证
elseif($type=='bindFuyouSms'){
	$this->need('mobile','手机号');
	$mobile = $this->v("mobile");
	
	if($this->token('bindfuyou_smssend_flag')==1){
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
	$this->token('bindfuyou_smscode',$smscode,600);
	$this->token('bindfuyou_smsmobile',md5($mobile.$smscode),600);
	$this->token('bindfuyou_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
}
//绑定富友金账户
elseif($type=='bindFuyou'){
	$this->need('sms_code','验证码');
	$this->need('mobile','手机号');
	$this->need('fuyou_login_id',"富友账户id");
	$fuyou_login_id = $this->v('fuyou_login_id');
	//传输此码，可以不验证手机验证码
	if(md5($this->v('sms_code'))!=$this->token('bindfuyou_smscode') || $this->token('bindfuyou_smsmobile')!=md5($this->v('mobile').$this->token('bindfuyou_smscode'))){
		 $this->error('VERIFY_ERROR','验证码不正确');
	}else{
		$this->token('bindfuyou_smscode',null);
	}
	if(false==M('member_info')->where("memberid='{$member_info['id']}'")->save(array('fuyou_login_id'=>$fuyou_login_id)))
		$this->error("ERR","账户绑定失败，请稍后再试！");
	return true;
	
}
//借款记录
elseif($type=='orderList'){
	$page = intval($this->v('page'))>0?intval($this->v('page')):1;
	$number=intval($this->v('number'))>0?intval($this->v('number')):90;
	if($number>100)
		$this->error('NUMBER_ERR','每页的条数不得大于100条');
	$count = M()->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}'")
				->count("distinct o.id");
	if($count>0){
		$order_status_name = " case 
									when o.status=2 and c.status=0 and c.back_time>NOW() then '使用中'
					                when o.status=1 then '审核中'
									when o.status=3 then '未通过'
									when o.status=2 and c.status=1 then '已完成'
								    when o.status=2 and c.status=0 and c.back_time<NOW() then '已逾期'
									end as order_status_name 
									";
		$orderList = M()->table("`order` o,order_process p,order_credit c")
					->where("o.id=p.order_id and o.id=c.order_id  and o.memberid='{$member_info['id']}'")
					->field("o.loanmoney,p.customer_status,o.id,date_format(o.timeadd,'%Y年%m月%d日') timeadd,c.status,{$order_status_name}")
					->order("o.timeadd desc")
					->limit((($page-1)*$number).",{$number}")
					->select();
		
	}
	count($orderList)<1?($orderList = array()):'';
	$this->data = array('count'=>intval($count),'orderlist'=>$orderList);
	return true;
	
}
//车友贷借款详情
elseif($type=='orderListDetail'){
	 $this->need('id','订单id');
	 $order_id = $this->v('id');
	$order_status_name = " case
									when o.status=2 and c.status=0 and c.back_time>NOW() then '1'
					                when o.status=1 then '2'
									when o.status=3 then '3'
									when o.status=2 and c.status=1 then '4'
								    when o.status=2 and c.status=0 and c.back_time<NOW() then '5'
									end as credit_order_status
									";
	$order_info = M()->table("`order` o,order_process p,order_credit c")
	->where("o.id=p.order_id and o.id=c.order_id  and o.memberid='{$member_info['id']}' and o.id='{$order_id}'")
	->field("o.loanmoney,p.customer_status,o.id,date_format(o.timeadd,'%Y年%m月%d日') timeadd,c.status,c.back_time,date_format(c.pay_time,'%Y-%m-%d') pay_time,date_format(c.back_time,'%Y-%m-%d') back_date,{$order_status_name}")
	->order("o.timeadd desc")
	->find();
	if($order_info['credit_order_status']==5){
		$order_info['late_days'] =ceil((time()-strtotime($order_info['back_time']))/86400);//逾期天数
		$order_info['late_fee'] = round($order_info['loanmoney']*$order_info['late_days']*0.01,2);//逾期金额
	}else{
		$order_info['late_days'] = 0;
		$order_info['late_fee'] = 0;
	}
	$this->data = array('order_detail'=>$order_info);
	return true;
}
//车友贷状态、基本信息
elseif($type=='credit_status'){
	$status = 1;
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
				->order("id desc")->find();
	if(false==$order_info || $order_info['order_status']==3 || $order_info['credit_status']==1){
		//未申请过、拒单、完成还款  允许贷款
		$status = 1;
	}elseif($order_info['order_status']==1 && $order_info['customer_status']==0 ){
		//订单处于审核中  不允许贷款
		$status=2;
	}elseif($order_info['order_status']==1 && $order_info['customer_status']==1){
		//订单处于审核通过  可以签约
		$status=4;
	}elseif($order_info['order_status']==1 && $order_info['customer_status']==6){
		//订单处于审核通过  签约完成，等待打款
		$status=5;
	}elseif($order_info['order_status']==2 && $order_info['credit_status']==0){
		//完成放款，可以还款
		$status=3;
	}
	//车友贷  贷款总额    申请人数
	$credit_total_money = M('order')->where("order_type=3")->field("sum(loanmoney) as backtotalmoney")->find();
	$credit_total_num = M('order')->where("order_type=3")->count('distinct memberid');
	
	$maxMoney = getMaxMoney($member_info['id']);
	$minMoney = $maxMoney<=0?0:1000;
	$is_late = empty($order_info['back_time'])?'0':(time()>strtotime($order_info['back_time'])?1:0);
	empty($order_info)?($order_info = array("id"=>'',"loanmoney"=>'',"timeadd"=>'','order_status'=>'','customer_status'=>'','credit_status'=>'','back_time'=>'','order_sn'=>'')):'';
	
	//活动券URL
	import("Think.ORG.Util.Activity");
	$activity = new Activity;
	$activityUrl = $activity->getCreditTicketUrl($member_info['id']);
	
	$this->data = array("activityUrl"=>$activityUrl,'credit_total_money'=>$credit_total_money['backtotalmoney']*5,'credit_total_num'=>$credit_total_num*10,'order_status'=>$status,'minMoney'=>$minMoney,'maxMoney'=>$maxMoney,'is_late'=>$is_late,'order_info'=>$order_info);
	return true;
	
}
//富友金账户基本信息
elseif($type=='fuyou_info'){
	import("Think.ORG.Util.Baofu");
	$baofu = new Baofu($member_info['id']);
	$preBindInfo = $baofu->bindBankCardStatus();
	if(false==$preBindInfo){
		$this->error("ERR","未绑定银行卡");
	}
	$preBindInfo['capAcntNo'] = substr($preBindInfo['acc_no'],0,4)."********".substr($preBindInfo['acc_no'],-3);
	$preBindInfo['mobile_no'] = substr($preBindInfo['mobile'],0,3)."****".substr($preBindInfo['mobile'],-3);
	$preBindInfo['cust_nm'] = $preBindInfo['names'];
	$preBindInfo["bank_nm"] = "";//支行
	$this->data = array('fuyou_info'=>$preBindInfo);
	return true;
}
//车友贷还款基本信息
elseif($type=='repayment_info'){
	$arr_data = [];
	import("Think.ORG.Util.Fuyou");
	$this->fuyou = new Fuyou();
	//富友金账户余额
	$balance = $this->fuyou->BalanceAction($member_info['id']);
	
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
				->order("id desc")->find();
	if(!($order_info['order_status']==2 && $order_info['credit_status']==0)){
		$this->error('ORDER_ERROR','订单未处于还款中');
	}
	$arr_data["order_id"] = $order_info["id"];
	$arr_data['timeadd'] = $order_info['timeadd'];//申请时间
	$arr_data['ca_balance'] = round(intval($balance['ca_balance'])/100,2);//富友账户余额
	$arr_data['loanmoney'] = $order_info['loanmoney'];//贷款金额
	$arr_data['back_time'] = $order_info['back_time'];//最迟还款日期
	$arr_data['is_late'] = time()<=strtotime($arr_data['back_time'])?0:1;//是否逾期    1：是
	if($arr_data['is_late']==1){//已逾期
		$arr_data['late_days'] =ceil((time()-strtotime($arr_data['back_time']))/86400);//逾期天数
		//2017-03-27 以后增加逾期利率
		if($order_info['timeadd']>='2017-03-27 00:00:00' && $arr_data['late_days']>5){
			$arr_data['late_fee'] = round($arr_data['loanmoney']*(5*0.01+($arr_data['late_days']-5)*0.02),2);
		}else{
			$arr_data['late_fee'] = round($arr_data['loanmoney']*$arr_data['late_days']*0.01,2);
		}
		//$arr_data['late_fee'] = round($arr_data['loanmoney']*$arr_data['late_days']*0.01,2);//逾期金额
		$arr_data['title'] = "逾期还款";
		$arr_data['adv_days'] = 0; //还款天数
	}else{//未逾期
		$days = intval(ceil((strtotime($arr_data['back_time'])-time())/86400)-1);
		$arr_data['late_days'] = 0;
		$arr_data['late_fee'] = 0;
		$arr_data['adv_days'] = $days; //提前还款天数
		$arr_data['title'] = $days<1?"我要还款":"提前还款";
	}
	$arr_data['back_money'] = $arr_data['loanmoney']+$arr_data['late_fee'];//还款金额
	$arr_data['balance_allow'] = $balance['ca_balance']>=intval($arr_data['back_money']*100)?1:0;//金账户余额是否够扣
	$this->data = array('repayment'=>$arr_data);
	return true;	
}
//提交车友贷还款
elseif($type=='repayment'){
	$this->need('sms_code','验证码');
	$sms_code = $this->v('sms_code');
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
				->order("id desc")->find();
	import("Think.ORG.Util.Credit");
	$credit = new Credit($member_info['id']);
	$trans = $credit->person2company($order_info['id'],$sms_code,"prePaySubmit");
	if(false==$trans){
		$error = $credit->getError();
		$this->error("BAOFU_ERROR",$error);
	}
	$this->data = "还款成功";
	return true;
}
//还款的时候，发送验证码
elseif($type=="repaymentSms"){
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
				->order("id desc")->find();
	if($this->token("repaymentSms_flag_{$order_info['id']}")){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	import("Think.ORG.Util.Credit");
	$credit = new Credit($member_info['id']);
	$trans = $credit->person2company($order_info['id'],"","prePay");
	if(false==$trans){
		$error = $credit->getError();
		$this->error("BAOFU_ERROR",$error);
	}
	$this->token("repaymentSms_flag_{$order_info['id']}",1,60*2);
	$this->data = "验证码发送成功";
	return true;
}
//取消订单
elseif($type=='give_up_order'){
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
				->order("id desc")->find();
	$order_info = M('order')->where("id='{$order_info['id']}' and memberid='{$member_info['id']}'")->find();
	if(false==$order_info)$this->error("ORDER_NOT_EXIST","订单不存在！");
	//客服拒单
	$order_id = M('order')->where("id='{$order_info['id']}'")->save(array('status'=>3));
	$save_arr = array(
			'customer_status'=>2,
			'customer'=>'客户放弃订单',
			'customer_time'=>date("Y-m-d H:i:s"),
			'customer_remark'=>"客户放弃订单",
	);
	$process_id = M('order_process')->where("order_id='{$order_info['id']}'")->save($save_arr);
	if(!($order_id && $process_id))
		$this->error("SYS_ERROR","网络连接超时，请稍后重试！");
	$this->data = "取消订单成功";
	return true;
}
//电子签章获取验证码
elseif($type=='eqian_sms'){
	import("Think.ORG.Util.Eqian");
	$eqian = new Eqian();
	$certi = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	$return = $eqian->sendSignCode($certi['certiNumber']);
	if($return){
		$this->data = '短信发送成功';
		return true;
	}else{
		$this->error("EQIAN_ERROR",'短信发送失败');
	}
}
//签署电子签章
elseif($type=='eqian_submit'){
	$this->need('eqian_smscode');
	$sms_code = $this->v('eqian_smscode');
	//获取订单信息
	$order_info = M('order')
				->table("`order` o,order_process p,order_credit c")
				->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$member_info['id']}' and o.order_type=3")
				->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn,c.contract_num")
				->order("id desc")->find();
	
	$credit_info = M('order_credit')->where(array('order_id'=>$order_info['id']))->find();
	$certi = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	if(empty($credit_info) || empty($credit_info['order_sn'])){
		$this->error("ORDER_ERROR",'订单信息有误');
	}
	import("Think.ORG.Util.Eqian");
	$eqian = new Eqian();
	$path = UPLOADPATH.'eqian/'.$order_info['order_sn'].'_2.pdf';
	$path1 = UPLOADPATH.'eqian/'.$order_info['order_sn'].'_2_1.pdf';
	$return = $return1 = '';
        //借吧居间协议
	$return = $eqian->userSafeSignPDF($certi['certiNumber'],$path, $path1,4, '', 120, '', '甲方(盖章)','',$sms_code,0,$order_info['id']);
	if($return){
                /*
                 * 个人协议
                 *  --免验证码签章
                 */
		$staticPath = UPLOADPATH.'eqian/'.$order_info['order_sn'].'_1.pdf';
		$staticPath1 = UPLOADPATH.'eqian/'.$order_info['order_sn'].'_1_1.pdf';
		$return1 = $eqian->userSafeSignPDF($certi['certiNumber'],$staticPath, $staticPath1,4, '', 120, '', '乙方(盖章)','',$sms_code,1,$order_info['id']);
                /*
                 * 补充协议
                 *    --免验证码签章
                 */
                $path4 = UPLOADPATH.'eqian/'.$credit_info['order_sn'].'_4.pdf';
                $path4_1 = UPLOADPATH.'eqian/'.$credit_info['order_sn'].'_4_1.pdf';
                if(is_file($path4)){
            		$eqian->userSafeSignPDF1($certi['certiNumber'],$path4,$path4_1,4, '', 120, '', '甲方(盖章)','',$sms_code,1,$order_info['id']);
            	}
                /*
                 * 员工个人借款承诺书
                 *      --仅仅个人签章个
                 *      --免验证码签章
                 */
            	$path3 = UPLOADPATH.'eqian/'.$credit_info['order_sn'].'_3.pdf';
                $path3_1 = UPLOADPATH.'eqian/'.$credit_info['order_sn'].'_3_1.pdf';
            	if(is_file($path3)){
            		$eqian->userSafeSignPDF1($certi['certiNumber'],$path3,$path3_1,4, '', 120, '', '承诺人(盖章)','',$sms_code,1,$order_info['id']);
            	}
   
        }else{
            $this->error("EQIAN_ERROR","签约失败(".$eqian->geterror().")");
        }
        
        $re = M('order_process')->where(array('order_id'=>$order_info['id']))->save(array('customer_status'=>6));
        if($re===false){
                $this->error("SYS_ERROR",'修改订单状态失败');
        }else{
                $this->data = '签约成功';
                return true;
        }
}

$this->error('TYPE_ERR','type类型错误');

//获取用户贷款金额
function  getMaxMoney($memberid){
		$pay_money = $currentMoney = $maxMoney = 0;
		$member_info = M('member_info')->where("memberid='{$memberid}'")->find();
		if(empty($member_info['certiNumber']))return $maxMoney;
		$credit_list = M('order_upload')->where("cer_card='{$member_info['certiNumber']}' and  total_num>return_num")->select();
		foreach($credit_list as $k=>$v){
			$return_num = $v['return_num'];//已还期数
			$delay_num = $v['delay_num'];//逾期期数
			if($return_num>=3 && $return_num<=5 && $delay_num>0){
				//D 类客户 还款3-5期，出现逾期
				$currentMoney = 3000;
			}elseif($return_num>=3 && $return_num<=5 && $delay_num<1){
				//C 类客户 还款3-5期，未出现逾期
				$currentMoney = 5000;
			}elseif($return_num>=6  && $return_num<24 && $delay_num>0){
				//B 类客户 还款6期以上（包含6），出现逾期
				$currentMoney = 8000;
			}elseif($return_num>=6 && $return_num<24 && $delay_num<1){
				//A 类客户 还款6期以上（包含6），未出现逾期
				$currentMoney = 10000;
			}
			//每一期的还款金额取其较小者
			$pay_money = $pay_money==0?$v['pay_money']:($pay_money>$v['pay_money']?$v['pay_money']:$pay_money);
			//取其抵贷记录较小者
			$maxMoney = $maxMoney==0?$currentMoney:($maxMoney>$currentMoney?$currentMoney:$maxMoney);
		}
		//每期还款
		if(intval($pay_money)>0)
			$maxMoney = $maxMoney>intval($pay_money)?intval($pay_money):$maxMoney;
		//积分高的用户额度高
		$Propore = signCredit($memberid);
		$maxMoney = intval($Propore*$maxMoney)<15000?intval($Propore*$maxMoney):15000;
		//员工贷款金额优先
		$maxMoney = false!==($staffMoney = getStaffMoney($memberid))?$staffMoney:$maxMoney;
		/*
		 * 佛光普照，每人均提额20% 2017-04-07 10:30
		 * */
		$maxMoney = $maxMoney*1.2;
		/*
		 * 注册赠送车友贷额度券
		*
		*/
		$maxMoney = creditTicket($memberid,$maxMoney);
		/*
		 * 大转盘券提额活动  2017-04-17
		 * 
		 * */
		$maxMoney = creditTurnTableTicket($memberid,$maxMoney);
		
                
                if(time()>=strtotime("2017-07-07 18:00:01") && time()<strtotime("2017-08-01")){
			$maxMoney = $maxMoney*1.5;
		}
        //提额活动
		$ac_map = [
			'status' => 1,
			'starttime' => ['ELT',date("Y-m-d H:i:s",time())],
			'endtime' => ['EGT',date("Y-m-d H:i:s",time())]
		];
		$pro_activity = getPromoteActivity($ac_map);
		foreach ($pro_activity as $value) {
			$maxMoney += $maxMoney*($value['pro_percent']/100);
		}        
                //两个月的还款用户限额2k
                if($return_num==2){
                    $maxMoney = 2000;
                }
                
		return intval($maxMoney);
	}
	
	//获取员工的最大金额
	function getStaffMoney($memberid){return false;
		$is_staff = M()->table("member_info i,staff s")->field("s.maxMoney")->where("i.memberid='{$memberid}' and  i.certiNumber=s.certiNumber and s.status=0")->find();
		if(false==$is_staff)return false;
		$is_staff['maxMoney'] = $is_staff['maxMoney']<1000?1000:$is_staff['maxMoney'];
		$is_staff['maxMoney'] = $is_staff['maxMoney']>10000?10000:$is_staff['maxMoney'];
		return intval($is_staff['maxMoney']);
	}
	
	//获取用户的门店|门店职位
	function getJobs($memberid){
		$is_staff = M()->table("member_info i")
						->join("staff s on s.certiNumber=i.certiNumber and s.status=0 ")
						->join("order_upload u on i.certiNumber=u.cer_card")
						->field("s.staff_jobs,u.product,u.department")
						->where("i.memberid='{$memberid}'")
						->group("i.memberid")
						->find();
		return $is_staff['staff_jobs']?$is_staff['staff_jobs']:$is_staff['department'];
	}
	//获取用户的合同编号
	function getContractNum($memberid){
		$contract_nums = M('order_upload ou')
		 ->join('member_info mi on mi.certiNumber=ou.cer_card')
		 ->field('ou.contract_num')
		 ->where("mi.memberid='{$memberid}'")
		 ->order('ou.return_num')
		 ->select();
		 return $contract_nums['0']['contract_num']?$contract_nums['0']['contract_num']:'';
	}
	/*
	 * 积分提升车友贷的额度 
	 * 				2016-12-01  13:55
	 * 
	 * @return 积分比例
	 * 		  66<= 积分<218   比例：1.1
	 * 		 218<=积分               比例：1.2  
	 * */
	function  signCredit($memberid){
		$Propore = 1;
		$score = M('sign')->where("memberid='{$memberid}'")->sum('score');
		if(intval($score)>=66 && intval($score)<218){
			$Propore = 1.1;
		}elseif(intval($score)>=218){
			$Propore = 1.2;
		}
		return $Propore;
	}
	
	//车友贷提高额度的券
	function creditTicket($memberid,$money){
		import("Think.ORG.Util.Activity");
		$activity = new Activity;
		if(false==($ticketInfo = $activity->isHaveCreditTicket($memberid)))return $money;
		$money = $money+round($money*$ticketInfo['up_percent']/100,2);
		return $money;
	}
	//车友贷大转盘提高额度券
	function creditTurnTableTicket($memberid,$money){
		import("Think.ORG.Util.Activity");
		$activity = new Activity;
		if(false==($ticketInfo = $activity->turnTableTicket($memberid)))return $money;
		$money = $money+round($money*$ticketInfo['up_percent']/100,2);
		return $money;
	}
	//获取提额活动的提升额度
	function getPromoteActivity($map)
	{
		return M('activity_promote')->field('pro_percent')->where($map)->select();
	}
?>