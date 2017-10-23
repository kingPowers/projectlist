<?php
/**
* 
*/
class InsuranceInstalment 
{
	public $memberid;
	public $order_id;
	public $smsCacheKey = 'dsad';
	public $total_money; //订单应付款总额
	public $initial_money; //首付款金额
	public $instalment_money; //分期总额
	public $period_money; //每期还款金额
	public $err = '';
	public $pay_type = ['full' => 1,'instalment' => 2];//付款方式1:全款；2：分期
	public $back_type = ['1' => "本月多期","2" => "本月单期",'3' => "提前多期",'4' => "提前单期"];
	public $config = [ //分期配置
		'initial'      =>   '3', //首付3期
		'periods'      =>   '10', //分期10期
		'period_space' =>   '1', //分期间隔时间
		'space_unit'   =>   'months', //间隔时间单位
		'late_rate'    =>   0.01,//逾期利率(天)
		'back_day'     =>    5,//每个月还款日
 	];
 	//构造函数
 	//@param:$memberid:用户ID 
 	//@oaramL:$order_id:车险订单ID
 	public function __construct($memberid = '',$order_id = '')
 	{
 		$this->memberid = $memberid;
 		if (!empty($order_id)) {
 			$this->init($order_id);
 		}
 	}
 	//车险订单分期数据初始化
 	//@param:$order_id:车险订单ID
 	public function init ($order_id)
 	{
 		$this->order_id = $order_id;
 		if (false == ($order_info = $this->orderInfo($order_id)))return false;
 		$this->total_money = $this->getTotalMoney($order_info);
 		$this->initial_money = $this->getInitialMoney($order_info);
 		$this->instalment_money = $this->getInstalmentMoney($this->total_money,$this->initial_money);
 		$this->period_money = $this->getPeriodMoney($this->instalment_money);
 	}
 	//获取车险订单总额
 	//@param：$order_info:车险订单详情
 	public function getTotalMoney($order_info = [])
 	{
 		return $this->total_money?$this->total_money:round(($order_info['force_money']+$order_info['business_money']),2);
 	}
 	//获取车险订单分期首付金额
 	//@param：$order_info:车险订单详情
 	public function getInitialMoney($order_info = [])
 	{
 		$initial_money = round(($order_info['force_money']+$this->config['initial']*($order_info['business_money']/10)),2);
 		return $this->initial_money?$this->initial_money:$initial_money;
 	}
 	//获取车险订单每期还款金额
 	//@param:$instalment_money:分期总额
 	public function getPeriodMoney ($instalment_money)
 	{
 		$money = round($instalment_money/$this->config['periods'],2);
 		return $this->period_money?$this->period_money:$money;
 	}
 	//获取车险分期总分期金额
 	//@param:$total_money:车险订单总额
 	//@param:$initial_money:车险分期首付
 	public function getInstalmentMoney ($total_money,$initial_money)
 	{
 		return  $this->instalment_money?$this->instalment_money:round(($total_money-$initial_money),2);
 	}
 	//用户分期总详情
 	public function userInstalment ($memberid = '')
 	{
 		$memberid = $memberid?$memberid:$this->memberid;
 		if (empty($memberid)) {$this->err = "用户ID错误【{__METHOD__}】";return false;}	
 		$data['nextmonth_unpayment']['month'] = 1;
 		$data['nexttwomonth_unpayment']['month'] = 2;
 		foreach ($data as &$value) {
 			$where = [
	 			'memberid' => $memberid,
	 			'back_year' => date("Y",strtotime("+{$value['month']} month")),
	 			'back_month' => date("m",strtotime("+{$value['month']} month")),
	 			'status' => 0,
	 		];
	 		$info = $this->payMent($where);
	 		$value = ['year' => $where['back_year'],'month' => $where['back_month'],'money'=> round($info['money']['unpay_money'],2)];
		}
		$current_info = $this->currentPayment($memberid);
		$data['curmonth_unpayment'] = [
			'year' => date("Y"),
			'month' => date("m"),
			'money' => round($current_info['money']['unpay_money'],2),
		];
		$data['total_unpayment'] = round($this->payMent(['memberid' => $memberid,'status' => 0])['money']['unpay_money'],2);//剩余应还款
		$data['total_instalment_money'] = $this->totalInstalmentMoney($memberid);//分期总账单
		return $data;
 	}
 	//分期总账单
	public function totalInstalmentMoney ($memberid = '')
	{
		$memberid = $memberid?$memberid:self::$memberid;
		$money = M('insurance_order')
		->where("memberid={$memberid} and pay_type=2")
		->getField("sum(business_money+force_money)");	 
		return $money?$money:0;
	}
 	/*
 	本月还款情况
 	@param:$memberid:用户ID
 	@return:array:
 	 */
 	public function currentPayment ($memberid = '')
 	{
 		$memberid = $memberid?$memberid:$this->memberid;
 		if (!empty($memberid))$where['memberid'] = $memberid;
 		$where['back_year'] = date("Y");
 		$where['back_month'] = date("m");
 		$where['is_delay'] = 0;
 		$cur_info = $this->payMent($where);//还款日期为本月的未逾期分期情况
 		//逾期未还分期
 		$unpay_delay_where['is_delay'] = 1;
 		$unpay_delay_where['status'] = 0;
 		if (!empty($memberid))$unpay_delay_where['memberid'] = $memberid;
 		$unpay_delay_info = $this->payMent($unpay_delay_where);
 		//逾期本月已还
 		$paid_delay_where['real_back_year'] = date("Y");
 		$paid_delay_where['real_back_month'] = date("m");
 		$paid_delay_where['is_delay'] = 1;
 		$paid_delay_where['status'] = 1;
 		if (!empty($memberid))$paid_delay_where['memberid'] = $memberid;
 		$paid_delay_info = $this->payMent($paid_delay_where);//dump($paid_delay_info);
 		$money['paid_money'] = $cur_info['money']['paid_money']+$paid_delay_info['money']['paid_money'];
 		$money['unpay_money'] = $cur_info['money']['unpay_money']+$unpay_delay_info['money']['unpay_money'];
 		$money['quit_money'] = $cur_info['money']['quit_money'];
 		$list['paid_list'] = array_merge($cur_info['list']['paid_list'],$paid_delay_info['list']['paid_list']);
 		$list['unpay_list'] = array_merge($cur_info['list']['unpay_list'],$unpay_delay_info['list']['unpay_list']);
 		$list['quit_list'] = array_merge($cur_info['list']['quit_list']);
 		//$list['real_back_info'] = $this->payMent(['real_back_year'=>date("Y"),'real_back_month'=>date("m"),'status'=>1]);//真实还款日为本月的订单
 		return ['money' => $money,'list' => $list];
 	}
 	/*
 	全部还款情况
 	 */
 	public function payMent ($params = []) 
 	{
 		$list = $this->instalmentOrderList($params);
 		if (empty($list)) {$this->err = "无分期订单";return false;}
 		$money['paid_money'] = 0;//已还金额
 		$money['unpay_money'] = 0;//未还金额
 		$money['quit_money'] = 0;//退保金额
 		$money['total_instalment_money'] = 0;//分期总额
 		$money['total_period_fee'] = 0;//总管理费
 		$return_list['paid_list'] = [];
 		$return_list['unpay_list'] = [];
 		$return_list['quit_list'] = [];
 		$status['is_delay'] = 0;
 		foreach ($list as $value) {
 			$value['periode_money'] = $value['pay_money'] + $value['period_fee'] + $value['pay_late_fee'];
 			if ($value['status'] == 0) {
 				$money['unpay_money'] += $this->instalmentPay($value);
 				$return_list['unpay_list'][] = $value;
 				if ($value['period_late_days'] > 0) {
	 				$status['is_delay'] = 1;
	 			}
 			} elseif ($value['status'] == 1) {
 				$money['paid_money'] += $value['back_money'];
 				$return_list['paid_list'][] = $value;
 				if ($value['period_late_days'] > 0) {
	 				$status['is_delay'] = 1;
	 			}
 			} elseif ($value['status'] == 2) {
 				$money['quit_money'] += $value['quit_money'];
 				$return_list['quit_list'][] = $value;
 			}
 			$money['total_instalment_money'] += $value['pay_money'];
 			$money['total_period_fee'] += $value['period_fee'];
 		}
 		$info = ['money' => $money,'list' => $return_list,'status' => $status];
 		return $info;
 	}
 	/*
 	车险分期每期应还还金额
 	@param:$info:分期详情
 	 */
 	public function instalmentPay ($info) 
 	{
 		$money = round(($info['pay_money'] + $info['pay_late_fee'] + $info['period_fee'] - $info['back_money']),2);
 		return $money;
 	}
 	//车险订单分期列表
 	//@param:$params:筛选条件
 	//@param:$page:分页页数（默认不分页）
 	//@param:$number:每页显示个数（默认8个）
 	//@parma:$order_by:排序规则
 	public function instalmentOrderList ($params = [],$order_by = '',$page = null,$number = 8) 
 	{
 		$where = [];
 		//分期有效订单
 		$where['io.pay_type'] = $this->pay_type['instalment'];
		//门店
		if (isset($params['store_name']) && !empty($params['store_name'])) {	
			$where['io.store_name'] .= ["like" => "%{$params['store_name']}%"];
		}
		//姓名
		if (isset($params['names']) && !empty($params['names'])) {
			$where['mi.names'] .= ["like" => "%{$params['names']}%"];
		}
		//手机号码
		if (isset($params['mobile']) && !empty($params['mobile'])) {
			$where['m.mobile'] .= ["like" => "%{$params['mobile']}%"];
		}
		//还款时间
        if(!empty($params["startTime"]) && empty($params["endTime"])){
        	$where['ii.back_time'] .= ["EGT" => $params['startTime']];
        }elseif(empty($params["startTime"]) && !empty($params["endTime"])){
        	$where['ii.back_time'] .= ["ELT" => $params['endTime']];
        }elseif(!empty($params["startTime"]) && !empty($params["endTime"])){
        	$where['ii.back_time'] .= ["between" => [$params['startTime'],$params['endTime']]];
        }
		//还款年
		if (isset($params['back_year'])) {
			$is_and = empty($where['_string'])?"":" and ";
			$where['_string'] .= $is_and."date_format(ii.back_time,'%Y')={$params['back_year']}";
		}
		//还款月
		if (isset($params['back_month'])) {
			$is_and = empty($where['_string'])?"":" and ";
			$where['_string'] .= $is_and."date_format(ii.back_time,'%m')={$params['back_month']}";
		}
		//真实还款年
		if (isset($params['real_back_year'])) {
			$is_and = empty($where['_string'])?"":" and ";
			$where['_string'] .= $is_and."date_format(ii.real_back_time,'%Y')={$params['real_back_year']}";
		}
		//真实还款月
		if (isset($params['real_back_month'])) {
			$is_and = empty($where['_string'])?"":" and ";
			$where['_string'] .= $is_and."date_format(ii.real_back_time,'%m')={$params['real_back_month']}";
		}
		//用户ID
		if (isset($params['memberid'])) {
			$where['io.memberid']= $params['memberid'];
		}
		//订单ID
		if (isset($params['order_id'])) {
			$where['ii.order_id'] = $params['order_id'];
		}
		//分期ID
		if (isset($params['instalmentid'])) {
			$where['ii.id'] = $params['instalmentid'];
		}
		//还款状态
		if (isset($params['status'])) {
			$where['ii.status'] = $params['status'];
		}
		//逾期
		if (isset($params['is_delay'])) {
			if ($params['is_delay'] === 0) {//未逾期订单
				$where['fee.period_late_days'] = 0;
			} elseif ($params['is_delay'] === 1) {//逾期订单
				$where['fee.period_late_days'] = ['gt',0];
			}
		}
		//排序
		$order_by = $order_by?$order_by:"ii.id asc";
		$late_fee = "
		case 
			when back_time<(NOW()) and status=0
				then round((ceil((unix_timestamp(NOW())-unix_timestamp(back_time))/86400))*{$this->config['late_rate']}*pay_money,2) 
			else late_fee 
			end as late_fees";
		$late_days = "
		case 
			when back_time<(NOW()) and status=0
				then 
					ceil((unix_timestamp(NOW())-unix_timestamp(back_time))/86400)
			else late_days 
			end as period_late_days";
		$status_name = "case when status=0 then '未还清' when status=1 then '已还清' when status=2 then '已退保' end as status_name";
		$field = "fee.status_name,fee.late_fees as pay_late_fee,fee.period_late_days,ii.*,date_format(ii.back_time,'%m') as pay_month,date_format(ii.back_time,'%Y') as pay_year,io.car_type,io.order_sn,io.force_money,io.business_money,io.memberid,io.insurance_type,io.certinumber_pic,io.drive_license_pic,io.status_process,io.service_license_pic,io.store_name,mi.names,m.mobile,mi.certiNumber,pay_info.pay_num,pay_info.total_back";
		if (is_null($page)) {
			$list = M("insurance_instalment ii")
			->join("insurance_order io on io.id=ii.order_id")
			->join("member_info mi on mi.memberid=io.memberid")
			->join("member m on m.id=io.memberid")
			->join("(select {$late_fee},{$late_days},{$status_name},pay_money,id from insurance_instalment) as fee on fee.id=ii.id")
			->join("(select COUNT(id) as pay_num,sum(back_money) as total_back,order_id from insurance_instalment WHERE `status`=1 GROUP BY order_id) as pay_info on pay_info.order_id=io.id")
			->field($field)
			->where($where)
			->order($order_by)
			->select();
		} else {
			$count = M("insurance_instalment ii")
			->join("insurance_order io on io.id=ii.order_id")
			->join("member_info mi on mi.memberid=io.memberid")
			->join("member m on m.id=io.memberid")
			->join("(select {$late_fee},{$late_days},{$status_name},pay_money,id from insurance_instalment) as fee on fee.id=ii.id")
			->field($field)
			->where($where)
			->count();
			$lists = M("insurance_instalment ii")
			->join("insurance_order io on io.id=ii.order_id")
			->join("member_info mi on mi.memberid=io.memberid")
			->join("member m on m.id=io.memberid")
			->join("(select {$late_fee},{$late_days},{$status_name},pay_money,id from insurance_instalment) as fee on fee.id=ii.id")
			->join("(select COUNT(id) as pay_num,sum(back_money) as total_back,order_id from insurance_instalment WHERE `status`=1 GROUP BY order_id) as pay_info on pay_info.order_id=io.id")
			->field($field)
			->where($where)
			->order($order_by)
			->limit(($page - 1)*$number.','.$number)
			->select();
			$list = ['count'=>$count,'list'=>$lists];
		}	
		//dump(M("insurance_instalment ii")->getLastSql());
		return $list?$list:[];
 	}
 	/*
 	付款获取预支付验证码
	@param:$pay_type:付款方式：1：全额；2：分期
 	*/
	public function getPaySms ($pay_type) {
		if (empty($this->memberid) || empty($this->order_id)) {
			$this->err = "订单或用户ID不能为空";
			return false;
		}
		if (false == ($order_info = $this->orderInfo($this->order_id))) return false;
		if ($pay_type == $this->pay_type['full']) {//全额付款
			$order_sn = $order_info['order_sn'].rand(000,999);
			if (time() <= strtotime("2017-10-31 23:59:59")) {
				$pay_money = $order_info['force_money'] + 0.9*$order_info['business_money'];
			} else {
				$pay_money = $this->total_money;
			}
			
		} elseif ($pay_type == $this->pay_type['instalment']) {//分期付款
			//账单订单号
			if (!empty(S("bill_sn_".$this->order_id))) {
				$order_sn = S("bill_sn_".$this->order_id);
			} else {
				$order_sn = $this->order_id.time().rand(000,999);
				S("bill_sn_".$this->order_id,$order_sn,60*30);
			}
			$pay_money = $this->initial_money;
		} else {
			$this->err = "付款方式错误";
			return false;
		}

		$member_info = $this->getMemberInfo();
        if(false==$member_info)return false;
       	import("Think.ORG.Util.Baofu");
    	$baofu = new Baofu($this->memberid); 
    	$type_info = ['1'=>"全款",'2'=>'分期'];	
    	$log = $this->getLoggerObj();
    	//认证预支付，发送验证码
		if(false==$baofu->prePay($order_sn,$pay_money,"","【借吧-车险-付款-获取验证码】")){
			$this->err = "BAOFU_ERROR:".$baofu->getError();
			$log->info("车险订单【".$this->order_id."】付款【{$type_info[$pay_type]}】获取验证码。宝付订单号：{$order_sn};金额：{$pay_money};状态：失败;原因：" . $this->err);
			S("bill_sn_".$this->order_id,null);
			return false;
		}
		$log->info("车险订单【".$this->order_id."】付款【{$type_info[$pay_type]}】获取验证码。宝付订单号：{$order_sn};金额：{$pay_money};状态：成功");
		return true;
	}
	public function getMemberInfo ($memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (empty($memberid)) {
			$this->err = "{__METHOD__}用户ID不能为空";
			return false;
		}
		$info = (array)M("member_info i,member m")->field("m.mobile,i.*")->where(["i.memberid"=>$memberid,"_string"=>"i.memberid=m.id"])->find();
		if (false == $info){$this->err = "该用户尚未注册";return false;}
		return $info;
	}
	//付款
	public function payMoney ($data)
	{
		$memberid = $data['memberid']?$data['memberid']:$this->memberid;
		$order_id = $data['order_id']?$data['order_id']:$this->order_id;
		$data['memberid'] = $memberid;
		$data['order_id'] = $order_id;
		$order_info = $this->orderInfo($order_id);
		if (false == $this->checkPayData($data))return false;
		if (false == $this->isAllowPay($order_id)) return false;
		$total_money = (time() <= strtotime("2017-10-31 23:59:59"))?($order_info['force_money']+0.9*$order_info['business_money']):$this->total_money;
		$data['pay_money'] = ($data['pay_type'] == $this->pay_type['full'])?$total_money:$this->initial_money;
		
		import("Think.ORG.Util.Baofu");
		$baofu = new Baofu($memberid);
		$log = $this->getLoggerObj();
		try {
			// if (!D('Common')->inTrans()) {
			// 	D('Common')->startTrans();
			// 	$trans = true;
			// }
			D('Common')->startTrans();
			$trans = true;
			//生成分期订单
			if ($data['pay_type'] == 2) {
				//账单编号缓存是都存在
				$bill_sn = S("bill_sn_".$order_id);
				S("bill_sn_".$order_id,null);
				if (empty($bill_sn)) {
					throw new Exception("验证码已失效，请重新获取");		
				}
				if (false == $this->addInstalment($order_id)) {
					throw new Exception($this->getError());		
				}
				$bill['back_sn'] = $bill['bill_sn'] = $bill_sn;
				$bill['order_id'] = $order_id;
				$bill['bill_type'] = 1;
				$bill['money'] = $data['pay_money'];
				$bill['remark'] = "借吧-车险-分期首付";
				$bill['status'] = 1;
				$bill['timeadd'] = date("Y-m-d H:i:s");
				if (false == M("instalment_bill")->add($bill)) {
					throw new Exception("账单新增失败，请稍后再试");
				}
			}
			$save['status_process'] = 4;
			$save['pay_type'] = $data['pay_type'];
			if (false == M("insurance_order")->where("id={$order_id}")->save($save)) {
				throw new Exception("保存订单失败");		
			}
			$process['order_id'] = $order_id;
			$process['status_process'] = 4;
			$process['status_deny'] = 0;
			$pay_type_info = ($data['pay_type']==2)?"分期首付":"全额";
			$process['message'] = "客户已付款，付款方式：{$pay_type_info};金额：{$data['pay_money']}";
			$process['timeadd'] = date("Y-m-d H:i:s");
			if (false == M("insurance_order_process")->add($process)) {
				throw new Exception("订单处理失败，请稍后再试");
			}
			//宝付支付
			 if(false==($result = $baofu->prePaySubmit($data['smscode'],"【借吧-车险-付款】"))){
                            throw new Exception("BAOFU_ERROR:".$baofu->getError());
                         }  		
    		if ($trans) {
    			D("Common")->commit();
    		}
    		$log->info("车险订单【{$order_id}】付款成功。付款方式：".($data['pay_type']==2)?"分期":"全额".";宝付订单：{$bill_sn}");
    		return "付款成功";
		} catch (Exception $ex) {
			$this->err = $ex->getMessage();
			$log->info("车险订单【{$order_id}】付款失败。ERROR:".$ex->getMessage()."宝付订单：{{$bill_sn}}");
			if ($trans) {
				D('Common')->rollback();
			}
			return false;
		}
	}
	//检验订单是否允许付款
	public function isAllowPay ($order_id)
	{
		if (false == ($order_info = $this->orderInfo($order_id)))return false;
		if ($order_info['status'] != 1 && ($order_info != 3)) {
			$this->err = "该订单尚未处于待付款状态";
			return false;
		}
		return true;
	}
	//检验付款数据
	public function checkPayData ($data) 
	{
		$not_empty = ['memberid'=>'用户ID','order_id'=>'订单ID','pay_type'=>'付款方式','smscode'=>"验证码"];
		foreach ($not_empty as $key => $value) {
			if (empty($data[$key])) {
				$this->err = "{$value}不能为空";
				return false;
			}
		}
		if (!in_array($data['pay_type'],$this->pay_type)) {
			$this->err = "付款方式错误";
			return false;
		}
		return true;
	}
	/*
	添加分期订单
	 */
	public function addInstalment ($order_id = '')
	{
		$order_id = $order_id?$order_id:$this->order_id;
		if (empty($order_id)) {
			$this->err = "{__METHOD__}订单ID不能为空";
			return false;
		}
		$list = $this->instalmentList($order_id);
		if (false != $this->instalmentOrderList(['order_id'=>$order_id])) {
			$this->err = "该订单已经分期，无需再申请";
			return false;
		}
		if (false == ($add_id = M("insurance_instalment")->addAll($list))) {
			$this->err = "添加分期订单失败。请稍后再试";
			return false;
		}
		return "添加成功";
	}
 	/*
	获取分期订单列表
	 */
	public function instalmentList ($order_id = '')
	{
		if (!empty($order_id) && $order_id != $this->order_id) {
			$this->init($order_id);
		}
		$order_id = $this->order_id;
		if (empty($order_id)) {
			$this->err = "【{__METHOD__}】订单id不能为空";
			return false;
		}
		//判断是否已经分期
		if ($instalment_info = $this->instalmentOrderList(['order_id'=>$order_id])) {
			$this->err = "改订单已分期";
			return false;
		}
		//生成新的分期列表
		if (false == $this->isAllowInstalment($order_id))return false;
		$list = [];
		for ($i = 1;$i <= $this->config['periods'];$i++) { 
			$tmp = [];
			$tmp['order_id'] = $order_id;
			$tmp['instalment_sn'] = $order_id.substr(time(),-4).rand(000,999).$i;
			$tmp['initial_money'] = $this->initial_money;
			$tmp['period'] = $i;
			$tmp['pay_money'] = $this->period_money;
			$days = intval($this->config['period_space']*$i);
			$back_time = date("Y-m-d 23:59:59",strtotime("+{$days} {$this->config['space_unit']}",time()));
			$BD = date("j",strtotime($back_time));
			$D = date("j",time());
			if ($D != $BD) {
				$back_time = date("Y-m-d 23:59:59",strtotime("-{$BD} days",strtotime($back_time)));
			}
			$tmp['back_time'] = $back_time;
			$tmp['late_days'] = 0;
			$tmp['late_fee'] = 0;
			$tmp['period_fee'] = 0;
			$tmp['status'] = 0;
			$tmp['timeadd'] = date("Y-m-d H:i:s");
			array_push($list,$tmp);
		}
		return $list?$list:[];
	}
	//是否允许分期
	public function isAllowInstalment ($order_id)
	{
		if (false == ($order_info = $this->orderInfo($order_id)))return false;
		if (($order_info['status'] != 1) || ($order_info['status_process'] != 3)) {
			$this->err = "该订单不允许分期";
			return false;
		}
		return true;
	}
	//已出账信息
	public function outBillInfo ($memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		$data['date'] = date("Y年m月");
		//本月分期详情
		$current_info = $this->currentPayment($memberid);
		$data['unpayment_money'] = $current_info['money']['unpay_money'];
		$data['unpayment_num'] = count($current_info['list']['unpay_list']);
		$data['unpay_instalment'] = $current_info['list']['unpay_list'];
		//本月已还订单（不含提前还款的）
		$all_paid_list = $current_info['list']['paid_list'];//dump($all_paid_list);
		$data['paid_money'] = 0;
		$data['paid_instalment'] = [];
		foreach ($all_paid_list as $value) {
			if (date("Ym",strtotime($value['real_back_time'])) === date("Ym")) {
				$data['paid_instalment'][] = $value;
				$data['paid_money'] += $value['back_money'];
			}
		}
		$data['paid_num'] = count($data['paid_instalment']);
		$data['is_allow_pay'] = $data['unpayment_money']?1:0;
		return $data;
	}
	//未出账信息
	public function unoutBillInfo ($memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (!empty($memberid))$where['memberid'] = $memberid;
		$data['date'] = date("Y年m月",strtotime("+1 months"));
		$where['back_year'] = date("Y",strtotime("+1 months"));
		$where['back_month'] = date("m",strtotime("+1 months"));
		$next_info = $this->payMent($where);
		$data['unpayment_money'] = $next_info['money']['unpay_money'];
		$data['unpayment_num'] = count($next_info['list']['unpay_list']);
		$data['unpay_instalment'] = $next_info['list']['unpay_list'];
		$data['paid_money'] = $next_info['money']['paid_money'];
		$data['paid_num'] = count($next_info['list']['paid_list']);
		$data['paid_instalment'] = $next_info['list']['paid_list'];
		$this_month = $this->outBillInfo($memberid)['unpayment_money'];
		$data['is_allow_pay'] = ($this_month == 0 && ($data['unpayment_money'] != 0))?1:0;
		return $data;
	}
	
	//订单分期明细
	public function instalmentDetail ($instalmentid) {
		if (empty($instalmentid)) {
			$this->err = "分期ID不能为空";
			return false;
		}
		if (false == $info = $this->instalmentOrderList(['instalmentid'=>$instalmentid])[0]) {
			$this->err = "分期订单不存在";
			return false;
		}
		$info['need_paymoney'] = $this->instalmentPay($info);//剩余应还金额
		$insurance_order = 	$this->orderInstalmentInfo($info['order_id']);
		$info['total_instalment_money'] = $insurance_order['total_instalment_money'];//车险总分期金额
		$info['total_period_fee'] = $insurance_order['total_period_fee'];//总手续费
		$info['pay_periodes'] = $insurance_order['pay_periodes'];//已还期数
		$info['is_delay'] = $insurance_order['is_delay'];//车险是否逾期
		return $info;
	}
	/*车险订单分期详情
	@params:$order_id:车险订单ID；
	*/
	public function orderInstalmentInfo ($order_id)
	{
		if (empty($order_id)) {
			$this->err = "{__METHOD__}订单ID不能为空";
			return false;
		}
		$instalment_info = $this->payMent(['order_id'=>$order_id]);//dump($instalment_info);
		if (false == $instalment_info) {
			$this->err = "该车险并无分期订单";
			return false;
		}
		$order['unpayment'] = (float)$instalment_info['money']['unpay_money'];//车险订单未还分期总额
		$order['payment'] = (float)$instalment_info['money']['paid_money'];//车险订单已还总额
		$order['total_instalment_money'] = (float)$instalment_info['money']['total_instalment_money'];;//车险分期总额
		$all_list = array_merge($instalment_info['list']['paid_list'],$instalment_info['list']['unpay_list'],$instalment_info['list']['quit_list']);
		//首付账单
		$initial_info = $this->historyBill(['order_id'=>$order_id,'bill_type'=>1]);
		$order['initial_time'] = $initial_info[0]['timeadd'];
		$order['initial_status'] = $initial_info[0]['status'];
		$order['initial_money'] = (float)$initial_info[0]['money'];
		$order['order_sn'] = $all_list[0]['order_sn'];
		$order['car_type'] = $all_list[0]['car_type'];
		$order['pay_periodes'] = count($instalment_info['list']['paid_list']);
		$order['unpay_periodes'] = count($instalment_info['list']['unpay_list']);//未还期数
		$order['instalment'] = $all_list;//获取的分期订单
		$order['total_period_fee'] = (float)$instalment_info['money']['total_period_fee'];//车险分期总手续费
		$order['is_delay'] = $instalment_info['status']['is_delay'];
		$order['total_money'] = $order['total_instalment_money'] + $order['initial_money'];
		return $order;
	}
	/*用户分期历史账单
	@param $time:时间数组：year：年；month:月；day:日
	@param $memberid:用户ID
	*/
	public function historyBill ($params = [])
	{
		// $params['memberid'] = $params['memberid']?$params['memberid']:$this->memberid;
		// if (empty($params['memberid'])) {
		// 	$this->err = "{__METHOD__}用户ID不能为空";
		// 	return false;
		// }
		//$where = "io.memberid={$params['memberid']}";
		if (isset($params['memberid'])) {
			$where['io.memberid'] = $params['memberid'];
		}
		if (isset($params['order_id'])) {
			$where['ib.order_id'] = $params['order_id'];
		}
		if (isset($params['instalmentid'])) {
			$where['ib.instalmentid'] = $params['instalmentid'];
		}
		if (isset($params['bill_type'])) {
			$where['ib.bill_type'] = $params['bill_type'];
		}
		if (isset($params['status'])) {
			$where['ib.status'] = $params['status'];
		}
		$field = "ib.*,date_format(ib.timeadd,'%Y') as bill_year,date_format(ib.timeadd,'%m') as bill_month";
		$list = M("instalment_bill as ib")
		->join("insurance_instalment as ii on ib.instalmentid=ii.id")
		->join("insurance_order as io on ib.order_id=io.id")
		->field($field)
		->where($where)
		->order($order_by)
		->select();
		//dump(M("instalment_bill as ib")->getLastSql());
		return $list;
	}
	//用户还款总账单、
	public function memberBill ($memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (empty($memberid)) {
			$this->err = "用户ID不能为空";
			return false;
		}
		$bill_list = M("instalment_bill as ib")
		->join("insurance_instalment as ii on ib.instalmentid=ii.id")
		->join("insurance_order as io on ib.order_id=io.id")
		->field("sum(ib.money) as pay_money,DATE_FORMAT(ib.timeadd,'%Y') as pay_year,DATE_FORMAT(ib.timeadd,'%m') as pay_month")
		->group("date_format(ib.timeadd,'%Y%m')")
		->order("ib.timeadd desc")
		->where("ib.status=1 and ib.bill_type=2 and io.memberid={$memberid}")
		->select();
		$list = [];
		foreach ($bill_list as $key => $value) {
			$year = $value['pay_year'];
			$month = $value['pay_month'];
			$pay_money = $value['pay_money'];
			if (empty($list[$year])) {
				$list[$year]['pay_year'] = $year;
			}
			$list[$year]['month'][$month]['month_name'] = $month;
			$list[$year]['month'][$month]['pay_money'] = $pay_money;
			$list[$year]['month'][$month]['status'] = ($this->instalmentOrderList(['memberid'=>$memberid,'status'=>0,'back_year'=>$year,'back_month'=>$month]))?0:1;
		}
		$tmp = [];
		foreach ($list as $key => $value) {
			
			$tmp_month = [];
			foreach ($value['month'] as $val) {
				$tmp_month[] = $val;
			}
			unset($value['month']);
			$tmp[] = array_merge($value,['month'=>$tmp_month]);

		}
		return $tmp?$tmp:['0'=>['pay_year'=>date("Y"),'month'=>[]]];
	}
	/*
	还款发送验证码
	$back_type:还款方式：1：本月全额；2：本月一期；3：下月全额：4：下月一期
	$instalmentid：分期ID；
	$memberid:用户ID；
	 */
	public function sendBackSms ($back_type,$instalmentid='',$memberid = '') {
		$memberid = $memberid?$memberid:$this->memberid;
		if (empty($memberid) || empty($back_type)) {
			$this->err = "用户ID或还款方式不能为空";
			return false;
		}
		$member_info = $this->getMemberInfo($memberid);
        if(false==$member_info)return false;
		if (in_array($back_type,[1,3])) {//全额还款
			$back_money = $this->totalBack($back_type,$memberid)['back_money'];		
		} elseif (in_array($back_type,[2,4])) {//还一期
			if (empty($instalmentid)) {
				$this->err = "分期ID不能为空";
				return false;
			}
			$back_info = $this->backOneInstalment($instalmentid);
			if (false == $this->instalmentIsAllowBack($back_type,$instalmentid))return false;
			$back_money = $back_info['money'];
		} else {
			$this->err = "还款类型错误";
			return false;
		}
		if (empty(S("back_sn_{$back_type}{$memberid}{$instalmentid}"))) S("back_sn_{$back_type}{$memberid}{$instalmentid}",time().rand(0000,9999).$back_type,30*60);
		$order_sn = S("back_sn_{$back_type}{$memberid}{$instalmentid}");
       	import("Think.ORG.Util.Baofu");
       	$log = $this->getLoggerObj();
       	$info = "车险分期还款发送验证码;{$this->back_type[$back_type]};分期【{$instalmentid}】;金额：{$back_money};宝付订单号：{$order_sn};状态：";
    	$baofu = new Baofu($memberid); 	
    	//认证预支付，发送验证码
        if(false==$baofu->prePay($order_sn,$back_money,"","【借吧-车险分期-还款-获取验证码】")){
                $this->err = "BAOFU_ERROR:".$baofu->getError();
                $log->info($info."失败；ERROR：".$baofu->getError());
                return false;
        }
        $log->info($info."成功");
		return true;
	}
	//还款
	//@param:$back_type:还款方式；
	//@param:$back_smscode:还款验证码
	//@param:$memberid:用户id
	//@instalmentid:分期id
	public function backMoney ($back_type,$back_smscode,$memberid = '',$instalmentid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (empty($back_type) || empty($memberid)) {
			$this->err = "还款类型或用户ID不能为空";
			return false;
		}
		$back_sn = S("back_sn_{$back_type}{$memberid}{$instalmentid}");
		if (empty($back_sn)) {
			$this->err = "验证码失效，请重新获取";
			return false;
		}
		if (empty($back_smscode)) {
			$this->err = "验证码不能为空";
			return false;
		}
		if (in_array($back_type,[1,3])) {//多期还款
			$back_info = $this->totalBack($back_type,$memberid);
			if ($back_info['is_allow_pay'] == 0) {
				$this->err = "暂未允许还款";
				return false;
			}
			foreach ($back_info['back_instalment'] as &$value) {
				$value['back_sn'] = $back_sn;
			}
			if (false == $this->multiBack($back_smscode,$back_info['back_instalment'],$memberid))return false;
		} elseif (in_array($back_type,[2,4])) {//单期还款
			if (empty($instalmentid)) {
				$this->err = "分期ID不能为空";
				return false;
			}
			$back_info = $this->backOneInstalment($instalmentid);
			if (false == $this->instalmentIsAllowBack($back_type,$instalmentid))return false;
			$back_info['back_sn'] = $back_sn;
			if (false == $this->singleBack($back_smscode,$back_info,$memberid)) return false;
		} else {
			$this->err = "还款方式错误";
			return false;
		}
		S("back_sn_{$back_type}{$memberid}{$instalmentid}",null);
		return '还款成功';
	}
	//多期还款
	//@param:$back_smscode:还款验证码
	//@param:$back_list:还款分期列表
	//@param:$memberid:用户id
	public function multiBack ($back_smscode,$back_list,$memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (empty($back_list)) {
			$this->err = "还款分期不能为空";
			return false;
		}
		if (empty($back_smscode)) {
			$this->err = "还款验证码不能为空";
			return false;
		}
		$log = $this->getLoggerObj();
		$info = "车险分期还款。方式：多期还款；宝付订单号：{$back_list[0]['back_sn']};状态：";
		try {
			// if (!D("Common")->inTrans()) {
			// 	D("Common")->startTrans();
			// 	$trans = true;
			// }
			D("Common")->startTrans();
			$trans = true;
			$instalment_updatelist = [];
			foreach ($back_list as $key=>$value) {
				$instalment_updatelist[$key]['id'] = $value['instalmentid'];
				$instalment_updatelist[$key]['status'] = 1;
				$instalment_updatelist[$key]['back_money'] = $value['money'];
				$instalment_updatelist[$key]['real_back_time'] = date("Y-m-d H:i:s");
				$instalment_updatelist[$key]['late_days'] = $value['late_days'];
				$instalment_updatelist[$key]['late_fee'] = $value['late_fee'];
				if (false == M("insurance_instalment")->save($instalment_updatelist[$key])) {
					throw new Exception("订单还款失败");
				}
			}
			
			if (false == M("instalment_bill")->addAll($back_list)) {
				throw new Exception("账单添加失败，请稍后再试");
			}
			//宝付支付
			import("Think.ORG.Util.Baofu");
			$baofu = new Baofu($memberid);
			if(false==($result = $baofu->prePaySubmit($back_smscode,"【借吧-车险-付款】"))){
         		 throw new Exception("BAOFU_ERROR:".$baofu->getError());
    		}
    		if ($trans) {
    			D("Common")->commit();
    		}
    		$log->info($info."成功");
    		return true;
		} catch (Exception $ex) {
			$this->err = $ex->getMessage();
			if ($trans) {
				D("Common")->rollback();
			}
			$log->info($info."失败；ERROR:".$ex->getMessage());
			return false;
		}
	}
	//单期还款
	//@param:$back_smscode:还款验证码
	//@param:$back_info:还款分期详情
	//@param:$memberid:用户id
	public function singleBack ($back_smscode,$back_info,$memberid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		$log = $this->getLoggerObj();
		$info = "车险分期还款。方式：单期【{$back_info['instalmentid']}】；金额：{$back_info['money']}；宝付订单号：{$back_info['back_sn']};结果:";
		try {
			// if (!D("Common")->inTrans()) {
			// 	D("Common")->startTrans();
			// 	$trans = true;
			// }
			D("Common")->startTrans();
			$trans = true;
			$update['id'] = $back_info['instalmentid'];
			$update['status'] = 1;
			$update['back_money'] = $back_info['money'];
			$update['real_back_time'] = date("Y-m-d H:i:s");
			$update['late_days'] = $back_info['late_days'];
			$update['late_fee'] = $back_info['late_fee'];
			if (false == M("insurance_instalment")->save($update)) {
				throw new Exception("还款失败，请稍后再试");	
			}
			if (false == M("instalment_bill")->add($back_info)) {
				throw new Exception("还款失败");	
			}
			//宝付支付
			import("Think.ORG.Util.Baofu");
			$baofu = new Baofu($memberid);
			if(false==($result = $baofu->prePaySubmit($back_smscode,"【借吧-车险-付款】"))){
         		 throw new Exception("BAOFU_ERROR:".$baofu->getError());
    		}
    		if ($trans) {
    			D("Common")->commit();
    		}
    		$log->info($info."成功");
    		return true;
		} catch (Exception $ex) {
			$this->err = $ex->getMessage();
			$log->info($info."失败");
			if ($trans) {
				D("Common")->rollback();
			}
			return false;
		}
	}
	//全额还款详情
	//@param:$back_type:还款类型：1、3
	//@param:$memberid:用户ＩＤ
	public function totalBack ($back_type,$memberid)
	{
		if (empty($memberid)) {
			$this->err = "用户ＩＤ不能为空";
			return false;
		}
		if ($back_type == 1) {//本月
			$bill_info = $this->outBillInfo($memberid);
		} elseif ($back_type == 3) {//提前
			$bill_info = $this->unoutBillInfo($memberid);
		}
		$pay_money = 0;
		$pay_instalment = $bill_info['unpay_instalment'];
		$bill_list = [];//分期账单列表
		foreach ($pay_instalment as $value) {
			if (false == $this->instalmentIsAllowBack($back_type,$value['id']))return false;
			if (false === ($back_info = $this->backOneInstalment($value['id'])))return false;
			$bill_list[] = $back_info;
			$pay_money += $back_info['money'];
		}
		return ['back_money'=>$pay_money,'back_instalment'=>$bill_list,'is_allow_pay'=>$bill_info['is_allow_pay']];
	}
	//还款一期分期的详情
	//@param:$instalmentid:分期ID
	public function backOneInstalment ($instalmentid)
	{
		if (empty($instalmentid)) {
			$this->err = "分期ID不能为空";
			return false;
		}
		if (false == $instalment_info = $this->instalmentOrderList(['instalmentid'=>$instalmentid])[0]) {
			$this->err = "该分期订单【{$instalmentid}】不存在";
			return false;
		}
		if ($instalment_info['status'] != 0) {
			$status = ['1'=>"已结清",'2'=>"已退保"];
			$this->err = "该分期订单【{$instalmentid}】{$status[$instalment_info['status']]}";
			return false;
		}
		$data['order_id'] = $instalment_info['order_id'];
		$data['instalmentid'] = $instalment_info['id'];
		$data['bill_sn'] = $data['order_id'].time().rand(000,999);
		$data['bill_type'] = 2;
		$data['money'] = $this->instalmentPay($instalment_info);//还款金额
		$data['status'] = 1;
		$data['remark'] = "借吧-车险-分期还款";
		$data['timeadd'] = date("Y-m-d H:i:s");
		$data['late_days'] = $instalment_info['period_late_days'];
		$data['late_fee'] = $instalment_info['pay_late_fee'];
		return $data;
	}
	//还款需求详情
	public function backInfo ($back_type,$memberid='',$instalmentid = '')
	{
		$memberid = $memberid?$memberid:$this->memberid;
		if (in_array($back_type,[1,3])) {
			$info = $this->totalBack($back_type,$memberid);
			return ['back_money'=>$info['back_money'],'is_allow_pay'=>$info['is_allow_pay']];
		} elseif (in_array($back_type,[2,4])) {
			if (empty($instalmentid)) {
				$this->err = "分期ID不能为空";
				return false;
			}
			$info = $this->backOneInstalment($instalmentid);
			if ($back_type == 4) {
				$is_allow_pay = ($this->instalmentIsAllowBack($back_type,$instalmentid) && ($this->outBillInfo($memberid)['unpayment_money'] == 0))?1:0;
			} else {
				$is_allow_pay = $this->instalmentIsAllowBack($back_type,$instalmentid)?1:0;
			}
			return ['back_money'=>$info['money'],'instalmentid'=>$info['instalmentid'],'is_allow_pay'=>$is_allow_pay];
		}
		$this->err = "还款类型不正确";
		return false;
	}
	//单期是否允许还款
	//@param:back_type:还款方式：2,4
	//@param:$instalmentid:分期ID
	public function instalmentIsAllowBack ($back_type,$instalmentid) 
	{
		if (empty($back_type)||empty($instalmentid)) {$this->err ="还款方式或分期ID错误"; return false;} 
		$back_info = $this->backOneInstalment($instalmentid);
		if (false == $back_info) {
			$this->err = '该分期不存在';
			return false;
		}
		if ($back_type == 4 && (false == $this->unoutBillInfo($this->memberid)['is_allow_pay'])) {
			$this->err = "本月尚未还清，不能提前还款";
			return false;
		}
		if ($back_info['money'] == 0) {
			$this->err = "还款金额错误";
			return false;
		}
		// if (false == $back_info || ($back_type == 4 && (false == $this->unoutBillInfo($memberid)['is_allow_pay'])) || $back_info['money'] == 0) {
		// 	$this->err = "该分期未处于未还款状态";
		// 	return false;
		// }
		return true;
	}
	//退保
	public function quitOrder ($instalmentid,$params = [])
	{
		if (empty($instalmentid)) {
			$this->err = "分期ID错误";
			return false;
		}
		if (false == ($instalment_info = $this->instalmentOrderList(['instalmentid'=>$instalmentid]))) {
			$this->err = "该车险分期不存在";
			return false;
		}
		$instalment_info = $instalment_info[0];
		if ($instalment_info['status'] != 0) {
			$status_name = ['1'=>'已结清','2'=>'已退保'];
			$this->err = "改分期{$status_name[$instalment_info['status']]}不可退保";
			return false;
		}
		if (false != $this->instalmentOrderList(['order_id'=>$instalment_info['order_id'],'status'=>2])) {
			$this->err = "该车险订单其他分期已退保，不能再次退保";
			return false;
		}
		$quit_list = $this->instalmentOrderList(['order_id'=>$instalment_info['order_id'],'status'=>0]);
		if (false == $quit_list) {
			$this->err = "改车险分期已全部结清，不可退保";
			return false;
		}
		try {
			D('Common')->startTrans();
			foreach ($quit_list as $quit) {
				$data = [
					'id'           =>  $quit['id'],
					'status'       =>  2,
					'quit_time'    =>  date("Y-m-d H:i:s"),
					'quit_money'   =>  $quit['pay_money'],
					'late_days'    =>  $quit['period_late_days'],
					'late_fee'     =>  $quit['pay_late_fee'],
				];
				if (false == M("insurance_instalment")->save($data)) {
					throw new Exception("退保失败，请稍后再试");					
				}
			}
			D("Common")->commit();
			return true;
		} catch (Exception $ex) {
			$this->err = $ex->getMessage();
			return false;
		}
	}
 	//车险订单详情
 	//@param:$order_id:订单ID
 	public function orderInfo ($order_id)
 	{
 		if (empty($order_id)) {
 			$this->err = "订单id错误【{__METHOD__}】";
 			return false;
 		}
 		if (false == ($info = M("insurance_order")->where("id='{$order_id}'")->find())) {
 			$this->err = "该车险订单不存在";
 			return false;
 		}
 		return $info;
 	}
 	public function getError ()
 	{
 		return $this->err;
 	}
 	//返回日志对象
    public function getLoggerObj($path = "instalment"){
        import('Think.ORG.Util.Logger');
        $log = Logger::getLogger($path, LOG_PATH);
        $log->info('-----------------');
        return $log;
    }
}
