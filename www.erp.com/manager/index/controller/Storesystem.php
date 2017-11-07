<?php
namespace manager\index\controller;

use manager\index\model\Loan;
use manager\index\model\UserStore;
use manager\index\model\User;
use manager\index\model\CarReport;
use manager\index\model\Lender;
use manager\index\model\Order;
/**
*   
*/
class Storesystem extends Common
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
		'material_list'=>  "已提交资料",
		'sales_man'    =>  "销售",
		'timeadd'      =>  "进件时间",
		'lasttime'     =>  "更新时间",
		'message'	   =>  "拒单原因",
		'operate'      =>  "操作",
	];
	public $base_table = [
		'1'     =>    ["title"=>"申请表<span style='color:red;'> * </span>",'url'=>"/Storesystem/applyTable.html?loanid="],
		'2'      =>    ["title"=>"身份证<span style='color:red;'> * </span>",'url'=>"/Image/cardphoto.html?loanid="],
		'3'      =>    ["title"=>"银行卡",'url'=>"/Image/bankCard.html?loanid="],
		'4'      =>    ["title"=>"流水",'url'=>"/Image/accountStatement.html?loanid="],
		'5'      =>    ["title"=>"征信",'url'=>"/Image/creditReport.html?loanid="],
		'6'      =>    ["title"=>"话单<span style='color:red;'> * </span>",'url'=>"/Image/phoneBill.html?loanid="],
		'7'      =>    ["title"=>"网查信息<span style='color:red;'> * </span>",'url'=>"/Image/netInfo.html?loanid="],
		'8'      =>    ["title"=>"生活照合影",'url'=>"/Image/lifePhoto.html?loanid="],
		'9'      =>    ["title"=>"资产信息",'url'=>"/Image/propertyInfo.html?loanid="],
		'10'      =>    ["title"=>"面审表<span style='color:red;'> * </span>",'url'=>"/Storesystem/interviewTable.html?loanid="],
		'11'      =>    ["title"=>"其他类",'url'=>"/Image/other.html?loanid="],
	];
	public $car_table = [
		'12'      =>    ["title"=>"评估表<span style='color:red;'> * </span>",'url'=>"/Storesystem/carevalTable.html?loanid="],
		'13'      =>    ["title"=>"合影",'url'=>"/Image/groupPhoto.html?loanid="],
		'14'      =>    ["title"=>"行驶证<span style='color:red;'> * </span>",'url'=>"/Image/drivingLicense.html?loanid="],
		'15'     =>    ["title"=>"驾驶证",'url'=>"/Image/driver.html?loanid="],
		'16'     =>    ["title"=>"车辆登记证<span style='color:red;'> * </span>",'url'=>"/Image/vehicleRegi.html?loanid="],
		'17'     =>    ["title"=>"保险及理赔查询",'url'=>"/Image/insurance.html?loanid="],
		'18'     =>    ["title"=>"车辆照片",'url'=>"/Image/carPhotos.html?loanid="],
		'19'     =>    ["title"=>"违章查询<span style='color:red;'> * </span>",'url'=>"/Image/carnetinfo.html?loanid="],
		'20'     =>    ["title"=>"钥匙",'url'=>"/Image/key.html?loanid="],
		'21'     =>    ["title"=>"合同照片",'url'=>"/Image/contact.html?loanid="],
	];
	public $car_traffic = [
		[
			"car_traffic1"  =>  ['title'=>"是否有结构性事故",'sure'=>"有","deny"=>"无"],
			"car_traffic2"  =>  ['title'=>"是否有一般性事故",'sure'=>"有","deny"=>"无"],
			"car_traffic3"  =>  ['title'=>"是否有泡水",'sure'=>"有","deny"=>"无"],
		], 
		[
			"car_traffic4"  =>  ['title'=>"是否有火烧",'sure'=>"有","deny"=>"无"],
			"car_traffic5"  =>  ['title'=>"是否有调表嫌疑",'sure'=>"有","deny"=>"无"],
			"car_traffic6"  =>  ['title'=>"液体有无泄漏",'sure'=>"是","deny"=>"否"],
		], 
		[
			"car_traffic7"  =>  ['title'=>"是否有改色",'sure'=>"有","deny"=>"无"],
			"car_traffic8"  =>  ['title'=>"是否有改装",'sure'=>"有","deny"=>"无"],
			"car_traffic9"  =>  ['title'=>"是否有限制交易",'sure'=>"有","deny"=>"无"],
		],
		[
			"car_traffic10"  =>  ['title'=>"VIN钢印是否原厂",'sure'=>"是","deny"=>"重刻"],
			"car_traffic11"  =>  ['title'=>"前档玻璃是否原厂",'sure'=>"有","deny"=>"无"],
			"car_traffic12"  =>  ['title'=>"后档玻璃是否原厂",'sure'=>"有","deny"=>"无"],
		],
	];
	public $_key = ['names'=>"姓名","mobile"=>"客户手机号","store_name"=>"门店"];
	public $loan;
	function _initialize()
	{
		$uid= User::getUid();
		$stores = UserStore::getStores($uid);
		$this->assign("store",$stores[0]);
		$loanid = $this->request->get("loanid");
		$this->loan = new Loan();
		if ($loanid) {//已填申请表信息
			$this->loan->where = ['id'=>$loanid];
			$info = $this->loan->loanList()[0];//dump($info);
			if ($this->request->request("get_data") ==1 )unset($info['loanid']);//获取用于编辑的资料，不修改原订单
			$this->assign("material",explode(",",$info['material']));
			$this->assign("info",$info);
		}	
		$this->assign("key",$this->_key);
	}
	function applyList ()
	{   
		$title = $this->getTitle(['material_list','message','status_name','risk_quote']);
		$where = array_merge(['status'=>1,'process_status'=>1],(array)$this->getParams());//dump($this->loan->where);
		$loanList = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);//dump($loanList);
		$list = $this->handleData($loanList,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	public function refuseList ()
	{
		$title = $this->getTitle(['material_list','operate','risk_quote']);
		$where = array_merge(['status'=>3,'deny_status'=>1],(array)$this->getParams());
		$refuseList = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);
		$list = $this->handleData($refuseList,$title);//dump($list);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
	public function loanTable ()
	{
		$this->assign("base_table",$this->base_table);
		$this->assign("car_table",$this->car_table);
		return $this->view->fetch();
	}
	public function lenderCredentials ()
	{
		return $this->view->fetch();
	}
	public function submitTable ()
	{
		$loanid = $this->request->post('loanid');
		if (empty($loanid))return $this->ajaxError("订单id错误");
		$order = new Order();
		$result = $order->lenderSubmit($loanid);
		if ($result) {
			return $this->ajaxSuccess("提交成功");
		} else {
			return $this->ajaxError($order->getError());
		}
	}
	public function refuse ()
	{
		if ($this->request->isPost() && ($this->request->post("is_refuse") == 1)) {
			$remark = $this->request->post("remark");
			$order = new Order();
			$result = $order->lenderDeny($this->request->post("loanid"),['remark'=>$remark]);
			if ($result) {
				return $this->ajaxSuccess("拒单成功");
			} else {
				return $this->ajaxError($order->getError());
			}
		}
	}
	public function applyTable ()
	{
		if (!empty($this->request->request("is_search"))) return $this->searchLoan();
		$request = $this->request;
		if ($request->isPost())return $this->applyToDb();
		$product = new \manager\index\model\ProductManager();
                $product->where["status"] = 1;
         
         $product=array_column($product->productList(),"name",'month');
         $month=array_keys($product);
         sort($month);
		// $productName= array_column($product->productList(),"name");
        // $month=array_unique(array_column($product->productList(),'month'));
        //sort($month);
        // dump($month);
		$this->assign("product",$product);
		$this->assign("month",$month);
		$this->assign("periodes",['3个月','12个月','24个月']);
		$this->assign("marriage",['未婚','已婚','离异','丧偶']);
		$this->assign("education",['高等学历（大专及以上）','中等学历（高中、中专）','初等学历（初中及以下）']);
		$this->assign("house",['按揭房','全款房','自建房','亲属住房','租用房','单位住房']);
		$this->assign("work_time",['<=1年','2年','3年','4年','>=5年']);
		$this->assign("unit_type",['国有企业','私营企业','外资']);
		if ($this->request->request('readonly') == 1) {
			return $this->fetch('material/applyinfo');
		}
		return $this->view->fetch();
	}
	public function searchLoan ()
	{
		$type = $this->request->request("type");
		$value = $this->request->request("value");
		$is_search = $this->request->request("is_search");
		$loanid = $this->request->request("excep_loanid");
		$this->loan->where = [$type => $value,'exceptLoanid'=>$loanid];
		$this->order = "l.timeadd desc";
		$list = $this->loan->loanList();
		if ($is_search == 2) {//显示查找结果
			$this->assign("list",$list);
			return $this->fetch('applysearch');
		}
		if (empty($value)) return $this->ajaxError();
		//相关联系人提示
		if ($is_search == 3) return $this->ajaxSuccess("查找结果",$list);
		//姓名、身份证、电话号码关联查询
		$list = $list[0];
		if (false != $list) {
			$store =  \manager\index\model\Store::get($list['storeid']);
			$list['store_name'] = $store->name;
			return $this->ajaxSuccess("有相似订单",$list);
		} else {
			return $this->ajaxError("未查到相似订单");
		}
	}
	public function applyToDb ()
	{
		$lender = new \manager\index\model\Lender();
		if ($this->request->post("loanid")) {//更新
			$data = $this->request->post();
			$data['id'] = $this->request->post("loanid");
			$result = $lender->editLender($data);
		} else {//新增
			$result = $lender->addLender($this->request->post());
		}
		if ($result) {
	 		return $this->ajaxSuccess("操作成功",['id'=>$result]);
		} else {
			return $this->ajaxError($lender->getError());
		}
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
                } elseif($tk=="material_list"){
                	if (strtolower($this->request->action()) == "examinelist") {
                		$return[$dk][$tk] = $this->getProduct($dv);
                	} else {
 						$return[$dk][$tk] = $this->changeMaterial($dv);
                	}           		
                } elseif ($tk == "risk_quota") {
                	$return[$dk][$tk] = $this->getRiskQuota($dv);
                } else {
					$return[$dk][$tk] = $dv[$tk];
				}
			}
			$return[$dk]['other']['id'] = $dv['loanid'];
		}
		return $return;
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
	//获取订单确认产品
	public function getProduct ($loanInfo)
	{
		return $loanInfo['product'];
	}
	//获取操作按钮
	public function getOperate ($params)
	{
		if (($params['status'] == 1) && ($params['process_status'] == 1)) {//申请列表
			$operate_btn = ['submit'=>'提交','edit'=>'编辑','refuse'=>"拒单"];
		} elseif (($params['status'] == 1) && (in_array($params['process_status'],[2,3]))) {//提交管理
			$operate_btn = ['complete'=>'补全资料','edit'=>'查看','refuse'=>'拒单'];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 4) {//反馈管理(初审拒单)
			$operate_btn = ['refuse'=>'拒单','complete'=>"补资料",'reSubmit'=>'重新提交','firstReviewInfo'=>"查看评审表"];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 7) {//反馈管理(终审拒单)
			$operate_btn = ['refuse'=>'拒单','reviewInfo'=>"查看评审表",'review'=>'申请复议'];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 9) {//反馈管理(终审通过)
			$operate_btn = ['receive'=>'接单','reviewInfo'=>"查看评审表",'review'=>'申请复议','refuse'=>"拒单"];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 11) {//复议通过
			$operate_btn = ['receive'=>'接单','refuse'=>'拒单','repeatInfo'=>'复议结果'];
		} elseif (($params['status'] == 1) && ($params['process_status']) == 13) {//等待签约
			$operate_btn = ['uploadContact'=>'上传合同','downloadTemplate'=>'下载合同模板'];
			if (in_array("21",explode(",",$params['material'])))$operate_btn['complete'] = "完成";
		} else {
			$operate_btn = [];
		}
		return $operate_btn;	
	}
	//面审表
	public function interviewTable ()
	{
		if ($this->request->isAjax()) {
                        $loanTrialModel = new \manager\index\model\LoanTrial();
			if ($loanTrialModel->editTrial($this->request->post())) {
				return $this->ajaxSuccess("操作成功");
			} else {
				return $this->ajaxError($loanTrialModel->getError());
			}
		}
                $this->assign("reviewData", \manager\index\model\LoanTrial::get(["loanid"=>$this->request->get("loanid")])->getData());
		return $this->view->fetch();
	}
	//车辆评估报表
	public function carevalTable ()
	{
		$loanid = $this->request->get("loanid");
		$car_report = new CarReport();//dump($loanid);
		$eval_info = $car_report->carReportOne($loanid);//dump($eval_info);
		$this->assign("eval_info",$eval_info);
		$this->assign("car_traffic",$this->car_traffic);
		if ($this->request->isPost() && !empty($this->request->post('loanid')))return $this->carevalToDb();
		if ($this->request->request('readonly') == 1) {
			$review_info = \manager\index\model\LoanReview::get(['loanid'=>$this->request->get("loanid")]);
			$this->assign("review_info",$review_info->getData());
			return $this->fetch("material/carreportinfo");
		}
		return $this->view->fetch();
	}
	//车辆评估报表数据入库
	public function carevalToDb()
	{
		$eval = new CarReport();
		$result = $eval->editCarReport($this->request->post());
		if ($result) {
			return $this->ajaxSuccess("操作成功");
		} else {
			return $this->ajaxError($eval->getError());
		}
	}      
        function submitList ()
	{
		$title = $this->getTitle(['message','risk_quote']);
		$where = array_merge(['status'=>1,'process_status'=>['in','2,3,5,6,8']],(array)$this->getParams());//dump($this->loan->where);
		$loanList = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);//dump($loanList);
		$list = $this->handleData($loanList,$title);
		$this->assign("list",$list);
		$this->assign("title",$title);
		return $this->view->fetch();
	}
    //已提交资料
    private function changeMaterial($data){
    	$myMaterial = $data['material'];
        $maContent = "";
        $material = $this->base_table + $this->car_table;//dump($material);
        if(empty($myMaterial))return $maContent;
        $arrMyMaterial = explode(",",$myMaterial);//dump($arrMyMaterial);
        $maContent = "<div class='list-btn'>查看</div><div class='list'><ul>";
        foreach($material as $key=>$val){
            if(in_array($key,$arrMyMaterial)){
                $maContent.="<li>{$val['title']}</li>";
            }else{
                $maContent.="<li><a href='{$val['url']}{$data['loanid']}' style='color:red;'>{$val['title']}</a></li>";
            }
        }
        $maContent .= "</ul></div>";
        return $maContent;
    }
    //反馈管理
    public function responseList ()
    {
    	if ($this->request->post("is_resub") ==1) {//重新提交
    		$order = new Order();
    		$loanid = $this->request->post("loanid");
    		$result = $order->doorReview($loanid);
    		if ($result) {
    			return $this->ajaxSuccess("提交成功");
    		} else {
    			return $this->ajaxError($order->getError());
    		}
    	}
    	$where = array_merge((array)$this->getParams(),['status'=>1,'process_status'=>['in','4,7,9']]);
    	$responseList = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);
    	//dump($responseList);
    	$title = $this->getTitle(['material_list','message','risk_quote']);
    	$list = $this->handleData($responseList,$title);//dump($list);
    	$this->assign("title",$title);
    	$this->assign("list",$list);
    	return $this->view->fetch();
    }
    //补资料信息
    public function completeInfo ()
    {
    	$loanid = $this->request->get("loanid");
    	$review = \manager\index\model\LoanReview::get(['loanid'=>$loanid]);
    	$this->assign("result",$review->first_result);
    	return $this->view->fetch();
    }
    //查看评审表
    public function reviewInfo ()
    {
    	$car_report = (new \manager\index\model\CarReport())->carReportOne($this->request->get("loanid"));
		//dump($car_report);
		$this->assign("car_info",$car_report);
		$this->assign("review_info",\manager\index\model\LoanReview::get(['loanid'=>$this->request->get("loanid")]));
		$this->assign("repeat",$this->request->get("repeat"));
    	return $this->view->fetch();
    }
    //申请复议
    public function applyReview ()
    {
    	if ($this->request->post("is_apply") == 1) {
    		$order = new Order();
    		$result = $order->doorRepeat($this->request->post("loanid"),['door_result'=>$this->request->post("review_info")]);
    		if ($result) {
    			return $this->ajaxSuccess("复议提交成功");
    		} else {
    			return $this->ajaxError($order->getError());
    		}
    	}
    	return $this->view->fetch();
    }
    //复议管理
    public function storeReviewList ()
    {

    	$where = array_merge((array)$this->getParams(),['status'=>1,'process_status'=>['in','8,11']]);
    	$responseList = $this->getLoanList(['where'=>$where,'is_apply_where'=>1]);
    	//dump($responseList);
    	$title = $this->getTitle(['material_list','message','risk_quote']);
    	$list = $this->handleData($responseList,$title);//dump($list);
    	$this->assign("title",$title);
    	$this->assign("list",$list);
    	return $this->view->fetch();
    }
    //稽核管理
    public function examineList ()
    {
    	$where = array_merge((array)$this->getParams(),['status' => 1,'process_status' => ['in','12,13,14']]);
    	$responseList = $this->getLoanList(['where' => $where,'is_apply_where' => 1]);//dump($responseList);
    	//$title = array_replace($this->getTitle(['message']), ['material_list' => "产品确认"]);
    	$title = $this->getTitle(['message','material_list']);
    	$list = $this->handleData($responseList,$title);
    	$this->assign("title",$title);
    	$this->assign("list",$list);
    	return $this->view->fetch();
    }
    //门店接单
    public function receive ()
    {
    	if ($this->request->post("is_receive") == 1) {
    		$order = new Order();
    		if (false == $order->passToContact($this->request->post("loanid"))) {
    			return $this->ajaxError($order->getError());
    		}
    		return $this->ajaxSuccess("接单成功");
    	}
    }
}