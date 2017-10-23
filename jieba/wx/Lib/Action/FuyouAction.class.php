<?php
/*
 *   富友金账户管理系统
 *   
 *   充值成功通知接口：http://www.jieba360.com/Fuyou/apiCashInNoticeSuccess
 *   提现成功通知接口：http://www.jieba360.com/Fuyou/apiCashOutNoticeSuccess
 *   提现退票通知接口：http://www.jieba360.com/Fuyou/apiCashOutNoticeError
 * 
 * 
 * */
class FuyouAction extends Action{
	
	private $private_key_path = "";//私钥文件路径
	private $public_key_path = "";//公钥文件路径
	private $openApi = 1;//开启通知接口
	
	function _initialize(){
		$this->private_key_path = dirname(FRAME_PATH).'/cer/php_prkey.pem';
		$this->public_key_path = dirname(FRAME_PATH).'/cer/php_pbkey.pem';
		import("Think.ORG.Util.Fuyou");
		$this->fuyou = new Fuyou();
	}
	
	
//------------------------------------------------金账户绑定银行卡（开户注册）成功后的回调--------------------------------------------
	/*
	 * APP开户注册    绑定银行卡回调
	* 		--富友回调此方法，完成 ‘开通第三方托管账户’
	* */
	public function appDoBindAccount(){
		//防止重复回调
		/*$member_info = M('member_info')->where("memberid='{$_POST['user_id_from']}' and memberid!=''")->find();
		if(!empty($member_info['fuyou_login_id'])){
			header("Content-type:text/xml");
			echo $this->fuyou->respXmlAppReg();
			header("Content-type:text/html;charset=utf-8");
			 exit('<script>alert("银行卡绑定成功");window.close();</script>');
			exit;
		}*/
		
		//开始验签
		$request_data = array(
				'resp_code'=>$_POST['resp_code'],//响应码
				'mchnt_cd'=>$_POST['mchnt_cd'],//商户代码
				'mchnt_txn_ssn'=>$_POST['mchnt_txn_ssn'],//请求流水号,最后一位为设备型号
				'mobile_no'=>$_POST['mobile_no'],//手机号码
				'cust_nm'=>$_POST['cust_nm'],//客户姓名
				'certif_id'=>$_POST['certif_id'],//身份证号码
				'email'=>$_POST['email'],//邮箱地址
				'city_id'=>$_POST['city_id'],//开户行地区代码
				'parent_bank_id'=>$_POST['parent_bank_id'],//开户行行别
				'bank_nm'=>$_POST['bank_nm'],//开户行支行名称
				'capAcntNo'=>$_POST['capAcntNo'],//帐号
				'user_id_from'=>$_POST['user_id_from'],//用户在商户系统的标志
				//'ver'=>$_POST['ver'],//版本号
		);
		ksort($request_data);
		$data = implode('|',$request_data);//待验证数据
		$sign = $_POST['signature'];//签名
		$resp_code = $_POST['resp_code'];//响应码
		//验签通过
		if($this->fuyou->rsaVerify($data, $sign)){
			$msg = $this->fuyou->getCode($_POST['resp_code']);
			$arr_client = array('2'=>'ios','3'=>'Android','4'=>'weixin');
			$client = substr($_POST['mchnt_txn_ssn'],-1);
			$log_data = array(
					'memberid'=>$_POST['user_id_from'],
					'type'=>$arr_client[$client],
					'relationsn'=>$_POST['mchnt_txn_ssn'],
					'codeid'=>'1',
					'extend'=>serialize($request_data)."|".serialize($_GET)."|".serialize($_POST),
					'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
					'note'=>$arr_client[$client].'开户注册',
			);
			$this->fuyou->log($log_data);
		}
		$reg_result=$this->fuyou->checkSignString($data,$sign,$resp_code);
		if(false==$reg_result){
			$error = $this->fuyou->getError();
			$error = $error?$error:"({$_POST['resp_code']})".'系统错误';
			header("Content-type:text/html;charset=utf-8");
			exit("<script>alert('{$error},请联系客服人员');window.close();</script>");
		}
		//开始绑定富友账户
		$post = array(
				'fuyou_login_id' => $_POST['mobile_no'],//富友注册用户名
		);
		if (!M('member_info')->where("memberid='{$_POST['user_id_from']}'")->save($post)) {
			header("Content-type:text/html;charset=utf-8");
			exit('<script>alert("网路开小差了，银行卡更新失败");window.close();</script>');
		}
		//实名认证
		$member_info = M('member_info')->where("memberid='{$_POST['user_id_from']}'")->find();
		if(0==$member_info['nameStatus']){
			M('member_info')->where("memberid='{$_POST['user_id_from']}'")->save(array('nameStatus'=>1,'names'=>$_POST['cust_nm'],'certiNumber'=> $_POST['certif_id']));
		}
		//回调地址参数
		$jumpurl = urldecode($_REQUEST['jumpurl'])?urldecode($_REQUEST['jumpurl']):"/member/account";
		redirect($jumpurl);
	}
	
	
	

	
//----------------------------------------------充值     成功后的回调----------------------------------------------------
/*
 * app充值成功回调接口
 * 			---充值成功后回调此方法
 * */
	
public function carryBack(){
	//开始验签
	$request_data = array(
			'resp_code'=>$_POST['resp_code'],//响应码
			'mchnt_cd'=>$_POST['mchnt_cd'],//商户代码
			'mchnt_txn_ssn'=>$_POST['mchnt_txn_ssn'],//请求流水号
			'login_id'=>$_POST['login_id'],//交易用户
			'amt'=>$_POST['amt'],//交易金额
			//'rem'=>$_POST['rem'],//备注
		);
	ksort($request_data);
	$data = implode('|',$request_data);//待验证数据
	$sign = $_POST['signature'];//签名
	$resp_code = $_POST['resp_code'];//响应码
	//验签通过
	if($this->fuyou->rsaVerify($data, $sign)){
		$msg = $this->fuyou->getCode($_POST['resp_code']);
		$memberid = M('member_info')->field('memberid')->where("fuyou_login_id='{$_POST['login_id']}'")->find();
		$log_data = array(
				'memberid'=>$memberid['memberid'],
				'relationsn'=>$_POST['mchnt_txn_ssn'],
				'type'=>'weixin',
				'codeid'=>'3',
				'extend'=>serialize($request_data),
				'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
				'note'=>'充值',
		);
		$this->fuyou->log($log_data);
	}
	$data=$this->fuyou->checkSignString($data,$sign,$resp_code);
	if(false==$data){
		$error = $this->fuyou->getError();
		$error = $error?$error:"({$_POST['resp_code']})".'系统错误';
		header("Content-type:text/html;charset=utf-8");
		exit("<script>alert('{$error}');window.close();</script>");
	}
	$ret = M('CashIn')->where(array('insn' =>$_POST['mchnt_txn_ssn']))->save(array('status' => 2, 'arrivaltime' => date('Y-m-d H:i:s')));
	$this->send_sms($memberid['memberid'],$_POST['mchnt_txn_ssn'],'cash_in');
	header("Content-type:text/html;charset=utf-8");
	$returnurl = urldecode($_REQUEST['returnurl'])?urldecode($_REQUEST['returnurl']):"/member/account";
	exit("<script language='javascript'>alert('充值成功');window.location.href='{$returnurl}';</script>");
}
	
//---------------------------------------------提现    成功后的回调--------------------------------------------------------
/*
 * 提现流程：
 * 
 * 说明：
 * 
 * */
	
	/*提现     APP提现回调接口
	 *			--申请提现成功后，回调此方法
	* */
	public function cashoutBack(){
		//开始验签
		$request_data = array(
				'resp_code'=>$_POST['resp_code'],//响应码
				'mchnt_cd'=>$_POST['mchnt_cd'],//商户代码
				'mchnt_txn_ssn'=>$_POST['mchnt_txn_ssn'],//请求流水号
				'login_id'=>$_POST['login_id'],//交易用户
				'amt'=>$_POST['amt'],//交易金额
		);
		ksort($request_data);
		$data = implode('|',$request_data);//待验证数据
		$sign = $_POST['signature'];//签名
		$resp_code = $_POST['resp_code'];//响应码
		$cashout_info = M("CashOut")->where("outsn='{$_POST['mchnt_txn_ssn']}'")->find();
		$client_arr = $cashout_info['remark']?explode(',',$cashout_info['remark']):array();
		//验签通过，写入日志
		if($this->fuyou->rsaVerify($data, $sign)){
			$msg = $this->fuyou->getCode($_POST['resp_code']);
			$log_data = array(
					'memberid'=>$cashout_info['memberid'],
					'relationsn'=>$_POST['mchnt_txn_ssn'],
					'type'=>$client_arr[0],
					'codeid'=>'4',
					'extend'=>serialize($request_data),
					'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
					'note'=>$client_arr[0].'申请提现',
			);
			$this->fuyou->log($log_data);
		}
		//验签通过，响应码（0000：成功）
		$reg_result=$this->fuyou->checkSignString($data,$sign,$resp_code);
		if(false==$reg_result){
			$error = $this->fuyou->getError();
			$error = $error?$error:"({$_POST['resp_code']})".'系统错误';
			header("Content-type:text/html;charset=utf-8");
			exit("<script>alert('{$error}');location.href='/credit/cashout';</script>");
		}
	
		//扣除账户余额
		header("Content-type:text/html;charset=utf-8");
		//$F = M('CashOut')->where(array('outsn' =>$_POST['mchnt_txn_ssn']))->save(array('status'=>2,'lasttime'=>date('Y-m-d H:i:s')));
		$this->send_sms($cashout_info['memberid'],$_POST['mchnt_txn_ssn'],'cash_out');
		$returnurl = urldecode($_REQUEST['returnurl'])?urldecode($_REQUEST['returnurl']):"/member/account";
		exit("<script>alert('申请提现成功！');location.href='{$returnurl}';</script>");
	
	}	
	
	
//-------------------------------------提现通知接口         提现成功通知    提现失败通知--------------------------------------------------
	/*
	 * 富友金账户  提现通知接口
	 * 			---提现成功时，富友调用此接口，通知我系统提现进度
	 * 
	 * */
	public function apiCashOutNoticeSuccess(){
		header("Content-type:text/xml");
		echo $this->fuyou->apiCashOutNoticeSuccess($_POST);
		exit;
	}

	/*
	 * 富友金账户  提现退票通知接口
	* 			---提现失败时，富友调用此接口，通知我系统提现进度
	*
	* */
	public function apiCashOutNoticeError(){
		header("Content-type:text/xml");
		echo $this->fuyou->apiCashOutNoticeError($_POST);
		exit;
	}
	

//-----------------------------充值通知   充值并且成功-------------------------------------------------------
	
	/*
	 * 充值通知
	 * 		--充值成功，富友调用此接口
	 * 
	 * */
	public function apiCashInNoticeSuccess(){
		header("Content-type:text/xml");
		echo $this->fuyou->apiCashInNoticeSuccess($_POST);
		exit;
	}
	
	private function send_sms($memberid,$order_sn,$type='cash_in'){
		if($type=='cash_in'){
			$m_info = M('member m,member_info i')->where("m.id='{$memberid}' and m.id=i.memberid")->find();
			$realname = $m_info['names']?$m_info['names']:$m_info['username'];
			import("Think.ORG.Util.Fuyou");
			$fuyou = new Fuyou();
			$balance = $fuyou->BalanceAction($m_info['memberid']);
			if(false==$balance){
				$available = 0;
			}else{
				$available = intval(intval($balance['ca_balance'])/100);
			}
			$cash_in = M('cash_in')->field("date_format(timeadd,'%Y年%m月%d日') as timeadd,amount")->where("insn='{$order_sn}'")->find();
			$content = "尊敬的{$realname}（先生/女生）您好：您于{$cash_in['timeadd']}成功在借吧平台充值{$cash_in['amount']}元，当前您的账户余额为{$available}元。如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
			sendsms($m_info['mobile'], $content,1);
		}elseif($type=='cash_out'){
			$m_info = M('member m,member_info i')->where("m.id='{$memberid}' and m.id=i.memberid")->find();
			$realname = $m_info['names']?$m_info['names']:$m_info['username'];
			$content = "尊敬的{$realname}（先生/女生）您好：您的提现申请已收到，我们将会在两个小时之内进行处理，请您耐心等待。如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
			sendsms($m_info['mobile'], $content,1);
		}
	}

}
?>