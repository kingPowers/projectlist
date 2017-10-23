<?php
/**
 * 
 * 大转盘活动
 * 
 * 
 * */
class ActivityturntableAction extends  CommonAction {
	private $rule =  array(
			'1'=>array('pro'=>10,"prizeName"=>"提额10%","ticket"=>"10"),//提额10%，占据10%概率
			'2'=>array('pro'=>20,"prizeName"=>"提额20%","ticket"=>"20"),//提额20%，占据20%概率
			'3'=>array('pro'=>40,"prizeName"=>"提额30%","ticket"=>"30"),//提额30%，占据40%概率
			'4'=>array('pro'=>15,"prizeName"=>"提额50%","ticket"=>"50"),//提额50%，占据15%概率
			'5'=>array('pro'=>5,"prizeName"=>"额度翻倍","ticket"=>"100"),//额度翻倍，占据5%概率
			'6'=>array('pro'=>10,"prizeName"=>"5积分","ticket"=>"0"),//送5积分，占据10%概率
			//'7'=>array('pro'=>2,"prizeName"=>""),//送10积分，占据2%概率
		);
	public function _initialize(){
		if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
			$_SESSION['token'] = $_REQUEST['token'];
		}
		if(false==($member_info = $this->getMemberInfo()) && ACTION_NAME!='beginturn')redirect("/member/login?redirecturl=".urlencode("/ActivityTurnTable/".ACTION_NAME));
		$this->updateToOver();
	}
	//概率测试
	function testPro(){
		$s['rule'][1] = 0;
		$s['rule'][2] = 0;
		$s['rule'][3] = 0;
		$s['rule'][4] = 0;
		$s['rule'][5] = 0;
		$s['rule'][6] = 0;
		for($i=0;$i<1000;$i++){
			$rand = intval($this->getPrize());
			$s['rule'][$rand]=$s['rule'][$rand]+1;
				
		}
		dump("i:{$i}");
		dump("1:{$s['rule'][1]}");
		dump("2:{$s['rule'][2]}");
		dump("3:{$s['rule'][3]}");
		dump("4:{$s['rule'][4]}");
		dump("5:{$s['rule'][5]}");
		dump("6:{$s['rule'][6]}");
		
	}
	public function index(){
		$member_info = $this->getMemberInfo();
		$num = $this->getPrizeNum($member_info['id']);
		$centerName = $num>=2?"5积分抽奖<br/>上限2次":"免费抽奖<br/><font>每天2次</font>";
		$this->assign("centerName",$centerName);
		$this->display();
	}
	
	public function beginTurn(){
		if(false==($member_info = $this->getMemberInfo())){
			$this->error("对不起，您的登录已超时，请重新登录！");
		}
		if(time()<strtotime("2017-03-17")){
			$this->error("对不起，活动未开始！");
		}
		if(time()>strtotime("2017-04-29")){
			$this->error("对不起，活动已结束！");
		}
		
		$num = $this->getPrizeNum($member_info['id']);
		if($num>=4){
			$this->error("您今日次数用完了，明日再来吧！");
		}
		$centerName = $num>=1?"5积分抽奖<br/>上限2次":"免费抽奖<br/><font>每天2次</font>";
						
		$prize = $this->getPrize();
		//两次免费机会
		if($num>=2){
			$score = $this->getSign($member_info['id']);
			if($score<5)$this->error("积分不足");
			$signData = [
				"memberid"=>$member_info['id'],
				'type'=>12,
				"score"=>-5,
				'remark'=>'放财周幸运大转盘扣除',
				'is_receive'=>1,
			];
			M('sign')->add($signData);
		}
		$actData = array(
				'memberid'=>$member_info['id'],
				'type'=>$prize,
				'prize'=>$this->rule[$prize]['prizeName'],
				'up_percent'=>$this->rule[$prize]['ticket'],
				'over_time'=>date("Y-m-d H:i:s",strtotime("+7 days")),
		);
		$isPrize = M('activity_turntable')->add($actData);
		if(false==$isPrize){
			$this->error("换个姿势，再转一次！");
		}
		if($prize==6){
			$signData = [
			"memberid"=>$member_info['id'],
			'type'=>12,
			"score"=>5,
			'remark'=>'放财周幸运大转盘赠送',
			'is_receive'=>1,
		];
			M('sign')->add($signData);
		}
		$this->ajaxReturn(["prize"=>intval($prize),"status"=>1,'prizeName'=>"恭喜您获得{$this->rule[$prize]['prizeName']}的奖励","centerName"=>$centerName]);
		
	}
	//获取奖品
	private function getPrize(){
		$str = $this->strProb();
		$rand = rand(1,100);
		return substr($str,$rand,1);
	}
	
	//今日抽奖数量
	private function getPrizeNum($memberid){
		$date = date("Y-m-d");
		return intval(M('activity_turntable')->where("memberid='{$memberid}' and timeadd>='{$date}' and timeadd<='{$date} 23:59:59'")->count());
	}
	//我的积分和
	private function getSign($memberid){
		return M('sign')->where("memberid='{$memberid}'")->sum('score');
	}
	//券失效
	private function updateToOver(){
		M("activity_turntable")->where("over_time<NOW() and prize like '%提额%'")->save(['status'=>2]);
	}
	//概率字符串
	private function strProb(){
		$str = "";
		foreach($this->rule as $k=>$v)
			$str.= str_repeat($k,$v['pro']);
		return str_shuffle(str_shuffle($str));
	}
}