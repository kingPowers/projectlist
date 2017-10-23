<?php
/**
 * 分享--分享到朋友圈，等
 * 
 * 
 * */
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();

if($type=='shareData'){
	
	$commonData = array(
		'title'=>'借吧--注册',
		'desc'=>'欢迎注册借吧-平台，快速借款给你',
		'link'=>_WWW_.'/member/register/recintcode/'.$member_info['invitecode'],
		'imgUrl'=>_STATIC_.'/2015/member/image/account/heads.png',
		
	);
	
	$shareData = array(
		'qq'=>$commonData,
		'qRoom'=>$commonData,
		'weixin'=>$commonData,
		'weixinFriend'=>$commonData,
	);
	$this->data = $shareData;
    return true;
}
//分享得积分
elseif($type=='addScore'){
	$where = array('memberid'=>$member_info['id'],'type'=>'11');
	$where['timeadd'] = array('between',array(date('Y-m-d'),date('Y-m-d')." 23:59:59"));
	if(false==M('sign')->where($where)->find()){
		$is_add = M('sign')->add(array('memberid'=>$member_info['id'],'type'=>'11','score'=>1,'remark'=>'分享得积分','is_receive'=>'1'));
	}
	$this->data = array('result'=>$is_add?'分享赚积分成功':"您已分享过了");
	return true;
}


$this->error('TYPE_ERR','type类型错误');
?>