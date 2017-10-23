<?php
/*
   个推推送个人站内消息
 */
class GetuiMessage{
  private $error = '';
  private $message = '';//推送信息
  private $summary = '';//推送信息简介
  private $title = "借吧推荐人信息";//推送标题
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
  	return $info;
  }
  /*       
   * 推荐好友成功注册推送站内消息
   * 					---给推荐人推送消息
   * 
   * $memberid:被推荐好友的用户id
   * 
   * */
  public function registerMessage($memberid){
  	 //站内消息模板
  	 $this->title = "成功推荐好友注册";
  	 $this->summary = "<name>先生/女士，您邀请的手机尾号为<mobile>的好友已成功注册借吧，您获取1积分！";
  	 $this->message = $this->summary."感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
  	 //查询用户基本信息
  	 $member_info = $this->memberInfo(array('m.id'=>$memberid));
  	 //用户有推荐人，给推荐人推送消息
  	 if(!empty($member_info['recintcode'])){
  	 	$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));//return json_encode($friend_info);
  	 	//处理推送消息内容
  	 	$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  	 	$this->message = str_replace(array('<name>','<mobile>'),array($names,substr($member_info['mobile'],-4)),$this->message);
  	 	$this->summary = str_replace(array('<name>','<mobile>'),array($names,substr($member_info['mobile'],-4)),$this->summary);
  	 	//return json_encode($friend_info);
  	 	//保存推送信息
  	 	$data = array();
  	 	$data['touserid'] = $friend_info['id'];
  	 	$data['title'] = $this->title;
  	 	$data['summary'] = $this->summary;
  	 	$data['content'] = $this->message;
  	 	$data['timeadd'] = date('Y-m-d H:i:s');
  	 	$data['status'] = '1';
  	 	$data['code'] = 'person';
  	 	$data['type'] = '2';
  	 	//return json_encode($data);
  	 	$sys_id = $this->saveSysData($data);
  	 	//return $sys_id;
  	 	//开始推送文本消息
  	 	if($sys_id){
  	 		$result = $this->sendText($friend_info['id'],$sys_id);
  	 	}
  	 	
  	 }
  	 return ($result==true?true:false);
   }
   /*
   * 成功申请车租宝|车贷宝推送站内消息
   * 					---给推荐人推送消息
   * $memberid:用户id
   * 
   * */
   public function applyOrder($memberid,$order_id){
     //站内消息模板
   	 $this->title = "成功申请<order>";
     $this->summary = "<name>先生/女士，您的好友<friend_name>（手机尾号<friend_mobile>）已成功申请<order_type>!";
  	 $this->message = $this->summary."申请金额：<money>元，城市：<city>。感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！"; 
  	 //查询用户基本信息
  	 $member_info = $this->memberInfo(array('m.id'=>$memberid));
  	 //用户有推荐人，给推荐人推送消息
  	 if(!empty($member_info['recintcode'])){
  	 	$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  	 	$order_info = M('order')->where("id='{$order_id}' and order_type in(1,2)")->find();
  	 	if(false==$order_info)return false;
  	 	//处理推送消息内容
  	 	$member_mobile = substr($member_info['mobile'],-4);
		$order_type = array('1'=>'车贷宝','2'=>'车租宝');
  		$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  		$this->message = str_replace(array('<name>','<friend_name>','<friend_mobile>','<order_type>','<money>','<city>'),array($names,$member_info['names'],$member_mobile,$order_type[$order_info['order_type']],$order_info['loanmoney'],$order_info['city']),$this->message);
  		$this->summary = str_replace(array('<name>','<friend_name>','<friend_mobile>','<order_type>'),array($names,$member_info['names'],$member_mobile,$order_type[$order_info['order_type']]),$this->summary); 	 	
		$this->title = str_replace(array("<order>"),array($order_type[$order_info['order_type']]),$this->title);
  	 	//保存推送信息
  	 	$data = array();
  	 	$data['touserid'] = $friend_info['id'];
  	 	$data['title'] = $this->title;
  	 	$data['summary'] = $this->summary;
  	 	$data['content'] = $this->message;
  	 	$data['timeadd'] = date('Y-m-d H:i:s');
  	 	$data['status'] = '1';
  	 	$data['code'] = 'person';
  	 	$data['type'] = '2';
  	 	//return json_encode($data);
  	 	$sys_id = $this->saveSysData($data);
  	 	//return $sys_id;
  	 	//开始推送文本消息
  	 	if($sys_id){
  	 		$result = $this->sendText($friend_info['id'],$sys_id);
  	 	}
  	 	
  	 	
  	 }
  	 return ($result==true?true:false);
   }
   /*
    车友贷完成评论成功后，给推荐人发信息
    $memberid:评论的用户
    $order_id:评论的订单
    */
   public function commentSuccess($memberid,$order_id){
	     //站内消息
	    $this->title = "评论订单得积分";
	  	$this->summary = "<name>先生/女士，您评论车友贷订单<order_sn>成功，恭喜您获取10积分!";
	    $this->message = $this->summary." 感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
	  	$member_info = $this->memberInfo(array('m.id'=>$memberid));
	  	$order_info = M('`order` o,order_credit c')->field("o.*,c.order_sn")->where("o.id='{$order_id}' and o.id=c.order_id and o.order_type=3 and o.status=2")->find();
	  	if(false==$order_info)return false;
	    $this->message = str_replace(array('<name>','<order_sn>'),array($member_info['names'],$order_info['order_sn']),$this->message);
	    $this->summary = str_replace(array('<name>','<order_sn>'),array($member_info['names'],$order_info['order_sn']),$this->summary);
	    //保存推送信息
		$data = array();
		$data['touserid'] = $memberid;
		$data['title'] = $this->title;
		$data['summary'] = $this->summary;
		$data['content'] = $this->message;
		$data['timeadd'] = date('Y-m-d H:i:s');
		$data['status'] = '1';
		$data['code'] = 'person';
		$data['type'] = '2';
		$sys_id = $this->saveSysData($data);
	    //开始推送文本消息
		if($sys_id){
	 		$result = $this->sendText($memberid,$sys_id);
	 	}
        return ($result==true?true:false);
  	 	
   }
   
   /*
    车友贷逾期减积分的通知信息
    $memberid :逾期用户
    */
   public function delayMessage($memberid,$sub_score){
     //站内消息
     $this->title = "车友贷逾期通知";
     $this->summary = "<name>先生/女士，您的车友贷有逾期，积分减<sub_score>分!";
     $this->message = $this->summary."感谢您对借吧的信任和支持，如有任何意见或建议，请拨打客服热线：400-663-9066！";
     $member_info = $this->memberInfo(array('m.id'=>$memberid));
     if(false==$member_info)return false;
     $this->message = str_replace(array('<name>','<sub_score>'),array($member_info['names'],$sub_score),$this->message); 
     $this->summary = str_replace(array('<name>','<sub_score>'),array($member_info['names'],$sub_score),$this->summary); 
     //保存推送信息
		$data = array();
		$data['touserid'] = $memberid;
		$data['title'] = $this->title;
		$data['summary'] = $this->summary;
		$data['content'] = $this->message;
		$data['timeadd'] = date('Y-m-d H:i:s');
		$data['status'] = '1';
		$data['code'] = 'person';
		$data['type'] = '2';
		$sys_id = $this->saveSysData($data);
      //开始推送文本消息
      if($sys_id){
	 		$result = $this->sendText($memberid,$sys_id);
	 	}
      return ($result==true?true:false);
   }
    /*车友贷-贷款成功后，给推荐人发消息
    *$memberid:申请订单的用户
    *$order_id:订单id
    * return boolen
  * */
  public function  applyCreditSuccessMessage($memberid,$order_id){
  	//站内消息
  	$this->title = "成功申请车友贷";
  	$this->summary = "<name>先生/女士，您的好友<friend_name>（手机尾号<friend_mobile>）<timeadd>申请车友贷成功!";
  	$this->message = $this->summary."<order_sn>交易成功<timeadd>!恭喜您获取1积分,感谢您对借吧的信任和支持!";
  	$member_info = $this->memberInfo(array('m.id'=>$memberid));
  	//return json_encode($member_info);
  	//用户有推荐人，给推荐人推送消息
  	if(!empty($member_info['recintcode'])){
  		$friend_info = $this->memberInfo(array('mobile'=>$member_info['recintcode']));
  		$order_info = M('`order` o,order_credit c')->field("o.*,c.order_sn")->where("o.id='{$order_id}' and o.id=c.order_id and o.order_type=3 and o.status=2")->find();
  		//return json_encode($order_info);
  		if(false==$order_info)return false;
  		$member_mobile = substr($member_info['mobile'],-4);
  		$names = $friend_info['names']?$friend_info['names']:$friend_info['mobile'];
  		$this->summary = str_replace(array('<name>','<friend_name>','<friend_mobile>','<timeadd>'),array($names,$member_info['names'],$member_mobile,date("Y年m月d日",strtotime($order_info['timeadd']))),$this->summary);
  		$this->message = str_replace(array('<name>','<friend_name>','<friend_mobile>','<timeadd>','<order_sn>'),array($names,$member_info['names'],$member_mobile,date("Y年m月d日",strtotime($order_info['timeadd'],$order_info['order_sn']))),$this->message);
  		//保存推送信息
  	 	$data = array();
  	 	$data['touserid'] = $friend_info['id'];
  	 	$data['title'] = $this->title;
  	 	$data['summary'] = $this->summary;
  	 	$data['content'] = $this->message;
  	 	$data['timeadd'] = date('Y-m-d H:i:s');
  	 	$data['status'] = '1';
  	 	$data['code'] = 'person';
  	 	$data['type'] = '2';
  	 	$sys_id = $this->saveSysData($data);
  		//开始推送文本消息
  		if($sys_id){
  	 		$result = $this->sendText($friend_info['id'],$sys_id);
  	 	}
  	 	
  	}
  	 return ($result==true?true:false);
  }
  
  
  /*
   *个推推送站内信
  * $sysData  touserid:用户id   title:标题     content:内容
  * */
  public function getTuiToUser($sysData){
  	$data = array('status'=>1,"code"=>"person","type"=>2);
  	$data['touserid'] = $sysData['touserid'];
  	$data['title'] = $sysData['title'];
  	$data['summary'] = $sysData['content'];
  	$data['content'] = $sysData['content'];
  	$sys_id = $this->saveSysData($data);
  	if($sys_id)
  		$result = $this->sendText($data['touserid'],$sys_id);
  	return true==$result?true:false;
  }

  	 /*
  	   	推送消息
  	   $memberid:推荐人的memberid（推送对象）
  	   $sys_id 个人消息储存id
  	  */
  	 public function sendText($memberid,$sys_id){
  	 	if(empty($memberid)) return false;
        import("Think.ORG.Util.Getui");
		$getui = new Getui;
		$sysInfo = M("system")->where("id='{$sys_id}'")->find();
		$json = json_encode(array('id'=>$sys_id,'content'=>$this->summary?$this->summary:$sysInfo['summary']));
		$android_res = $getui->pushMessageToSingle($memberid,$this->title?$this->title:$sysInfo['title'],$json,"android");
        $ios_res = $getui->pushMessageToSingle($memberid,$this->title?$this->title:$sysInfo['title'],$json,"ios");
		if((false == $android_res) && (false == $ios_res)){
			$this->error = json_encode($getui->getError());		
			return false;
		}
		return true;
  	 }
  	 /*
  	   保存推送消息
  	  */
  	 public function saveSysData($data){
        if(empty($data['touserid'])) return false;
        $add_id = M('system')->add($data);
        if(false == $add_id)return false;
        return $add_id;
  	 }
  	
     public function getError(){
     	return $this->error;
     }
}
?>