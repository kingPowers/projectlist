<?php
/**
* 车辆保险
*/
class CarinsuranceAction extends CommonAction
{
        private $memberInfo;
        
	public function _initialize()
	{
                if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
                    $_SESSION['token'] = $_REQUEST['token'];
                }
		if (false == ($this->memberInfo = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/Carinsurance/" . ACTION_NAME));
		$this->assign("member_info",$this->memberInfo);
	}
        //添加订单
	public function apply()
	{
		//dump($this->getMemberInfo());
                if($_POST){
                    import("Think.ORG.Util.InsuranceOrder");
                    $insurance = new InsuranceOrder();
                    $insurance->memberId = $this->memberInfo["id"];
                    $insurance->carType = $_POST["car_type"];
                    $insurance->insuranceType = implode(",",$_POST["insurance_type"]);
                    $insurance->smsCode = $_POST["sms_code"];
                    $insurance->storeName = $_POST["store_name"];
                    $insurance->address = $_POST['address'];
                    $insurance->recMobile = $_POST['recMobile'];
                    $result = $insurance->addOrder();
                    if($result==true){
                        $this->success("订单添加成功");
                    }else{
                        $this->error($insurance->getError());
                    }
                    
                }
		$params['_cmd_'] = "insuranceOrder";
		$params['type'] = "applyData";
		$service_res = $this->service($params);//dump($service_res['dataresult']);
		$this->assign("apply_info",$service_res['dataresult']);
                if(empty(session('_applyInsurance_mobile_')))session('_applyInsurance_mobile_',md5(time()));
                $this->assign('_applyInsurance_mobile_',session('_applyInsurance_mobile_'));
		$this->display();
	}
        //获取验证码
        public function applySms(){
            if(!empty(S($_POST['_applyInsurance_mobile_'])))$this->error("请1分钟后再获取验证码");
            if(empty($_POST['_applyInsurance_mobile_']) || empty(session('_applyInsurance_mobile_')) || $_POST['_applyInsurance_mobile_']!=session('_applyInsurance_mobile_') || S(session('_applyInsurance_mobile_'))>10){
                    session('_applyInsurance_mobile_',null);
                    $this->ajaxError('页面已失效，请刷新页面');
            }
            S($_POST['_applyInsurance_mobile_'],1,60);
            $params['_cmd_'] = "insuranceOrder";
            $params['type'] = "sendSms";
            $service_res = $this->service($params);
            if(0===$service_res['errorcode']){
                $this->success("验证码已成功发送");
            }else{
                S($_POST['_applyInsurance_mobile_'],null);
                $this->error($service_res['errormsg']);
            }
        }
	public function getStore()
	{
		$store_name = $this->_post("store_name","trim");
		$params['_cmd_'] = "insuranceOrder";
		$params['type'] = "applyData";
		$params['searchStoreName'] = $store_name;
		$service_res = $this->service($params);//dump($_POST);
		$this->ajaxSuccess('成功',$service_res['dataresult']['stores']);
	}
        //订单列表
	public function myCarInsurance()
	{
            $params['_cmd_'] = "insuranceOrder";
            $params['type'] = "orderList";
            $params['status'] = $_GET["status"];
	    $params['is_pay'] = $_GET["is_pay"];
            $service_res = $this->service($params);
            $this->assign("list",$service_res["dataresult"]["list"]);
            $this->display();
	}
        //订单详情
	public function insuranceInfo()
	{
            $params['_cmd_'] = "insuranceOrder";
            $params['type'] = "orderDetail";
            $params['id'] = $_GET["id"];
            $service_res = $this->service($params);
            $this->assign("detail",$service_res["dataresult"]);
            //dump($service_res);exit;
            $this->display();
	}
        //取消订单
        public function orderCancle(){
            if(empty($_GET["id"]))$this->redirect("myCarInsurance");
            $params['_cmd_'] = "insuranceOrder";
            $params['type'] = "orderCancle";
            $params['id'] = $_GET["id"];header("content-type:text/html;charset=utf-8");
            $service_res = $this->service($params);
            if(0===$service_res['errorcode']){
                exit("<script>alert('订单取消成功');location.href='insuranceInfo?id={$_GET["id"]}'</script>");
                //$this->redirect("insuranceInfo?id={$_GET["id"]}");
            }else{
                exit("<script>alert('{$service_res['errormsg']}');location.href='insuranceInfo?id={$_GET["id"]}'</script>");
                return;
            }
        }
     //查看保单
    public function myGuarantee ()
    {
        $params['_cmd_'] = "insuranceOrder";
        $params['type'] = "orderDetail";
        $params['id'] = $_GET["id"];
        $service_res = $this->service($params);
        $this->assign("detail",$service_res["dataresult"]);
        $this->display();
    }
}