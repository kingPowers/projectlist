<?php 
	//签到送积分
$this->need('type','类型');
$type = $this->v('type');
$member_info = $this->token_check_force();
//签到日历列表
if($type=='list'){
	$_info = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	$sign = sign($member_info['id']);
	empty($sign)?$sign = array():'';
	$num = M('sign')->where("memberid='{$member_info['id']}' and type=1")->count();
	$signSum = M('sign')->where("memberid='{$member_info['id']}'")->sum('score');
	$this->data = array(
			'sign'=>$sign,
			'month'=>date("n"),
			'signNum'=>intval($num),
			'signSum'=>intval($signSum),
			'username'=>$member_info['username'],
			'avatar'=>$_info['avatar'],
			);
	return true;

//我要签到
}elseif($type=="sign"){
	$date = date("Y-m-d");
	$is_sign = M('sign')->where("memberid='{$member_info['id']}' and  date_format(timeadd,'%Y-%m-%d')='{$date}'")->find();
	if(false!=$is_sign)$this->error('ERR','您已签过到了');
	$add_id = M('sign')->add(array('memberid'=>$member_info['id'],'type'=>1,'score'=>1,'remark'=>'签到得积分'));
	if($add_id)return true;
	$this->error("ERR",'签到失败，请稍后再试！');
//立即领取
}elseif($type=='receive'){
	$sign = sign($member_info['id']);
	if(count($sign)!=5)$this->error("ERR",'您已领取过了');
	$where['id'] = array('in',array_column($sign,'id'));
	$where['memberid'] = $member_info['id'];
	$save_id = M('sign')->where($where)->save(array('is_receive'=>1));
	if(!$save_id)$this->error('ERR','领取失败，请稍后再试！');
	$add_id = M('sign')->add(array('memberid'=>$member_info['id'],'type'=>2,'score'=>5,'remark'=>'累计签到赠送'));
	if($add_id)return true;
	$this->error("ERR",'领取失败，请稍后再试！');

//我要兑换
}elseif($type="exchange"){
	
}
$this->error('TYPE_ERROR','type参数错误');

//未额外获取积分的记录
function sign($memberid){
	$return = array();
	$last_time = M('sign')->where("memberid='{$memberid}' and type=1 and is_receive=1")->order("timeadd desc")->find();
	$where['timeadd'] = array('gt',$last_time==false?0:$last_time['timeadd']);
	$where['memberid'] = $memberid;
	$where['type'] = 1;
	$where['is_receive'] = 0;
	$return = M('sign')->where($where)->order("timeadd desc")->limit("0,5")->select();
	return $return;
}
?>