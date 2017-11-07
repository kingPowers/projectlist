<?php
namespace manager\index\controller;

use manager\index\model\Loan;
use manager\index\model\Lender;
use manager\index\model\User;
use manager\index\model\UserStore;
use manager\index\model\CarReport;
use manager\index\model\LoanReview;
use manager\index\model\Order;
/**
* 
*/
class Riskcontrol extends Common
{
	public $base_title = [
		'loan_sn'       =>  "订单编号",
		'name'          =>  "门店",
		'names'         =>  "客户姓名",
		'certi_number'  =>  '身份证号',
        'car_plate'     =>  "车牌号",
		'product'       =>  "产品",
		'periodes'      =>  "期限",
		'money'         =>  "申请金额",
		'is_complete'   =>  "资料是否齐全",
		'status_name'   =>  "状态",
		'sales_man'     =>  "销售",
		'handle_man'    =>  "经手人",
		'timeadd'       =>  "进件时间",
		'lasttime'      =>  "更新时间",
		'operate'       =>  "操作",
	];
	public $loan;
	public function _initialize ()
	{
		$uid= User::getUid();
		$stores = UserStore::getStores($uid);
		$this->assign("store",$stores[0]);
		$loanid = $this->request->get("loanid");
		$this->loan = new Loan();
		if ($loanid) {//已填申请表信息
			$this->loan->where = ['id'=>$loanid];
			$info = $this->loan->loanList()[0];//dump($info);
			$this->assign("info",$info);
		}	
		$this->assign("key",$this->_key);
	}
	//分单管理
	public function distributeList ()
	{
		if ($this->request->post("is_rob") == 1) {//抢单
			$loanid = $this->request->post("loanid");
			$order = new Order();
			$result = $order->competeOrder($loanid);
			if ($result) {
				return $this->ajaxSuccess("抢单成功");
			} else {
				return $this->ajaxError($order->getError());
			}
		}
		$title = $this->getTitle(['handle_man']);
		$this->loan->where = array_merge((array)action("Storesystem/getParams"),['status'=>1,"process_status"=>3]);
		$distribute_list = $this->loan->loanList(['isPage'=>1]);//dump($distribute_list);
		$list = $this->handleData($distribute_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	public function distributeOrder ()
	{
		return $this->view->fetch();
	}
	//车辆评估管理
	public function carevaluateList ()
	{
		$title = $this->getTitle(['handle_man','certi_number','car_plate']);
		$this->loan->where = array_merge((array)action("Storesystem/getParams"),['status'=>1,"process_status"=>2]);
		$carevaluate_list = $this->loan->loanList(['isPage'=>1]);//dump($distribute_list);
		$list = $this->handleData($carevaluate_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	//报价页面
	public function quoteInfo ()
	{	
		if ($this->request->isAjax() && ($this->request->post('is_quote') == 1)) {
			$order = new Order();
			$loanid = $this->request->post("loanid");
			$result = $order->companyQuote($loanid,$this->request->post("quote_price"));
			if ($result) {
				return $this->ajaxSuccess("操作成功");
			} else {
				return $this->ajaxError($order->getError());
			}
		}
		$loanid = $this->request->get("loanid");
		$report_info = (new CarReport())->carReportOne($loanid);
		//dump($report_info);
		$report_controller = controller("Storesystem");
		$this->assign("car_traffic",$report_controller->car_traffic);
		$this->assign("eval_info",$report_info);
		return $this->view->fetch();
	}
	//提交报价
	public  function subQuote ()
	{
		$order = new Order();
		$loanid = $this->request->post("loanid");
		$result = $order->companyQuoteSubmit($loanid); 
		if ($result) {
			return $this->ajaxSuccess("操作成功");
		} else {
			return $this->ajaxError($order->getError());
		}
	}
	//初审
	public function firstreviewList ()
	{
		$title = $this->getTitle(['certi_number','car_plate']);
		$where = array_merge((array)action("Storesystem/getParams"),['status'=>1,"process_status"=>['in','10,5']]);
		$firstreview_list = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);//dump($distribute_list);
		$list = $this->handleData($firstreview_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	//初审评审表
	public function firstReview ()
	{
		if ($this->request->post("is_save") == 1) {		
			$order = new Order();
			$result = $order->firstReview($this->request->post('loanid'),$this->request->post());
			if ($result) {
				return $this->ajaxSuccess("操作成功");
			} else {
				return $this->ajaxError($order->getError());
			}						
		}
		$car_report = (new \manager\index\model\CarReport())->carReportOne($this->request->get("loanid"));
		//dump($car_report);
		
		$this->assign("car_info",$car_report);
		$this->assign("review_info",LoanReview::get(['loanid'=>$this->request->get("loanid")]));
		return $this->view->fetch();
	}
	public function review ()
	{
		if ($this->request->post("is_save") == 1) {
			$order = new Order();
			$result = $order->finalRepeat($this->request->post('loanid'),$this->request->post());
			if ($result) {
				return $this->ajaxSuccess("操作成功");
			} else {
				return $this->ajaxError($order->getError());
			}
		}
		$car_report = (new \manager\index\model\CarReport())->carReportOne($this->request->get("loanid"));
		//dump($car_report);
		$this->assign("car_info",$car_report);
		$this->assign("review_info",LoanReview::get(['loanid'=>$this->request->get("loanid")]));
		return $this->view->fetch();
	}
	//终审
	public function finaljudgeList ()
	{
		$title = $this->getTitle(['certi_number','car_plate']);
		$this->loan->where = array_merge((array)action("Storesystem/getParams"),['status'=>1,"process_status"=>6]);
		$finaljudge_list = $this->loan->loanList(['isPage'=>1]);//dump($distribute_list);
		$list = $this->handleData($finaljudge_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	//终审操作
	public function finalOperate ()
	{
		if ($this->request->post("is_pass") == 1) {
			$result = false;	
		}
		if ($this->request->post("is_refuse") == 1) {//终审拒单
			$order = new Order();
			$result = $order->lenderDeny($this->request->post("loanid"),['remark'=>"终审拒单:".$this->request->post('final_remark')]);
		}
		if ($result) {
			return $this->ajaxSuccess("操作成功");
		} else {
			return $this->ajaxError(json_encode($_POST));
		}
	}
	public function creditEval ()
	{
		return $this->view->fetch();
	}
	//复议管理
	public function reviewList ()
	{
		if ($this->request->post("is_review") == 1) {
			$order = new Order();
			$loanid = $this->request->post("loanid");
			$data = [
				"repeat_status" => $this->request->post("repeat_status"),
				'repeat_quota'  => $this->request->post("review_money"),
				'repeat_result' => $this->request->post("repeat_result"),
				'loanid'        => $this->request->post("loanid"),
			];
			$result = $order->doubleFinalRepeat($loanid,$data);
			if ($result) {
				return $this->ajaxSuccess("操作成功");
 			} else {
 				return $this->ajaxError($order->getError());
 			}
		}
		$title = $this->getTitle(['certi_number','car_plate']);
		$this->loan->where = array_merge((array)action("Storesystem/getParams"),['status'=>1,"process_status"=>['in',8]]);
		$review_list = $this->loan->loanList(['isPage'=>1]);//dump($distribute_list);
		$list = $this->handleData($review_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	//历史订单
	public function historyList ()
	{
		$title = $this->getTitle(['certi_number','car_plate']);
		if ($this->request->get('process') == 1) {
			$where = ['status' =>3];
		} elseif ($this->request->get('process') == 2) {
			$where = ['status' => 1,'process_status' => ['in','8,9']];
		} else {
			$where = ['status' => ['in','1,3'],'process_status' => ['in','8,9']];
		}
		$this->loan->where = array_merge((array)action("Storesystem/getParams"),$where);
		$review_list = $this->loan->loanList(['isPage'=>1]);//dump($distribute_list);
		$list = $this->handleData($review_list,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		$this->assign("status_process",['全部' => 0,'已拒单'=> 1,'已通过' => 2]);
		return $this->view->fetch();
	}
	//复议评估表
	public function reviewTable ()
	{
		$car_report = (new \manager\index\model\CarReport())->carReportOne($this->request->get("loanid"));
		//dump($car_report);
		$this->assign("car_info",$car_report);
		$this->assign("review_info",LoanReview::get(['loanid'=>$this->request->get("loanid")]));
		return $this->view->fetch();
	}
	//列表数据处理
	public function handleData ($data,$title)
	{
		if (empty($data) || empty($title)) return [];
		$return = [];
		foreach ($data as $dk => $dv) {
			foreach ($title as $tk => $tv) {
				if ($tk == "operate") {
					$return[$dk][$tk] = $this->getOperate($dv);
				} elseif ($tk == "is_complete") {
					$return[$dk][$tk] = (count(explode(",",$dv["material"]))==12)?"是":"否";
                } elseif ($tk == "handle_man") {
                	$user = User::get($dv['pend_uid']);
                	$return[$dk][$tk] = $user->names;
                } else {
					$return[$dk][$tk] = $dv[$tk];
				}
			}
			$return[$dk]['other']['id'] = $dv['loanid'];
		}
		return $return;
	}
	//获取操作按钮
	public function getOperate ($params)
	{
		if (($params['status'] == 1) && ($params['process_status'] == 2)) {//分单管理
			$operate_btn = ['quote'=>'报价','subQuote'=>'提交','edit' => '查看资料'];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 3) {//反馈管理
			$operate_btn = ['robOrder'=>'抢单','edit'=>"查看"];
		} elseif (($params['status'] == 1) && (in_array($params['process_status'],[10,5]))) {//初审管理
			$operate_btn = ['submit'=>"评审",'show'=>"查看资料"];         
		} elseif (($params['status'] == 1) && (in_array($params['process_status'],[6]))) {//终审
			$operate_btn = ['creditEval'=>'评审','edit'=>'查看'];
		} elseif (($params['status'] == 1) && (in_array($params['process_status'],[8]))) {//复议管理
			$operate_btn = ['reviewEval'=>'复议评估','edit'=>'查看'];
		} elseif (($params['status'] == 3) || (in_array($params['process_status'],[9,11]))) {
			$operate_btn = ['show'=>"查看资料"];
		} else {
			$operate_btn = [];
		}
		return $operate_btn;	
	}
}