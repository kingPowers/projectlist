<?php
namespace manager\index\model;

abstract  class Eqian  extends Common{
    public $project_id = '1111563517';
    public $project_secret = '95439b0863c241c63a861b87d1e647b7';
    
    public $certiNumber;//用户个人身份证号
    
    public $companyName;//公司名称
    
    protected $sign;//eqian对象
    protected $personAccountId;//用户accountId
    protected $companyAccountId;//企业accountId
    protected $personImageBase64;//用户个人章照片base64
    protected $companyImageBase64;//企业照片base64
    protected $memberInfo;//用户信息
    protected $companyInfo;//企业信息
    
    public function __construct($certiNumber = null,$companyName = null,$data = []) {
        parent::__construct($data);
        $this->certiNumber = $certiNumber;
        $this->companyName = $companyName;
        $this->bootstrap();
    }
     /*
     * init项目初始化
     * $return boolean
     * */
     protected function bootstrap() {
        if($this->sign===null){
            vendor('Eqian.eSignOpenAPI');
            vendor('Eqian.core.eSign');
            vendor('Eqian.core.ErrorConstant');
            \tech\core\eSign::$_CONFIG = $this->config();
            $this->sign = new \tech\core\eSign();
            $this->sign->init();
        } 
        return $this->sign;
    }
    
    public function config(){
        return [
            "project_id"=>$this->project_id,
            "project_secret"=>$this->project_secret,
        ];
    }
    
     /**
     * 普通用户签章
      *         ---个人账户签章
      *         --支持多个文档同时签章（上限10个）
      *         $file = [
      *                 0=>["file"=>"c:/aa.pdf",
      *                     "key"=>"甲方盖章"
      *                     ],
      *                 1=>["file"=>"c:/a.pdf","key"=>"甲方盖章"],

      *                 ],
      *         eg.$eqian->userMultiSignPDF($file,000000);
      * 
      * 
     * @parm  	$signFiles:待签署的文件数组
      *             eg.
      *                 [
      *                 0=>["file"=>"c:/aa.pdf",
      *                     "key"=>"甲方盖章"
      *                     ]
      *                 ],
      * 
      * @param string $key:关键字 
      *             eg.甲方【盖章】，pdf中所有出现此关键字的地方都会被盖上章
      * 
     *  @param $code:获取的验证码
      * 
      * @param $imageSavePath:印章图片保存路径
      * 
     * @return boolean  成功签章返回true,否则返回false
     * 			
     */
    public function userMultiSignPDF($signFiles = [],$code,$imageSavePath = null){
        //生成个人印章
        if(false==($image64=$this->addTemplateSeal())){
            return false;
        }
        //生成图片印章
        $this->savePicSign($image64,$imageSavePath?$imageSavePath: dirname($signFiles[0]["file"])."/{$this->memberInfo["certiNumber"]}.png");
        /*
         * 开始签章
         *      @param string $accountId  账户唯一ID，必须
         *      @param array $signParams 待签章的文件数组，必须
         *      @param string $image64  印章图片字符串，必须传
         *      $mobile 接收验证码的手机号，可为空
         *      $code  验证码
         */
        $signType = "Key";//关键字签章
        $signParams = [];
        foreach($signFiles as $k=>$val){
          if(!is_file($val["file"])){$this->error = "文件不存在{$val["file"]}";return false;}
          $fileBean = ["srcPdfFile"=>$val["file"],"dstPdfFile"=>$val["file"],"fileName"=> basename($val["file"]),"ownerPassword"=>""];
          $signPos = ["posPage"=>'',"posX"=>"120","posY"=>"","key"=>$val["key"],"width"=>""];//签章位置
          $signParams[$k]  = ["signType"=>$signType,"fileBean"=>$fileBean,"signPos"=>$signPos];
        }
        $result = $this->sign->userMutilSignPDF($this->personAccountId, $signParams,$image64, $mobile = '', $code);
        
        $returnResult = "";
        foreach($result["failList"] as $k=>$errors){
            if($errors["errCode"]!=0){
                $returnResult.="第{$k}个文件签约失败:{$errors["msg"]};";
            }
        }
        if($returnResult===""){
            return true;
        }else{
            $this->error = $returnResult;
            return false;
        }
    }
    /*
     * 用户发送验证码
     *      @return boolean
     *      验证码发送成功，返回true,否则返回false 
     */
    public function sendSignCode(){
        if(false==($accountId = $this->addPersonAccount())){
            return false;
        }
        $result = $this->sign->sendSignCode($accountId);
        if($result["errCode"]==0){
            return true;
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
  
    
    /*
     * 注销个人账户
     *   --需要传递个人账户数据
     * 
     */
    public function delUserAccount(){
        if(false==($accountId = $this->addPersonAccount())){
            return false;
        }
        $result = $this->sign->delUserAccount($accountId);
        if($result['errCode']==0){
            return true;
        }else{
            $this->error = $result['msg'];
            return false;
        }
    }

    /*
     * 公司、个人  单个文件盖章
     *      $files = ['srcFile'=>"","key"=>""]
     *      $this->selfSignPDF();
     *      
     *    @param $id:个人或公司ID
     *    @param array  $files:待签署的文件
     *    @param $key:关键字
     *    @param $type:member|company  个人用户   合作公司 
     *   
     */
    public function selfSignPDF($files,$templateType,$type = "member"){
        if($type=="member"){//个人账户
             if(false==($accountId = $this->addPersonAccount()))return false;
             $image64=$this->addTemplateSeal($templateType);
        }elseif($type=="company"){//公司账户
            if(false==($accountId = $this->addOrganizeAccount()))return false;
            $image64=$this->addCompanyTemplateSeal($templateType);
            return false;
        }
        $signFile = ['srcPdfFile' =>$files["srcFile"],'dstPdfFile' => $files["srcFile"] ,'fileName' => "",'ownerPassword' => ' ',];
        $signPos = ['posPage' =>"",'posX' =>"120" ,'posY' =>"",'key' =>$files["key"],'width' =>"",];
        $result = $this->sign->userSignPDF($accountId,$signFile,$signPos,"Key",$image64);
        if($result["errCode"]==0){
            return true;
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
    /*
     * 获取用户信息
     * @return array
     *      eg.array(
     *          "names"=>"张三",//不可空
     *          "certiNumber"=>"身份证号",//不可空
     *          "mobile"=>"手机号",//不可空
     *          );
     *      
     *      eg. public function getMemberInfo(){
     *              return $this->memberInfo = [
     *                    "names"=>"张三",//不可空
                          "certiNumber"=>"身份证号",//不可空
                          "mobile"=>"手机号",//不可空
     *              ];
     *          }
     */
    abstract public function getMemberInfo();
    /*
     * 获取公司信息
     *  @return array
     *        [
     *          "merge"=>0//是否三证合一  1：是  0：否
     *          "company_code"=>"组织机构代码" 不可空
     *          "legal_mobile"=>"企业关联手机号" 不可空
     *          "companyname"=>"企业名称" 不可空
     *      ];
     *     eg.
     *          public function getCompanyInfo(){
     *              return $this->companyInfo = [
     *                     "merge"=>0,//是否三证合一  1：是  0：否
                           "company_code"=>"组织机构代码", //组织机构代码号、多证合一或工商注册码不可空
                           "legal_mobile"=>"企业关联手机号",// 不可空
                           "companyname"=>"企业名称", //不可空
     *              ];
     *          }     
     *      
     */
    abstract public function getCompanyInfo();




    /**
     * @return bool|string 
     *          成功时返回个人账户ID标识
     *          失败时返回false
     * 创建个人账户
     */
    public function addPersonAccount(){
        if(false==$this->getMemberInfo()){
            return false;
        }
        if(empty($this->memberInfo["names"])){$this->error = "用户姓名为空";return false;}
        if(empty($this->memberInfo["mobile"])){$this->error = "用户手机号为空";return false;}    
        if(empty($this->memberInfo["certiNumber"])){$this->error = "用户身份证号为空";return false;}
        if($this->personAccountId)return $this->personAccountId;
        /*
         * 手机号 不可空 ，姓名 不可空，身份证号 不可空
         */
        $return = $this->sign->addPersonAccount($this->memberInfo['mobile'],$this->memberInfo['names'],$this->memberInfo['certiNumber']);
        if($return['errCode']==0){
            return $this->personAccountId = $return['accountId'];
        }else{
           $this->error = $return['msg'];
           return false;
        }
    }

    /**
     * 创建个人模板印章
     * @param $templateType 印章的类型   矩形:rectangle|正方形:square|艺术字:fzkc
     *         公司章则传输：star，标准公章
     * @return bool
     */
    public function addTemplateSeal($templateType = "rectangle"){
        if(false==$this->addPersonAccount()){
            return false;
        }
        if($this->personImageBase64)return $this->personImageBase64;
        //参数说明 账户标识   印章类型   印章颜色
        $return = $this->sign->addTemplateSeal($this->personAccountId,$templateType,"red");
        if($return['errCode']==0){
            return $this->personImageBase64 = $return["imageBase64"];
        }else{
            $this->error = $return['msg'];
            return false;
        }
        
    }
    /*
     * 创建企业账户
     */
    public function addOrganizeAccount(){
        if(false==$this->getCompanyInfo()){
            return false;
        }
        if(empty($this->companyInfo["company_code"])){$this->error = "组织机构代码为空:company_code";return false;}
        if(empty($this->companyInfo["legal_mobile"])){$this->error = "企业手机号为空:legal_mobile";return false;}
        if(empty($this->companyInfo["companyname"])){$this->error = "企业名称为空:companyname";return false;}
        //参数说明：机构名称    组织机构代码号    企业注册类型
        //$regType 表示是否三证合一
        $regType = $this->companyInfo["merge"]?1:0;//组织机构类型  0：组织机构代码号   1：多证合一
        $result = $this->sign->addOrganizeAccount($this->companyInfo["legal_mobile"],$this->companyInfo["companyname"],$this->companyInfo["company_code"],$regType);
        if($result["errCode"]==0){
            return $this->companyAccountId = $result["accountId"];
        }else{
            $this->error = $result["msg"];
            return false;
        }
    }
    
    /**
     * 创建企业模板印章
     * @param $templateType 印章的类型   star:标准公章 | oval:椭圆形印章
     * @return bool | string
     */
    public function addCompanyTemplateSeal($templateType = "star"){
        if(false==$this->addOrganizeAccount()){
            return false;
        }
        if($this->companyImageBase64)return $this->companyImageBase64;
        //参数说明 账户标识   印章类型   印章颜色
        $return = $this->sign->addTemplateSeal($this->companyAccountId,$templateType,"red");
        if($return['errCode']==0){
            return $this->companyImageBase64 = $return["imageBase64"];
        }else{
            $this->error = $return['msg'];
            return false;
        }
        
    }
    
    /*
     * 写入图片
     *      个人或公司章转换为图片形式
     *    @param $image64  字符串形式的base64图片
     *    @param $savePath  保存路径
     */
    public function savePicSign($image64,$savePath){
        if(is_file($savePath) && false!=($fileInfo = fstat(fopen($savePath, "r")))){
            if(time()<$fileInfo["mtime"]+7*24*3600){
                return true;
            }
        }
        $result = file_put_contents($savePath,base64_decode($image64));
        if($result){
            //此处可添加图片裁切等
            return true;
        }
        return false;
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
 
}
