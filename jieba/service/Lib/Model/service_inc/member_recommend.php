<?php 
//邀请记录
$member_info = $this->token_check_force();
$page = intval($this->v('page'))>0?intval($this->v('page')):1;
$number=intval($this->v('number'))>0?intval($this->v('number')):10;
if($number>100)
	$this->error('NUMBER_ERR','每页的条数不得大于100条');
$count = M('member')->where("recintcode='{$member_info['mobile']}'")->join("`order` as o on member.id=o.memberid and o.status=2")->count('distinct member.id');
if($count>0){
	$recommList = M('member')->field("username,member.mobile,o.loanmoney,date_format(o.timeadd,'%Y-%m-%d') as timeadd")->join("`order` as o on member.id=o.memberid and o.status=2")->where("recintcode='{$member_info['mobile']}'")->limit((($page-1)*$number).",{$number}")->order("timeadd desc")->select();
}
empty($recommList)?$recommList = array():'';
$this->data = array('count'=>intval($count),'recommlist'=>$recommList);
return true;
?>