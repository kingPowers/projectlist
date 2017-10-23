<?php
/* 
 * 计划任务脚本处理Action
 */
class BashAction extends Action {
	/*
	 * 泰克泰勒短信平台用户接收状态
	 * 		--短信平台调用此链接，返回短信发送情况
	 * */
	public function smsTaiResponse(){
		if(!empty($_REQUEST["mobile"]) && !empty($_REQUEST["msgid"])){
			$smsInfo = M("sms_record")->where("mobile='{$_REQUEST["mobile"]}' and extend like '%{$_REQUEST["msgid"]}%'")->find();
			if(false!=$smsInfo){
				M("sms_record")->where("mobile='{$_REQUEST["mobile"]}' and extend like '%{$_REQUEST["msgid"]}%'")->save(["extend"=>$smsInfo['extend']."回执状态：".$_REQUEST["status"]]);
				echo(json_encode(['message'=>"短信发送状态获取成功",'request'=>json_encode($_REQUEST),'status'=>1]));
			}else{
				echo(json_encode(['message'=>"无此短信",'request'=>json_encode($_REQUEST),'status'=>0]));
			}
			//推送微信消息
			if($_REQUEST['status']!="1"){
				import("Think.ORG.Util.wxMessage");
				$wxmessage = new wxMessage();
				$message = "短信发送失败(code:{$_REQUEST["status"]})：{$_REQUEST['mobile']}，{$smsInfo['content']}";
				
				$result = $wxmessage->sendText($message,"oWP0WwbWC4CeOk-S28O7kTMG2OkI");//汤潮沛微信openid
				$wxmessage->saveWxMessageLog(['memberid'=>"142",'message' =>$message,'result'=>$result==true?"推送成功":$wxmessage->getError()]);
					
				$result = $wxmessage->sendText($message,"oWP0Wwc-LCkyx0YPn-dU3f2R_Cz8");//石向前微信openid
				$wxmessage->saveWxMessageLog(['memberid'=>"143",'message' =>$message,'result'=>$result==true?"推送成功":$wxmessage->getError()]);
					
			}
			exit;
		}else{
			exit(json_encode(['message'=>"request参数为空",'request'=>json_encode($_REQUEST),'status'=>0]));
		}
	}
	
	/*
	 * 金牌经纪人批处理
	*/
	public function agent(){
		import("Think.ORG.Util.GoldAgent");
		$gold = new GoldAgent();
	
		/*订单自动审核通过
		 *          ----一定时间后，审核中的订单自动转为甩单中
		*/
		$gold->autoPassed();
	
		/*订单自动失效
		 *          ---一定时间后，无人解锁订单，订单自动失效
		*/
		$gold->autoUnpassed();
	
		/*自动转账
		 *      ---一定时间以后，自动转账给用户
		*/
		$gold->autoTransToSeller();
	
	}
	
	
	public function hand(){
		//$my_trans_array[] = array('title'=>'【信用贷】还款本金','money'=>intval($this->my_round("2898.06")));//金额，单位：分
		//$my_trans_array[] = array('title'=>'【信用贷】手续费','money'=>intval($this->my_round($credit_info['fee'])));//金额，单位：分
		//$my_trans_array[] = array('title'=>'【信用贷】利息','money'=>intval($this->my_round($credit_info['ratemoney'])));//金额，单位：分
		//$my_trans_array[] = array('title'=>'【信用贷】服务费','money'=>intval($this->my_round($credit_info['plat_fee'])));//金额，单位：分
		//if($arr_credit['late_fee']>0)
		//$my_trans_array[] = array('title'=>'【信用贷】滞纳金','money'=>intval($this->my_round($arr_credit['late_fee'])));//金额，单位：分
		
        //import("Think.ORG.Util.Fuyou");
     	//$mg = new Fuyou;
     	//参数：memberid,fuyou_login_id   金额    说明
     	//http://wx.jieba360.com/bash/hand
     	//$result = $mg->transferBmu('2054','15050680100','','2500',"【信用贷】手续费");
     	//dump($result);
     	//dump($mg->getError());
	}
	
	public function getFuyouBankCard(){
		import("Think.ORG.Util.Fuyou");
		$fuyou = new Fuyou();
		$S = S("fuyou_membanknumber");
		if(empty($S) || $_REQUEST['clear'])S("fuyou_membanknumber","1",60*60);
		$memberInfo = M("member m,member_info i")->where("m.id=i.memberid and i.memberid not in(select memberid from fuyou_membanknumber) and i.nameStatus=1 and m.id not in({$S})")->find();
    	if(false!=($bankInfo = $fuyou->FuyouStatus($memberInfo['memberid']))){
    		$addData = array();
    		$addData["memberid"] = $memberInfo['memberid'];
    		$addData["mobile"] = $bankInfo['login_id'];
    		$addData["mobile_no"] = $bankInfo['mobile_no'];
    		$addData["names"] = $bankInfo['cust_nm'];
    		$addData["certiNumber"] = $bankInfo['certif_id'];
    		$addData["bank_card"] = $bankInfo['capAcntNo'];
    		$addData["bank_name"] = $bankInfo['bank_name'];
    		$addData["bank_province"] = $bankInfo['parent_bank_id'];
    		$addData["bank_city"] = $bankInfo['city_id'];
    		$addData["bank_branch"] = $bankInfo['bank_nm'];
    		$add_id = M("fuyou_membanknumber")->add($addData);
    		dump($add_id);
    	}elseif(!empty($memberInfo['memberid'])){
    		S("fuyou_membanknumber",S("fuyou_membanknumber").",{$memberInfo['memberid']}");
    	}
    	dump($memberInfo);dump($bankInfo);
    	sleep(1);
		echo "<script type='text/javascript'>location.reload();</script>";
	}

	
    /*
     * 还款完成 给予积分
     *   1.提前还款者，积分20分
     *   2.正常还款者，积分10分
     *   3.逾期还款者，逾期一天扣除2分，逾期10天以后，每逾期一天扣除10分
     *   4.成功借款者，积分5分
     * */
    public function addScore(){
    	$config = array(
    		'begin_time'=>'2016-10-19 00:00:00',//积分赠送的开始日期，以订单申请日期开始为准
    		
    		'pay_success'=>array( //借款【成功者】
    				'title'	=>'借款成功赠送',
    				'is_give'=>'1',//是否给予积分
    				'score'=>'5',//赠送的积分
    				),
    		'e_repayment_success'=>array( //提前还款【成功者】
    				'title'	=>'提前还款成功赠送',
    				'is_give'=>'1',//是否给予积分
    				'score'=>'20',//赠送积分，提前还款
    				),
    		'repayment_success'=>array( //正常还款【成功者】
    				'title'	=>'正常还款成功赠送',
    				'is_give'=>'1',//是否给予积分
    				'score'=>'10',//赠送的积分，正常还款
    				),
    		'l_repayment_success'=>array( //逾期还款【成功者】
    				'title'	=>'逾期(<day>)还款扣除',
    				'is_give'=>'1',//是否扣除积分
    				'score'=>array('0'=>'-2','1'=>'-10'),//扣除的积分 ，逾期1-9天，每天扣除2分；逾期10天以上，每天扣除10分
    				),
    		
    	);
    	
    	import("Think.ORG.Util.wxMessage");
    	$msg = new wxMessage;
    	
    	//借款成功者
    	if($config['pay_success']['is_give']){
    		/*
    		 * 1.车友贷
    		 * 2.成单
    		 * 3.未还款
    		 * 4.已打款
    		 * */
    		$date = date("Y-m-d");
    		$pay_success_where = "  and o.order_type=3 and o.status=2 and  c.status=0  and p.customer_status=4 ";
    		$pay_success_where.=" and  s.remark is null ";
    		
    		$list = M() ->table("order_process p,order_credit c,`order` o")
    					->join("sign s on s.memberid=o.memberid and s.relationid=o.id and s.type=5 ")
    					->field("o.id,s.remark,o.memberid")
    					->where("o.id=p.order_id and o.id=c.order_id and o.timeadd>='{$config['begin_time']}' {$pay_success_where}")
    					->select();
    		if(count($list)>0){
	    		foreach($list as $k=>$v){
	    			$sign_where = "s.memberid='{$v['memberid']}' s.relationid='{$v['id']}' and s.type=5 ";
	    			if(false==M('sign s')->where($sign_where)->find()){
	    				$add_sign = array(
	    						'memberid'=>$v['memberid'],
	    						'type'=>5,
	    						'score'=>$config['pay_success']['score'],
	    						'remark'=>$config['pay_success']['title'],
	    						'relationid'=>$v['id'],
	    						'timeadd'=>date("Y-m-d H:i:s"),
	    						'is_receive'=>1,
	    				);
	    				M('sign')->add($add_sign);
	    				//微信通知
	    				$msg->creditApplySuccess($v['memberid'],['add_score'=>abs($add_sign['score'])]);
	    			}
	    		}
    		}
    	}
    	
    	
    	
    	
    	//已经还款者（提前还款、正常还款者、逾期还款者）
    		/*
    		 * 1.车友贷
    		* 2.成单
    		* 3.已还款
    		* 4.已还款
    		* */
    		$date = date("Y-m-d");
    		$repayment_success_where = "  and o.order_type=3 and o.status=2 and  c.status=1  and p.customer_status=5";
    		$repayment_success_where.=" and  s.remark is null ";
    		//是否逾期
    		$is_late_sql = "  if(c.back_time>c.back_real_time,'1','0') as is_late,
    						  case 
    				          when c.back_time>c.back_real_time then ceil((unix_timestamp(c.back_time)-unix_timestamp(c.back_real_time))/86400) 
    						  when c.back_time<=c.back_real_time  then ceil((unix_timestamp(c.back_real_time)-unix_timestamp(c.back_time))/86400)
    						  end as late_days
    						";
    		
    		$list = M() ->table("order_process p,order_credit c,`order` o")
			    		->join("sign s on s.memberid=o.memberid and s.relationid=o.id and s.type in(6,7,8) ")
			    		->field("o.id,s.remark,o.memberid,c.back_time,c.back_real_time,{$is_late_sql}")
			    		->where("o.id=p.order_id and o.id=c.order_id and o.timeadd>='{$config['begin_time']}' {$repayment_success_where}")
			    		->select();
    		
    		if(count($list)>0){
    			foreach($list as $k=>$v){
    				$is_sign = M('sign s')->where("s.memberid='{$v['memberid']}' s.relationid='{$v['id']}' ")->find();
    				$is_e_repayment = $v['is_late']==0 && $config['e_repayment_success']['is_give'] && $v['late_days']>1;//提前还款
    				$is_repayment = $v['is_late']==0 && $config['repayment_success']['is_give'] ;//正常还款
    				$is_l_repayment = $v['is_late']==1 && $config['l_repayment_success']['is_give'];//逾期还款
    				if(false==$is_sign && ($is_e_repayment||$is_repayment||$is_l_repayment)){
    					$add_data = $this->l_repayment($v,$config);
    					$add_sign = array(
    							'memberid'=>$v['memberid'],
    							'type'=>$add_data['type'],
    							'score'=>$add_data['score'],
    							'remark'=>$add_data['title'],
    							'relationid'=>$add_data['relationid'],
    							'timeadd'=>date("Y-m-d H:i:s"),
    							'is_receive'=>1,
    					);
    					M('sign')->add($add_sign);
    					//微信通知
    					$msg->creditRepaymentSuccess($v['memberid'],['add_score'=>abs($add_sign['score']),'remark'=>$add_sign['remark']]);
    				}
    			}
    		}
    		
    		/*
    		 * 逾期未还款的用户
    		 * 1.成单
    		 * 2.未还款
    		 * 3.已逾期
    		 * */
    		if($config['l_repayment_success']['is_give']){
    			$date = date("Y-m-d");
    			$l_repayment_error_where = "  and o.order_type=3 and o.status=2 and  c.status=0  and p.customer_status=4  and  c.back_time<NOW() ";
    			$l_repayment_error_where.=" and  s.remark is null ";
    			
    			$is_late_sql = "  if(c.back_time>NOW(),'1','0') as is_late,
    						  case
    				          when c.back_time>NOW() then ceil((unix_timestamp(c.back_time)-unix_timestamp(NOW()))/86400)
    						  when c.back_time<=NOW()  then ceil((unix_timestamp(NOW())-unix_timestamp(c.back_time))/86400)
    						  end as late_days
    						";
    			
    			$list = M() ->table("order_process p,order_credit c,`order` o")
    			->join("sign s on s.memberid=o.memberid and s.relationid=o.id and s.type=8  and date_format(s.timeadd,'%Y-%m-%d')='{$date}'")
    			->field("o.id,s.remark,o.memberid,{$is_late_sql}")
    			->where("o.id=p.order_id and o.id=c.order_id and o.timeadd>='{$config['begin_time']}' {$l_repayment_error_where}")
    			->select();
    			//dump(M()->getLastSql());
    			if(count($list)>0){
    				foreach($list as $k=>$v){
    					$sign_where = "s.memberid='{$v['memberid']}' s.relationid='{$v['id']}' and s.type=8 and  date_format(s.timeadd,'%Y-%m-%d')='{$date}' ";
    					if(false==M('sign s')->where($sign_where)->find()){
    						
    						$add_sign = array(
    								'memberid'=>$v['memberid'],
    								'type'=>8,
    								'score'=>$v['late_days']>9?$config['l_repayment_success']['score'][1]:$config['l_repayment_success']['score'][0],
    								'remark'=>str_replace('<day>',$v['late_days'],$config['l_repayment_success']['title']),
    								'relationid'=>$v['id'],
    								'timeadd'=>date("Y-m-d H:i:s"),
    								'is_receive'=>1,
    						);
    						M('sign')->add($add_sign);
    						//微信通知
    						//$msg->delayMessage($v['memberid'],['sub_score'=>abs($add_sign['score'])]);
    					}
    				}
    			}
    		}
    	
    	echo  'Ok';
    	
    	
    }
    /*
     * 逾期还款计算公式
     * 
     * */
    private function l_repayment($order,$config){
    	$return = array();
    	if($order['back_time']>=$order['back_real_time']){//未逾期
    		$late_days = ceil((strtotime($order['back_time'])-strtotime($order['back_real_time']))/86400);
    		$return['relationid'] = $order['id'];
    		$return['type'] =$late_days<=1?7:6;
    		$return['title'] = $late_days<=1?$config['repayment_success']['title']:$config['e_repayment_success']['title'];
    		$return['score'] = $late_days<=1?$config['repayment_success']['score']:$config['e_repayment_success']['score'];
    		
    	}else{//已逾期
    		$late_days = ceil((strtotime($order['back_real_time'])-strtotime($order['back_time']))/86400);
    		$return['relationid'] = $order['id'];
    		$return['type'] = 8;
    		$return['title'] = str_replace('<day>',$late_days,$config['l_repayment_success']['title']);
    		$return['score'] = $late_days>9?$config['l_repayment_success']['score'][1]:$config['l_repayment_success']['score'][0];
    	}
    	return $return;
    }
    /*
    车险分期自动还款
     */
    public function instalmentAutoBack ()
    {
        import("Think.ORG.Util.InsuranceInstalment");
        $instalment = new InsuranceInstalment();
        $unpay_list = $instalment->instalmentOrderList(['status'=>0]);
        foreach ($unpay_list as $value) {
            if (date("Y-m-d",time()) == date("Y-m-d",strtotime($value['back_time']))) {//本日还款
                $log = $this->getLoggerObj();
                $back_info = $instalment->backOneInstalment($value['id']);
                if (false == $back_info)return false;
                import("Think.ORG.Util.Baofu");
                $baofu = new Baofu($value['memberid']);
                $back_info['back_sn'] = time().rand(0000,9999)."auto"."_".$value['id'];
                try {
                    if (!D('Common')->inTrans()) {
                        D("Common")->startTrans();
                        $trans = true;
                    }
                    $instalment_save['status'] = 1;
                    $instalment_save['back_money'] = $back_info['money'];
                    $instalment_save['real_back_time'] = date("Y-m-d H:i:s");
                    $instalment_save['late_days'] = 0;
                    $instalment_save['late_fee'] = 0;
                    if (false == M("insurance_instalment")->where("id={$value['id']}")->save($instalment_save)) {
                        throw new Exception("分期保存失败");     
                    }
                    if (false == M("instalment_bill")->add($back_info)) {
                        throw new Exception("还款账单保存失败"); 
                    }
                    if (false == $baofu->bfCollect($back_info['back_sn'],$back_info['money'],"【借吧-车险分期-自动还款-金额（{$back_info['money']}）】")) {
                        throw new Exception($baofu->getError().$value['memberid']); 
                    }
                    if ($trans) {
                       D("Common")->commit();
                    }
                    $log->info("--车险分期【{$value['id']}】自动还款成功--:".json_encode($back_info));
                } catch (Exception $ex) {
                    $log->info("--车险分期【{$value['id']}】自动还款失败--ERROR:".$ex->getMessage());
                }
            }
        }
        echo "OK";
    }
    //返回日志对象
    public function getLoggerObj($path = "instalment"){
        import('Think.ORG.Util.Logger');
        $log = Logger::getLogger($path, LOG_PATH);
        $log->info('-----------------');
        return $log;
    }
}
