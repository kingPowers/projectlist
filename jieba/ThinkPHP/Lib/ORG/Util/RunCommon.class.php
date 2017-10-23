<?php 
/*
 * 执行本目录中公共的类
 * 
 * */
class RunCommon{
	private $error = "";
	/*
	 * 扩展方法，会员注册成功后调用此方法
	 * @param $params: 执行方法所用的参数
	 * */
	public static function runRegister($params = []){
		try{
			/*
			 * 注册成为环信用户
			 * 
			 * */
			$chat = new Chat();
			$chatResult = $chat->accreditRegister($params['mobile'],'123456',1);//创建用户
			/*
			 * 微信推送，注册成功后给推荐人推送微信消息
			 * */
			$msg = new wxMessage;
			$regResult = $msg->regMessage($params['memberid']);
			
			/*
			 * 站内信息，注册成功后给推荐人推送站内消息
			 * */
			$getui = new GetuiMessage();
			$getuiResult = $getui->registerMessage($params['memberid']);
			/*
			 * 注册送推荐人车友贷额度券
			* */
			$credit = new Activity;
			$creditResult = $credit->addCreditTicket($params['memberid']);
			
           } catch (Exception $e){
               return false;   
         }
	}
	
	/*
	 * 成功【申请】贷款车友贷， 会调用此方法
	* */
	public function runApplyCreditSuccess($params){
		try{
			/*
			 * 车友贷加额券的使用
			 * 
			 * */
			$credit = new Activity;
			$credit->saveCreditTicketToApply($params['memberid'],$params['order_id']);
			
		}catch (Exception $e){
			
		}
	}
	
	/*
	 * 贷款订单（车贷宝|车租宝|车友贷）【拒单】， 会调用此公共方法
	* */
	public function runApplyOrderRefuse($params){
		try{
			/*
			 * 车友贷加额券恢复初始状态
			*
			* */
			if($params['order_type'] && $params['order_type']==3){
				$credit = new Activity;
				$credit->saveCreditTicketToBegin($params['memberid'],$params['order_id']);
			}
				
		}catch (Exception $e){
				
		}
	}
	
	/*
	 * 成功【贷款】车友贷， 会调用此方法
	 * */
	public static function runCreditSuccess($params){
		try{
			//车友贷成功贷款发送微信消息
			$msg = new wxMessage;
			$msg->applyCreditSuccessMessage($params['memberid'],$params['order_id']);
			//车友贷成功贷款发送站内消息
			$getui = new GetuiMessage;
			$getui->applyCreditSuccessMessage($params['memberid'],$params['order_id']);
			//车友贷贷款成功，标记额度券为已使用
			$credit = new Activity;
			$creditResult = $credit->saveCreditTicketToSuccess($params['memberid'],$params['order_id']);
		
		}catch (Exception $e){
			
		}
	}
	
	//自动加载
	public static function autoload($className){
		import("Think.ORG.Util.{$className}");
	}
	
}
spl_autoload_register(array('runCommon','autoload'));
?>