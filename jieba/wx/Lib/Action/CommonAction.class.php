<?php

/*
 * 智信创富金融
 */

/**
 * Description of CommonAction
 * 继承基类
 * @author Nydia
 */
class CommonAction extends Action {

    public $M;
	  public $currentprotocol = 'http://';
	  public $httphost;
    public $online = true;

    public function __CONSTRUCT() {
        parent::__CONSTRUCT();
        $this->M = D();
        $this->set_seo();//设置网页标题、简介
        $ip = get_client_ip();
        //$address = $this->getIP();
        //$ban_model = array("member");
      
//         if(!in_array($address, array('101.81.33.132')))
//         {
//            if(in_array(strtolower(MODULE_NAME),$ban_model))
//             {
//               exit("<div style='color:red;font-size:50px;margin:50px 100px;'>该功能正在维护中，请稍后访问！</div>");
//             }
//         }
		
        
//         if(in_array($ip, array('116.226.1.86'))){
//             $headfoot = true;
//         }else{
//             $headfoot = false;
//         }
        $headfoot = true;
        $this->assign('headfoot',$headfoot);
        $this->assign('domainroot',DOMAIN_ROOT);
        $this->redis = new Redis();
        $this->redis->connect(REDIS_SERVER, REDIS_PORT);
    }
    public function getIP()
    { 
      if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
          $ip = getenv("HTTP_CLIENT_IP"); 
      else 
          if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
              $ip = getenv("HTTP_X_FORWARDED_FOR"); 
          else 
              if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
                  $ip = getenv("REMOTE_ADDR"); 
              else 
                  if (isset ($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
                      $ip = $_SERVER['REMOTE_ADDR']; 
                  else 
                      $ip = "unknown"; 
      return ($ip); 
    }
    /*
     * 定义调用的接口
     *  $param  $data['_cmd_'] 方法名,必填
     *          ---
     *          1.接口的其他参数，也是必填的 
     *          2.接口的公共参数可以不填
     *             
     *  example:调用登录接口
     * 		$this->service(array('_cmd_'=>'member_login','mobile'=>'13600000000','password'=>'123456'));
     * 
     * */
   public function service($data){
   		$service = C('TMPL_PARSE_STRING');
   		$url = !empty($service["_SERVICE_"])?$service["_SERVICE_"]:"//service.".DOMAIN_ROOT;
   		return  $this->curlpost($url,array_merge($data,$this->setParams($data['_cmd_'])));
   }
   
   private function setParams($_cmd_){
    	$_client_keys_ = array('IOS'=>'123456','ANDROID'=>'123456','BROWSER'=>'123456','SMS'=>'SMSAPI201408');
    	empty(session('_deviceid_'))?session('_deviceid_',uniqid()):'';
   		return array(
   			'_deviceid_'=>session('_deviceid_'),//设备id
   			'_client_'=>'BROWSER',//设备类型，browser表示微信
   			'_sign_'=>md5("BROWSER|{$_client_keys_['BROWSER']}|{$_cmd_}"),//签名
   			'_token_'=>$this->getToken(),
   		);
   }
   
   public function getToken(){
   		return isset($_SESSION['token']) && !empty(S("token_{$_SESSION['token']}_member_info"))?$_SESSION['token']:'';
   }
   
   /*
    * 网页获取微信code
    * 	
    * 
    * */
   public function getCode(){
   		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) return '';
   	 	$appid = C('AppID');
   	 	$return_url = urlencode("http://wx.jieba360.com/".$_SERVER['REQUEST_URI']);
   	 	header("Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$return_url}&response_type=code&scope=snsapi_base&state=wx&connect_redirect=1#wechat_redirect");
   }   
   /*
    * 网页获取微信openid
    *   example:
    *      if(empty($_GET['code']))
    		$this->getCode();
    	$openid = $this->getOpenid($_GET['code']);
    *
    * */
 	public function getOpenid($code){
 		 if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false || empty($code))return '';
         $appid = C('AppID');$secret = C('AppSecret');
         $res = $this->curlpost("https://api.weixin.qq.com/sns/oauth2/access_token?appid={$appid}&secret={$secret}&code={$code}&grant_type=authorization_code",array());
         return $res['openid'];
         
   }
   //用户是否登录
   public function isLogin(){
   	 return $_SESSION['token'] && S("token_{$_SESSION['token']}_member_info");
   }
   //获取用户的详细信息
   public function getMemberInfo(){
	   if($this->isLogin()){
	   	 	$m = S("token_{$_SESSION['token']}_member_info");
	   	 	return M()->table("member m,member_info mi")->field("m.*,mi.memberid,mi.avatar,mi.qrcode,mi.sex,mi.certificate,mi.certiNumber,mi.nameStatus,mi.names,mi.emailStatus,mi.nameStatus,mi.fuyou_login_id")->where("m.id=mi.memberid and m.id='{$m['id']}'")->find();
	   	 }
   	 	return false;
   }
   
    public function page_limit() {
        $limitstr = ($this->M->page['no'] - 1) * $this->M->page['num'] . ',' . $this->M->page['num'];
        return $limitstr;
    }

    public function set_pages($pageurl, $new = "") {
        $this->assign('page_vars', $this->M->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page');
        $this->assign('page', $pagehtml);
    }
    
    //设置分页
    public function set_SimplePages($pageurl, $new = "") {
        $this->assign('page_vars', $this->M->simple_page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:simple_page');
        $this->assign('page', $pagehtml);
    }

    //设置页面SEO
    public function set_seo($config = array()) {
        //$system = M('system')->cache(true)->find();
        $pageseo = array('title' => '借吧', 'keywords' => '借吧', 'description' => $system['description']);
        if (!empty($config)) {
            $pageseo = array_merge($pageseo ,$config);
        }
        $this->assign('pageseo', $pageseo);
    }

    public function boxpost() {
        $data = $this->_request('data');
        if (empty($data)) {
            return null;
        }
        $postring = explode('&amp;', $data);
        $post = array();
        foreach ($postring as $po) {
            $poitem = explode('=', $po);
            if ($poitem[0] == 'r')
                continue;
            $post[$poitem[0]] = $poitem[1];
        }
        return $post;
    }

    public function ajaxcheck($name, $content = NULL) {
        $ajaxcheck = session('ajaxcheck');
        if (!$content) {
            return $ajaxcheck[$name];
        } else {
            $ajaxcheck[$name] = $content;
            session('ajaxcheck', $ajaxcheck);
        }
    }

    public function curlpost($url,$array){
    	
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST,true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        //curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt($ch, CURLOPT_TIMEOUT,120);   //只需要设置一个秒的数量就可以
        $res = $data = curl_exec($ch);
        
        $curl["code"] = curl_errno($ch);
        $curl["curl_info"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        $data = json_decode(trim($data,chr(239).chr(187).chr(191)),true);
       
        //M("wxmessage_log")->add(["message"=>json_encode(['data'=>$data,"res"=>$res,"curl"=>$curl,"param"=>$array,"url"=>$url,"server"=>$_SERVER])]);
        if(false==$data){
        	$data = array("errorcode"=>"SYS_ERR","errormsg"=>"网络连接失败！");
        }
        return $data;
    }

    
    //空方法
    protected function _empty() {
        R('Empty/_empty');
    }
    
    protected function ajaxSuccess($message='',$data=''){
    	$this->ajaxReturn($data,$message,1);
    }
    
    protected function ajaxError($message='',$data=''){
    	$this->ajaxReturn($data,$message,0);
    }

    
}
