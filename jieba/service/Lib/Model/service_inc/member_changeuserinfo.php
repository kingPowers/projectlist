<?php
/*
 * 修改个人信息
 * 		修改密码、修改用户名、修改头像
 * */
$this->need('type','类型');
$type = $this->v('type');
$member_info = $this->token_check_force();
//修改密码
if($type=='setpwd'){
	$this->need('old_pwd','原始密码');
	$this->need('new_pwd','新密码');
	$this->need('renew_pwd','确认密码');
	$old_pwd 	 = gen_password($this->v('old_pwd'));
	$new_pwd = $this->v('new_pwd');
	$renew_pwd = $this->v('renew_pwd');
	$key = "loginerr_{$member_info['mobile']}";
	if(strlen($old_pwd)<6){
		return $this->error('PASSWORD_ERROR','密码不能少于6个字符');
	}
	if(S($key)>5)$this->error("MAX_PWD","原错误次数过多，请1小时后再修改密码");
	if(false==M('member')->where("id='{$member_info['id']}' and password='{$old_pwd}'")->find()){
		S($key, intval(S($key)) + 1,60*10);
		$this->error('NOPWD',"原密码错误");
	}
	if($new_pwd!=$renew_pwd)$this->error("PWD_NO","两次输入密码不一致");
	
	/*if(empty($this->token('recover_access_token')) || $this->token('recover_access_token')!=md5($mobile)){
		$this->error('TOKEN',"验证码失效，请重新获取手机验证码");
	}*/
	$update = D('Member')->where("id='{$member_info['id']}'")->save(array('password'=>gen_password($new_pwd)));
	if(false===$update){
		return $this->error('SYS_ERROR_50','系统错误请稍后再试');
	}
	S($key,null);
	$this->data="密码修改成功";
	return true;
}
//修改用户名
elseif($type=='setusername'){
	$this->need('username','用户名');
	$username = $this->v('username');
	$key = "setusername_{$member_info['mobile']}";
	if(!preg_match("/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]{2,8}$/u",$username)){
		$this->error("USER_ERR",'用户名必须由2-8位的汉字、字母、数字组成');
	}
	if(intval(S($key))>0){
		$this->error("USER_EXIST_NUM","每个月只可修改一次用户名");
	}
	if(M("member")->where("id='{$member_info['id']}' and id!=''")->save(array('username'=>$username))){
		S($key, intval(S($key)) + 1,60*60*24*30);
		$this->data = "用户名修改成功";
		return true;
	}else{
		$this->error("NETWORK_ERR","网络连接超时，请稍后再试");
	}
}
//修改头像
elseif($type=='setavatar'){
	
}
//实名认证
elseif($type=='realname'){
	$this->need('names','真实姓名');
	$this->need('certiNumber','身份证号');
	if(1==$member_info['nameStatus'])$this->error("ERR","您已实名认证过了");
	$realname['names'] = $this->v('names');
	$realname['certiNumber'] = $this->v('certiNumber');
	$realname['nameStatus'] = 1;
	if(false==isCreditNo($realname['certiNumber']))$this->error("ERR","身份证号错误");
	if(false!=M('member_info')->where("memberid!='{$member_info['id']}' and certiNumber='{$realname['certiNumber']}'")->find())$this->error("ERR","对不起，身份证号已被其他账号绑定过了！");
	import('Think.ORG.Util.Fuyou');
	$fuyou = new Fuyou();
	$realname_info = $fuyou->queryUserInfs_v2($realname['certiNumber']);
	if($realname_info['plain']['results']['result']['capAcntNo']){
		if(trim($realname['names'])!=trim($realname_info['plain']['results']['result']['cust_nm'])){
			$this->error("NAME_ERROR","真实姓名错误");
		}
	}
	if(false==M('member_info')->where("memberid='{$member_info['id']}'")->save($realname))$this->error("实名失败，请稍后再试");
	$this->data = "实名认证成功";
	return true;
	
}
//修改邀请码
elseif($type=='recommend'){
	$memberid = $member_info['id'];
	$this->need("valid_code","验证码");
	$this->need("invite_mobile","邀请人手机号");
	$valid_code = $this->v('valid_code');
	$invite_mobile = $this->v('invite_mobile');
	//判断所写的新邀请人是否存在
	if(!M('member')->where("mobile='{$invite_mobile}'")->field("id")->find()){
		$this->Error("NO_REG",'邀请人手机号未注册');
	}
	//判断是否已经有申请记录
	$where['status'] = 1;
	$where['memberid'] = $memberid;
	$apply_info = M('MemberRecintApply')->where($where)->find();
	if(!empty($apply_info)){
		$this->Error("NOT_ALLOW",'对不起，您已提交申请,请耐心等待审核!');
	}
	if ($this->token("changerecommend") != md5($valid_code)) {
		$this->Error("SMS_ERROR",'手机验证码错误');
	}
	//upload picture
	$type = 'invite_code';
	import('ORG.Net.UploadFile');
	$upload = new UploadFile();// 实例化上传类
	
	$upload->maxSize  = 3145728 ;// 设置附件上传大小
	$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
	$upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
	$upload->saveRule = 'code_'.$memberid.'_'.time();           //设置文件保存规则唯一
	$upload->thumb = true;
	// 设置引用图片类库包路径
	//$upload->imageClassPath = '@.ORG.Image';
	$upload->uploadReplace = true;                 //同名则替换
	//设置需要生成缩略图的文件后缀
	$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
	//设置缩略图最大宽度
	$upload->thumbMaxWidth = '400,100';
	//设置缩略图最大高度F
	$upload->thumbMaxHeight = '400,100';
	if(!$upload->upload()) {// 上传错误提示错误信息
		$this->Error("PIC_ERROR",$upload->getErrorMsg());
	}else{// 上传成功 获取上传文件信息
		$info =  $upload->getUploadFileInfo();
	}
	$filepath['url'] = '/Upload/'.$type.'/';
	$filepath['filename'] = $info[0]['savename'];
	$data['memberid'] = $memberid;
	$data['recint_code'] = $invite_mobile;
	$data['time'] = date('Y-m-d H:i:s');
	$data['status'] = 1;
	$data['filename'] = $filepath['filename'];
	$data['recint_code_befor'] = $member_info['recintcode'];
	$return = M('MemberRecintApply')->add($data);
	if(!$return){
		$this->Error("SYS_ERR",'网络连接超时，请稍后再试');
	}
	$this->data = "申请成功,请等待审核!";
	return true;
}
//修改邀请码验证码
elseif($type=='recommendSms'){
	$mobile = $member_info['mobile'];
	$sms_type = 'changerecommend';
	
	if($this->token($sms_type."_smssend_flag")==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token($sms_type,$smscode,600);
	$this->token("{$sms_type}_smsmobile",md5($mobile.$smscode),600);
	$this->token("{$sms_type}_smssend_flag",1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
	
	
	
	//开始发送验证码
	$sent = sendverify($mobile,1,'',$sms_type);
	if($sent){
		S(session('_changerecommend_'),S(session('_changerecommend_'))+1,10*60);
		//$this->ajaxcheck('changeMobileCode', array('value' => $mobile, 'valid' => true, 'msg' => '','timeadd'=>date("Y-m-d H:i:s")));
	}
	$info = array();
	$info['msg'] = '已将短信验证码发送至您手机，请注意查收。';
	$this->ajaxReturn($info,'json');
}elseif($type=='member_info'){
	//生成二维码
	import("Think.ORG.Util.myqrcode");
	new myqrcode($member_info['id']);
	$info = M()->table("member m,member_info mi")->field("m.id,m.username,m.mobile,m.invitecode,m.recintcode,mi.memberid,mi.avatar,mi.qrcode,mi.sex,mi.certificate,mi.certiNumber,mi.nameStatus,mi.names,mi.emailStatus,mi.nameStatus,if(mi.fuyou_login_id is null,m.mobile,mi.fuyou_login_id) as fuyou_login_id")->where("m.id=mi.memberid and m.id='{$member_info['id']}'")->find();
	$info['avatar'] = $info['avatar']?_STATIC_."/Upload/avatar/{$info['avatar']}":_STATIC_."/2015/member/image/account/heads.png";

	$info['nameStatus'] = 1;
	if(1==$info['nameStatus']){
		import("Think.ORG.Util.Fuyou");
		import("Think.ORG.Util.Baofu");
		$fuyou = new Fuyou();
		$baofu = new Baofu($info['memberid']);
		$info['openFuyouStatus'] = $baofu->bindBankCardStatus()?1:0;
		$info['balance'] = (false==($balance = $fuyou->BalanceAction($member_info['id'])))?'0':(intval($balance['ca_balance'])/100);
	}else{
		$info['openFuyouStatus'] = 0;
	}
	$info['is_staff'] = M('staff')->where("certiNumber='{$info['certiNumber']}' and certiNumber!=''")->find()?1:0;
	//是否开通金牌经纪人
	import("Think.ORG.Util.GoldAgent");
	$gold = new GoldAgent();
	$info['isOpenGold'] = false==($agentInfo = $gold->isOpenGold($member_info['id']))?0:1;
	if($info['isOpenGold']==1){
		$info['isPayGold'] = 1;
		$info['nickname'] = $agentInfo['nickname'];//昵称
		$info['company_name'] = $agentInfo['company_name'];//公司简称
		$info['company_full_name'] = $agentInfo['company_full_name'];//公司全称
		$info['pic_card'] = _STATIC_."/Upload/qrcode/{$member_info['id']}/".$agentInfo['pic_card'];//二维码地址
		$info['pic_card2'] = $agentInfo['pic_card2']?_STATIC_."/Upload/agent/".$agentInfo['pic_card2']:_STATIC_."/2015/member/image/account/add_photo.png";//个人名片
		$info['is_vip'] = $agentInfo['is_vip']==2?1:0;//是否vip账户
		$info['vip_picurl'] =$gold->getMemberV($member_info['id']);
		if(!empty($info['vip_picurl']))$info['is_vip'] = 1;
	}else{
		$info['isPayGold'] = true==$gold->isPayGold($member_info['id'])?1:0;
		$info['nickname'] = "";//昵称
		$info['company_name'] = "";//公司简称
		$info['company_full_name'] = "";//公司全称
		$info['pic_card'] = "";//二维码地址
		$info['pic_card2'] = $agentInfo['pic_card2']?_STATIC_."/Upload/agent/".$agentInfo['pic_card2']:_STATIC_."/2015/member/image/account/add_photo.png";//个人名片
		$info['is_vip'] = 0;//是否vip账户
		$info['vip_picurl'] = "";
	}
	$this->data = array('member_info'=>$info);
	return true;
}
$member_info['bindinfo'] = M('member_info')->where(array('memberid'=>$member_info['id']))->find();
$this->token('member_info',$member_info,3600*24);
$this->error('TYPE_ERROR','type参数错误');	


 function isCreditNo($vStr)
{
	$vCity = array(
			'11','12','13','14','15','21','22',
			'23','31','32','33','34','35','36',
			'37','41','42','43','44','45','46',
			'50','51','52','53','54','61','62',
			'63','64','65','71','81','82','91'
	);

	if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

	if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

	$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
	$vLength = strlen($vStr);

	if ($vLength == 18)
	{
		$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
	} else {
		$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
	}

	if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
	if ($vLength == 18)
	{
		$vSum = 0;

		for ($i = 17 ; $i >= 0 ; $i--)
		{
		$vSubStr = substr($vStr, 17 - $i, 1);
		$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
		}

		if($vSum % 11 != 1) return false;
	}

	return true;
}

?>