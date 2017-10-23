<?php

class TextAction extends CommonAction {

    public function index1(){
      $a = $_SESSION['token'];
      $b = S("token_{$_SESSION['token']}_member_info");
      echo $a;
      dump($b);
      $this->display();
    }
    public function t1()
    {
        import("ORG.Util.String");
        $str = String::randString(5,4);
        echo $str;
    }
    public function pwd_safety($pwd)
    {
      $level = 0;
      if(empty($pwd))return $level;
      $level += preg_match("/[0-9]+/",$pwd)?1:0;
      $level += preg_match("/[0-9]{3,}/",$pwd)?1:0;
      $level += preg_match("/[a-z]+/",$pwd)?1:0;
      $level += preg_match("/[a-z]{3,}/",$pwd)?1:0;
      $level += preg_match("/[A-Z]+/",$pwd)?1:0;
      $level += preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$pwd)?1:0;
      $level += preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$pwd)?1:0;
      $level += preg_match("/[A-Z]{3,}/",$pwd)?1:0;
      $level += (strlen($pwd) >= 10)?1:0;
      return $level;
    }
    public function text1()
    {
      $this->display();
    }
   public function service($data){
        $service = C('TMPL_PARSE_STRING');
   		//$url = !empty($service["_SERVICE_"])?$service["_SERVICE_"]:"//service.".DOMAIN_ROOT;
   		$url = "http://service.zxcf.com";
   		//dump($url);exit;
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
   public function curlpost($url,$array){
        $ch = curl_init();//dump($array);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        $data = curl_exec($ch);
        curl_close($ch);dump($data);    
        $data = json_decode($data,true);//dump($data);
        return $data;
    }

}
