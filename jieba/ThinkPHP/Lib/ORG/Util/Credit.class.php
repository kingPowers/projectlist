<?php
/*
 * 车友贷
 * 
 * */
class Credit {
	
	private $memberid = "";//借吧用户id
	
	private $error = "";
	private $log;
        private static  $setting = [
            'loanmoney'=>array('min'=>'1000','max'=>'10000'),//信用贷的贷款范围
            'week_percent'=>'0.6',//周利率  ，单位：%
            'fee'=>'25',		//服务费，单位：元
            'plat_fee'=>'4',//平台管理费利率，单位：%
        ];
        
	function __construct($memberid){
		if(empty($memberid)){$this->error = "memberid不能为空";return false;}
		$this->memberid = $memberid;
	}
	
/*
     * 商户转账给用户
     * 		---公司转账给个人，完成信用贷借款
     * 		@param  $memberid:用户id
     * 				$order_id:订单id
     * 
     * 		@return bool
     * */
    public function company2person($order_id){
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p")->where("o.id='{$order_id}' and o.id=p.order_id")->find();
    	if(false==$order_info){
    		$this->error = "订单不存在";
    		return false;
    	}
        $config = self::getSetting(["changeFeeTime"=>strtotime($order_info["timeadd"])]);
        
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
    		if (!D('order_process')->inTrans()) {
    			D('order_process')->startTrans();
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
                $autoCardMoney = intval($this->activityAutoCard($order_info["loanmoney"]));
    		$ratemoney = round($order_info['loanmoney']*$config['week_percent']*0.01,2)-$autoCardMoney;
    		//服务费
    		$fee = $config['fee'];
    		//平台管理费
    		$plat_fee = round($order_info['loanmoney']*$config['plat_fee']*0.01,2);
    		//实际打款给客户 = 金额-利息-服务费-平台管理费
    		$pay_money = round($order_info['loanmoney']-$ratemoney-$fee-$plat_fee,2);
    		//最迟还款日期
    		$back_time = date("Y-m-d H:i:s",strtotime("+7 days",strtotime(date("Y-m-d")." 23:59:59")));
    		$arr_credit = array('ratemoney'=>$ratemoney,'plat_fee'=>$plat_fee,'fee'=>$fee,'pay_money'=>$pay_money,'pay_time'=>date("Y-m-d H:i:s"),'back_time'=>$back_time);
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
    		$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    		//开始转账
    		import("Think.ORG.Util.Baofu");
	    	$baofu = new Baofu($this->memberid);
	    	if(false==($baofu->bfPay($credit_info['order_sn'],$pay_money,"【借吧-车友贷-放款】"))){
	    		throw new Exception($baofu->getError().$this->memberid);
	    	}
    		//发送短信
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$member_info = M('member_info')->where("memberid='{$this->memberid}'")->find();
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}在借吧平台申请的款项已审核成功，现已到账。如需更多服务，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		sendsms($order_info['mobile'], $content,1);
    		if($autoCardMoney>0){
                    $content = "{$member_info['names']}先生/女士，您是借吧的高信誉客户，专享八月集卡活动的特别福利，根据活动规则，系统已经自动为您减免{$autoCardMoney}元利息，本次车友贷实际到账金额{$pay_money}元。";
                    sendsms($order_info['mobile'], $content,1);
                }
    		if ($trans) {
    			D('order_process')->commit();
    		}
    		return true;
    	}catch(Exception $ex){
    		$this->error = $ex->getMessage();
    		if ($trans) {
    			D('order_process')->rollback();
    		}
    		return false;
    	}
    	
    	
    }
	
	/*
     * 车友贷还款
     * 		---【宝付支付】个人转账给公司，完成车友贷还款
     * 
     * 	@param $order_id:订单id
     * 	@param $sms_code:手机验证码
     * 		
     * 	@return boolean
     * */
    public function person2company($order_id,$sms_code = '',$type = "prepay"){
        $log = $this->getLoggerObj('person2company');
        $log->info("参数：order_id:{$order_id};sms_code:{$sms_code};type:{$type}");
    	$config = array(
    			'late_percent'=>'1',//逾期利率  ，单位：%
    	);
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p,order_credit c")->where("o.id='{$order_id}' and o.id=p.order_id and o.id=c.order_id")->find();
    	if(false==$order_info){
                $log->info("订单不存在");
    		$this->error = "订单不存在";
    		return false;
    	}
    	//订单状态
    	if($order_info['customer_status']==5){
                $log->info("已还过款了");
    		$this->error = "您已还过款了";
    		return false;
    	}
    	if($order_info['customer_status']!=4){
                $log->info("对不起，此订单不是【待还款】状态");
    		$this->error = "对不起，此订单不是【待还款】状态";
    		return false;
    	}
        
      $saveRepayment =  M("order_credit")->where("order_id='{$order_id}'")->save(["repayment_sn"=>"sn{$order_id}_".time().rand(111,999)]);                   
      if(false==$saveRepayment){
          $log->info("订单号正在生成,请稍后再试！");
          $this->error = "订单号正在生成,请稍后再试！";
          return false;
      }
    	
    	$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    	$arr_credit = array();
    	$arr_credit['status'] = 1;
    	if(strtotime($credit_info['back_time'])>=time()){
    		$arr_credit['remark'] = "正常还款";
    		$arr_credit['late_fee'] = $arr_credit['late_dyas'] = 0;
    	}else{
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
    	
    	import("Think.ORG.Util.Baofu");
    	$baofu = new Baofu($this->memberid);
    	
    	
    	
    	$prePay = S("prePay_{$this->memberid}");
    	//认证预支付，发送验证码
    	if($type=="prePay"){
    		if(false==$baofu->prePay($credit_info['repayment_sn'],$arr_credit['back_money'],"","【借吧-车友贷-还款-获取验证码】")){
    			$this->error = $baofu->getError();
                        $log->info("认证支付：获取验证码失败:{$this->error}");
    			return false;
    		}
                $log->info("认证支付：获取验证码成功!");
    		return true;
    	}
    	
    	if(empty($sms_code)){
                $log->info("认证支付-确认支付：验证码为空!");
    		$this->error = "请输入验证码";
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
                $log->info("order_process修改成功");
    		$arr_credit['back_real_time'] = date("Y-m-d H:i:s",strtotime($prePay['trade_date']));
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
                $log->info("order_credit修改成功");
    		$amt = intval($arr_credit['back_money']*100);
    		//宝付-开始转账
    		if(false==($result = $baofu->prePaySubmit($sms_code,"【借吧-车友贷-还款】"))){
    			throw new Exception($baofu->getError());
    		}
                $log->info("认证支付-确认支付，付款成功");
    		M('order_credit')->query("update order_credit set remark=concat(remark,'{$result["trans_content"]["trans_head"]["return_msg"]}') where id='{$credit_info['id']}'");
    		if ($trans) {
    			D('Common')->commit();
    		}
    		//发送短信
    		$member_info = M('member_info')->where("memberid='{$this->memberid}'")->find();
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}借款已成功还款{$arr_credit['back_money']}元，您本次在借吧平台的欠款已结清，很高兴为您提供服务！如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		sendsms($order_info['mobile'], $content,1);
    		return true;
    	}catch(Exception $ex){
    		$this->error = $ex->getMessage();
                 $log->info("现金贷还款失败：{$this->error}");
    		if ($trans) {
    			D('Common')->rollback();
    		}
    		return false;
    	}
    	 
    }
    
    /*
     * manager后台还款车友贷
     * @param  string  $payMobile:还款人手机号(从该手机号对应的银行卡中扣钱)
     * @parm   string  $orderMobile:还款订单手机号（申请车友贷订单的手机号）
     * @return boolean
     */
    public function managerRepayment($payMobile,$orderMobile,$remark = "",$isCollect = ''){
        $config = array(
    			'late_percent'=>'1',//逾期利率  ，单位：%
    	);
        $remark = $remark?$remark:"【借吧-车友贷-客服干预还款】";
        $payMemberInfo = M("member")->where("mobile='{$payMobile}'")->find();
        if(false==$payMemberInfo){
            $this->error = "还款人手机号错误";
            return false;
        }
        $orderMemberInfo = M("member")->where("mobile='{$orderMobile}'")->find();
        if(false==$payMemberInfo){
            $this->error = "还款订单手机号错误";
            return false;
        }
        $orderBaofuInfo = M('order')
                    ->table("`order` o,order_process p,order_credit c")
                    ->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$orderMemberInfo['id']}' and o.order_type=3")
                    ->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
                    ->order("id desc")->find();
        if(false==$orderBaofuInfo){
            $this->error = "订单不存在";
            return false;
        }
      $order_id = $orderBaofuInfo["id"];
      $saveRepayment =  M("order_credit")->where("order_id='{$order_id}'")->save(["repayment_sn"=>"sn{$order_id}_".time().rand(111,999)]);                   
      if(false==$saveRepayment){
          $this->error = "订单号正在生成,请稍后再试！";
          return false;
      }
       
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p,order_credit c")->where("o.id='{$order_id}' and o.id=p.order_id and o.id=c.order_id")->find();
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
    	
    	$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    	$arr_credit = array();
    	$arr_credit['status'] = 1;
    	if(strtotime($credit_info['back_time'])>=time()){
    		$arr_credit['remark'] = "正常还款";
    		$arr_credit['late_fee'] = $arr_credit['late_dyas'] = 0;
    	}else{
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
    	
    	import("Think.ORG.Util.Baofu");
    	$baofu = new Baofu($payMemberInfo["id"]);
    	$trans = false;
    	try{
    		if (!D('Common')->inTrans()) {
    			D('Common')->startTrans();
    			$trans = true;
    		}
                $handOrder = [
                        "pay_mobile"=>$payMobile,
                        "repayment_mobile"=>$orderMobile,
                        "money"=>$arr_credit['back_money'],
                        "order_id"=>$order_id,
                        "remark"=>$remark,
                ];
                $handResult = M('baofu_hand_repayment')->add($handOrder);
    		if(false==$handResult){
    			throw new Exception('还款记录生成失败301');
    		}
                
    		$arr_process = array('customer_status'=>5);
    		$process = M('order_process')->where("order_id='{$order_id}'")->save($arr_process);
    		if(false==$process){
    			throw new Exception('修改订单失败201');
    		}

    		$arr_credit['back_real_time'] = date("Y-m-d H:i:s");
    		$is_credit = M('order_credit')->where("order_id='{$order_id}'")->save($arr_credit);
    		if(false==$is_credit){
    			throw new Exception('修改订单失败102');
    		}
    		$amt = intval($arr_credit['back_money']*100);
    		//宝付-开始转账
    		if(intval($isCollect)!=2 && false==($result = $baofu->bfCollect($credit_info['repayment_sn'],$arr_credit['back_money'],$remark))){
                    throw new Exception($baofu->getError());
    		}
    		M('order_credit')->query("update order_credit set remark=concat(remark,'-{$remark}-','{$result['resp_msg']}') where id='{$credit_info['id']}'");
    		if ($trans) {
    			D('Common')->commit();
    		}
    		//发送短信
    		$member_info = M('member_info')->where("memberid='{$orderMemberInfo["id"]}'")->find();
    		$timeadd = date("Y年m月d日",strtotime($order_info['timeadd']));
    		$content = "尊敬的{$member_info['names']}（先生/女生）您好：您于{$timeadd}借款已成功还款{$arr_credit['back_money']}元，您本次在借吧平台的欠款已结清，很高兴为您提供服务！如有任何疑问，请拨打借吧客服热线：400-663-9066进行咨询。【借吧】";
    		//sendsms($order_info['mobile'], $content,1);
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
     * 订单还款情况
     *      根据用户ID返回该用户车友贷待还款情况
     *  @param $memberid:用户ID
     *  @return boolean
     */
    public function repaymentInfo($memberid){
        $config = array(
            'late_percent'=>'1',//逾期利率  ，单位：%
    	);
        $repaymentInfo = [
            "order_id"=>"",
            "memberid"=>$memberid,
            "back_money"=>0,//应还金额
            "late_days"=>0,//逾期天数
            "late_fee"=>0, //滞纳金额
            "remark"=>"", //备注
        ];
        $orderInfo = M('order')
                    ->table("`order` o,order_process p,order_credit c")
                    ->where("o.id=p.order_id and o.id=c.order_id and o.memberid='{$memberid}' and o.order_type=3")
                    ->field("o.id,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') timeadd,o.status as order_status,p.customer_status,c.status as credit_status,c.back_time,c.order_sn")
                    ->order("id desc")->find();
        if(false==$orderInfo){
            $repaymentInfo["remark"] = "订单不存在";
            return $repaymentInfo;
        }
      $order_id = $repaymentInfo["order_id"] = $orderInfo["id"];
    	//订单
    	$order_info = M('order')->table("`order` o,order_process p,order_credit c")->where("o.id='{$order_id}' and o.id=p.order_id and o.id=c.order_id")->find();
    	if(false==$order_info){
    		 $repaymentInfo["remark"]= "订单不存在";
    		return $repaymentInfo;
    	}
    	//订单状态
    	if($order_info['customer_status']==5){
    		 $repaymentInfo["remark"] = "您已还过款了";
    		return $repaymentInfo;
    	}
    	if($order_info['customer_status']!=4){
            $repaymentInfo["remark"] = "对不起，此订单不是【待还款】状态";
            return $repaymentInfo;
    	}
    	
    	$credit_info = M('order_credit')->where("order_id='{$order_id}'")->find();
    	if(strtotime($credit_info['back_time'])>=time()){
            $repaymentInfo["remark"] = "正常还款";
            $repaymentInfo['late_fee'] = $repaymentInfo['late_days'] = 0;
    	}else{
            $repaymentInfo["remark"] = "逾期还款";
            $repaymentInfo['late_days'] = ceil((time()-strtotime($credit_info['back_time']))/86400);
            //2017-03-27 以后增加逾期利率
            if($order_info['timeadd']>='2017-03-27 00:00:00' && $repaymentInfo['late_days']>5){
                    $repaymentInfo['late_fee'] = round($order_info['loanmoney']*$config['late_percent']*(5*0.01+($repaymentInfo['late_days']-5)*0.02),2);
            }else{
                    $repaymentInfo['late_fee'] = round($order_info['loanmoney']*$config['late_percent']*$repaymentInfo['late_days']*0.01,2);
            }
    	}
    	$repaymentInfo['back_money'] = $repaymentInfo['late_fee']+$order_info['loanmoney'];
        return $repaymentInfo;
    }
    
    public function getError(){
    	return $this->error;
    }
    //返回日志对象
    public function getLoggerObj($path = "creditOrder"){
        if(false==$this->log){
            import('Think.ORG.Util.Logger');
            $this->log = Logger::getLogger($path, LOG_PATH);
            $this->log->info("--------");
        }
        return $this->log;
    }
    //获取设置
    public static function getSetting($params = []){
        $setting = self::$setting;
        
        /*
         * 2017-08-20号以后服务费提高到35元
         */
        if($params["changeFeeTime"]>strtotime('2117-10-01')){
            $setting["fee"] = 35;
        }
        //平台管理费
        if(isset($params["loanmoney"]) && round($params["loanmoney"],2)>0){
            $setting["plat_fee_money"] = round($params["loanmoney"]*0.01*$setting['plat_fee'],2);
        }
        return $setting;
    }
    //自动使用优惠卡
    public function activityAutoCard($money){
        if(empty($this->memberid)){
            $this->getLoggerObj("activityAutoCard")->info("用户ID为空");
            return false;
        }
        $this->getLoggerObj("activityAutoCard")->info("开始使用优惠卡：memberId:{$this->memberid}");
        $saveId = M("activity_autocard")->where(["memberid"=>$this->memberid,"status"=>0,"overtime"=>["lt",date("Y-m-d H:i:s")]])->save(["status"=>2]);
        $this->getLoggerObj("activityAutoCard")->info("【memberId:{$this->memberid}】更新过期优惠卡：{$saveId}");
        $where = [];
        if($money>=5000 && $money<10000){
            $where["card_money"] = 10;
        }elseif($money>=10000){
            $where["card_money"] = 20;
        }else{
            $this->getLoggerObj("activityAutoCard")->info("【memberId:{$this->memberid}】贷款金额不在范围内:{$money}");
            return false;
        }
        $where["status"] = 0;
        $where["memberid"] = $this->memberid;
        $where["timeadd"] = ["elt",date("Y-m-d H:i:s")];
        $where["overtime"] = ["gt",date("Y-m-d H:i:s")];
        if(false==($cardInfo = M("activity_autocard")->where($where)->find())){
             $this->getLoggerObj("activityAutoCard")->info("【memberId:{$this->memberid}】没有可用优惠卡".M()->getLastSql());
             return false;
        }
        if(false==M("activity_autocard")->where(["id"=>$cardInfo["id"]])->save(["status"=>1])){
            $this->getLoggerObj("activityAutoCard")->info("【memberId:{$this->memberid}】优惠卡更新失败");
            return false;
        }
        $this->getLoggerObj("activityAutoCard")->info("【memberId:{$this->memberid}】优惠卡成功使用");
        return $cardInfo["card_money"];
    }
}