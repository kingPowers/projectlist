<?php
/*
 * 平安保险管理
 */
class InsuranceOrder{
    
    const STATUS_APPLY = "等待确认";
    const STATUS_CONFIRM = "已提交，待反馈";
    const STATUS_PAY = "等待付款";
    const STATUS_BILL = "已付款，待传保单";
    const STATUS_SUCCESS = "已成单";
    const STATUS_PERSONAL = "客户拒单";
    const STATUS_CUSTOMER = "客服拒单";
    const STATUS_INSURANCE = "保险人员拒单";
    
    
    public $memberId;//会员ID
    public $smsCode;//验证码
    public $carType;//车子类别  1：公司车   2：个人车
    public $storeName;//门店名称
    public $insuranceType;//险种
    public $address;//收件地址
    public $recMobile;//推荐人手机号
    
    private $memberInfo;//会员信息
    private $error;//错误信息
    private $orderInfo;//订单信息
    private $static_domain;//static域名
    private $wwwDomain;//www域名
    private $saveImgPath;//上传图片的路径
    private $smsCacheKey = "insuranceOrder";//验证码存储key
    
    public function __construct($config = []) {
        $this->static_domain = "http://static.".DOMAIN_ROOT;
        $this->saveImgPath = FRAME_PATH."../static/Upload/insurance/";
        $this->wwwDomain = "http://www.".DOMAIN_ROOT;
        $this->configure($config);
    }
    
    private function configure($config = []){
        if(!empty($config)){
            $reflection = new ReflectionClass($this);
            foreach($reflection->getProperties() as $proper){
                $properName = $proper->getName();
                if(!isset($config[$properName]))continue;
                if($proper->isPublic()){
                    $this->$properName = $config[$properName];
                }else{
                    $this->__set($properName,$config[$properName]);
                }
            }
        }
    }
    public function __set($name, $value) {
        $setter = "set$name";
        if(method_exists($this,$setter)){
            return $this->$setter($name,$value);
        }else{
            //throw new Exception("设置一个未知的属性：$name");
        }
    }
    
    public function __get($name) {
        $getter = "get$name";
        if(method_exists($this,$setter)){
            return $this->$getter($name);
        }else{
            throw new Exception("读取一个未知的属性：$name");
        }
    }


    /*
     * 订单列表
     *  @param array $params  查询条件,包括where,order
    *  @param  int   $page    页数， 规定：-1表示不分页
    *  @param  int   $number  每页的条数  默认为12
     * @return array
    * 
    *        eg.
    *        $params = [
    *            "where"=>["companyname"=>$companyName,],//where查询条件
    *            "order"=>"",//排序方式
    *        ];
     */
   public function orderList($params = [],$page = -1,$number = 12){
        $paramWhere = isset($params["where"])?$params["where"]:[];
        $order = isset($params["order"])?$params["order"]:"ins.timeadd desc";//" ins.status asc,ins.timeadd desc,ins.status_process desc ";
        //申请时间
        if(!empty($paramWhere["startTime"]) && empty($paramWhere["endTime"])){
            $where["ins.timeadd"] = ["egt",$paramWhere["startTime"]];
        }elseif(empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["ins.timeadd"] = ["elt",$paramWhere["endTime"]];
        }elseif(!empty($paramWhere["startTime"]) && !empty($paramWhere["endTime"])){
            $where["ins.timeadd"] = ["between",[$paramWhere["startTime"],$paramWhere["endTime"]]];
        }
        //门店
        if(isset($paramWhere["store_name"]) && !empty($paramWhere["store_name"])){
            $where["ins.store_name"] =["like","%{$paramWhere["store_name"]}%"];
        }
        //手机号
        if(isset($paramWhere["mobile"]) && !empty($paramWhere["mobile"])){
            $where["m.mobile"] =["like","%{$paramWhere["mobile"]}%"];
        }
        //memberID
        if(isset($paramWhere["memberId"]) && !empty($paramWhere["memberId"])){
            $where["m.id"] =$paramWhere["memberId"];
        }
        //姓名
        if(isset($paramWhere["names"]) && !empty($paramWhere["names"])){
            $where["mi.names"] = ["like","%{$paramWhere["names"]}%"];
        }
        //订单ID
        if(isset($paramWhere["id"]) && !empty($paramWhere["id"])){
            $where["ins.id"] = $paramWhere["id"];
        }
        //订单状态
        if(isset($paramWhere["status"]) && !empty($paramWhere["status"])){
            $where["ins.status"] = $paramWhere["status"];
        }
        if(!empty($paramWhere["status"]) && $paramWhere["status"]==1){
            if($paramWhere["is_pay"]==1){
                $where["ins.status_process"] = 3;
            }
        }
        //付款方式
        if (isset($paramWhere["pay_type"]) && !empty($paramWhere["pay_type"])) {
          $where['pay_type'] = $paramWhere['pay_type'];
        }
        //进度状态
        if(isset($paramWhere["status_process"]) && !empty($paramWhere["status_process"])){
            $where["ins.status_process"] = $paramWhere["status_process"];
        }
        //拒单状态
        if(isset($paramWhere["status_deny"]) && !empty($paramWhere["status_deny"])){
            $where["ins.status_deny"] = $paramWhere["status_deny"];
        }
        $statusName = "case  "
                . "when ins.status=1 and ins.status_process=1 then '".self::STATUS_APPLY."' "
                . "when ins.status=1 and ins.status_process=2 then '".self::STATUS_CONFIRM."' "
                . "when ins.status=1 and ins.status_process=3 then '".self::STATUS_PAY."' "
                . "when ins.status=1 and ins.status_process=4 then '".self::STATUS_BILL."' "
                . "when ins.status=2  then '".self::STATUS_SUCCESS."' "
                . "when ins.status=3 and ins.status_deny=1 then '".self::STATUS_PERSONAL."' "
                . "when ins.status=3 and ins.status_deny=2 then '".self::STATUS_CUSTOMER."' "
                . "when ins.status=3 and ins.status_deny=3 then '".self::STATUS_INSURANCE."' "
                . "end as statusName,";
        
        $where["_string"] = "m.id=mi.memberid "
                . "and m.id=ins.memberid  ";
        $fields = "ins.*,$statusName"
                . "mi.names,mi.certiNumber,mi.nameStatus,"
                . "m.mobile";
        $table = "member m,member_info mi,insurance_order ins";
        
        $limit = $page===-1?"":($page-1)*$number.",".$number;
        $count = M()->table($table)
                        ->where($where)
                        ->field($fields)
                        ->order($order)
                        ->count();
        $list = (array)M()->table($table)
                            ->where($where)
                            ->field($fields)
                            ->order($order)
                            ->limit($limit)
                            ->select();
        $domain = $this->static_domain."/Upload/insurance/";
        foreach($list as &$val){
            if(!empty($val["certinumber_pic"]))$val["certinumber_pic"] = $domain.$val["certinumber_pic"];
            if(!empty($val["drive_license_pic"]))$val["drive_license_pic"] = $domain.$val["drive_license_pic"];
            if(!empty($val["service_license_pic"]))$val["service_license_pic"] = $domain.$val["service_license_pic"];
            if(!empty($val["price_pic"]))$val["price_pic"] = $domain.$val["price_pic"];
            if(!empty($val["bill_pic"])){
                $arrPic = json_decode($val["bill_pic"],true);
                $arrPic["force_bill_pic"] = $domain.$arrPic["force_bill_pic"];
                $arrPic["business_bill_pic"] = $domain.$arrPic["business_bill_pic"];
                $val["bill_pic"] = $arrPic;
                $val["bill_pic1"] = [0=>["bill_pic"=>$arrPic["force_bill_pic"],"bill_sn"=>$arrPic["force_bill_sn"],"bill_name"=>"强制险"],1=>["bill_pic"=>$arrPic["business_bill_pic"],"bill_sn"=>$arrPic["business_bill_sn"],"bill_name"=>"商业险"]];
            }else{
                $val["bill_pic"] = [];
                $val["bill_pic1"] = [0=>["bill_pic"=>"","bill_sn"=>"","bill_name"=>""],1=>["bill_pic"=>"","bill_sn"=>"","bill_name"=>""]];
            }
        }
        return ["count"=>intval($count),"list"=>$list];    
   }
   /*
    * 订单详情
    *   @param $id:订单ID，insurance_order 主键
    *   @return array
    */
   public function orderDetail($id){
       $list = $this->orderInfo($id);
       if(empty($list))return [];
       //订单进度
       $processList = (array)M("insurance_order_process")->where(["order_id"=>$id])->order("timeadd desc")->select();
       foreach($processList as &$process){
           $process["timeadd"] = date("Y年m月d日",strtotime($process["timeadd"]));
       }
       $list["process"] = $processList;
       $list["is_eqian"] = (is_file(UPLOADPATH."allot/{$id}_1.pdf") || is_file(UPLOADPATH."allot/{$id}_2.pdf"))?1:0;
       $list["eqian_fenqi"] = is_file(UPLOADPATH."allot/{$id}_2.pdf")?1:0;
       $list["eqian_quane"] = is_file(UPLOADPATH."allot/{$id}_1.pdf")?1:0;
       $list["payUrl"] = $this->wwwDomain."/allot/pay";
       $list['bus_discount'] = 1;//商业险折扣
       if (time <= strtotime("2017-10-31 23:59:59")) {
          $list['bus_discount'] = 0.9;
       }
       return $list;
   }
   /* 订单信息
    * @param $id:订单ID，insurance_order 主键
    *   @return array
    */
   public function orderInfo($id){
       if($this->orderInfo[$id])return $this->orderInfo[$id];
       $list = $this->orderList(["where"=>["id"=>$id]],-1);
       if(empty($list)){
           $this->error = "订单信息为空";
           return [];
       }
       import("Think.ORG.Util.InsuranceInstalment");
       $list["list"][0]["is_instalment"] = (new InsuranceInstalment())->instalmentOrderList(['order_id'=>$id])?1:0;
       return $this->orderInfo[$id] = $list["list"][0];
   }
   /*
    * 添加订单
    */
   public function addOrder(){
       if(!$this->beforeAddOrder())return false;
       //上传照片
       if($this->carType==1){
           $certinumber = "";
           $service = $this->uploadImage("service_license_pic");
            if(false==$service)return false;
       }else{
           $service = "";
           $certinumber = $this->uploadImage("certinumber_pic");
           if(false==$certinumber)return false;
       }
       $driveLicense = $this->uploadImage("drive_license_pic");
       if(false==$driveLicense)return false;
       $addData = [
           "memberid"=>$this->memberId,
           "order_sn"=>time().rand(111,999),
           "rec_mobile"=>$this->recMobile,
           "car_type"=>$this->carType,
           "store_name"=>$this->storeName,
           "insurance_type"=>$this->insuranceType,
           "certinumber_pic"=>basename($certinumber),
           "drive_license_pic"=>basename($driveLicense),
           "service_license_pic"=>basename($service),
           "address"=>$this->address,
       ];
       if($addId = M("insurance_order")->add($addData)){
            return $addId;
       }else{
           $this->error = "订单未申请成功";
           return false;
       }
   }
   /*
    * 修改订单到提交
    *       --“提交”操作，待处理订单到保险人员待接单
    *   @param $id:订单ID
    *   return boolean
    */
   public function editToSubmit($id){
       $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==1 && $orderInfo["status_process"]==1){
           return $this->editOrder($id,["status_process"=>2,"message"=>"已提交保险公司，待反馈"]);
       }else{
           $this->error = "订单未处于 ‘".self::STATUS_APPLY."’";
           return false;
       }
   }
   //上传报价单
   public function uploadPricePic($id){
       $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==1 && $orderInfo["status_process"]==2){
           if(round($orderInfo["insurance_money"],2)<=0){$this->error = "请先编辑报价单";return false;}
           if(empty($_FILES["price_pic"]["name"])){$this->error = "请选择报价单图片";return false;}
           $pricePic = $this->uploadImage("price_pic");
           if(false==$pricePic)return false;
          
           $saveData = [
               "status_process"=>3,
               "message"=>"待客户付款，保险金".($orderInfo["force_money"]+$orderInfo["business_money"])."元",
               "price_pic"=>basename($pricePic),
           ];
           return $this->editOrder($id,$saveData);
       }else{
           $this->error = "订单未处于 ‘".self::STATUS_CONFIRM."’";
           return false;
       }
   }
   /*
    *修改订单到待付款 
    *       --“编辑”操作，待接单订单到待付款中
    */
   public function editToPay($id,$data = []){
       if(!$this->beforeEditToPay($id, $data))return false;
       $totalMoney = $data["force_money"]+$data["business_money"];
       $saveData = [
           //"status_process"=>3,
           "message"=>"保险公司已接单",
           "force_money"=>$data["force_money"],
           "business_money"=>$data["business_money"],
           "taxation_rate"=>$data["taxation_rate"],
           "profit_point"=>$data["profit_point"],
           "insurance_money"=>round($totalMoney-($totalMoney-$data["force_money"])*(100-$data["taxation_rate"])*$data["profit_point"]/10000,2),
       ];
       return $this->editOrder($id,$saveData,true);
   }
   /*
    *修改订单到待上传保单
    *           ---‘确认付款’操作； 待付款订单到已付款
    */
   public function editToBill($id,$data = []){
       $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==1 && $orderInfo["status_process"]==3){
           return $this->editOrder($id, ["status_process"=>4,"message"=>"已付款，待出保单"]);
       }else{
           $this->error = "订单未处于 ‘".self::STATUS_PAY."’";
           return false;
       }
   }
   /*
    * 修改订单到成功
    *       --‘上传保单’操作，上传保单后订单完成，成单
    */
   public function editToSuccess($id,$data = []){
        $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==1 && $orderInfo["status_process"]==4){
           if(empty($data["force_bill_sn"])){$this->error = "请填写强险保单编号";return false;}
           if(empty($data["business_bill_sn"])){$this->error = "请填写商业险保单编号";return false;}
           if(empty($_FILES["force_bill_pic"]["name"])){$this->error = "请选择强险保单图片";return false;}
           if(empty($_FILES["business_bill_pic"]["name"])){$this->error = "请选择商业险保单图片";return false;}
           $businessBillPic = $this->uploadImage("business_bill_pic");
           if(false==$businessBillPic)return false;
           $forceBillPic = $this->uploadImage("force_bill_pic");
           if(false==$forceBillPic)return false;
           $arrBill = [
                        "force_bill_pic"=>basename($forceBillPic),
                        "business_bill_pic"=>basename($businessBillPic),
                        "force_bill_sn"=>$data["force_bill_sn"],
                        "business_bill_sn"=>$data["business_bill_sn"],
                      ];
           return $this->editOrder($id, ["status_process"=>5,"bill_pic"=>json_encode($arrBill),"status"=>2,"message"=>"已出保单，订单完成"]);
       }else{
           $this->error = "订单未处于 ‘".self::STATUS_BILL."’";
           return false;
       }
   }
   /*
    * 拒单
    *   param $id:订单ID
    *   param $type:拒单人分类  1：客户拒单   2：客服拒单   3：保险人员拒单 4:系统自动拒单
    *   return boolean
    */
   public function editToDeny($id,$type,$params = []){
       if(empty($params["message"])){$this->error = "请填写拒单理由";return false;}
       return $this->editOrder($id, ["status"=>3,"status_deny"=>$type,"message"=>"已拒单","deny_message"=>$params["message"]]);
   }
   
   //下载压缩包文件
   public function uploadZip($id,$ignoreImgs = []){
       $orderInfo = $this->orderInfo($id);
       if(empty($orderInfo))return false;
       $dir = $this->saveImgPath."zip/";
       if(!is_dir($dir))mkdir($dir,0755);
       $filename = $dir."{$orderInfo["names"]}_{$id}_Img.zip";
       $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
      if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) { 
          $this->error = "文件冲突";
          return false;
      }
      $addToZip = ["certinumber_pic","drive_license_pic","service_license_pic"];
      if(count($ignoreImgs)>0)$addToZip = array_diff ($addToZip, $ignoreImgs);
      
      foreach($addToZip as $picAttr){
          if(!empty($orderInfo[$picAttr]) && is_file($this->saveImgPath.basename($orderInfo[$picAttr]))){
              $zip->addFile($this->saveImgPath.basename($orderInfo[$picAttr]),basename($orderInfo[$picAttr]));
          }
      }
      
      $zip->close();
      return $this->static_domain."/Upload/insurance/zip/".basename($filename);
   }
  
   protected function beforeEditToPay($id,$data = []){
       $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==1 && $orderInfo["status_process"]==2){
           //if(empty($orderInfo["price_pic"])){$this->error = "请先上传报价单照片";return false;}
           $notEmpty = ["force_money"=>"强制险金额不能为空",
                        "business_money"=>"商业险金额不能为空",
                        "taxation_rate"=>"税费不能为空",
                        "profit_point"=>"返点不能为空",
               ];
           foreach($notEmpty as $attribute=>$message){
               if(empty($data[$attribute])){
                   $this->error = $message;
                   return false;
               }
           }
           if($data["taxation_rate"]>=100){$this->error = "税费百分比值不正确";return false;}
           if($data["profit_point"]>=100){$this->error = "返点百分比值不正确";return false;}
           return true;
       }else{
           $this->error = "订单未处于 ‘".self::STATUS_CONFIRM."’";
           return false;
       }
       
   }

   //修改订单
   protected function editOrder($id,$data = [],$addProcess = true){
       if(!$this->beforeEditOrder($id))return false;
       $trans = false;
    	try{
            if (!D('insurance_order')->inTrans()) {
                D('insurance_order')->startTrans();
                $trans = true;
            }
            
            if($addProcess==true &&  false==$this->orderProcess($id, $data["message"])){
                throw new Exception("操作失败:101");
            }
           
            if(false==M("insurance_order")->where(["id"=>$id])->save($data)){
                throw new Exception("操作失败：102");
            }
            if ($trans) {
                D('insurance_order')->commit();
            }
            return true;
        }catch (Exception $ex) {
            $this->error = $ex->getMessage();
            if ($trans) {
                D('insurance_order')->rollback();
            }
            return false;
        }
   }
   
   private function beforeEditOrder($id){
       $orderInfo = $this->orderInfo($id);
       if($orderInfo["status"]==2){
           $this->error = "该订单".self::STATUS_SUCCESS;
           return false;
       }elseif($orderInfo["status"]==3){
           $this->error = "该订单已被拒";
           return false;
       }
       return true;
   }


   //添加订单之前
   protected function beforeAddOrder(){
       $notEmpty = ["memberId"=>"会员ID为空","carType"=>"请选择个人车/公司车","smsCode"=>"请输入验证码",
           //"storeName"=>"请输入门店名称",
           "insuranceType"=>"请选择申请险种"];
       foreach ($notEmpty as $key=>$message){
           if(false==$this->$key){
               $this->error = $message;
               return false;
           }
       }
       //if(!in_array($this->storeName,$this->stores())){$this->error = "门店名称不正确";return false;}
      // $isForceInsurance = false;$fiveInsurance = $handredInsurance = $thirdInsurance = false;
       foreach(explode(",",(string)$this->insuranceType) as $insurance){
           if(!in_array($insurance,$this->insurances())){
               $this->error = "申请险种不正确";
               return false;
           }
       }
       
       if(empty($this->address)){$this->error = "请填写联系地址";return false;}
       if(FALSE===$this->nameStatus()){
           $this->error = "会员未实名认证";
           return false;
       }
       
       if($this->carType==1){//公司车
           if(empty($_FILES["service_license_pic"])){$this->error = "请选择营业证照片";return FALSE;}
       }else{
           if(empty($_FILES["certinumber_pic"])){$this->error = "请选择身份证照片";return FALSE;}
       }
       if(empty($_FILES["drive_license_pic"])){$this->error = "请选择行驶证照片";return FALSE;}
       if(false===$this->recommandMobile())return false;
       if(false===$this->checkSms()){
           return false;
       }
       return true;
   }
   //推荐手机号
   protected function recommandMobile(){
       if($this->recMobile){
           if(preg_match('/^1[0-9]{10}$/',$this->recMobile)==false){
               $this->error = "推荐人手机号格式不正确";
               return false;
           }
           $memberInfo = $this->getMemberInfo();
           if($memberInfo["mobile"]==$this->recMobile){
               $this->error = "推荐人不能是自己手机号";
               return false;
           }
       }
       return true;
   }


   //发送验证码
   public function sendSms(){
        $memberInfo = $this->getMemberInfo();
        if(false==$memberInfo)return FALSE;
        if(false!=S($this->smsCacheKey.$memberInfo["mobile"]."Flag")){
            $this->error = "您点击的太频繁了";
            return false;
        }
        import('Think.ORG.Util.SMS');
        if(false==SMS::buildverify($memberInfo["mobile"])){
             $this->error = '验证码发送失败';
             return false;
        }
        S($this->smsCacheKey.$memberInfo["mobile"],session("smscode"),60*10);
        S($this->smsCacheKey.$memberInfo["mobile"]."Flag", 1,60);
        return true;
   }
   //检查验证码是否正确
   protected function checkSms(){
        $memberInfo = $this->getMemberInfo();
        if(false==$memberInfo)return FALSE;
        
        if(empty(S($this->smsCacheKey.$memberInfo["mobile"]))){
            $this->error = "请先获取验证码";            
            return false;
        }
        if(md5($this->smsCode)==S($this->smsCacheKey.$memberInfo["mobile"])){
            S($this->smsCacheKey."Flag",null);
            S($this->smsCacheKey.$memberInfo["mobile"],null);
            return true;
        }else{
             $this->error = "验证码不正确";
            return false;
        }
   }
   //申请订单的数据
   public function applyData($searchKey = ""){
       $result = [];
       $memberInfo = $this->getMemberInfo();
       if(false==$memberInfo)return $result;
       $result["names"] = $memberInfo["names"];
       $result["certiNumber"] = $memberInfo["certiNumber"];
       $result["insurance"] = $this->insurances();
       if(empty($searchKey)){
           $result["stores"] = [];
       }else{
            $result["stores"] = $this->stores($searchKey);
       }
       return $result;
   }
   //申请的险种
   public function  insurances(){
       return [
           "交强险",
           "机动车损失险",
           "机动车第三者责任保险(100万)",
           "机动车全车盗抢保险",
           "不计免赔",
       ];
   }
   
           
   //门店列表
   public function stores($searchKey = ""){
       $where = ["type"=>2,"status"=>1];
       if(!empty($searchKey))$where["name"] = ["like","%{$searchKey}%"];
       $storeList = M("store")->where($where)->select();
       if(false!=$storeList){
           $stores = array_column($storeList,"name");
           array_push($stores,"无");
       }else{
           $stores[] = "无";
       }
       return $stores;
       /*return [
            "常熟门店",
            "宜兴门店",
            "常州门店",
            "太仓门店",
            "江阴门店",
            "南京门店",
            "绍兴门店",
            "南通门店",
            "厦门门店",
            "合肥门店",
            "江西门店",
            "山西门店",
            "苏州门店",
            "无锡门店",
            "蚌埠门店",
            "贵阳门店",
            "成都门店",
            "兴义门店",
            "重庆门店",
            "凯里门店",
            "仟帆门店",
            "嘉兴门店",
        ];*/
   
   }

      //是否实名认证
   public function nameStatus(){
       $memberInfo = $this->getMemberInfo();
       if(false==$memberInfo || $memberInfo["nameStatus"]==0)return false;
       return true;
   }
   
   public function getMemberInfo(){
       if(false!=$this->memberInfo)return $this->memberInfo;
       if($this->memberId===null)return false;
       return $this->memberInfo = (array)M("member_info i,member m")->field("m.mobile,i.*")->where(["i.memberid"=>$this->memberId,"_string"=>"i.memberid=m.id"])->find();
   }
   
   public function getError(){
       return $this->error;
   }
   private function uploadImage($fileName,$params = []){
       $config = [
           "imageExts"=>["jpg","gif","png","jpeg"],
           "maxSize"=>"10485760",//10M,
           "savePath"=>$this->saveImgPath,
           "uploadReplace"=>true,
           "saveRule"=>time().rand(111,999).rand(11,99),
           ];
       $config = array_merge($config,(array)$params);
        if(!empty($_FILES[$fileName]["name"])){
            import('ORG.Net.UploadFile');
            $uploadObject = new UploadFile();
            $uploadObject->maxSize = $config["maxSize"];
            $uploadObject->allowExts = $config["imageExts"];
            $uploadObject->savePath = $config["savePath"];
            $uploadObject->uploadReplace = $config["uploadReplace"];
            $uploadObject->saveRule = $config["saveRule"];
            if(false==($info = $uploadObject->uploadOne($_FILES[$fileName]))){
                $this->error = $uploadObject->getErrorMsg();
                return false; 
            }else{
                return $info[0]["savepath"].$info[0]['savename'];
            }
        }
        $this->error = "未选择上传文件";
        return false;
    }
    
    
    
  //--------------订单进度------------------------------
  protected function  orderProcess($order_id,$message){
      $orderInfo = $this->orderInfo($id);
      if(empty($orderInfo))return false;
      $addData = [
          "order_id"=>$order_id,
          "status_process"=>$orderInfo["status_process"],
          "status_deny"=>$orderInfo["status_deny"],
          "message"=>$message,
      ];
      $where = $addData;
      if(false!=M("insurance_order_process")->where($where)->find())return true;
      return M("insurance_order_process")->add($addData);
  }
  
    
}