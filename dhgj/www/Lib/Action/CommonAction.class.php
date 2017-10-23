<?php
/*
 * 
 * 
 * */
class CommonAction extends Action {
	private $client = "WEB";//设备标识
  //分页
  public $page = array();
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
   public function __construct() {
      $this->page = M()->page;
      $this->page['num'] = 5;
      parent::__construct();
   }
   public function service($data){
   		$service = C('TMPL_PARSE_STRING');
   		$url = !empty($service["_SERVICE_"])?$service["_SERVICE_"]:"//daiservice.".DOMAIN_ROOT;
   		return  $this->curlpost($url,array_merge($data,$this->setParams($data['_cmd_'])));
   }
   //设置公共参数
   private function setParams($_cmd_){
    	$_client_keys_ = array("WEB"=>'123456','IOS'=>'123456','ANDROID'=>'123456','BROWSER'=>'123456','SMS'=>'SMSAPI201408');
    	empty(session('_deviceid_'))?session('_deviceid_',uniqid()):'';
   		return array(
   			'_deviceid_'=>session('_deviceid_'),//设备id
   			'_client_'=>$this->client,//设备类型，browser表示微信
   			'_sign_'=>md5("{$this->client}|{$_client_keys_[$this->client]}|{$_cmd_}"),//签名
   			'_token_'=>$this->getToken(),
   			'_formurl_'=>!empty($service["_SERVICE_"])?$service["_SERVICE_"]:"//daiservice.".DOMAIN_ROOT,
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
   public function getCode(){//return '';
   		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) return '';
   	 	$appid = C('AppID');
   	 	$return_url = urlencode("http://".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI']);
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
	   	 	return S("token_{$_SESSION['token']}_member_info");
	   	 }
   	 	return false;
   }
   
    public function page_limit() {
        $limitstr = ($this->M->page['no'] - 1) * $this->M->page['num'] . ',' . $this->M->page['num'];
        return $limitstr;
    }

    public function set_pages($pageurl, $new = "") {
        $this->assign('page', $this->page);//dump($this->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page1');
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
        $pageseo = array('title' => '借吧', 'keywords' => '借吧', 'description' => $system['description']);
        if (!empty($config)) {
            $pageseo = array_merge($pageseo ,$config);
        }
        $this->assign('pageseo', $pageseo);
    }


    public function curlpost($url,$array){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//dump($array);exit;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt($ch, CURLOPT_TIMEOUT,20);   //只需要设置一个秒的数量就可以
        $data = curl_exec($ch);//dump($data);
        curl_close($ch);if($_REQUEST['common'])dump($data);//exit;
        $data = json_decode($data,true);//exit;
        return $data;
    }
    protected function ajaxSuccess($message='',$data=''){
      $this->ajaxReturn($data,$message,1);
    }
    
    protected function ajaxError($message='',$data=''){
      $this->ajaxReturn($data,$message,0);
    }
    public function set_session($name,$destory='') {
       if(!empty($destory)){unset($_SESSION[$name]);return true;}
       if(!isset($_SESSION[$name]))$_SESSION[$name] = md5(microtime(true)); 
       return $_SESSION[$name];
    }
    public function valid_session($name) { 
        if(!isset($_SESSION[$name]))return false;
        $return = $_REQUEST[$name] === $_SESSION[$name] ? true : false; 
        return $return; 
    } 
    
}
