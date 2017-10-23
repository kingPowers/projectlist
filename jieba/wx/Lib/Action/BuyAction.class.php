<?php

/*
 * 智信创富金融
 */

/**
 * Description of IndexAction
 * @author Nydia
 */
class BuyAction extends CommonAction {
	
	//申请买车贷第一步
    public function index() {
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/buy/".ACTION_NAME));
    	if($_POST && !empty($_POST['data'])){
    		if(empty($_POST['data']['city'])){
    			$this->error('贷款城市不能为空');
    		}
    		if(empty($_POST['data']['dealer'])){
    			$this->error('经销商不能为空');
    		}
    		if(empty($_POST['data']['car_brand'])){
    			$this->error('车辆品牌不能为空');
    		}
    		if(empty($_POST['data']['car_class'])){
    			$this->error('车辆型号不能为空');
    		}
    		if(empty($_POST['data']['loanmonth'])){
    			$this->error('借款期限不能为空');
    		}
    		if(!preg_match('/^[1-9]*|0.\d*[1-9]\d*|0?.0+|0$/',$_POST['data']['loanmoney'])){
    			$this->error('车商报价不正确');
    		}
    		if(false!=M('order')->where("memberid='{$member_info['id']}' and order_type=2 and status=1")->find())$this->error('您已提交过申请，敬请等待审核结果！');
    		$_SESSION['buy'] = $_POST['data'];
    		$this->success('数据校验正确');
    	}
    	$params = array();
    	$params['_cmd_'] = "order";
    	$params['type'] = "setting";
    	$service_res = $this->service($params);
    	$this->assign('setting',$service_res['dataresult']);
    	$this->assign('dealer',M('dealer')->where("status=1")->order("sort desc,lasttime desc,id desc")->select());
    	$this->display();

    }
    //申请买车贷第二步
    public function add_buy_submit(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/buy/".ACTION_NAME));
    	if(empty($_SESSION['buy']) && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))$this->error('您已提交申请，请勿重复操作，如有疑问请联系客服！');
    	if(empty($_SESSION['buy']) && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))header("/buy/index/");
    	if($_POST && !empty($_POST['data'])){
    		if(empty($_POST['data']['names'])){
    			$this->error('姓名不能为空');
    		}
    		if(empty($_POST['data']['mobile'])){
    			$this->error('手机号不能为空');
    		}
    		if(empty($_POST['data']['sms_code'])){
    			$this->error('验证码不能为空');
    		}
    		$params = array();
    		$params['_cmd_'] = "order";
    		$params['type'] = "buy_add";
    		$params = array_merge($params,$_POST['data'],$_SESSION['buy']);
    		$service_res = $this->service($params);
   			if(0===$service_res['errorcode']){
   				unset($_SESSION['buy']);
   				$this->ajaxReturn(array('id'=>$service_res['dataresult']['add_id']), '提交成功',1);
   			}
    		$this->error($service_res['errormsg']);
    	}
    	$this->assign('member_info',$member_info);
    	if(empty(session('_buy_mobile_')))session('_buy_mobile_',md5(time()));
    	$this->assign('_buy_mobile_',session('_buy_mobile_'));
    	$this->display();
    }
    
    //买车贷提交成功
    public function add_buy_success(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/buy/".ACTION_NAME));
    	$order = M('order')->where("id='{$_REQUEST['id']}'")->find();
    	$this->assign('order',$order);
    	$this->display();
    }
    
    
    public function buySendSms(){
    	if(false==($member_info = $this->getMemberInfo()))$this->error("请重新登录");
    	$params = array();
    	$params['_cmd_'] = "order";
    	$params['type'] = "buySendSms";
    	$params['mobile'] = $_POST['mobile'];
    	if(empty($_POST['_buy_mobile_']) || empty(session('_buy_mobile_')) || $_POST['_buy_mobile_']!=session('_buy_mobile_') || S(session('_buy_mobile_'))>10){
    		session('_buy_mobile_',null);
    		$this->ajaxError('页面已失效，请刷新页面');
    	}
    	S(session('_buy_mobile_'),S(session('_buy_mobile_'))+1,10*60);
    	$service_res = $this->service($params);
    	session('_buy_mobile_',null);
    	if(0===$service_res['errorcode'])
    		$this->success("验证码已成功发送!");
    	else
    		$this->error($service_res['errormsg']);
    	 
    }
    //买车贷流程
    public function buy_flow(){
        $this->display();
    }


}
