<?php
/*
 * 富友第三方支付
 * 
 */
namespace manager\index\model;

class Fuyou extends Common{
   const PAY_NAME = "【富友】";
    
    private $isDevelop = false;//是否是生产环境 true:表示生产环境 false:表示测试环境
    
    private $currentDomain;//当前接口URL域名
    
    private $mchntCd;//商户号
    
    private $loginId;//商户用户名
    
    private $ver;
    
    private $privateKey;
    
    private $publicKey;
    
    public function config(){
        //调试环境下配置
        $debugConfig = [
            "currentDomain"=>"https://jzh-test.fuiou.com/jzh/",//域名
            "mchntCd"=>"0002900F0041077",//商户代码
            "loginId"=>"jzh09",//登录用户名
            "ver"=>"0.44",//版本号
            "privateKey"=>"php_prkey.pem",//私钥文件名称
            "publicKey"=>"php_pbkey.pem",//公钥文件名称
            
        ];
        //生产环境下配置
        $developConfig = [
            "currentDomain"=>"https://jzh.fuiou.com/",
            "mchntCd"=>"",//商户代码
            "loginId"=>"",//登录用户名
            "ver"=>"0.44",//版本号
            "privateKey"=>"",//私钥文件名称
            "publicKey"=>"",//公钥文件名称
        ];
        return ["debugConfig"=>$debugConfig,"developConfig"=>$debugConfig];
    }
    
    //初始化
    protected function initialize() {
        parent::initialize();
       
        $this->initConfig();
    }
    /*
     * 开户注册
     *      --HTTP形式开通金账户
     *   eg. $fuyou = new Fuyou();
     *       $fuyou->reg($data);
     *     
     */
    public function reg($data){
        $memberInfo = $this->memberInfo();
        $currentUrl = $this->currentDomain."reg.action";
        if(false==$this->preReg($data))return false;
        
        $params = array();
        $params['ver'] = $this->ver;//版本
        $params['mchnt_cd'] = $this->mchntCd;//商户代码,必填
        $params['mchnt_txn_ssn'] = $data['mchnt_txn_ssn']?:$this->getTxnSsn();//流水号,必填
        $params['mobile_no'] = $data['mobile'];//手机号码 ,必填
        $params['cust_nm'] = $data['names'];  //真实姓名,必填
        $params['certif_id'] = $data['certiNumber'];//身份证号,必填
        $params['email'] = $data['email'];//邮箱
        $params['city_id'] = $data['city_id'];//开户行地区代码,必填
        $params['parent_bank_id'] = $data["bank_code"];//开户行行别(开户银行代号),必填
        $params['bank_nm'] = $data["bank_nm"];//开户行支行名称
        $params['capAcntNm'] = "";
        $params['capAcntNo'] = $data['capAcntNo'];//银行卡卡号,必填
        $params['password'] = md5($data['password']);//密码
        $params['lpassword'] = md5($data['lpassword']);//确认密码
        $params['rem'] = $data['rem'];//备注
        
        $result = $this->postdata($params, $currentUrl);
        $arrResult = $this->checkresult($result);
        if(false===$arrResult)return false;
    	return $arrResult;
    }
    //开户注册数据
    protected function preReg(&$data){
        $memberInfo = $this->memberInfo();
        
        //身份证号
        if(false===($queryInfo = $this->queryFromCertiNumber($data["certiNumber"]))){
            return false;
        }elseif(count($queryInfo)>0){
            $this->error = "身份证号已绑定过银行卡";
            return false;
        }
        //手机号
        if(false===($queryInfo = $this->queryFromMobile($data["mobile"]))){
            return false;
        }elseif(count($queryInfo)>0){
            $this->error = "手机号已绑定过银行卡";
            return false;
        }
        //银行卡号
        if(false===($queryInfo = $this->queryFromBankCard($data["capAcntNo"]))){
            return false;
        }elseif(count($queryInfo)>0){
            $this->error = "银行卡号已被绑定过";
            return false;
        }
        if(empty($data["names"])){$this->error = "姓名为空";return false;}
        if(empty($data["city_id"])){$this->error = "开户城市为空";return false;}
        if(empty($data["bank_code"])){$this->error = "开户银行为空";return false;}
        if(!empty($data["email"]) && !isEmail($data["email"])){$this->error = "邮箱格式不正确";return false;}
        //用户唯一标识
        if(!isset($data["userId"])){
            $data["userId"] = $memberInfo["userId"]?:substr($data["certiNumber"],-5);
        }
        
       return true; 
    }
    //用户信息
    public function memberInfo(){
        return [
            ""
        ];
    }
    /*
     * 根据手机号查询用户信息
     * @param $mobile:手机号
     * @param $refresh:是否刷新数据，默认是否
     * 
     * @return boolean|array
     */
    public function queryFromMobile($mobile,$refresh = false){
        if(empty($mobile)){
            $this->error = "手机号为空";
            return false;
        }elseif(!isMobile($mobile)){
            $this->error = "手机号格式错误";
            return false;
        }
        if($this->_query[$mobile]===null || $refresh){
            $this->_query[$mobile] = $this->queryUserInfs(["user_ids"=>$mobile]);
        }
        if(false===$this->_query[$mobile]){
            $this->error = "手机号格式错误";
            return false;
        }
        return $this->_query[$mobile];
    }
    /*
     * 根据身份证号查询用户信息
     * @param $certiNumber:身份证号
     * @param $refresh:是否刷新数据，默认是否
     * 
     * @return boolean|array
     */
    public function queryFromCertiNumber($certiNumber,$refresh = false){
        if(empty($certiNumber)){
            $this->error = "身份证为空";
            return false;
        }elseif(!isCreditNo($certiNumber)){
            $this->error = "身份证格式错误";
            return false;
        }
        if($this->_query[$certiNumber]===null || $refresh){
            $this->_query[$certiNumber] = $this->queryUserInfs(["user_idNos"=>$certiNumber]);
        }
        if(false===$this->_query[$certiNumber]){
            $this->error = "身份证格式错误";
            return false;
        }
        return $this->_query[$certiNumber];
    }
    /*
     * 根据银行卡号查询用户信息
     * @param $certiNumber:身份证号
     * @param $refresh:是否刷新数据，默认是否
     * 
     * @return boolean|array
     */
    public function queryFromBankCard($bankCard,$refresh = false){
        if(empty($bankCard)){
            $this->error = "银行卡号为空";
            return false;
        }elseif(!isBankNo($bankCard)){
            $this->error = "银行卡号格式错误";
            return false;
        }
        if($this->_query[$bankCard]===null || $refresh){
            $this->_query[$bankCard] = $this->queryUserInfs(["user_bankCards"=>$bankCard]);
        }
        if(false===$this->_query[$bankCard]){
            $this->error = "银行卡号格式错误";
            return false;
        }
        return $this->_query[$bankCard];
    }
    
    /*
     * 用户信息查询
     *          ---适用于单用户查询
     *  @param $params:查询信息
     *              --手机号，身份证号，银行卡号可以三选一或者多选，不填字段明文可为空不能为null
     *          
     *          eg. $params['user_ids'] 根据手机号查询
     *              $params['user_idNos'] 根据身份证号查询
     *              $params['user_bankCards'] 根据银行卡查询
     * 
     *      @return boolean|array 
     *              数据正确时返回array,否则返回false
     *              用户绑定银行卡时返回用户信息，否则返回空数组
     *              1.返回false，表示数据校验错误（手机号、身份证号格式错误）
     *              2.返回空数据，表示未在富友绑定银行卡
     *              3.返回非空数组，表示已绑定银行卡
     * 返回示例：
     * array(20) {
            ["mobile_no"] => string(11) "18966444466"
            ["login_id"] => string(11) "18966444466"
            ["cust_nm"] => string(9) "佘语芙"
            ["certif_id"] => string(18) "542132199409038130"
            ["email"] => array(0) {}
            ["city_id"] => string(4) "3310"
            ["parent_bank_id"] => string(4) "0303"
            ["bank_nm"] => string(36) "中国光大银行杭州朝晖支行"
            ["capAcntNo"] => string(16) "6214923101062027"
            ["card_pwd_verify_st"] => array(0) {}
            ["id_nm_verify_st"] => array(0) {}
            ["contract_st"] => array(0) {}
            ["user_st"] => string(1) "1"
            ["reg_dt"] => string(8) "20171031"
            ["czTxSmsNotify"] => string(2) "00"
            ["amtOutSmsNotify"] => string(2) "00"
            ["amtInSmsNotify"] => string(2) "00"
            ["wtCzParam"] => string(2) "01"
            ["wtTxParam"] => string(2) "01"
            ["user_tp"] => string(1) "1"
          }
     */
    private $_query = [];
    protected function queryUserInfs($params){
        $currentUrl = $this->currentDomain."queryUserInfs_v2.action";
        $data = array();
        $data['mchnt_cd'] = $this->mchntCd;//商户代码,必填
        $data['mchnt_txn_ssn'] = $this->getTxnSsn();//流水号,必填
        $data['mchnt_txn_dt'] = date('Ymd');//交易日期,必填
        $data['user_idNos'] = (string)$params['user_idNos'];//待查询的身份证号,必填
        $data['user_bankCards'] = (string)$params['user_bankCards'];//银行卡号
        $data['user_ids'] = (string)$params['user_ids'];//注册手机号 
        $data['ver'] = $this->ver;
        $result=$this->postdata($data,$currentUrl);
        //验签
        $arrResult = $this->checkresult($result);
        if(false==$arrResult)return false;
        return (array)$arrResult["plain"]["results"]["result"];
    }
    /*
     * 查询余额
     * @param $certiNumber:身份证号
     * @return boolean|array
     *          1.成功时返回数组，表示查询成功
     *          2.否则返回false，表示未查询到账户
     * eg.返回示例
     * array(5) {
        ["user_id"] => string(11) "18966444466"//账号
        ["ct_balance"] => string(1) "0"   //总余额
        ["ca_balance"] => string(1) "0"   //可用余额
        ["cf_balance"] => string(1) "0"  //冻结余额
        ["cu_balance"] => string(1) "0" //未转结余额
      }
     */
    public function balance($certiNumber){
        if(false==($queryInfo = $this->queryFromCertiNumber($certiNumber))){
            return false;
        }
        $currentUrl = $this->currentDomain."BalanceAction.action";
        $data = array();
        $data['mchnt_cd'] = $this->mchntCd;//商户代码,必填
        $data['mchnt_txn_ssn'] = $this->getTxnSsn();//流水号,必填
        $data['mchnt_txn_dt'] = date("Ymd");//交易日期,必填
        $data['cust_no'] = $queryInfo['login_id'];//待查询的登录帐户,必填
        
        $result=$this->postdata($data,$currentUrl);
        //验签
        $arrResult = $this->checkresult($result);
        if(false==$arrResult)return false;
        return $arrResult["plain"]["results"]["result"];
    }
    
    
    /*
     * 
     * 初始化证书
     *      
     */
    private $certificatePath;
    private function getCertificatePath(){
        if($this->certificatePath===null){
            $this->certificatePath = ROOT_PATH."certificate".DS."fuyou";
        }
       return $this->certificatePath;
    }
    
    /*
     * 初始化属性
     *  
     */
    
    private function initConfig(){
        $config = $this->config()[$this->isDevelop===false?"debugConfig":"developConfig"];
        
        foreach($config as $pro=>$val){
            if(property_exists($this,$pro)){
                $this->$pro = $val;
            }
        }
        $this->privateKey = $this->getCertificatePath().DS.$this->privateKey;
        $this->publicKey = $this->getCertificatePath().DS.$this->publicKey;
    }
    
    /*
     * 签名加密
     * 	@parm    $data  string      待加密字符串
     *  @return  $sign  string      返回加密结果
     * */
    private function rsaSign($data) {
        if(!file_exists($this->privateKey)){
                $this->error = "私钥文件不存在";
                return false;
        }
        $priKey = file_get_contents($this->privateKey);
        $res = openssl_pkey_get_private($priKey);
        openssl_sign($data, $sign, $res);
        openssl_free_key($res);
        //base64编码
        $sign = base64_encode($sign);
        return $sign;  
    }
    /**
    * RSA验签
    * @param $data 待签名数据
    * @param $sign 要校对的的签名结果
    * return 验证结果  bool
    */
   private function rsaVerify($data, $sign)  {
        if(!file_exists($this->publicKey)){
            $this->error =  "公钥文件不存在！";
            return false;
        }
       $pubKey = file_get_contents($this->publicKey);
       $res = openssl_pkey_get_public($pubKey);
       $result = (bool)openssl_verify($data, base64_decode($sign), $res);
       openssl_free_key($res); 
       return $result;
   }
   //开始请求数据
   private function postdata($data,$url){
        ksort($data);//参数名的每一个值从a到z的顺序排序
        //dump($data);dump(implode('|',$data));
        $data['signature']=$this->rsaSign(implode('|',$data));
        return $this->curlpost($data, $url);
   }
   
    /*
     * HTTP——curl-post    
     *     @parm  	$data    array    待传输数据
     *     @parm  	$url     string   请求接口地址
     *     @return  $result  xml      响应结果
     * */
    private function curlpost($data,$url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        return $result;
    }
   
    /*
     * 对接口返回结果进行验证
     *  @parm    $data xml 待验证数据
     *  @return  array | bool
     */
    private function checkresult($data)
    {
        $arr=json_decode(json_encode(simplexml_load_string($data)),true);
        $str="/\<plain\>(.*?)\<\/plain\>/";
        preg_match_all($str ,$data,$matches);
        if($this->rsaVerify($matches[0][0],$arr['signature'])){
        	if('0000'!=$arr['plain']['resp_code']){
        		$code_info = $this->getCode($arr['plain']['resp_code']);
        		$this->error = "({$code_info['code']}){$code_info['msg_user']}";
        		return false;
        	}
            return $arr;
        }else{
            $this->error = "验签失败";
            return false;
        }
    }
    
    /*
	 * 富友 错误代码对应的含义
	 * return array
	 * */
	public function getCode($code){
		$return = array('code'=>$code,'msg'=>'系统异常[智信]','msg_user'=>'系统异常[智信]');
		$codeModel = $this->db()->table("fuyou_errorcode")->where(["code"=>$code])->find();
		if(false==$codeModel){
			return $return;
		}
                $codeInfo = $codeModel->toArray();
		$return['code'] = $code;
		$return['msg'] = $codeInfo['code_msg'];
		$return['msg_user'] = $codeInfo['msg_user'];
		return $return;
	}
        public function getError() {
            return $this->error?self::PAY_NAME.$this->error:'';
        }
        //流水号,流水号只能是纯数字
        private function getTxnSsn(){
            return date("YmdHis")."_".rand(1111,9999);
        }
    
}
