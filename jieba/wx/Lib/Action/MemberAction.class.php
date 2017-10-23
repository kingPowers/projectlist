<?php

/*
 * 智信创富金融
 */

/**
 * Description 
 * 
 */
class MemberAction extends CommonAction {
  public function __construct(){
        parent::__construct();
        if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
          $_SESSION['token'] = $_REQUEST['token'];
        }
    }

//---------------------------------注册、登录-------------------------------------------
   //注册	
   public function register(){
   		$redirectURL = urldecode($_REQUEST['redirecturl']);
   		if(false!=$this->isLogin())redirect(($redirectURL?$redirectURL:"/member/account/"));
   		if(isset($_POST) &&!empty($_POST['mobile'])){
   			$_POST['_cmd_'] = "member_register";
   			$_POST['type'] = "reg";
   			$_POST['_wx_openid_'] = $_POST['wx_openid'];
   			$_POST['_wx_trans_str_'] = md5($_POST['wx_openid']."|_342sf923wes#");
   			$_POST['regip'] = get_client_ip();
   			$_POST['lastip'] = get_client_ip();
   			$service_res = $this->service($_POST);
   			if(0===$service_res['errorcode']){
   				$_SESSION['token'] = $service_res['dataresult']['_token_'];
                //var_dump($_SESSION['token']);exit;
          //绑定经纪人邀请码
          $agentRecintcode = $_REQUEST['agentRecintcode']?$_REQUEST['agentRecintcode']:'';
          if(!empty($agentRecintcode)){
             M("member")->where("id={$service_res['dataresult']['mid']}")->save(array("agentrecintcode"=>$agentRecintcode));
          }
          $redirectURL = $this->_post("redirecturl",'trim');
   				redirect(($redirectURL?$redirectURL:"/member/account/"));
   			}
   		}
   		if(empty(session('_register_mobile_')))session('_register_mobile_',md5(time()));
   		if(!$_GET['code'] && empty($_POST['wx_openid']))$this->getCode();
   		$this->assign('wx_openid',empty($_POST['wx_openid'])?$this->getOpenid($_GET['code']):$_POST['wx_openid']);
   		if(!empty($service_res['errormsg'])) echo "<script type='text/javascript'>alert('{$service_res['errormsg']}');</script>";
      $this->assign('redirecturl',$redirectURL); 
   		$this->assign('error',$service_res['errormsg']);
   		$this->assign('_register_mobile_',session('_register_mobile_'));
   		$this->assign('recintcode',$_REQUEST['recintcode']?M('member')->where("invitecode='{$_REQUEST['recintcode']}' or mobile='{$_REQUEST['recintcode']}'")->find():'');
   		$this->display();
   }
   //注册发送验证码
   public function registerSendSms(){
   		$params = array();
   		$params['_cmd_'] = "member_register";
   		$params['type'] = "get_mobileverify";
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
   		$redirectURL = urldecode($_REQUEST['redirecturl']);
   		if(false!=$this->isLogin())redirect(($redirectURL?$redirectURL:"/member/account/"));
   		if(!$_GET['code'] && empty($_SESSION['login_openid']))$this->getCode();
        //自动登录
    	if($_SESSION['login_openid'] || false!=($_SESSION['login_openid'] =  $this->getOpenid($_GET['code']))){
    		$params = array();
    		$params['_wx_openid_'] = $_SESSION['login_openid'];
    		$params['_wx_trans_str_'] = md5($params['_wx_openid_']."|_342sf923wes#");
    		$params['_cmd_'] = "weixin_autologin";
    		$login_res = $this->service($params);
    		if(0===$login_res['errorcode']){
    			$_SESSION['token'] = $login_res['dataresult']['_token_'];
    			redirect(($redirectURL?$redirectURL:"/member/account/"));
    		}
    			
    	}
    	if(isset($_POST) && !empty($_POST['mobile'])){
    		$params = array('mobile'=>$_POST['mobile'],'password'=>$_POST['password'],'_cmd_'=>'member_login');
    		if(!empty($_SESSION['login_openid'])){
    			$params['_wx_openid_'] = $_SESSION['login_openid'];
    			$params['_wx_trans_str_'] = md5($params['_wx_openid_']."|_342sf923wes#");
    		}
    		$log_res = $this->service($params);
    		if(0===$log_res['errorcode']){
    			$_SESSION['token'] = $log_res['dataresult']['_token_'];
    			redirect(($redirectURL?$redirectURL:"/member/account/"));
    		}
    	}
		
    	$this->assign('error',$log_res['errormsg']);
    	if($log_res['errormsg']=='您已登录，请不要重复登陆') session('_deviceid_',null);
   		$this->display();
   }
   
 //---------------------------------------------退出、找回密码----------------------------
   
   //退出
   public function loginOut(){
	   	$params = array();
	   	if(!$_GET['code'] && empty($_SESSION['login_openid']))$this->getCode();
	   	if($_SESSION['login_openid'] || false!=($_SESSION['login_openid'] =  $this->getOpenid($_GET['code']))){
	   		$params['_wx_openid_'] = $_SESSION['login_openid'];
    		$params['_wx_trans_str_'] = md5($params['_wx_openid_']."|_342sf923wes#");
	   	}
	   	  $params['_cmd_'] = 'member_logout';	
	   	  $service_res = $this->service($params);
	   	  if(0===$service_res['errorcode']){
	   	  	unset($_SESSION['token']);
	   	  	redirect("/member/login/");
	   	  }
   	  
   }
   //找回密码
   public function recoverPwd(){
   		if(isset($_POST) && !empty($_POST['verify_code'])){
   			$params = array();
   			$params['_cmd_'] = "member_recoverpwd";
   			$params['type'] = "recover";
   			$params['mobile'] = $_POST['mobile'];
   			$params['verify_code'] = $_POST['verify_code'];
   			$service_res = $this->service($params);
   			session('_recoverpwd_mobile_',null);
   			if(0===$service_res['errorcode'])
   				$this->redirect("reset",array('recover_access_token'=>$service_res['dataresult']['recover_access_token'],'mobile'=>$service_res['dataresult']['mobile']));
   		}
   		if(empty(session('_recoverpwd_mobile_')))session('_recoverpwd_mobile_',md5(time()));
   		$this->assign('error',$service_res['errormsg']);
   		$this->assign('_recoverpwd_mobile_',session('_recoverpwd_mobile_'));
   		$this->display();
   }
   //找回密码发送短信
   public function recoverPwdSendSms(){
   		if(!empty($_POST['mobile'])){
   			$params = array();
   			$params['_cmd_'] = "member_recoverpwd";
   			$params['type'] = "get_mobileverify";
   			$params['mobile'] = $_POST['mobile'];
   			if(empty($_POST['_recoverpwd_mobile_']) || empty(session('_recoverpwd_mobile_')) || $_POST['_recoverpwd_mobile_']!=session('_recoverpwd_mobile_') || S(session('_recoverpwd_mobile_'))>10){
	   			session('_recoverpwd_mobile_',null);
	   			$this->ajaxError('页面已失效，请刷新页面:'.session('_recoverpwd_mobile_'));
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
   //重置密码
   public function reset(){
   	   if(!empty($_POST)){
   	   	$params = array();
   	   	$params['_cmd_'] = "member_recoverpwd";
   	   	$params['type'] = "recover_submit";
   	   	$params['mobile'] = $_POST['mobile'];
   	   	$params['recover_access_token'] = $_POST['recover_access_token'];
   	   	$params['setpwd'] = $_POST['setpwd'];
   	   	$params['resetpwd'] = $_POST['resetpwd'];
   	   	$service_res = $this->service($params);
   	   	if(0===$service_res['errorcode'])
   	   		$service_res['errormsg'] = "新密码修改成功";
   	   }
   	   $this->assign('status',0===$service_res['errorcode']?1:0);
   	   $this->assign('error',$service_res['errormsg']);
   	   $this->display();
   }
   //邀请记录
   public function inrecord(){
      $this->display();
   }
   public function caraccreditation(){
    $this->display();
   }
   //个人中心
   //个人中心
   
//-------------------------------账户->二维码、签到、修改头像----------------------------------------------   
   //账户列表
   public function account(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$member_info['url'] = C("TMPL_PARSE_STRING._WWW_")."/member/register/recintcode/{$member_info['invitecode']}";
   	$member_info['is_avatar'] = 1;
   	import("Think.ORG.Util.Fuyou");
   	$fuyou = new Fuyou();
   	$balance = $fuyou->BalanceAction($member_info['id']);
   	if(false==$balance){
   		$member_info['balance'] = 0;
   	}else{
   		$member_info['balance'] = intval($balance['ca_balance'])/100;
   	}
   	
    //未读消息数值统计
    $params = array();
    $params['_cmd_'] = "system";
    $params['type'] = "count";
    $service_res = $this->service($params);
    if($service_res){
       $count = $service_res['dataresult']['count'];
    }//dump($service_res);exit;
    $this->assign('count',$count);
    $this->assign('data',$service_res['dataresult']);
   	//$this->qrcode($member_info);
   	$this->assign('member_info',$member_info);
   	$this->set_seo(array('title'=>'个人中心'));
   	import("Think.ORG.Util.wxjs");
   	$wxjs = new wxjs;
   	$signPackage = $wxjs->GetSignPackage();
   	$this->assign('signPackage',$signPackage);
   	unset($_SESSION['from'],$_SESSION['from_index']);//标识是否首页进入到更多链接
   	//生成个人名片
   	import("Think.ORG.Util.myqrcode");
   	$qrcode = new myqrcode($member_info['id']);
   	$this->assign("card_name","{$member_info['id']}/card_{$member_info['id']}.png");
    //防止多次分享
    if(empty(session('is_share')))session('is_share',md5(time()));
    $this->assign("is_share",session("is_share"));
    /*
     认证金牌经纪人
    */   
    $agent_info = $this->agent();//dump($agent_info);  
    $this->assign("open_status",$$agent_info['open_status']);
    $this->assign("agent_info",$agent_info);  
   	$this->display();
   }
   /*
   认证金牌经纪人
    */
   public function agent(){
    $agent_info = array();   
    //判断是否已经认证金牌经纪人
    $params = array();
    $params['_cmd_'] = "member_changeuserinfo";
    $params['type'] = "member_info";
    $service_res = $this->service($params);
    $agent_info = $service_res['dataresult']['member_info'];
    //判断是否满足认证条件
    $agent_info['open_status'] = $this->openAgent(1);
    //判断账户余额是否足够认证
     $params['_cmd_'] = "goldAgent";
     $params['type'] = "setting";
     $service_res = $this->service($params);
     $agent_setting = $service_res['dataresult'];
     $agent_info['pay_money'] = $agent_setting['pay_money'];
     $agent_info['is_enough'] = R("Agent/enough_money",array($agent_setting['pay_money']));
     return $agent_info;
   }
   //判断是否满足认证经纪人
   //$type：是否需要返回值　默认(为空)：不需要（不为空）；"re":需要(返回是否满足认证经纪人：1：不满足：0：满足)
    public function openAgent($type=''){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/account"));
        $res = R("Agent/get_agent_status",array(1));
        if(!empty($type)) return $res['state'];
        redirect(($res['state'] == 1)?$res['url']:"/member/account/is_alert/1");
    }
   //分享得一个积分
   public function addScore(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
    if($this->_post("is_share") == session("is_share")){
       $params = array();
       $params['_cmd_'] = "share";
       $params['type'] = "addScore";
       $service_res = $this->service($params);
       $this->ajaxSuccess($service_res['dataresult']['result']);
    }else{
       $this->ajaxError('请重新分享');
    }
   }
    //线客服
    public function online(){
        $server_id = $_GET['id'];
        if(empty($server_id)){
            return 0;
        }
        if(false==$this->isLogin())redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
        /*$member_info = $this->getMemberInfo();
        $where['fro_to_id'] = $server_id.'_'.$member_info['id'];
        $key = 'msg'.$where['fro_to_id'];
        $last_time = $this->redis->hget($key,0);
        if(empty($last_time)){
            $last_time = 0;
        }//$last_time = 0;
        $where['created'] = array('egt',$last_time);
        $list = M('welive_msg')->where($where)->select();
        $this->redis->hset($key,0,time());*/
        $params = array();
        $params['_cmd_'] = "customer_list";
        $params['type'] = "online";
        $params['server_id'] = $server_id;
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->assign('list',$service_res['dataresult']['list']);
            $this->assign('id',$server_id);
            //$member_info = $service_res['dataresult']['member_info'];
            $this->assign('avatar',($service_res['dataresult']['avatar'])?($service_res['dataresult']['avatar']):("_STATIC_/2015/member/image/account/heads.png"));
            $this->assign('server_avatar',"_STATIC_/2015/member/image/account/ke.png");
        }
        $this->display();
    }
    //写数据
    public function writeMessage(){
        $id = $this->_post('id', 'intval', 0);
        $content = $this->_post('content');
        /*$member_info = $this->getMemberInfo();
        $data['fro_to_id'] = $id.'_'.$member_info['id'];
        $data['msg'] = $content;
        $data['created'] = time();
        $data['type'] = 0;
        $return = M('welive_msg')->add($data);
        $key = 'msg'.$data['fro_to_id'];
        $this->redis->hIncrBy($key,'new',1);*/
        $params = array();
        $params['_cmd_'] = "customer_list";
        $params['type'] = "writeMessage";
        $params['server_id'] = $id;
        $params['content'] = $content;
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->ajaxReturn('发送成功','json');
        }else{
            $this->ajaxReturn('发送失败,请重新发送','json');
        }
    }
    /**
     *定时获取消息
     */
    public function getMessageJson(){
        $id = $this->_post('id', 'intval', 0);
        /*$member_info = $this->getMemberInfo();
        $where['fro_to_id'] = $id.'_'.$member_info['id'];
        $key = 'msg'.$where['fro_to_id'];
        $last_time = $this->redis->hget($key,0);
        if(empty($last_time)){
            $last_time = 0;
        }
        $where['created'] = array('egt',$last_time);
        $where['type'] = 1;
        $list = M('welive_msg')->where($where)->select();
        $this->redis->hset($key,0,time());*/
        $params = array();
        $params['_cmd_'] = "customer_list";
        $params['type'] = "getMessageJson";
        $params['server_id'] = $id;
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->ajaxReturn($service_res['dataresult']['list'],'json');
        }
    }
    /**
     * 查看历史聊天记录
     */
    public function historyMessage(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/customer_list"));
        $id = $this->_post('id', 'intval', 0);
        $count = $this->_post('count');
        /*$count = $count/2;
        $where['fro_to_id'] = $id.'_'.$member_info['id'];
        $list = M('welive_msg')->where($where)->order('id desc')->limit("$count,10")->select();
        $list = array_reverse($list);*/
        $params = array();
        $params['_cmd_'] = "customer_list";
        $params['type'] = "historyMessage";
        $params['server_id'] = $id;
        $params['count'] = $count/2;
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->ajaxReturn($service_res['dataresult']['list'],'json');
        }
    }
    //手机认证
   public function autherPhone(){
   	  if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	  $this->assign('member_info',$member_info);
      $this->display();
   }
   //系统消息列表
   public function systemnews(){
       if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
       $params = array();
       $params['_cmd_'] = "system";
       $params['type'] = "list";
       $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
       $_REQUEST['status']?($params['status'] = $_REQUEST['status']):'';
       $service_res = $this->service($params);
       if($_REQUEST['is_ajax']){
           $status = $service_res['dataresult']['systemlist']?1:0;
           $this->ajaxReturn($service_res['dataresult']['systemlist'],"加载成功",$status);
       }//dump($service_res);exit;
       $this->assign('system',$service_res['dataresult']);
       $this->display();
   }
   //系统消息详情
   public function newsdetail(){
   	  $this->display();
   }

   //签到
   public function sign(){
   	if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
   		$_SESSION['token'] = $_REQUEST['token'];
   	}
   	
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$params = array();
   	$params['_cmd_'] = "member_sign";
   	if($_REQUEST['data'] && !empty($_REQUEST['data']['type'])){
   		$success = array('receive'=>'领取成功','sign'=>'签到成功');
   		$params['type'] = $_REQUEST['data']['type'];
   		$service_res = $this->service($params);
   		if(0===$service_res['errorcode'])
   			$this->success($success[$_REQUEST['data']['type']]);
   		else
   			$this->error($service_res['errormsg']);
   	}
   	$params['type'] = "list";
   	$service_res = $this->service($params);
   	$this->assign('data',$service_res['dataresult']);
   	$this->display();
   }
   
   //修改头像
   public function avatar(){
       if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
       $memberid = $member_info['id'];
       //upload picture
       $type = 'avatar';
       import('ORG.Net.UploadFile');
       $upload = new UploadFile();// 实例化上传类
       //$upload->maxSize  = 3145728 ;// 设置附件上传大小
       $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
       $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
       $upload->saveRule = 'code_'.$memberid.time();           //设置文件保存规则唯一
       $upload->thumb = true;
       //$upload->saveRule = $memberid.time();           //设置文件保存规则唯一
       $upload->zipImages = true;
       $upload->thumbRemoveOrigin = true;
       // 设置引用图片类库包路径
       //$upload->imageClassPath = '@.ORG.Image';
       $upload->uploadReplace = true;                 //同名则替换
       //设置需要生成缩略图的文件后缀
       //$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
       //设置缩略图最大宽度
       $upload->thumbMaxWidth = '400,100';
       //设置缩略图最大高度F
       $upload->thumbMaxHeight = '400,100';
       if(!$upload->upload()) {// 上传错误提示错误信息
           $this->ajaxError($upload->getErrorMsg());
       }else{// 上传成功 获取上传文件信息
           $info =  $upload->getUploadFileInfo();
       }
       $filepath['url'] = '/Upload/'.$type.'/';
       $filepath['filename'] = $info[0]['savename'];
       $data['avatar'] = 'thumb_'.$filepath['filename'];
       $where['memberid'] = $memberid;
       $return = M('Member_info')->where($where)->save($data);
       /*
        * 更新我的二维码
       * */
       $qrcode = array('memberid'=>$memberid,'avatar'=>'thumb_'.$filepath['filename'],'url'=>C("TMPL_PARSE_STRING._WWW_")."/member/register/recintcode/{$member_info['invitecode']}",'is_avatar'=>1,'is_update'=>1);
       $this->qrcode($qrcode);
       if(!$return){
           $this->ajaxError('上传失败');
       }else{
           $this->ajaxReturn($data,'上传成功');
       }
   }
   
 //-----------------------------------账户->我的订单、待评价----------------------------------
   //我的订单列表
   public function myorder(){
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$params = array();
   	$params['_cmd_'] = "order";
   	$params['type'] = "list";
   	$params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
   	$_REQUEST['status']?($params['status'] = $_REQUEST['status']):'';
   	$service_res = $this->service($params);
   	if($_REQUEST['is_ajax']){
   	  $status = $service_res['dataresult']['orderlist']?1:0;
   	  $this->ajaxReturn($service_res['dataresult']['orderlist'],"加载成功",$status);
   	}//dump($service_res);exit;
   	$this->assign('order',$service_res['dataresult']);
   	$this->display();
   }
   //贷款详情-买车贷
   public function buydetail(){
	   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
	   	$params = array();
	   	$params['_cmd_'] = "order";
	   	$params['type'] = "buydetail";
	   	$params['order_id'] = $_REQUEST['order_id'];
	   	$service_res = $this->service($params);//dump($service_res);exit;
	   	if(false==$service_res['dataresult']['data'])
	   		$this->assign("applycode_err",1);//订单号错误
	   	$this->assign('data',$service_res['dataresult']['data']);
	   	$this->display();
   }
   
   //贷款详情-车抵贷
   public function loanerp(){
   	 if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	 $params = array();
   	 $params['_cmd_'] = "order";
   	 $params['type'] = "loanerp";
   	 $params['ContractNo'] = $_REQUEST['ContractNo'];
   	 $service_res = $this->service($params);//dump($service_res);exit;
   	 if(false==$service_res['dataresult']['loanerp'])
   	 	$this->assign("applycode_err",1);//合同编号错误
   	 $this->assign('data',$service_res['dataresult']);
   	 $this->display();
   }
   //还款明细
   public function loandetail(){
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$params = array();
   	$params['_cmd_'] = "order";
   	$params['type'] = "loanerp";
   	$params['ContractNo'] = $_REQUEST['ContractNo'];
   	$service_res = $this->service($params);
   	$this->assign('data',$service_res['dataresult']);
   	$this->display();
   }
   
   
	//待评价列表
   public function myassed(){
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$params = array();
   	$params['_cmd_'] = "order";
   	$params['type'] = "assed";
   	$params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
   	$_REQUEST['status']?($params['status'] = $_REQUEST['status']):'';
   	$service_res = $this->service($params);
   	if($_REQUEST['is_ajax']){
   	  $status = $service_res['dataresult']['assedlist']?1:0;
   	  $this->ajaxReturn($service_res['dataresult']['assedlist'],"加载成功",$status);
   	}
   	$this->assign('assedlist',$service_res['dataresult']['assedlist']);
   	 $this->display();
   }
    //已评价列表
    public function myasseded(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
        $params = array();
        $params['_cmd_'] = "order";
        $params['type'] = "asseded";
        $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
        $service_res = $this->service($params);
        if($_REQUEST['is_ajax']){
            $status = $service_res['dataresult']['assedlist']?1:0;
            $this->ajaxReturn($service_res['dataresult']['assedlist'],"加载成功",$status);
        }
        $this->assign('assedlist',$service_res['dataresult']['assedlist']);
        $this->display();
    }
   //我要评价
   public function assed(){
       if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
       import("Think.ORG.Util.wxjs");
       $wxjs = new wxjs;
       $signPackage = $wxjs->GetSignPackage();
       $this->assign('signPackage',$signPackage);
       $this->assign('isWeixin',strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false?0:1);
       $params = array();
       $params['_cmd_'] = "order";
       $params['type'] = "is_self_order";
       $params['order_id'] = $this->_get('orderid','trim');
       $service_res = $this->service($params);
       if(!empty($service_res['errorcode'])){
           $this->assign('error',$service_res['errormsg']);
           //$this->error($service_res['errormsg']);
       }
       $assed_type = $this->_get('type','trim');
       $this->assign('assed_type',$assed_type);
       $this->assign('order_id',$params['order_id']);
       $this->assign('applyCode',$service_res['dataresult']['data']['applyCode']);
       $this->assign('backtotalmoney',$service_res['dataresult']['data']['backtotalmoney']);
   	   $this->display();
   }
    //我要评价提交
    public function assedCommit(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/assed"));
        $memberid = $member_info['id'];
        //upload picture
        $data = $_POST;
        //判断是否已经评论过
        $where['order_id'] = $data['uniqe_id'];
        $where['memberid'] = $memberid;
        //$where['status'] = array('in','0,1,3');
        $is_assed = M('order_assed')->where($where)->find();
        if(!empty($is_assed)){
            if($is_assed['status']!=2){
                $this->ajaxError('不能重复评价');
            }
        }
        $info = array();
        if(!empty($_FILES)){
            $type = 'assed';
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();// 实例化上传类
            $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
            $upload->thumb = true;
            $upload->saveRule = $memberid.time();           //设置文件保存规则唯一
            // 设置引用图片类库包路径
            //$upload->imageClassPath = '@.ORG.Image';
            $upload->uploadReplace = true;                 //同名则替换
            $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
            //设置缩略图最大宽度
            $upload->thumbMaxWidth = '400,100';
            //设置缩略图最大高度F
            $upload->thumbMaxHeight = '400,100';
            if(!$upload->upload()) {// 上传错误提示错误信息
                $this->ajaxError($upload->getErrorMsg());
            }else{// 上传成功 获取上传文件信息
                $info =  $upload->getUploadFileInfo();
            }
            $file['images'] = '';
            foreach($info as $inf){
                $file['images'] = $file['images'].'|'.$inf['savename'];
            }
        }
        $file['order_id'] = $data['uniqe_id'];
        $file['memberid'] = $memberid;
        $file['content'] = $data['content'];
        $file['location'] = $data['map_url'];
        $file['timeadd'] = time();
        $file['status'] = $file['is_cream'] = 0;
        if(empty($is_assed)){
            $return = M('order_assed')->add($file);
        }else{
            $order_where['order_id'] = $data['uniqe_id'];
            $return = M('order_assed')->where($order_where)->save($file);
        }
        if($return===false){
            $this->ajaxError('操作失败');
        }else{
            if(empty($is_assed)){
                //评论得积分，并推送通知（微信|站内信）
            	import("Think.ORG.Util.wxMessage");
            	$msg = new wxMessage;
            	$msg->commentSuccess($memberid,$data['uniqe_id']);
            	
            }
            $this->ajaxReturn($file,'json');
        }
    }

   
 //------------------------------------账户->账户中心-------------------------------------------
	//账户中心列表
   public function accountCenter(){
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	 $this->assign('openfuyou',$member_info['fuyou_login_id']);
   	 $this->display();
   }
   //修改手机号
   public function setMobile(){
   	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	 $this->assign('member_info',$member_info);
   	 $this->display();
   }
   //修改密码
   public function setpwd(){
   	  if(false==$this->isLogin())redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	  if(isset($_POST) && !empty($_POST['old_pwd'])){
   	  	$params = array();
   	  	$params['_cmd_'] = "member_changeuserinfo";
   	  	$params['type'] = "setpwd";
   	  	$params['old_pwd'] = $_POST['old_pwd'];
   	  	$params['new_pwd'] = $_POST['new_pwd'];
   	  	$params['renew_pwd'] = $_POST['renew_pwd'];
   	  	$service_res = $this->service($params);
   	  	if(0===$service_res['errorcode']){
   	  		$this->success("新密码修改成功");
   	  	}else{
   	  		$this->error($service_res['errormsg']);
   	  	}
   	  }
   	 $this->display();
   }
    //账户中心
   public function acenter(){
        $this->display();
   }
   //系统消息详情
   public function systems(){
       if(false==$this->isLogin())redirect("/member/login?redirecturl=".urlencode("/member/systems?id=".$_GET['id']));
       $params = array();
       $params['_cmd_'] = "system";
       $params['type'] = "list_info";
       $params['id'] = $_GET['id'];
       $service_res = $this->service($params);
       //var_dump($service_res);exit;
       if($service_res){
           $systemlist = $service_res['dataresult']['systemlist'];
       }
       //var_dump($list);exit;
       $this->assign('systemlist',$systemlist);
       $this->display();
   }

   //修改用户名
   public function setUsername(){
   	  if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	  if(isset($_POST) && !empty($_POST['username'])){
   	  	$params = array();
   	  	$params['_cmd_'] = "member_changeuserinfo";
   	  	$params['type'] = "setusername";
   	  	$params['username'] = $_POST['username'];
   	  	$service_res = $this->service($params);
   	  	if(0===$service_res['errorcode']){
   	  		$this->success("用户名修改成功");
   	  	}else{
   	  		$this->error($service_res['errormsg']);
   	  	}
   	  }
   	  $this->assign('member_info',$member_info);
   	  $this->set_seo(array('title'=>'个人中心'));
   	  $this->display("setusername");
   }
   //修改邀请码
   public function recommend(){
   	   if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	   if(empty(session('_changerecommend_'))){
   	   	session('_changerecommend_',time().$member['id']);
   	   }
   	   $this->assign('_changerecommend_',session('_changerecommend_'));
   	   $this->assign('member_info',$member_info);
   		$this->display();
   }
   
   public function dorecommend(){
   		if(false==($member_info = $this->getMemberInfo()))$this->error("请重新登录");
        $memberid = $member_info['id'];
        $valid_code = $_POST['valid_code'];
        $invite_mobile = $_POST['invite_mobile'];
        if(empty($valid_code)  || empty($invite_mobile)){
            $this->ajaxError('请填写必要信息');
        }
        //判断所写的新邀请人是否存在
        if(!M('member')->where("mobile='{$invite_mobile}'")->field("id")->find()){
            $this->ajaxError('邀请人手机号未注册');
        }
        //判断是否已经有申请记录
        $where['status'] = 1;
        $where['memberid'] = $memberid;
        $apply_info = M('MemberRecintApply')->where($where)->find();
        if(!empty($apply_info)){
            $this->ajaxError('对不起，您已提交申请,请耐心等待审核!');
        }
        if (session('smscode') != md5($valid_code)) {
            $this->ajaxError('手机验证码错误','json');
        }else{
        	session('smscode',null);
        }
        //upload picture
        $type = 'invite_code';
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类

        $upload->maxSize  = 3145728 ;// 设置附件上传大小
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
        $upload->saveRule = 'code_'.$memberid.'_'.time();           //设置文件保存规则唯一
        $upload->thumb = true;
        // 设置引用图片类库包路径
        //$upload->imageClassPath = '@.ORG.Image';
        $upload->uploadReplace = true;                 //同名则替换
        //设置需要生成缩略图的文件后缀
        $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '400,100';
        //设置缩略图最大高度F
        $upload->thumbMaxHeight = '400,100';
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->ajaxError($upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        $filepath['url'] = '/Upload/'.$type.'/';
        $filepath['filename'] = $info[0]['savename'];
        $data['memberid'] = $memberid;
        $data['recint_code'] = $invite_mobile;
        $data['time'] = date('Y-m-d H:i:s');
        $data['status'] = 1;
        $data['filename'] = $filepath['filename'];
        $data['recint_code_befor'] = $member_info['recintcode'];
        $return = M('MemberRecintApply')->add($data);
        if(!$return){
            $this->ajaxError('操作失败');
        }else{
            $this->success('申请成功,请等待审核!');
        }
   }
   
   public function sendSmsChangeRecommend(){
   	   if(false==($member_info = $this->getMemberInfo()))$this->error("请重新登录");
        $mobile = $member_info['mobile'];
        $sms_type = 'changerecommend';
        //防止程序重复刷   手机号验证码
        S(session('_changerecommend_'))?S(session('_changerecommend_')):S(session('_changerecommend_'),0);
        if(empty($_POST['_changerecommend_']) || empty(session('_changerecommend_')) || $_POST['_changerecommend_']!=session('_changerecommend_') || S(session('_changerecommend_'))>2){
            session('_changerecommend_',null);
            $this->ajaxError('页面已失效，请刷新页面');
        }
        //开始发送验证码
        $sent = sendverify($mobile,1,'',$sms_type);
        if($sent){
            S(session('_changerecommend_'),S(session('_changerecommend_'))+1,10*60);
            //$this->ajaxcheck('changeMobileCode', array('value' => $mobile, 'valid' => true, 'msg' => '','timeadd'=>date("Y-m-d H:i:s")));
        }
        $info = array();
        $info['msg'] = '已将短信验证码发送至您手机，请注意查收。';
        $this->ajaxReturn($info,'json');
    }

//-----------------------------------------账户->邀请记录、活动专区、在线客服、更多-----------------------------

   //邀请记录
   public function recommList(){
   	  if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	  $params = array();
   	  $params['_cmd_'] = "member_recommend";
   	  $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
   	  $service_res = $this->service($params);
   	  if($_REQUEST['is_ajax']){
   	  	$status = $service_res['dataresult']['recommlist']?1:0;
   	  	$this->ajaxReturn($service_res['dataresult']['recommlist'],"加载成功",$status);
   	  }
   	  $this->assign('recommend',$service_res['dataresult']);
   	  $this->display();
   }
    //邀请活动专区
    //活动券
   public function activity(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
    $activity_ticket = M('credit_ticket')->where("memberid={$member_info['id']} and status in(0,1,2)")->order("timeadd desc")->select();
    $this->assign("ticket_lists",$activity_ticket);
    $this->display();
   }
   //过期活动券
   public function activity_overdue(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
    $over_ticket = M('credit_ticket')->where("memberid={$member_info['id']} and status=3")->order("timeadd desc")->select();
    $this->assign("over_ticket",$over_ticket);
    $this->display();
   }
   //活动专区
   public function activity_result(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME."/ticket_id/".$_REQUEST['ticket_id']));
    if(!empty($_REQUEST['ticket_id'])){
      $ticket_info = M('credit_ticket')->where("id={$_REQUEST['ticket_id']}")->find();
      $this->assign("ticket_info",$ticket_info);
    }else{
      $this->error("活动券异常！");
    }    
    $this->display();
   }
   //活动专区-分享
   public function activity_share(){
    if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
    import("Think.ORG.Util.wxjs");
    $wxjs = new wxjs;
    $signPackage = $wxjs->GetSignPackage();
    $this->assign('signPackage',$signPackage);
    //生成个人名片
    import("Think.ORG.Util.myqrcode");
    $qrcode = new myqrcode($member_info['id']);
    $this->assign("card_name","_STATIC_/Upload/qrcode/{$member_info['id']}/card_{$member_info['id']}.png");
    $this->assign("member_info",$member_info);
    $this->display();
   }
    //在线客服列表页
    public function customer_list(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
    	if($_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST']."/" || strtolower($_SERVER['HTTP_REFERER'])=="http://".$_SERVER['HTTP_HOST']."/index" || strtolower($_SERVER['HTTP_REFERER'])=="http://".$_SERVER['HTTP_HOST']."/index/index")
    		$_SESSION['from_index'] = 1;
        /*$where['groupid'] = 2;
        $lists = M('User')->where($where)->select();
        foreach($lists as &$list){
            $list['ison'] = $this->redis->hget('kf_online',$list['id']);
            $list['ison'] = ($list['ison']+28800>time())?1:0;
            if(empty($list['nickname'])){
                $list['nickname'] = $list['id'];
            }
        }
        foreach ($lists as $k => $v) {
            $edition[] = $v['ison'];
        }
        array_multisort($edition,SORT_DESC,$lists);*/
        $params = array();
        $params['_cmd_'] = "customer_list";
        $params['type'] = "customerList";
        $service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $this->assign('list',$service_res['dataresult']['list']);
        }
//        $this->assign('list',$lists);
        $this->display();
    }
   //更多首页(关于我们、帮助中心、意见反馈)
   public function more(){
     //if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
     if($_SERVER['HTTP_REFERER']=="http://".$_SERVER['HTTP_HOST']."/" || strtolower($_SERVER['HTTP_REFERER'])=="http://".$_SERVER['HTTP_HOST']."/index" || strtolower($_SERVER['HTTP_REFERER'])=="http://".$_SERVER['HTTP_HOST']."/index/index")
     	$_SESSION['from'] = 1;
   	 $this->display();
   }
   //关于我们首页
   public function aboutus(){
   	$this->display();
   }
   //关于我们-公司简介
   public function aboutcompany(){
   	$this->display();
   }
   //借吧简介
   public function jieba(){
        $this->display();
   }
   //关于我们-合作伙伴
   public function aboutpartner(){
   	$this->display();
   }
   //关于我们-联系我们
   public function aboutcontact(){
    //if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
   	$this->display();
   }
    //地图
   public function map(){
    	$this->assign('width' , $this->_get('width')?$this->_get('width'):810 );
    	$this->assign('height' , $this->_get('height')?$this->_get('height'):450 );
    	$this->assign('content' , $this->_get('content')?$this->_get('content'):'上海宝山区殷高西路101号高景国际大厦' );
    	$this->assign('title' , $this->_get('title')?$this->_get('title'):'智信创富金融');
    	$this->assign('lng' , $this->_get('lng')?$this->_get('lng'):121.49082 );
    	$this->assign('lat' , $this->_get('lat')?$this->_get('lat'):31.327448 );
    	$this->assign('addControl' , $this->_get('addControl')=='false'?false:true );
    	 
    	$this->display();
   }
   //帮助中心列表
   public function helpcenter(){
   	$this->display();
   }
   //帮助中心-问题详情
   public function helpcenterqa(){
   	$this->display();
   }
    //订单问题
   public function order(){
   	$this->display();
   }
    //买车贷常见问题
   public function buy(){
   	$this->display();
   }
    //车抵贷常见问题
   public function car(){
   	$this->display();
   }
    //我与车生活常见问题
    public function life(){
        $this->display();
    }
   //意见反馈
   public function feedback(){

       //if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/member/".ACTION_NAME));
       if(isset($_POST) &&!empty($_POST['content'])){
           $params = array();
           $params['_cmd_'] = "view";
           $params['type'] = "view_add";
           $params['content'] = $_POST['content'];
           $service_res = $this->service($params);
           if( 0 === $service_res['errorcode']){
               $this->success("提交成功");
           }else{

               $this->error($service_res['errormsg']);
           }
       }
       $this->assign('error',$service_res['errormsg']);
   	   $this->display();
   }
   
   public function agreement(){
   	  $this->display();
   }

//----------------------------------------工具函数----------------------------------------------------- 
   /*
    * 生成我的二维码
    * 	$member_info array
    * 		$member_info['memberid']:用户id
    * 		$member_info['avatar']:头像
    * 		$member_info['url']:二维码url
    * 		$member_info['is_avatar']:二维码是否带头像
    *       $member_info['is_update']:二维码是否更新
    * */
   private  function qrcode(&$member_info){
   	$dir = APP_PATH."../static/Upload/qrcode/";
   	$file = "qrcode_{$member_info['memberid']}.png";
   	$default_avatar = APP_PATH."../static/2015/member/image/account/heads.png";
   	if(!is_file($dir.$file) || $member_info['is_update']==1){
   		if(!is_dir($dir))mkdir($dir,0755);
   		vendor("phpqrcode.phpqrcode");
   		QRcode::png($member_info['url'],$dir.$file,"H",5);
   		$member_info['is_avatar']?$this->qrcodeLogo($dir.$file,$member_info['avatar']?APP_PATH."../static/Upload/avatar/{$member_info['avatar']}":$default_avatar):'';
   		M('member_info')->where("memberid='{$member_info['memberid']}'")->save(array('qrcode'=>"/Upload/qrcode/{$file}"));
   		$member_info['qrcode'] = "/Upload/qrcode/{$file}";
   	}
   }
   //生成带头像的二维码
   private function qrcodeLogo($QR,$logo){
   	if(!is_file($QR) || !is_file($logo))return false;
   	$logo_function = strtolower(substr($logo,strrpos($logo,".")+1))=="png"?"imagecreatefrompng":"imagecreatefromjpeg";
   	list($max_width, $max_height) = getimagesize($QR);
   	list($src_width,$src_height) = getimagesize($logo);
   	$desc_img = imagecreatefrompng($QR);
   	$thumbImgWidth = "50";$thumbImgHeight = "50";
   	$scale = min($thumbImgWidth / $src_width, $thumbImgHeight / $src_height);
   	$thumbImg = imagecreate($src_width*$scale, $src_height*$scale);
   	if (function_exists("ImageCopyResampled"))
   		imagecopyresampled($thumbImg, $logo_function($logo), 0, 0, 0, 0, $thumbImgWidth, $thumbImgHeight, $src_width, $src_height);
   	else
   		imagecopyresized($thumbImg, $logo_function($logo), 0, 0, 0, 0, $thumbImgWidth, $thumbImgHeight, $src_width, $src_height);
   	imagecopymerge($desc_img,$thumbImg,$max_width/2-$thumbImgWidth/2,$max_height/2-$thumbImgHeight/2,0,0,$thumbImgWidth,$thumbImgHeight,90);
   	ImagePng($desc_img,$QR);
   }
   
}
