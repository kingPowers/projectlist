<?php
/**
 * Description of IndexAction
 */
class CreditAction extends CommonAction {
    public $settings = array();
    private $order_status;
    private $order_data;
    public function _initialize(){
        if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
                $_SESSION['token'] = $_REQUEST['token'];
        }

        $result = $this->service(["_cmd_"=>"credit","type"=>"setting"]);
        $this->settings = $result['dataresult']['credit'];
        $this->actionName = $this->getActionName();
        if(!in_array(ACTION_NAME,["xjdlc","help"])){
            if(false==($member_info = $this->getMemberInfo()) && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))$this->error("您已超时，请重新登录");
            if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
            import("Think.ORG.Util.Baofu");
            $this->baofu = new Baofu($member_info['id']);
            import("Think.ORG.Util.Credit");
            $this->Credit = new Credit($member_info['id']);
            $order_info = $this->service(["_cmd_"=>"credit","type"=>"credit_status"]);
            $staff_info = $this->service(["_cmd_"=>"member_changeuserinfo","type"=>"member_info"]);
            $this->order_status=$order_info['dataresult']['order_status'];
            $this->order_data = $order_info['dataresult']['order_info'];
            $this->assign('order_info',$order_info['dataresult']);
            //是否员工  1：是  0：否
            $this->assign('is_staff',$staff_info['dataresult']['member_info']['is_staff']);
        }
    }
	
	
	
	/*
	 * 在线申请介绍
	 * */
    public function index() {
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	$this->assign('btn_status',$this->order_status);
        $this->assign('btn_ids',$this->order_data['id']);
        $this->display();
    }
    /*
     * 在线申请信用贷
     * 		---需要同步提交过来，不适用于ajax
     * */
    public function apply(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if($this->order_status!=1)redirect("/credit/index");
    	if(false==$this->baofu->bindBankCardStatus())redirect("/{$this->actionName}/bindBaofu?returnurl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(isset($_REQUEST) && !empty($_REQUEST['apply'])){
    		$params = array();
    		$params['_cmd_'] = "credit";
    		$params['type'] = "credit_add";
    		$params['loanmoney'] = $_REQUEST['loanmoney'];
    		$params['check_money'] = 1;//检测贷款金额
    		$service_res = $this->service($params);
    		if(0===$service_res['errorcode']){
    			$_SESSION['credit']['loanmoney'] = $_REQUEST['loanmoney'];
    			redirect("/credit/apply_submit?loanmoney={$_REQUEST['loanmoney']}");
    		}else{
    			header("Content-type:text/html;charset=utf-8");
    			exit("<script>alert('{$service_res['errormsg']}');location.href='/{$this->actionName}/".ACTION_NAME."'</script>");
    		}
    	}
    	$this->assign("setting",$this->settings);
    	$this->display();
    }
    //在线申请提交
    public function apply_submit(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(empty($_SESSION['credit']) && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))$this->error('您已提交申请，请勿重复操作，如有疑问请联系客服！');
    	if(empty($_SESSION['credit']) && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))header("/credit/index/");
    	if(isset($_POST) && !empty($_POST['data'])){
    		$params = array();
    		$params['_cmd_'] = "credit";
    		$params['type'] = "credit_add";
    		$params['loanmoney'] = $_SESSION['credit']['loanmoney']?$_SESSION['credit']['loanmoney']:$_REQUEST['loanmoney'];
    		$params['sms_code'] = $_POST['data']['sms_code'];
    		$params['mobile'] = $member_info['mobile'];
    		$service_res = $this->service($params);
    		if(0===$service_res['errorcode']){
    			unset($_SESSION['credit']);
    			$this->ajaxReturn($service_res['dataresult'],'提交成功',1);
    			//$this->success("提交成功");
    		}else{
    			$this->error($service_res['errormsg']);
    		}
    	}
    	$member_info['mobile'] = substr($member_info['mobile'],0,3)."****".substr($member_info['mobile'],-4);
    	$this->assign('member_info',$member_info);
    	if(empty(session('_credit_mobile_')))session('_credit_mobile_',md5(time()));
    	$this->assign('_credit_mobile_',session('_credit_mobile_'));
    	$this->display();
    }
    
    public function creditSendSms(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("请重新登录");
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "creditSendSms";
    	$params['mobile'] = $member_info['mobile'];
    	if(empty($_POST['_credit_mobile_']) || empty(session('_credit_mobile_')) || $_POST['_credit_mobile_']!=session('_credit_mobile_') || S(session('_credit_mobile_'))>10){
    		session('_credit_mobile_',null);
    		$this->ajaxError('页面已失效，请刷新页面');
    	}
    	S(session('_credit_mobile_'),S(session('_credit_mobile_'))+1,10*60);
    	$service_res = $this->service($params);
    	session('_credit_mobile_',null);
    	if(0===$service_res['errorcode'])
    		$this->success("验证码已成功发送!");
    	else
    		$this->error($service_res['errormsg']);
    
    }
    //在线申请成功
    public function apply_success(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(empty($_REQUEST['id']))redirect("/credit/apply");
    	$order = M('order')->where("id='{$_REQUEST['id']}'")->find();
    	$this->assign('order',$order);
    	$this->display();
    }
    
    //实名认证
    public function realName(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(isset($_POST) && !empty($_POST['data'])){
    		$params = array();
    		$params['_cmd_'] = "member_changeuserinfo";
    		$params['type'] = "realname";
    		$params['names'] = $_POST['data']['names'];
    		$params['certiNumber'] = $_POST['data']['certiNumber'];
    		$service_res = $this->service($params);
    		if(0===$service_res['errorcode']){
    			$this->success("实名认证成功");
    		}else{
    			$this->error($service_res['errormsg']);
    		}
    	}
        $returnurl = ($_REQUEST['from'] == 'agent')?'/credit/bindCard?returnurl='.urlencode($_REQUEST['returnurl']):urldecode($_REQUEST['returnurl']);      
    	$this->assign("member_info",$member_info);
    	$this->assign("returnurl",$returnurl);
    	$this->display();
    }
    //绑定银行卡
    public function bindCard(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode(urldecode($_REQUEST['returnurl'])));
    	if(false==$this->baofu->bindBankCardStatus())redirect("/{$this->actionName}/bindBaofu?returnurl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "fuyou_info";
    	$service_res = $this->service($params);
    	$this->assign('card_info',$service_res["dataresult"]["fuyou_info"]);
    	$this->display();
    }
    
    //绑定富友金账户
    public function bindFuyou(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	$fuyou_info = $this->fuyou->FuyouStatus($member_info['memberid']);
    	if(false==$fuyou_info){
    		$error = $this->fuyou->getError();
    		header("Content-type:text/html;charset=utf-8");
    		exit("<script>alert('{$error}');location.href='/{$this->actionName}/apply'</script>");
    	}
    	if(isset($_POST) && !empty($_POST['data'])){
    		$params = array();
    		$params['_cmd_'] = "credit";
    		$params['type'] = "bindFuyou";
    		$params['fuyou_login_id'] = $fuyou_info['login_id'];
    		$params['sms_code'] = $_POST['data']['sms_code'];
    		$params['mobile'] = $fuyou_info['mobile_no'];
    		$service_res = $this->service($params);
    		if(0===$service_res['errorcode']){
    			unset($_SESSION['credit']);
    			$this->success("验证成功");
    		}else{
    			$this->error($service_res['errormsg']);
    		}
    	}
    	//$fuyou_info['certif_id'] = substr($fuyou_info['certif_id'],0,2)."****".substr($fuyou_info['certif_id'],-4);
    	$fuyou_info['capAcntNo'] = substr($fuyou_info['capAcntNo'],0,4)."********".substr($fuyou_info['capAcntNo'],-3);
    	$fuyou_info['mobile_no'] = substr($fuyou_info['mobile_no'],0,3)."****".substr($fuyou_info['mobile_no'],-3);
    	$this->assign('fuyou_info',$fuyou_info);
    	$this->assign("returnurl",urldecode($_REQUEST['returnurl']));
    	$this->assign("myurl",$_SERVER['REQUEST_URI']);
    	if(empty(session('_bindfuyou_mobile_')))session('_bindfuyou_mobile_',md5(time()));
    	$this->assign('_bindfuyou_mobile_',session('_bindfuyou_mobile_'));
    	$this->display();
    }
    
    //绑定富友金账户发送验证码
    public function bindFuyouSms(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("登录超时，请重新登录！");
    	$fuyou_info = $this->fuyou->FuyouStatus($member_info['memberid']);
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "bindFuyouSms";
    	$params['mobile'] = $fuyou_info['mobile_no'];
    	if(empty($_POST['_bindfuyou_mobile_']) || empty(session('_bindfuyou_mobile_')) || $_POST['_bindfuyou_mobile_']!=session('_bindfuyou_mobile_') || S(session('_bindfuyou_mobile_'))>10){
    		session('_bindfuyou_mobile_',null);
    		$this->ajaxError('页面已失效，请刷新页面');
    	}
    	S(session('_bindfuyou_mobile_'),S(session('_bindfuyou_mobile_'))+1,10*60);
    	$service_res = $this->service($params);
    	session('_bindfuyou_mobile_',null);
    	if(0===$service_res['errorcode'])
    		$this->success("验证码已成功发送");
    	else
    		$this->error($service_res['errormsg']);
    }
    //宝付-绑定银行卡
    public function bindBaofu(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(isset($_POST) && !empty($_POST['sub'])){
    		if(empty($_POST["sms_code"]))$this->error("请输入验证码");
    		$result = $this->baofu->preBindCardSubmit($_POST["sms_code"]);
    		if(false!=$result){
    			$this->success("绑卡成功");
    		}else{
    			$this->error($this->baofu->getError());
    		}
    	}
    	$cardInfo = M("member m,member_info i")
    				->join("fuyou_membanknumber f on i.memberid=f.memberid")
    				->join("baofu_bindcard b on b.memberid=i.memberid")
    				->field("if(b.names is not null,b.names,i.names) as names,if(b.certiNumber is not null,b.certiNumber,i.certiNumber) as id_card,if(b.mobile is not null,b.mobile,if(f.mobile_no is not null,f.mobile_no,m.mobile)) as mobile,if(b.acc_no is not null,b.acc_no,f.bank_card) as acc_no,if(b.bank_name is not null,b.bank_name,f.bank_name) as bank_name")
    				->where("m.id=i.memberid and m.id='{$member_info['memberid']}'")
    				->find();
    	if(false!=($preBindInfo = S("bindCard_{$member_info['memberid']}"))){
    		//表记录优先
    		$cardInfo['names'] = $cardInfo['names']?$cardInfo['names']:$preBindInfo["names"];
    		$cardInfo['id_card'] = $cardInfo['id_card']?$cardInfo['id_card']:$preBindInfo["certiNumber"];
    		//缓存优先
    		$cardInfo['acc_no'] = $preBindInfo['acc_no']?$preBindInfo['acc_no']:$cardInfo["acc_no"];
    		$cardInfo['bank_name'] = $preBindInfo['bank_name']?$preBindInfo['bank_name']:$cardInfo["bank_name"];
    		$cardInfo['mobile'] = $preBindInfo['mobile']?$preBindInfo['mobile']:$cardInfo["mobile"];
    	}
    	$cardInfo['isBind'] = false!=$this->baofu->bindBankCardStatus()?1:0;
    	$cardInfo['title'] = $cardInfo['isBind']==1?"换绑储蓄卡":"绑定储蓄卡";
    	$this->assign("cardInfo",$cardInfo);
    	$this->assign("bank_info",$this->baofu->getBankNames());
    	if(empty(session('_bindbaofu_mobile_')))session('_bindbaofu_mobile_',md5(time()));
    	$this->assign('_bindbaofu_mobile_',session('_bindbaofu_mobile_'));
    	$this->assign("returnurl",urldecode($_REQUEST['returnurl'])?urldecode($_REQUEST['returnurl']):"/credit/bindCard");
    	$this->display();
    }
    //宝付获取验证码
    public function bindBaofuSms(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("登录超时，请重新登录！");
    	$cardInfo = array();
    	$emptyArr = array("names"=>"请填写真实姓名","id_card"=>"请填写身份证号","acc_no"=>"请填写银行卡","bank_name"=>"请填写开户银行","mobile"=>"请填写银行预留手机号");
    	foreach($emptyArr as $k=>$v){
    		if(empty($_POST[$k]))$this->error($v);
    		$cardInfo[$k] = $_POST[$k];
    	}
    	if(!empty(S($_POST['_bindbaofu_mobile_'])))$this->error("请1分钟后再获取验证码");
    	if(empty($_POST['_bindbaofu_mobile_']) || empty(session('_bindbaofu_mobile_')) || $_POST['_bindbaofu_mobile_']!=session('_bindbaofu_mobile_') || S(session('_bindbaofu_mobile_'))>10){
    		session('_bindbaofu_mobile_',null);
    		$this->ajaxError('页面已失效，请刷新页面');
    	}
    	S($_POST['_bindbaofu_mobile_'],1,60);
    	$result = $this->baofu->preBindCard($cardInfo);
    	if(false!=$result)
    		$this->success("验证码已成功发送");
    	else{
    		S($_POST['_bindbaofu_mobile_'],null);
    		$this->error($this->baofu->getError());
    	}
    		
    }
    
    //借款记录
    public function orderList(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "orderList";
    	$params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
    	$service_res = $this->service($params);
    	if($_REQUEST['is_ajax']){
    		$status = $service_res['dataresult']['orderlist']?1:0;
    		$this->ajaxReturn($service_res['dataresult']['orderlist'],"加载成功",$status);
    	}//dump($service_res['dataresult']['orderlist']);exit;
    	$this->assign('orderlist',$service_res['dataresult']['orderlist']);
    	$this->display();
    }
    
    //贷款记录详情
    public function orderListDetail(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(empty($_REQUEST['id']))redirect("/credit/orderlist");
    	
    	$order_status_name = " case
									when o.status=2 and c.status=0 and c.back_time>NOW() then '1'
					                when o.status=1 then '2'
									when o.status=3 then '3'
									when o.status=2 and c.status=1 then '4'
								    when o.status=2 and c.status=0 and c.back_time<NOW() then '5'
									end as _order_status_
									";
    	$order_info = M()->table("`order` o,order_process p,order_credit c")
				    	 ->where("o.id=p.order_id and o.id=c.order_id  and o.memberid='{$member_info['id']}' and o.id='{$_REQUEST['id']}'")
				    	 ->field("o.loanmoney,p.customer_status,o.id,date_format(o.timeadd,'%Y年%m月%d日') timeadd,c.status,c.back_time,date_format(c.pay_time,'%Y-%m-%d') pay_time,date_format(c.back_time,'%Y-%m-%d') back_date,{$order_status_name}")
				    	 ->order("o.timeadd desc")
				    	 ->find();
    	if($order_info['_order_status_']==5){
    		$order_info['late_days'] =ceil((time()-strtotime($order_info['back_time']))/86400);//逾期天数
    		$order_info['late_fee'] = round($order_info['loanmoney']*$order_info['late_days']*0.01,2);//逾期金额
    	}
    	$this->assign('order_info',$order_info);
    	$this->display();
    }
    
    
    //还款
    public function  repayment(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(false==$this->baofu->bindBankCardStatus())redirect("/{$this->actionName}/bindBaofu?returnurl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	
    	//开始还款
    	if($_POST && !empty($_POST['repayment'])){
    		if(empty($_POST["sms_code"])){
    			exit("<script>alert('验证码不能为空');location.href='/{$this->actionName}/repayment'</script>");
    		}
    		$params = array();
    		$params['_cmd_'] = "credit";
    		$params['type'] = "repayment";
    		$params['sms_code'] = $_POST['sms_code'];
    		$service_res = $this->service($params);
			if(!(0===$service_res['errorcode'])){
				header("Content-type:text/html;charset=utf-8");
				exit("<script>alert('{$service_res['errormsg']}');location.href='/{$this->actionName}/repayment'</script>");
			}
			redirect("/credit/repayment_success/id/{$_POST['order_id']}");		
    	}
    	
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "repayment_info";
    	$service_res = $this->service($params);
    	$this->assign("data",$service_res['dataresult']["repayment"]);
    	if(empty(session('_bindbaofu_mobile_')))session('_bindbaofu_mobile_',md5(time()));
    	$this->assign('_bindbaofu_mobile_',session('_bindbaofu_mobile_'));
    	$this->display();
    }
    
    //还款时候发送验证码
    public function  repaymentSms(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("登录已超时");
    	$params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "repaymentSms";
    	if(empty($_POST['_bindbaofu_mobile_']) || empty(session('_bindbaofu_mobile_')) || $_POST['_bindbaofu_mobile_']!=session('_bindbaofu_mobile_') || S(session('_bindbaofu_mobile_'))>10){
    		session('_bindbaofu_mobile_',null);
    		$this->ajaxError('页面已失效，请刷新页面');
    	}
    	S(session('_bindbaofu_mobile_'),S(session('_bindbaofu_mobile_'))+1,10*60);
    	$service_res = $this->service($params);
    	session('_bindbaofu_mobile_',null);
    	if(0===$service_res['errorcode'])
    		$this->success("验证码已成功发送");
    	else
    		$this->error($service_res['errormsg']);
    }
    
    //还款成功
    public function repayment_success(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
    	if(empty($_REQUEST['id']))redirect("/credit/index");
    	$order = M('order_credit')->where("order_id='{$_REQUEST['id']}'")->find();
    	$this->assign('order',$order);
    	$this->display();
    }
    //充值
    public function carry(){
    	$returnurl = urlencode("/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST['returnurl']}");
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
    	import("Think.ORG.Util.Fuyou");
    	$this->fuyou = new Fuyou();
    	if($_POST && $_POST['money']){
    		//实名认证
    		if(0==$member_info['nameStatus'])redirect("/{$this->actionName}/realname?returnurl={$returnurl}");
    		//开户注册
    		if(false==$this->fuyou->FuyouStatus($member_info['id'])){
    			$is_reg = $this->fuyou->appWebReg($member_info['id'],"/Fuyou/appDoBindAccount?jumpurl={$returnurl}");
    			if(false==$is_reg){
    				$error = $this->fuyou->getError();
    				header("Content-type:text/html;charset=utf-8");
    				exit("<script>alert('{$error}');location.href='/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST['returnurl']}'</script>");
    			}else{
    				header("Content-type:text/html;charset=utf-8");
    				exit($is_reg);
    			}
    		}elseif(empty($member_info['fuyou_login_id'])){
    			redirect("/{$this->actionName}/bindfuyou?returnurl={$returnurl}");
    		}
    		//开始去充值
    		$carry = $this->fuyou->app_500002($member_info['id'],$_POST['money']*100,"/fuyou/carryBack?returnurl=".urlencode($_REQUEST['returnurl']));
    		if(false==$carry){
    			$error = $this->fuyou->getError();
    			header("Content-type:text/html;charset=utf-8");
    			exit("<script>alert('{$error}');location.href='/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST["returnurl"]}'</script>");
    		}
    		header("Content-type:text/html;charset=utf-8");
    		exit($carry);
    		
    	}
    	$this->assign("returnurl",urlencode($_REQUEST['returnurl']));
    	$this->display();
    }
    
    //提现
    public function cashout(){
    	$returnurl = urlencode("/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST['returnurl']}");
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
    	import("Think.ORG.Util.Fuyou");
    	$this->fuyou = new Fuyou();
    	if($_POST && $_POST['money']){
    		//实名认证
    		if(0==$member_info['nameStatus'])redirect("/{$this->actionName}/realname?returnurl={$returnurl}");
    		//开户注册
    		if(false==$this->fuyou->FuyouStatus($member_info['id'])){
    			$is_reg = $this->fuyou->appWebReg($member_info['id'],"/Fuyou/appDoBindAccount?jumpurl={$returnurl}");
    			if(false==$is_reg){
    				$error = $this->fuyou->getError();
    				header("Content-type:text/html;charset=utf-8");
    				exit("<script>alert('{$error}');location.href='/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST['returnurl']}'</script>");
    			}else{
    				header("Content-type:text/html;charset=utf-8");
    				exit($is_reg);
    			}
    		}elseif(empty($member_info['fuyou_login_id'])){
    			redirect("/{$this->actionName}/bindfuyou?returnurl={$returnurl}");
    		}
    		//开始去提现
    		$cashout = $this->fuyou->app_500003($member_info['id'],$_POST['money']*100,"/fuyou/cashoutBack?returnurl=".urlencode($_REQUEST['returnurl']));
    		if(false==$cashout){
    			$error = $this->fuyou->getError();
    			header("Content-type:text/html;charset=utf-8");
    			exit("<script>alert('{$error}');location.href='/{$this->actionName}/".ACTION_NAME."?returnurl={$_REQUEST["returnurl"]}'</script>");
    		}
    		header("Content-type:text/html;charset=utf-8");
    		exit($cashout);
    		
    	}
    	
    	if(false==$this->fuyou->FuyouStatus($member_info['id'])){
    		$is_reg = $this->fuyou->appWebReg($member_info['id'],"/Fuyou/appDoBindAccount?jumpurl={$_REQUEST['returnurl']}");
    		if(false==$is_reg){
    			$error = $this->fuyou->getError();
    			header("Content-type:text/html;charset=utf-8");
    			exit("<script>alert('{$error}');location.href='".urldecode($_REQUEST['returnurl'])."'</script>");
    		}else{
    			header("Content-type:text/html;charset=utf-8");
    			exit($is_reg);
    		}
    	}
    	 
    	$fuyou_info = $this->fuyou->FuyouStatus($member_info['memberid']);
    	if(false==$fuyou_info){
    		$error = $this->fuyou->getError();
    		header("Content-type:text/html;charset=utf-8");
    		exit("<script>alert('{$error}');location.href='/{$this->actionName}/apply'</script>");
    	}
    	$balance = $this->fuyou->BalanceAction($member_info['id']);
    	$fuyou_info['balance'] = $balance['ca_balance']/100;
    	$fuyou_info['capAcntNo'] = substr($fuyou_info['capAcntNo'],0,4)."********".substr($fuyou_info['capAcntNo'],-3);
    	$fuyou_info['mobile_no'] = substr($fuyou_info['mobile_no'],0,3)."****".substr($fuyou_info['mobile_no'],-3);
    	$this->assign('fuyou_info',$fuyou_info);
    	$this->assign("returnurl",$_REQUEST['returnurl']);
    	$this->display();
    }
    
    
    //协议合同
    public function creditAgreement(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
    	$this->assign('member_info',$member_info);
    	if(!empty($_REQUEST['order_id'])){
    		$credit_info = M('order_credit')->table("`order` o,order_credit c")->where("o.id=c.order_id and c.order_id='{$_REQUEST['order_id']}'")->find();
    		$credit_info['img_path'] = APP_PATH."../static/Upload/eqian_pic/";
    		if(file_exists($credit_info['img_path']."boss.png"))$credit_info['boss_img']=1;
    		if(file_exists($credit_info['img_path']."{$credit_info['memberid']}.png"))$credit_info['person_img']=1;
    		$this->assign('data',$credit_info);
    	}
    	$this->display();
    }
    
    //协议合同
    public function creditAgreement2(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
    	if(!empty($_REQUEST['order_id'])){
    		$credit_info = M('order_credit')->table("`order` o,order_credit c")->where("o.id=c.order_id and c.order_id='{$_REQUEST['order_id']}'")->find();
    		$credit_info['img_path'] = APP_PATH."../static/Upload/eqian_pic/";
    		if(file_exists($credit_info['img_path']."boss.png"))$credit_info['boss_img']=1;
    		if(file_exists($credit_info['img_path']."{$credit_info['memberid']}.png"))$credit_info['person_img']=1;
    		$this->assign('data',$credit_info);
    	}
        $this->assign('member_info',$member_info);
        $this->assign("setting",$this->settings);
    	$this->display();
    }
    
    //还款承诺书
    public function creditAgreement4(){
    	
    	if(!empty($_REQUEST['order_id'])){
	    	$data = M()->table('`order` o,order_credit c,member m,member_info i')
				    	->join(" staff s on s.certiNumber=i.certiNumber")
				    	->where("o.id='{$_REQUEST['order_id']}' and o.id=c.order_id and  o.memberid=i.memberid and i.memberid=m.id ")
				    	->field("o.memberid,o.names,date_format(o.timeadd,'%Y年%m月%d日') as date,o.loanmoney,c.order_sn,m.mobile,i.certiNumber,if(s.id,'1','0') as is_staff")
				    	->find();
	    	$data['img_path'] = APP_PATH."../static/Upload/eqian_pic/";
	    	if(file_exists($data['img_path']."{$data['memberid']}.png"))$data['person_img']=1;
	    	$this->assign('data',$data);
    	}
    	$this->display();
    }
    
    //协议合同
    public function creditAgreement3(){
    	//订单为签约状态时，允许签约
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
    	$this->assign('member_info',$member_info);
        $this->assign('order_ids',$_GET['order_id']);
    	if($_POST && $_POST['data']['agree'] && $_POST['data']['code']){
            $params = array();
            $params['_cmd_'] = "credit";
            $params['type'] = "eqian_submit";
            $params['eqian_smscode'] = $_POST['data']['code'];
            $service_res = $this->service($params);
            if(0===$service_res['errorcode']){
                $this->success('签约成功');
            }else{
                $this->error('没签约成功('.$service_res['errormsg'].")");
            }
            
    	}
		//避免代码刷验证码
    	if(empty(session('_eqianbao_mobile_')))session('_eqianbao_mobile_',md5(time()));
    	$this->assign('_eqianbao_mobile_',session('_eqianbao_mobile_'));
    	$this->display();
    }
/*
 * e签宝发送验证码
 */
 public function send_sms(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/{$this->actionName}/".ACTION_NAME));
        if(empty($_POST['data']['_eqianbao_mobile_']) || empty(session('_eqianbao_mobile_')) || $_POST['data']['_eqianbao_mobile_']!=session('_eqianbao_mobile_') || S(session('_eqianbao_mobile_'))>10){
        	session('_eqianbao_mobile_',null);
        	$this->ajaxError('页面已失效，请刷新页面');
        }
        $params = array();
    	$params['_cmd_'] = "credit";
    	$params['type'] = "eqian_sms";
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->success('短信发送成功');
        }else{
            $this->error('短信没发出去('.$service_res['errormsg'].")");
        }
    }
    
    public function give_up(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("对不起，请重新登录！");
    	$order_info = M('order')->where("id='{$_REQUEST['order_id']}' and memberid='{$member_info['id']}'")->find();
    	if(empty($_REQUEST['order_id']) || false==$order_info)$this->error("订单不存在！");
    	//客服拒单
    	$order_id = M('order')->where("id='{$_REQUEST['order_id']}'")->save(array('status'=>3));
    	$save_arr = array(
    			'customer_status'=>2,
    			'customer'=>'客户放弃订单',
    			'customer_time'=>date("Y-m-d H:i:s"),
    			'customer_remark'=>"客户放弃订单",
    	);
    	$process_id = M('order_process')->where("order_id='{$_REQUEST['order_id']}'")->save($save_arr);
    	if($order_id && $process_id)
    		$this->success("操作成功");
    	$this->error("网络连接超时，请稍后重试！");
    	
    }
    //车友贷流程
    public function xjdlc(){
    	$this->display();
    }
    
    //常见问题
    public function help(){
        $this->assign("setting",$this->settings);
        $this->display();
    }
    //补充协议
    public function agree_supple(){
      if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl={$returnurl}");
      if(!empty($_REQUEST['order_id'])){
    		$credit_info = M('order_credit')->table("`order` o,order_credit c")->where("o.id=c.order_id and c.order_id='{$_REQUEST['order_id']}'")->find();
    		$credit_info['img_path'] = APP_PATH."../static/Upload/eqian_pic/";
    		if(file_exists($credit_info['img_path']."boss.png"))$credit_info['boss_img']=1;
    		if(file_exists($credit_info['img_path']."{$credit_info['memberid']}.png"))$credit_info['person_img']=1;
    		$this->assign('data',$credit_info);
    	}
    	$this->assign('member_info',$member_info);
      $this->display();  
    }
}
