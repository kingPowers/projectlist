<?php
/**
* 订单管理
*/
class OrderAction extends CommonAction
{
	private $keys = array("mobile"=>'申请人手机','username'=>'申请人姓名','names'=>'客户姓名','certiNumber'=>'客户身份证');
	private $page_num = 10;
	private $_static_ = '';
	public function __construct()
    {
    	parent::__construct();
		$this->_static_ = defined(_STATIC_)?_STATIC_:"http://daistatic.lingqianzaixian.com";
	}
	public function orderApply()
	{
        $return = $this->getParams('apply');
        $where = $return['where'];
        $params = $return['params'];
        $where['o.status'] = 1;
        $count = M("`order` o")
        ->join("member m on m.id=o.memberid")
        ->where($where)
        ->count();//echo $count;
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $lists = M("`order` o")
        ->field("o.*,m.mobile,m.username")
        ->join("member m on m.id=o.memberid")
        ->where($where)
        ->order("o.timeadd desc")
        ->limit($limit)
        ->select();
        $this->setPage("/Order/orderApply{$params}/p/*.html");
        $this->assign('list', $lists);//dump($lists);
        $this->assign("keys",$this->keys);
        $this->display();
	}
	public function pic_info()
	{
		$order_id = $this->_get("id",'trim');
		$picNames = array('客户照片','身份证照片','借款合同','抵押合同','行驶证','驾驶证','车辆登记证');
		$pic_url = M('order_pic')
	    ->field("user_pic,certi_pic,loanContract_pic,mortContract_pic,travelLicense_pic,driveLicense_pic,carRegistration_pic")
	    ->where("order_id={$order_id}")
	    ->find();
        $memberid = M("`order`")->where("id={$order_id}")->getField("memberid");
	    $pic = array();
	    foreach ($pic_url as $key => $value) 
	    {
	      $pic[$key] = array();
	      if($value != '')
	      {
	     	  $pic[$key] = explode("|",$value);
		      array_walk(
		        $pic[$key],
	            function(&$value, $k, $prefix){$value = $prefix.$value;},
	            $this->_static_."/Upload/".$key."/".$memberid."/"
		      );
	      }//if        
	    }//foreach
	    
	    $pic_key = array_keys($pic);
	    $pic_name = array_combine($pic_key, $picNames);
	    $pic_info['pic_url'] = $pic;  
		$pic_info['pic_name'] = $pic_name;//dump($pic_info);
		$this->assign("pic_info",$pic_info);
		$this->assign("pic_key",implode("|",$pic_key));
		$this->display();
	}
	public function GPS_info()
	{
		$order_id = $this->_get("id","trim");
		$GPS_info = M("`order`")->field("GPS_member,GPS_password,GPS_url")->where("id={$order_id}")->find();
		$this->assign("info",$GPS_info);
		$this->display();
	}
	public function distribute()
	{
		$order_id = $this->_get("id","trim");

		$receiveMember = M("member")->where("usertype=2 and status =1")->select();
		//dump($receiveMember);
		if($_POST && ($_POST['is_ajax'] == 1))
		{
			$memberid = $this->_post("memberid","trim");
			$order_id = $this->_post("order_id","trim");
            $dis_price = $this->_post("dis_price","trim");
			if(empty($memberid))$this->ajaxError("请选择发单人");
			if(empty($order_id))$this->ajaxError("订单ID错误");
            if(empty($dis_price))$this->ajaxError("请填写接单报价");
			if(false == ($order_info=M("`order`")->where("id={$order_id}")->find()))$this->ajaxError("订单不存在");
			if($order_info['status'] != 1)
			{
               $err = array('0'=>'不完整','2'=>'尚未修改','3'=>'已分发','4'=>'已完成','5'=>'已删除');
		       $this->ajaxError("订单【{$err[$order_info['status']]}】,不能分发");
			}
			try
			{
				if(!D('common')->inTrans())
				{
					D('common')->startTrans();
					$trans = true;
				}
				if(false == M("`order`")->where("id={$order_id}")->save(array("status"=>3)))throw new Exception("订单分发失败");
				$addData = array();
				$addData['order_id'] = $order_id;
				$addData['memberid'] = $memberid;
				$addData['timeadd'] = $timeadd;
                $addData['dis_price'] = $dis_price;
				if(false == M('order_distribute')->add($addData))throw new Exception("订单分发失败");
				if($trans)
				{
					D('common')->commit();
					$this->ajaxSuccess("分发成功");
				}
			}catch(Exception $ex){
                if($trans)
                {
                	D('common')->rollback();
                	$this->ajaxError($ex->getMessage());
                }
			}
		}
		$this->assign("receiveMember",$receiveMember);
		$this->assign("order_id",$order_id);
		$this->display();
	}
	public function refuse()
	{
		if($_POST && ($_POST['is_ajax']==1))
		{
			$order_id = $this->_post("order_id","trim");
			if(false == ($order_info = M("`order`")->where("id={$order_id}")->find()))$this->ajaxError("订单不存在");
			if($order_info['status'] != 1)
			{
               $err = array('0'=>'不完整','2'=>'已打回修改','3'=>'已分发','4'=>'已完成','5'=>'已删除');
		       $this->ajaxError("订单【{$err[$order_info['status']]}】");
			}
			if(fase == M("`order`")->where("id={$order_id}")->save(array("status"=>2,"edit_num"=>1)))$this->ajaxError("操作失败，请稍后再试");
			$this->ajaxSuccess("操作成功");
		}
	}
	public function receiveOrderList()
	{
		$_keys = array("mobile"=>'接单用户手机','username'=>'接单用户名','applyUser'=>'申请用户名','applyMobile'=>'申请用户手机','names'=>'客户姓名','certiNumber'=>'客户身份证');
		$_keys1 = array("1"=>"未接单","2"=>"已接单");
		$return = $this->getParams('receive');
        $where = $return['where'];
        $params = $return['params'];     
        //是否接单
        $key1 = $this->_get("key","trim");
    	switch ($key1) {
    		case '1':
    			$where['o.status'] = 3;
    			break;
    		case '2':
    			$where['o.status'] = 4;
    			break;
    		default:
    			$where['o.status'] = array("in","3,4");
    			break;
    	}
    	$params .= "/key/{$key1}";
        $count = M("order_distribute od")
        ->join("member m on m.id=od.memberid")
        ->join("`order` o on od.order_id=o.id")
        ->join("left join (select m.username,m.mobile,m.id as applyId from `order` o left join member m on m.id=o.memberid group by o.memberid) as um on um.applyId=o.memberid")
        ->where($where)
        ->count();
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $lists = M("order_distribute od")
        ->field("o.*,od.timeadd as distribute_time,od.receive_time,m.mobile,m.username,um.username as applyUser,um.mobile as applyMobile")  
        ->join("member m on m.id=od.memberid")
        ->join("`order` o on od.order_id=o.id")
        ->join("left join (select m.username,m.mobile,m.id as applyId from `order` o left join member m on m.id=o.memberid group by o.memberid) as um on um.applyId=o.memberid")
        ->where($where)
        ->limit($limit)
        ->order("distribute_time desc")
        ->select();//dump($this->page);
        $this->setPage("/Order/receiveOrderList{$params}/p/*.html");
        $this->assign('list', $lists);//dump($lists);
        $this->assign("keys",$_keys);//echo M('order_distribute od')->getLastSql();
        $this->assign("keys1",$_keys1);
        $this->display();
	}
	public function export_apply_list()
	{
		$this->export_data('apply_list');
	}
	public function export_data_apply_list()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$params = $return['params'];
		$where['o.status'] = 1;
		$lists = M("`order` o")
        ->field("o.*,m.mobile,m.username")
        ->join("member m on m.id=o.memberid")
        ->where($where)
        ->order("o.timeadd desc")
        ->select();
        foreach ($lists as &$value) 
		{
           $value['sex'] = ($value['sex'] == 0)?"男":"女";
		}
		$cols = array(
            array('username','申请用户名'),
            array('mobile','申请人手机'),
            array('names','客户姓名'),
            array('sex','客户性别'),
            array('certiNumber','身份证号'),
            array('loan_city','借款城市'),
            array('loan_company','借款公司'),
            array('loan_money','借款金额'),
            array('mort_company','抵押公司'),
            array('return_num','返还期数'),
            array('car_brand','车辆型号'),
            array('plate_num','车牌号码'),
            array('frame_num','车架号'),
            array('GPS_member','GPS账号'),
            array('GPS_password','GPS密码'),
            array('GPS_url','平台地址'),
            array('trail_price','拖车价格'),
            array('timeadd','申请时间'),
        );
        return array('data'=>$lists,'cols'=>$cols);
	}
	public function export_receiveOrder_list()
	{
		$this->export_data('receiveOrder_list');
	}
	public function export_data_receiveOrder_list()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$params = $return['params'];
        $is_receive = $this->_get('key','trim');
        switch ($is_receive) {
            case 1:
                $where['o.status'] = 3;
                break;
            case 2:
                $where['o.status'] = 4;
                break;
            default:
                $where['o.status'] = array("in","3,4");
                break;
        }
		$lists = M("order_distribute od")
        ->field("o.*,od.timeadd as distribute_time,od.receive_time,m.mobile,m.username,um.username as applyUser,um.mobile as applyMobile")  
        ->join("member m on m.id=od.memberid")
        ->join("`order` o on od.order_id=o.id")
        ->join("left join (select m.username,m.mobile,m.id as applyId from `order` o left join member m on m.id=o.memberid group by o.memberid) as um on um.applyId=o.memberid")
        ->where($where)
        ->order("distribute_time desc")
        ->select();//dump($this->page);
        foreach ($lists as &$value) 
		{
           $value['sex'] = ($value['sex'] == 0)?"男":"女";
		}
		$cols = array(
            array('username','接单用户名'),
            array('mobile','接单用户手机'),
            array('applyUser','申请用户名'),
            array('applyMobile','申请用户手机'),
            array('names','客户姓名'),
            array('sex','客户性别'),
            array('certiNumber','身份证号'),
            array('loan_city','借款城市'),
            array('loan_company','借款公司'),
            array('loan_money','借款金额'),
            array('mort_company','抵押公司'),
            array('return_num','返还期数'),
            array('car_brand','车辆型号'),
            array('plate_num','车牌号码'),
            array('frame_num','车架号'),
            array('GPS_member','GPS账号'),
            array('GPS_password','GPS密码'),
            array('GPS_url','平台地址'),
            array('trail_price','拖车价格'),
            array('distribute_time','发单时间'),
            array('receive_time','接单时间')
        );
        return array('data'=>$lists,'cols'=>$cols);
	}
	public function getParams($type)
	{
		$where = array();$params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if(!empty($value))
        {
        	switch ($key) {
        		case 'mobile':
        			$where['m.mobile'] = $value;
        			break;
        		case 'username':
        			$where['m.username'] = array("like","%".$value."%");
        			break;
        		case 'applyUser':
        			$where['um.username'] = array("like","%".$value."%");
        			break;
        		case 'applyMobile':
        			$where['um.mobile'] = $value;
        			break;
        		case 'names':
        			$where['o.names'] = array("like","%".$value."%");
        			break;
        	    case 'certiNumber':
        			$where['o.certiNumber'] = $value;
        			break;
        	}
        	$params .= "/k/{$key}/v/{$value}";
        }
        if($type=='apply')
        {
	        if($starttime && !$endtime){
	            $where['o.timeadd'] = array('egt',$starttime);
	            $params .= '/starttime/'.$starttime;
	        }else if(!$starttime && $endtime){
	            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
	            $where['o.timeadd'] = array('elt',$endtime);
	            $params .= '/endtime/'.$endtime;
	        }else if($starttime && $endtime){
	            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
	            $where['o.timeadd'] = array('between',array($starttime,$endtime));
	            $params .= '/starttime/'.$starttime;
	            $params .= '/endtime/'.$endtime;
	        }
	    }elseif($type=="receive")
	    {
	    	if($starttime && !$endtime){
	            $where['od.timeadd'] = array('egt',$starttime);
	            $params .= '/starttime/'.$starttime;
	        }else if(!$starttime && $endtime){
	            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
	            $where['od.timeadd'] = array('elt',$endtime);
	            $params .= '/endtime/'.$endtime;
	        }else if($starttime && $endtime){
	            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
	            $where['od.timeadd'] = array('between',array($starttime,$endtime));
	            $params .= '/starttime/'.$starttime;
	            $params .= '/endtime/'.$endtime;
	        }
	    }
        return $data = array("where"=>$where,"params"=>$params);
	}
	/*
	
	下载数据

	 */
	public function export_data($type=""){
       $export_type = array('apply_list'=>'订单申请列表','recriveOrder_list'=>'分发订单列表');
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
?>