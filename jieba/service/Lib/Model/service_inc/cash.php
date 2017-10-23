<?php
//充值提现管理
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();
import("Think.ORG.Util.Baofu");
$this->baofu = new Baofu($member_info['id']);
//充值
if($type=='cash_in'){
	$this->need('money',"充值金额");
	$return_url = $this->v("return_url");//接口回调地址
	$money = $this->v("money");
	if(false==$this->baofu->bindBankCardStatus()){
		$key = "webviewkey{$member_info['id']}".time();
		S($key,$is_reg,60*2);
		$this->data = array('jumpurl'=>_WWW_."/credit/bindBaofu?key={$key}&token={$this->_token_}&returnurl=".urlencode("credit/index?jieba=xjd"));
		return true;
	}
	$this->error("ERR","对不起，充值服务已暂停，请升级APP！");

	$key = "webviewkey{$member_info['id']}".time();
	S($key,$carry,60*2);
	$this->data = array('jumpurl'=>_SERVICE_."/index/webView?key={$key}");
    return true;
}
//提现
if($type=='cash_out'){
	$this->need('money',"提现金额");
	$return_url = $this->v("return_url");//接口回调地址
	$money = $this->v("money");
	if(false==$this->baofu->bindBankCardStatus()){
		$key = "webviewkey{$member_info['id']}".time();
		S($key,$is_reg,60*2);
		$this->data = array('jumpurl'=>_WWW_."/credit/bindBaofu?key={$key}&token={$this->_token_}&returnurl=".urlencode("credit/index?jieba=xjd"));
		return true;
	}
	$this->error("ERR","对不起，提现服务已暂停，请升级APP！");
	
}
$this->error('TYPE_ERR','type类型错误');
?>