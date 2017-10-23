<?php
/*
 *登录接口 
 * 
 */
$this->token_check_force(true);
if(''==$this->_wx_openid_)$this->error("OPENID_EMPTY",'openid为空');
$key = "loginauto_{$this->_wx_openid_}";
if(S($key)>5)$this->error("MAX_LOGIN","登录过于频繁");

$member = D('Member')->where("wx_openid='{$this->_wx_openid_}' and wx_openid!=''")->find();
if(!$member){
	S($key, intval(S($key)) + 1,60*10);
    return $this->error('NO_BIND','微信未绑定账号');
}
if($member['status'] != 1)return $this->error('NO_AUTH','该用户已被冻结！请联系客服！');
M('Member')->where(array('id'=>$member['id']))->save(array('lasttime'=>date('Y-m-d H:i:s')));
$this->data = array('_token_'=>$this->_token_, 'username'=>$member['username']);
return true;
?>