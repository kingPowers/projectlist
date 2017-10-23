<?php
require_once("Recorder.class.php");
require_once("URL.class.php");
//用于连接本机的hash及签章运算服务
class javaComm{
		
  protected $recorder;
  public $urlUtils;
  protected $error;

  function __construct($port){
    $this->recorder = new Recorder();
    $this->urlUtils = new URL();

	$this->recorder->write('equipId',"123456");
	$state = md5(uniqid(rand(), TRUE));
    $this->recorder->write('state',$state);
    $this->recorder->write('javaInitUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/init');
    $this->recorder->write('javaLoginUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/login');
    $this->recorder->write('selfHashSignUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/selfHashSign');
    $this->recorder->write('userHashSignUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/userHashSign');
    $this->recorder->write('verifyPdfUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/verifyPdf');
    $this->recorder->write('verifyTextUrl', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/verifyText');
    $this->recorder->write('selfHashSignStream', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/selfFileSign');
    $this->recorder->write('userHashSignStream', 'http://localhost:' . $port . '/tech-sdkwrapper/servlet/userFileSign');
  }
	
	public function init($project_id, $project_secret)
	{    
		//-------请求参数列表
    $keysArr = array(
        "projectId" => $project_id,
		"projectSecret" => $project_secret
    );

	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('javaInitUrl'), json_encode($keysArr)); 

 // echo "初始化参数打印:<br/>";
 //    var_dump($keysArr);
 //    echo "初始化地址打印:<br/>";
 //    echo $this->recorder->read('javaInitUrl'."<br/>";
 //        echo "状态码:".$return_code."xxxxxxxxxx返回参数:".$return_content;
        
		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];
		if($return_code != 200)
		{
			return 9999;
		} 
		return $errCode;
	}

	public function projectid_login(){
		list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('javaLoginUrl'), ''); 
		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];

		if($return_code != 200 || $errCode != 0)
		{
			return false;
		} 
		return true;
  }
  
  public function selfSignPDF($devId, $srcPdfFile, $dstPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
  	if(!empty($key))
  	{
  		$posType = 1;
  	} else {
  		$posType = 0;
  	}

  	$keysArr = array(
  		"devId" => $this->convertDevId($devId),
  		"srcPdfFile" => $srcPdfFile,
  		"dstPdfFile" => $dstPdfFile,
			"sealId" => $seaId,
			"signType" => $signType,
			"fileName" => $fileName,
			"posType" => $posType,
			"posPage" => $posPage,
			"posX" => $posX,
			"posY" => $posY,
			"key" => $key
		);
		
		//echo '平台自身签署传参：';
		//print_r($keysArr);
		//echo '<br><br>';
		
		list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('selfHashSignUrl'), json_encode($keysArr)); 
		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];
		
	if($return_code != 200)
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpError=>$return_code,
				httpRet =>$return_content,
				errShow => false);
  	}
		return json_decode($return_content,TRUE);
  }
  
  public function selfSignPDFStream($devId, $srcPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
  	if(!empty($key))
  	{
  		$posType = 1;
  	} else {
  		$posType = 0;
  	}
  	$keysArr = array(
  		"devId" => $this->convertDevId($devId),
        "file"=> $this->urlUtils->getRealFileIgnore($srcPdfFile),
  		//"file" => '@' . $srcPdfFile,
			"sealId" => $seaId,
			"signType" => $signType,
			"fileName" => $fileName,
			"posType" => $posType,
			"posPage" => $posPage,
			"posX" => $posX,
			"posY" => $posY,
			"key" => $key
		);
		
		$response = $this->urlUtils->post($this->recorder->read('selfHashSignStream'), $keysArr); 
		if(empty($response))
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpError=>$return_code,
				httpRet =>$return_content,
				errShow => false);
  	}
		return json_decode($response,TRUE);
  }
  
  public function userSignPDF($devId, $accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName,$code="")
  {
  	if(empty($key))
  	{
  		$posType = 1;
  	} else {
  		$posType = 0;
  	}
  	  	
  	$keysArr = array(
  		"devId" => $this->convertDevId($devId),
  		"accountId" => $accountId,
  		"srcPdfFile" => $srcPdfFile,
  		"dstPdfFile" => $dstPdfFile,
			"sealData" => $sealData,
			"signType" => $signType,
			"fileName" => $fileName,
			"posType" => $posType,
			"posPage" => $posPage,
			"posX" => $posX,
			"posY" => $posY,
			"key" => $key,
            "code"=>$code
		);
		
		// echo '平台用户签署传参：';
  //       var_dump($keysArr);
		// // print_r($keysArr);
		// echo '<br><br>';
		// echo $this->recorder->read('userHashSignUrl');
  //       echo '<br><br>';
		list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('userHashSignUrl'), json_encode($keysArr));
		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];

		if($return_code != 200)
  	{
         echo $return_content."<br/>";
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpError=>$return_code,
				httpRet =>$return_content,
				errShow => false);
  	}


		return json_decode($return_content,TRUE);
  }
  
  public function userSignPDFStream($devId, $accountId, $sealData, $srcPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
  	if(empty($key))
  	{
  		$posType = 1;
  	} else {
  		$posType = 0;
  	}
  	  	
  	$keysArr = array(
  		"devId" => $this->convertDevId($devId),
  		"accountId" => $accountId,
        "file"=> $this->urlUtils->getRealFileIgnore($srcPdfFile),
  		//"file" => '@' . $srcPdfFile,
			"sealData" => $sealData,
			"signType" => $signType,
			"fileName" => $fileName,
			"posType" => $posType,
			"posPage" => $posPage,
			"posX" => $posX,
			"posY" => $posY,
			"key" => $key
		);
		
		$response = $this->urlUtils->post($this->recorder->read('userHashSignStream'), $keysArr); 
		if(empty($response))
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpError=>$return_code,
				httpRet =>$return_content,
				errShow => false);
  	}
		return json_decode($response,TRUE);
  }
  
  private function convertDevId($devId)
  {
  	return substr($devId, strlen("prj_"));
  }
  //pdf验签
  public function localVerifyPdf($srcPdfFile, $dstPdfFile,$filepath)
  {
  	$keysArr = array(
  		"srcPdfFile" => $srcPdfFile,
  		"dstPdfFile" => $dstPdfFile,
		"filePath" => $filepath
		);
		
		list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('verifyPdfUrl'), json_encode($keysArr)); 
		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];
		echo $errCode;
		
		if($return_code != 200)
	  	{
	  		return array(
					errCode => 101,
					msg => "网络错误",
					httpError=>$return_code,
					httpRet =>$return_content,
					errShow => false);
	  	}
		return json_decode($return_content,TRUE);
  }
}
