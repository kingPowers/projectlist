<?php

require_once("Recorder.class.php");
require_once("URL.class.php");
require_once("javaComm.class.php");
require_once ("MacAddr.class.php");
require_once ("ErrorConstant.class.php");
vendor('Eqian.comm.config');
class eSign{
  const VERSION = "1.0";
	 //private $ITSM_GETAPIINFO_URL = "http://121.40.164.61:8080/tgmonitor/rest/app!getAPIInfo2";
    //private $ITSM_GETAPIINFO_URL = "http://121.43.159.210:8080/tgmonitor/rest/app!getAPIInfo2";
    private $ITSM_GETAPIINFO_URL = "http://itsm.tsign.cn/tgmonitor/rest/app!getAPIInfo2";
	private $TECH_SERVICE_PORT = "8080";
	private $project_id;
	private $project_secret;
	private $token;
	private $devId;

  protected $recorder;
  public $urlUtils;
  protected $error;
  protected $javaComm;
  protected $ErrorConstant;
  function __construct(){
  	$this->recorder = new Recorder();
  	$this->urlUtils = new URL();
  	$this->javaComm = new javaComm($this->TECH_SERVICE_PORT);
	  $this->macAddr = new GetMacAddr(PHP_OS);
	  $this->recorder->write('equipId', $this->macAddr->mac_addr );

		//-------生成唯一随机串防CSRF攻击
    $state = md5(uniqid(rand(), TRUE));
    $this->recorder->write('state',$state);
    $this->ErrorConstant = new ErrorConstant();
  }

	public function init($project_id, $project_secret)
	{
    if(empty($project_id)){
        return $this->ErrorConstant->PROJECT_ID_NULLPOINTER;
    }
    if(empty($project_secret)){
        return $this->ErrorConstant->PROJECT_SECRET_NULLPOINTER;
    }

		if(!empty($this->project_id) && !empty($this->project_secret)
				&& 0 == strcmp($this->project_id, $project_id)
				&& 0 == strcmp($this->project_secret, $project_secret))
		{
			return 0;
		}
		$this->project_id = $project_id;
		$this->project_secret = $project_secret;
		$ret = $this->javaComm->init($project_id, $project_secret);
		return $this->getUrlListFromSrv();
	}

  private function getUrlListFromSrv()
  {
  	//-------请求参数列表
    $keysArr = array(
    	"project_id" => $this->project_id,
      "project_secret" => $this->project_secret,
      "channel" => "phptechsdk1.0",
      "version" => "1.0",
      "http_type"=>"https"
    );
		$response = $this->urlUtils->post($this->ITSM_GETAPIINFO_URL, $keysArr);
    //--------检测错误是否发生
    $itsm = json_decode($response);

    if($itsm->errCode != 0){
        echo "eSign init error <br>";
        return (int)$itsm->errCode;
    }
    $this->saveUrlInfo($itsm->https_urls);
    return 0;
  }

  private function saveUrlInfo($urlAry)
	{
		$urlCount = count($urlAry);
		for($i = 0; $i < $urlCount; $i++)
		{
			$url = $urlAry[$i];

			if(0 == strcmp($url->urlKey, 'loginUrl'))
			{
				$this->recorder->write('loginUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techAddAccountUrl'))
			{
				$this->recorder->write('techAddAccountUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techAddTempSealUrl'))
			{
				$this->recorder->write('techAddTempSealUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techSignHashUrl'))
			{
				$this->recorder->write('techSignHashUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techSaveSignlogUrl'))
			{
				$this->recorder->write('techSaveSignlogUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techSaveSignedFile'))
			{
				$this->recorder->write('techSaveSignedFile', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'techGetDocUrlUrl'))
			{
				$this->recorder->write('techGetDocUrlUrl', $url->urlValue);
			}
			if(0 == strcmp($url->urlKey, 'ConvertDocUrl'))
			{
				$this->recorder->write('ConvertDocUrl', $url->urlValue);
			}
      if(0 == strcmp($url->urlKey, 'techSendMobileCodeUrl'))
      {
        $this->recorder->write('techSendMobileCodeUrl', $url->urlValue);
      }
      if(0 == strcmp($url->urlKey, 'techAddFileSealUrl'))
      {
        $this->recorder->write('techAddFileSealUrl', $url->urlValue);
      }
      if(0 == strcmp($url->urlKey, 'techUpdateAccountUrl'))
      {
        $this->recorder->write('techUpdateAccountUrl', $url->urlValue);
      }
		}
	}

	public function projectid_login(){
		$equipId = $this->recorder->read("equipId");
    //-------构造请求参数列表
    $keysArr = array(
  		"project_id" => $this->project_id,
  		"project_secret" => $this->project_secret,
  		"response_type" => "post",
  		"equipId" => $equipId,
  		"loginType" => "4",
		);
    $login_url = $this->urlUtils->post($this->recorder->read('loginUrl'), $keysArr);
  //   print_r($keysArr);
		// echo '<br>==============';
  //     print_r($this->recorder->read('loginUrl'));
		// echo '<br>==============';
		// print_r($login_url);
		// echo '<br>==============';
		$ret_json = json_decode($login_url,TRUE);
		$errCode = $ret_json['errCode'];
		if($errCode == 0)
		{
			$this->token = $ret_json['token'];
			//$this->recorder->write('token',$this->token);
			$this->devId = $ret_json['accountId'];
			//echo '登录成功：token--' . $this->token . '<br>';
			$this->javaComm->projectid_login();
			return true;
		}
		return false;
  }

  public function addPersonAccount($mobile, $name, $idNo,$personarea=0, $email='', $organ='', $title='', $address='')
  {
    if(empty($mobile)){
        return $this->ErrorConstant->MOBILE_NULLPOINTER;
    }
    if(empty($name)){
        return $this->ErrorConstant->PERSON_NAME_NULLPOINTER;
    }
    if(empty($idNo)){
        return $this->ErrorConstant->PERSON_IDNO_NULLPOINTER;
    }
  	$personareaArr=array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4");
    $personarea=(string)$personarea;

  	if(($personarea!="0"&&empty($personarea))||!in_array($personarea, $personareaArr))
  	{
  		return $this->ErrorConstant->LEGAL_AREA_INVALIDATE;
  	}
  	$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"account"=>array(
				"email"=>$email,	//邮箱地址
				"mobile" => $mobile,	//手机号码
				"person" => array
				(
				  "name" => $name,		//真实姓名
				  "idNo" => $idNo,		//身份证号码
				  "organ" => $organ,			//公司
				  "title" => $title,		//职务
				  "address" => $address,	//邮寄地址
				  "personArea"=>$personarea //用户归属地
				)
			)
		);
//      var_dump($this->recorder->read('techAddAccountUrl'),json_encode($keysArr));exit;
  	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techAddAccountUrl'), json_encode($keysArr));
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

  public function addOrganizeAccount($mobile, $name, $organCode, $email='', $organType=0, $regCode ='', $legalName='',$legalArea=0,$userType='1',$agentName='',$agentIdNo='',$legalIdNo='',$regType='0')
  {
    if(empty($mobile)){
        return $this->ErrorConstant->MOBILE_NULLPOINTER;
    }
    if(empty($name)){
        return $this->ErrorConstant->ORGANIZE_NAME_NULLPOINTER;
    }
    if(empty($organCode)){
        return $this->ErrorConstant->ORGAN_CODE_NULLPOINTER;
    }
  	$personareaArr=array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4");
    $legalArea=(string)$legalArea;
  	if(($legalArea!="0"&&empty($legalArea))||!in_array($legalArea, $personareaArr,true))
  	{
  		return $this->ErrorConstant->LEGAL_AREA_INVALIDATE;
  	}

  	switch ($userType) {
  		case '1':
  			/*if(empty($agentName)||empty($agentIdNo)){
  				return $this->showError('代理注册时，代理人姓名和身份证号不能为空!');
  			}*/
            if(empty($agentName)){
                    return $this->ErrorConstant->AGENT_NAME_NULL;
                }
            if(empty($agentIdNo)){
                return $this->ErrorConstant->AGENT_IDCARD_NULL;
            }
  			break;
		case '2':
  			/*if(empty($legalName)||empty($legalIdNo)){
  				return $this->showError('法人注册时，法人姓名和身份证号不能为空!');
  			}*/

            if(empty($legalName)){
                    return $this->ErrorConstant->LEGAL_NAME_NULL;
                }
            if(empty($legalIdNo)){
                return $this->ErrorConstant->LEGAL_IDCARD_NULL;
            }
  			break;

  		default:
            return $this->ErrorConstant->PARAM_INVALIDATE;
  			//return $this->showError('注册类型不合法!');
  			break;
  	}

  	$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"account"=>array(
				"email"=>$email,
				"mobile" => $mobile,
				"organize" => array
				(
				  "name" => $name,
				  "organCode" => $organCode,
				  "organType" => $organType,
				  "regCode" => $regCode,
				  "legalName" => $legalName,
				  "userType" => $userType,
				  "legalArea"=>$legalArea, //用户归属地
				  "agentName"=>$agentName, //代理人姓名
				  "agentIdNo"=>$agentIdNo, //代理人身份证号
				  "legalIdNo"=>$legalIdNo, //法人身份证号
				  "licenseType"=>$regType//营业执照类型
				)
			)
		);
  	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techAddAccountUrl'), json_encode($keysArr));
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

  public function addTemplateSeal($accountId, $templateType='square', $color='red', $hText='', $qText='')
  {
     if(empty($accountId)){
        return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
    }
    if(empty($templateType)){
      return $this->showError('模版类型不能为空!');
    }
    if(empty($color)){
      return $this->ErrorConstant->SEALCOLOR_NULLPOINTER;
    }
  	$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"accountId" => $accountId,
			"templateType" => $templateType,
			"color" => $color,
			"hText" => $hText,
			"qText" => $qText
		);
  	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techAddTempSealUrl'), json_encode($keysArr));
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

	public function signDataHash($data,$accountId = ''){
      return $this->commonsignDataHash($data,$accountId,'');
  }

  private function save_hash_tech($hash,$signInfo,$accountId=''){
        $redirect_url = $this->recorder->read("redirect_url");

		if(empty($accountId))
		{
			$accountId = $this->devId;
		}
    $keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"accountId" => $accountId,
			"docName" => '',
			"docHash" => $hash,
			"signInfo" => $signInfo,
			"timestamp" => "",
		);

		list($return_code, $return_content) =  $this->urlUtils->http_post_json($this->recorder->read('techSaveSignlogUrl'), json_encode($keysArr));

		$ret = json_decode($return_content,TRUE);
		$errCode = $ret['errCode'];
		if($return_code == 200 && $errCode == 0)
		{
			//echo "--------------------signId: " . $ret['signId'];
			return TRUE;
		}
		return array(
				errCode => 1003,
				msg => "保存摘要信息失败",
				httpError=>$return_code,
				httpRet =>$return_content,
				errShow => false);
  }

  public function selfSignPDF($srcPdfFile, $dstPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName)
  {

     if(empty($srcPdfFile)){
            return $this->ErrorConstant->SING_FILE_NOT_EXISTS;
    }

    if(empty($dstPdfFile)){
            return $this->ErrorConstant->SIGNED_FILE_NOT_EXISTS;
    }
  	return $this->javaComm->selfSignPDF($this->devId, $srcPdfFile, $dstPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName);
  }

  public function selfSignPDFStream($srcPdfFile, $dstPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
    if(empty($srcPdfFile)){
            return $this->ErrorConstant->SING_FILE_NOT_EXISTS;
    }

    if(empty($dstPdfFile)){
            return $this->ErrorConstant->SIGNED_FILE_NOT_EXISTS;
    }
  	$ret = $this->javaComm->selfSignPDFStream($this->devId, $srcPdfFile, $seaId, $signType, $posPage, $posX, $posY, $key, $fileName);

  	if($ret['errCode'] == 0){
  		$saveFile = file_put_contents($dstPdfFile, base64_decode($ret['stream']), true);
  	}
  	return $ret;
  }

  public function userSignPDF($accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
    if(empty($accountId)){
            return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
    }

    if(empty($sealData)){
            return $this->ErrorConstant->SESEALID_NULLPOINTER;
    }
    if(empty($srcPdfFile)){
            return $this->ErrorConstant->SING_FILE_NOT_EXISTS;
    }

    if(empty($dstPdfFile)){
            return $this->ErrorConstant->Doc_SVAEPATH_NULLPOINTER;
    }
  	return $this->javaComm->userSignPDF($this->devId, $accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName,"");
  }

  public function userSignPDFStream($accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName)
  {
    if(empty($accountId)){
            return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
    }

    if(empty($sealData)){
            return $this->ErrorConstant->SESEALID_NULLPOINTER;
    }
    if(empty($srcPdfFile)){
            return $this->ErrorConstant->DOC_NOT_EXIST;
    }

    if(empty($dstPdfFile)){
            return $this->ErrorConstant->Doc_SVAEPATH_NULLPOINTER;
    }
  	$ret = $this->javaComm->userSignPDFStream($this->devId, $accountId, $sealData, $srcPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName);
  	if($ret['errCode'] == 0){
  		$saveFile = file_put_contents($dstPdfFile, base64_decode($ret['stream']), true);
  	}
  	return $ret;
  }

	public function saveSignedFile ($docFilePath, $docName, $signer)
	{
        if(empty($docFilePath)){
            return $this->ErrorConstant->SIGNED_FILE_NOT_EXISTS;
        }

        if(empty($docName)){
                return $this->ErrorConstant->DOC_NAME_NULLPOINTER;
        }
        if(empty($signer)){
                return $this->ErrorConstant->SIGNER_NOT_EXISTS;
        }

		$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"docName" => $docName,
			"signer" => $signer,
      "file"=> $this->urlUtils->getRealFileIgnore($docFilePath)
			// "file"=> '@'.$docFilePath
		);
		// print_r($keysArr);
		// echo "<br>";
		// echo $this->recorder->read('techSaveSignedFile');
		// echo "<br>";
  	$response = $this->urlUtils->post($this->recorder->read('techSaveSignedFile'), $keysArr);
  	if(empty($response))
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpRet =>$response,
				errShow => false);
  	}
  	return json_decode($response,TRUE);
	}

	public function getSignedFile ($docId)
	{
         if(empty($docId)){
                return $this->ErrorConstant->DOCID_NULLPOINTER;
        }
		$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"docId" => $docId
		);
  	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techGetDocUrlUrl'), json_encode($keysArr));
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

	public function showSealImage($sealData)
	{
		if(!is_array($sealData))
		{
			$sealData = json_decode($sealData,TRUE);
		}
		if(is_array($sealData))
		{
			// 获取电子印章数据
			$data = base64_decode($sealData['data']);
			$xml = simplexml_load_string($data);
			if(!is_null($xml)){
				return (string)$xml->SealInfo->PictrueInfo->data;
			}
		}
		return '';
	}

  public function conv2pdf($docFilePath, $docType='doc')
  {
    if($docFilePath!="0"&&empty($docFilePath))
    {
        return $this->ErrorConstant->DOC_NOT_EXIST;
    }
  	$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
      "file"=> $this->urlUtils->getRealFileIgnore($docFilePath),
  		"doctype" => $docType
  	);

  	$response = $this->urlUtils->post($this->recorder->read('ConvertDocUrl'), $keysArr);
		if(empty($response))
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpRet =>$response,
				errShow => false);
  	}
  	return json_decode($response,TRUE);
  }


  //pdf验签
  public function localVerifyPdf($srcPdfFile, $dstPdfFile,$filepath)
  {
  	return $this->javaComm->localVerifyPdf($srcPdfFile, $dstPdfFile,$filepath);
  }

  //平台用户自身签署
  public function selfTextSign($srcData)
  {
  	return $this->javaComm->selfTextSign($this->devId,$srcData);
  }


  //文本验签
  public function localVerifyText($srcData,$signResult)
  {
    if(empty($srcData)){
        return $this->ErrorConstant->PDF_HASH_NULLPOINTER;
    }

    if(empty($signResult)){
        return $this->ErrorConstant->SIGNRESULT_NULLPOINTER;
    }
  	$keysArr = array(
  		"srcData" => $srcData,
  		"signResult" => $signResult
  	);

  	list($return_code, $return_content) =  $this->urlUtils->http_post_json($this->recorder->read('verifyTextUrl'), json_encode($keysArr));

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

  //文件流验签
  public function streamVerify($file)
  {
    if(empty($file)){
       return $this->ErrorConstant->FILE_NOT_EXIST;
    }
    //$fields['file'] = '@'.$file;
    $fields['file'] = $this->urlUtils->getRealFileIgnore($file);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->recorder->read('verifyPdfUrl') );
    curl_setopt($ch, CURLOPT_POST, 1 );
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );

    ob_start();
    curl_exec($ch);
    $result = ob_get_contents();
    ob_end_clean();
    curl_close($ch);
    return  json_decode($result,TRUE);
  }

  //文件名验签
  public function filePathVerify($filepath)
  {
    if(empty($filepath)){
       return $this->ErrorConstant->FILE_NOT_EXIST;
    }

  	$keysArr = array(
  		"filePath" => $filepath
  	);

  	$response = $this->urlUtils->post($this->recorder->read('verifyPdfUrl'), $keysArr);
		if(empty($response))
  	{
  		return array(
				errCode => 101,
				msg => "网络错误",
				httpRet =>$response,
				errShow => false);
  	}
  	return json_decode($response,TRUE);
  }

  //返回错误信息
  public function showError($errorMsg)
  {
  		return array(
				errCode => 101,
				msg => $errorMsg,
				errShow => false
			);
  }

  //更新账户信息
  public function updateAccountContact($mobile,$email,$accountUid)
  {
  	$keysArr = array(
  		"token" => $this->token,
  		"equipId" => $this->recorder->read("equipId"),
			"account"=>array(
				"email"=>$email,	//邮箱地址
				"mobile" => $mobile,	//手机号码
				"accountUid"=>$accountUid
			)
		);
  	list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techUpdateAccountUrl'), json_encode($keysArr));
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

  //发送签署短信验证码
  public function sendSignCode($accountUid)
  {
    if(empty($accountUid))
    {
        return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
    }

    $keysArr=array(
        "token" =>$this->token,
        "equipId"=>$this->recorder->read("equipId"),
        "type"=>4,
        "accountUid"=>$accountUid);
    list($return_code, $return_content) =$this->urlUtils->http_post_json($this->recorder->read("techSendMobileCodeUrl"),json_encode($keysArr));
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

  //pdf文档转化为图片(本地文件)
  public function localPdf2Image($docFilePath, $pageNum="", $imageSize="max")
  {
    if(empty($docFilePath) ||!file_exists($docFilePath))
    {
        return $this->ErrorConstant->SING_FILE_NOT_EXISTS;
    }
    $keysArr=array(
        "token" =>$this->token,
        "equipId"=>$this->recorder->read("equipId"),
        "file"=> $this->urlUtils->getRealFileIgnore($docFilePath),
        "pageNum"=>$pageNum,
        "imageSize"=>$imageSize);
    $response = $this->urlUtils->post($this->recorder->read('localPdfToImageUrl'), $keysArr);
    if(empty($response))
    {
      return array(
        errCode => 101,
        msg => "网络错误",
        httpRet =>$response,
        errShow => false);
    }
    return json_decode($response,TRUE);
  }

  //平台用户签署(需要短信验证码)
  public function userSafeSignPDF($accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName,$code)
  {
    if(empty($accountId)){
            return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
    }

    if(empty($sealData)){
            return $this->ErrorConstant->SESEALID_NULLPOINTER;
    }
    if(empty($srcPdfFile)){
            return $this->ErrorConstant->SING_FILE_NOT_EXISTS;
    }

    if(empty($dstPdfFile)){
            return $this->ErrorConstant->SIGNED_FILE_NOT_EXISTS;
    }
    if(empty($code)){
            return $this->ErrorConstant->CODE_NULLPOINTER;
    }
    return $this->javaComm->userSignPDF($this->devId, $accountId, $sealData, $srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY, $key, $fileName,$code);
  }


    public function safesignDataHash($data,$accountId = '',$code){
      if(empty($code)){
        return $this->ErrorConstant->CODE_NULLPOINTER;
      }
      return $this->commonsignDataHash($data,$accountId,$code);
  }

  //平台用户文本签名（需要短信验证）
  private function commonsignDataHash($data,$accountId = '',$code){
      if(empty($data)){
          return $this->ErrorConstant->PDF_HASH_NULLPOINTER;
      }


      $redirect_url = $this->recorder->read("redirect_url");

      if(empty($accountId))
      {
        $accountId = $this->devId;
      }

        $hash = base64_encode(hash('sha256',$data,true));
        $keysArr = array(
          "token" => $this->token,
          "equipId" => $this->recorder->read("equipId"),
          "accountId" => $accountId,
          "hash"=>$hash,
          "code"=>$code
        );


      list($return_code, $return_content) =  $this->urlUtils->http_post_json($this->recorder->read('techSignHashUrl'), json_encode($keysArr));

      // print_r($this->recorder->read('techSignHashUrl'));
      // print_r("<br/>");
      // print_r($keysArr);
      // print_r("参数为:<br/>".$return_content);


      $ret = json_decode($return_content,TRUE);
      $errCode = $ret['errCode'];

      if($return_code == 200 && $errCode == 0)
      {
        $signInfo = $ret['signResult'];
        //echo "--------------------signResult :" . $signInfo;

        $saveRet = $this->save_hash_tech($hash,$signInfo,$accountId);
        if(is_bool($saveRet) && $saveRet)
        {
          return $signInfo;
        }
        if(is_array($saveRet))
        {
          return $saveRet;
        }
        return array(
          errCode => 1002,
          msg => "保存摘要信息失败",
          errShow => true,
        );
      }
      else{
          return array(errCode => $errCode ,
          msg => $ret['msg'],
          errShow => true);
      }
      // if(strlen($ret) < 1)
      // {
      //   return array(errCode => 1002,
      //     msg => "保存摘要信息失败",
      //     httpError=>$return_code,
      //     errShow => true);
      // }
      return $ret;
  }

  /**
   * 创建手绘印章
   */
  public function addFileSeal($accountId, $imgB64, $color='red')
  {
     if(empty($accountId)){
        return $this->ErrorConstant->ACCOUNT_NULLPOINTER;
      }
      if(empty($imgB64)){
        return $this->ErrorConstant->SEAL_BASE64_NULLPOINTER;
      }

    $keysArr = array(
      "token" => $this->token,
      "equipId" => $this->recorder->read("equipId"),
      "accountId" => $accountId,
      "imgB64" => $imgB64,
      "color" => $color
    );
    list($return_code, $return_content) = $this->urlUtils->http_post_json($this->recorder->read('techAddFileSealUrl'), json_encode($keysArr));
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
