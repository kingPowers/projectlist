<?php
/*
 *登录接口 
 * 
 */
if($this->token_check_force(true)){
       $this->errorcode = 'LOGIN_DITTO';
       throw new Exception('您已登录，请不要重复登陆');
 } 
$this->need('mobile','手机号');
$this->need('password','密码');
$user_name = $this->v('mobile');
$password  = gen_password($this->v('password'));
$key = "loginerr_{$user_name}";
if(S($key)>5)$this->error("MAX_LOGIN","登录错误次数过多，请1小时后再登录");
$member = D('Member')->where("(username='{$user_name}' or mobile='{$user_name}') and (password='{$password}' {$pass_pwd})")->find();
if(!$member){
		S($key, intval(S($key)) + 1,60*10);
	    return $this->error('NO_AUTH','用户名或密码错误！');
}

try{

	if(!D('Common')->inTrans()){
		D('Common')->startTrans();
		$trans = true;
	}
	M('Member')->where(array('id'=>$member['id']))->save(array('lasttime'=>date('Y-m-d H:i:s')));
	if($this->_wx_openid_ && empty($member['wx_openid']) && false==M('Member')->where(array('wx_openid'=>$this->_wx_openid_))->find()){
		D('Member')->where(array('id'=>$member['id']))->save(array('wx_openid'=>$this->_wx_openid_));
	}
	$this->_token_ = $this->gen_token($member['id'],$member['username'],$member['password'],$member['mobile'],time());
	$res = $this->save_token_todb($this->_token_,$member['id'],$member['username'],time());
	if(false===$res){
	  throw new Exception("系统错误请稍后再试");  
	}
	if($trans){
		D('Common')->commit();	
	}
	$this->data = array('_token_'=>$this->_token_, 'username'=>$member['username']);
    return true;
}catch(Exception $e){
	if ($trans) {
		D('Common')->rollback();
	}
	return $this->error("SYS_ERROR",$e->getMessage());
}		
?>