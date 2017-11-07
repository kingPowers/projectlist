<?php 
namespace manager\index\controller;

use manager\index\model\ProductManager;
use manager\index\model\Loan;
use manager\index\model\Order;
/**
* 稽核管理
*/
class Examine extends Common
{
	public $base_title = [
		'loan_sn'      =>  "订单编号",
		'name'         =>  "门店",
		'names'        =>  "客户姓名",
        'car_plate'    =>  "车牌号",
		'product'      =>  "产品",
		'periodes'     =>  "期限",
		'money'        =>  "申请金额",
		'risk_quota'   =>  "风控额度",
		'is_complete'  =>  "资料是否齐全",
		'status_name'  =>  "状态",
		'sales_man'    =>  "销售",
		//"product_ensure"=> "产品确认",
		'timeadd'      =>  "进件时间",
		'lasttime'     =>  "更新时间",
		'operate'      =>  "操作",
	];
	public function checkList ()
	{
		$where = array_merge((array)$this->getParams(),['status' => 1,'process_status' => ['in','12,13']]);
    	$responseList = $this->getLoanList(['where' => $where,'is_apply_where' => 1]);
    	$title = $this->base_title;;
    	$list = $this->handleData($responseList,$title);
    	$this->assign("title",$title);
    	$this->assign("list",$list);
    	return $this->view->fetch();
	}
	public function selectProduct()
	{
		if ($this->request->post("is_selected") == 1) {
			$order = new Order();
			$loanid = $this->request->post('loanid');
			$product_id = $this->request->post("productid");
			if (false == $order->passToProduct($loanid,["product_id" => $product_id])) {
				return $this->ajaxError($order->getError());
			}
			return $this->ajaxSuccess("产品确认成功");
		}
		$product = new ProductManager();
		$product->where['status'] = 1;
		$productList = $product->productList();
		$this->assign("list",$productList);//dump($productList);
		$loan = Loan::get($this->request->request("loanid"));
		$this->assign("store_pro",$loan->product);
		return $this->view->fetch();
	}
	//下载合同模板
	public function downloadTemplate ()
	{
			$order = new Order();
			$order->downLoadContactTemplete($this->request->get("loanid"));
		// if ($this->request->post("is_template") == 1) {
		// 	$order = new Order();
		// 	if (false == $order->downLoadContactTemplete($this->request->post("loanid"))) {
		// 		return $this->ajaxError($order->getError());
		// 	} 
		// 	return $this->ajaxSuccess("下载成功");
		// }
	}
	//下载合同
	public function downloadContract ()
	{
		$order = new Order();
		$order->downloadContact($this->request->get("loanid"));
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
				} elseif($tk=="product_ensure"){
                	$return[$dk][$tk] = $this->getProduct($dv);        		
                } elseif ($tk == "is_complete") {
					$return[$dk][$tk] = (count(explode(",",$dv["material"]))==12)?"是":"否";
                } elseif ($tk == 'risk_quota') {
                	$return[$dk][$tk] = $this->getRiskQuota($dv);
                } else {
					$return[$dk][$tk] = $dv[$tk];
				}
			}
			$return[$dk]['other']['id'] = $dv['loanid'];
		}
		return $return;
	}
	//获取订单确认产品
	public function getProduct ($loanInfo)
	{
		return $loanInfo['product'];
	}
	//获取订单风控额度
	public function getRiskQuota ($loanInfo)
	{
		if (empty($loanInfo)) return 0;
		$review_info = \manager\index\model\LoanReview::get(['loanid'=>$loanInfo['loanid']]);
		$info = $review_info->getData();
		if (!empty($info['repeat_quota'])) {
			return $info['repeat_quota'];
		} elseif (!empty($info['final_quota'])) {
			return $info['final_quota'];
		} else {
			return $info['first_quota'];
		}
	}
	//获取操作按钮
	public function getOperate ($params)
	{
		if (($params['status'] == 1) && ($params['process_status'] == 12)) {//待确认产品
			$operate_btn = ["selectProduct" => "选择产品"];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 13) {//待签合同
			$operate_btn = ["downloadTemplate" => "下载合同模板"];
			if (in_array("21",explode(",",$params['material'])))$operate_btn['downloadContract'] = "下载合同";
		} else {
			$operate_btn = [];
		}
		return $operate_btn;	
	}
	public function getParams ()
	{
		$k = $this->request->get("k");
		$v = $this->request->get("v");
		$starttime = $this->request->get("starttime");
		$endtime = $this->request->get("endtime");
		if ($starttime)$where['startTime'] = $starttime;
		if ($endtime)$where['endTime'] = $endtime;
		if ($v) {
			switch ($k) {
				case 'names':
					$where['names'] = $v;
					break;
				case 'store_name':
					$where['storeName'] = $v;
					break;
				case 'mobile':
					$where['mobile'] = $v;
					break;
			}
		}
		return $where;	
	}
}