<?php
class wxjs{
  private $appId = '';
  private $appSecret = '';
  private static $_map = array();//存储静态变量

  public function __construct() {
    $this->appId = C('AppID');
    $this->appSecret = C('AppSecret');
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket={$jsapiTicket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
   if(self::$_map['ticket'] && self::$_map['ticket_timetemp']>time())
		return self::$_map['ticket'];
   $accessToken = $this->getAccessToken();
   $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token={$accessToken}";
   $res = json_decode($this->httpGet($url));
   $ticket = $res->ticket;
   if ($ticket) {
      self::$_map['ticket_timetemp'] = time() + 7000;
      self::$_map['ticket'] = $ticket;
   }
    return self::$_map['ticket'];
  }

  private function getAccessToken() {
	if(self::$_map['access_token'] && self::$_map['token_timetemp']>time())
		return self::$_map['access_token'];
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
    $res = json_decode($this->httpGet($url));
    $access_token = $res->access_token;
    if ($access_token) {
        self::$_map['access_token'] = $access_token;
        self::$_map['token_timetemp'] = time()+7000;
    }
    return self::$_map['access_token'];
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
}