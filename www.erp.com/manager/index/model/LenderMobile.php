<?php
/*
 * 申请表手机版订单
 */
namespace manager\index\model;

class LenderMobile  extends Common{
    //protected $token;
    public  $loanid;//订单id
    protected $table = "lender";
    protected $rule = [
        ["token",'require','msg'=>'token不能为空','scene'=>['add']],
        ["names",'require','msg'=>'姓名不能为空','scene'=>['add']],
        ["certi_number",'require','msg'=>'身份证号不能为空','scene'=>['add']],
        ["mobile",'require','msg'=>'手机号不能为空','scene'=>['add']],
        ["car_number",'require','msg'=>'车牌号不能为空','scene'=>['add']],
        ["sms_code",'require','msg'=>'验证码不能为空','scene'=>['add']],
    ];
    /*
     * 添加订单
     */
    public function addLender($data){
        $this->data($data);
        if($this->checkValidate($data,"add")){
            $data["timeadd"] = date("Y-m-d H:i:s");
            $data["sales_man"] = $this->getSellerNames();
            if(true===$this->checkUnique()){
                $this->error = "您已添加过此车牌的订单";
                return false;
            }
            
            $stores = UserStore::getStores($this->sellerUid);
            if(empty($stores)){$this->error = "您的登录账号未关联门店，请联系管理员";return false;}
            if(count($stores)>1){$this->error = "您的登录账号关联多个门店，不可新增订单";return false;}
            $data["storeid"] = $stores[0]["storeid"];
            
            //校验验证码
            $smsModel = new Sms(["mobile"=>$this->mobile,"location"=>"LenderMobile","smsCode"=>$this->sms_code]);
            if(false==$smsModel->checkVerify()){
                $this->error = $smsModel->getError();
                return false;
            }
            
            $loanModel = new Loan();
            $lenderModel = new Lender();
            $carReportModel = new CarReport();
            
            $data["loan_sn"] = $loanModel->createSnFromStore($data["storeid"]);
            $this->db()->startTrans();
            
            if(!$loanModel->allowField(true)->save($data)){
                $this->db()->rollback();
                $this->error="新增失败(101)";
                return false;
            }
            
            $this->data("id", $loanModel->id);
            if(!$lenderModel->allowField(true)->save(["loanid"=>$loanModel->id,"timeadd"=>date("Y-m-d H:i:s")])){
                $this->db()->rollback();
                $this->error="新增失败(102)";
                return false;
            }
            
            if(!$carReportModel->allowField(true)->save(["car_number"=>$this->car_number],["loanid"=>$loanModel->id])){
                $this->db()->rollback();
                $this->error="新增失败(103)";
                return false;
            }
            $this->db()->commit();
            return true;
        }
        return false;
    }
    /*
     * 发送验证码
     *  @param $mobile:手机号
     *  @return boolean
     */
    public function sendSmsCode($mobile){
        $smsModel = new Sms(["mobile"=>$mobile,"location"=>"LenderMobile"]);
        if(false==$smsModel->sendVerify()){
            $this->error = $smsModel->getError();
            return false;
        }
        return true;
    }
    
    //检测订单是否有结果
    protected  function checkUnique(){
        $db = $this->db();
        $db->select("l.id");
        $db->table("loan l,car_report c");
        $db->where("l.id=c.loanid");
        $db->where("l.names='{$this->names}'");
        $db->where("l.certi_number='{$this->certi_number}'");
        $db->where("c.car_number='{$this->car_number}'");
        return (boolean)$db->find();
    }






    /*
     * 订单列表
     * 
     *      eg.(new LenderModel)->loanList(["isPage"=>1]);
     *          
     * 
     *  @return array
     */
    public function loanList($params = []){
        $list = [];
        if(!empty($this->getSellerNames())){
            $loanModel = new Loan();
            $carReportModel = new CarReport();
            $loanModel->where["salesMan"] = $this->sellerNames;
            $loanModel->order = "l.timeadd desc,le.timeadd desc";
            if(!empty($this->loanid)){
                $loanModel->where["id"] = $this->loanid;
            }
            $list = $loanModel->loanList($params);
            foreach($list as &$loan){
                $carReportInfo = $carReportModel->carReportOne($loan["id"]);
                $loan["car_number"] = $carReportInfo["car_number"];
                $loan["yearMonth"] = date("Y年m月",strtotime($loan["timeadd"]));
            }
        }
        return (array)$this->changeList($list);
    }
    /*
     * 订单详情
     *    eg.
     *      $lenderModel = new loanMobile();
     *      $lenderModel->loanid = 2;//订单主键id
     *      $lenderModel->loanOne();
     * 
     * return array
     */
    public function loanOne(){
        if(empty($this->loanid))return [];
        return $this->loanList();
    }
    
    /*
     * 获取销售员UID
     * @return string
     */
    private $sellerNames;
    private $sellerUid;
    public function getSellerNames(){
        if(empty($this->getSellerInfo()) )return '';
        if($this->sellerNames)return $this->sellerNames;
        $userModel = new User();
        $userModel->where["certi_number"] = (string)$this->sellerInfo["member_info"]["staff_info"]["certiNumber"];
        $userInfo = $userModel->userList();
        $this->sellerUid = (string)$userInfo[0]["id"];
        return $this->sellerNames = (string)$userInfo[0]["names"];
    }
    
    /*
     * 获取销售员信息
     *      ---根据借吧Service-token（接口-memberinfo） 获取
     * @return array
     */
    private $sellerInfo;
    public function getSellerInfo(){
        if(!empty($this->sellerInfo))return $this->sellerInfo;
        if($this->token===null){$this->error="token为空";return false;}
        $url = config("view_replace_str")['jieba_service'];
        $cmd = "member_changeuserinfo";
        $type="member_info";
        $params = array(
   			'_deviceid_'=>uniqid(),//设备id
   			'_client_'=>'BROWSER',//设备类型，browser表示微信
   			'_sign_'=>md5("BROWSER|123456|{$cmd}"),//签名
   			'_token_'=>$this->token,
                        '_cmd_'=>$cmd,
                        'type'=>$type,
   		);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_TIMEOUT,20);   //只需要设置一个秒的数量就可以
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data,true);
        if($data["errorcode"]!==0){
            $this->sellerInfo = [];
        }else{
            $this->sellerInfo = $data["dataresult"];
        }
        return $this->sellerInfo;                
    }
    
    private function changeList($list){
        $threeList = [];
        $timeList = array_values(array_unique(array_column($list,"yearMonth")));
        foreach($timeList as $key=>$date){
            $threeList[]["yearMonth"] =$date;
            foreach($list as $k=>$loan){
                if($loan["yearMonth"]==$date){
                    $threeList[$key]["list"][] = $loan;
                    unset($list[$k]);
                }
            }
        }
        return $threeList;
    }
    
}
