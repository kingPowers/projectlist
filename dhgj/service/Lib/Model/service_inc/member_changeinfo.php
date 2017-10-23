<?php
/*
 * 修改个人信息
 * 		重置密码、修改用户名、修改头像
 * */
 $this->need('type','类型');
$type = $this->v('type');
$member_info = $this->token_check_force();
//重置密码发送验证码
if ($type == "resetpwd_verify")
{
   $mobile = $member_info['mobile'];
   if ($this->token("smssend_flag") == 1)
   {
       return $this->error('SEND_TOO_OFTEN','您点的太频繁了');
   }
   if (preg_match('/^1[0-9]{10}$/', $mobile) == false)
   {
        return $this->error('MOBILE_ERROR','手机号不正确');
   }
   if (false == D('Member')->where(array("mobile"=>$mobile))->field('id')->find())
   {
		return $this->error('ACCOUNT_NOT_EXIST','该手机号尚未注册');
   }
    import('Think.ORG.Util.SMS');
    $sms_res = SMS::buildverify($mobile);
    if ($sms_res == false)
    {
        return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
    }
    $smscode = session('smscode');
    $this->token('smscode',$smscode,600);
    $this->token('smsmobile',md5($mobile.$smscode),600);
    $this->token('smssend_flag',1,6);
    $this->data = '验证码发送成功,有效期为10分钟';
    return true;
} 
//验证重置密码的信息
elseif ($type == "resetpwd")
{
  $this->need('verify_code','手机验证码');
	$this->need('pwd','密码');
	$this->need('repwd','确认密码');
	$verify_code = $this->v('verify_code'); 
	$mobile = $member_info['mobile'];
	$setpwd = $this->v('pwd');
	$resetpwd = $this->v('repwd');
	if(md5($verify_code) != $this->token('smscode') || $this->token('smsmobile') != md5($mobile.$this->token('smscode'))){
        return $this->error('VERIFY_ERROR','验证码不正确');
    }
    //清除缓存
    $this->token('smscode',null);
    $this->token('smsmobile',null);
    if (strlen($setpwd)<6)
    {
		   return $this->error('PASSWORD_ERROR','密码不能少于6个字符');
	  }
	if ($setpwd != $resetpwd)$this->error("两次输入密码不一致");
	$update = D('Member')->where("mobile='{$mobile}'")->save(array('password'=>gen_password($setpwd),"pwd_safety"=>pwd_safety($setpwd)));
	if (false === $update)
  {
		return $this->error('SYS_ERROR_50','系统错误请稍后再试');
	}
	$this->data = array("info"=>"密码重置成功","pwd_safety"=>pwd_safety($setpwd));
	return true;
}
//修改用户名
elseif ($type == "revise_username")
{
  $this->need("username","用户名");
  $username = $this->v('username');
  if (!preg_match("/^[0-9a-zA-Z\x{4e00}-\x{9fa5}]{2,8}$/u",$username))
  {
    $this->error("USER_ERR",'用户名必须由2-8位的汉字、字母、数字组成');
  }
  if(M('member')->where("username={$username}")->find())$this->error("USER_ERR",'该用户名已经存在');
  if (M("member")->where("id='{$member_info['id']}' and id!=''")->save(array('username'=>$username)))
  {
    $this->data = "用户名修改成功";
    return true;
  }
  else
  {
    $this->error("NETWORK_ERR","网络连接超时，请稍后再试");
  }
}
elseif ($type == "member_info")
{
  $info = M('member')->field("username,mobile,usertype")->where("id={$member_info['id']}")->find();
  if(false == $info)$this->error("USER_ERROR","该用户不存在");
  $this->data = array('member_info'=>$info);
  return true;
}
$this->token('member_info',$member_info,3600*24);
$this->error('TYPE_ERROR','type参数错误');
?>