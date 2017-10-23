<?php

class Baofu {
	private $domain = "http://wx.jieba360.com";
	
	
	/*
	 //正式环境下配置
	 private $domain = "http://wx.jieba360.com";
	 private $private_key_path = "";//私钥文件路径
	 private $public_key_path = "";//公钥文件路径
	 private $backTransRequest = "https://public.baofoo.com/cutpayment/api/backTransRequest";//认证类接口
	 private  $bfpayBF0040001 = "https://public.baofoo.com/baofoo-fopay/pay/BF0040001.do";//代付交易URL
	 private $bfcollectBF0040001 = "https://public.baofoo.com/cutpayment/api/backTransRequest";//代扣交易URL
         private $bfpayBF0040002 = "https://public.baofoo.com/baofoo-fopay/pay/BF0040002.do";//代付交易状态查询URL
	  */
	
	/*
	 * 公共配置
	 * 
	 * */
	private $mchnt_cd = "1160669";//商户号
	private $data_type = "json";//数据类型xml|json
	private $memberid = "";//借吧用户id
	private $error = "";
	private $_bfBalance = false;//宝付商户账户余额
        private $_bindBankCardStatus = false;//银行卡绑定状态
	
	/*
	 * 认证类配置
	 * 
	 * */
	private $authVersion = "4.0.0.0";//认证类版本号
	private $terminal_id = "34400";//终端号
	private $backTransRequest = "https://public.baofoo.com/cutpayment/api/backTransRequest";//认证类接口
	private $private_key_path = "";//私钥文件路径
	private $public_key_path = "";//公钥文件路径
	private $private_key_password = "608051";//私钥文件密码
	
	/*
	 * 代付类配置
	 * 
	 * */
	private $payVersion = "4.0.0";//代付类版本号
	private $pay_terminal_id = "34399";//终端号
	private $pay_private_key_path = "";//私钥文件路径
	private $pay_public_key_path = "";//公钥文件路径
	private $pay_private_key_password = "608051";//私钥文件密码
	private $bfpayBF0040001 = "https://public.baofoo.com/baofoo-fopay/pay/BF0040001.do";//代付交易URL
	private $bfpayBF0040002 = "https://public.baofoo.com/baofoo-fopay/pay/BF0040002.do";//代付交易状态查询URL
	/*
	 * 代扣类配置
	 * 
	 * */
	private $collectVersion = "4.0.0.0";//代扣类版本号
	private $collect_terminal_id = "34399";//终端号
	private $collect_private_key_path = "";//私钥文件路径
	private $collect_public_key_path = "";//公钥文件路径
	private $collect_private_key_password = "608051";//私钥文件密码
	private $bfcollectBF0040001 = "https://public.baofoo.com/cutpayment/api/backTransRequest";//代扣交易URL
	
	
	function __construct($memberid){
		$this->setSecretKeyPath();//证书路径
		if(empty($memberid)){$this->error = "memberid不能为空";return false;}
		$this->memberid = $memberid;
	}
	
	//获取证书绝对路径
	private function setSecretKeyPath(){
		$this->private_key_path = dirname(FRAME_PATH).'/cer/jieba_bf_pri.pfx';//认证类私钥
		$this->public_key_path = dirname(FRAME_PATH).'/cer/jieba_baofu_auth_pub400.cer';//认证类公钥
		$this->pay_public_key_path = dirname(FRAME_PATH).'/cer/jieba_baofu_pay_pub399.cer';//代付类公钥
		$this->pay_private_key_path = dirname(FRAME_PATH).'/cer/jieba_bf_pri.pfx';//代付类私钥
		$this->collect_private_key_path = dirname(FRAME_PATH).'/cer/jieba_bf_pri.pfx';//代扣类私钥
		$this->collect_public_key_path = dirname(FRAME_PATH).'/cer/jieba_baofu_pay_pub399.cer';//代扣类公钥
	}
	
	/*
	 * 预绑卡,可以换绑银行卡
	 * @param $cardData 数据
	 * 				acc_no:银行卡号，必须
	 * 				id_card:身份证号，必须
	 * 				mobile:预留电话，必须
	 * 				bank_name:开户行名称，必须
	 * 				names:持卡人姓名，必须
	 * 
	 * @return boolean
	 * */
	public function preBindCard($cardData){
		if(false==$this->checkCardInfo($cardData))return false;
		//待加密数据
		$encData = array();
		$encData['txn_sub_type'] = "11";//交易子类，必填
		$encData["biz_type"] = "0000";//接入类型，C
		$encData["terminal_id"] = $this->terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData["trans_serial_no"] = rand(111,999).time();//商户流水号，必填
		$encData["trans_id"] = rand(1111,9999).time();//商户订单号，必填
		$encData["acc_no"] = $cardData["acc_no"];//银行卡号，必填
		$encData["id_card_type"] = "01";//身份证类型，选填
		$encData["id_card"] = $cardData["id_card"];//身份证号，必填
		$encData["id_holder"] = $cardData["names"];//持卡人姓名，必填
		$encData["mobile"] = $cardData["mobile"];//银行卡绑定手机号，必填
		$encData["valid_date"] = $cardData["valid_date"];//卡的有效期，选填
		$encData["pay_code"] = $this->getBankCode(trim($cardData['bank_name']));//银行编码，必填
		$encData["trade_date"] = date("YmdHis"); //订单日期，必填
		
		$postArr = array();
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['txn_sub_type'] = "11";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		
		if(false==($responseArr = $this->postOfResponse($this->backTransRequest,$postArr,"认证-预绑卡")))return false;
		$saveArr = array(
						'trans_sn'=>$encData["trans_id"],
						'acc_no'=>$encData["acc_no"],
						'certiNumber'=>$encData["id_card"],
						'names'=>$encData["id_holder"],
						'mobile'=>$encData["mobile"],
						'bank_name'=>$cardData["bank_name"],
						'bank_code'=>$encData["pay_code"],
				);
		S("bindCard_{$this->memberid}",$saveArr,30*60);
		return true;
	}
	/*
	 * 预绑卡
	 * 		--确认绑卡
	* @param $sms_code:验证码
	*
	* @return boolean|array
	* */
	public function preBindCardSubmit($sms_code){
		if(empty($sms_code)){$this->error = "验证码不能为空";return false;}
		if(strlen($sms_code)!=6){$this->error = "请输入6位数字验证码";return false;}
		$preBindInfo = S("bindCard_{$this->memberid}");
		if(empty($preBindInfo)){$this->error = "验证码已失效，请重新获取";return false;}
		$encData = array();
		$encData['txn_sub_type'] = "12";//交易子类，必填
		$encData["biz_type"] = "0000";//接入类型，C
		$encData["terminal_id"] = $this->terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData["trans_serial_no"] = rand(111,999).time();//商户流水号，必填
		$encData["trans_id"] = $preBindInfo["trans_sn"];//商户订单号，必填
		$encData["sms_code"] = $sms_code;//短信验证码，必填
		$encData["trade_date"] = date("YmdHis"); //订单日期，必填
		
		$postArr = array();
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['txn_sub_type'] = "12";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		if(false==($responseArr = $this->postOfResponse($this->backTransRequest,$postArr,"认证-确认绑卡")))return false;
		$this->bankCardToJieba(array_merge(S("bindCard_{$this->memberid}"),['bind_id'=>$responseArr['bind_id']]));
		S("bindCard_{$this->memberid}",null);
		return $responseArr;
	}
	
	/*
	 * 用户绑定银行卡状态
	 * @return boolean|array
	 * */
	public function bindBankCardStatus(){
		$cardInfo = $this->getBankCardInfo();
		if(false!=$cardInfo && !empty($cardInfo['acc_no']) && false!=($bindInfo = $this->isBindCard($cardInfo['acc_no']))){
			return array_merge($cardInfo,$bindInfo);
		}
		$this->error = "未绑定银行卡";
		return false;
	}
	
	/*
	 *  银行卡是否绑定宝付
	*	@param acc_no:银行卡号
	*	@return boolean|array
	* */
	public function isBindCard($acc_no){
            if(false!==$this->_bindBankCardStatus){
                return $this->_bindBankCardStatus;
            }
		//待加密数据
		$encData = array();
		$encData['txn_sub_type'] = "03";//交易子类，必填
		$encData["biz_type"] = "0000";//接入类型，C
		$encData["terminal_id"] = $this->terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData["trans_serial_no"] = rand(111,999).time();//商户流水号，必填
		$encData["acc_no"] = $acc_no;//银行卡号，必填
		$encData["trade_date"] = date("YmdHis");//订单日期,必填
		
		$postArr = array();
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['txn_sub_type'] = "03";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		$this->_bindBankCardStatus = $this->postOfResponse($this->backTransRequest,$postArr,"认证-绑卡状态");
                return $this->_bindBankCardStatus;
	}
	
	/*
	 * 解除银行卡的绑定关系
	 * 
	 * @return boolean|array
	 * */
	public function unBindBankCard(){
		if(false==($bankInfo = $this->bindBankCardStatus()))return false;
		$encData = array();
		$encData['txn_sub_type'] = "02";//交易子类，必填
		$encData["biz_type"] = "0000";//接入类型，C
		$encData["terminal_id"] = $this->terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData["trans_serial_no"] = rand(111,999).time();//商户流水号，必填
		$encData["bind_id"] =$bankInfo["bind_id"];//绑定标识号，必填
		$encData["trade_date"] = date("YmdHis");//订单日期,必填
		
		$postArr = array();
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['txn_sub_type'] = "02";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		return $this->postOfResponse($this->backTransRequest,$postArr,"认证-解绑银行卡");
	}
	
	/*
	 * 查询宝付商户账户余额
	*
	* */
	public function  baofuBalance(){
		if(false!==$this->_bfBalance && round($this->_bfBalance,2)>0){
			return $this->_bfBalance;
		}
		$url = "https://public.baofoo.com/open-service/query/service.do";
		$params = [
			"member_id"=>$this->mchnt_cd,//商户号，必填
			"terminal_id"=>"34398",//终端号，必填(网银终端号)
			"return_type"=>"json",//返回报文类型，必填
			"trans_code"=>"BF0001",//交易码，必填
			"version"=>"4.0.0",//版本号，必填
			"account_type"=>"1",//账户类型，必填
			"key"=>"ugk7lkzcm9rzkgct",//key
		];
		$params['sign'] = strtoUpper(md5(http_build_query($params)));
		unset($params["key"]);
		$result = $this->curlpost($url,$params);
		$arrRes = json_decode($result,true);
		if(false==$arrRes){$this->error = "商户账户余额查询失败";return false;}
		if("0000"==$arrRes["trans_content"]["trans_head"]["return_code"]){
			$this->_bfBalance = round($arrRes["trans_content"]["trans_reqDatas"]["trans_reqData"]["balance"],2);
			return $this->_bfBalance;
		}else{
			$this->error = $arrRes["trans_content"]["trans_head"]["return_msg"];
			return false;
		}
			
	}
	
	/*
	 * 代付交易
         *              ---交易成功的订单号直接返回
	 * 		---资金从商户转到用户
         * 
	 * 	 @param $trans_no:订单号
	 * 	 @param $trans_money:订单金额,单位：元
	 * 	 @param $remark:备注摘要
	 *   @return boolean|array
	 * 	代付成功返回数组，否则 返回false 
	 * */
	public function bfPay($trans_no,$trans_money,$remark = ""){
		if(false==($bindInfo = $this->bindBankCardStatus()))return false;
		//交易成功的订单号直接返回TRUE
                if(false!==($searchResult = $this->bfPaySearch($trans_no))){
                    if(0===(int)$searchResult["state"]){//付款中订单，不可再次代付
                        $this->error = "订单付款中";
                        return false;
                    }elseif(1===(int)$searchResult["state"]){//代付成功订单，不可再次代付
                        return $searchResult;
                    }
                    //-1：代付失败、2：代付退款  允许再次代付
                }
		//商户账户余额判断
		if(false==($bfBalance = $this->baofuBalance()))return false;
		if($bfBalance<$trans_money){$this->error = "商户账户余额不足";return false;}
		
		$encData = array();
		$encData['trans_no'] = $trans_no;//商户订单号，必填
		$encData["trans_money"] = $trans_money;//转账金额，必填，单位：元
		$encData["to_acc_name"] = $bindInfo["names"];//收款人姓名，必填
		$encData['to_acc_no'] = $bindInfo["acc_no"];//收款人银行账号，必填
		$encData["to_bank_name"] = $bindInfo["bank_name"];//开户银行，必填
		$encData["trans_card_id"] = $bindInfo["certiNumber"];//身份证号，必填
		$encData["trans_mobile"] = $bindInfo["mobile"];//手机号，必填
		
		$encData['to_pro_name'] = "";//开户省，C,可空，必传参数
		$encData['to_city_name'] = "";//开户市，C,可空，必传参数
		$encData['to_acc_dept'] = "";//支行，C,可空，必传参数
		$encData["trans_summary"] = $remark;//备注摘要
		//数据格式
		$enc = array(
				"trans_content"=>array(
						"trans_reqDatas"=>array(
								"0"=>array(
										'trans_reqData' =>array(
												'0'=>$encData,
										),
								),
						),
				),
		);
		
		$postArr = array();
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['version'] = $this->payVersion;//版本号，必填
		$postArr['terminal_id'] = $this->pay_terminal_id;//终端号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($enc,$this->pay_private_key_path,$this->pay_private_key_password);//加密数据，必填
		return $this->payPostOfResponse($this->bfpayBF0040001,$postArr,"【{$remark}】代付-代付交易");
}
        /*
         * 宝付代付订单查询
         *          代付交易订单状态查询
         *          仅支持单笔订单查询
         * @param $trans_no:订单号
         * @return boolean|array
         * 订单查询成功返回数组，否则返回false
         */
        public function bfPaySearch($trans_no){
            $enc = [
                "trans_content"=>[
                    "trans_reqDatas"=>[
                        [
                            "trans_reqData"=>[
                                ["trans_batchid"=>"","trans_no"=>$trans_no],
                            ],
                        ],
                    ],
                ],
            ];
            $postArr = array();
            $postArr['member_id'] = $this->mchnt_cd;//商户号，必填
            $postArr['version'] = $this->payVersion;//版本号，必填
            $postArr['terminal_id'] = $this->pay_terminal_id;//终端号，必填
            $postArr['data_type'] = $this->data_type;//加密数据类型，必填
            $postArr['data_content'] = $this->rsaSign($enc,$this->pay_private_key_path,$this->pay_private_key_password);//加密数据，必填
            if(false!=($searchResult=$this->payPostOfResponse($this->bfpayBF0040002,$postArr,"代付-代付交易状态查询"))){
                $orderState = $searchResult["trans_content"]["trans_reqDatas"][0]["trans_reqData"];
                return $orderState;
            }else{
                return false;
            }  
	}
	
	/*
	 * 认证支付--预支付交易
	 * 	@param $trans_no:订单号
	 *  @param $trans_money:交易金额，单位：元
	 *  @param $date:订单交易日期，格式：YYYYmmddHHiiss
	 *  @param $remark:备注
	 *  
	 *  @return boolean
	 * */
	public function prePay($trans_no,$trans_money,$date = '',$remark = ""){
		if(false==($bindInfo = $this->bindBankCardStatus()))return false;
		$date = $date?date("YmdHis",strtotime($date)):date("YmdHis");
		$encData = array();
		$encData['txn_sub_type']="15";//交易子类，必填
		$encData['biz_type']="0000";//接入类型，必填
		$encData['terminal_id']=$this->terminal_id;//终端号，必填
		$encData['member_id']=$this->mchnt_cd;//商户号，必填
		$encData['trans_serial_no']=rand(1111,9999).time();//商户流水号，必填
		$encData['trans_id']=$trans_no;//商户订单号，必填
		$encData['bind_id']=$bindInfo['bind_id'];//绑定标识号，必填
		$encData['txn_amt']=intval(100*$trans_money);//交易金额，必填,单位：分
		$encData['trade_date']=$date;//订单日期，必填
		$encData["additional_info"] = $remark;//附加字段，选填
		$encData['risk_content']=['client_ip'=>$this->getClientIp()];//风险控制参数，必填(用户IP地址)
		$postArr = array();
		$postArr['txn_sub_type'] = "15";//交易子类，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		if(false==($responseArr = $this->postOfResponse($this->backTransRequest,$postArr,"【{$remark}】认证-预支付")))return false;
		S("prePay_{$this->memberid}",$responseArr,30*60);
		return true;
	}
	
	/*
	 * 认证支付--支付确认交易
	 * 
	 * 	@param $sms_code:验证码
	 * 	@param $remark:备注
	 * 	@return boolean|array
	 * */
	public function prePaySubmit($sms_code,$remark = ""){
		if(empty($sms_code)){$this->error = "验证码不能为空";return false;}
		$prePayInfo = S("prePay_{$this->memberid}");
		if(empty($prePayInfo)){$this->error = "验证码已失效，请重新获取";return false;}
		$encData = array();
		$encData['txn_sub_type'] = "16";//交易子类，必填
		$encData["biz_type"] = "0000";//接入类型，C
		$encData["terminal_id"] = $this->terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData["trans_serial_no"] = rand(1111,9999).time();//商户流水号，必填
		$encData["business_no"] = $prePayInfo["business_no"];//宝付流水号，必填
		$encData["sms_code"] = $sms_code;//短信验证码，必填
		$encData["trade_date"] = date("YmdHis"); //订单日期，必填
		$encData["additional_info"] = $remark;//附加字段，选填
		
		$postArr = array();
		$postArr['version'] = $this->authVersion;//版本号，必填
		$postArr['terminal_id'] = $this->terminal_id;//终端号，必填
		$postArr['txn_type'] = "0431";//交易类型，必填
		$postArr['txn_sub_type'] = "16";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
		if(false==($responseArr = $this->postOfResponse($this->backTransRequest,$postArr,"【{$remark}】认证-确认支付")))return false;
		S("prePay_{$this->memberid}",null);
		return $responseArr;
		
	}
	
        /*
         * 认证支付交易状态查询
         *  @param $trans_no：交易订单号
         *  @return boolean|array
         *      订单查询成功返回数组，否则返回FALSE
         */
         public function  prePaySearch($trans_no,$remark = '认证支付状态查询'){
            $orderLog = M("baofu_log")->where("memberid='{$this->memberid}' and data like '%{$trans_no}%' and remark like '%获取验证码%'")->find(); 
            if(false==$orderLog){
                $this->error = "订单号查询为空";
                return  false;
            }else{
                $bfResponse = json_decode($orderLog["data"],true);
                $trade_date = $bfResponse["response"]["trade_date"];//订单日期    
            }
            $encData = array();
            $encData['txn_sub_type'] = "31";//交易子类，必填
            $encData["biz_type"] = "0000";//接入类型，C
            $encData["terminal_id"] = $this->terminal_id;//终端号，必填
            $encData['member_id'] = $this->mchnt_cd;//商户号，必填
            $encData["trans_serial_no"] = rand(1111,9999).time();//商户流水号，必填
            $encData["orig_trans_id"] = $trans_no;//原始商户订单号，必填
            $encData["orig_trade_date"] = $trade_date;//订单日期，必填
            $encData["additional_info"] = $remark;//附加字段，选填

            $postArr = array();
            $postArr['version'] = $this->authVersion;//版本号，必填
            $postArr['terminal_id'] = $this->terminal_id;//终端号，必填
            $postArr['txn_type'] = "0431";//交易类型，必填
            $postArr['txn_sub_type'] = "31";//交易子类，必填
            $postArr['member_id'] = $this->mchnt_cd;//商户号，必填
            $postArr['data_type'] = $this->data_type;//加密数据类型，必填
            $postArr['data_content'] = $this->rsaSign($encData);//加密数据，必填
            if(false==($responseArr = $this->postOfResponse($this->backTransRequest,$postArr,"【{$remark}】认证-认证支付状态查询")))return false;
            return $responseArr;
         }
        
	/*
	 * 代扣
	 * 		---资金从用户银行卡到宝付商户账户 
	 * 	@param $trans_no:订单号
	 *  @param $trans_money:交易金额，单位：元
	 *  @return boolean|array
	 * */
	public function bfCollect($trans_no,$trans_money,$remark = ""){
		if(false==($bindInfo = $this->bindBankCardStatus()))return false;
		$encData = array();
		$encData['trans_id'] = $trans_no;//订单号，必填
		$encData['txn_sub_type'] = "13";//交易子类，必填
		$encData['biz_type'] = "0000";//接入类型，必填，0000表示储蓄卡支付
		$encData["terminal_id"] = $this->collect_terminal_id;//终端号，必填
		$encData['member_id'] = $this->mchnt_cd;//商户号，必填
		$encData['pay_code'] = $bindInfo["bank_code"];//银行编码，必填
		$encData['acc_no'] = $bindInfo["acc_no"];//卡号，必填
		$encData['id_card_type'] = "01";//身份证类型,必填
		$encData['id_card'] = $bindInfo["certiNumber"];//身份证号，必填
		$encData['id_holder'] =$bindInfo["names"];//持卡人姓名，必填
		$encData['mobile'] =$bindInfo["mobile"];//银行预留电话，必填
		$encData["txn_amt"] = intval($trans_money*100);//交易金额，单位：分
		$encData["trans_serial_no"] = rand(111,999).time();//商户流水号，必填
		$encData["trade_date"] = date("YmdHis"); //订单日期，必填
		$encData["additional_info"] = $remark;//备注，选填
		
		$postArr = array();
		$postArr['version'] = $this->collectVersion;//版本号，必填
		$postArr['terminal_id'] = $this->collect_terminal_id;//终端号，必填
		$postArr['txn_type'] = "0413";//交易类型，必填
		$postArr['txn_sub_type'] = "13";//交易子类，必填
		$postArr['member_id'] = $this->mchnt_cd;//商户号，必填
		$postArr['data_type'] = $this->data_type;//加密数据类型，必填
		$postArr['data_content'] = $this->rsaSign($encData,$this->collect_private_key_path,$this->collect_private_key_password);//加密数据，必填
		return $this->collectPostOfResponse($this->bfcollectBF0040001,$postArr,"【{$remark}】代扣-代扣交易");
	}
	
	/*
	 * 绑定银行卡之前的检测
	 * 	@param $data:待检测数据
	 *  @return boolean
	 * */
	private function checkCardInfo($data){
		if(empty($data['acc_no']) || false==$this->checkBankCard($data["acc_no"])){
			$this->error = "银行卡号不正确";
			return false;
		}
		if(empty($data['id_card']) || false==$this->checkCertiNumber($data["id_card"])){
			$this->error = "身份证号不正确";
			return false;
		}
		if(empty($data['mobile']) || false==$this->checkMobile($data["mobile"])){
			$this->error = "手机号不正确";
			return false;
		}
		if(empty($this->memberid)){$this->error = "用户ID不能为空";return false;}
		if(empty($data['names'])){$this->error = "持卡人姓名不能为空";return false;}
		if(empty($data['bank_name'])){$this->error = "开户银行名称不能为空";return false;}
		if(!in_array(trim($data['bank_name']),array_keys(array_flip($this->getBankNames())))){$this->error = "开户银行名称错误";return false;}
		return true;
	}
	/*
	 * 绑定银行卡到借吧
	 * 			--银行卡信息存入借吧数据库
	 * 
	 * @param $data:绑定银行卡数据
	 * @return boolen|array
	 * */
	private function bankCardToJieba($data){
		if(false==$this->memberid || empty($data))return false;
		$addData = array();
		$addData["memberid"] = $this->memberid;
		if(!empty($data["trans_sn"]))$addData["trans_sn"] = $data["trans_sn"];//订单号
		if(!empty($data["acc_no"]))$addData["acc_no"] = $data["acc_no"];//银行卡号
		if(!empty($data["certiNumber"]))$addData["certiNumber"] = $data["certiNumber"];//身份证号
		if(!empty($data["names"]))$addData["names"] = $data["names"];//姓名
		if(!empty($data["mobile"]))$addData["mobile"] = $data["mobile"];//手机号
		if(!empty($data["bank_name"]))$addData["bank_name"] = $data["bank_name"];//银行名称
		if(!empty($data["bank_code"]))$addData["bank_code"] = $data["bank_code"];//银行编码
		if(!empty($data["bind_id"]))$addData["bind_id"] = $data["bind_id"];//bind_id
		$addData['status'] = 1;//状态
		if(false!=($bindInfo = M("baofu_bindcard")->where("memberid='{$this->memberid}'")->find())){
			$this->log($this->memberid,$bindInfo,"重新绑卡-旧卡记录");
			$save_id = M("baofu_bindcard")->where("memberid='{$this->memberid}'")->save($addData);
		}else{
			$save_id = M("baofu_bindcard")->add($addData);
		}
		//实名认证
		M('member_info')->where("memberid='{$this->memberid}'")->save(["names"=>$addData["names"],"certiNumber"=>$addData["certiNumber"],"nameStatus"=>1]);
		if(false==$save_id){$this->error = "绑卡保存失败";return false;}
		return $addData;
	}
	
	
	
	public function getBankCardInfo(){
		return M("baofu_bindcard")->where("memberid='{$this->memberid}'")->find();
	}
	
	public function getMemberInfo(){
		return M()->table("member m,member_info mi")->field("m.*,mi.memberid,mi.avatar,mi.qrcode,mi.sex,mi.certificate,mi.certiNumber,mi.nameStatus,mi.names,mi.emailStatus,mi.nameStatus,mi.fuyou_login_id")->where("m.id=mi.memberid and m.id='{$this->memberid}'")->find();
	}
	
	public function getError(){
		$err = array(
				'绑定关系不存在'=>"未绑定银行卡1",
				'卡号和支付通道不匹配'=>"卡号和开户银行不匹配1",
				"报文交易要素格式错误密文域中参数acc_no校验失败"=>"银行卡号不正确",
				"报文交易要素格式错误密文域中参数id_holder格式校验失败"=>"真实姓名不正确",
		);
		return $err[$this->error]?$err[$this->error]:$this->error;
	}
	
	/*
	 * 检查手机号格式
	 * @param $mobile:手机号
	 * @return boolean
	 * */
	private function checkMobile($mobile){
		return preg_match("/^1[3|4|5|7|8|9][0-9]{9}$/", $mobile) ? true : false;
	}
	
	/*
	 * 检查银行卡号格式
	 * @param $cardNo:银行卡号
	 * @retutn boolean
	 * */
	private function checkBankCard($cardNo){
		$arr_no = str_split($cardNo);
		$last_n = $arr_no[count($arr_no)-1];
		krsort($arr_no); $i = 1;$total = 0;
		foreach ($arr_no as $n){
			$total += $i%2==0?($n*2>=10?(1+($n*2)%10):$n*2):$n;
			$i++;
		}
		$total -= $last_n;
		$total *= 9;
		if($last_n != ($total%10)){
			return false;
		}
		return true;
	}
	
	/*
	 * 检车身份证号格式
	 * @param $certiNumber:身份证号
	 * @return boolean
	 * */
	private function checkCertiNumber($certiNumber){
		$vCity = array(
				'11','12','13','14','15','21','22',
				'23','31','32','33','34','35','36',
				'37','41','42','43','44','45','46',
				'50','51','52','53','54','61','62',
				'63','64','65','71','81','82','91'
		);
		
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $certiNumber)) return false;
		
		if (!in_array(substr($certiNumber, 0, 2), $vCity)) return false;
		
		$certiNumber = preg_replace('/[xX]$/i', 'a', $certiNumber);
		$vLength = strlen($certiNumber);
		
		if ($vLength == 18)
		{
			$vBirthday = substr($certiNumber, 6, 4) . '-' . substr($certiNumber, 10, 2) . '-' . substr($certiNumber, 12, 2);
		} else {
			$vBirthday = '19' . substr($certiNumber, 6, 2) . '-' . substr($certiNumber, 8, 2) . '-' . substr($certiNumber, 10, 2);
		}
		
		if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		if ($vLength == 18)
		{
			$vSum = 0;
		
			for ($i = 17 ; $i >= 0 ; $i--)
			{
			$vSubStr = substr($certiNumber, 17 - $i, 1);
			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}
			if($vSum % 11 != 1) return false;
		}
		return true;
	}
	/*
	 * 根据银行名称返回银行编码
	 * @prasm $bankName:银行名称
	 * @return string
	 * */
	private function getBankCode($bankName){
		$bankNames = array_flip($this->getBankNames());
		return "".$bankNames[trim($bankName)];
	}
	//银行数组
	public function getBankNames(){
		return array(
			"ICBC"=>"中国工商银行",
			"ABC"=>"中国农业银行",
			"CCB"=>"中国建设银行",
			"BOC"=>"中国银行",
			"BCOM"=>"中国交通银行",
			"CIB"=>"兴业银行",
			"CITIC"=>"中信银行",
			"CEB"=>"中国光大银行",
			"PAB"=>"平安银行",
			"PSBC"=>"中国邮政储蓄银行",
			"SHB"=>"上海银行",
			"SPDB"=>"浦东发展银行",
			"CMBC"=>"中国民生银行",
			"CMB"=>"招商银行",
			"GDB"=>"广发银行",
			"HXB"=>"华夏银行",
		);
	}
	
	/*
	 * 操作日志存于数据表 
	 * @param $memberid:用户id
	 * @param $data:保存数据
	 * @param $remark:备注
	 * @return boolean
	 * */
	private function log($memberid,$data = array(),$remark = ""){
		$add_id = M('baofu_log')->add(['memberid'=>$memberid,'data'=>json_encode($data),"remark"=>$remark]);
		if(false==$add_id)return false;
		return true;
	}
	/*
	 * 代扣类-响应数据转换
	* 		@param $url:接口url
	* 		@param $data:请求参数
	* 		@return boolean|array
	* */
	private function collectPostOfResponse($url,$data,$remark = '代扣'){
		$result = $this->curlpost($url,$data);
		if(false==($resultArr = json_decode($result,true))){
			$resultArr = json_decode($this->rsaVerify($result,$this->collect_public_key_path),true);
		}
		$this->log($this->memberid,['apply'=>$data,'response'=>$resultArr],$remark."-{$resultArr['resp_msg']}");
		if($resultArr["resp_code"]=="0000")return $resultArr;
		$this->error = $resultArr["resp_msg"];
		return false;
	
	}
	/*
	 * 代付类-响应数据转换
	 * 		@param $url:接口url
	 * 		@param $data:请求参数
	 * 		@return boolean|array
	 * */
	private function payPostOfResponse($url,$data,$remark = '代付'){
		$result = $this->curlpost($url,$data);
		if(false==($resultArr = json_decode($result,true))){
			$resultArr = json_decode($this->rsaVerify($result,$this->pay_public_key_path),true);
		}
		$this->log($this->memberid,['apply'=>$data,'response'=>$resultArr],$remark."-{$resultArr["trans_content"]["trans_head"]["return_msg"]}");
		if($resultArr["trans_content"]["trans_head"]["return_code"]=="0000")return $resultArr;
		$this->error = $resultArr["trans_content"]["trans_head"]["return_msg"];
		return false;
		
	}
	
	/*
	 * 认证类--响应数据转换
	* 		@param $url:接口url
	* 		@param $data:请求参数
	* 		@return boolean|array
	* */
	private function postOfResponse($url,$data,$remark = '认证')
	{	
		if(false==($curlResult = $this->curlpost($url,$data)))return false;
		if(false==($rsaResult=$this->rsaVerify($curlResult)))return false;
		$resultArr = json_decode($rsaResult,true);//post-解密-json
		$this->log($this->memberid,['apply'=>$data,'response'=>$resultArr],$remark."-{$resultArr['resp_msg']}");
		if($resultArr['resp_code']=='0000')return $resultArr;
		$this->error = $resultArr['resp_msg'];
		return false;
	}
	
	//curl-post
	private function curlpost($url,$data){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置超时限制防止死循环
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
		$result = curl_exec($ch);
		curl_close($ch); // 关闭CURL会话
		return $result;
	}
	
	/*
	 * 生成加密后的数据签名
	* 	@parm    $data  array 
	*   @param   $secretKey:秘钥文件路径 （私钥）
	*   @param   $keyPassword:秘钥文件密码
	*  @return  $sign  string 返回加密结果
	* */
	private function rsaSign($data,$secretKey = '',$keyPassword = '') {
		$secretKey = $secretKey?$secretKey:$this->private_key_path;
		$keyPassword = $keyPassword?$keyPassword:$this->private_key_password;
		if(!file_exists($secretKey)){
			$this->error = "私钥文件不存在";
			return false;
		}
		$private_key = array();
		$pkcs12 = file_get_contents($secretKey);
		openssl_pkcs12_read($pkcs12, $private_key,$keyPassword);
		if(false==$private_key){
			$this->error = "私钥文件不可用";
			return false;
		}
		 $data_content = base64_encode(str_replace("\\/", "/",json_encode($data)));
         $encrypted = "";
         $totalLen = strlen($data_content);
         $encryptPos = 0;
         while ($encryptPos < $totalLen){
             openssl_private_encrypt(substr($data_content, $encryptPos, 32),$encryptData,$private_key["pkey"]);
             $encrypted .= bin2hex($encryptData);
             $encryptPos += 32;
        }
        return $encrypted;
	}
	
	/**
	 * RSA解密
	 * @param $encrypted 密文
	 * @param $secretKey 秘钥文件（公钥）
	 * @return 解密后的数据
	 * 
	 */
	private function rsaVerify($encrypted,$secretKey = '')  {
		$secretKey = $secretKey?$secretKey:$this->public_key_path;
		if(!file_exists($secretKey)){
			$this->error =  "宝付公钥文件不存在！";
			return false;
		}
		
		$keyFile = file_get_contents($secretKey);
		$public_key = openssl_get_publickey($keyFile);
		if(false==$public_key){
			$this->error = "公钥文件不可用";
			return false;
		}
		$decrypt = "";
        $totalLen = strlen($encrypted);
        $decryptPos = 0;
        while ($decryptPos < $totalLen) {
             openssl_public_decrypt($this->hex2bin(substr($encrypted, $decryptPos, 32 * 8)),$decryptData,$public_key);
             $decrypt .= $decryptData;
             $decryptPos += 32 * 8;
        }
        $decrypt=base64_decode($decrypt);
        return $decrypt;
	}
	
	private function hex2bin( $str ) {
		$sbin = "";
		$len = strlen( $str );
		for ( $i = 0; $i < $len; $i += 2 ) {
			$sbin .= pack( "H*", substr( $str, $i, 2 ) );
		}
		return $sbin;
	}
	
	
	
	
	/*
	 * 对接口返回结果进行验证
	*  @parm    $data xml 待验证数据
	*  @return  array | bool
	*/
	public function checkresult($data){
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
	//获取用户IP地址
	private function getClientIp(){
		return $_SERVER['REMOTE_ADDR']?$_SERVER['REMOTE_ADDR']:"120.55.191.75";
	}
	
	/*
	 * 数组转换为form-input形式返回
	*
	* */
	private function arr2form($data){
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
	
}