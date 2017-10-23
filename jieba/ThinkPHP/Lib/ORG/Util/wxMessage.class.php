<?php
//推送微信消息
class wxMessage{
  private $appId = '';
  private $appSecret = '';
  private $sendMessageUrl = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=";
  private static $_map = array();//存储静态变量
  private $error = '';
  
  private static $accessToken = '';
  private $openId = "";//获取微信用户的openid 
  private $body = "";//推送的消息内容

  public function __construct() {
    $this->appId = WXAPPID;
    $this->appSecret = WXAPPSECRED;
  }
  //获取用户基本信息
  public function memberInfo($where = array()){
  	if(empty($where)){
  		$this->error = "用户查询条件为空";
  		return false;
  	}
  	$where['mi.memberid'] = array('exp',"=m.id");
  	$info = M()->table("member m,member_info mi")->field("m.*,mi.names")->where($where)->find();
  	if(false==$info){
  		$this->error = "用户基本信息异常";
  		return false;
  	}
  	$this->openId = $info['wx_openid'];
  	return $info;
  }
  
  /*
   * 推荐好友成功注册推送微信消息
   * 					---给推荐人推送消息
   * 
   * $memberid:被推荐好友的用户id
   * 
   * */
  public function regMessage($memberid){
  	//微信消息模板
  	 $message = "<name>先生/女士，您邀请的手机尾号为<mobile>的好友已成功注册借吧，您获取1积分！感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
  	 //查询用户基本信息
  	 $member_info = $this->memberInfo(array('m.id'=>$memberid));
  	 //用户有推荐人，给推荐人推送消息
  	 if(!empty($member_info['recintcode'])){
  	 	$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  	 	//处理推送消息内容
  	 	$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  	 	$message = str_replace(array('<name>','<mobile>'),array($names,substr($member_info['mobile'],-4)),$message);
  	 	//开始推送文本消息
  	 	$result = $this->sendText($message);
  	 	//记录微信消息日志
  	 	$log_data = array(
  	 			'memberid'=>$friend_info['id'],
  	 			'message' =>$this->body,
  	 			'result'=>$result==true?"推送成功":$this->error,
  	 	);
  	 	
  	 	$this->saveWxMessageLog($log_data);
  	 	//开始赠送积分
  	 	$mobile = substr($member_info['mobile'],0,3)."****".substr($member_info['mobile'],-4);
  	 	$sign_data = array(
  	 			'memberid'=>$friend_info['id'],
  	 			'type' =>9,
  	 			'score'=>1,
  	 			'remark'=>"推荐好友({$mobile})注册赠送",
  	 			'is_receive'=>1,
  	 	);
  	 	$this->saveSign($sign_data);
  	 }
  	 
  	 
  	 
  	 
  	 
  	 return ($result==true?true:false);
  }
  /*
   * 成功申请车租宝|车贷宝推送微信消息
   * 					---给推荐人推送消息
   * $memberid:用户id
   * 
   * */
  public function applyOrder($memberid,$order_id){
  	//微信消息模板
  	$message = "<name>先生/女士，您的好友<friend_name>（手机尾号<friend_mobile>）已成功申请<order_type>，申请金额：<money>元，城市：<city>。感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
  	//查询用户基本信息
  	$member_info = $this->memberInfo(array('m.id'=>$memberid));
  	//用户有推荐人，给推荐人推送消息
  	if(!empty($member_info['recintcode'])){
  		$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  		$order_info = M('order')->where("id='{$order_id}' and order_type in(1,2)")->find();
  		if(false==$order_info)return false;
		$member_mobile = substr($member_info['mobile'],-4);
		$order_type = array('1'=>'车贷宝','2'=>'车租宝');
  		$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  		$message = str_replace(array('<name>','<friend_name>','<friend_mobile>','<order_type>','<money>','<city>'),
  								array($names,$member_info['names'],$member_mobile,$order_type[$order_info['order_type']],$order_info['loanmoney'],$order_info['city']),$message);
  		//开始推送文本消息
  		$result = $this->sendText($message);
  		//记录微信消息日志
  		$log_data = array(
  				'memberid'=>$friend_info['id'],
  				'message' =>$this->body,
  				'result'=>$result==true?"推送成功":$this->error,
  		);
  			
  		$this->saveWxMessageLog($log_data);
  	}
  	
  	return ($result==true?true:false);
  	
  }
  /*车贷宝|车租宝贷款成功后，给推荐人发消息
   *$memberid:申请订单的用户 
   *$order_id:订单id
   * return boolen
   * */
  public function  applyOrderSuccessMessage($memberid,$order_id){
  	//微信消息模板
  	$template_first = "<name>先生/女士，您的好友<friend_name>（手机尾号<friend_mobile>）已于<timeadd>成功借款<order_type>";
  	$redirect_url = C("TMPL_PARSE_STRING._WWW_")."/member/recommlist";
  	$template_keyword1 = "<order_sn>";
  	$template_keyword2 = "交易成功";
  	$template_keyword3 = "<timeadd>";
  	$template_keyword4 = "09:00-18:00";
  	$template_remark = "   您的好友借款金额：<loanmoney>元，期限12个月，您获取5积分！感谢您对借吧的信任和支持!";
  	
  	$member_info = $this->memberInfo(array('m.id'=>$memberid));
  	//用户有推荐人，给推荐人推送消息
  	if(!empty($member_info['recintcode'])){
  		$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  		$order_info = M('`order` o,order_process p')->field("o.*,p.applyCode as order_sn")->where("o.id='{$order_id}' and o.id=p.order_id and o.order_type in(1,2) and o.status=2")->find();
  		if(false==$order_info)return false;
  		$member_mobile = substr($member_info['mobile'],-4);
  		$order_type = array('1'=>'车贷宝','2'=>'车租宝');
  		$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  		//模板替换
  		$first = str_replace(array('<name>','<friend_name>','<friend_mobile>','<timeadd>','<order_type>'),array($names,$member_info['names'],$member_mobile,date("Y-m-d",strtotime($order_info['timeadd'])),$order_type[$order_info['order_type']]),$template_first);
  		$keyword1 = str_replace('<order_sn>',$order_info['order_sn'],$template_keyword1);
  		$keyword2 =$template_keyword2;
  		$keyword3 = str_replace('<timeadd>',$order_info['timeadd'],$template_keyword3);
  		$keyword4 = $template_keyword4;
  		$remark = str_replace('<loanmoney>',$order_info['loanmoney'],$template_remark);
  		 
  		//开始推送文本消息
  		$result = $this->sendTemplate($redirect_url,$first,$keyword1,$keyword2,$keyword3,$keyword4,$remark);
  		//记录微信消息日志
  		$log_data = array(
  				'memberid'=>$friend_info['id'],
  				'message' =>$this->body,
  				'result'=>$result==true?"推送成功":$this->error,
  		);
  		$this->saveWxMessageLog($log_data);
  		//开始赠送积分
  		$mobile = substr($member_info['mobile'],0,3)."****".substr($member_info['mobile'],-4);
  		$sign_data = array(
  				'memberid'=>$friend_info['id'],
  				'type' =>10,
  				'score'=>5,
  				'remark'=>"推荐好友({$mobile})借贷({$order_info['id']})赠送",
  				'is_receive'=>1,
  		);
  		$this->saveSign($sign_data);
  		//推送站内信--个推
  		//开始推送个推消息--站内信
  		$this->getTuiToUser($friend_info['id'],"成功申请".$order_type[$order_info['order_type']],$first.$remark);
  	}
  	
  }
  
  /*车友贷-贷款成功后，给推荐人发消息
   *$memberid:申请订单的用户
  *$order_id:订单id
  * return boolen
  * */
  public function  applyCreditSuccessMessage($memberid,$order_id){
  	//微信消息模板
  	$template_first = "<name>先生/女士，您的好友<friend_name>（手机尾号<friend_mobile>）<timeadd>申请车友贷成功";
  	$redirect_url = C("TMPL_PARSE_STRING._WWW_")."/member/recommlist";
  	$template_keyword1 = "<order_sn>";
  	$template_keyword2 = "交易成功";
  	$template_keyword3 = "<timeadd>";
  	$template_keyword4 = "09:00-18:00";
  	$template_remark = "   恭喜您获取1积分,感谢您对借吧的信任和支持!";
  	 
  	$member_info = $this->memberInfo(array('m.id'=>$memberid));
  	//用户有推荐人，给推荐人推送消息
  	if(!empty($member_info['recintcode'])){
  		$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  		$order_info = M('`order` o,order_credit c')->field("o.*,c.order_sn")->where("o.id='{$order_id}' and o.id=c.order_id and o.order_type=3 and o.status=2")->find();
  		if(false==$order_info)return false;
  		$member_mobile = substr($member_info['mobile'],-4);
  		$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  		//模板替换
  		$first = str_replace(array('<name>','<friend_name>','<friend_mobile>','<timeadd>'),array($names,$member_info['names'],$member_mobile,date("Y-m-d",strtotime($order_info['timeadd']))),$template_first);
  		$keyword1 = str_replace('<order_sn>',$order_info['order_sn'],$template_keyword1);
  		$keyword2 =$template_keyword2;
  		$keyword3 = str_replace('<timeadd>',$order_info['timeadd'],$template_keyword3);
  		$keyword4 = $template_keyword4;
  		$remark = str_replace('<loanmoney>',$order_info['loanmoney'],$template_remark);
  			
  		//开始推送文本消息
  		$result = $this->sendTemplate($redirect_url,$first,$keyword1,$keyword2,$keyword3,$keyword4,$remark);
  		//记录微信消息日志
  		$log_data = array(
  				'memberid'=>$friend_info['id'],
  				'message' =>$this->body,
  				'result'=>$result==true?"推送成功":$this->error,
  		);
  		$this->saveWxMessageLog($log_data);
  		//开始赠送积分
  		$mobile = substr($member_info['mobile'],0,3)."****".substr($member_info['mobile'],-4);
  		$sign_data = array(
  				'memberid'=>$friend_info['id'],
  				'type' =>10,
  				'score'=>1,
  				'remark'=>"推荐好友({$mobile})借车友贷({$order_info['id']})赠送",
  				'is_receive'=>1,
  		);
  		$this->saveSign($sign_data);
  
  
  	}
  	 
  }
  /*
    车友贷完成评论成功后，给推荐人发信息
    $memberid:评论的用户
    $order_id:评论的订单
    */
   public function commentSuccess($memberid,$order_id){
       //微信消息
      $message = "<name>先生/女士，您评论车友贷订单<order_sn>成功，恭喜您获取10积分! 感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
      $member_info = $this->memberInfo(array('m.id'=>$memberid));
      $order_info = M('`order` o,order_credit c')->field("o.*,c.order_sn")->where("o.id='{$order_id}' and o.id=c.order_id and o.order_type=3 and o.status=2")->find();
      if(false==$order_info)return false;
      //开始赠送积分
      $data_sign = [];
      $data_sign['memberid'] = $memberid;
      $data_sign['type'] = 4;
      $data_sign['score'] = 10;
      $data_sign['remark'] = "评论订单({$order_id})得积分";
      $data_sign['timeadd'] = date('Y-m-d H:i:s');
      $data_sign['is_receive'] = 1;
      $this->saveSign($sign_data);
      $message = str_replace(array('<name>','<order_sn>'),array($member_info['names'],$order_info['order_sn']),$message); 
      //开始推送文本消息
      $result = $this->sendText($message);
      //记录微信信息
      $log_data = array(
          'memberid'=>$memberid,
          'message' =>$this->body,
          'result'=>$result==true?"推送成功":$this->error,
      );    
      $this->saveWxMessageLog($log_data);
      //开始推送个推消息--站内信
      import("Think.ORG.Util.GetuiMessage");
      $getui = new GetuiMessage;
      $getui->commentSuccess($memberid,$order_id);
      
      return ($result==true?true:false);
      
   } 
   
   /*
           车友贷申请成功减分的通知信息
   @params	$memberid :用户id
   $data:array 数据
   @return boolean
   */
   public function creditApplySuccess($memberid,$data){
	   	//微信消息
	   	$message = "<name>先生/女士，您的车友贷申请成功，积分增加<add_score>分! 感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
	   	$member_info = $this->memberInfo(array('m.id'=>$memberid));
	   	if(false==$member_info)return false;
	   	$message = str_replace(array('<name>','<add_score>'),array($member_info['names'],$data['add_score']),$message);
	   	//开始推送文本消息
	   	$result = $this->sendText($message);
	   	//记录微信信息
	   	$log_data = array(
	   			'memberid'=>$memberid,
	   			'message' =>$this->body,
	   			'result'=>$result==true?"推送成功":$this->error,
	   	);
	   	$this->saveWxMessageLog($log_data);
	   	
	   	return ($result==true?true:false);
   }
   
   
   /*
         车友贷还款成功加分的通知信息
   @params	$memberid :用户id
   $data:array 数据
   @return boolean
   */
   public function creditRepaymentSuccess($memberid,$data){
   	//微信消息
   	$message = "<name>先生/女士，您的车友贷<remark><add_score>积分! 感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
   	$member_info = $this->memberInfo(array('m.id'=>$memberid));
   	if(false==$member_info)return false;
   	$message = str_replace(array('<name>','<add_score>','remark'),array($member_info['names'],$data['add_score'],$data['remark']),$message);
   	//开始推送文本消息
   	$result = $this->sendText($message);
   	//记录微信信息
   	$log_data = array(
   			'memberid'=>$memberid,
   			'message' =>$this->body,
   			'result'=>$result==true?"推送成功":$this->error,
   	);
   	$this->saveWxMessageLog($log_data);
   	 
   	return ($result==true?true:false);
   }
   
   
   
   /*
   	 车友贷逾期减积分的通知信息
    @params	$memberid :逾期用户
    		$data:array 数据
    @return boolean	
    */
   public function creditDelayMessage($memberid,$data){
     //微信消息
     $message = "<name>先生/女士，您的车友贷有逾期，积分减<sub_score>分! 感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
     $member_info = $this->memberInfo(array('m.id'=>$memberid));
     if(false==$member_info)return false;
     $message = str_replace(array('<name>','<sub_score>'),array($member_info['names'],$data['sub_score']),$message); 
      //开始推送文本消息
      $result = $this->sendText($message);
      //记录微信信息
      $log_data = array(
          'memberid'=>$memberid,
          'message' =>$this->body,
          'result'=>$result==true?"推送成功":$this->error,
      );    
      $this->saveWxMessageLog($log_data);

      return ($result==true?true:false);
   }
  

  
  
  //发送文本消息
  public function sendText($message,$openid = ''){
  	if(false==($accessToken = $this->getAccessToken()))return false;
  	
  	$url = $this->sendMessageUrl.$accessToken;
  	$data = array(
  		'touser'=>($openid?$openid:$this->openId),
        'msgtype'=>'text',
        'text'=>array('content'=>'body'),
  	);
  	$this->body = str_replace('body',$message,json_encode($data));
  	$res = $this->curlpost($url,$this->body);
  	if($res['errcode']!=0){
  		$this->error = "文本消息失败({$res['errcode']}:{$res['errmsg']})";
  		return false;
  	}
  	return true;
  }
  
  /*发送图文消息
   *	$title:消息的标题
   *	$message:消息的内容
   *	$picUrl:图片的url
   *	$direct_url:跳转的url 
   **/
  public function sendTextPic($title,$message,$picUrl,$direct_url,$openId = ''){
  	if(false==($accessToken = $this->getAccessToken()))return false;
  	$url = $this->sendMessageUrl.$accessToken;
  	$data = array(
  			'touser'=>$openId?$openId:$this->openId,
  			'msgtype'=>'news',
  			'news'=>array('articles'=>array(0=>array('title'=>'titleData','description'=>'descriptionData','url'=>'urlData','picurl'=>'picurlData'))),
  	);
  	$this->body = str_replace(array('titleData','descriptionData','picurlData','urlData'),array($title,$message,$picUrl,$direct_url),json_encode($data));
  	
  	$res = $this->curlpost($url,$this->body);
  	if($res['errcode']!=0){
  		$this->error = "图文消息失败({$res['errcode']}:{$res['errmsg']})";
  		return false;
  	}
  	return true;
  }
  
  //发送模板消息
  public function sendTemplate($redirect_url = '',$first = '',$keyword1 = '',$keyword2 = '',$keyword3 = '',$keyword4 = '',$remark='',$openId = ''){
  		if(false==($templete = $this->getTempleteId()))return false;
  		if(false==($accessToken = $this->getAccessToken()))return false;
  		$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
  		$data = array(
  				'touser'=>$openId?$openId:$this->openId,
  				'template_id'=>$templete['template_id'],
  				'url'=>'redirect_url',
  				'data'=>array(
  						'first'=>array('value'=>'firstData','color'=>'#173177'),
  						'keyword1'=>array('value'=>'keyword1Data','color'=>'#173177'),
  						'keyword2'=>array('value'=>'keyword2Data','color'=>'#173177'),
  						'keyword3'=>array('value'=>'keyword3Data','color'=>'#173177'),
  						'keyword4'=>array('value'=>'keyword4Data','color'=>'#173177'),
  						'remark'=>array('value'=>'remarkData','color'=>'#000000'),
  				),
  		);
  		$arr_string = array('redirect_url','firstData','keyword1Data','keyword2Data','keyword3Data','keyword4Data','remarkData');
  		$arr_replace = array($redirect_url,$first,$keyword1,$keyword2,$keyword3,$keyword4,$remark);
  		$this->body = str_replace($arr_string,$arr_replace,json_encode($data));
  		$res = $this->curlpost($url,$this->body);
  		if($res['errcode']!=0){
  			$this->error = "图文消息失败({$res['errcode']}:{$res['errmsg']})";
  			return false;
  		}
  		
  		return true;
  }

  /*
   * 发送站内信给指定用户
   * 			--‘个推’推送站内信
   * @param $toMemberid 推送给的用户id 
   * 		$title:推送的标题
   * 		$content:推送的内容
   * @return boolean
   * */
  private function getTuiToUser($toMemberid,$title,$content){
  	  import("Think.ORG.Util.GetuiMessage");
      $getui = new GetuiMessage;
      return $getui->getTuiToUser(['touserid'=>$toMemberid,'title'=>$title,"content"=>$content]);
  }
  
  
  //获取AccessToken
  private function  getAccessToken(){
  	if(!empty(self::$accessToken))return self::$accessToken;
  	$url = "https://api.weixin.qq.com/cgi-bin/token?";
  	$data = array(
  		'grant_type'=>'client_credential',
    	'appid'=>$this->appId,
    	'secret'=>$this->appSecret,
  	);
  	$token_info = $this->curlpost($url,$data);
  	if(empty($token_info['access_token'])){
  		$this->error = "access_token获取失败({$token_info['errcode']}:{$token_info['errmsg']})";
  		return false;
  	}
  	self::$accessToken = $token_info['access_token'];
  	return $token_info['access_token'];
  }
  
  //获取templete_id:模板id
  private function getTempleteId($list_position = 0){
  	 if(false==($accessToken=$this->getAccessToken()))return false;
  	 $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token={$accessToken}";
  	 $templeteInfo = $this->curlpost($url);
  	 if(!empty($templeteInfo['template_list'])){
  	 	return $templeteInfo['template_list'][$list_position];
  	 }
  	 $this->error = "模板获取失败";
  	 return false;
  }
  
  //存储微信推送消息记录
  public function saveWxMessageLog($data){
  	 if(empty($data['memberid']))return false;
  	 $add_id = M('wxmessage_log')->add($data);
  	 if(false==$add_id)return false;
  	 return true;
  }
  //存储积分
  private function saveSign($data){
  	if(empty($data['memberid']))return false;
  	if(false!=M('sign')->where($data)->find())return false;
  	$add_id = M('sign')->add($data);
  	if(false==$add_id)return false;
  	return true;
  }
  
  public function getError(){
  	return $this->error;
  }
//--------------------------工具方法---------------------------------------------  
  private function curlpost($url,$data=array()){
  	$ch = curl_init();
  	curl_setopt($ch, CURLOPT_URL, $url);
  	curl_setopt($ch, CURLOPT_POST, 1);
  	// 这一句是最主要的
  	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
  	$response = curl_exec($ch);
  	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  	curl_close($ch) ;
  	$response = json_decode($response,true);
  	return $response;
  }


}