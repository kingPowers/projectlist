<?php
$this->need('type','类型');
$type = $this->v('type');
if($type=='get_mobileverify'){
	//$this->checkImageVerifyCode();
    $this->need('mobile','手机号');
    $mobile = $this->v('mobile');
    if($this->token('smssend_flag')==1){
        $this->error('SEND_TOO_OFTEN','您点的太频繁了');
    }
    if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
        return $this->error('MOBILE_ERROR','手机号不正确');
    }
    if(false!=M('Member')->where(array('mobile'=>$mobile))->find()){
        $this->error('MOBILE_DITTO','手机号码已被注册');
    }
    import('Think.ORG.Util.SMS');
    $sms_res = SMS::buildverify($mobile);
    if($sms_res==false){
        return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
    }
    $smscode = session('smscode');
    $this->token('smscode',$smscode);
    $this->token('smsmobile',md5($mobile.$smscode));
    $this->token('smssend_flag',1,60);
    $this->data = '验证码发送成功';
    return true;
}
if($type=='reg'){
    //验证必要性
    $this->need('password','密码');
    $this->need('re_password','确认密码');
    $this->need('mobile','手机号码');
    $this->need('verify_code','手机验证码');

    $password = $this->v('password');
    $re_password = $this->v('re_password');
    $mobile = $this->v('mobile');
    $verify_code = $this->v('verify_code');
    $recintcode = $this->v('recintcode');
    
    if(strlen($password)<6){
        return $this->error('PASSWORD_ERROR','密码不能少于6个字符');
    }
    if(md5($verify_code)!=$this->token('smscode') || $this->token('smsmobile')!=md5($mobile.$this->token('smscode'))){
        return $this->error('VERIFY_ERROR','验证码不正确');
    }
    if($password!=$re_password){
        return $this->error('REPASSWORD_ERROR','两次密码不一致');
    }
    if(false!=M('Member')->where(array("mobile"=>$mobile))->find()){
        return $this->error('USERNAME_DITTO','手机号已被注册');
    }
    
    $this->token('smscode',null);
    $this->token('smsmobile',null);
  
    $add_array = array(
        'username'      => $mobile,
        'password'      => gen_password($password),
        'mobile'        => $mobile,
        'source_code'   => $this->_client_,
    	'mobile_location' => get_mobile_location($mobile),
    	'invitecode'=>createInvcode($mobile),
    	'regip'=>$this->v('regip'),
    	'lastip'=>$this->v('lastip'),
    	
    );
    if($this->_wx_openid_ && false==M('Member')->where(array('wx_openid'=>$this->_wx_openid_))->find()){
        $add_array['wx_openid'] = $this->_wx_openid_;
    }
    if($recintcode && false!=($recomm=M('Member')->where(array('username'=>$recintcode,'mobile'=>$recintcode,'_logic'=>'or'))->find())){
    	$add_array['recintcode'] = $recomm['mobile'];
    }
    $res = M('Member')->add($add_array);
    if(false===$res){
        return $this->error('SYS_ERROR_184','系统错误请稍后再试');
    }
    $this->_token_ = $this->gen_token($res,$mobile,$add_password,$mobile,time());
    $token_res = $this->save_token_todb($this->_token_,$res,$mobile,time());
    if(!$token_res){
        $this->error('SYS_ERROR_86','系统错误');
    }
    /*
     * 注册成功后调用公共类方法
    *
    * */
    import("Think.ORG.Util.RunCommon");
    RunCommon::runRegister(['mobile'=>$mobile,'memberid'=>$res]);
    
    $this->data = array('_token_' => $this->_token_,'mid'=>$res,'username' => $mobile);
    return true;
}
$this->error('TYPE_ERROR','类型错误');

function createInvcode($mobile) {
	for($i=4;$i<=8;$i++){
		for($j=0;$j<10;$j++){
			$code=substr($mobile, -$i).array_rand(range(0,9)).array_rand(range(0,9)).array_rand(range(0,9));
			if(false==M('member')->where("invitecode='{$code}'")->field("id")->find()){
				return $code;
			}
		}
	}
}


function get_mobile_location($mobile) {
	if (!isMobile($mobile)) {
		return false;
	}
	$return = array();
	$api = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel={$mobile}";
	$string = iconv('gb2312', 'utf-8', file_get_contents($api));
	$string = str_replace(array("\r", "\n", "\r\n", "'"), array('', '', '', ''), $string);
	$string = substr($string, strpos($string, '{') + 1);
	$string = substr($string, 0, strpos($string, '}'));
	$array = explode(',', $string);
	foreach ($array as $var) {
		$ex = explode(':', $var);
		$key = trim($ex[0]);
		$val = trim($ex[1]);
		$return[$key] = $val;
	}
	return $return['province'] . '-' . $return['catName'];
}

?>