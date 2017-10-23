<?php
$this->need('type','类型');
$type = $this->v('type');
if($type == "repwd_verify"){
   $this->need('mobile','手机号');
   $mobile = $this->v("mobile");
   if($this->token("smssend_flag") == 1){
       return $this->error('SEND_TOO_OFTEN','您点的太频繁了');
   }
   if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
        return $this->error('MOBILE_ERROR','手机号不正确');
    }
    if(false==D('Member')->where(array("mobile"=>$mobile))->field('id')->find()){
		return $this->error('ACCOUNT_NOT_EXIST','手机号未注册');
	}
    import('Think.ORG.Util.SMS');
    $sms_res = SMS::buildverify($mobile);
    if($sms_res==false){
        return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
    }
    $smscode = session('smscode');
    $this->token('smscode',$smscode,600);
    $this->token('smsmobile',md5($mobile.$smscode),600);
    $this->token('smssend_flag',1,6);
    $this->data = '验证码发送成功,有效期为10分钟';
    return true;
}
if($type=='recover'){
	$this->need('mobile','手机号码');
  $this->need('verify_code','手机验证码');
	$this->need('setpwd','密码');
	$this->need('resetpwd','确认密码');
    $verify_code = $this->v('verify_code'); 
	$mobile 	 = $this->v('mobile');
	$setpwd = $this->v('setpwd');
	$resetpwd = $this->v('resetpwd');
    if(md5($verify_code)!=$this->token('smscode') || $this->token('smsmobile')!=md5($mobile.$this->token('smscode'))){
        return $this->error('VERIFY_ERROR','验证码不正确');
    }
    //清除缓存
    $this->token('smscode',null);
    $this->token('smsmobile',null);

	if(strlen($setpwd)<6){
		return $this->error('PASSWORD_ERROR','密码不能少于6个字符');
	}
	if($setpwd!=$resetpwd)$this->error("两次输入密码不一致");
	$update = D('Member')->where("mobile='{$mobile}'")->save(array('password'=>gen_password($setpwd),"pwd_safety"=>pwd_safety($setpwd)));
	if(false===$update){
		return $this->error('SYS_ERROR_50','系统错误请稍后再试');
	}
	$this->data= "密码找回成功";
	return true;
}
$this->error('TYPE_ERROR','类型错误');
?>