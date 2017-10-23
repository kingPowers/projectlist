<?php
error_reporting(E_ERROR  | E_PARSE);
// require_once (CLASS_PATH.'/../../include/log4php/Logger.php');
// Logger::configure(CLASS_PATH.'/../../log4php.properties');
/*
 * @brief url封装类，将常用的url请求操作封装在一起
 */
class URL {
	private $error;
	private $cookie;
	public function __construct() {
	}
	public function init($project_id, $project_secret, $redirectUrl) {
		// -------请求参数列表
		$keysArr = array (
				"projectId" => $project_id,
				"securtCode" => $project_secret,
				"redirectUrl" => $redirectUrl 
		);
		
		$response = $this->post ( "http://itsm.tsign.cn/tgmonitor/rest/app!getAPIInfo", $keysArr );
		// echo $response;
		// --------检测错误是否发生
		$itsm = json_decode ( $response );
		
		// print_r($itsm);
		if ($itsm->errCode != 0) {
			// $this->error->showError($itsm->error, $itsm->error_description);
			return ( int ) $itsm->errCode;
		}
		return 0;
	}
	/**
	 * combineURL
	 * 拼接url
	 * 
	 * @param string $baseURL
	 *        	基于的url
	 * @param array $keysArr
	 *        	参数列表数组
	 * @return string 返回拼接的url
	 */
	public function combineURL($baseURL, $keysArr) {
		$combined = $baseURL . "?";
		$valueArr = array ();
		
		foreach ( $keysArr as $key => $val ) {
			$valueArr [] = "$key=$val";
		}
		
		$keyStr = implode ( "&", $valueArr );
		$combined .= ($keyStr);
		
		return $combined;
	}
	
	/**
	 * get_contents
	 * 服务器通过get请求获得内容
	 * 
	 * @param string $url
	 *        	请求的url,拼接后的
	 * @return string 请求返回的内容
	 */
	public function get_contents($url) {
		if (ini_get ( "allow_url_fopen" ) == "1") {
			$response = file_get_contents ( $url );
		} else {
			$ch = curl_init ();
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
			curl_setopt ( $ch, CURLOPT_URL, $url );
			$response = curl_exec ( $ch );
			curl_close ( $ch );
		}
		
		// -------请求为空
		// if(empty($response)){
		// $this->error->showError("50001");
		// }
		//记载日志
		$this->recordHttpRequest($url,"",$response);
		
		return $response;
	}
	
	/**
	 * get
	 * get方式请求资源
	 * 
	 * @param string $url
	 *        	基于的baseUrl
	 * @param array $keysArr
	 *        	参数列表数组
	 * @return string 返回的资源内容
	 */
	public function get($url, $keysArr) {
		$combined = $this->combineURL ( $url, $keysArr );
		return $this->get_contents ( $combined );
	}
	
	/**
	 * post
	 * post方式请求资源
	 * 
	 * @param string $url
	 *        	基于的baseUrl
	 * @param array $keysArr
	 *        	请求的参数列表
	 * @param int $flag
	 *        	标志位
	 * @return string 返回的资源内容
	 */
	public function post($url, $keysArr, $flag = 0) {
		// echo '<br>'.$url.'---------------'.$keysArr.'<br>';
		// print_r($keysArr);
		$ch = curl_init ();
		if (! $flag)
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
			
			// curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			// curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $keysArr );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		// curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
		$ret = curl_exec ( $ch );
		
		// 失败重试一次
		$trytime = 3;
		for($i = 0; $i < $trytime; $i++){
			$return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
			if ($return_code != 200) {
	      $ret = curl_exec($ch);
	    } else {
	    	break;
	    }
		}
		
		curl_close ( $ch );
		// echo $ret;
		// 记载日志
		$this->recordHttpRequest($url, json_encode($keysArr),$ret);


		return $ret;
	}
	public function http_post_json($url, $data_string, $flag = 0) {
		$ch = curl_init ();
		if (! $flag)
			curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data_string );
		curl_setopt ( $ch, CURLOPT_POST, TRUE );
		curl_setopt ( $ch, CURLOPT_VERBOSE, false );
		curl_setopt ( $ch, CURLOPT_HEADER, FALSE );
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, TRUE );
		// curl_setopt($ch, CURLOPT_COOKIE, $this->cookie);
		curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
				'Content-Type: application/json' 
		) );
		ob_start ();
		curl_exec ( $ch );
		
		// 失败重试一次
		$trytime = 3;
		for($i = 0; $i < $trytime; $i++){
			$return_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
			if ($return_code != 200) {
		      curl_exec($ch);
		    } else {
		    	break;
		    }
		}
    		
		$return_content = ob_get_contents ();
		ob_end_clean ();
		// echo $return_content;
		
		// 记载日志
		$this->recordHttpRequest($url,$data_string,$return_content);

		return array (
				$return_code,
				$return_content 
		);
	}

	/**
	 * 兼容不同版本的文件上传
	 */
	public function getRealFileIgnore($filePath){
		if (class_exists('\CURLFile')) {
		    return new CURLFile($filePath);
		} else {
		    return '@' . $filePath;
		}
	}


	// 写一个loadprint函数
	// loadclass函数不具备自动加载类的功能
	public static function loadprint($class) {
	    $filename = CLASS_PATH.'/../log4php/'. $class .'.php';
	    if(is_file($filename)){
	        require_once($filename);
	    }
	}
	

	/**
	 * 记载模拟请求的日志
	 */
	public function recordHttpRequest($url,$param,$response){
		$isSuccess=spl_autoload_register(array('URL','loadprint'));
		if(class_exists('Logger')){
			Logger::configure(CLASS_PATH.'/../comm/log4php.properties');
			$logger = Logger::getRootLogger();
			$str="\r\n"."【请求的网址】:".$url."\r\n";
			$str.="【请求的参数】:" .$param."\r\n";
			$str.="【请求的结果】:" .$response."\r\n";
	 		$logger->debug($str);
	 	}
	}
}
