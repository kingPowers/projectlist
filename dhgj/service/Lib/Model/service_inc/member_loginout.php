<?php
/******--------用户退出--------******/
$member_info = $this->token_check_force();
if($this->token_destory()){
	if($this->_wx_openid_)
		M('member')->where("id='{$member_info['id']}'")->save(array('wx_openid'=>''));
	$this->data = '您已安全退出';
}else			
	$this->error('SYS_ERROR_7','系统错误,请稍后再试');
return true;
?>