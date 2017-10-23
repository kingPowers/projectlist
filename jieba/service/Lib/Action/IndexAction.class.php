<?php
class IndexAction extends CommonAction {
	public $logger;
	private $_version_currect_;
	private $_client_keys_ = array('IOS'=>'123456','ANDROID'=>'123456','BROWSER'=>'123456','SMS'=>'SMSAPI201408');
	public $_deviceid_;
	public $_client_;
	public $_sign_;
	public $_token_;
	public $_cmd_;
	public $_wx_openid_ = '';
	private $_wx_trans_key_ = '_342sf923wes#';

	public $params;

	public function __construct(){
		parent::__CONSTRUCT();
		$this->_version_currect_ = array(
			'v'=>C('APP_LAST_VERSION'),
			'update_force'=>C('APP_UPDATE_FORCE'),
			'update_desc'=>C('APP_UPDATEDESC'),
			'v_ios'	=> C('APP_LAST_VERSION_IOS'),   //IOS最新版本
			'update_force_ios'	=> C('APP_UPDATE_FORCE_IOS'),	//IOS是否强制更新
			'update_desc_ios'	=> C('APP_UPDATEDESC_IOS') //IOS更新提示
		);
	}
public function index(){
		$this->logger = new ServiceLogger("app");
		//系统参数
		$this->params = $_REQUEST;
		$this->params['client_ip'] = get_client_ip();
		$this->_cmd_ = $this->a('_cmd_');
		$this->_deviceid_ = $this->a('_deviceid_');
		$this->_client_ = $this->a('_client_');
		$this->_sign_ = $this->a('_sign_');
		$this->_token_ = $this->a('_token_');
		if($this->params['_wx_openid_']){
			$wx_openid = $this->a('_wx_openid_');
			//微信ID传输验证
			$wx_trans_str = $this->a('_wx_trans_str_');
			if(md5($wx_openid.'|'.$this->_wx_trans_key_)==$wx_trans_str){
				$this->_wx_openid_ = $wx_openid;
			}
		}
		$this->logger->w('CMD:'.$this->_cmd_.',Client:'.$this->_client_);
		$post_flated = $_REQUEST;
		if(isset($post_flated['password'])){
			$post_flated['password'] = '******';
		}
		$this->logger->w('DATA:'.json_encode($post_flated));
		if(!$this->_cmd_){
			return $this->service_error(5001,'[_cmd_]系统参数不能为空');
		}
		if(!$this->_deviceid_){
			return $this->service_error(5002,'[_deviceid_]系统参数不能为空');
		}
		if(!$this->_client_){
			return $this->service_error(5003,'[_client_]系统参数不能为空');
		}
		if(!$this->_sign_){
			return $this->service_error(5004,'[_sign_]系统参数不能为空');
		}
		if(!$this->_token_){
			$this->_token_ = md5($this->_deviceid_.C('SECURE_KEY'));
		}
		//签名检查
		$this->authcheck();
		$m = D('Index');
		$m->_cmd_ = $this->_cmd_;
		$m->_token_ = $this->_token_;
		$m->_deviceid_ = $this->_deviceid_;
		$m->_client_ = $this->_client_;
		$m->_params_ = $this->params;
		$m->_version_currect_ = $this->_version_currect_;
		$m->_wx_openid_ = $this->_wx_openid_;
		try{
			$m->run();
			return $this->service_success($m->data);
		}catch(Exception $e){
			return $this->service_error($m->errorcode,$e->getMessage());
		}
	}

	public function authcheck(){
		if(md5(($this->_client_.'|'.$this->_client_keys_[$this->_client_].'|'.$this->_cmd_))!=$this->_sign_){
			return $this->service_error(401,'签名错误');
		}
	}

	public function a($name){
		$value = '';
		if($name=='_cmd_'){
			$value = $this->params['_cmd_'];
			unset($this->params['_cmd_']);
		}
		if($name=='_deviceid_'){
			$value = $this->params['_deviceid_'];
			unset($this->params['_deviceid_']);
		}
		if($name=='_client_'){
			$value = $this->params['_client_'];
			unset($this->params['_client_']);
		}
		if($name=='_sign_'){
			$value = $this->params['_sign_'];
			unset($this->params['_sign_']);
		}
		if($name=='_token_'){
			$value = $this->params['_token_'];
			unset($this->params['_token_']);
		}
		if($name=='_wx_openid_'){
			$value = $this->params['_wx_openid_'];
			unset($this->params['_wx_openid_']);
		}
		if($name=="_wx_trans_str_"){
			$value = $this->params['_wx_trans_str_'];
			unset($this->params['_wx_trans_str_']);
		}
		return $value;
	}

	public function service_error($code,$message,$data=''){
		$this->logger->w("Failure-".$code."-".$message."-".serialize($data));
		header("Content-type: application/json");
		$json = json_encode($this->other2String(array('errorcode'=>$code,'errormsg'=>$message,'dataresult'=>$data,'servertime'=>time()),$this->_client_));
		exit(trim(trim($json,chr(239).chr(187).chr(191))));
	}

	public function service_success($data=''){
		$this->logger->w("Success---".serialize($data));
		header("Content-type: application/json");
		$json = json_encode(array('errorcode'=>0,'errormsg'=>'','dataresult'=>$this->other2String($data,$this->_client_),'servertime'=>time()));
		exit(trim(trim($json,chr(239).chr(187).chr(191))));
	}
	
	
	
	public function verifycode(){
		$deviceid = $this->_get('d');
		$token = $this->_get('t');
		if (empty($deviceid) && empty($token)){
			die('必须指定token或设备号');
		}
		$width = $this->_get('w')?$this->_get('w'):48;
		$height = $this->_get('h')?$this->_get('h'):22;
		if(!$token){
			$token = md5($deviceid.C('SECURE_KEY'));
		}
		import('ORG.Util.String');
		$vcode = String::randString(4,1);
		$m = D('Index');
		$m->_token_ = $token;
		$m->token('imageVerifycode',$vcode);
		import('ORG.Util.Image');
		Image::buildImageVerify(0, 0, 'png', $width, $height, 'verify',$vcode);
	}
	
	//跳转到webview
	public function webView(){
		$key = $_REQUEST['key'];
		if(empty($key))exit("key is null");
		header("Content-type:text/html;charset=utf-8");
		$value = S($key);
		if(empty($value))exit('(参数为空)页面已过期，请重新请求！');
		exit($value);
	}
	
	
	
	//其他格式数据强制转换为string
	private function other2String($data,$client=''){
    	if(is_array($data)){
    		foreach($data as $key=>$val){
    			$data[$key] = $this->other2String($val,$client);
    			if(strtolower($client)=='android'){
    				$parse_key = $this->parse_name($key);
    				$parse_key?($data[$parse_key]=$data[$key]):'';
    			}
    		}
    	}else{
    		return (string)$data;
    	}
    	return $data;
    }
    //将C语言风格转换为java风格
	private function parse_name($name){
    	$per_name = $name;
    	$name = trim($name,"_");
    	$name = preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name);
    	$name = preg_replace("/([a-zA-Z])_/e", "strtoupper('\\1')", $name);
    	return ($per_name == $name?'':$name);
    }
	
}

class ServiceLogger {
	public $fp = null;
	public $write_file = true;
	public function __construct($name,$desc){
		if($this->write_file){
			$this->fp = fopen('/var/log/appservicelog/'.$name.'.'.date('Y-m-d').'.log','a');
		}
	}
	public function __destruct(){
		if($this->write_file){
			fclose($this->fp);
		}
	}
	public function w($content){
		$line = "[".date('Y-m-d H:i:s')."]\t[".get_client_ip()."]\t".$content."\n";
		if($this->write_file){
			fwrite($this->fp,$line);
		}else{
			echo $line;
		}
	}
	
}
?>