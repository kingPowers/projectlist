<?php
/*
  金牌经纪人
 */
class AgentAction extends CommonAction{
	private $agent_info = array();//经纪人信息
	private $error;
	private $agent_status = array("state" =>1);//是否满足认证经纪人
	private $agent_setting = array();//经纪人setting
  private $agent_haddle = array("is_delete" => "删除订单","is_edit" => "修改订单","is_again" => "重新上架","is_finish" => "完成订单");   
  private $report_status = array("0"=>"","1"=>"举报订单","2"=>"已举报");
	public function __construct(){
       //exit("<div style='color:red;font-size:50px;margin:50px 100px;'>该功能正在维护中，请稍后访问！</div>");
		   parent::__construct();
		  // exit;
		  //获取金牌经济人信息
		    $params = array();
        $params['_cmd_'] = "member_changeuserinfo";
		    $params['type'] = "member_info";
        $service_res = $this->service($params);
        $this->agent_info = $service_res['dataresult']['member_info'];
        //dump($this->agent_info);
        //获取经纪人setting
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "setting";
        $service_res = $this->service($params);
        $this->agent_setting = $service_res['dataresult'];
        //dump($service_res);
	}
	/*
	
	 金牌经纪人认证

	 */
	public function agent_authentication(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/Agent/agent_authentication"));
		if($member_info['nameStatus'] == 0)redirect("/credit/realname?returnurl=".urlencode("/Agent/agent_authentication"));
    if($this->agent_info['isPayGold'] == '0')redirect("/member/account");
		if($this->agent_info['isOpenGold'] == 1)redirect("/agent/agent_account");  
		//获取省份
		$provinces = $this->get_privince();
		if(empty(session('_agent_mobile_')))session('_agent_mobile_',md5(time()));
		//dump($provinces);
		$this->assign('provinces',$provinces);
    $this->assign('_agent_mobile_',session('_agent_mobile_'));
		$this->assign("mobile",$this->agent_info['mobile']);
		$this->assign("names",$this->agent_info['names']);
		$this->assign("certiNumber",$this->agent_info['certiNumber']);
		$this->assign("open_money",$this->agent_setting['pay_money']);
		$this->display();
	}
  //是否满足认证经纪人：1：不满足；0：满足
	public function get_agent_status($type=''){		
    	    if($this->agent_info['nameStatus'] == 0){
               $this->agent_status['message'] = "您尚未实名认证";
               $this->agent_status['confirm'] = "去认证";
               $this->agent_status['url'] = "/credit/realname?from=agent&returnurl=".urlencode("/member/openAgent");
    	    }elseif($this->agent_info['openFuyouStatus'] == 0){
               $this->agent_status['message'] = "您尚未开通金账户";
               $this->agent_status['confirm'] = "去开通";
               $this->agent_status['url'] = "/credit/bindCard?returnurl=".urlencode("/member/openAgent");
		    }elseif(empty($this->agent_info['fuyou_login_id'])){
               $this->agent_status['message'] = "您尚未绑定金账户";
               $this->agent_status['confirm'] = "去绑定";
               $this->agent_status['url'] = "/credit/bindFuyou?returnurl=".urlencode("/member/openAgent");    	
		    }else{
			   $this->agent_status['state'] = 0;
		    }
    if($type == '1'){
        return $this->agent_status;
    }		    
		if($this->_post("is_get")){
			$agent_status = json_encode($this->agent_status);
		    $this->ajaxSuccess("获取成功",$agent_status);			
		}
	}
   
	//发送认证验证码
	public function sen_authentication_sms(){
	  if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
      $params = array();
      $params['_cmd_'] = "goldAgent";
      $params['type'] = "openAgentSendSms";
      if(empty($_POST['_agent_mobile_']) || empty(session('_agent_mobile_')) || $_POST['_agent_mobile_']!=session('_agent_mobile_') || S(session('_agent_mobile_'))>10){
        session('_agent_mobile_',null);
        $redirecturl ="/agent/agent_authentication";
        $this->ajaxError('页面已失效，请刷新页面',$redirecturl);
      }
      S(session('_agent_mobile_'),S(session('_agent_mobile_'))+1,2*10*60);
      session('_agent_mobile_',null);
      $service_res = $this->service($params);  
      if(0===$service_res['errorcode']){
        $this->ajaxSuccess("验证码已成功发送");
      }else{
        $this->ajaxError($service_res['errormsg']);
      }
	}
	//提交认证
	public function submit_authentication(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/agent_authentication"));
		 if(isset($_POST) && !empty($_POST['province'])){
        $params = array();
        $params['_cmd_'] = "goldAgent";
		    $params['type'] = "openAgent";
        $params['province'] = $this->_post("province","trim");
		    $params['city'] = $this->_post("city","trim");
		    $params['company_name'] = $this->_post("company_name","trim");
		    $params['company_full_name'] = $this->_post("comany_full_name","trim");
		    $params['sms_code'] = $this->_post("verify_code","trim");   
		    $service_res = $this->service($params);
		    if(0===$service_res['errorcode']){
               $this->ajaxSuccess($service_res['dataresult']);
		    }else{
                $this->ajaxError($service_res['errormsg'],$reurl);
		    }

        }
	}
  public function contract(){
    if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
          $_SESSION['token'] = $_REQUEST['token'];
        }
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/contract"));

    //dump($member_info);
    $this->assign("member_info",$member_info);
    //dump($this->agent_setting);
    $this->assign("agent_setting",$this->agent_setting);
    $this->display();
  }
	/*
	
	经纪人账户中心

	 */
	public function agent_account(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/agent_account"));     
        if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
        //限制用户名显示
        if(mb_strlen($this->agent_info['nickname'],'utf8')>4){
        	$this->agent_info['nickname'] = mb_substr($this->agent_info['nickname'],0,4,'utf8')."...";
        }
        //限制公司简称显示
        if(mb_strlen($this->agent_info['company_name'],'utf8')>4){
        	$this->agent_info['company_name'] = mb_substr($this->agent_info['company_name'],0,4,'utf8')."...";
        }
        //dump($this->agent_info);
        $this->assign("agent_info",$this->agent_info);
		    $this->display();
	}
	/*
	
	 他人甩单列表及详情

	 */
	public function order_lists(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/order_lists"));
     // dump($_POST);
		  import("Think.ORG.Util.wxjs");
    	$wxjs = new wxjs;
    	$signPackage = $wxjs->GetSignPackage();
    	$this->assign('signPackage',$signPackage);
    	$this->assign('isWeixin',strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false?0:1);
    	$this->assign('islogin',$this->isLogin()?1:0);
		  $provinces = $this->get_privince();
		  //$return = (isset($_REQUEST['return']) && ($_REQUEST['return']=="info"))?json_decode($_COOKIE['order_params'],true):$this->getParams();//判断是否来自订单详情
      $return = $this->getParams();
      if($return['jobs'] == "全部"){
        $return['jobs'] = "";
      }	
        //setcookie("order_params",json_encode($return),time()+60*60);//保存用户筛选条件
        $params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "orderList";
        $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;   
        $params['number'] = 10;    
        $params['status'] = $return['status'];
        $params['is_fullmoney'] = $return['is_fullmoney'];	
        $params['jobs'] = $return['jobs'];
        $params['province'] = $return['province'];
        $params['city'] = $return['city'];
        $service_res = $this->service($params);
        $order_lists = $service_res['dataresult'];
        $order_lists = $this->get_order_status($order_lists);     
        //dump($this->order_status);
        //dump($order_lists);
        //dump($return);
        if($_REQUEST['is_ajax']){
   	  	   $status = empty($order_lists)?0:1;  	  	   
   	  	   if($status) $this->ajaxSuccess("加载成功",$order_lists);
   	  	    $this->ajaxError("加载失败");  	  	   
   	    }
   	    $more = (count($order_lists) >=  $params['number'])?1:0;
   	    $this->assign("more",$more);
   	    $this->assign("params",$return);
        $this->assign("status",$this->agent_setting['status']);
        $this->assign("jobs",$this->agent_setting['jobs']);
        $this->assign("is_fullmoney",$this->agent_setting['full_money']);	
        $this->assign("provinces",$provinces);
        $this->assign("order_lists",$order_lists);
		    $this->display();        		
	}
	//他人甩单详情/我已解锁订单详情
	public function order_info(){
	  //订单归属：0：他人；1：我已解锁
		$order_affiliation = ($this->_get("order_affiliation","trim") == 1)?1:0; 
    $order_id = $this->_get("order_id","trim");
		$reurl = ($order_affiliation == 1)?"/agent/my_unlocked_order":("/agent/order_info/order_id/".$order_id); 
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode($reurl));
		if($order_affiliation == 1){
			if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
		}		
		$order_memberid = $this->_get("memberid","trim");
		if(!order_id) redirect("/agent/order_lists");
		$params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "orderDetail";
        $params['order_id'] = $order_id;
        $params['memberid'] = $order_memberid;
        $service_res = $this->service($params);
        $order = $service_res['dataresult'];
        $order_info = $this->get_order_status($order);        
        if($order_affiliation == 0){  
          $this->assign("order_info",$order_info);       
        	$this->assign("unlockmoney",$this->agent_setting['unlock_money']);
        	$this->display();
        }elseif($order_affiliation == 1){
          $order_info['operate'] = $this->report_status[$this->_get("reportStatus","trim")];
          $order_info['reportStatus'] = $this->_get("reportStatus",'trim');
          $order_info['transfer_id'] = $this->_get("transfer_id","trim");
          $this->assign("order_info",$order_info);  
          //dump($order_info);
          $this->display("unlocked_order_info");
        }       
	}
  //解锁电话
	public function get_mobile(){
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
		if($this->_post("is_ajax","trim")){
			import("Think.ORG.Util.Fuyou");
		    $fuyou = new Fuyou();
		    $balance = 1 ;
		    $availAmount = $fuyou->BalanceAction($this->agent_info['memberid']);
		    $amount = $this->agent_setting['unlock_money']*100;
		    if(false==$availAmount) $balance = 0;
    	    if((false!= $availAmount) && ($availAmount['ca_balance']/100-$amount/100<0))$balance = 0;
    	    $order_id = $this->_post("order_id",'trim');
    	    if($this->agent_info['isOpenGold'] == 0){
               $this->agent_status['message'] = "请您注册成为金牌经纪人";
               $this->agent_status['confirm'] = "去注册";
               $this->agent_status['url'] = "/agent/agent_authentication";
               $agent_status = json_encode($this->agent_status);
               $this->ajaxError("获取失败",$agent_status);
    	    }elseif($balance == 0){
    	       $this->agent_status['message'] = "您的账户余额不足";
               $this->agent_status['confirm'] = "去充值";
               $this->agent_status['url'] = "/credit/carry?returnurl=".urlencode("/Agent/order_info/order_id/{$order_id}/order_affiliation/0");
               $agent_status = json_encode($this->agent_status);
               $this->ajaxError("获取失败",$agent_status);
    	    }else{
    	       $this->agent_status['state'] = 0;
    	    }
    	      $params = array();
            $params['_cmd_'] = "goldAgent";
            $params['type'] = "addUnlock";
            $params['order_id'] = $order_id;
            $service_res = $this->service($params);
            if($service_res['errorcode'] === 0){
                $this->ajaxSuccess($service_res['dataresult']);
            }else{
            	$agent_status = json_encode($this->agent_status);
            	$this->ajaxError($service_res['errormsg'],$agent_status);
            }
            $this->ajaxError("成功",'0');
			
		}
	}
    public function getParams(){
        $data = array();
        $data['province'] = $this->_request("province","trim");
        $data['city'] = $this->_request("city","trim");
        $data['status'] = $this->_request("status","trim");
        $data['is_fullmoney'] = $this->_request("is_fullmoney","trim");
        $data['jobs'] = $this->_request("jobs","trim");
        return $data;
    }
    /*
    
     我要甩单

     */
	public function order_submit(){ 
        if(!isset($_SESSION['_order_session_']) || $_SESSION['_order_session_'] == ''){ 
              $this->set_session('_order_session_'); 
          }
        //提交新的甩单
        if(($_POST['names']) && empty($_POST['order_id'])){             
            if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
            if($this->agent_info['isOpenGold'] == 0)$this->ajaxError("请先注册金牌经纪人");             
            if(!$this->valid_session('_order_session_')) $this->ajaxError("请不要重复提交");
            if(false == $this->is_mobile($this->_post("mobile",'trim'))) $this->ajaxError("手机号不正确");   	
            $params = array();
            $params['_cmd_'] = "goldAgent";
            $params['type'] = "addOrder";
            $params['names'] = $this->_post("names",'trim');
            $params['age'] = $this->_post("age",'trim');
            $params['province'] = explode("-",$this->_post("userAddress"))[0];
            $params['city'] = explode("-",$this->_post("userAddress"))[1];
            $params['car_province'] = explode("-",$this->_post("carAddress"))[0];
            $params['car_city'] = explode("-",$this->_post("carAddress"))[1];
            $params['jobs'] = $this->_post("jobs",'trim');
            $params['brands'] = $this->_post("brands",'trim');
            $params['buy_time'] = $this->_post("buy_time",'trim');
            $params['car_price'] = $this->_post("car_price",'trim');
            $params['car_drive'] = $this->_post("car_drive",'trim');
            $params['car_frame_number'] = $this->_post("car_frame_number",'trim');
            $params['is_fullmoney'] = $this->_post("full_money",'trim');
            $params['mort_company'] = $this->_post("mort_company",'trim');
            $params['loan_money'] = $this->_post("loanmoney",'trim');
            $params['mobile'] = $this->_post("mobile",'trim');
            $params['remark'] = $this->_post("remark",'trim');
            $service_res = $this->service($params);
            if($service_res['errorcode'] === 0){
            	   $this->set_session('_order_session_');
                 $this->ajaxSuccess($service_res['dataresult']);
            }else{
                 $this->ajaxError($service_res['errormsg']);
            }     	
        }
        //提交修改的订单
        if(($_POST['names']) && ($_POST['order_id'])){
          if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
          if($this->agent_info['isOpenGold'] == 0)$this->ajaxError("请先注册金牌经纪人");
        	$service_res = $this->sub_edit_order();
        	if($service_res['errorcode'] === 0){
            	   $this->set_session('_editorder_session_');
                 $this->ajaxSuccess($service_res['dataresult']);
            }else{
                 $this->ajaxError($service_res['errormsg']);
            }
        }
        //获取需要修改的订单信息
        if($_GET['id']){
            if(!isset($_SESSION['_editorder_session_']) || $_SESSION['_editorder_session_']==''){ 
              $this->set_session('_editorder_session_'); 
            }
            $params = array();
            $params['_cmd_'] = "goldAgent";
            $params['type'] = "editOrder";
            $params['order_id'] = $this->_get("id",'trim');
            $service_res = $this->service($params);
            $order_info = $service_res['dataresult'];//dump($order_info);
            if($order_info['is_fullmoney'] == '1'){
            	$order_info['full_money_name'] = '是';
            }else{
                $order_info['full_money_name'] = '否';
            }
            $order_info['userAddress'] = $order_info['province']."-".$order_info['city'];
            $order_info['carAddress'] = $order_info['car_province']."-".$order_info['car_city'];   
            $this->assign("order_info", $order_info); 
            $this->assign("_editorder_session_",session('_editorder_session_'));            
        }
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/order_submit"));
        if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
        $this->assign("_order_session_",session('_order_session_'));
        //echo session('_order_session_');
        $this->assign("provinces",$this->get_privince());
		    $this->display();
	}
  //提交修改信息
	public function sub_edit_order(){
            if(!$this->valid_session('_editorder_session_')) $this->ajaxError("请不要重复提交");     	
            $params = array();
            $params['_cmd_'] = "goldAgent";
            $params['type'] = "submitEditOrder";
            $params['names'] = $this->_post("names",'trim');
            $params['order_id'] = $this->_post("order_id",'trim');
            $params['age'] = $this->_post("age",'trim');
            $params['province'] = explode("-",$this->_post("userAddress"))[0];
            $params['city'] = explode("-",$this->_post("userAddress"))[1];
            $params['car_province'] = explode("-",$this->_post("carAddress"))[0];
            $params['car_city'] = explode("-",$this->_post("carAddress"))[1];
            $params['jobs'] = $this->_post("jobs",'trim');
            $params['brands'] = $this->_post("brands",'trim');
            $params['buy_time'] = $this->_post("buy_time",'trim');
            $params['car_price'] = $this->_post("car_price",'trim');
            $params['car_drive'] = $this->_post("car_drive",'trim');
            $params['car_frame_number'] = $this->_post("car_frame_number",'trim');
            $params['is_fullmoney'] = $this->_post("full_money",'trim');
            $params['mort_company'] = $this->_post("mort_company",'trim');
            $params['loan_money'] = $this->_post("loanmoney",'trim');
            $params['mobile'] = $this->_post("mobile",'trim');
            $params['remark'] = $this->_post("remark",'trim');
            $service_res = $this->service($params);
            return $service_res;  		
	}
	/*
	我的订单列表
	 */
	//我的甩单列表
	public function my_order_lists(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_order_lists"));       
        if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
        $params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "myOrderList";
        $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
        $params['number'] = 10;
        $service_res = $this->service($params);
        $order_lists =  $service_res['dataresult'];
        $order_lists = $this->get_order_status($order_lists);
        foreach($order_lists as $key1 => &$val1){
            foreach($val1 as $key2 => &$val2){
              //可操作按钮
              if((array_key_exists($key2, $this->agent_haddle)) && ($val2 == 1)){
                $val1['operate'][$key2] = $this->agent_haddle[$key2];
              }
            }
            $val1['operate'] = array_reverse($val1['operate']);
         } 
        //dump($order_lists['0']);
        if($_REQUEST['is_ajax']){
   	  	   $status = empty($order_lists)?0:1; 	  	   
   	  	   if($status) $this->ajaxSuccess("加载成功",$order_lists);
   	  	    $this->ajaxError("加载失败");  	  	   
   	    }
   	    $more = (count($order_lists) >=  $params['number'])?1:0;
   	    $this->assign("more",$more);
   	    $this->assign("order_lists",$order_lists);
        $this->display();
	}
	//删除我的订单
	public function delete_my_order(){
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
		if($_POST['id']){
			 $params['_cmd_'] = "goldAgent";
             $params['type'] = "deleteMyOrder";
             $params['order_id'] = $_POST['id'];
             $service_res = $this->service($params);
             if($service_res['errorcode'] === 0){
                $this->ajaxSuccess("删除成功");
            }else{
                $this->ajaxError($service_res['errormsg']);
            }
		}else{
			$this->ajaxError("订单有误");
		}
	}
	//重新上架
	public function passed_again(){
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
		if($_POST['id']){
			 $params['_cmd_'] = "goldAgent";
             $params['type'] = "passedAgain";
             $params['order_id'] = $_POST['id'];
             $service_res = $this->service($params);
             if($service_res['errorcode'] === 0){
                $this->ajaxSuccess("上架成功");
            }else{
                $this->ajaxError($service_res['上架失败']);
            }
		}else{
			$this->ajaxError("订单有误");
		}		
	}
	//订单完成
	public function order_success(){
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
		if($_POST['id']){
			 $params['_cmd_'] = "goldAgent";
             $params['type'] = "finishMyOrder";
             $params['order_id'] = $_POST['id'];
             $service_res = $this->service($params);
             if($service_res['errorcode'] === 0){
                $this->ajaxSuccess($service_res['dataresult']);
            }else{
                $this->ajaxError($service_res['errormsg']);
            }
		}else{
			$this->ajaxError("订单有误");
		}				
	}
	//我的甩单详情
	public function my_order_info(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_order_lists"));
		if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
		$order_id = $this->_get("order_id","trim");
		if(!order_id) redirect("/agent/order_lists");
		$params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "editOrder";
        $params['order_id'] = $order_id;
        $service_res = $this->service($params);
        //dump($service_res);
        $order = $service_res['dataresult'];
        $order_info = $this->get_order_status($order);
            foreach($order_info as $key2 => &$val2){
              //可操作按钮
              if((array_key_exists($key2, $this->agent_haddle)) && ($val2 == 1)){
                $order_info['operate'][$key2] = $this->agent_haddle[$key2];
              } 
            }
        $order_info['operate'] = array_reverse($order_info['operate']);
        //dump($order_info);
        $this->assign("order_info",$order_info);
		$this->display();
	}
	//已解锁订单
	public function my_unlocked_order(){
		    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_unlocked_order"));       
        if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
        $params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "lockedOrderList";
        $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
        $params['number'] = 10;
        $service_res = $this->service($params);
        $order_lists =  $service_res['dataresult'];       
        $order_lists = $this->get_order_status($order_lists);
        foreach($order_lists as $key1 => &$val1){
            foreach($val1 as $key2 => &$val2){
              //可操作按钮
              if($key2 == "reportStatus"){
                $val1['report_operate'] = $this->report_status[$val2];
              }
            }
         }
        //dump($order_lists);
        if($_REQUEST['is_ajax']){
   	  	   $status = empty($order_lists)?0:1;
   	  	   if($status) $this->ajaxSuccess("加载成功",$order_lists);
   	  	    $this->ajaxError("加载失败");  	  	   
   	    }   
   	    $more = (count($order_lists) >=  $params['number'])?1:0;
   	    $this->assign("more",$more);
   	    $this->assign("order_lists",$order_lists);
        $this->display();
	}
	public function delete_my_unlock(){
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
		if($_POST['order_id']){
             $params = array();
             $params['_cmd_'] = "goldAgent";
             $params['type'] = "deleteMyLockedOrder";
             $params['order_id'] = $this->_post("order_id","trim");
             $service_res = $this->service($params);
             if($service_res['errorcode'] === 0){
                 $this->ajaxSuccess($service_res['dataresult']);
             }else{
                 $this->ajaxError($service_res['errormsg']);
             }
		}else{
			$this->ajaxError("订单信息有误");
		}
	}
	//解锁订单的详情
	public function unlocked_order_info(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_unlocked_order"));  
    if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
		$this->display();
	}
	//举报订单
	public function order_report(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_unlocked_order"));  
		if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
		if(!isset($_SESSION['_report_session_']) || $_SESSION['_report_session_']=='') { 
                $this->set_session('_report_session_'); 
        }
		if($this->_get("order_id","trim")){		 
      $this->assign("_report_session_",$_SESSION['_report_session_']);
			$this->assign("order_id",$this->_get("order_id","trim"));
			$this->assign("transfer_id",$this->_get("transfer_id","trim"));
			$this->display();
		}
		if($this->_post("order_id","trim")){
		    if(!$this->valid_session('_report_session_')) $this->ajaxError("请不要重复提交");
        $is_upload = 0;
        foreach($_FILES as &$value){
          if($value['error'] == 0){
             $is_upload = 1;unset($value);break;
          }
        }
        	if($is_upload){
               $type = "report";
               $pic_urls = $this->uploadImage($this->agent_info['memberid'],$type);
               if(false == $pic_urls)$this->ajaxError($this->error);
          }	   
		      $params = array();
        	$params['_cmd_'] = "goldAgent";
        	$params['type'] = "addReportOrder";
        	$params['content'] = $this->_post('content','trim');
        	$params['order_id'] = $this->_post('order_id','trim'); 
        	$params['transfer_id'] = $this->_post('transfer_id','trim');
          $params['report_pic1'] = 1; 
        	$service_res = $this->service($params);
        	if($service_res['errorcode'] === 0){
        		$this->set_session('_report_session_');
        		$save_id = M('gold_report')->where("id={$service_res['dataresult']['add_id']}")->save($pic_urls);
        		$this->ajaxSuccess($service_res['dataresult']['message']);
        	}else{
        		$this->set_session('_report_session_');
        		$this->ajaxError($service_res['errormsg']);
        	}
		}
		
	}
  //举报详情
  public function report_info(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/my_unlocked_order"));  
    if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
    $params = array();
    $params['_cmd_'] = "goldAgent";
    $params['type'] = "reprotOrderInfo";
    $params['order_id'] = $this->_get('order_id','trim'); 
    $params['transfer_id'] = $this->_get('transfer_id','trim');
    $service_res = $this->service($params);
    //dump($service_res);
    $this->assign("report_info",$service_res['dataresult']);
    $this->display();
  }
	/*
	
	修改经纪人信息

	 */
	
	//修改信息
	public function edit_agent_info(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/edit_agent_info"));  
		if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
        if(!isset($_SESSION['_session_']) || $_SESSION['_session_']=='') { 
            $this->set_session('_session_'); 
        } 
        if($_POST['nickname']){
        	if(!$this->valid_session('_session_')) $this->ajaxError("请不要重复提交");
        	$params = array();
        	$params['_cmd_'] = "goldAgent";
        	$params['type'] = "editAgentInfo";
        	$params['nickname'] = $this->_post('nickname','trim');
        	$params['company_full_name'] = $this->_post('company_full_name','trim'); 
        	$params['company_name'] = $this->_post('company_name','trim');
        	$params['sms_code'] = $this->_post('verify_code','trim'); 
        	$params['pic_card2'] = 1;
        	$service_res = $this->service($params);
        	if(($service_res['errorcode']) === 0){
        		$this->set_session('_session_');
        		$type = "agent";
            $is_upload = ($_FILES['pic_card2']['error'] == 0)?1:0; 
            if($is_upload){
        		    if(!($this->uploadImage($this->agent_info['memberid'],$type))) $this->ajaxError($this->error);
            }
        		$this->ajaxSuccess($service_res['dataresult']);
        	}else{        		
        		$this->ajaxError($service_res['errormsg']);
        	} 
            
        }
        if(empty(session('_edit_mobile_')))session('_edit_mobile_',md5(time()));
        $this->assign('_edit_mobile_',session('_edit_mobile_'));
        $this->assign("_session_",$_SESSION['_session_']);
        $this->assign("agent_info",$this->agent_info);
		$this->display();
	}
	/*public function get_src(){
		if($_FILES){
			$f = json_encode($_FILES);
			$this->ajaxSuccess("成功",$f);
		}else{
			$this->ajaxError("失败");
		}
	}*/
	//发送修改信息验证码
	public function send_edit_agent_sms(){
      if(false==($member_info = $this->getMemberInfo()))$this->ajaxError("请重新登录");
      $params = array();
      $params['_cmd_'] = "goldAgent";
      $params['type'] = "editAgentSendSms";
      if(empty($_POST['_edit_mobile_']) || empty(session('_edit_mobile_')) || $_POST['_edit_mobile_']!=session('_edit_mobile_') || S(session('_edit_mobile_'))>10){
        session('_edit_mobile_',null);
        $redirecturl ="/agent/edit_agent_info";
        $this->ajaxError('页面已失效，请刷新页面',$redirecturl);
      }
      S(session('_agent_mobile_'),S(session('_agent_mobile_'))+1,2*10*60);
      session('_agent_mobile_',null);
      $service_res = $this->service($params);  
      if(0===$service_res['errorcode']){
        $this->ajaxSuccess($service_res['dataresult']);
      }else{
        $this->ajaxError($service_res['errormsg']);
      }
	}
	//关于甩单
	public function about(){
		$this->display();
	}
	//消费记录
	public function purchase_history(){
		if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/purchase_history"));  
		if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
	    $params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "transList";
        $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
        $params['number'] = 10;
        $service_res = $this->service($params);
        $purchase_list = $service_res['dataresult'];
        if($_REQUEST['is_ajax']){
   	  	   $status = empty($purchase_list)?0:1;   	  	   
   	  	   if($status) $this->ajaxSuccess("加载成功",$purchase_list);
   	  	    $this->ajaxError("加载失败");  	  	   
   	    }
   	    $more = (count($purchase_list) >=  $params['number'])?1:0;
   	    $this->assign("more",$more);
        $this->assign("purchase_list",$purchase_list);
		$this->display();
	}
	//等级说明
	public function grades_description(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/grades_description"));  
    if($this->agent_info['isOpenGold'] == 0)redirect("/agent/agent_authentication");
    $params = array();
    $params['_cmd_'] = "goldAgent";
    $params['type'] = "levelDes";
    $service_res = $this->service($params);
    $grades_list = $service_res['dataresult'];
    //dump($grades_list);
    $this->assign("grades_list",$grades_list);
		$this->display();
	}
	
	//邀请记录
  public function recommand(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/agent/".ACTION_NAME));
   	  $params = array();
   	  $params['_cmd_'] = "goldAgent";
   	  $params['type'] = "recommandList";
   	  $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
   	  $service_res = $this->service($params);
   	  if($_REQUEST['is_ajax']){
   	  	$status = $service_res['dataresult']['recommandList']?1:0;
   	  	$this->ajaxReturn($service_res['dataresult']['recommandList'],"加载成功",$status);
   	  }//dump($service_res['dataresult']);
   	  $this->assign('recommand',$service_res['dataresult']);
   	  $this->display();
  }
	
  //甩单流程
  public function order_process(){
    $this->display();
  }
  //支付认证费用
  public function pay_money(){
    $params = array();
    $params['_cmd_'] = "goldAgent";
    $params['type'] = "openAgent";
    $service_res = $this->service($params);
    if($service_res['errorcode'] === 0){
      $this->ajaxSuccess($service_res['dataresult']);
    }else{
      $this->ajaxError("支付失败，请重新再试");
    }
  }
	public function set_session($name) { 
        $_SESSION[$name] = md5(microtime(true)); 
  }
  public function valid_session($name) { 
        $return = $_REQUEST[$name] === $_SESSION[$name] ? true : false; 
        return $return; 
  } 
    //验证手机号码
  public function is_mobile($mobile){
   return (preg_match("/^1[34578]\d{9}$/", $mobile))?true:false;  
  }
   //获取省份
	public function get_privince(){
		$lists = M('car_city')->where('city_code=0')->select();
      foreach($lists as &$list){
        $list['province_name'] = explode(" ",$list['province_name'])[1];
        $list['names'] = $list['province_name'];
      }
        return $lists;
	}
     //获取城市
	public function get_citys(){
		if(!empty($this->_post('province_code','trim'))){
		   $province_code = $this->_post('province_code','trim');
		   $lists = M('car_city')->where("province_code={$province_code} and city_code!=0")->select();
           $city = json_encode($lists);
           $this->ajaxSuccess("获取成功",$city);
        }else{
           $this->ajaxError("省份不存在");
        }
	}
	//获取订单是否全款。单主是否为V
	public function get_order_status($data){
       foreach($data as $key1 => &$val1){
          if(is_array($val1)){
        	  //是否全款
            $val1['isFullMoney'] = $val1['is_fullmoney'];
        	  $val1['is_fullmoney'] = ($val1['is_fullmoney'] == 1)?"全款":"按揭";       	
         	  //是否加V    
         	  $val1['nickname'] = (mb_strlen($val1['nickname'],'utf8') > 4)?(mb_substr($val1['nickname'],0,4,'utf8')."..."):$val1['nickname'];         	
         	  $val1['company_name'] = (mb_strlen($val1['company_name'],'utf8')>4)?(mb_substr($val1['company_name'],0,4,'utf8')."..."):$val1['company_name'];
            if(($val1['status'] == 1) || ($val1['status'] == 3)){
              $val1['status_bg'] = "tag_bule";
            }elseif($val1['status'] == 2){
              $val1['status_bg'] = "tag_red";
            }elseif($val1['status'] == 5){
              $val1['status_bg'] = "tag_gray";
            }          
          }else{
          	if($key1 == "is_fullmoney"){
               $data['isFullMoney'] = $val1;
               $val1 = ($val1 == 1)?"全款":"按揭";              
          	}
          	if(($key1 == "nickname") || ($key1 == "company_name")){
          		 $val1 = (mb_strlen($val1,'utf8')>4)?(mb_substr($val1,0,4,'utf8')."..."):$val1; 	    
          	}
            if($key1 == "status"){
              if(($val1 == 1) || ($val1 == 3)){
                 $data['status_bg'] = "tag_bule";
              }elseif($val1 == 2){
                 $data['status_bg'] = "tag_red";
              }elseif($val1 == 5){
                 $data['status_bg'] = "tag_gray";
              }
            }
          }
        } 
        return $data;                
	}
  public function bind_agent()
   {
       $agentRecintcode = $_REQUEST['agentRecintcode'];
       $mobile = $_REQUEST['mobile']?$_REQUEST['mobile']:'';
       $bind_recintcode = M("member")->where("mobile={$mobile}")->getfield("agentrecintcode");
       if(!isset($_SESSION['_bind_']) || $_SESSION['_bind_']=='') { 
            $this->set_session('_bind_'); 
        } 
       if(empty($mobile))
       {
          if(false==($member_info = $this->getMemberInfo()))redirect("/agent/not_login?agentRecintcode=".$agentRecintcode);
          $mobile = $member_info['mobile'];   
          $bind_recintcode = $member_info['agentrecintcode'];
       }
       $is_bind = $bind_recintcode?'1':'0';
       $agentRecintcode = $bind_recintcode?$bind_recintcode:$agentRecintcode;
       $this->assign("trueRecintcode",$agentRecintcode);
       //隐藏部分邀请码
       if($agentRecintcode)
       {
           $recintcode = substr($agentRecintcode,0,3).str_repeat("*",4).substr($agentRecintcode,-(strlen($agentRecintcode)-7));
       }else{
           $recintcode = $agentRecintcode;
       }
       if(empty(session('_bind_mobile_')))session('_bind_mobile_',md5(time()));
       $this->assign('_bind_mobile_',session('_bind_mobile_'));
       $this->assign('_bind_',$_SESSION['_bind_']);
       $this->assign("mobile",$mobile);
       $this->assign("agentRecintcode",$recintcode);  
       $this->assign("is_bind",$is_bind);
       $this->display();
   }
   public function submit_bind()
   {
      $agentRecintcode = $this->_post("agentRecintcode","trim");
      $mobile = $this->_post("mobile","trim");
      $verify_code = $this->_post("verify_code","trim");
      if(!$this->valid_session('_bind_')) $this->ajaxError("请不要重复提交");
      if(md5($verify_code)!=S("smscode".$mobile) || S("smsmobile".$mobile)!=md5($mobile.S("smscode".$mobile))){
        $this->ajaxError("验证码不正确");
      }
      if(false == M("gold_agent ga")->join("member m on m.id=ga.memberid")->where("m.mobile={$agentRecintcode}")->find())$this->ajaxError("该邀请码不存在");
      if(false == M("member")->where("mobile='{$mobile}'")->save(array("agentrecintcode"=>$agentRecintcode)))$this->ajaxError("绑定失败");
      unset($_SESSION['_bind_']);
      $this->ajaxSuccess("绑定成功");
   }
   public function send_bind_sms()
   {
       $mobile = $this->_post('mobile',"trim");
       //$this->ajaxError($_POST['_bind_mobile_']);
       if(empty($_POST['_bind_mobile_']) || empty(session('_bind_mobile_')) || $_POST['_bind_mobile_']!=session('_bind_mobile_') || S(session('_bind_mobile_'))>3){
        session('_bind_mobile_',null);
        $this->ajaxError('页面已失效，请刷新页面');
       }
       S(session('_bind_mobile_'),S(session('_bind_mobile_'))+1,60*2*10);
       $send_sms = $this->send_sms($mobile);    
       if($send_sms['status']==0)$this->ajaxError($send_sms['error']);
       $this->ajaxSuccess("验证码发送成功，有效期60秒");
       
   }
   public function send_sms($mobile)
   {
      $error = '';
     if(empty($mobile)){
      $error = '电话号码不能为空';
      $status=0;
      return array("status"=>$status,"error"=>$error);
    }
     if(S('smssend_flag')==1){
      $error = '您点的太频繁了';
      $status=0;
      return array("status"=>$status,"error"=>$error);
    }
     if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
      $error = '手机号码不正确';
      $status=0;
      return array("status"=>$status,"error"=>$error);
    }
     if(false == M('Member')->where(array('mobile'=>$mobile))){
      $error = '手机号码尚未注册';
      $status=0;
      return array("status"=>$status,"error"=>$error);
    }
     import('Think.ORG.Util.SMS');
     $sms_res = SMS::buildverify($mobile);
     if($sms_res==false){$error = '验证码发送失败';$status=0;}
     $smscode = session('smscode');
     S("smscode".$mobile,$smscode);
     S("smsmobile".$mobile,md5($mobile.$smscode));
     S("smssend_flag",1,60);
     return array("status"=>1,"error"=>$error);
   }
   public function not_login()
   {
      $agentRecintcode = $_REQUEST['agentRecintcode'];    
      $this->assign("agentRecintcode",$agentRecintcode);
      $this->display();
   }
   public function is_register()
   {
      $mobile = $this->_post('mobile',"trim");
      $agentRecintcode = $this->_post("agentRecintcode","trim");
      if(M("member")->where("mobile={$mobile}")->find())redirect("/agent/bind_agent/mobile/".$mobile."/agentRecintcode/".$agentRecintcode);
      redirect("/member/register?mobile=".$mobile."&recintcode=".$agentRecintcode."&agentRecintcode=".$agentRecintcode."&redirecturl=".urlencode("/agent/bind_agent/"));
   }
  /*
   判断账户余额是否足够
   参数：$need:所需金额；单位：元
   返回值：$data数组
           $data['status']:1,余额充足；0，余额不足
           $data['extra']:差额
   */
  public function enough_money($need){
      import("Think.ORG.Util.Fuyou");
      $fuyou = new Fuyou();
      $data = array();
      $data['status'] = 0;
      $data['extra'] = 0;
      $availAmount = $fuyou->BalanceAction($this->agent_info['memberid']);
      $amount = $need*100;
      if((false!= $availAmount) && ($availAmount['ca_balance']-$amount>=0)){
        $data['status'] = 1;
      }else{
        $data['extra'] = ceil(($amount-$availAmount['ca_balance'])/100);
      }
      return $data;
  }
    //下载文件
    public function uploadImage($memberid,$type,$report_id = ''){
       import('ORG.Net.UploadFile');
       $upload = new UploadFile();// 实例化上传类
       if($type == "agent"){
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
         $upload->saveRule = 'code_'.$memberid.time();           //设置文件保存规则唯一
         $upload->thumb = true;
         $upload->saveRule = $memberid."_".time();           //设置文件保存规则唯一
         $upload->uploadReplace = true;                 //同名则替换
         //设置需要生成缩略图的文件后缀
         $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
         //设置缩略图最大宽度
         $upload->thumbMaxWidth = '400,100';
         //设置缩略图最大高度F
         $upload->thumbMaxHeight = '400,100'; 
          if(!$upload->upload()) {// 上传错误提示错误信息
             $this->error = $upload->getErrorMsg();
             return false;
          }else{// 上传成功 获取上传文件信息
             $info =  $upload->getUploadFileInfo();
             $data['pic_card2'] = implode("|",array_column($info,"savename"));
          }
          if(false==($save_id=M('gold_agent')->where(array('memberid'=>$memberid))->save($data))){
			       $this->error = "修改失败，请稍后再试！";
			       return false;
		      }
		      return true;
       }elseif($type == "report"){
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
         $upload->saveRule = 'code_'.$memberid.time();           //设置文件保存规则唯一
         $upload->thumb = true;
         $upload->saveRule = $memberid."_".time();           //设置文件保存规则唯一
         $upload->uploadReplace = true;                 //同名则替换
         //设置需要生成缩略图的文件后缀
         $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
         //设置缩略图最大宽度
         $upload->thumbMaxWidth = '400,100';
         //设置缩略图最大高度F
         $upload->thumbMaxHeight = '400,100';
          if(!$upload->upload()) {
               $this->error = $upload->getErrorMsg();
               return false;
           }else{
              $info =  $upload->getUploadFileInfo();
              $data['pic_urls'] = implode("|",array_column($info,"savename"));
              return $data;
           }
           /*if(false==($save_id = M('gold_report')->where("id={$report_id}")->save($data))){
               $this->error = "图片上传失败！";
               return false;
           }*/
           //$this->error = M('gold_report')->where("order_id={$order_id}")->save($data);
                  
       }
	}
    public function locationByGPS($lng = '', $lat = ''){
    
	$lng = !empty($_REQUEST['lng'])?$_REQUEST['lng']:$lng;
	$lat = !empty($_REQUEST['lat'])?$_REQUEST['lat']:$lat;

	$params = array(
			'coordtype' => 'bd09ll',//GPS获取坐标，坐标类型，可选参数，默认为bd09经纬度坐标。允许的值为bd09ll、bd09mc、gcj02、wgs84。bd09ll表示百度经纬度坐标，bd09mc表示百度墨卡托坐标，gcj02表示经过国测局加密的坐标，wgs84表示gps获取的坐标。
			'location' => $lat . ',' . $lng,//纬度、经度
			'ak' => 'wZcPObxiUHlV77wuusKnUIUhsg4IntI7',
			'output' => 'json',//输出类型
			'pois' => 1,//是否显示周边
			'src'=>'借吧'
	);
    $resp = R("Index/async",array('http://api.map.baidu.com/geocoder/v2/', $params, false));
	$data = json_decode($resp, true);
	if ($data['status'] != 0)
	{
		$this->ajaxError($data['message']);
	}
	$data = array(
			'address' => $data['result']['formatted_address'],
			'province' => $data['result']['addressComponent']['province'],
			'city' => $data['result']['addressComponent']['city'],
			'street' => $data['result']['addressComponent']['street'],
			'street_number' => $data['result']['addressComponent']['street_number'],
			'city_code'=>$data['result']['cityCode'],
			'lng'=>$data['result']['location']['lng'],
			'lat'=>$data['result']['location']['lat'],
			'pois'=>$data['result']['pois']['0']['name'],
			'formatted_address'=>$data['result']['formatted_address'],
			'pc'=>$data['result']['addressComponent']['city'].$data['result']['addressComponent']['district'],
	);
	if($_REQUEST['is_ajax']){
		$this->ajaxSuccess('请求成功',$data);
	}
 }
	
}