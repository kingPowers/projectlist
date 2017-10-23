<?php

class MemberAction extends CommonAction {
	
	//初始化操作
	protected function _initialize() {
		$action = array('login', 'register','registersendsms','unioncallback','checkunique',"recoverpwd","recoverpwdsendsms","recoversuccess");
		$logon = in_array(strtolower(ACTION_NAME), $action) ? false : true;
		//$this->_check_login($logon);
	}
	
	
   /*
    * 注册第一步
    * 	
    * */
   public function register(){
   		$redirectURL = urldecode($_REQUEST['redirecturl']);
   		//if(false!=$this->isLogin())redirect(($redirectURL?$redirectURL:"/member/account/"));
   		if(isset($_POST) &&!empty($_POST['mobile'])){
   			$_POST['_cmd_'] = "member_register";
   			$_POST['type'] = "reg";
   			$_POST['regip'] = get_client_ip();
   			$_POST['lastip'] = get_client_ip();
   			$service_res = $this->service($_POST);
   			if(0===$service_res['errorcode']){
   				$_SESSION['token'] = $service_res['dataresult']['_token_'];
   				$_SESSION['member'] = $this->getMemberInfo();
   				$_SESSION["member"]['mobile'] = substr($_POST['mobile'],0,3)."****".substr($_POST['mobile'],-4);
   				$this->success(['url'=>"/member/regok"]);
   			}else{
   				$this->error($service_res['errormsg']);
   			}
   		}
   		if(empty(session('_register_mobile_')))session('_register_mobile_',md5(time()));
   		$this->assign('error',$service_res['errormsg']);
   		$this->assign('_register_mobile_',session('_register_mobile_'));
   		$this->assign('recintcode',$_REQUEST['recintcode']?M('member')->where("invitecode='{$_REQUEST['recintcode']}' and invitecode!=''")->find():'');
   		$this->display();
   }
   //注册第二步
 	public function regok(){
        $this -> display();
    }
   
   /**
    * 注册   字段的唯一验证
    *  用户名、手机号
    * 
    */
   public function checkUnique() {
	   $type = $this->_post('type', 'trim');
	   $val = $this->_post($type, 'trim');
	   if (empty($type) || empty($val))$this->error('参数不完整');
	   $exists = M('Member')->where(array('id' => array('gt', 0), $type => $val))->field('id')->find();
	   if ($exists)$this->error("{$val}已注册");
	   $this->success('可用');
   }
   //注册发送验证码
   public function registerSendSms(){
   		$params = array();
   		$params['_cmd_'] = "member_register";
   		$params['type'] = "register_verify";
   		$params['mobile'] = $_POST['mobile'];
   		if(empty($_POST['_register_mobile_']) || empty(session('_register_mobile_')) || $_POST['_register_mobile_']!=session('_register_mobile_') || S(session('_register_mobile_'))>10){
   			session('_register_mobile_',null);
   			$this->ajaxError('页面已失效，请刷新页面');
   		}
   		S(session('_register_mobile_'),S(session('_register_mobile_'))+1,10*60);
   		$service_res = $this->service($params);
   		session('_register_mobile_',null);
   		if(0===$service_res['errorcode'])
   			$this->success("验证码已成功发送");
   		else 
   			$this->error($service_res['errormsg']);
   		
   }
  
   //登录
   public function login(){
      $member_info = $this->getMemberInfo();
   		$redirectURL = urldecode($_REQUEST['redirecturl']);
   		//if(false!=$this->isLogin())redirect(($redirectURL?$redirectURL:"/member/account/"));
    	if(isset($_POST) && !empty($_POST['mobile'])){
    		$params = array('mobile'=>$_POST['mobile'],'password'=>$_POST['password'],'_cmd_'=>'member_login');     		
    		$log_res = $this->service($params);
    		if(0===$log_res['errorcode']){
    			$_SESSION['token'] = $log_res['dataresult']['_token_'];
    			$_SESSION['member'] = $this->getMemberInfo();
    			$_SESSION["member"]['mobile'] = substr($_POST['mobile'],0,3)."****".substr($_POST['mobile'],-4);//$this->ajaxSuccess("成功");
          $url = $_POST['redirecturl']?$_POST['redirecturl']:'/member/account';
          $this->ajaxReturn(['status'=>1,'info'=>'登陆成功','syn'=>1,'url'=>$url]);
          //$this->ajaxSuccess("登陆成功",$url);
          //$this->success(['url'=>"/member/regok"]);
    		}else{
    			$this->error($log_res['errormsg']);
    		}
    		
    	}
    	if($log_res['errormsg']=='您已登录，请不要重复登陆') session('_deviceid_',null);
      $this->assign("redirecturl",$redirectURL);
   		$this->display();
   }
   //退出
   public function loginOut(){
	   	  $params = array();
	   	  $params['_cmd_'] = 'member_loginout';	
	   	  $service_res = $this->service($params);
	   	  if(0===$service_res['errorcode']){
	   	  	unset($_SESSION['token'],$_SESSION['member']);
	   	  	exit(true);
	   	  }
   	  
   }
   //找回密码
   public function recoverPwd(){
   		if(isset($_POST) && !empty($_POST['smscode'])){
   			$params = array();
   			$params['_cmd_'] = "member_recoverpwd";
   			$params['type'] = "recover";
   			$params['mobile'] = $_POST['mobile'];
   			$params['setpwd'] = $_POST['password'];
   			$params['resetpwd'] = $_POST['repassword'];
   			$params['verify_code'] = $_POST['smscode'];
   			$service_res = $this->service($params);
   			session('_recoverpwd_mobile_',null);
   			if(0===$service_res['errorcode']){
   				$this->success("修改成功");
   			}else{
   				$this->error($service_res['errormsg']);
   			}
   		}
   		if(empty(session('_recoverpwd_mobile_')))session('_recoverpwd_mobile_',md5(time()));
   		$this->assign('_recoverpwd_mobile_',session('_recoverpwd_mobile_'));
   		$this->display();
   }
   //找回密码发送短信
   public function recoverPwdSendSms(){
   		if(!empty($_POST['mobile'])){
   			$params = array();
   			$params['_cmd_'] = "member_recoverpwd";
   			$params['type'] = "repwd_verify";
   			$params['mobile'] = $_POST['mobile'];
   			if(empty($_POST['_recoverpwd_mobile_']) || empty(session('_recoverpwd_mobile_')) || $_POST['_recoverpwd_mobile_']!=session('_recoverpwd_mobile_') || S(session('_recoverpwd_mobile_'))>10){
	   			session('_recoverpwd_mobile_',null);
	   			$this->ajaxError('页面已失效，请刷新页面');
	   		}
	   		S(session('_recoverpwd_mobile_'),S(session('_recoverpwd_mobile_'))+1,10*60);
	   		$service_res = $this->service($params);
	   		session('_recoverpwd_mobile_',null);
	   		if(0===$service_res['errorcode'])
	   			$this->success("验证码已成功发送");
	   		else
	   			$this->error($service_res['errormsg']);
	   	}
	   	$this->error("手机号必填");
   }
   public function recoverSuccess()
   {
       $this->display();
   }
   //重置密码
   public function reset(){
   	   if(!empty($_POST) && !empty($_POST['password'])){
     	   	$params = array();
     	   	$params['_cmd_'] = "member_changeinfo";
     	   	$params['type'] = "resetpwd";
     	   	$params['verify_code'] = $_POST['verify_code'];
     	   	$params['pwd'] = $_POST['password'];
     	   	$params['repwd'] = $_POST['repassword'];
     	   	$service_res = $this->service($params);
     	   	if(0===$service_res['errorcode'])
          {
             $_SESSION['member']['pwd_safety'] = $service_res['dataresult']['pwd_safety'];
             $this->ajaxSuccess($service_res['dataresult']['info']);
     	   	}else{
             $this->ajaxError($service_res['errormsg']);
          }
   	   }
       if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urldecode("/member/reset"));
       if(empty(session('_reset_mobile_')))session('_reset_mobile_',md5(time()));
       $this->assign('_reset_mobile_',session('_reset_mobile_'));
       $this->assign("mobile",$member_info['mobile']);
   	   $this->display();
        
   }
   public function resetSendSms()
   {
    if(false==($member_info = $this->getMemberInfo()))$this->ajaxError('请重新登录');
      if(!empty($_POST['mobile'])){
        $params = array();
        $params['_cmd_'] = "member_changeinfo";
        $params['type'] = "resetpwd_verify";
        if(empty($_POST['_reset_mobile_']) || empty(session('_reset_mobile_')) || $_POST['_reset_mobile_']!=session('_reset_mobile_') || S(session('_reset_mobile_'))>10){
          session('_reset_mobile_',null);
          $this->ajaxError('页面已失效，请刷新页面');
        }
        S(session('_reset_mobile_'),S(session('_reset_mobile_'))+1,10*60);
        $service_res = $this->service($params);
        session('_reset_mobile_',null);
        if(0===$service_res['errorcode'])
        {
          
          $this->success($service_res['dataresult']);
        }         
        $this->error($service_res['errormsg']);
      }
      $this->error("手机号必填");
   }
   public function reset_success()
   {
      $this->display();
   }
   public function account()
   {
     if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urldecode("/member/account"));
      $this->display();
   }
   public function changeUsername(){
      if($_POST['is_ajax'] == 1)
      {
         $new_name = $this->_post("new_name","trim");
         $params = array();
         $params['_cmd_'] = "member_changeinfo";
         $params['type'] = 'revise_username';
         $params['username'] = $new_name;
         $service_res = $this->service($params);
         if($service_res['errorcode'] === 0)
         {
            $_SESSION['member']['username'] = $new_name;
            $this->ajaxSuccess("修改成功",array("new_name"=>$new_name));
         }else{
            $this->ajaxError($service_res['errormsg']);
         }
      }
   }
   private function _check_login($login = true){
	   	if(true!=$login){
	   		$redirectURL = urldecode($_REQUEST['redirecturl']);
	   		if(false!=$this->getMemberInfo())
	   			redirect(($redirectURL?$redirectURL:"/member/account/"));
	   	}else{
	   		if(false==$this->getMemberInfo())
	   			redirect(("/member/login/?redirecturl=".urlencode("/member/".ACTION_NAME)));
	   	}
   }

}
