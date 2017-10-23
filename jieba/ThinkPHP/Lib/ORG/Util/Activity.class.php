<?php
/*
 * 活动类
 * 
 * */ 
class Activity{
	private $memberid;
	private $recinted_memberid;
	private $error = "";
	private $_static_ = "";
	function __construct(){
		$this->_static_ = defined(_STATIC_)?_STATIC_:"http://static.jieba360.com";
		//批量更新额度券状态
		$this->updateCreditTicketOvers();
		
	}
	
	
	/*
	 * 新增车友贷额度增加券
	 * 	@param $memberid:被推荐人用户id
	 *  @return int|boolean
	 * */
	public function addCreditTicket($memberid){
		if(empty($memberid)){$this->error = "memberid参数不能为空";return false;}
		$memberInfo = $this->getMemberInfo($memberid);
		if(empty($memberInfo['recintcode'])){$this->error = "推荐关系为空";return false;}
		if(false==($recintInfo = M('member')->where("mobile='{$memberInfo['recintcode']}'")->find())){$this->error = "推荐关系不正确";return false;}
		$setting = $this->creditTicketSetting();
		$addData = [
			'memberid'=>$recintInfo['id'],
			'recinted_memberid'=>$memberid,
			'valid_days'=>$setting['valid_days'],
			'up_percent'=>$setting['up_percent'],
			'over_time'=>date("Y-m-d H:i:s",strtotime("+{$setting['valid_days']} days")),
		];
		if($add_id = M('credit_ticket')->add($addData)){
			$message = "恭喜您获取提高车友贷额度券一张。您成功推荐***".substr($memberInfo['mobile'],-4)."注册借吧,赠送7天有效期的车友贷额度券，快去申请车友贷吧！";
			$this->sendCreditTicket($recintInfo['id'],"推荐注册送车友贷提高额度券",$message);
			return $add_id;
		}
		$this->error = "额度券新增失败，请稍后再试！";
		return false;
	}
	
	/*
	 * 更新车友贷到初始状态
	 * 			--车友贷拒单，券可以再次使用
	 * @param $memberid:用户id
	 * @param $order_id:车友贷订单id
	 * @return boolean
	 * */
	public function saveCreditTicketToBegin($memberid,$order_id){
		if($save_id = M('credit_ticket')->where(["memberid"=>$memberid,"order_id"=>$order_id,"status"=>1])->save(['status'=>0,"remark"=>"拒单，加额券恢复初始",'success_time'=>"","apply_time"=>""]))return true;
		$this->error = "额度券更新成功失败，请稍后再试";
		return false;
	}
	/*
	 * 更新券为--已使用
	 * @param $memberid:用户id
	 * @param $order_id:车友贷订单id
	 * @return boolean
	 * */
	public function saveCreditTicketToSuccess($memberid,$order_id){
		if($save_id = M('credit_ticket')->where(["memberid"=>$memberid,"order_id"=>$order_id])->save(['status'=>2,'success_time'=>date("Y-m-d H:i:s")]))return true;
		$this->error = "额度券更新成功失败，请稍后再试";
		return false;
	}
	/*
	 * 更新券为--使用中
	* @param $memberid:用户id
	* @param $order_id:订单id
	* @return boolean
	* */
	public function saveCreditTicketToApply($memberid,$order_id){
		//大转盘券
		if(false!=($turnInfo = $this->turnTableTicket($memberid))){
			M("activity_turntable")->where("id='{$turnInfo['id']}'")->save(['status'=>1,'order_id'=>$order_id]);
		}
		
		if(false==($info = $this->isHaveCreditTicket($memberid)) || $info['status']!=0){
			$this->error = $info['status']==0?"额度券已被使用":"额度券不存在";
			return false;
		}
		if($save_id = M('credit_ticket')->where(["id"=>$info['id']])->save(['status'=>1,'order_id'=>$order_id,'apply_time'=>date("Y-m-d H:i:s")]))return true;
		$this->error = "额度券更新使用中失败，请稍后再试";
		return false;
	}
	/*用户是否有券
	 * @param $memberid:用户id
	 * @return boolean|array
	 * */
	public function isHaveCreditTicket($memberid){
		return M('credit_ticket')->where(['memberid'=>$memberid,'status'=>0])->order("timeadd asc")->find();
	}
	
	//获取车友贷券
	public function getCreditTicketUrl($memberid){
		$shareTicket = $this->isHaveCreditTicket($memberid);
		$turnTicket = $this->turnTableTicket($memberid);
		if(false!=$turnTicket && $turnTicket['up_percent']>20){
			return $this->_static_."/2015/activity/image/activity41/activityturntable_{$turnTicket['type']}.png"; 
		}elseif(false!=$shareTicket){
			return $this->_static_."/2015/activity/image/activity41/ticket_20.png";
		}
		return "";
	}
	
	//批量更新过期券
	public function updateCreditTicketOvers(){
		$save_id = M()->query("update credit_ticket set status=3 where over_time<'".date("Y-m-d H:i:s")."' and status=0");
		return $save_id;
	}
	
	
	public function creditTicketSetting(){
		return [
			'up_percent'=>'20',//提升车友贷额度，单位：%
			'valid_days'=>'7',//券的有效期， 单位：天
		];
	}
	
	/*
	 * 新增车友贷额度增加券，推送消息
	 * 
	 * */
	public function sendCreditTicket($memberid,$title,$message){
		//推送微信消息
		import("Think.ORG.Util.wxMessage");
		$msg = new wxMessage;
		$info = $msg->memberInfo(['id'=>$memberid]);
		$msg->sendText($message);
		//个推站内消息
		import("Think.ORG.Util.GetuiMessage");
		$getui = new GetuiMessage;
		$data = array();
		$data['touserid'] = $memberid;
		$data['title'] = $title;
		$data['summary'] = $message;
		$data['content'] = $message;
		$data['status'] = '1';
		$data['code'] = 'person';
		$data['type'] = '2';
		if($sys_id = $getui->saveSysData($data)){
			$getui->sendText($memberid,$sys_id);
		}
		
	}
	
	public function getMemberInfo($memberid){
		return M('member_info mi,member m')->where("mi.memberid=m.id and memberid='{$memberid}'")->find();
	}
	
	public function getError(){
		return $this->error;
	}
	
	//大转盘活动
	public function turnTableTicket($memberid){
		return M('activity_turntable')->where("memberid='{$memberid}' and over_time>=NOW() and status=0 and up_percent>0")->order("up_percent desc")->find();
	}
}
?>