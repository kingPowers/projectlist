<?php
/*
 * 2017-02-08
 * 金牌经济人管理
 * 
 * 
 * */
class GoldAgent{
	private $error = '';
	
	private $order_setting = [
				'current_num'=>'10', //每个用户当天限制添加订单数量
				'maxEditNum'=>1,//甩单状态的订单修改的最大次数
				'error_code'=>[//订单失效错误类别
						'1'=>["涉及隐私 ","/2015/agent_img/err_1.png"],
						'2'=>["广告内容","/2015/agent_img/err_2.png"],
						'3'=>["订单超时","/2015/agent_img/err_3.png"],
						'4'=>["其他原因",""],//【其他原因】，自动生成图章
						'5'=>["直接拒单",""],//【直接拒单】，订单直接失效，不可上架
						
					],
				'unlock'=>'2',//解锁订单需要的金额
				'passedHours'=>'2',//2个小时以后订单自动上架
				'unPassedHours'=>'72',//72小时以后，无人购买，订单自动下架
				'reportHours'=>'24',//24小时内可以举报已解锁的订单
                'transToSellerHours'=>'24',//24小时后自动打款给卖方
				
			];
	private $_static_ = '';//static域名
	
	function __construct(){
		$this->_static_ = defined(_STATIC_)?_STATIC_:"http://static.jieba360.com";
		foreach($this->order_setting['error_code'] as &$val)
			$val[1] = !empty($val[1])?$this->_static_.$val[1]:"";
	}
	

	/*
	 * 开通成为金牌经济人权限
	 * @params $data
	 * @return boolean
	 * */
	public function openGold($data){
		if(false==$this->isAllowOpenGold($data['memberid']))return false;
		if(false==$this->isPayGold($data['memberid'])){
			//未支付费用
			try{
				if (!D('Common')->inTrans()) {
					D('Common')->startTrans();
					$trans = true;
				}
				$agentData = [];
				$agentData['memberid'] = $data['memberid'];
				$agentData['is_pay'] = 1;
				$agentData['pay_time'] = date("Y-m-d H:i:s");
				$agentData['pay_money'] = round($this->getSetting('pay_money'),2);
				if(false==M('gold_agent')->add($agentData)){
					throw new Exception('添加订单失败，请稍后再试');
				}
				
				$transData = [];
				$transData['memberid'] = $data['memberid'];
				$transData['detailsn'] = rand(111,999).time();
				$transData['money'] = 0-$agentData['pay_money'];
				$transData['type'] = 0;// 0:认证金牌经纪人  1：解锁订单    2：打款给客户记录
				$transData['remark'] = "认证金牌经纪人";
				if(false==M('gold_transfer')->add($transData)){
					throw new Exception('添加账单失败，请稍后再试');
				}
				import("Think.ORG.Util.Fuyou");
				$fuyou = new Fuyou();
				$memberinfo = M('member_info')->where("memberid='{$data['memberid']}'")->find();
				$is_trans = $fuyou->transferBu($data['memberid'],$memberinfo['fuyou_login_id'],'',intval($agentData['pay_money']*100),"【借吧】-金牌经纪人-{$transData['remark']}",$transData['detailsn']);
				if($is_trans==false){
					throw new Exception($fuyou->getError());
				}
					
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
			
		}else{
			//已支付金额，未完成第二步认证
			if($data['province']==''){$this->error = "经纪人所在省份不能为空";return false;}
			if($data['city']==''){$this->error = "经纪人所在城市不能为空";return false;}
			if($data['company_name']==''){$this->error = "公司简称不能为空";return false;}
			if($data['company_full_name']==''){$this->error = "公司全称不能为空";return false;}
			$agentData = [];
			$agentData['province'] = $data['province'];
			$agentData['city'] = $data['city'];
			$agentData['company_name'] = $data['company_name'];
			$agentData['company_full_name'] = $data['company_full_name'];
			$agentData['nickname'] = $data['nickname'];
			if(false!=M('gold_agent')->where("memberid='{$data['memberid']}'")->save($agentData))return true;
			$this->error = "认证失败，请稍后再试！";
			return false;
		}
		
	}
	/*
	 * 是否允许开通金牌经济人权限
	 * @param $memberid:用户id 
	 * @return boolean
	 * */
	public function isAllowOpenGold($memberid){
		if(false!=$this->isOpenGold($memberid)){$this->error = "您已成为金牌经纪人，无需重复开通";return false;}
		//未支付金额，金账户余额核查
		if(false==$this->isPayGold($memberid)){
			import("Think.ORG.Util.Fuyou");
			$fuyou = new Fuyou();
			if(false==($balance_info = $fuyou->BalanceAction($memberid))){$this->error = $fuyou->getError();return false;}
			$paymoney = round($this->getSetting('pay_money'),2);
			if(intval($balance_info['ca_balance'])<intval($paymoney*100)){$this->error = "余额不足";return false;}
			
		}
		return true;
		
	}
	
	/*
	 * 用户开通经纪人权限是否付钱
	 * @params $memberid:用户ID
	 * @return boolean
	 * */
	public function isPayGold($memberid){
		return  
		(false == M('gold_transfer')->where("memberid='{$memberid}' and (order_id='' or order_id is null) and type=0")->find()?
		false:M('gold_agent')->where("memberid='{$memberid}' and is_pay=1")->find());
	}
	/*
	 * 用户是否开通金牌经济人权限
	 * @param $memberid:用户id 
	 * @return boolean|array
	 * */
	public function isOpenGold($memberid){
		if(false==($goldInfo=$this->isPayGold($memberid)) || (empty($goldInfo['company_name']) && empty($goldInfo['city'])))return false;
		return $goldInfo;
	}
	/*
	 * 订单列表
	 * @param $memberid:用户id
	 * @param $_where ：where查询条件
	 * @param $page:页数
	 * @param $number:每页条数
	 * @return array
	 * */
	public function orderList($memberid,$_where = [],$page = 1,$number = 10){
		if(intval($page)<1)$page = 1;
		if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
		//if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		if(!empty($_where['status']) && !in_array($_where['status'],[0,2,3])){$this->error = "订单状态[status]参数错误";return false;}
		if(empty($_where['status']))
			$_where['status'] = array('in',"2,3");
		$count = M('gold_order')->where($_where)->count();
		$list = array();
		if($count>0){
			$_status_name = "case when status=1 then '待审核' when status=2 then '甩单中' when status=3 then '甩单成功' when status=4 then '已删除' when status=5 then '订单失效' end as status_name";
			$list = M('gold_order')
						->where($_where)
						->field("*,{$_status_name}")
						->limit(($page-1)*$number.",".$number)
						->order("status asc,last_time desc,timeadd desc")
						->select();
			foreach($list as &$val){
				$val['timeadd'] = date("m月d日 H:i",strtotime($val['timeadd']));
				//浏览次数
				$val['scanCount'] = $this->getScanCount($val['id']);
				//解锁次数
				$val['unlockCount'] = $this->getUnlockCount($val['id']);
				//订单是否已被我解锁
				$val['isMyLockedOrder'] = $this->isUnlock($memberid,$val['id'])?1:0;
				//合并头像信息
				if(false!=($agentInfo = $this->getAgent($val['memberid']))){
					$val = array_merge($val,$agentInfo);
				}
				//隐藏部分数据
				$val = $this->hideData($val);
			}//foreach
		}//if-count
		
		return !empty($list)?$list:[];
	}
	
	/*
	 * 查看别人订单--订单详情
	 * @param 	$memberid:解锁订单的用户id    $order_id:订单id
	 * @return array
	 * */
	public function orderDetail($memberid,$order_id){
		$list = $this->editOrder($order_id);
		unset($list['is_delete'],$list['is_edit'],$list['is_again'],$list['is_finish']);
		//订单是否已被我解锁
		$list['isMyLockedOrder'] = $this->isUnlock($memberid,$order_id)?1:0;
		//添加浏览次数
		$this->addScan($memberid,$order_id);
		return $this->hideData($list);
	}
	/*
	 * 我的订单列表
	 * @param $memberid:会员id
	 * @param $page:分页
	 * @param $number:每页条数
	 * */
	public function myOrderList($memberid,$page = 1,$number = 10){
		if(intval($page)<1)$page = 1;
		if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
		if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		$_where = [];
		$_where['memberid'] = $memberid;
		$_where['status'] = array('in',"1,2,3,5");
		$count = M('gold_order')->where($_where)->count();
		$list = array();
		if($count>0){
			$_status_name = "case when status=1 then '待审核' when status=2 then '甩单中' when status=3 then '甩单成功' when status=4 then '已删除' when status=5 then '订单失效' end as status_name";
			$list = M('gold_order')->where($_where)
									->field("*,{$_status_name}")
									->limit(($page-1)*$number.",".$number)
									->order("last_time desc,timeadd desc")
									->select();
			foreach($list as $key=>$val){
				$list[$key] = $this->TreatMyOrderData($val);
			}//foreach
		}//if-count
		
		return !empty($list)?$list:[];
	}
	/*
	 * 金牌经纪人添加订单
	 * @param $data  订单数据
	 * @return boolean|int   
	 * 添加订单成功时，返回订单id
	 * */
	public function addOrder($data){
		//检查会员合法性、订单合法性
		if(false===$this->isAllowAddOrder($data['memberid']))return false;
		//检查数据合法性
		if(false===$this->checkData($data))return false;
		//数据处理
		$data['car_frame_number'] = strtoupper($data['car_frame_number']);
		$data['status'] = 1;
		$data['last_time'] = date("Y-m-d H:i:s");
		$data['contract_num'] = rand(111,999).time();//合同编号
		if(false==($add_id = M('gold_order')->add($data))){
			$this->error = "添加订单失败，请稍后再试";
			return false;
		}
		return $add_id;
    	
	}
	/*
	 * 金牌经济人-修改订单信息
	 * @param $order_id 订单id
	 * @return array
	 * */
	public function editOrder($order_id){
		if($order_id=='' || false==($editInfo = $this->isGoldOrder($order_id))){
			$this->error = "订单id错误";
			return false;
		}
		$editInfo = $this->TreatMyOrderData($editInfo);
		return $editInfo;
	}
	/*
	 *金牌经纪人-修改订单 
	 * @param $order_id 订单id
	 * @param $data  订单数据
	 * @return boolean   
	 * */
	public function submitEditOrder($order_id,$data){
		if($order_id=='' || false==($editInfo =  $this->isGoldOrder($order_id))){
			$this->error = "订单id错误";
			return false;
		}
		//处于甩单中的订单，只允许修改一次
		if($editInfo['status']==2 && $editInfo['edit_num']>=$this->order_setting['maxEditNum']){
			$this->error = "修改次数已达上限";
			return false;
		}
		
		if(intval($this->getUnlockCount($order_id))>0){
			$this->error = "对不起，订单已被他人解锁，修改不了！";
			return false;
		}
		
		//交易成功，已删除的订单不可修改
		if(in_array($editInfo['status'],array(3,4))){
			$err = array('3'=>'甩单成功','4'=>'已删除');
			$this->error = "对不起，订单已【{$err[$editInfo['status']]}】,不能修改";
			return false;
		}
		//检查数据合法性
		if(false===$this->checkData($data))return false;
		//数据处理
		$data['car_frame_number'] = strtoupper($data['car_frame_number']);
		unset($data['timeadd']);unset($data['order_id']);unset($data['id']);
		//未修改订单内容
		if(false== M('gold_order')->where("id='{$order_id}'")->save($data)){
			$this->error = "未修改订单内容";
			return true;
		}else{
                        if($editInfo['status']==2 )$data['edit_num'] = intval($editInfo['edit_num']+1);
			$data['status'] = 1;//待审核状态
			$data['error_code'] = "";//清空error_code
			$data['last_time'] = date("Y-m-d H:i:s");
			$save_id = M('gold_order')->where("id='{$order_id}'")->save($data);
			if($save_id)
				M('gold_order_history')->add(array('memberid'=>$editInfo['memberid'],'data'=>serialize($editInfo)));
			return true;
		}
		
	}

	/*
	 * 删除我的订单
	 * @param $memberid：用户id  
	 * @param $order_id:订单id
	 * @return boolean
	 * */
	public function deleteMyOrder($memberid,$order_id){
		$_where = [];
		$_where['memberid'] =$memberid;
		$_where['id'] = $order_id; 
		if(intval($this->getUnlockCount($order_id))>0){
			$this->error = "对不起，订单已被他人解锁，删不了！";
			return false;
		}
		if(false==M('gold_order')->where($_where)->save(array('status'=>4,'last_time'=>date("Y-m-d H:i:s")))){
			$this->error = "订单删除失败，请稍后重试！";
			return false;
		}
		return true;
	}
	
	/*
	 * 完成我的订单
	* @param $memberid：用户id
	* @param $order_id:订单id
	* @return boolean
	* */
	public function finishMyOrder($memberid,$order_id){
		$_where = [];
		$_where['memberid'] =$memberid;
		$_where['id'] = $order_id;
		$_where['status'] = 2;
		if(false==M('gold_order')->where($_where)->find()){
			$this->error = "对不起，订单状态未处于甩单中！";
			return false;
		}
                
                if(0==$this->getUnlockCount($order_id)){$this->error = "对不起订单未被解锁，不可完成订单！";return false;}
                
		if(false==M('gold_order')->where($_where)->save(array('status'=>3,'last_time'=>date("Y-m-d H:i:s")))){
			$this->error = "订单完成失败，请稍后重试！";
			return false;
		}
		return true;
	}
	
	
	/*
	 * 订单浏览次数
	 * */
	public function getScanCount($order_id){
		return intval(M('gold_scan')->where("order_id='{$order_id}'")->count());
	}
	/*
	 * 添加浏览次数
	 * @param $memberid:用户id
	 * @param $order_id:订单id
	 * @return boolean
	 * */
	public function addScan($memberid,$order_id){
		$time = date("Y-m-d H:i:s",strtotime("-2 hours"));
		if(false==M('gold_scan')->where("order_id='{$order_id}' and memberid='{$memberid}' and timeadd>='{$time}'")->find()){
			if(false!=M('gold_scan')->add(array('memberid'=>$memberid,'order_id'=>$order_id)))return true;
			$this->error = "添加浏览订单失败";
			return false;
		}else{
			$this->error = "您近期已浏览过此订单";
			return false;
		}
	}
	/*
	 * 添加解锁记录
	 * 			---购买订单，并转账（转账到公司账户下）
	 * @param  $memberid:用户id
	 * @param  $order_id:订单id
	 * @return boolean  
	 * */
	public function addUnlock($memberid,$order_id){
		if(false==($info = $this->isGoldOrder($order_id))){$this->error = "订单不存在";return false;}
		if($info['status']!='2'){$this->error = "对不起，订单【{$info['status_name']}】，不能解锁";return false;}
		if(false==$this->isUnlock($memberid,$order_id)){
			if(false==$this->isOpenGold($memberid)){
				$this->error = "请您注册成为金牌经纪人";
				return false;
			}
			//账户余额判断
			import("Think.ORG.Util.Fuyou");
			$fuyou = new Fuyou();
			if(false==($balance_info = $fuyou->BalanceAction($memberid))){$this->error = $fuyou->getError();return false;}
			$unlockmoney = round($this->order_setting['unlock'],2);
			if(intval($balance_info['ca_balance'])<intval($unlockmoney*100)){$this->error = "余额不足";return false;}
			
			try{
				if (!D('Common')->inTrans()) {
					D('Common')->startTrans();
					$trans = true;
				}
				$data = [];
				$data['memberid']=$memberid;
				$data['order_id']=$order_id;
				$data['type'] = 1;// 0:认证金牌经纪人  1：解锁订单    2：打款给客户记录
				$data['money'] = 0-$unlockmoney;
				$data['detailsn'] = rand(111,999).time();
				$data['remark'] = "解锁订单";
				if(false==M('gold_transfer')->add($data)){
					throw new Exception('添加订单失败，请稍后再试');
				}
				//转账
				$memberinfo = M('member_info')->where("memberid='{$data['memberid']}'")->find();
				$is_trans = $fuyou->transferBu($data['memberid'],$memberinfo['fuyou_login_id'],'',intval($unlockmoney*100),"【借吧】-金牌经纪人-{$data['remark']}",$data['detailsn']);
				if($is_trans==false){
					throw new Exception($fuyou->getError());
				}
				
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
			
		}else{
			$this->error = "您已解锁此订单，无需重复解锁";
			return false;   
		}
	}
		

	/*
	 * 解锁订单数量
	 * @param $order_id:订单数量
	 * @return int
	 * */
	public function getUnlockCount($order_id){
		return intval(M('gold_transfer')->where("order_id='{$order_id}' and type=1")->count());
	}
	
	/*
	 * 订单自动审核通过
	 * 			---审核中的订单，一定时间以后订单状态成为帅单中
	 * */
	public function autoPassed($orderIds = []){
		$passed_hours = $this->order_setting['passedHours'];
		$_where = [];
                if(!empty($orderIds) && is_array($orderIds))
                    $_where['id'] = ['in',$orderIds];
		$_where['status'] = "1";
		$_where['last_time']  = array('lt',date("Y-m-d H:i:s",strtotime("-{$passed_hours} hours")));
		$save_id = M('gold_order')->where($_where)->save(array('status'=>2,'last_time'=>date("Y-m-d H:i:s")));
		return $save_id;
	}
        
        /*
	 * 客服审核通过订单
	 * 
	 * */
	public function  customPassed($orderIds){
                if(empty($orderIds)){$this->error = "订单id不能为空";return false;}
                if(!is_array($orderIds)){$this->error = "id参数错误";return false;}
                $_where = [];
                $_where['id'] = ['in',$orderIds];
		$_where['status'] = "1";
		$save_id = M('gold_order')->where($_where)->save(array('status'=>2,'last_time'=>date("Y-m-d H:i:s")));
		return $save_id;
	}
	
	/*
	 * 甩单中的订单自动下架
	 * 			---一段时间后，无人解锁的订单，自动失效
	 * */
	public function  autoUnpassed(){
		$passed_hours = $this->order_setting['unPassedHours'];
		$_where = [];
		$_where['status'] = "2";
		$_where['id'] = array("exp","not in(select order_id from gold_transfer where (order_id!='' or order_id is not null))");
		$_where['last_time']  = array('lt',date("Y-m-d H:i:s",strtotime("-{$passed_hours} hours")));
		$save_id = M('gold_order')->where($_where)->save(array('status'=>5,'error_code'=>'3','last_time'=>date("Y-m-d H:i:s")));
		return $save_id;
	}
	
	/*
	 * 客服人员审核订单--审核下架
	 * @param $order_id:订单id  
	 * @param $error_code:错误代码（常用图章代码）
	 * @param $remark:备注（其他原因说明）
	 * @return int|boolean
	 * */
	public function cumtomUnPassed($order_id,$error_code,$remark = ''){
		$arrErrorCode = $this->order_setting['error_code'];
		if(!in_array($error_code,array_keys($arrErrorCode))){
			$this->error = "错误代码参数[error_code]错误";
			return false;
		}
		
		$saveData = array(
				'status'=>5,
				'error_code'=>$error_code,
				'last_time'=>date("Y-m-d H:i:s"),
		);
		
		if($arrErrorCode[$error_code][0]=="其他原因"){
			if(empty(trim($remark))){$this->error = "其他原因不能为空";return false;}
			$errData = [];
			$errData['order_id'] = $order_id;
			$errData['picName'] = $this->createErrorCodePic($remark);
			$errData['remark'] = $remark;
			if(false==($errorInfo = M("gold_errorcode")->where("order_id='{$order_id}'")->find()))
				M("gold_errorcode")->add($errData);
			else 
				M("gold_errorcode")->where("id='{$errorInfo['id']}'")->save($errData);
			$saveData['error_code'] = "";//【置空】错误代码（常用图章代码）
		}
		if($arrErrorCode[$error_code][0]=="直接拒单"){
			$this->deleteErrorCodePic($order_id);
			$saveData['edit_num'] = $this->order_setting['maxEditNum'];//修改次数直接设置最大值，直接拒单不可再次修改
		}
		$_where = [];
		$_where['status'] = array('in',"1,2,3");
		$_where['id'] = $order_id;
		$save_id = M('gold_order')->where($_where)->save($saveData);
		return (true==$save_id?$save_id:false);
		 
	}
	
	/*
	 * 账户记录列表
	 * @param $memberid:用户id
	 * @param $page:分页
	 * @param $number:每页的条数
	 * @return array|boolean
	 * */
	public function transList($memberid,$page = 1,$number = 10){
		if(intval($page)<1)$page = 1;
		if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
		if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		$_where = [];
		$_where['memberid'] = $memberid;
		$count = M('gold_transfer')->where($_where)->count();
		$list = array();
		if($count>0){
			$list = M('gold_transfer')
					->where($_where)
					->limit(($page-1)*$number.",".$number)
					->field("id,date_format(timeadd,'%Y-%m-%d') as timeadd,money,remark")
					->order("timeadd desc,id desc")
					->select();
			
		}//if-count
		
		return !empty($list)?$list:[];
	}
	
	/*
	 * 已解锁订单列表
	 * @param $memberid:用户id
	 * @param $page:分页
	 * @param $number:每页条数
	 * @return array
	 * */
	public function lockedOrderList($memberid,$page = 1,$number = 10){
		if(intval($page)<1)$page = 1;
		if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
		if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		$_where = [];
		$_where['f.memberid'] = $memberid;
		$_where['f.type'] = 1;
		$_where['f.is_delete'] = 0;//是否删除，0：否，1：是
		$_where['f.order_id'] = array("exp","is not null or order_id!=''");
		$count = M('gold_transfer f')
				->join("gold_order go on go.id=f.order_id")
				->field("go.*")
				->where($_where)	
				->count();
		$list = array();
		if($count>0){
			$_status_name = "case when go.status=1 then '待审核' when go.status=2 then '甩单中' when go.status=3 then '甩单成功' when go.status=4 then '已删除' when go.status=5 then '订单失效' end as status_name";
			$list = M('gold_transfer f')
				->join("gold_order go on go.id=f.order_id")
				->field("go.*,{$_status_name},f.id as transfer_id,f.timeadd as transfer_timeadd")
				->where($_where)
				->limit(($page-1)*$number.",".$number)
				->order("f.timeadd desc")
				->select();
			foreach($list as &$val){
				$val['timeadd'] = date("m月d日 H:i",strtotime($val['transfer_timeadd']));//解锁日期
				$val['buy_time'] = date("Y.m.d",strtotime($val['buy_time']));
				//浏览次数
				$val['scanCount'] = $this->getScanCount($val['id']);
				//解锁次数
				$val['unlockCount'] = $this->getUnlockCount($val['id']);
                                $isReport = $this->isAllowReport($memberid,$val['id'],$val['transfer_id']);
				$val['reportStatus'] = is_array($isReport)?2:(true==$isReport?1:0);//订单举报状态  0:不允许举报   1：可举报   2：已举报
				//合并头像信息
				if(false!=($agentInfo = $this->getAgent($val['memberid']))){
					$val = array_merge($val,$agentInfo);
				}
			}
		}
		return !empty($list)?$list:[];
	}
	/*
	 * 删除我已解锁的订单
	 * @param $memberid:用户id
	 * @param $order_id:订单id
	 * @return boolean
	 * */
	public function deleteMyLockedOrder($memberid,$order_id){
		if(false==($transInfo = $this->isUnlock($memberid,$order_id))){$this->error = "您未解锁此订单";return false;}
		if($transInfo['is_delete']==1){$this->error = "您已删除此订单";return false;}
		$_where = [];
		$_where['memberid'] = $memberid;
		$_where['order_id'] = $order_id;
		$_where['type'] = 1;
		$_where['is_delete'] = 0;
		if(false==M('gold_transfer')->where($_where)->save(array('is_delete'=>1))){
			$this->error = "订单删除失败，请稍后重试！";
			return false;
		}
		return true;
	}
	/*
	 * 发布人订单-重新上架
	 * @param $memberid:用户id
	 * @param @order_id:订单id
	 * @return int|boolean
	 * */
	public  function passedAgain($memberid,$order_id){
                $orderInfo = $this->isGoldOrder($order_id);
                if(""!=$orderInfo['error_code']){$this->error = "对不起，您的订单【".$this->order_setting['error_code'][$orderInfo['error_code']][0]."】";return false;}
		$_where = [];
		$_where['id'] = $order_id;
		$_where['memberid'] = $memberid;
		$_where['status'] = 5;
		$save_id = M('gold_order')->where($_where)->save(array('status'=>2,'error_code'=>"",'last_time'=>date("Y-m-d H:i:s")));
		return (true==$save_id?$save_id:false);
	}
	
	
	/*
	 * 我举报的订单数据
	 * @param $memberid:用户id
	 * @param $order_id:订单id
	 * @param $transfer_id:举报内容id
	 * @return array|boolean
	 * */
	public function reprotOrderInfo($memberid,$order_id,$transfer_id){
            if(!is_array($info=$this->isReport($memberid,$order_id,$transfer_id))){$this->error = "对不起，举报内容不见了";return false;}
            if(!empty($info['pic_urls'])){
            	$path = $this->_static_."/Upload/report/";
            	$info['pic_urls'] = explode("|",str_replace("|","|".$path,$path.$info['pic_urls']));
            }else{
            	$info['pic_urls'] = [];
            }
            $repResult = ['1'=>'订单处理中','2'=>'卖方责任','3'=>'买方责任'];
            $info['remark'] = $info['remark']?"{$repResult[$info['status']]}--".$info['remark']:"客服正在积极处理中，请耐心等待……";
            return $info;
	}
	
	
	
	/*
	 * 举报订单
	 * @param $memberid:用户id
	 * @param $data:举报内容，包括订单id
	 * @return boolean|int
	 * */
	public function addReportOrder($memberid,$data){
		if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		if(!isset($data['order_id']) || empty($data['order_id']) || false==($orderInfo = $this->isGoldOrder($data['order_id']))){
			$this->error = "订单id错误";
			return false;
		}
		if(!in_array($orderInfo['status'],array(2,3,5))){$this->error = "举报不了，订单未完成或甩单中";return false;}
		if(false!=$this->isReport($memberid,$data['order_id'],$data['transfer_id'])){
			$this->error = "您已举报过此订单，敬请客服处理！";
			return false;
		}
		//未解锁订单
		if(false==($unlockInfo = $this->isUnlock($memberid,$data['order_id']))){
			$this->error = "您未解锁此订单";
			return false;
		}
		
		$in_time = time()<intval(strtotime($unlockInfo['timeadd'])+intval($this->order_setting['reportHours'])*3600)?true:false;
		if($unlockInfo['timeadd'] && !$in_time){
			$this->error = "举报不了，订单已过{$this->order_setting['reportHours']}小时";
			return false;
		}
		
		$data['transfer_id'] = $unlockInfo['id'];
		$data['memberid'] = $memberid;
		$data['status'] = 1;//订单处理中
		if(!empty($_FILES)){
			$type = 'report';
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			$upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;
			$upload->thumb = true;
			$upload->saveRule = $memberid."_".time();           
			$upload->uploadReplace = true;                 
			$upload->thumbPrefix = 'm_,s_';  
			$upload->thumbMaxWidth = '400,100';
			$upload->thumbMaxHeight = '400,100';
			if(!$upload->upload()) {
				$this->error = $upload->getErrorMsg();
				return false;
			}
			$info =  $upload->getUploadFileInfo();
			$data['pic_urls'] = implode("|",array_column($info,"savename"));
		}
		
		if(false==($add_id = M('gold_report')->add($data))){
			$this->error = "举报失败，请稍后重试！";
			return false;
		}
		return $add_id;
	}
        
        /*订单解锁金额---自动打款给卖方
         *  @param $order_id:订单ID
         * @return int
         */
        public function autoTransToSeller(){
            $hours = $this->order_setting["transToSellerHours"];
            $i = 0;
            $_where = [];
            $_where['f.type'] = "1";
            $_where['f.is_trans'] = "0";
            $_where['f.timeadd'] = ['lt',date("Y-m-d H:i:s",strtotime("-{$hours} hours"))];
            $_where['r.id'] = ["exp","is null"];
            $list = M('gold_transfer f')
                       ->join("gold_report r on r.transfer_id=f.id")
                       ->join("gold_order o on f.order_id=o.id")
                       ->field("f.*,r.id as report_id,o.memberid as in_memberid")
                       ->where($_where)
                       ->select();
            if(count($list)>0){
            	$log = $this->getLoggerObj();
            	$log->info("---订单解锁，转账给卖方：");
            }
            foreach($list as $k=>$val){
                $log->info("订单数据：".json_encode($val));
                if(false!=$this->isReport($val['memberid'], $val['order_id'], $val['id'])){
                    $log->info("订单处于举报中");
                    continue;
                }
                if(false==$this->trans($val['id'],$val['in_memberid'],"解锁订单收益")){
                    $log->info("订单转账失败:".$this->error);
                    continue;
                }else{
                    $i++;
                    $log->info("订单转账成功");
                }
                sleep(1);
            }//foreach
            if(count($list)>0)
            	$log->info("---订单解锁，转账给卖方:共".count($list)."个订单；成功转账{$i}笔");
            return $i;
        }
        /*客服处理举报订单
         *  @param $report_id:举报订单ID
         *  @param $data  :处理订单数据  
         * @return boolean
         */
        public function customTrans($report_id,$data = []){
            if(empty($data['status']) || !in_array($data['status'],[2,3])){$this->error = "参数[data:status]错误";return false;}
            if(empty($data['remark'])){$this->error = "处理内容不能为空";return false;}
            if(false==($reportInfo = M('gold_report')->where("id='{$report_id}'")->find())){$this->error = "举报订单不存在";return false;}
            if($reportInfo['status']!=1){$this->error = "此举报订单已被处理过！";return false;}
           
            try{
                if (!D('Common')->inTrans()) {
                        D('Common')->startTrans();
                        $trans = true;
                }
                $saveReport = [];
                $saveReport['status'] = $data['status'];
                $saveReport['remark'] = $data['remark'];
                if(false==M('gold_report')->where("id='{$report_id}'")->save($saveReport)){
                        throw new Exception('处理举报订单失败');
                }
                 $log = $this->getLoggerObj();
                 $log->info("---客服处理，订单解锁：");
                 $log->info(json_encode($data));
                if(2==$data['status']){//卖方责任，打款给买方
                    $log->info("卖方责任，打款给买方transfer_id:{$reportInfo['transfer_id']}:in_memberid:{$reportInfo['memberid']}");
                    if(false==$this->trans($reportInfo['transfer_id'], $reportInfo['memberid'],"【客服】-解锁订单退款"))
                         throw new Exception('打款给买方，转账失败');
                    
                }elseif(3==$data['status']){//买方责任，打款给卖方
                    $orderInfo = $this->isGoldOrder($reportInfo['order_id']);
                    $log->info("买方责任，打款给卖方transfer_id:{$reportInfo['transfer_id']}:in_memberid:{$orderInfo['memberid']}");
                     if(false==$this->trans($reportInfo['transfer_id'], $orderInfo['memberid'],"【客服】-解锁订单收益"))
                          throw new Exception('打款给卖方，转账失败['.$this->error."]");
                }
                $log->info("转账成功");
                if ($trans) {
                        D('Common')->commit();
                }
                return true;
		}catch(Exception $ex){
			$this->error = $ex->getMessage();
                        $log->info($this->error);
			if ($trans) {
				D('Common')->rollback();
			}
			return false;
		}
                
        }
	
	/*
	 * 修改用户个人信息
	 * @param $memberid:用户id
	 * @param $data:待处理数据
	 * @return boolean
	 * */
	public function editAgentInfo($memberid,$data){
		if($memberid=='' || false==M('member')->where("id='{$memberid}'")->find()){$this->error = "会员id错误";return false;}
		if(empty($data['nickname'])){$this->error = "昵称不能为空";return true;}
		if(empty($data['company_name'])){$this->error = "公司简称不能为空";return false;}
		if(empty($data['company_full_name'])){$this->error = "公司全称不能为空";return false;}
		$data['vip_time'] = date("Y-m-d H:i:s");
		unset($data['pic_card2']);
		if(!empty($_FILES)){
			$type = 'agent';
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			$upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;
			$upload->thumb = true;
			$upload->saveRule = $memberid."_".time();           
			$upload->uploadReplace = true;                 
			$upload->thumbPrefix = 'm_,s_';  
			$upload->thumbMaxWidth = '800,100';
			$upload->thumbMaxHeight = '800,100';
			if(!$upload->upload()) {
				$this->error = $upload->getErrorMsg();
				return false;
			}
			$info =  $upload->getUploadFileInfo();
			$data['pic_card2'] = implode("|",array_column($info,"savename"));
		}
		
		if(false==($save_id=M('gold_agent')->where(array('memberid'=>$memberid))->save($data))){
			$this->error = "修改失败，请稍后再试！";
			return false;
		}
		return $save_id;
	}
	
	public function isGoldOrder($order_id){
		$_status_name = "case when status=1 then '待审核' when status=2 then '甩单中' when status=3 then '甩单成功' when status=4 then '已删除' when status=5 then '订单失效' end as status_name";
		return M('gold_order')->field("*,{$_status_name}")->where("id='{$order_id}'")->find();
	}
	
	/*
	 * 订单是否已被解锁
	* @param  $memberid:用户id
	* @param  $order_id:订单id
	* @return boolean|array
	* */
	public function isUnlock($memberid,$order_id){
		if(false!=($unLockInfo = M("gold_transfer")->where("memberid='{$memberid}' and order_id='{$order_id}' and type=1")->find()))return $unLockInfo;
		//自己的订单无需解锁，可直接查看
		if(false!=($orderInfo = $this->isGoldOrder($order_id)) && $orderInfo['memberid']==$memberid)return true;
		return false;
	}
	
	/*
	 * 是否举报该订单
	 * @param $memberid:用户id   
	 * @param $order_id:订单id
	 * @param $transfer_id:解锁id
	 * @return boolean|array
	 * */
	public function isReport($memberid,$order_id,$transfer_id){
            return M('gold_report')->where(array('memberid'=>$memberid,'order_id'=>$order_id,'transfer_id'=>$transfer_id))->find();
	}
	
        /*
	 * 是否允许举报该订单
	 * @param $memberid:用户id   
	 * @param $order_id:订单id
	 * @param $transfer_id:解锁id
	 * @return boolean
	 * */
        public function isAllowReport($memberid,$order_id,$transfer_id){
            if(false!=($reportInfo = $this->isReport($memberid, $order_id, $transfer_id)))return $reportInfo;
            if(false==($transInfo = $this->isUnlock($memberid,$order_id)))return false;
             $in_time = time()<intval(strtotime($transInfo['timeadd'])+intval($this->order_setting['reportHours'])*3600)?true:false;
            if(!$in_time)return false;
            return true;
        }
	/*
	 * 是否允许添加订单
	 * @param  $memberid：用户id  
	 * @return boolean
	 * */
	public function isAllowAddOrder($memberid){
		$order_setting = $this->order_setting;
		if($memberid=='' || (false==M('member')->where("id='{$memberid}'")->find())){
			$this->error = "用户id不存在";
			return false;
		}
		
		if(false==($agent_info = $this->isOpenGold($memberid))){
			$this->error = "对不起，只有金牌经济人可以甩单";
			return false;
		}
		
		if($agent_info['status']==2){
			$this->error = "对不起，您的金牌经济人权限已被禁用";
			return false;
		}
		//订单基础配置校审
		if($order_setting!=''){
			if($order_setting['current_num']<intval(M('gold_order')->where("memberid='{$memberid}' and  timeadd between date_format(NOW(),'%Y-%m-%d') and concat(date_format(NOW(),'%Y-%m-%d'),' 23:59:59')")->count())){
				$this->error = "对不起，今日订单已达上限";
				return false;
			}
		}
		return true;
	}
	
	//检查数据合法性
	public function checkData($data){
		if($data['names']==''){$this->error = "客户姓名不能为空";return false;}
		if($data['age']==''){$this->error = "客户年龄不能为空";return false;}
		if(intval($data['age'])>100 || intval($data['age'])<10){$this->error = "客户年龄不正确";return false;}
		if(''==$data['province']){$this->error = "客户省份不能为空";return false;}
		if(''==$data['city']){$this->error = "客户城市不能为空";return false;}
		if(''==$data['car_province']){$this->error = "车辆户籍所在省份不能为空";return false;}
		if(''==$data['car_city']){$this->error = "车辆户籍所在城市不能为空";return false;}
		if(''==$data['jobs']){$this->error = "客户职业不能为空";return false;}
		if(''==$data['brands']){$this->error = "品牌型号不能为空";return false;}
		if(''==$data['buy_time']){$this->error = "购车时间不能为空";return false;}
		if(''===$data['car_price']){$this->error = "裸车价格不能为空";return false;}
		if(round($data['car_price'],2)>1000 || round($data['car_price'],2)<1){$this->error = "裸车价格不正确";return false;}
		if(''==$data['car_drive']){$this->error = "行驶里程不能为空";return false;}
		if(round($data['car_drive'],2)>100 || round($data['car_drive'],2)<=0){$this->error = "行驶里程不正确";return false;}
		if(''==$data['car_frame_number']){$this->error = "车架号码不能为空";return false;}
		if(strlen($data['car_frame_number'])!='17'){$this->error = "车架号码必须17位";return false;}
		if(''==$data['is_fullmoney']){$this->error = "是否全款不能为空";return false;}
		if(!in_array($data['is_fullmoney'],array_keys($this->getSetting('mort_company')))){$this->error = "是否全款的参数错误";return false;}
		if(''==$data['mort_company'] && $data['is_fullmoney']!=1){$this->error = "抵押单位不能为空";return false;}
		if(''==$data['loan_money']){$this->error = "贷款金额不能为空";return false;}
		if(round($data['loan_money'],2)>100 || round($data['loan_money'],2)<1){$this->error = "贷款金额不正确";return false;}
		if(''==$data['mobile']){$this->error = "联系电话不能为空";return false;}
		if(''==$data['remark']){$this->error = "备注不能为空";return false;}
		if(!preg_match("/^1[0-9]{10}$/", $data['mobile'])){$this->error = "联系电话不正确";return false;}
		return true;
	}
	
	/*
	 * 获取金牌经纪人基本信息
	 * 
	 * */
	public function getAgent($memberid){
		$agentInfo = M('gold_agent agent,member_info i,member m')
				->field("i.avatar,m.username,agent.nickname,if(agent.is_vip=2,'1','0') as is_vip,agent.company_name")
				->where("agent.memberid=i.memberid and i.memberid=m.id and agent.memberid='{$memberid}'")
				->find();
		$agentInfo['avatar'] = $this->_static_.($agentInfo['avatar']?'/Upload/avatar/'.$agentInfo['avatar']:'/2015/member/image/account/heads.png');
        $agentInfo['vip_picurl'] = $this->getMemberV($memberid);
        if(!empty($agentInfo['vip_picurl']))$agentInfo['is_vip'] = 1;
		return $agentInfo;
	}
	
	/*
	 * 获取金牌经济人模块的基本配置信息
	 * @key 配置数组的key
	 * @return array
	 * */
	public function getSetting($key=''){
		$setting = array();
        $setting['status'] = [0=>'全部',2=>'甩单中','3'=>'甩单成功'];//订单状态
		$setting['pay_money'] = 100;//认证经济人收取的费用
		$setting['jobs'] = [0=>'全部',1=>'私营业主',2=>'上班族',3=>'公务员',4=>'在校生'];//职业
		$setting['mort_company'] = [0=>'全部','1'=>'银行',"2"=>'小贷公司'];//抵押单位
		$setting['full_money'] = [0=>'全部','1'=>'全款','2'=>'按揭'];//车辆类型，按揭|全款
		$setting['unlock_money'] = $this->order_setting['unlock'];//解锁单子金额
		$setting['error_code'] = $this->order_setting['error_code'];//订单错误类别
		if($key=='' || $key=='all' || !in_array($key,array_keys($setting)))return $setting;
		
		return $setting[$key];
	}
	//获取错误信息
	public function getError(){
		return $this->error;
	}
        /*
         * 获取用户V字标识，默认返回用户当前最高V等级
         * 					--获取用户V字图片URL
         * 
         * @param $memberid:用户id   $level:等级
         * @return string
         * 
         * 			说明  level 的获取细节：$level = intval(array_search('未完成',array_column(array_merge($this->levelDes($mmeberid),[['statusName'=>'未完成']]),"statusName")))-1;
         * 				1.获取level列表，最后插入未完成元素
         *          	2.取出’未完成‘一列，搜索取出’未完成‘的键
         *          	3.键减去1，就是用户当前最高V等级
       
         */
        public function getMemberV($memberid,$level = ''){
            if(''===$level){
            	$levelDesList = $this->levelDes($memberid);
            	$level = intval(array_search('未完成',array_column(array_merge($levelDesList,[['statusName'=>'未完成']]),"statusName")))-1;
            	$level = $level<0?$level:$levelDesList[$level]['level'];
            }
            if(intval($level)<0){return "";}
            return $this->_static_."/2015/image/yydai/agent/ico_v{$level}.png";
        }
      
        /*
         * 等级说明
         * 		--个人账户等级说明
         *  @param $memberid:用户iD
         *  @return array
         * */
        public function levelDes($memberid){
        	$list = [];
        	$rules = ['1'=>2,'2'=>5,'3'=>10,'4'=>20,'5'=>40,'6'=>70,'7'=>110,'8'=>160,'9'=>220];
        	$finishCount = intval(M('gold_order')->where("memberid='{$memberid}' and status=3")->count());
        	$is_vip = false!=($info = M('gold_agent')->field("if(is_vip=2,'1','0') as is_vip")->where("memberid='{$memberid}'")->find()) && $info['is_vip']?"已完成":"未完成";
        	if(!($finishCount>=$rules['1'] && $is_vip=='未完成'))
        		$list[]= ['levelPic'=>$this->getMemberV($memberid,0),'levelTitle'=>'上传名片，完成认证','statusName'=>$is_vip,"level"=>0];
        	foreach($rules as $k=>$v)
        		$list[] = ['level'=>$k,'levelPic'=>$this->getMemberV($memberid,$k),'levelTitle'=>"甩单完成{$v}单",'statusName'=>($finishCount>=$v?"已完成":"未完成")];
        	return $list;
        }
        /*
         * 获取订单失效时的图片名称
         * @param $order_id:订单id
         * @param $error_code:图片名称
         * @return array|boolean
         * */
        public function getErrorCodePicName($order_id,$error_code = ""){
        	//常用错误图片
        	if(''!=$error_code && !empty($this->order_setting['error_code'][$error_code][1]))return $this->order_setting['error_code'][$error_code][1];
        	//个别错误图片
        	if(false!=($errorInfo = M('gold_errorcode')->where("order_id='{$order_id}'")->find()) && is_file(APP_PATH."../static/2015/agent_img/{$errorInfo['picName']}")){
        		return "{$this->_static_}/2015/agent_img/{$errorInfo['picName']}";
        	}
        	return "";
        }
        
        /*
         * 生成订单错误信息图章 
         * @param $text:图章文字
         * @param $picName:图片名称
         * @reutrn string 图片名称
         * */
        public function createErrorCodePic($text,$picName = ''){
        	$picName = ""==$picName?rand(111,999).time().".png":$picName;
        	$errDefaultDir = APP_PATH."../static/2015/agent_img";
        	$font_size = APP_PATH."../static/Upload/msyh.ttc"; //字体样式
        	$source = imagecreatefrompng($errDefaultDir."/err_default.png");//建立图像源
        	$bg = imagecolorallocatealpha($source,0,0,0,127);//拾取一个完全透明的颜色   
        	$color = imagecolorallocate($source,196,10,9); //字体颜色     
        	imagealphablending($source , false);//关闭混合模式，以便透明颜色能覆盖原画板     
        	imagefill($source,0,0,$bg);//填充透明背景     
        	imagefttext($source,round(220/mb_strlen($text,"UTF-8"),4)-8,10,20,95,$color,$font_size,$text);     
        	imagesavealpha($source,true);//设置保存PNG时保留透明通道信息     
        	imagepng($source,$errDefaultDir."/{$picName}");//生成图片     
        	imagedestroy($source);
        	return $picName;
        	
        }
        
        /*
         * 删除订单错误图章
         * @param $order_id:订单id
         * @return boolean
         * */
        public function deleteErrorCodePic($order_id){
        	if(false!=($errInfo = M('gold_errorcode')->where("order_id='{$order_id}'")->find())){
        		unlink(APP_PATH."../static/2015/agent_img/{$errInfo['picName']}");
        		$delete_id = M('gold_errorcode')->where("id='{$errInfo['id']}'")->delete();
        		return true==$delete_id?true:false;
        	}
        	return false;
        }
        
        /*
         * 邀请记录 
         * 
         * 
         * */
        public function recommandList($memberid,$page = 1,$number = 10){
        	if(intval($page)<1)$page = 1;
        	if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
        	$memberInfo = M('member')->where("id='{$memberid}'")->find();
        	$_where = [];
        	$_where['m.recintcode'] = $memberInfo['mobile'];
        	$_where['m.id'] = ['exp',"is not null and (m.id=a.memberid)"];
        	$count = M('member m,gold_agent a')->where($_where)->count();
        	$lists = array('total'=>intval($count),"recommandList" =>[]);
        	if($count>0){
        		$lists['recommandList'] = M('member m,gold_agent a')
        						->field("m.mobile,date_format(a.timeadd,'%Y-%m-%d') as timeadd")
				        		->where($_where)
				        		->limit(($page-1)*$number.",".$number)
				        		->order("a.timeadd desc")
				        		->select();
        	}//if-count
        	$lists['recommandList'] = !empty($lists['recommandList'])?$lists['recommandList']:[];
        	return $lists;
        }
        
        /*
         * 解锁转账
         * 		--解锁订单收益、解锁订单退款
         * @param $trans_id:解锁订单id
         * @param in_memberid:收款账户用户id 
         * @param remark:转账备注
         * @return boolean
         */
        private function trans($trans_id,$in_memberid,$remark){
            $_where = [];
            $_where['id'] = $trans_id;
            $_where['type'] = 1;
            $_where['is_trans'] = 0;
            $trans_info = M('gold_transfer')->where($_where)->find();
            if(false==$trans_info){$this->error = "转账订单id错误";return false;}
            if(false!=M("gold_transfer")->where("order_id='{$trans_info['order_id']}' and memberid='{$in_memberid}' and type=2")->find()){$this->error = "已转过账单，请勿重复转账";return false;}
            try{
                if (!D('Common')->inTrans()) {
                        D('Common')->startTrans();
                        $trans = true;
                }
                
                $save_id = M('gold_transfer')->where(['id'=>$trans_info['id']])->save(['is_trans'=>1]);
                if(false==$save_id){
                    throw new Exception('修改转账记录失败');
                }
                $addTransData = [];
                $addTransData['type'] = 2;//0:认证金牌经纪人  1：解锁订单    2：打款给客户记录
                $addTransData['memberid'] = $in_memberid;
                $addTransData['detailsn'] = rand(111,999).time();
                $addTransData['order_id'] = $trans_info['order_id'];
                $addTransData['money'] = false==strpos($remark,"退款")?round(abs($trans_info['money'])/2,2):abs($trans_info['money']);
                $addTransData['remark'] = $remark;
               if(false==M('gold_transfer')->add($addTransData)){
                    throw new Exception('添加账单失败，请稍后再试');
                }
                import("Think.ORG.Util.Fuyou");
                $fuyou = new Fuyou();
                $memberinfo = M('member_info')->where("memberid='{$in_memberid}'")->find();
                $is_trans = $fuyou->transferBu($in_memberid,'',$memberinfo['fuyou_login_id'],intval($addTransData['money']*100),"【借吧】-金牌经纪人-{$addTransData['remark']}",$addTransData['detailsn']);
                if($is_trans==false){
                        throw new Exception($fuyou->getError());
                }
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
	 //处理我的订单数据
     private function  TreatMyOrderData($data){
     	$data['timeadd'] = date("m月d日 H:i",strtotime($data['timeadd']));
     	$data['buy_time'] = date("Y.m.d",strtotime($data['buy_time']));
     	$data['is_delete'] = 0;	//是否可以删除， 0：否  1：是
     	$data['is_edit'] = 0;	//是否可以修改， 0：否  1：是
     	$data['is_again'] = 0;	//是否可以重新上架， 0：否  1：是
     	$data['is_finish'] = 0;	//是否可以完成订单， 0：否  1：是
     	$data['error_code_pic'] = "";
     	if($data['status']==1){
     		//审核中，可以删除、修改
     		$data['is_delete'] = $data['is_edit'] = 1;
     	}elseif($data['status']==2){
     		//甩单中，可以修改、完成订单
     		$data['is_delete'] = $data['is_finish'] = $data['is_edit'] = 1;
     	}elseif($data['status']==3){
     		//甩单成功，可以删除
     		$data['is_delete'] = 1;
     	}
     	elseif($data['status']==5){
     		//订单失效，可以删除、修改、重新上架
     		$data['is_delete'] = $data['is_edit'] = $data['is_again'] = 1;
     		$data['error_code_pic'] = $this->getErrorCodePicName($data['id'],$data['error_code']);
     	}
     	//合并头像信息
     	if(false!=($agentInfo = $this->getAgent($data['memberid']))){
     		$data = array_merge($data,$agentInfo);
     	}
     	//浏览次数
     	$data['scanCount'] = $this->getScanCount($data['id']);
     	//解锁次数
     	$data['unlockCount'] = $this->getUnlockCount($data['id']);
     	
     	/*
     	 * 按钮显示规则
     	 * */
     	//存在解锁次数，不允许删除订单
     	if($data['unlockCount']>0)$data['is_delete'] = $data['is_edit'] = 0;
     	else $data['is_finish'] = 0;
     	//存在错误，不可重新上架
     	if(""!=$data['error_code'])$data['is_again'] = 0;
     	//超过修改次数不允许再次修改
     	if(1==$data['is_edit'] && $data['edit_num']>=$this->order_setting['maxEditNum'])$data['is_edit'] = 0;
     	return $data;
     }  
	//隐藏部分数据
	private function hideData($data){
		if(isset($data['isMyLockedOrder']) && 0==$data['isMyLockedOrder']){
			if($data['mobile'] && !empty($data['mobile'])){
                            $data['mobile'] = substr($data['mobile'],0,3)."****".substr($data['mobile'],-3);
			}
                        if($data['names'] && !empty($data['names'])){
                            $data['names'] = mb_substr($data['names'],0,1,"UTF-8"). str_repeat("*", intval(mb_strlen($data['names'],"UTF-8")-1));
			}
                         if($data['car_frame_number'] && !empty($data['car_frame_number'])){
                            $data['car_frame_number'] = substr($data['car_frame_number'],0,6)."****".substr($data['car_frame_number'],-7);
			}
		}
		return $data;
	}
        //返回日志对象
        public function getLoggerObj($path = "goldAgent"){
            import('Think.ORG.Util.Logger');
            $log = Logger::getLogger($path, LOG_PATH);
            $log->info('-----------------');
            return $log;
        }
        private function sqlToLog($sql){
            $log = $this->getLoggerObj("goldAgentSql");
            $log->info($sql);
        }
}