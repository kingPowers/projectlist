<?php
/**
 *保险分期管理
 *      
 */
class AllotAction extends CommonAction {
    public function _initialize() {//dump($_SERVER);exit;
          if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
                    $_SESSION['token'] = $_REQUEST['token'];
                }
         if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode($_SERVER["REQUEST_URI"]));
    }
    //分期首页
    public function index() {
        $params = ["_cmd_"=>"insuranceInstalment","type"=>"userInstalment"];//分期列表
        $userInstalment = $this->service($params);
        //dump($userInstalment);
        $this->assign('userInstalment',$userInstalment["dataresult"]);
        $this->display();
    }
    //付款页面
    public function pay () 
   {
        if($_POST && !empty($_POST["paySub"])){
            $result = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"payMoney","smscode"=>$_POST["sms_code"],"pay_type"=>$_POST["pay_type"],"order_id"=>$_POST["order_id"]]);
            if(0===$result['errorcode']){
                $this->success("付款成功");
            }else{
                $this->error($result['errormsg']);
            }
        }
        $orderId = $_GET["id"];
        if(empty($orderId))redirect("/carinsurance/myCarInsurance");
        $params = ["_cmd_"=>"insuranceInstalment","type"=>"instalmentList","order_id"=>$orderId];//分期列表
        $instalmentList = $this->service($params);
        $orderDetail = $this->service(["_cmd_"=>"insuranceOrder","type"=>"orderDetail","id"=>$orderId]);
        $this->assign("instalmentList",$instalmentList["dataresult"]);
        $this->assign("detail",$orderDetail["dataresult"]);
        //dump($instalmentList);exit;
        if(empty(session('_payallot_mobile_')))session('_payallot_mobile_',md5(time()));
    	$this->assign('_payallot_mobile_',session('_payallot_mobile_'));
        $this->assign("member_info",$this->getMemberInfo());
   	$this->display();
   }
   //付款获取验证码
   public function paySmsCode(){
       if($_POST){
           if(empty($_POST["order_id"])){
               $this->error("参数错误(101)");
           }
           if(empty($_POST["pay_type"])){
               $this->error("参数错误(102)");
           }
           if(empty($_POST['_payallot_mobile_']) || empty(session('_payallot_mobile_')) || $_POST['_payallot_mobile_']!=session('_payallot_mobile_')){
	   	session('_payallot_mobile_',null);
	   	$this->ajaxError('页面已失效，请刷新页面');
	   }
           $smsCodeResult = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"prePaySms","pay_type"=>$_POST["pay_type"],"order_id"=>$_POST["order_id"]]);
           session('_payallot_mobile_',null);
            if(0===$smsCodeResult['errorcode']){
                $this->success("验证码已成功发送");
            }else{
                $this->error($smsCodeResult['errormsg']);
            }
       }
       $this->error("参数错误");
   }
   //E签宝发送验证码
   public function eqianSms(){
       if($_POST){
           if(empty($_POST["order_id"])){
               $this->error("参数错误(101)");
           }
           if(empty($_POST["pay_type"])){
               $this->error("参数错误(102)");
           }
           $smsCodeResult = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"eqian_sms","pay_type"=>$_POST["pay_type"],"order_id"=>$_POST["order_id"]]);
            if(0===$smsCodeResult['errorcode']){
                $this->success("验证码已成功发送");
            }else{
                $this->error($smsCodeResult['errormsg']);
            }
       }
       $this->error("参数错误");
   }
   //E签宝开始签章
   public function eqianSubmit(){
       if($_POST){
           if(empty($_POST["order_id"])){
               $this->error("参数错误(101)");
           }
           if(empty($_POST["pay_type"])){
               $this->error("参数错误(102)");
           }
           if(empty($_POST["eqian_smscode"])){
               $this->error("验证码不能为空");
           }
           $smsCodeResult = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"eqian_submit","pay_type"=>$_POST["pay_type"],"order_id"=>$_POST["order_id"],"eqian_smscode"=>$_POST["eqian_smscode"]]);
            if(0===$smsCodeResult['errorcode']){
                $this->success("签约成功");
            }else{
                $this->error($smsCodeResult['errormsg']);
            }
       }
       $this->error("参数错误");
   }
   
   /*
    * 已出账单
    */
   public function  hasBill(){
        $params = ["_cmd_"=>"insuranceInstalment","type"=>"outBillInfo"];//分期列表
        $outBillInfo = $this->service($params);
        $this->assign('outBillInfo',$outBillInfo["dataresult"]);
        //dump($outBillInfo);exit;
       $this->display("hasBill");
   }
   /*
    * 未出账单
    */
   public function noBill(){
       $params = ["_cmd_"=>"insuranceInstalment","type"=>"unoutBillInfo"];//分期列表
        $unoutBillInfo = $this->service($params);
        $this->assign('unoutBillInfo',$unoutBillInfo["dataresult"]);
        //dump($unoutBillInfo);exit;
       $this->display("noBill");
   }
   /*
    * 账单明细
    *       --分期明细
    */
   public function  billDetail(){
       if(empty($_GET["order_id"]))redirect("/allot/index");
        $params = ["_cmd_"=>"insuranceInstalment","type"=>"orderInstalmentInfo","order_id"=>$_GET["order_id"]];//分期列表
        $unoutBillInfo = $this->service($params);
        $this->assign('orderInstalmentInfo',$unoutBillInfo["dataresult"]);
        //dump($unoutBillInfo);exit;
       $this->display("billDetail");
   }
   /*
    * 月份账单
    *       --按照月份分类账单
    */
   public function billMonth(){
       $params = ["_cmd_"=>"insuranceInstalment","type"=>"memberBill"];
       $unoutBillInfo = $this->service($params);
       $this->assign('billMonth',$unoutBillInfo["dataresult"]);
       //dump($unoutBillInfo);exit;
       $this->display("billMonth");
   }
   
   /*
    * 还款首页
    *       --还款金额页面
    */
   public function repaymentIndex(){
       $this->display("repaymentIndex");
   }
   /*
    * 开始还款
    *       --开始分期还款
    */
   public function beginRepayment(){
       if($_POST && !empty($_POST["paySub"])){
            $result = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"backMoney","back_smscode"=>$_POST["back_smscode"],"back_type"=>$_POST["back_type"],"instalmentid"=>$_POST["instalmentid"]]);
            if(0===$result['errorcode']){
                $this->success("付款成功");
            }else{
                $this->error($result['errormsg']);
            }
        }
        $backInfo = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"backInfo","back_type"=>$_GET["back_type"],"instalmentid"=>$_GET["instalmentid"]]);
        $this->assign("backInfo",$backInfo["dataresult"]);  
        //dump($backInfo);exit;
       if(empty(session('_payallot_instalment_mobile_')))session('_payallot_instalment_mobile_',md5(time()));
       $this->assign('_payallot_instalment_mobile_',session('_payallot_instalment_mobile_'));
       $this->display("beginRepayment");
   }
   /*
    * 还款发送验证码
    */
   public function beginRepaymentSms(){
       if($_POST){
           if(empty($_POST['_payallot_instalment_mobile_']) || empty(session('_payallot_instalment_mobile_')) || $_POST['_payallot_instalment_mobile_']!=session('_payallot_instalment_mobile_')){
	   	session('_payallot_instalment_mobile_',null);
	   	$this->ajaxError('页面已失效，请刷新页面');
	   }
           $smsCodeResult = $this->service(["_cmd_"=>"insuranceInstalment","type"=>"sendBackSms","back_type"=>$_POST["back_type"],"instalmentid"=>$_POST["instalmentid"]]);
           session('_payallot_instalment_mobile_',null);
            if(0===$smsCodeResult['errorcode']){
                $this->success("验证码已成功发送");
            }else{
                $this->error($smsCodeResult['errormsg']);
            }
       }
       $this->error("参数错误");
   }
   /*
    * 分期明细
    */
   public function instalmentDetail(){
       if(empty($_GET["instalId"]))redirect("/allot/index");
       $params = ["_cmd_"=>"insuranceInstalment","type"=>"instalmentDetail","instalId"=>$_GET["instalId"]];
       $unoutBillInfo = $this->service($params);
       $this->assign('instalmentDetail',$unoutBillInfo["dataresult"]);
       //dump($unoutBillInfo);exit;
       $this->display("instalmentDetail");
   }
   /*
    * 已还款列表
    */
   public function repaymentList(){
        $type = $_REQUEST["type"]=="hasBill"?"outBillInfo":"unoutBillInfo";
        $params = ["_cmd_"=>"insuranceInstalment","type"=>$type];//分期列表
        $outBillInfo = $this->service($params);
        $this->assign('repaymentList',$outBillInfo["dataresult"]["paid_instalment"]);
        //dump($outBillInfo);exit;
       $this->display("repaymentList");
   }
   /*
    * 总账单
    */
   public function totalBill(){
       $params = ["_cmd_"=>"insuranceInstalment","type"=>"userInstalment"];//分期列表
       $userInstalment = $this->service($params);
       $this->assign('userInstalment',$userInstalment["dataresult"]);
       //dump($userInstalment);exit;
       $this->display("totalBill");
   }
}
