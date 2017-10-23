
<?php
class Getui{
    private $cid = '';//个人cid
	private $url = '';//消息跳转的url
	private $logo = 'http://static.jieba360.com/2015/member/image/login/jiebalogo.png';//推送消息的logo
    private $error = '';
    public $result = '';
    
    private $client_arr = array('android','ios');//设备
    private $android_url = 'http://wx1.lingqianzaixian.com';//安卓跳转的url
    private $ios_url = 'http://wx1.lingqianzaixian.com';//IOS跳转的url
    
    
    public function __CONSTRUCT() {
        vendor('getui.getui_config');
    }
    
    
    /*
     * 群推通知，安装APP的客户均可以收到
     * 	@parm   $title  通知标题
     *  @parm	$content通知内容
     *  @param	$client	通知设备（android|ios）
     *  @param  $logo	通知logo
     * */
    public function  pushMessageToApp($title,$content,$client,$logo=''){
    	$client = strtolower($client);
    	if(!in_array($client,$this->client_arr)){
    		$this->error = "client参数错误【android|ios】";
    		return false;
    	}
    	//IOS发送方式
    	if(strtolower($client)=='ios'){
    		return $this->pushAPNL($title,$content);
    	}
    	
    	
    	$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
    	$template = $this->IGtTransmissionTemplate($title,$content,$client,$this->$client."_url",$logo?$logo:$this->logo);
    	$message = new IGtAppMessage();
    	$message->set_isOffline(false);
    	$message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
    	$message->set_data($template);
    	
    	$appIdList=array(APPID);//$client = 'ios';
    	$phoneTypeList=array(strtoupper($client));
    	$cdt = new AppConditions();
    	$cdt->addCondition(AppConditions::PHONE_TYPE, $phoneTypeList);
    	
    	$message->set_appIdList($appIdList);
    	$message->set_conditions($cdt->getCondition());
    	$rep = $igt->pushMessageToApp($message,"");
    	$this->result = $rep;
    	if($rep['result']=='ok')return true;
    	$this->error = $rep['result'];
    	return false;
    }
    /*
     * 发送单个通知--推送通知给某个用户
     * 
     * */
    public function pushMessageToSingle($memberid,$title,$content,$client,$logo = ''){
    	$client = strtolower($client);
    	if(!in_array($client,$this->client_arr)){
    		$this->error = "client参数错误【android|ios】";
    		return false;
    	}
    	if(empty($title) || empty($content)){
    		$this->error = "个推标题或内容不能为空";
    		return false;
    	}
    	//IOS发送方式
    	if(strtolower($client)=='ios'){
    		return $this->pushAPNL($title,$content,$memberid);
    	}
    	
    	$igt = new IGeTui(NULL,APPKEY,MASTERSECRET,false);
    	//$template = $this->IGtLinkTemplate($title,$content,$client,$this->$client."_url",$logo?$logo:$this->logo);//链接模板
    	$template = $this->IGtTransmissionTemplate($title,$content,$client,$this->$client."_url",$logo?$logo:$this->logo);//透传模板
    	
    	$message = new IGtSingleMessage();
    	
    	$message->set_isOffline(true);//是否离线
    	$message->set_offlineExpireTime(3600*12*1000);//离线时间
    	$message->set_data($template);//设置推送消息类型
    	//$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，1为wifi推送，0为不限制推送
    	//接收方
    	$target = new IGtTarget();
    	$target->set_appId(APPID);
    	$target->set_clientId($this->getCid($memberid,$client));
    	//$target->set_alias(Alias);
    	$rep = $igt->pushMessageToSingle($message, $target);
    	$this->result = $rep;
    	if($rep['result']=='ok')return true;
    	$this->error = $rep['result'];
    	return false;
    	 
    }
    
    
    /*
     * 获取个人用户的CID（设备id）
     * @parm $memberid 用户id  
     * @parm $cid	   设备id 
     * @parm $client  设备类型 android|ios
     * @return String  设备id
     * */
    public function getCid($memberid,$client,$cid = ''){
    	if($cid!='')return $cid;
    	$device_arr = unserialize(M('member_info')->where("memberid='{$memberid}'")->getField('getui_cid'));
    	return $device_arr[strtolower($client)];
    }
    
    public function getError(){
    	return array('error'=>$this->error,'result'=>$this->result);
    }
    
    /*
     * 透传链接模板
    * 	 @parm $title:通知标题
    *   @parm $content:通知内容
    *   @parm $url:通知打开的链接地址
    *   @parm $logo:通知logo
    * */
    private function  IGtTransmissionTemplate($title,$content,$client,$url='',$logo = ''){
    	$template =  new IGtTransmissionTemplate();
    	$template->set_appId(APPID);//应用appid
    	$template->set_appkey(APPKEY);//应用appkey
    	$template->set_transmissionType(1);//透传消息类型
    	$template->set_transmissionContent($content);//透传内容
    	
    	//APN高级推送
    	$apn = new IGtAPNPayload();
    	$alertmsg=new DictionaryAlertMsg();
    	$alertmsg->body="body";
    	$alertmsg->actionLocKey="ActionLockey";
    	$alertmsg->locKey=$content;
    	$alertmsg->locArgs=array("locargs");
    	$alertmsg->launchImage="launchimage";
    	//        IOS8.2 支持
    	$alertmsg->title=$title;
    	$alertmsg->titleLocKey=$title;
    	$alertmsg->titleLocArgs=array("TitleLocArg");
    	
    	$apn->alertMsg=$alertmsg;
    	$apn->badge=7;
    	$apn->sound="";
    	//$apn->add_customMsg("payload","payload");
    	//$apn->contentAvailable=1;
    	$apn->category="ACTIONABLE";
    	$template->set_apnInfo($apn);
    	
    	//PushApn老方式传参
    	//    $template = new IGtAPNTemplate();
    	//          $template->set_pushInfo("", 10, "", "com.gexin.ios.silence", "", "", "", "");
    	
    	return $template;
    }
    
    /*
     * 通知链接模板
     * 	 @parm $title:通知标题
     *   @parm $content:通知内容
     *   @parm $url:通知打开的链接地址
     *   @parm $logo:通知logo
     * */
 private function IGtLinkTemplate($title,$content,$client,$url='',$logo = ''){
    $contents = json_decode($content,true);
    $content = $contents['content'];
    $template =  new IGtLinkTemplate();
    $template ->set_appId(APPID);//应用appid
    $template ->set_appkey(APPKEY);//应用appkey
    $template ->set_title($title);//通知栏标题
    $template ->set_text($content);//通知栏内容
    $template ->set_logo($logo);//通知栏logo
    $template ->set_logoURL($logo);//通知栏logo
    $template ->set_isRing(true);//是否响铃
    $template ->set_isVibrate(true);//是否震动
    $template ->set_isClearable(true);//通知栏是否可清除
    $template ->set_url($url);//打开连接地址
    //$template->set_duration(BEGINTIME,ENDTIME); //设置ANDROID客户端在此时间区间内展示消息
    //iOS推送需要设置的pushInfo字段
       $apn = new IGtAPNPayload();
       //$apn->alertMsg = "alertMsg";
       $apn->badge = 11;
       $apn->actionLocKey = "ActionLockey";
       $apn->category = "ACTIONABLE";
       $apn->locKey = $title;
       $apn->title = $title;
       $apn->titleLocArgs = array("titleLocArgs");
       $apn->titleLocKey = $title;
       $apn->body = "body";
       //$apn->customMsg = array("payload"=>"payload");
       $apn->contentAvailable = 1;
       $apn->launchImage = "launchImage";
       $apn->locArgs = array("locArgs");
      // $apn->sound=("test1.wav");;
       $template->set_apnInfo($apn);
       //$template->set_notifyStyle(1);//通知栏消息布局样式
    return $template;
}



/*
 * IOS 专用  apns离线 
 *   @param $title:标题
 * 
 * */
function pushAPNL($title,$content,$memberid=''){
	$number = 20;
    $contents = json_decode($content,true);
	$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
	$template = new IGtAPNTemplate();
	$apn = new IGtAPNPayload();
	        $alertmsg=new DictionaryAlertMsg();
	        $alertmsg->body=$contents['content'];
	        $alertmsg->actionLocKey="ActionLockey";
	        $alertmsg->locKey=$contents['content'];
	        $alertmsg->locArgs=array($content);
	        $alertmsg->launchImage="launchimage";
			//IOS8.2 支持
	        $alertmsg->title=$title;
	        $alertmsg->titleLocKey=$title;
	        $alertmsg->titleLocArgs=array("TitleLocArg");
	        $apn->alertMsg=$alertmsg;
	$template->set_apnInfo($apn);
	$message = new IGtSingleMessage();
	//多个用户推送接口
	putenv("needDetails=true");
	$listmessage = new IGtListMessage();
	$listmessage->set_data($template);
	$contentId = $igt->getAPNContentId(APPID, $listmessage);
	
	$deviceTokenList = array();
	if(empty($memberid)){//群发
		$count = M('getui_device')->count();
		for($i=0;$i<ceil($count/$number);$i++){
			$list = M('getui_device')->limit($i*$number.",{$number}")->select();
			foreach($list as $v)$deviceTokenList[] = $v['devicetoken'];
		}
	}else{//指定用户发送
		$deviceTokenList[] = $this->getCid($memberid,'ios');
	}
	
	if(empty($deviceTokenList)){
		$this->error = "devicetoken为空";
		return false;
	}
	$rep = $igt->pushAPNMessageToList(APPID, $contentId, $deviceTokenList);
	$this->result = $rep;
    	if($rep['result']=='ok')return true;
    	$this->error = $rep['result'];
    	return false;
}


}
