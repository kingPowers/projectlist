<?php
$this->need('type','类型');
$type = $this->v('type');
if($type=='register_verify'){
    $this->need('mobile','手机号');
    $mobile = $this->v('mobile');
    if($this->token('reg_smssend_flag')==1){
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
    $this->token('register_smscode',$smscode);
    $this->token('register_smsmobile',md5($mobile.$smscode));
    $this->token('reg_smssend_flag',1,60);
    $this->data = '验证码发送成功';
    return true;
}
if($type = "reg_info"){
   $this->need('mobile','手机号码');
    $this->need('password','密码');
    $this->need('re_password','确认密码');
    $this->need('verify_code','手机验证码');   
    $password = $this->v('password');
    $re_password = $this->v('re_password');
    $mobile = $this->v('mobile');
    $verify_code = $this->v('verify_code');	
    if(strlen($password)<6){
        return $this->error('PASSWORD_ERROR','密码不能少于6个字符');
    }
    $v = md5($verify_code);
    $a = $this->token('register_smscode');
    $b = $this->token('register_smsmobile');
    $c = md5($mobile.$this->token('register_smscode'));
    if(md5($verify_code)!=$this->token('register_smscode') || $this->token('register_smsmobile')!=md5($mobile.$this->token('register_smscode'))){
        return $this->error('VERIFY_ERROR','验证码不正确');
    }
     
    if($password!=$re_password){
        return $this->error('REPASSWORD_ERROR','两次密码不一致');
    }
    if(false!=M('Member')->where(array("mobile"=>$mobile))->find()){
        return $this->error('USERNAME_DITTO','手机号已被注册');
    }   
    $this->token('register_smscode',null);
    $this->token('register_smsmobile',null); 
    $add_array = array(
        'username'      => $mobile,
        'password'      => gen_password($password),
        'pwd_safety'    => pwd_safety($password),
        'mobile'        => $mobile,
        'source_code'   => $this->_client_,
    	'mobile_location' => get_mobile_location($mobile),
    	'regip' => $this->v('regip'),
    	'lastip' => $this->v('lastip'), 
      'usertype' => 1//默认用户类型为发单人
    );
    if($this->_wx_openid_ && false==M('Member')->where(array('wx_openid'=>$this->_wx_openid_))->find()){
        $add_array['wx_openid'] = $this->_wx_openid_;
    }
   try{
      if(!D('Common')->inTrans()){
          D('Common')->startTrans();
          $trans = true;
      }
      if(false===($res = M('Member')->add($add_array))){
        throw new Exception('系统错误请稍后再试');
      }
       $this->_token_ = $this->gen_token($res,$mobile,$add_password,$mobile,time());
       $token_res = $this->save_token_todb($this->_token_,$res,$mobile,time());
       if(!$token_res){
          throw new Exception('系统错误');
       }
       if($trans){
       	 D('Common')->commit();
       }
       $this->data = array('_token_' => $this->_token_,'mid'=>$res,'username' => $mobile);
       return true;
   }catch(Exception $ex){
   	  if($trans){
   	 	 D('Common')->rollback();
   	  }
   	 return $this->error('SYS_ERROR_86',$ex->getMessage());
   }      
}
$this->error('TYPE_ERROR','类型错误');
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