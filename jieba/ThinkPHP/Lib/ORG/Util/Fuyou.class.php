<?php 
/*
 * 富友金账户开发  接口
 * 
 * */
class Fuyou{
	/*
	 * 
	 * 正式环境下配置
	 * 
	 * */
	
	private $domain = "http://wx.jieba360.com";
	private $private_key_path = "";//私钥文件路径
	private $public_key_path = "";//公钥文件路径
	
	private $query_url = "https://jzh.fuiou.com/query.action";//明细查询   --37
	private $app_500002_url = "https://jzh.fuiou.com/app/500002.action";//app，商户APP个人用户免登录快捷充值--26
	private $appReg_url = "https://jzh.fuiou.com/app/appWebReg.action";//APP，个人用户自助开户注册（APP）---25
	private $app_500003_url = "https://jzh.fuiou.com/app/500003.action";//app，商户app免登录提现接口 --30
	private $queryUserInfs_url = "https://jzh.fuiou.com/queryUserInfs.action";//用户信息查询   ---40
	private $queryUserInfs_v2_url = "https://jzh.fuiou.com/queryUserInfs_v2.action";//用户信息查询(身份证号)   ---接口41
	private $BalanceAction_url = "https://jzh.fuiou.com/BalanceAction.action";//余额查询   --36
	private $transferBmu_url = "https://jzh.fuiou.com/transferBmu.action";//转账 --5
	private $transferBu_url = "https://jzh.fuiou.com/transferBu.action";//划拨 --转账6
	
	private $app_bind_bank_num_url = "http://wx.jieba360.com/Fuyou/appDoBindBankCard";//app，绑定银行卡成功后的回调地址
	private $app_page_notify_url = "http://wx.jieba360.com/Fuyou/app_bfreceive.html";//app充值,充值成功后的通知地址
	private $app_carry_success_url = "http://wx.jieba360.com/Fuyou/carryBack";//app，提现后的回调地址,PC|APP回调地址相同
	private $freeze_url = "https://jzh.fuiou.com/freeze.action";//冻结--7
	
	private $mchnt_cd = "0002900F0278484";//商户代码，富友分配给各合作商户的唯一识别码
	private $loginId = "ZXCF001c07";//商户登录用户名，富友分配给智富金融唯一登录用户名
	private $userLogin = "13641656749";//个人账户名，划拨专用
	private $ver = "0.44";//富友版本
	
	
	
	
	/*
	 * 
	 * 测试环境下配置
	 * */
	/*private $domain = "http://wx1.lingqianzaixian.com";
	private $private_key_path = "";//私钥文件路径
	private $public_key_path = "";//公钥文件路径
	
	private $query_url = "https://jzh-test.fuiou.com/jzh/query.action";//明细查询   --37
	private $app_500002_url = "https://jzh-test.fuiou.com/jzh/app/500002.action";//app，商户APP个人用户免登录快捷充值--26
	private $appReg_url = "https://jzh-test.fuiou.com/jzh/app/appWebReg.action";//APP，个人用户自助开户注册（APP）---25
	private $app_500003_url = "https://jzh-test.fuiou.com/jzh/app/500003.action";//app，商户app免登录提现接口 --30
	private $queryUserInfs_url = "https://jzh-test.fuiou.com/jzh/queryUserInfs.action";//用户信息查询   ---40
	private $queryUserInfs_v2_url = "https://jzh-test.fuiou.com/jzh/queryUserInfs_v2.action";//用户信息查询(身份证号)   ---接口41
	private $BalanceAction_url = "https://jzh-test.fuiou.com/jzh/BalanceAction.action";//余额查询   --36
	private $transferBmu_url = "https://jzh-test.fuiou.com/jzh/transferBmu.action";//转账 --5
	private $freeze_url = "https://jzh-test.fuiou.com/jzh/freeze.action";//冻结--7
	private $transferBu_url = "https://jzh-test.fuiou.com/jzh/transferBu.action";//划拨 --转账6
	
	private $app_bind_bank_num_url = "http://wx1.lingqianzaixian.com/Fuyou/appDoBindBankCard";//app，绑定银行卡成功后的回调地址
	private $app_page_notify_url = "http://wx1.lingqianzaixian.com/Fuyou/app_bfreceive.html";//app充值,充值成功后的通知地址
	private $app_carry_success_url = "http://wx1.lingqianzaixian.com/Fuyou/carryBack";//app，提现后的回调地址,PC|APP回调地址相同
	
	private $mchnt_cd = "0002900F0096235";//商户代码，富友分配给各合作商户的唯一识别码
	private $loginId = "user110";//商户登录用户名，富友分配给智富金融唯一登录用户名
	private $userLogin = "13636576587";//个人账户名，划拨专用
	private $ver = "0.44";//富友版本
			
	private $error = '';	  //错误信息
	private $error_code = 0;  //错误代码
	public  $data = '';//数据
	*/
	
	
	
	function __construct(){
		$this->private_key_path = dirname(FRAME_PATH).'/cer/php_prkey.pem';
		$this->public_key_path = dirname(FRAME_PATH).'/cer/php_pbkey.pem';
	}
	
	
	
	/*2
	 * 用户开户注册  APP端
	* 		@parm    $memberid:用户id
	* 				 $client:设备
	*       		 $return_url:商户返回地址
	*		@return  bool|array
	* */

	public function  appWebReg($memberid,$return_url,$client = 4){
		$member_info = M("member_info")->table("member_info i,member m")->field("i.*,m.mobile,m.username")->where("m.id='{$memberid}' and m.id=i.memberid")->find();
		$return_url = $this->domain.$return_url;
		if(false==$member_info){
			$this->error = "会员信息异常";
			$this->error_code = "2001";
			return false;
		}
		//是否实名认证
		if(0==$member_info['nameStatus']){
			$this->error = "会员未实名认证";
			$this->error_code = "2002";
			return false;
		}
		
		//是否开户
		$FuyouUserInfo = $this->FuyouStatus($memberid);
		if(false!=$FuyouUserInfo){
			$this->error = "会员已开户";
			return false;
		}
		
		$data = array();
		$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
		$data['mchnt_txn_ssn'] = $member_info['memberid']."".time().$client;//流水号,必填
		$data['user_id_from'] =$member_info['memberid'];//用户在商户系统的标志(系统唯一标识)
		$data['mobile_no'] =$member_info['mobile'];//手机号码 
		$data['cust_nm'] =''.$member_info['names'];  //真实姓名
		$data['certif_id'] =''.$member_info['certiNumber'];  //身份证号
		$data['email'] =''.$member_info['email'];//邮箱
		$data['city_id'] ='';//开户行地区代码
		$data['parent_bank_id'] ='';//开户行行别(开户银行代号)
		$data['bank_nm'] ='';//开户行支行名称
		$data['capAcntNo'] ='';//银行卡卡号
		$data['back_notify_url'] =$return_url;//商户后台通知地址
		//$data['ver'] = $this->ver;//版本号
		//开始生成签名
		ksort($data);//参数名的每一个值从a到z的顺序排序
		$str = implode('|',$data);
		$data['page_notify_url'] =$return_url;//商户返回地址，必填，手机版无此参数
		M('fuyou_log')->add(array('memberid'=>$member_info['memberid'],'extend'=>serialize($data)));
		$sign=$this->rsaSign($str);
		$data['signature']=$sign;
		$data['form_url']=$this->appReg_url;//跳转富友url
		$this->data = $data;
		return $this->arr2form($data);
	}
	
	/*
	 * app开户成功后，响应xml状态码，通知富友账户
	 *   					---否则富友隔一定的时间回调6次
	 * */
	public function respXmlAppReg(){
		$result = array();
		$result['ap']['plain']['resp_code'] = '0000';//返回码,商户流水号不存在
		$result['ap']['plain']['mchnt_cd'] = $this->mchnt_cd;//商户代码
		$result['ap']['plain']['mchnt_txn_ssn'] = time().rand(1111,9999);//流水号
		$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
		$xml = $this->arr2xml($result);
		return $xml;
	}
	
	/*
	 * 余额查询
	 * 	@parm 	$memberid:memberid
	 * 			$date:交易日期 ，格式：20160202
	 * 			$sn:流水号
	 *  @return bool|array
	 *  			----用户开户返回余额数组，否则返回false
	 * */
	public function BalanceAction($memberid,$date = '',$sn = ''){
		return array(
			"balance"=>100*10000*100,//100万
			"ca_balance"=>100*10000*100,//100万
		);
		if(false ==($fuyou_info = $this->isOpenFuyou($memberid))){
			return false;
		}
		$data = array();
		$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
		$data['mchnt_txn_ssn'] = ''==$sn?'9'.time():$sn;//流水号,必填
		$data['mchnt_txn_dt'] = ''==$date?date('Ymd'):$date;//交易日期,必填
		$data['cust_no'] = $fuyou_info['login_id'];//待查询的登录帐户,必填
		
		$result=$this->postdata($data,$this->BalanceAction_url);
		$data=$this->checkresult($result);
		return $data['plain']['results']['result'];
	}
	
	/*
	 * 用户是否注册开通
	 * 			--开通并绑定富友金账户
	 * 	@parm     $memberid:待查询登录账户用户名
	 * 	@return   bool|array   
	 * 					----开通返回账户信息，否则返回false
	 * */
	public function isOpenFuyou($memberid){
		$member_info = M("member_info")->table("member_info i,member m")->field("i.*,m.mobile,m.username")->where("m.id='{$memberid}' and m.id=i.memberid")->find();
		if(false==$member_info){
			$this->error = "会员信息异常";
			$this->error_code = "1001";
			return false;
		}
		if(0==$member_info['nameStatus']){
			$this->error = "会员未实名认证";
			$this->error_code = "1005";
			return false;
		}
		
		if(empty($member_info['fuyou_login_id'])){
			$this->error = "会员未绑定金账户";
			$this->error_code = "1006";
			return false;
		}
		
		return $this->FuyouStatus($memberid);
	}
	
	/*
	 * 富友金账户状态(根据身份证号判断的)
	 * 	@param $memberid:用户id
	 *  @return bool
	 * 
	 * */
	public function FuyouStatus($memberid){
		$member_info = M("member_info")->table("member_info i,member m")->field("i.*,m.mobile,m.username")->where("m.id='{$memberid}' and m.id=i.memberid")->find();
		if(false==$member_info){
			$this->error = "会员信息异常";
			$this->error_code = "1001";
			return false;
		}
		//身份证号校验是否开户
		$openFuyou = $this->queryUserInfs_v2($member_info['certiNumber']);
		if($openFuyou['plain']['results']['result']['capAcntNo'] && $openFuyou['plain']['results']['result']['user_st']==1){
			return $openFuyou['plain']['results']['result'];
		}
		//手机号校验是否开户
		$openFuyou = $this->queryUserInfs($member_info['mobile']);
		if($openFuyou['plain']['results']['result']['capAcntNo'] && $openFuyou['plain']['results']['result']['user_st']==1){
			return $openFuyou['plain']['results']['result'];
		}
		
		$this->error = "未开户注册";
		$this->error_code = "1002";
		if($openFuyou['plain']['results']['result']['user_st']==2){
			$this->error = "您资金托管账户已注销";
			$this->error_code = "1003";
		}
		if($openFuyou['plain']['results']['result']['user_st']==3){
			$this->error = "您资金托管账户处于申请注销状态";
			$this->error_code = "1004";
		}
		return false;
	}
	
	/*
	 * 用户信息查询
	 * 	@parm $fuyou_login_id:待查询登录账户用户名   $note:备注
	 * 	@return bool|array 
	 * */
	public function queryUserInfs($fuyou_login_id,$note = '备注'){
		$data = array();
		$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
		$data['mchnt_txn_ssn'] = '9'.time();//流水号,必填
		$data['mchnt_txn_dt'] = date('Ymd');//交易日期,必填
		$data['user_ids'] = $fuyou_login_id;//待查询的登录帐户,必填
		$data['ver'] = $this->ver;
		$result=$this->postdata($data,$this->queryUserInfs_url);
		//验签
		$data=$this->checkresult($result);
		$bank_code = $data['plain']['results']['result']['parent_bank_id'];
		if($bank_code)
			$data['plain']['results']['result']['bank_name'] = M('fuyou_bank')->where("code='{$bank_code}'")->getField("code_msg");
		
		return $data;
		
	}
	
	/*
	 * 用户信息查询
	 * 			---根据用户身份证号查询是否开户
	* 	@parm $fuyou_login_id:待查询登录账户身份证号   $note:备注
	* 	@return bool|array
	* */
	public function queryUserInfs_v2($user_idNos,$note = '备注'){
		$data = array();
		$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
		$data['mchnt_txn_ssn'] = '10'.time();//流水号,必填
		$data['mchnt_txn_dt'] = date('Ymd');//交易日期,必填
		$data['user_idNos'] = $user_idNos;//待查询的登录帐户,必填
		$data['user_bankCards'] = '';//银行卡号
		$data['user_ids'] = '';//注册手机号 
		$data['ver'] = $this->ver;
		$result=$this->postdata($data,$this->queryUserInfs_v2_url);
		//验签
		$data=$this->checkresult($result);
		$bank_code = $data['plain']['results']['result']['parent_bank_id'];
		if($bank_code)
			$data['plain']['results']['result']['bank_name'] = M('fuyou_bank')->where("code='{$bank_code}'")->getField("code_msg");
		return $data;
	
	}
	/*
	 * APP快捷充值      商户APP个人用户免登录快捷充值
	 * @parm 	$memberid:会员id  
     *  		$amount:充值金额，以分为单位  
     *  		$return_url:返回地址
     *  		$client:充值设备，  1：web端  2：ios 3:Android  4:微信
     *  
     *  @return bool|array
	 * 
	 * */
	
    public function app_500002($memberid,$amount,$return_url,$client = '4'){
    	$amount = intval($amount);//金额取整数,以分为单位  
    	$return_url = $this->domain.$return_url;
    	//是否开户注册
    	if(false ==($fuyou_info = $this->isOpenFuyou($memberid))){
    		return false;
    	}
    	$member_info  = M('member_info')->where("memberid='{$memberid}' and memberid!=''")->find();
    	//开始创建订单
    	$v_oid = create_sn('cashin');
    	$data = array(
    			'insn' => $v_oid,
    			'memberid' => $memberid,
    			'amount' => ($amount/100),
    			'extend' => $fuyou_info['capAcntNo'].$fuyou_info['bank_nm'],
    			'status' => 1,//充值状态  0:未提交   1:提交充值    2:充值到账       3:充值失败
    			'client' => $client,//充值方式  1：web端  2：ios 3:Android  4:微信
    	);
    	$result = M('CashIn')->add($data);
    	if (!$result) {
    		$this->error = "创建订单失败";
    		return false;
    	}
    	 
    	$data = array();
    	$data['mchnt_cd'] = $this->mchnt_cd;//商户代码，必填
    	$data['mchnt_txn_ssn'] = $v_oid;//流水号，必填
    	$data['login_id'] = $fuyou_info['login_id'];//用户登录名，必填
    	$data['amt'] = $amount;//充值金额，以分为单位，   必填
    	$data['page_notify_url'] =$return_url;//商户返回地址，必填
    	$data['back_notify_url'] = '';//商户后台通知地址，选填
    	 
    	ksort($data);//参数名的每一个值从a到z的顺序排序
    	$str = implode('|',$data);
    	//生成签名
    	$sign=$this->rsaSign($str);
    	$data['signature']=$sign;
    	$data['form_url']=$this->app_500002_url;//跳转富友url
    	$this->data = $data;
    	return $this->arr2form($data);
    }
    /*
     * APP提现      商户APP个人用户免登录提现
    * @parm 	$memberid:会员id
    *  			$login_id:金账户个人用户登录名
    *  			$amount:充值金额，以分为单位
    *  			$client:充值设备，  1：web端  2：ios 3:Android  4:微信
    *
    *  @return bool|array
    *
    * */
    public function app_500003($memberid,$amount,$return_url,$client = 4){
    	$return_url = $this->domain.$return_url;
    	//是否开户注册
    	if(false ==($fuyou_info = $this->isOpenFuyou($memberid))){
    		return false;
    	}
    	//账户余额最少2元
    	if ($amount/100 < 2) {
    		$this->error = "提现金额(".($amount/100).")小于2元";
    		return false;
    	}
    	
    	//账户资金是否够扣
    	$availAmount = $this->BalanceAction($memberid);
    	if(false==$availAmount)return false;
    	if($availAmount['ca_balance']/100-$amount/100<0){
    		$this->error = "余额不足";
    		return false;
    	}
    	//手续费,富友提现手续费   3元/笔
    	$fee = 2;
    	$arr_client = array('2'=>'ios','3'=>'Android','4'=>'weixin');
    	$outsn = create_sn('cashout');
    	$outData = array(
    			'memberid' => $memberid,
    			'total' => $amount/100,
    			'amount' => ($amount/100-$fee),
    			'fee' => $fee,
    			'outsn' => $outsn,
    			'extend' => '',
    			'remark' => $arr_client[$client].',提现',
    	);
    	$F = M("CashOut")->add($outData);
    	if ($F == false) {
    		$this->error= "订单生成失败";
    		return false;
    	}
    	
    	$data = array();
    	$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
    	$data['mchnt_txn_ssn'] = $outsn;//流水号,必填
    	$data['login_id'] = $fuyou_info['login_id'];//金账户个人用户登录名,必填
    	$data['amt'] = intval($amount);//提现金额，以’分‘为单位,必填
    	$data['page_notify_url'] = $return_url;//商户返回地址,必填
    	$data['back_notify_url'] = '';//商户后台通知地址
    	ksort($data);//参数名的每一个值从a到z的顺序排序
    	$str = implode('|',$data);
    	$sign=$this->rsaSign($str);
    	$data['signature']=$sign;
    	$data['form_url']=$this->app_500003_url;//跳转富友url
    	$this->data = $data;
    	return $this->arr2form($data);
    }

    
 
 
    
    /*
     * 提现成功通知接口
     * 	@parm 	$post:数据
     * 	@return xml
     * */
    public function apiCashOutNoticeSuccess($post){
    	$data = array();
    	$data['mchnt_cd'] = $post['mchnt_cd'];//商户代码，必填
    	$data['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号，必填
    	$data['mchnt_txn_dt'] = $post['mchnt_txn_dt'];//交易日期，必填
    	$data['mobile_no'] = $post['mobile_no'];//手机号码，必填
    	$data['amt'] = $post['amt'];//提现金额  单位 分，必填
    	$data['remark'] = $post['remark'];//备注
    	ksort($data);
    	if($this->rsaVerify(implode('|',$data), $post['signature'])){
    		
    		$cash_out = M('cash_out')->where("outsn='{$post['mchnt_txn_ssn']}'")->find();
    		if(false==$cash_out){
    			M('fuyou_log')->add(array('extend'=>3));
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '5346';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    	
    		
    		//更新账单
    		$trans = false;
    		if (!D('Common')->inTrans()) {
    			D('Common')->startTrans();
    			$trans = true;
    		}
   			
    		try {
    			/*$F = M('MemberInfo')->where("memberid='{$cash_out['memberid']}' and  cashoutAmount-{$cash_out['total']}>=0")->setDec('cashoutAmount', $cash_out['total']);
    			$update_status= M('cash_out')->where("id='{$cash_out['id']}' and outsn='{$post['mchnt_txn_ssn']}'")->save(array('status'=>3));
    			if(!($F && $update_status)){
    				throw new Exception('提现状态修改失败！');
    			}*/
    			 $update_status= M('cash_out')->where("id='{$cash_out['id']}' and outsn='{$post['mchnt_txn_ssn']}'")->save(array('status'=>2));
    			if(!$update_status){
    				throw new Exception('提现状态修改失败！');
    			}
    			
    			if ($trans) {
    				D('Common')->commit();
    			}
    			//账单更新成功
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '0000';//返回码
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    			
    		}catch (Exception $ex){//账单更新失败
    			if ($trans) {
    				M('fuyou_log')->add(array('extend'=>serialize($post),'note'=>'提现，'.$ex->getMessage()));
    				D('Common')->rollback();
    			}
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '9901';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    			
    		}
    		
    	}else{//验签失败
    		M('fuyou_log')->add(array('extend'=>serialize($post),'note'=>'提现，验签失败'));
    		$result = array();
    		$result['ap']['plain']['resp_code'] = '5002';//返回码
    		$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    		$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    		$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    		$xml = $this->arr2xml($result);
    		return $xml;
    	}
    }
    
    /*
     * 商户转账给用户
     * 		---公司转账给个人，完成信用贷借款
     * 		@param  $memberid:用户id
     * 				$order_id:订单id
     * 
     * 		@return bool
     * */
    public function company2person($memberid,$order_id){
    	$config = array(
    		'week_percent'=>'0.6',//周利率  ，单位：%
    		'fee'=>'25',		//服务费，单位：元
    		'plat_fee'=>'3',//平台管理费利率，单位：%
    	);
    	//是否开户
    	if(false==$this->isOpenFuyou($memberid)){
    		return false;
    	}
    	
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p")->where("o.id='{$order_id}' and o.id=p.order_id")->find();
    	if(false==$order_info){
    		$this->error = "订单不存在";
    		return false;
    	}
    	
    	/*
    	 * 2017-02-06号开始  平台管理费利率调整到  4%
    	*
    	*
    	* */
    	if(strtotime($order_info['timeadd'])>=strtotime('2017-02-06')){
    		$config['plat_fee'] = 4;
    	}
    	
    	//订单状态
    	if($order_info['customer_status']==4){
    		$this->error = "您已打过款了";
    		return false;
    	}
    	
    	
    	if($order_info['customer_status']!=6){
    		$this->error = "对不起，此订单不是【已签约】状态";
    		return false;
    	}
    	

    	$trans = false;
    	try{
    		
    		if (!D('Common')->inTrans()) {
    			D('Common')->startTrans();
    			$trans = true;
    		}
    		$arr_process = array('customer_status'=>4,'store_status'=>1);
    		$process = M('order_process')->where("order_id='{$order_id}'")->save($arr_process);
    		if(false==$process){
    			throw new Exception('修改订单失败101');
    		}
            //修改订单状态
            $order_edite = array('status'=>2);
            $orders = M('order')->where("id='{$order_id}'")->save($order_edite);
            if(false==$orders){
                throw new Exception('修改订单失败103');
            }
    		//利息
    		$ratemoney = round($order_info['loanmoney']*$config['week_percent']*0.01,2);
    		//服务费
    		$fee = $config['fee'];
    		//平台管理费
    		$plat_fee = round($order_info['loanmoney']*$config['plat_fee']*0.01,2);
    		//实际打款给客户 = 金额-利息-服务费-平台管理费
    		$pay_money = round($order_info['loanmoney']-$ratemoney-$fee-$plat_fee,2);
    		//最迟还款日期
    		$back_time = date("Y-m-d H:i:s",strtotime("+7 days",strtotime(date("Y-m-d")." 23:59:59")));
    		//所在门店
    		//$department = M('order_upload u,member_info i')->where("u.cer_card=i.certiNumber and i.memberid='{$memberid}'")->getField("u.department");
    		$arr_credit = array('ratemoney'=>$ratemoney,'plat_fee'=>$plat_fee,'fee'=>$fee,'pay_money'=>$pay_money,'pay_time'=>date("Y-m-d H:i:s"),'back_time'=>$back_time);
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
    		
    		//开始转账
    		$amt = intval($this->my_round($pay_money));
            $where['memberid'] = $memberid;
            $member_info = M('member_info')->where($where)->find();
            if(empty($member_info)){
                throw new Exception("用户不存在({$this->error})");
            }
    		$is_trans = $this->transferBmu($memberid,$this->loginId,$member_info['fuyou_login_id'],$amt,"【信用贷】打款给客户({$amt})");
    		if(false==$is_trans){
    			throw new Exception("打款失败({$this->error})");
    		}
    		
    		//发送短信
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}在借吧平台申请的款项已审核成功，现已到账，请登录借吧账户中心提现。如需更多服务，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		sendsms($order_info['mobile'], $content,1);
    		
    		if ($trans) {
    			D('Common')->commit();
    		}
    		return true;
    	}catch(Exception $ex){
    		$this->error = $ex->getMessage();
    		if ($trans) {
    			D('Common')->rollback();
    		}
    		return false;
    	}
    	
    	
    }
    /*
     * 个人转账给公司
     * 		---个人转账给公司，完成信用贷还款
     * 
     * */
    public function person2company($memberid,$order_id){
    	$config = array(
    			'late_percent'=>'1',//逾期利率  ，单位：%
    	);
    	//是否开户
    	if(false==$this->isOpenFuyou($memberid)){
    		return false;
    	}
    	 
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p")->where("o.id='{$order_id}' and o.id=p.order_id")->find();
    	if(false==$order_info){
    		$this->error = "订单不存在";
    		return false;
    	}
    	 
    	//订单状态
    	if($order_info['customer_status']==5){
    		$this->error = "您已还过款了";
    		return false;
    	}
    	 
    	 
    	if($order_info['customer_status']!=4){
    		$this->error = "对不起，此订单不是【待还款】状态";
    		return false;
    	}
    	 
    	 
    	$trans = false;
    	try{
    	
    		if (!D('Common')->inTrans()) {
    			D('Common')->startTrans();
    			$trans = true;
    		}
    		$arr_process = array('customer_status'=>5);
    		$process = M('order_process')->where("order_id='{$order_id}'")->save($arr_process);
    		if(false==$process){
    			throw new Exception('修改订单失败201');
    		}
    		
    		$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    		$arr_credit = array();
    		$arr_credit['back_real_time'] = date("Y-m-d H:i:s");
    		$arr_credit['status'] = 1;
    		if(strtotime($credit_info['back_time'])>=time()){
    			$arr_credit['remark'] = "正常还款";
    			$arr_credit['late_fee'] = 0;
    			$arr_credit['late_dyas'] = 0;
    		}else{
    			//逾期
    			$arr_credit['remark'] = "逾期还款";
    			$arr_credit['late_days'] = ceil((time()-strtotime($credit_info['back_time']))/86400);
    			//2017-03-27 以后增加逾期利率
    			if($order_info['timeadd']>='2017-03-27 00:00:00' && $arr_credit['late_days']>5){
    				$arr_credit['late_fee'] = round($order_info['loanmoney']*$config['late_percent']*(5*0.01+($arr_credit['late_days']-5)*0.02),2);
    			}else{
    				$arr_credit['late_fee'] = round($order_info['loanmoney']*$config['late_percent']*$arr_credit['late_days']*0.01,2);
    			}
    		}
    		$arr_credit['back_money'] = $arr_credit['late_fee']+$order_info['loanmoney'];
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
    		$balance = $this->BalanceAction($memberid);
    		if($balance['ca_balance']<intval($arr_credit['back_money']*100)){
    			throw new Exception('余额不足');
    		}
			
    		
    		$amt = intval($arr_credit['back_money']*100);
    		$where['memberid'] = $memberid;
    		$member_info = M('member_info')->where($where)->find();
    		if(empty($member_info)){
    			throw new Exception("用户不存在");
    		}
    		
    		/*
    		 * 开始转账
    		 * 
    		 * */
    		//转账记录
    		$my_trans_array = array();$trans_result = "";
    		$my_trans_array[] = array('title'=>'【信用贷】还款本金','money'=>intval($this->my_round($credit_info['pay_money'])));//金额，单位：分
    		$my_trans_array[] = array('title'=>'【信用贷】手续费','money'=>intval($this->my_round($credit_info['fee'])));//金额，单位：分
    		$my_trans_array[] = array('title'=>'【信用贷】利息','money'=>intval($this->my_round($credit_info['ratemoney'])));//金额，单位：分
    		$my_trans_array[] = array('title'=>'【信用贷】服务费','money'=>intval($this->my_round($credit_info['plat_fee'])));//金额，单位：分
    		if($arr_credit['late_fee']>0)
    			$my_trans_array[] = array('title'=>'【信用贷】滞纳金','money'=>intval($this->my_round($arr_credit['late_fee'])));//金额，单位：分
    		//循环转账
    		foreach($my_trans_array as $key=>$val){
    			$is_trans = $this->transferBmu($memberid,$member_info['fuyou_login_id'],'',$val['money'],$val['title']);
    			if(false==$is_trans){
    				$trans_result.="【".$val['title']."】转账失败,";
    				//转账失败后再持续转3遍
    				for($i=1;$i<3;$i++){
    					$trans_fail_trans = $this->transferBmu($memberid,$member_info['fuyou_login_id'],'',$val['money'],$val['title']);
    					if(false==$is_trans){
    						$trans_result.="({$val['title']}失败后再次转账(第{$i}次)：转账失败),";
    					}else{
    						$trans_result.="({$val['title']}失败后再次转账(第{$i}次)：交易成功);";
    						break;
    					}
    					if($i>5)break;
    					sleep(1);
    				}
    				
    			}else{
    				$trans_result.="【".$val['title']."】交易成功;";
    			}
    			sleep(1);
    		}
    		
    		M('order_credit')->query("update order_credit set remark=concat(remark,',{$trans_result}') where id='{$credit_info['id']}'");
    		
    		
    		
    		if ($trans) {
    			D('Common')->commit();
    		}
    		
    		//转账
    		/*$is_trans = $this->transferBmu($memberid,$member_info['fuyou_login_id'],'',$amt,"【借吧】信用贷 客户还款({$amt})");
    		if(false==$is_trans){
    			throw new Exception("还款失败({$this->error})");
    		}*/
    		
    		
    		//冻结
    		/*$is_freeze = $this->freeze($memberid,$member_info['fuyou_login_id'],$amt);
    		if(false==$is_freeze){
    			throw new Exception("还款失败({$this->error})");
    		}*/
    		
    		//发送短信
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}借款已成功还款{$arr_credit['back_money']}元，您本次在借吧平台的欠款已结清，很高兴为您提供服务！如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		sendsms($order_info['mobile'], $content,1);
    		
    		
    		if ($trans) {
    			D('Common')->commit();
    		}
    		return true;
    	}catch(Exception $ex){
    		$this->error = $ex->getMessage();
    		if ($trans) {
    			D('Common')->rollback();
    		}
    		return false;
    	}
    	 
    }
    
    
    
    /*
     * 个人转账给公司
    * 		---个人转账给公司，完成信用贷还款
    *
    * */
    public function person2companyHandler($memberid,$order_id){
    	$config = array(
    			'late_percent'=>'1',//逾期利率  ，单位：%
    	);
    	//是否开户
    	if(false==$this->isOpenFuyou($memberid)){
    		return false;
    	}
    
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p")->where("o.id='{$order_id}' and o.id=p.order_id")->find();
    	if(false==$order_info){
    		$this->error = "订单不存在";
    		return false;
    	}
    
    	//订单状态
    	if($order_info['customer_status']==5){
    		$this->error = "您已还过款了";
    		return false;
    	}
    
    
    	if($order_info['customer_status']!=4){
    		$this->error = "对不起，此订单不是【待还款】状态";
    		return false;
    	}
    
    
    	$trans = false;
    	try{
    		 
    		if (!D('Common')->inTrans()) {
    			D('Common')->startTrans();
    			$trans = true;
    		}
    		$arr_process = array('customer_status'=>5);
    		$process = M('order_process')->where("order_id='{$order_id}'")->save($arr_process);
    		if(false==$process){
    			throw new Exception('修改订单失败201');
    		}
    
    		$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    		$arr_credit = array();
    		$arr_credit['back_real_time'] = date("Y-m-d H:i:s");
    		$arr_credit['status'] = 1;
    		if(strtotime($credit_info['back_time'])>=time()){
    			$arr_credit['remark'] = "正常还款";
    			$arr_credit['late_fee'] = 0;
    			$arr_credit['late_dyas'] = 0;
    		}else{
    			//逾期
    			$arr_credit['remark'] = "逾期还款";
    			$arr_credit['late_days'] = ceil((time()-strtotime($credit_info['back_time']))/86400);
    			$arr_credit['late_fee'] = round($order_info['loanmoney']*$config['late_percent']*$arr_credit['late_days']*0.01,2);
    		}
    		$arr_credit['back_money'] = $arr_credit['late_fee']+$order_info['loanmoney'];
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
    		$balance = $this->BalanceAction($memberid);
    		if($balance['ca_balance']<intval($arr_credit['back_money']*100)){
    			//throw new Exception('余额不足');
    		}
    			
    
    		$amt = intval($arr_credit['back_money']*100);
    		$where['memberid'] = $memberid;
    		$member_info = M('member_info')->where($where)->find();
    		if(empty($member_info)){
    			throw new Exception("用户不存在");
    		}
    
    		/*
    		 * 开始转账
    		*
    		* */
    		//转账记录
    		$my_trans_array = array();$trans_result = "";
    		$my_trans_array[] = array('title'=>'【信用贷】还款本金','money'=>intval($this->my_round($credit_info['pay_money'])));//金额，单位：分
    		//$my_trans_array[] = array('title'=>'【信用贷】手续费','money'=>intval($this->my_round($credit_info['fee'])));//金额，单位：分
    		//$my_trans_array[] = array('title'=>'【信用贷】利息','money'=>intval($this->my_round($credit_info['ratemoney'])));//金额，单位：分
    		//$my_trans_array[] = array('title'=>'【信用贷】服务费','money'=>intval($this->my_round($credit_info['plat_fee'])));//金额，单位：分
    		//if($arr_credit['late_fee']>0)
    			//$my_trans_array[] = array('title'=>'【信用贷】滞纳金','money'=>intval($this->my_round($arr_credit['late_fee'])));//金额，单位：分
    		//循环转账
    		foreach($my_trans_array as $key=>$val){
    			$is_trans = $this->transferBmu($memberid,$member_info['fuyou_login_id'],'',$val['money'],$val['title']);
    			if(false==$is_trans){
    				$trans_result.="【".$val['title']."】转账失败,";
    				//转账失败后再持续转3遍
    				for($i=1;$i<3;$i++){
    					$trans_fail_trans = $this->transferBmu($memberid,$member_info['fuyou_login_id'],'',$val['money'],$val['title']);
    					if(false==$is_trans){
    						$trans_result.="({$val['title']}失败后再次转账(第{$i}次)：转账失败),";
    					}else{
    						$trans_result.="({$val['title']}失败后再次转账(第{$i}次)：交易成功);";
    						break;
    					}
    					if($i>5)break;
    					sleep(1);
    				}
    
    			}else{
    				$trans_result.="【".$val['title']."】交易成功;";
    			}
    			sleep(1);
    		}
    
    		M('order_credit')->query("update order_credit set remark=concat(remark,',{$trans_result}') where id='{$credit_info['id']}'");
    
    
    
    		if ($trans) {
    			D('Common')->commit();
    		}
    
    
    		//发送短信
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}借款已成功还款{$arr_credit['back_money']}元，您本次在借吧平台的欠款已结清，很高兴为您提供服务！如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		sendsms($order_info['mobile'], $content,1);
    
    
    		if ($trans) {
    			D('Common')->commit();
    		}
    		return true;
    	}catch(Exception $ex){
    		$this->error = $ex->getMessage();
    		if ($trans) {
    			D('Common')->rollback();
    		}
    		return false;
    	}
    
    }
    
    
    /*
     * 冻结个人账户资金
     * 	@parm  $memberid:用户id  $cust_no:冻结目标登陆账户      $amt:冻结金额     $rem:备注
     * 	@return bool|array
     * 
     * */
    private function freeze($memberid,$cust_no,$amt,$rem = '【信用贷】还款冻结',$type='weixin'){
    	$data = array();
    	$detailsn = '9'.time().rand(1000,999);
    	$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
    	$data['mchnt_txn_ssn'] = $detailsn;//流水号,必填
    	$data['cust_no'] = $cust_no;//冻结目标登陆账户,必填
    	$data['amt'] = intval($amt);//冻结金额,必填
    	$data['rem'] = $rem;//备注
    	$result=$this->postdata($data,$this->freeze_url);
    	$xml_to_arr = json_decode(json_encode(simplexml_load_string($result)),true);
    	$msg = $this->getCode($xml_to_arr['plain']['resp_code']);
    	$log_data = array(
    			'memberid'=>$memberid,
    			'relationsn'=>$detailsn,
    			'type'=>$type,
    			'codeid'=>2,
    			'extend'=>serialize($xml_to_arr['plain']),
    			'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
    			'note'=>$rem."--冻结({$cust_no}冻结".($amt/100)."元)",
    	);
    	$this->log($log_data);
    	//验签
    	$data=$this->checkresult($result);
    	return $data;
    }
    
    
    /*
     * 转账   web|APP通用版转账(商户与个人之间)
    *  @parm   $memberid:会员id   $out_cust_no:付款登录账户     $in_cust_no:收款登录账户   $amt:转账金额(单位：分)  $rem:备注  $detailsn:流水号
    *  @return bool|array
    * */
    public function transferBmu($memberid,$out_cust_no,$in_cust_no,$amt,$rem = '',$detailsn = '',$codeid='5',$type = 'weixin'){
    	$memberinfo = M('member_info')->where("memberid='{$memberid}'")->find();
    	$detailsn = ''==$detailsn?time():$detailsn;//流水号（money_detail:detailsn）
    	$data = array();
    	$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
    	$data['mchnt_txn_ssn'] = $detailsn;//流水号,必填
    	$data['out_cust_no'] = $out_cust_no==''?$this->loginId:$out_cust_no;//付款登录账户,必填
    	$data['in_cust_no'] = $in_cust_no==''?$this->loginId:$in_cust_no;//收款登录账户,必填
    	$data['amt'] = intval($amt);//转账金额,必填
    	$data['rem'] = $rem;//备注
    	$data['contract_no'] = '';//预授权合同号
    	$result=$this->postdata($data,$this->transferBmu_url);
    	$xml_to_arr = json_decode(json_encode(simplexml_load_string($result)),true);
    	$msg = $this->getCode($xml_to_arr['plain']['resp_code']);
    	$log_data = array(
    			'memberid'=>$memberid,
    			'relationsn'=>$detailsn,
    			'type'=>$type,
    			'codeid'=>$codeid,
    			'extend'=>serialize($xml_to_arr['plain']),
    			'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
    			'note'=>$rem."--转账({$out_cust_no}转账".($amt/100)."元给{$in_cust_no})",
    	);
    	$this->log($log_data);
    	//验签
    	$data=$this->checkresult($result);
    	return $data;
    }
    
    /*
     * 划拨   web|APP通用版划拨(商户与个人之间)
    *  @parm   $memberid:会员id   $out_cust_no:付款登录账户     $in_cust_no:收款登录账户   $amt:转账金额(单位：分)  $rem:备注  $detailsn:流水号
    *  @return bool|array
    * */
    public function transferBu($memberid,$out_cust_no,$in_cust_no,$amt,$rem = '',$detailsn = '',$codeid='5',$type = 'weixin'){
    	$memberinfo = M('member_info')->where("memberid='{$memberid}'")->find();
    	$detailsn = ''==$detailsn?time():$detailsn;//流水号（money_detail:detailsn）
    	$data = array();
    	$data['mchnt_cd'] = $this->mchnt_cd;//商户代码,必填
    	$data['mchnt_txn_ssn'] = $detailsn;//流水号,必填
    	$data['out_cust_no'] = $out_cust_no==''?$this->userLogin:$out_cust_no;//付款登录账户,必填
    	$data['in_cust_no'] = $in_cust_no==''?$this->userLogin:$in_cust_no;//收款登录账户,必填
    	if($data['in_cust_no']==$data['out_cust_no']){$this->error = "对不起，付款收款账户不能相同";return false;}
    	$data['amt'] = intval($amt);//转账金额,必填
    	$data['rem'] = $rem;//备注
    	$data['contract_no'] = '';//预授权合同号
    	$result=$this->postdata($data,$this->transferBu_url);
    	$xml_to_arr = json_decode(json_encode(simplexml_load_string($result)),true);
    	$msg = $this->getCode($xml_to_arr['plain']['resp_code']);
    	$log_data = array(
    			'memberid'=>$memberid,
    			'relationsn'=>$detailsn,
    			'type'=>$type,
    			'codeid'=>$codeid,
    			'extend'=>serialize($xml_to_arr['plain']),
    			'result'=>"({$msg['code']}){$msg['code_msg']}|{$msg['msg_user']}",
    			'note'=>$rem."--划拨({$out_cust_no}划拨{$out_cust_no} ".($amt/100)."元给{$in_cust_no})",
    	);
    	$this->log($log_data);
    	//验签
    	$data=$this->checkresult($result);
    	return $data;
    }
    /*
     * 提现失败通知接口
    * 	@parm 	$post:数据
    * 	@return xml		
    * */
    public function apiCashOutNoticeError($post){
    	$data = array();
    	$data['mchnt_cd'] = $post['mchnt_cd'];//商户代码，必填
    	$data['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号，必填
    	$data['mchnt_txn_dt'] = $post['mchnt_txn_dt'];//交易日期，必填
    	$data['mobile_no'] = $post['mobile_no'];//手机号码，必填
    	$data['amt'] = $post['amt'];//提现金额  单位 分，必填
    	$data['remark'] = $post['remark'];//备注
    	ksort($data);
    	if(!$this->rsaVerify(implode('|',$data), $post['signature'])){
    		$cash_out = M('cash_out')->where("outsn='{$post['mchnt_txn_ssn']}'")->find();
    		if(false==$cash_out){
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '5346';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    		
    		//更新账单
    		$update_status = '';
    	 	$update_status= M('cash_out')->where("id='{$cash_out['id']}' and outsn='{$post['mchnt_txn_ssn']}'")->save(array('status'=>2));
    		if(!$update_status){
    			throw new Exception('提现状态修改失败！');
    		}
    		
    		if($update_status){
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '0000';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}else{//账单更新失败
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '9901';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    	}else{//验签失败
    		$result = array();
    		$result['ap']['plain']['resp_code'] = '5002';//返回码
    		$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    		$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    		$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    		$xml = $this->arr2xml($result);
    		return $xml;
    	}
    }
    
    /*
     * 充值成功通知接口
    * 	@parm 	$post:数据
    * 	@return xml
    * */
    public function apiCashInNoticeSuccess($post){
    	$data = array();
    	$data['mchnt_cd'] = $post['mchnt_cd'];//商户代码，必填
    	$data['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号，必填
    	$data['mchnt_txn_dt'] = $post['mchnt_txn_dt'];//交易日期，必填
    	$data['mobile_no'] = $post['mobile_no'];//手机号码，必填
    	$data['amt'] = $post['amt'];//提现金额  单位 分，必填
    	$data['remark'] = $post['remark'];//备注
    	ksort($data);
    	if(!$this->rsaVerify(implode('|',$data), $post['signature'])){
    		$cash_in = M('cash_in')->where("insn='{$post['mchnt_txn_ssn']}'")->find();
    		if(false==$cash_in){
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '5346';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    		//账单异常
    		if(sprintf("%1\$.2f",$cash_in['total'])-sprintf("%1\$.2f",($post['amt']/100))!=0 ||$cash_in['status']>1 ){
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '9901';//返回码,系统异常
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    		//更新账单
    		$update_status = '';
    		$update_status= M('cash_in')->where("id='{$cash_in['id']}' and insn='{$post['mchnt_txn_ssn']}'")->save(array('status'=>2));
    		if(!$update_status){
    			throw new Exception('提现状态修改失败！');
    		}
    	
    		if($update_status){
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '0000';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}else{//账单更新失败
    			$result = array();
    			$result['ap']['plain']['resp_code'] = '9901';//返回码,商户流水号不存在
    			$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    			$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    			$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    			$xml = $this->arr2xml($result);
    			return $xml;
    		}
    	}else{//验签失败
    		$result = array();
    		$result['ap']['plain']['resp_code'] = '5002';//返回码
    		$result['ap']['plain']['mchnt_cd'] = $post['mchnt_cd'];//商户代码
    		$result['ap']['plain']['mchnt_txn_ssn'] = $post['mchnt_txn_ssn'];//流水号
    		$result['ap']['signature'] = $this->rsaSign("{$result['ap']['plain']['mchnt_cd']}|{$result['ap']['plain']['mchnt_txn_ssn']}|{$result['ap']['plain']['resp_code']}");//签名
    		$xml = $this->arr2xml($result);
    		return $xml;
    	}
    }
    
    
//-------------------------------------------工具方法-------------------------------------------------------

    /*
     * 数组转换为form-input形式返回
     * 
     * */
    public function arr2form($data){
    	$html = "<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>";
    	$html="<form name='fuyoucheckout' id='fuyoucheckout' method='post'>";
    	foreach ($data as $key => $val)
    	{
    		$html.="<input type='hidden' name='{$key}' value='{$val}'/>";
    	}
    	$html.="</form>";
    	$html.= "<script type = 'text/javascript'>";
    	$html.="document.fuyoucheckout.action = '".$data['form_url']."';";
    	$html.="document.fuyoucheckout.submit();";
    	$html.="</script>";
    	return $html;
    }
    
    
	/*
	 * HTTP——curl-post    
	 *     @parm  	$data    array    待传输数据
	 *     @parm  	$url     string   请求接口地址
	 *     @return  $result  xml      响应结果
	 * */
    public function postdata($data,$url)
    {
        $str="";
        ksort($data);//参数名的每一个值从a到z的顺序排序
        $str = implode('|',$data);
        //生成签名
        $sign=$this->rsaSign($str);
        $data['signature']=$sign;
        //开始传输
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        return $result;
    }

	
	
	
	/*
	 * 签名加密
	 * 	@parm    $data  string      待加密字符串
	 *  @return  $sign  string      返回加密结果
	 * */
	public function rsaSign($data) {
		if(!file_exists($this->private_key_path)){
			$this->error = "私钥文件不存在";
			return false;
		}
	    $priKey = file_get_contents($this->private_key_path);
	    $res = openssl_pkey_get_private($priKey);
	    openssl_sign($data, $sign, $res);
	    openssl_free_key($res);
		//base64编码
	   	$sign = base64_encode($sign);
	    return $sign;  
	}
	
	
	
	/**
	 * RSA验签
	 * @param $data 待签名数据
	 * @param $sign 要校对的的签名结果
	 * return 验证结果  bool
	 */
	public function rsaVerify($data, $sign)  {
		if(!file_exists($this->public_key_path)){
			$this->error =  "富友公钥文件不存在！";
			return false;
		}
		$pubKey = file_get_contents($this->public_key_path);
	    $res = openssl_pkey_get_public($pubKey);
	    $result = (bool)openssl_verify($data, base64_decode($sign), $res);
	    openssl_free_key($res); 
	    return $result;
	}
	
    /*
     * 对接口返回结果进行验证
     *  @parm    $data xml 待验证数据
     *  @return  array | bool
     */
    public function checkresult($data)
    {
        $arr=json_decode(json_encode(simplexml_load_string($data)),true);
        $str="/\<plain\>(.*?)\<\/plain\>/";
        preg_match_all($str ,$data,$matches);
        if($this->rsaVerify($matches[0][0],$arr['signature'])){
        	if('0000'!=$arr['plain']['resp_code']){
        		$code_info = $this->getCode($arr['plain']['resp_code']);
        		$this->error = "({$code_info['code']}){$code_info['msg_user']}";
        		return false;
        	}
            return $arr;
        }else{
            $this->error = "验签失败";
            return false;
        }
    }
    
    /*
     * 对接口返回结果进行验证
    *  @parm    $data string 待验证数据   
    *  			$sign:string 签名  
    *  			$resp_code string 结果代码
    *  @return  array | bool
    */
    public function checkSignString($data,$sign,$resp_code)
    {
    	if($this->rsaVerify($data,$sign)){
    		if('0000'!=$resp_code){
    			$code_info = $this->getCode($resp_code);
    			$this->error = "({$code_info['code']}){$code_info['msg_user']}";
    			return false;
    		}
    		return true;
    	}else{
    		$this->error = "验签失败";
    		return false;
    	}
    }
    
    
    
    public function getError(){
		return $this->error;
	}
	
	/*
	 * 富友 错误代码对应的含义
	 * return array
	 * */
	public function getCode($code){
		$return = array('code'=>$code,'msg'=>'系统异常[智信]','msg_user'=>'系统异常[智信]');
		$code_info = M('fuyou_errorcode')->where("code='{$code}'")->find();
		if(false==$code_info){
			return $return;
		}
		$return['code'] = $code;
		$return['msg'] = $code_info['code_msg'];
		$return['msg_user'] = $code_info['msg_user'];
		return $return;
	}
	
	/*
	 * 富友操作日志
	 * @parm $data:待存入数据
	 * 				memberid:会员id
	 * 				relationsn:操作日志sn   type:设备类别（web|android|ios|weixin）
	 * 				codeid:日志分类（1：开户;2：注销;3:充值;4:提现;5:投资转账;6:还款转账）
	 * 				extend:扩展数据
	 * 				result:操作结果       note:备注
	 * 				timeadd:添加时间
	 * */
	public function log($data){
		$data['timeadd'] = date("Y-m-d H:i:s");
		$log_res = M('fuyou_log')->add($data);
		return ($log_res?true:false);
	}
	
	//数组转换为xml格式
	private function arr2xml($data, $root = true){
	  $str="";
	  if($root)$str .= "<?xml version='1.0' encoding='utf-8'?> ";
	  foreach($data as $key => $val){
	    if(is_array($val)){
	      $child = $this->arr2xml($val, false);
	      $str .= "<{$key}>{$child}</{$key}>";
	    }else{
	      $str.= "<{$key}>{$val}</{$key}>";
	    }
	  }
	  return $str;
	}
	
	private function my_round($num){
		$arr_num = explode('.',$num);
    	return intval($arr_num[0]*100+(('0.'.$arr_num[1])*100));
	}
	
}

?>