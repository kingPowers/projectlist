<?php
/**
* 金牌经纪人
*/
class AgentAction extends CommonAction{	
	private $page_num = 12;  
    private $_keys = array( 'names' => '客户姓名','gold_names' => '经纪人姓名', 'mobile' => '客户手机号','gold_mobile' => '经纪人电话','certiNumber'=>'身份证号');
    private $_keys2 = array( 'names' => '姓名','mobile' => '手机号','certiNumber'=>'身份证号');
	//申请
    public function __construct()
    {
        parent::__construct();
          //获取金牌经济人信息
    }
	public function agent_order_apply(){
        $return = $this->getParam();
        //dump($_GET);
        $where = $return[0];
        $params = $return[1];
        $where['go.status'] = 1;
        $count = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->where($where)
        ->field("count(go.id) as num")
        ->order("go.id")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $order_lists = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.*,mi.certiNumber,mi.names as gold_names, m.mobile as gold_mobile")
        ->where($where)
        ->limit($limit)
        ->order("go.id desc")
        ->select();
        $this->setPage("/Agent/agent_order_apply{$params}/p/*.html");
        $this->assign("order_lists",$order_lists);
        $this->assign("keys",$this->_keys);
		$this->display();
	}
        
        //拒单
        public function unPassed(){
            $ids  = $_POST['ids'];
            if(empty($ids))$this->error("请选择订单");
            if(!is_array($ids))$this->error("参数错误");
            import("Think.ORG.Util.GoldAgent");
            $gold = new GoldAgent();
            if(false!=$gold->customPassed($ids)){
                $this->success("操作成功");
            }
            $this->error("操作失败，请稍后重试！");
            $this->display();
        }
        
	//甩单通过
        public function passed(){
            $ids  = $_POST['ids'];
            if(empty($ids))$this->error("请选择订单");
            if(!is_array($ids))$this->error("参数错误");
            import("Think.ORG.Util.GoldAgent");
            $gold = new GoldAgent();
            if(false!=$gold->customPassed($ids)){
                $this->success("操作成功");
            }
            $this->error("操作失败，请稍后重试！");
        }
        
        //拒绝甩单
        public function unPass(){
            import("Think.ORG.Util.GoldAgent");
            $gold = new GoldAgent();
            if(isset($_POST) && !empty($_POST['data'])){
                if(empty($_POST['data']['id']))$this->error("订单id不能为空");
                if(empty($_POST['data']['error_code']))$this->error("拒单原因不能为空");
                if(false==$gold->cumtomUnPassed($_POST['data']['id'],$_POST['data']['error_code'],$_POST['data']['remark'])){
                    $this->error("拒单失败【".$gold->getError()."】");
                }else{
                    $this->success("拒单成功");
                }
            }
            $setting = $gold->getSetting();
            $this->assign("error_code",$setting['error_code']);
            $this->display();
        }
        
	//甩单中
	public function agent_order(){
		$return = $this->getParam();
		$where = $return[0];
        $params = $return[1];
        $where['go.status'] = 2;
        $count = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->where($where)
        ->field("count(go.id) as num")
        ->order("go.id")
        ->find();
        //echo M("gold_order go")->getLastSql();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $order_lists = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.*,mi.certiNumber,mi.names as gold_names, m.mobile as gold_mobile")
        ->where($where)
        ->limit($limit)
        ->order("go.id desc")
        ->select();
        $this->setPage("/Agent/agent_order{$params}/p/*.html");
        $this->assign("order_lists",$order_lists);
        $this->assign("keys",$this->_keys);
		$this->display();
	}
    public function agent_info(){
        $order_id = $this->_get("order_id","trim");
        if($order_id){
            $agent_info = M("member m")
            ->join("gold_order go on go.memberid=m.id")
            ->join("member_info mi on mi.memberid = m.id")
            ->where("go.id={$order_id}")
            ->field("go.remark,m.mobile,mi.names")
            ->find();
            $this->assign("agent_info",$agent_info);
        }
        $this->display();
    }
	//甩单成功
	public function agent_order_success(){
		$return = $this->getParam();
		$where = $return[0];
        $params = $return[1];
        $where['go.status'] = 3;
        $count = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->where($where)
        ->field("count(go.id) as num")
        ->order("go.id")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $order_lists = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.*,mi.certiNumber,mi.names as gold_names, m.mobile as gold_mobile")
        ->where($where)
        ->limit($limit)
        ->order("go.id desc")
        ->select();
        $this->setPage("/Agent/agent_order_success{$params}/p/*.html");
        $this->assign("order_lists",$order_lists);
        $this->assign("keys",$this->_keys);
		$this->display();
	}
	//删除甩单
	public function agent_order_delete(){
		$return = $this->getParam();
		$where = $return[0];
        $params = $return[1];
        $where['go.status'] = 4;
        $count = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->where($where)
        ->field("count(go.id) as num")
        ->order("go.id")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $order_lists = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.*,mi.certiNumber,mi.names as gold_names, m.mobile as gold_mobile")
        ->where($where)
        ->limit($limit)
        ->order("go.id desc")
        ->select();
        $this->setPage("/Agent/agent_order_delete{$params}/p/*.html");
        $this->assign("order_lists",$order_lists);
        $this->assign("keys",$this->_keys);
		$this->display();
	}
	//甩单失效
	public function agent_order_lose(){
		$return = $this->getParam();
		$where = $return[0];
        $params = $return[1];
        $where['go.status'] = 5;
        $count = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->where($where)
        ->field("count(go.id) as num")
        ->order("go.id")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $order_lists = M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.*,mi.certiNumber,mi.names as gold_names, m.mobile as gold_mobile")
        ->where($where)
        ->limit($limit)
        ->order("go.id desc")
        ->select();
        $this->setPage("/Agent/agent_order_lose{$params}/p/*.html");
        $this->assign("order_lists",$order_lists);
        $this->assign("keys",$this->_keys);
		$this->display();
	}
    //投诉列表
    public function order_report(){
        $return = $this->getParam("report");
        $where = $return[0];
        $params = $return[1];
        $where['gr.status'] = 1;
        $count = M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->where($where)
        ->field("count(gr.id) as num")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        //$M = M("gold_report gr");
        $report_lists = M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->join("member m on m.id=gr.memberid")
        ->field("gr.*,mi.certiNumber,mi.names,m.username,m.mobile")
        ->where($where)
        ->limit($limit)
        ->order("gr.timeadd desc")
        ->select();
        foreach ($report_lists as &$val) {
            if(!empty($val['pic_urls']))
                $val['pic_urls'] = explode("|",$val['pic_urls']);
        }
        //dump($report_lists);
        $this->assign("report_lists",$report_lists);
        $this->setPage("/Agent/order_report{$params}/p/*.html");
        $this->assign("keys",$this->_keys2);
        $this->display();
    }
    //处理页面
    public function haddle_inter(){
        $id = $this->_get('id', 'intval', 0);
        if($id){
            $info = M("gold_report")
            ->where("id={$id}")
            ->find();
        }
        $this->assign("info",$info);
        $this->display("report_haddle");
    }
    //处理投诉
    public function report_haddle(){
       if($_POST && !empty($_POST['data'])){
            if(empty($_POST['data']['id']))$this->error("订单id不能为空");
            if(empty($_POST['data']['status']))$this->error("请选择处理结果");
            if(empty($_POST['data']['remark']))$this->error("请填写处理意见");
            import("Think.ORG.Util.GoldAgent");
            $gold = new GoldAgent();
            if(true==$gold->customTrans($_POST['data']['id'],$_POST['data'])){
                $this->success("处理成功");
            }else{
                $error = $gold->getError();
                $this->error("处理失败[{$error}]");
            }
        }
       
    }
    //投诉订单详情
    public function order_info(){
        if($_REQUEST['id']){ 
            $order_info = M("gold_report gr")
            ->join("gold_order go on go.id=gr.order_id")
            ->join("member m on m.id=go.memberid")
            ->join("member_info mi on mi.memberid=m.id")
            ->where("gr.id={$_REQUEST['id']}")
            ->field("go.*,m.mobile as gold_mobile,mi.names as gold_names")
            ->find();
            if($order_info){
               $this->assign("order_info",$order_info);
            }
            $this->display();
        }
    }
    //投诉历史列表
    public function report_history(){
        $return = $this->getParam("report");
        $where = $return[0];
        $params = $return[1];
        $where['gr.status'] = array("in","2,3");
        $count = M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->where($where)
        ->field("count(gr.id) as num")
        ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        //$M = M("gold_report gr");
        $report_lists = M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->join("member m on m.id=gr.memberid")
        ->field("gr.*,mi.certiNumber,mi.names,m.username,m.mobile")
        ->where($where)
        ->limit($limit)
        ->order("gr.timeadd desc")
        ->select();
        foreach ($report_lists as &$val) {
            if(!empty($val['pic_urls']))
                $val['pic_urls'] = explode("|",$val['pic_urls']);
        }
        $this->assign("report_lists",$report_lists);
        $this->setPage("/Agent/report_history{$params}/p/*.html");
        $this->assign("keys",$this->_keys2);
        $this->display();
    }
	public function getParam($type = ""){
		$params = '';
		$key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if($type == ""){
            $names = "go.names";
            $gold_names = "mi.names";
            $mobile = "go.mobile";
            $gold_mobile = "m.mobile";
            $certiNumber = "mi.certiNumber";
            $time = "go.timeadd";
        }elseif($type == "report"){
            $names = "mi.names";
            $mobile = "m.mobile";
            $certiNumber = "mi.certiNumber";
            $time = "gr.timeadd";
        }
        if (!empty($value)) {
             switch($key){
                case 'names':
                    $where[$names] = $value;
                break;
                case 'gold_names':
                    $where[$gold_names] = $value;
                break;
                case 'mobile':
                    $where[$mobile] = $value;
                break;
                case 'gold_mobile':
                    $where[$gold_mobile] = $value;
                break;
                case 'certiNumber':
                    $where[$certiNumber] = $value;
                break;
             }
                $params .= "/k/{$key}/v/{$value}";
         }
        if($starttime && !$endtime){
            $where[$time] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $where[$time] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $where[$time] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        return array($where,$params);
	}
	/*
	导出申请列表数据
	*/
    public function export_apply_list(){
        $this->export_data('apply_list');
	}
	public function export_data_apply_list(){
		$return = $this->getParam();
		$where = $return['0'];
		$where['go.status'] = 1;
		$lists =  M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.names,go.mobile,mi.names as gold_names,m.mobile as gold_mobile,mi.certiNumber,go.contract_num,go.age,go.province,go.city,go.jobs,go.brands,go.buy_time,go.car_price,go.car_drive,go.car_frame_number,go.is_fullmoney,go.mort_company,go.loan_money,go.timeadd")
        ->where($where)
        ->order("go.id desc")
        ->select();
        foreach($lists as &$list){
            if($list['is_fullmoney'] == 0){
                $list['is_fullmoney'] = '未知';
            }elseif($list['is_fullmoney'] == 1){
                $list['is_fullmoney'] = '是';
            }else{
            	$list['is_fullmoney'] = '否';
            }
        }
        $cols = array(
            array('names','客户姓名'),
            array('mobile','手机号码'),
            array('gold_names','经纪人姓名'),
            array('gold_mobile','经纪人电话'),
            array('certiNumber','身份证号'),
            array('contract_num','合同编号'),
            array('age','年龄'),
            array('province','省份'),
            array('city','城市'),
            array('jobs','职业'),
            array('brands','车辆品牌型号'),
            array('buy_time','购车时间'),
            array('car_price','裸车价格'),
            array('car_drive','行驶里程'),
            array('car_frame_number','车架号码'),
            array('is_fullmoney','是否全款'),
            array('mort_company','抵押单位'),
            array('loan_money','贷款金额'),
            array('timeadd','添加时间')
        );
         return array('data'=>$lists,'cols'=>$cols);
	}
	/*
	导出甩单中列表数据
	*/
    public function export_order_list(){
        $this->export_data('order_list');
	}
	public function export_data_order_list(){
		$return = $this->getParam();
		$where = $return['0'];
		$where['go.status'] = 2;
		$lists =  M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.names,go.mobile,mi.names as gold_names,m.mobile as gold_mobile,mi.certiNumber,go.contract_num,go.age,go.province,go.city,go.jobs,go.brands,go.buy_time,go.car_price,go.car_drive,go.car_frame_number,go.is_fullmoney,go.mort_company,go.loan_money,go.last_time")
        ->where($where)
        ->order("go.id desc")
        ->select();
        foreach($lists as &$list){
            if($list['is_fullmoney'] == 0){
                $list['is_fullmoney'] = '未知';
            }elseif($list['is_fullmoney'] == 1){
                $list['is_fullmoney'] = '是';
            }else{
            	$list['is_fullmoney'] = '否';
            }
        }
        $cols = array(
            array('names','客户姓名'),
            array('mobile','手机号码'),
            array('gold_names','经纪人姓名'),
            array('gold_mobile','经纪人电话'),
            array('certiNumber','身份证号'),
            array('contract_num','合同编号'),
            array('age','年龄'),
            array('province','省份'),
            array('city','城市'),
            array('jobs','职业'),
            array('brands','车辆品牌型号'),
            array('buy_time','购车时间'),
            array('car_price','裸车价格'),
            array('car_drive','行驶里程'),
            array('car_frame_number','车架号码'),
            array('is_fullmoney','是否全款'),
            array('mort_company','抵押单位'),
            array('loan_money','贷款金额'),
            array('last_time','添加时间')
        );
         return array('data'=>$lists,'cols'=>$cols);
	}
	/*
	导出甩单成功列表数据
	*/
    public function export_success_list(){
        $this->export_data('success_list');
	}
	public function export_data_success_list(){
		$return = $this->getParam();
		$where = $return['0'];
		$where['go.status'] = 3;
		$lists =  M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.names,go.mobile,mi.names as gold_names,m.mobile as gold_mobile,mi.certiNumber,go.contract_num,go.age,go.province,go.city,go.jobs,go.brands,go.buy_time,go.car_price,go.car_drive,go.car_frame_number,go.is_fullmoney,go.mort_company,go.loan_money,go.last_time")
        ->where($where)
        ->order("go.id desc")
        ->select();
        foreach($lists as &$list){
            if($list['is_fullmoney'] == 0){
                $list['is_fullmoney'] = '未知';
            }elseif($list['is_fullmoney'] == 1){
                $list['is_fullmoney'] = '是';
            }else{
            	$list['is_fullmoney'] = '否';
            }
        }
        $cols = array(
            array('names','客户姓名'),
            array('mobile','手机号码'),
            array('gold_names','经纪人姓名'),
            array('gold_mobile','经纪人电话'),
            array('certiNumber','身份证号'),
            array('contract_num','合同编号'),
            array('age','年龄'),
            array('province','省份'),
            array('city','城市'),
            array('jobs','职业'),
            array('brands','车辆品牌型号'),
            array('buy_time','购车时间'),
            array('car_price','裸车价格'),
            array('car_drive','行驶里程'),
            array('car_frame_number','车架号码'),
            array('is_fullmoney','是否全款'),
            array('mort_company','抵押单位'),
            array('loan_money','贷款金额'),
            array('last_time','添加时间')
        );
         return array('data'=>$lists,'cols'=>$cols);
	}
	/*
	导出甩单删除列表数据
	*/
    public function export_delete_list(){
        $this->export_data('delete_list');
	}
	public function export_data_delete_list(){
		$return = $this->getParam();
		$where = $return['0'];
		$where['go.status'] = 4;
		$lists =  M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.names,go.mobile,mi.names as gold_names,m.mobile as gold_mobile,mi.certiNumber,go.contract_num,go.age,go.province,go.city,go.jobs,go.brands,go.buy_time,go.car_price,go.car_drive,go.car_frame_number,go.is_fullmoney,go.mort_company,go.loan_money,go.last_time")
        ->where($where)
        ->order("go.id desc")
        ->select();
        foreach($lists as &$list){
            if($list['is_fullmoney'] == 0){
                $list['is_fullmoney'] = '未知';
            }elseif($list['is_fullmoney'] == 1){
                $list['is_fullmoney'] = '是';
            }else{
            	$list['is_fullmoney'] = '否';
            }
        }
        $cols = array(
            array('names','客户姓名'),
            array('mobile','手机号码'),
            array('gold_names','经纪人姓名'),
            array('gold_mobile','经纪人电话'),
            array('certiNumber','身份证号'),
            array('contract_num','合同编号'),
            array('age','年龄'),
            array('province','省份'),
            array('city','城市'),
            array('jobs','职业'),
            array('brands','车辆品牌型号'),
            array('buy_time','购车时间'),
            array('car_price','裸车价格'),
            array('car_drive','行驶里程'),
            array('car_frame_number','车架号码'),
            array('is_fullmoney','是否全款'),
            array('mort_company','抵押单位'),
            array('loan_money','贷款金额'),
            array('last_time','添加时间')
        );
         return array('data'=>$lists,'cols'=>$cols);
	}
    /*
	导出甩单失效列表数据
	*/
    public function export_lose_list(){
        $this->export_data('lose_list');
	}
	public function export_data_lose_list(){
		$return = $this->getParam();
		$where = $return['0'];
		$where['go.status'] = 5;
		$lists =  M("gold_order go")
        ->join("member_info mi on mi.memberid=go.memberid")
        ->join("member m on m.id=mi.memberid")
        ->field("go.names,go.mobile,mi.names as gold_names,m.mobile as gold_mobile,mi.certiNumber,go.contract_num,go.age,go.province,go.city,go.jobs,go.brands,go.buy_time,go.car_price,go.car_drive,go.car_frame_number,go.is_fullmoney,go.mort_company,go.loan_money,go.last_time")
        ->where($where)
        ->order("go.id desc")
        ->select();
        foreach($lists as &$list){
            if($list['is_fullmoney'] == 0){
                $list['is_fullmoney'] = '未知';
            }elseif($list['is_fullmoney'] == 1){
                $list['is_fullmoney'] = '是';
            }else{
            	$list['is_fullmoney'] = '否';
            }
        }
        $cols = array(
            array('names','客户姓名'),
            array('mobile','手机号码'),
            array('gold_names','经纪人姓名'),
            array('gold_mobile','经纪人电话'),
            array('certiNumber','身份证号'),
            array('contract_num','合同编号'),
            array('age','年龄'),
            array('province','省份'),
            array('city','城市'),
            array('jobs','职业'),
            array('brands','车辆品牌型号'),
            array('buy_time','购车时间'),
            array('car_price','裸车价格'),
            array('car_drive','行驶里程'),
            array('car_frame_number','车架号码'),
            array('is_fullmoney','是否全款'),
            array('mort_company','抵押单位'),
            array('loan_money','贷款金额'),
            array('last_time','添加时间')
        );
         return array('data'=>$lists,'cols'=>$cols);
	}
    /*
    导出举报订单列表
     */
    public function export_report_list(){
        $this->export_data('report_list');
    }
    public function export_data_report_list(){
        $return = $this->getParam("report");
        $where = $return['0'];
        $where['gr.status'] = 1;
        $M = M("gold_report gr");
        $lists = $M//M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->join("member m on m.id=gr.memberid")
        ->field("gr.*,mi.certiNumber,mi.names,m.username,m.mobile")
        ->where($where)
        ->order("gr.timeadd desc")
        ->select();
        foreach($lists as &$list){
            $list['status'] = ($list['status'] == 1)?"未审核":"";
        }
        $cols = array(
            array('username',"用户名"),
            array('mobile','手机号码'),
            array('certiNumber','身份证号'),
            array('names','姓名'),
            array('content','举报内容'),
            array('timeadd','举报时间'),
            array('status','状态')
        );
         return array('data'=>$lists,'cols'=>$cols);
    }
    /*
    导出举报历史列表
     */
    public function export_history_list(){
        $this->export_data('history_list');
    }
    public function export_data_history_list(){
        $return = $this->getParam("report");
        $where = $return['0'];
        $where['gr.status'] = array("in","2,3");
        $M = M("gold_report gr");
        $lists = $M//M("gold_report gr")
        ->join("member_info mi on mi.memberid=gr.memberid")
        ->join("member m on m.id=gr.memberid")
        ->field("gr.*,mi.certiNumber,mi.names,m.username,m.mobile")
        ->where($where)
        ->order("gr.timeadd desc")
        ->select();
        foreach($lists as &$list){
            $list['status'] = ($list['status'] == 2)?"卖方责任":"买方责任";
        }
        $cols = array(
            array('username',"用户名"),
            array('mobile','手机号码'),
            array('certiNumber','身份证号'),
            array('names','姓名'),
            array('content','举报内容'),
            array('lasttime','处理日期'),
            array('remark','处理意见'),
            array('status','举报结果')
        );
         return array('data'=>$lists,'cols'=>$cols);
    }
	
    /*
     * 导出金牌经纪人账单
     * 		1.按照日期检索  入账   出账     投资账单
     * 
     * */
    public function exportBill(){
    	$return = $this->getParam();unset($return[0]['go.timeadd']);
    	$starttime = $this->_get('starttime', 'trim')?$this->_get('starttime', 'trim'):"2017-01-01";
    	$endtime = $this->_get('endtime', 'trim')?$this->_get('endtime', 'trim')." 23:59:59":"2117-01-01";
    	//if(empty($starttime) || empty($endtime))exit("开始、截止日期不能为空");
    	$whereOrder = $return[0];
    	$whereOrder['go.id'] = ['exp'," in(select distinct(order_id) from gold_transfer where timeadd>='{$starttime}' and timeadd<='{$endtime}' and (order_id is not null or order_id!=''))"];
    	$orderList = M('gold_order go')->join("member m on m.id=go.memberid")->join("member_info mi on mi.memberid=go.memberid")->field("go.contract_num,go.id,m.mobile")->where($whereOrder)->select();
    	
    	vendor('PHPExcel.PHPExcel');
    	vendor('PHPExcel.PHPExcel.IOFactory');
    	vendor('PHPExcel.PHPExcel.Writer.Excel5');
    	$sett = $this->getExcelSetting();
    	$fileName = date("Y-m-d H:i")." 导出账单";
    	$PHPExcel = new PHPExcel();
    	//$activeSheet = $PHPExcel->getActiveSheet();
    	//制作表头,并设置宽度
    	$word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    	$cols = array(
    			0=>["title"=>'合同编号','index'=>"A","width"=>"20"],
    			1=>["title"=>'甩单人','index'=>"B","width"=>"20"],
    			2=>["title"=>'解锁人','index'=>"C","width"=>"15"],
    			3=>["title"=>'入账','index'=>"D","width"=>"12"],
    			4=>["title"=>'入账日期','index'=>"E","width"=>"20"],
    			5=>["title"=>'解锁人','index'=>"F","width"=>"15"],
    			6=>["title"=>'到期出账','index'=>"G","width"=>"12"],
    			7=>["title"=>'出账日期','index'=>"H","width"=>"20"],
    			8=>["title"=>'投诉人','index'=>"I","width"=>"15"],
    			9=>["title"=>'出账','index'=>"J","width"=>"12"],
    			10=>["title"=>'处理时间','index'=>"K","width"=>"20"],
    		);
    	$PHPExcel->getActiveSheet()->mergeCells("A1:".$word[count($cols)-1]."1");
    	$PHPExcel->getActiveSheet()->setCellValue("A1",$fileName);
    	foreach($cols as $k => $v){
    		$PHPExcel->getActiveSheet()->setCellValue("{$v['index']}2",$v['title']);
    		$PHPExcel->getActiveSheet()->getColumnDimension($v['index'])->setWidth($v['width']);
    	}
    	$i = 3;
    	foreach($orderList as $k=>$v){
    		//入账记录
    		$outList = M('gold_transfer f')
    						->join("member m on m.id=f.memberid")
    						->field("m.mobile,f.money,f.timeadd")
    						->where(['f.type'=>1,'f.order_id'=>$v['id'],'f.timeadd'=>['between',[$starttime,$endtime]]])
    						->select();
    		
    		foreach($outList as $outval){
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[2]['index']}{$i}",$outval['mobile']);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[3]['index']}{$i}",abs($outval['money']));
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[4]['index']}{$i}",$outval['timeadd']);
    			$i++;
    		}
    		//出账记录  【无人举报】出账，【卖家责任】出账
    		$inWhere = ['f.type'=>2,'f.order_id'=>$v['id'],
    		'f.timeadd'=>['between',[$starttime,$endtime]],
    		'f.remark'=>['exp'," like '%【客服】-解锁订单收益%'"],
    		];
    		$inList = M('gold_transfer f')
    		->join("member m on m.id=f.memberid")
    		->field("m.mobile,f.money,f.timeadd")
    		->where($inWhere)
    		->select();
    		foreach($inList as $inval){
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[5]['index']}{$i}",$inval['mobile']);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[6]['index']}{$i}",0-$inval['money']);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[7]['index']}{$i}",$inval['timeadd']);
    			$i++;
    		}
    		
    		//投诉记录,【买家责任】的记录
    		$repWhere = ['f.type'=>2,'f.order_id'=>$v['id'],
    					'f.timeadd'=>['between',[$starttime,$endtime]],
    					'f.remark'=>['exp'," like '%【客服】-解锁订单退款%'"],
    					];
    		$reList = M('gold_transfer f')
    		->join("member m on m.id=f.memberid")
    		->join("gold_report r on r.transfer_id=f.id and r.status=2")
    		->field("m.mobile,f.money,f.timeadd")
    		->where($repWhere)
    		->select();
    		
    		foreach($reList as $val){
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[8]['index']}{$i}",$val['mobile']);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[9]['index']}{$i}",0-$val['money']);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[10]['index']}{$i}",$val['timeadd']);
    			$i++;
    		}
    		//和并单元格
    		$total = intval(count($outList)+count($inList)+count($reList));
    		if($total>0){
    			$start = $i-$total;
    			$end = $i-1;
    			$PHPExcel->getActiveSheet()->mergeCells("{$cols[0]['index']}{$start}:{$cols[0]['index']}{$end}")->getStyle("{$cols[0]['index']}{$start}:{$cols[0]['index']}{$end}")->applyFromArray(['bold'=>true,'borders'=>$sett['borders'],'alignment'=>$sett['alignment']]);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[0]['index']}{$start}",$v['contract_num']);
    			$PHPExcel->getActiveSheet()->mergeCells("{$cols[1]['index']}{$start}:{$cols[1]['index']}{$end}")->getStyle("{$cols[1]['index']}{$start}:{$cols[1]['index']}{$end}")->applyFromArray(['bold'=>true,'borders'=>$sett['borders'],'alignment'=>$sett['alignment']]);
    			$PHPExcel->getActiveSheet()->setCellValueExplicit("{$cols[1]['index']}{$start}",$v['mobile']);
    		}
    		
    	}
    	//设置表格边框
    	$PHPExcel->getActiveSheet()->getStyle("A1:{$word[count($cols)-1]}".($i-1))->applyFromArray(['bold'=>true,'borders'=>$sett['borders'],'alignment'=>$sett['alignment']]);
    	
    	$outputFileName = $fileName.'.xls';
    	$xlsWriter = new PHPExcel_Writer_Excel5($PHPExcel);
    	ob_end_clean();//清除缓冲区,避免乱码
    	header("Content-Type: application/force-download");
    	header("Content-Type: application/octet-stream");
    	header("Content-Type: application/download");
    	header('Content-Disposition:inline;filename="'.$outputFileName.'"');
    	header("Content-Transfer-Encoding: binary");
    	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    	header("Pragma: no-cache");
    	$xlsWriter->save( "php://output" );
    }
    
    private function getExcelSetting(){
    	return [
    		//边框格式
    		'borders'=>[
    				'allborders'=>['style'=>PHPExcel_Style_Border::BORDER_THIN],
    			],
    		//单元格居中
    		'alignment'=>[
    				'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,//水平居中
    				'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER,//垂直居中
    			],
    		
    	];
    }

	/*
	
	下载数据

	 */
	public function export_data($type=""){
       $export_type = array('apply_list'=>'甩单申请列表','order_list'=>'甩单中列表','success_list'=>'甩单成功列表','delete_list'=>'甩单删除列表','lose_list'=>'甩单失效列表','report_list'=>'举报中订单列表','history_list'=>"举报历史列表");
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();
        $function = 'export_data_'.$type;
        $result = $this->$function();
        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $cols = $result['cols'];
        foreach($cols as $k => $v){
            $PHPExcel->getActiveSheet()->setCellValue($word[$k].'1',$cols[$k][1]);
        }
        if($result['data']){
            $i = 2;
            $cols_count = count($cols);
            foreach($result['data'] as &$val){
                for($j=0;$j<$cols_count;$j++){
                    $PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
                }
                $i++;
            }
        }
        $outputFileName = $export_type[$type].date('Y-m-d H:i:s',time()).'数据.xls';
        $xlsWriter = new PHPExcel_Writer_Excel5($PHPExcel);
        ob_end_clean();//清除缓冲区,避免乱码
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$outputFileName.'"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $xlsWriter->save( "php://output" );
	}

}