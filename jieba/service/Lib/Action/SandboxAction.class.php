<?php
class SandboxAction extends CommonAction {

	public $params = '';

	public function index(){
		$this->display();
	}

	public function submit(){
		$this->params = $_POST;
		unset($this->params['__hash__']);
		$_cmd_ = $this->a('_cmd_');
		if(!$_cmd_){
			return $this->parentscript('debugline','系统参数_cmd_不能为空');
		}
		$_deviceid_ = $this->a('_deviceid_');
		$_client_ = $this->a('_client_');
		if(!$_deviceid_ || !$_client_){
			return $this->parentscript('debugline','系统参数_deviceid_和_client_不能为空');
		}
		$this->parentscript('debugline','正在生成签名...');
		$_sign_ = md5($_client_.'|123456|'.$_cmd_);
		$this->parentscript('debugline','_sign_='.$_sign_);
		$this->parentscript('set_sign_',$_sign_,false);
		$ptoken = $this->a('_token_');
		if($ptoken=='******'){
			$ptoken = '';
		}
		$token = $ptoken?$ptoken:session('token');
		if($token){
			$this->parentscript('set_token_',$token,false);
		}
		$data = $this->params + array(
			'_cmd_'=>$_cmd_,
			'_deviceid_'=>$_deviceid_,
			'_client_'=>$_client_,
			'_sign_'=>$_sign_,
			'_token_'=>$token,
		);
		$result = $this->curlpost($data);
		echo '<style type="text/css">html{font-size:12px;color:#888;font-family:Arial;}</style><pre>';
		echo '发送的数据：';
		print_r($data);
		echo '返回的数据：';
		$result = json_decode($result,true);
		if($_cmd_=='member_login'){
			if($result['errorcode']=='0'){
				$token = $result['dataresult']['_token_'];
				$this->parentscript('debugline','登录成功...');
				$this->parentscript('set_token_',$token,false);
			}
		}
		if($_cmd_ =='member_reg' && $_POST['type']=='reg'){
			if($result['errorcode']=='0'){
				$token = $result['dataresult']['_token_'];
				$this->parentscript('debugline','注册成功...');
				$this->parentscript('set_token_',$token,false);
			}
		}
		print_r($result);
		echo '</pre>';
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
		return $value;
	}

	public function parentscript($function,$string,$viatime=true){
		echo '<script type="text/javascript">parent.'.$function.'("'.($viatime?(date('Y-m-d H:i:s').' '):'').$string.'");</script>';
	}

	public function curlpost($array){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://service.'.DOMAIN_ROOT);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
		$data = curl_exec($ch);
		curl_close($ch);
	    return $data;
	}
}
?>