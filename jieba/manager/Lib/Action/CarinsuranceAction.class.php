<?php
/**
* 车险处理
*/
class CarinsuranceAction extends CommonAction
{
	public $base_title = [//后台、保险人员所用列表
		'store_name'         =>    '申请门店',
		'order_sn'             =>    "订单编号",
		'car_type'           =>    '车辆类型',
		'names'              =>    '姓名',
		'mobile'             =>    '手机号',
		'certiNumber'        =>    '身份证号',
		'rec_mobile'		 =>    '推荐人手机号',
		'receive_money'      =>    '应收金额',
		// 'force_money'        =>    '强制险',
		// 'business_money'     =>    '商业险',
		// 'taxation_rate'      =>    '税费利率',
		// 'profit_point'       =>    "返点",
		// 'insurance_money'    =>    "实收金额",
		'money_info'         =>    '金额详情',
		'insurance_type'     =>    '险种',
		'identity_card'      =>    '身份证/营业执照',
		'drive_license_pic'  =>    '行驶证',
		'timeadd'            =>    '申请时间',
		'statusName'         =>    "状态",
		'uploadPic'          =>    "下载图片",
		'operate'            =>    "操作",
	];
	public $instalment_title = [
		'store_name'         =>    '申请门店',
		'instalment_sn'      =>    "订单编号",
		'car_type'           =>    '车辆类型',
		'names'              =>    '姓名',
		'mobile'             =>    '手机号',
		'certiNumber'        =>    '身份证号',
		'insurance_type'     =>    '险种',
		'need_paymoney'      =>    '应还金额',
		'initial_money'      =>	   '首付',
		'timeadd'            =>    '成单时间',
		'back_money'		 =>	   '当期还款额',
		'back_time'			 =>    '应还款日期',
		'real_back_time'     =>    '实际还款日期',
		'period_late_days'   =>    '逾期',
		'pay_num'            =>    "状态",
		'operate'            =>    "操作",
	];
	public $quit_title = [
		'store_name'         =>    '申请门店',
		'instalment_sn'      =>    "订单编号",
		'car_type'           =>    '车辆类型',
		'names'              =>    '姓名',
		'mobile'             =>    '手机号',
		'certiNumber'        =>    '身份证号',
		'insurance_type'     =>    '险种',
		'money'              =>    '金额',
		'initial_money'      =>	   '首付',
		'pay_money'		 =>	   '每期还款额',
		'quit_money'		 =>    '退保金额',
		'back_time'          =>    '最后还款日',
		'quit_time'          =>    '退保日期',
		'timeadd'            =>    "成单日期",
		'status_name'        =>    "逾期",
	];
	public $unset_title = ['money_info','operate']; //门店显示的所需删除的列表
	public $keys = [
		'store'      =>  '门店',
		'names'      =>  "姓名",
		'mobile'     =>  '手机号码',
	];
	public $group_list = ['customer_userid'=>'2','insurance_userid'=>'25','store_userid'=>'3','other_userid'=>'999'];//角色的分组ID
	public $order = '';//订单处理类
	public $groupid = '';//后台用户类型
	public $store_name = "";//登陆门店人员的门店名称
	public $page_num = 10;//每页显示条数
	public $title = '';//最终列表标题
	public $car_type = ['1'=>'公司车','2'=>'个人车'];//车辆类型
	public function _initialize()
	{
		$this->getGroupidList();//设置分组列表
		$this->groupid = $_SESSION['user']['groupid'];//用户分组id
		//设定表格列表标题
		if ($this->groupid == $this->group_list['store_userid']) {
			$this->title = $this->getStoreTitle($this->base_title,$this->unset_title);
			$this->store_name = $this->getStoreName($_SESSION['user']['username']);
		} else {
			$this->title = $this->base_title;
		}
		//拒单列表去掉'操作'标题
		if (strtolower(ACTION_NAME) == 'refuselist') {
			unset($this->title['operate']);
			$this->title['deny_message'] = '拒单原因';
		} elseif (strtolower(ACTION_NAME) == 'fullpaymentlist') {
			unset($this->title['operate']);
		}
		import("Think.ORG.Util.InsuranceOrder");
		$this->order = new InsuranceOrder();
		$this->assign("keys",$this->keys);
		$this->assign('list_title',$this->title);
	}
	/*
	获取数据库管理员分组的角色id列表
	 */
	public function getGroupidList()
	{
		$group_name = ['customer_userid'=>'客服人员','store_userid'=>'门店经理','insurance_userid'=>'保险'];
		foreach ($group_name as $k => $val) {
			if (!empty($this->group_list[$k])) {
				$this->group_list[$k] = M('auth_group')->where(['title'=>['like',"%{$val}%"]])->getField('id');
			}			
		}
	}
	//申请中列表
	public function applyList()
	{
  		$return = $this->getList(1,"1,2");
  		$params = $return['params'];
  		$order_list = $return['list'];
 		$this->setPage("/Carinsurance/applyList{$params}/p/*.html");
		$this->assign("list",$order_list);
		$this->display();
	}
	//金额详情
	public function moneyInfo()
	{
		$orderid = $this->_get("id","intval");
		$info = $this->order->orderInfo($orderid);
		$this->assign("info",$info);
		$this->display();
	}
	//订单详情
	public function detail()
	{
		$orderid = $this->_get("id","intval");
		$info = $this->order->orderInfo($orderid);
		$this->assign("info",$info);//dump($info);
		$this->display();
	}
	//客服提交订单
	public function subOrder()
	{
		if ($_POST && ($_POST['is_sub'] == 1)) {
			$result = $this->order->editToSubmit($this->_post("orderid",'intval'));
			if (false == $result) {
				$this->ajaxError($this->order->getError().$_POST['orderid']);
			} else {
				$this->ajaxSuccess("提交成功");
			}
		}
	}
	//编辑订单
	public function edit()
	{
		if ($_POST && ($_POST['is_edit'] == 1)) {

			$orderid = $this->_post("orderid",'intval');
			$result = $this->order->editToPay($orderid,$_POST);
			if ($result == false) {
				$this->ajaxError($this->order->getError());
			} else {
				$this->ajaxSuccess("提交成功");
			}
		}
		$orderid = $this->_get("id","intval");
		$order_info = $this->order->orderInfo($orderid);
		//dump($order_info);
		$this->assign("info",$order_info);
		$this->display();
	}
	//上传报价
	public function uploadPrice()
	{
		if ($_POST && ($_POST['is_upload'] == 1)) {
			$res = $this->order->uploadPricePic($this->_post("orderid",'intval'));
			if ($res == false) {
				$this->ajaxError($this->order->getError());
			} else {
				$this->ajaxSuccess("上传成功");
			}
		}
		$orderid = $this->_get("id","intval");
		$info = $this->order->orderInfo($orderid);
		$this->assign("info",$info);
		$this->display();
	}
	//拒单及拒单原因
	public function refuse()
	{
		if ($_POST && ($_POST['is_refuse'] == 1)) $this->addRefuse();
		$this->display();
	}
	//提交拒单原因
	public function addRefuse()
	{
		$remark = $this->_post("remark","trim");
		$orderid = $this->_post("orderid","intval");
		if ($this->groupid == $this->group_list['customer_userid']) {
			//客服拒单
			$type = 2;
		} elseif ($this->groupid == $this->group_list['insurance_userid']) {
			$type = 3;
		}
		if (empty($type)) {
			$this->ajaxError("您无拒单权限");
		}
		$params['message'] = $remark;
		$res = $this->order->editToDeny($orderid,$type,$params);
		if ($res == false) {
			$this->ajaxError($this->order->getError());
		}
		$this->ajaxSuccess("拒单成功");
	} 
	//已接单列表
	public function receiveList()
	{
		$return = $this->getList(1,"3,4");
  		$params = $return['params'];
  		$order_list = $return['list'];
 		$this->setPage("/Carinsurance/receiveList{$params}/p/*.html");
		$this->assign("list",$order_list);
		$this->display();
	}
	//确认付款
	public function ensurePayment()
	{
		if ($_POST && ($_POST['is_ensure'] == 1)) {
			$orderid = $this->_post("orderid","intval");
			$res = $this->order->editToBill($orderid);
			if ($res == false) {
				$this->ajaxError($this->order->getError());
			}
			$this->ajaxSuccess("操作成功");
		}
	}
	//上传保单页面
	public function guarantee()
	{
		if ($_POST && ($_POST['is_upload'] == 1)) {
			$data['force_bill_sn'] = $this->_post("force_bill_sn","trim");
			$data['business_bill_sn'] = $this->_post("business_bill_sn","trim");
			$res = $this->order->editToSuccess($this->_post("orderid",'intval'),$data);
			if ($res == false) {
				$this->ajaxError($this->order->getError());
			} else {
				$this->ajaxSuccess("上传成功");
			}
		}
		$orderid = $this->_get("id","intval");
		$info = $this->order->orderInfo($orderid);
		$this->assign("info",$info);
		$this->display();
	}
	//成单列表
	public function finishList()
	{
		$return = $this->getList(2,'5');
  		$params = $return['params'];
  		$order_list = $return['list'];
 		$this->setPage("/Carinsurance/finishList{$params}/p/*.html");
		$this->assign("list",$order_list);
		$this->display();
	}
	//保单信息
	public function guaranteeInfo()
	{
		$orderid = $this->_get("id","intval");
		$info = $this->order->orderInfo($orderid);//dump($info);
		$this->assign("info",$info);
		$this->display();
	}
	//拒单列表
	public function refuseList()
	{
		$return = $this->getList(3,'');
  		$params = $return['params'];
  		$order_list = $return['list'];
 		$this->setPage("/Carinsurance/refuseList{$params}/p/*.html");
		$this->assign("list",$order_list);
		$this->display();
	}
	//保险分期列表
	public function instalmentList ()
	{
		import("Think.ORG.Util.InsuranceInstalment");
		$return = $this->getParams();
		$params = $return['params'];
		$where = $return['where'];
		$page = $_REQUEST['p']?$_REQUEST['p']:1;
		$instalment = new InsuranceInstalment();
		$where['status'] = ['in','0,1'];//dump($where);
		$be_list = $instalment->instalmentOrderList($where,'',$page,$this->page_num);
		$this->title = $this->instalment_title;
		$af_list = $this->handleData($be_list['list']);//dump($be_list);
		$count = $be_list['count'];
		$this->page['count'] = $count;
        $this->page['no'] = $page;
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Carinsurance/instalmentList{$params}/p/*.html");
		$this->assign("list_title",$this->title);
		$this->assign("list",$af_list);
		$this->display();
	}
	//退保
	public function quitOrder ()
	{
		if ($_POST && ($_POST['is_quit'] == 1)) {
			$instalmentid = $this->_post("instalmentid");
			import("Think.ORG.Util.InsuranceInstalment");
			$instalment = new InsuranceInstalment();
			$result = $instalment->quitOrder($instalmentid);
			if ($result) {
				$this->ajaxSuccess("退保成功",$result);
			} else {
				$this->ajaxError($instalment->getError());
			}
		}
	}
	//分期退保列表
	public function instalmentQuitList ()
	{
		import("Think.ORG.Util.InsuranceInstalment");
		$return = $this->getParams();
		$params = $return['params'];
		$where = $return['where'];
		$page = $_REQUEST['p']?$_REQUEST['p']:1;
		$instalment = new InsuranceInstalment();
		$where['status'] = 2;
		$be_list = $instalment->instalmentOrderList($where,'',$page,$this->page_num);
		$this->title = $this->quit_title;
		$af_list = $this->handleData($be_list['list']);//dump($be_list);
		$count = $be_list['count'];
		$this->page['count'] = $count;
        $this->page['no'] = $page;
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Carinsurance/instalmentQuitList{$params}/p/*.html");
		$this->assign("list_title",$this->title);
		$this->assign("list",$af_list);
		$this->display();
	}
	//全额还款列表
	public function fullPaymentList ()
	{
		$return = $this->getList(4,'');
  		$params = $return['params'];
  		$order_list = $return['list'];
 		$this->setPage("/Carinsurance/fullPaymentList{$params}/p/*.html");
		$this->assign("list",$order_list);
		$this->display();
	}
	//下载图片压缩包
	public function uploadPic()
	{
		if ($_POST && ($_POST['is_upload_pic'] == 1)) {
			$res = $this->order->uploadZip($this->_post("orderid",'intval'));
			if ($res == false) {
				$this->ajaxError($this->order->getError());
			}
			$this->ajaxSuccess("成功",$res);
		}
	}
	//根据状态获取列表
	public function getList($status,$status_process = '')
	{
		$return = $this->getParams();
		$params = $return['params'];
		$order_params['where'] = $return['where'];
		if ($status == 4) {//全额还款
			$order_params['where']['status'] = 2;
			$order_params['where']['pay_type'] = 1;
		} else {
			$order_params['where']['status'] = $status;
		}		
		if ($status_process) {
			$order_params['where']['status_process'] = ['in',$status_process];
		}		
		if ($this->store_name) {
			$order_params['where']['store_name'] = $this->store_name;
		}
		$page = $_REQUEST['p']?$_REQUEST['p']:1;
		//dump($order_params);
		$list = $this->order->orderList($order_params,$page,$this->page_num);
		//dump($list);
		$order_list = $this->handleData($list['list']);
		$count = $list['count'];
		$this->page['count'] = $count;
        $this->page['no'] = $page;
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        return ['list'=>$order_list,"params"=>$params];
	}
	public function getParams()
	{
		$key = $this->_get("k",'trim');
		$value = $this->_get("v","trim");
		$starttime = $this->_get("starttime","trim");
		$endtime = $this->_get("endtime","trim");
		$where = [];$params = '';
		switch ($key) {
			case 'store':
				  $where['store_name'] = $value;
				  $params .= "/k/store/v/{$value}";
				  break;
			case 'names':
				  $where['names'] = $value;
				  $params .= "/k/names/v/{$value}";
				  break;
			case 'mobile':
				  $where['mobile'] = $value;
				  $params .= "/k/mobile/v/{$value}";
				  break;
		}
	    if ($starttime) {
	    	$where['startTime'] = $starttime;
	    	$params .= "/starttime/{$starttime}";
	    }
	    if ($endtime) {
	    	$where['endTime'] = $endtime;
	    	$params .= "/endtime/{$endtime}";
	    }
	    return ['where' => $where,"params" => $params];
	}
	/*获取门店人员门店名称
	$username:门店人员用户账号
	*/
	public function getStoreName($username)
	{
		if (empty($username)) return '';
		$where = [
			'username' => $username,
			'type'     => 2,
			'status'   => 1,
		];
		return M('store')->where($where)->getField("name");
	}
	/*处理列表数据
	$data:需要处理的数据列表
	*/
	public function handleData($data)
	{
		if (empty($data)) return [];
		$return = [];
		foreach ($data as $k => $value) {
			foreach ($this->title as $key => $val) {
				if ($key == 'identity_card') {//确定身份验证图片
					$return[$k][$key] = $this->identity_card($value);
				} elseif ($key == 'operate') {//确定操作按钮
					$return[$k][$key] = $this->getOperate($value);
				} elseif ($key == 'car_type') {//确定车辆类型
					$return[$k][$key] = $this->car_type[$value[$key]];
				} elseif (($key == 'taxation_rate') || ($key == 'profit_point')) {
					$return[$k][$key] = $value[$key] . "%";
				} elseif ($key == 'receive_money') {
					$return[$k][$key] = (float)($value['force_money'] + $value['business_money']);
				} elseif ($key == 'money_info') {//查看金额详情按钮
					$return[$k][$key] = "<a onclick='moneyInfo(" . $value['id'] . ")'>查看</a>";
				} elseif ($key == 'uploadPic') {
					$return[$k][$key] = "<a onclick='uploadPicZip(" . $value['id'] . ")'>下载</a>";
				} elseif ($key == 'mobile' && ($this->groupid == $this->group_list['insurance_userid']) && $value["status_process"]<4) {
					$return[$k][$key] = substr($value[$key], 0,-4) . str_repeat("*", 4);
				} elseif ($key == "need_paymoney") {
					$return[$k][$key] = $value['pay_money'] + $value['late_fees'] + $value['period_fee'];
				} elseif ($key == 'pay_num') {
					$return[$k][$key] = "已还".($value['pay_num']?$value['pay_num']:0)."期";
				} elseif ($key == 'insurance_type') {
					$return[$k][$key] = str_replace(',', '<br/>', $value['insurance_type']);
				} else {
					$return[$k][$key] = $value[$key];
				}	
			}
			//需要的其他数据
			$return[$k]['other']['id'] = $value['id'];
		}
		return $return;
	}
	/*
	获取当前订单的身份验证图片
	$info:当前订单的详情
	返回图片的URL
	 */
	public function identity_card($info)
	{
		if (!in_array($info['car_type'],['1','2'])) return '';
		return ($info['car_type'] == '1')?$info['service_license_pic']:$info['certinumber_pic'];
	}
	/*
	获取处理订单的按钮
	$parmas:条件
	 */
	public function getOperate($params)
	{
		$operate_status = $params['status_process'];
		$status = 0;
		if ($params['instalment_sn']) {//区分保险订单和分期订单
			$instalment_status = $params['status'];
		} else {
			$status = $params['status'];
		}
		if (empty($operate_status)) return [];
		$operate_btn = [];
		//保险人员处理按钮
		if ($this->groupid == $this->group_list['insurance_userid']) {
			if (($status == 1) && ($operate_status == 2)) {
				$operate_btn['edit'] = "编辑";
				$operate_btn['uploadPrice'] = "上传报价";
				$operate_btn['refuse'] = "拒单";
				$operate_btn['detail'] = "详情";
			} elseif (($status == 1) && ($operate_status == 4)) {
				$operate_btn['uploadGuarantee'] = "上传保单";
				$operate_btn['detail'] = "详情";
			} elseif (($status == 1) && ($operate_status == 1 || $operate_status == 3)) {
				$operate_btn['detail'] = "详情";
			}
		} elseif ($this->groupid == $this->group_list['customer_userid']) {
			//客服人员处理按钮
			if (($status == 1) && ($operate_status == 1)) {
				$operate_btn['subOrder'] = "提交";
				$operate_btn['refuse'] = "拒单";
			} elseif (($status == 1) && ($operate_status == 3)) {
				$operate_btn['refuse'] = "拒单";
			} elseif ($status == 2) {
				$operate_btn['guaranteeInfo'] = "查看保单";
			} elseif ($operate_status == 4 && ($instalment_status == 0) && ($params['is_quit'] == 0) && (strtolower(ACTION_NAME) == 'instalmentlist')) {//分期退保
				$operate_btn['quitInsurance'] = "退保";
			}
		} else {//其他人员
			$operate_btn = [];
		}
		return $operate_btn;	
	}
	/*获取门店显示列表
	 $base_title:全部显示的列表
	 $unset_title:需要删除的列表
	*/
	public function getStoreTitle($base_title,$unset_title)
	{
		foreach ($base_title as $key => &$value) {
			foreach ($unset_title as $val) {
				if ($key == $val) {
					unset($base_title[$key]);
				}
			}
		}
		return $base_title;
	}
}