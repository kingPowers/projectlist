<?php
/*
 * 订单管理
 * 
 */

namespace manager\index\model;
use manager\index\model\Common;
class Loan  extends Common{
    const TO_BE_SUBMITED = 1;const TO_BE_SUBMITED_MSG = "待提交";
    
    const TO_BE_QUOTED = 2;const TO_BE_QUOTED_MSG = "待报价";
    
    const TO_BE_SINGLE = 3;const TO_BE_SINGLE_MSG = "待分单";
    
    const TO_BE_PEND = 10;const TO_BE_PEND_MSG = "待初审";
    
    const TO_DOOR_FEEDBACK = 4;const TO_DOOR_FEEDBACK_MSG = "初审拒单，等待门店反馈";
    
    const TO_PEND_FEEDBACK = 5;const TO_PEND_FEEDBACK_MSG = "等待初审反馈";
    
    const TO_BE_FINAL_PEND = 6;const TO_BE_FINAL_PEND_MSG = "等待终审";
    
    const TO_DOOR_FINAL_FEEDBACK = 7;const TO_DOOR_FINAL_FEEDBACK_MSG = "等待门店复议";
    
    const TO_PEND_FINAL_FEEDBACK = 8;const TO_PEND_FINAL_FEEDBACK_MSG = "等待终审复议";
    
    const TO_BE_FINAL2 = 9;const TO_BE_FINAL2_MSG = "终审通过，等待门店处理";
    
    const TO_BE_FINAL3 = 11;const TO_BE_FINAL3_MSG = "复议通过，等待门店处理";
    
    const TO_BE_CONTRACT = 12;const TO_BE_CONTRACT_MSG = "风控通过，待确认产品";
    
    const TO_BE_PRODUCT = 13;const TO_BE_PRODUCT_MSG = "产品已定，待签合同";
    
    const TO_BE_PAYMONEY = 14; const TO_BE_PAYMONEY_MSG = "等待放款";
  
    const LOAN_DEAL = 1;     //订单处理中
    const LOAN_SUCCESS = 2;//已成单
    const LOAN_FAIL = 3;  //订单失败
    
    const LOAN_FAIL_DOOR = 1;//门店拒单
    
    const LOAN_FAIL_FINAL = 2;//终审拒单
    
    protected $table="loan";
    
    protected $rule=[
        ["purpose",'require','msg'=>'请填写借款用途','scene'=>['add','edit']],
        ["names",'require','msg'=>'客户姓名不能为空','scene'=>['add','edit']],
        ["mobile",'require','msg'=>'客户手机号不能为空','scene'=>['add','edit']],
        ['mobile','checkMobile:modelcustom-\manager\index\model\Loan','电话号码不正确','scene'=>['add','edit']],
        ["certi_number",'require','msg'=>'客户身份证号不能为空','scene'=>['add','edit']],
        ['certi_number','checkCertiNumber:modelcustom-\manager\index\model\Loan','身份证不正确','scene'=>['add','edit']],
        ["product",'require','msg'=>'产品不能为空','scene'=>['add','edit']],
        ["periodes",'require','msg'=>'借款期限不能为空','scene'=>['add','edit']],
        ["money",'require','msg'=>'贷款金额不能为空','scene'=>['add','edit']],
        ["money",'float','msg'=>'贷款金额不正确','scene'=>['add','edit']],
        // ["sales_man",'require','msg'=>'经办客户经理不能为空','scene'=>['add','edit']],
        // ["signtime",'require','msg'=>'请填写借款人签署日期','scene'=>['add','edit']],
        ["lender_source","require","msg"=>"请选择客户来源",'scene'=>['add','edit']],
        
    ];
    public function checkCertiNumber ($value,$rule,$data,$field,$title)
    {
        if (false == isCreditNo($value)) {
            return "身份证格式不正确";
        }
        return true;
    }
    public function checkMobile ($value,$rule)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)==false){
            return '手机号格式不正确';
        }
        return true;
    }
    /*
     * 数据校验
     */
    public function checkMyValidate(&$data, $scene = null, $batch = null) {
        
        if(empty($uid= User::getUid())){$this->error = "你的登录已超时";return false;}
        $stores = UserStore::getStores($uid);
        if(empty($stores)){$this->error = "您的登录账号未关联门店，请联系管理员";return FALSE;}
        if(count($stores)>1){$this->error = "您的登录账号关联多个门店，不可新增订单";return false;}
        
        if (empty($data['id']) || (false == ($loanModel = static::get($data['id'])))) {//新增
            $data["material"] = 1;
            $data['signtime'] = date("Y-m-d");
            $data["timeadd"] = $data["lasttime"] = date("Y-m-d H:i:s");
            $data["storeid"] = $stores[0]["storeid"];
            $data["loan_sn"] = $this->createSnFromStore($data["storeid"]);
        }
        $data['sales_man'] = User::getUserInfo('names');  
        return parent::checkValidate($data, $scene, $batch);
    }
    /*
     * 添加材料标识
     *  @param $loanId:订单id
     *  @param $mateSign:材料标识 
     *      1：面审表；2：征信报告；3;网查信息；4：人员证件照；
     *      5：银行流水；6：其他相关证照；7：车辆证照；8：车辆评估报告；
     *      9：违章查询；10保险及理赔查询；11：车辆其他相关证照    12：申请表
     * @return boolean
     */
    public static function addMaterial($loanId,$mateSign){
        $loanModel = Loan::get($loanId);
        $material = $loanModel->getData('material');
        if(!empty($material) && !in_array($mateSign,explode(",",$material))){
            return (boolean)$loanModel->save(["material"=>$material.",{$mateSign}"]);
        }elseif(empty($material)){
            return (boolean)$loanModel->save(["material"=>$mateSign]);
        }
        return true;
    }
    
    
    /*
     * 订单列表
     *  @param $params 参数数组
     *  @return array  二维数组
     *          
     *              eg. 
     *                 $loan = new Loan(); 
     *                 $loan->where['names']='张三';//查询
     *                 $loan->order = '';           //排序
     *                 $loan->loanList(['isPage'=>1]);//分页列表
     */
    public function loanList($params = []){
        $db = db($this->table);
        //dump($db);
        //exit;
        $where = [];
        //id
        if(isset($this->where["id"])&& !empty($this->where["id"])){
            $db->where("l.id",$this->where["id"]);
        }
        //门店sn编码
        if(isset($this->where["loan_sn"])&& !empty($this->where["loan_sn"])){
            $db->where("l.loan_sn","like","{$this->where["loan_sn"]}%");
        }
        //门店id
        if(isset($this->where["storeId"])&& !empty($this->where["storeId"])){
            $db->where("l.storeid",$this->where["storeId"]);
        }
        //初审人员id
        if(isset($this->where["pendUid"])&& !empty($this->where["pendUid"])){
            $db->where("l.pend_uid",$this->where["pendUid"]);
        }
        //销售人员姓名
        if(isset($this->where["salesMan"])&& !empty($this->where["salesMan"])){
            $db->where("l.sales_man",$this->where["salesMan"]);
        }
        //录单人员名称
        if(isset($this->where["lenderAuthor"])&& !empty($this->where["lenderAuthor"])){
            $db->where("le.author",$this->where["lenderAuthor"]);
        }
        //姓名
        if(isset($this->where["names"])&& !empty($this->where["names"])){
            $db->where("l.names","like","%{$this->where["names"]}%");
        }
        //电话
        if(isset($this->where["mobile"])&& !empty($this->where["mobile"])){
            $db->where("l.mobile",$this->where["mobile"]);
        }
        //身份证号
        if(isset($this->where["certi_number"])&& !empty($this->where["certi_number"])){
            $db->where("l.certi_number",$this->where["certi_number"]);
        }
        //门店名称
        if(isset($this->where["storeName"]) && !empty($this->where["storeName"])){
            $db->where("s.name","like","%{$this->where["storeName"]}%");
        }
        //开始时间
        if(isset($this->where["startTime"]) && !empty($this->where["startTime"])){
            $db->where("l.timeadd",">=",$this->where["startTime"]);
        }
        //结束时间
        if(isset($this->where["endTime"]) && !empty($this->where["endTime"])){
            $db->where("l.timeadd","<=",$this->where["endTime"]);
        }
        //订单状态
        if(isset($this->where["status"]) && !empty($this->where["status"])){
            $db->where("l.status",$this->where["status"]);
        }
        //订单处理进程
        if(isset($this->where["process_status"]) && !empty($this->where["process_status"])){
            $db->where("l.process_status",$this->where["process_status"]);
        }
        //去除loanid搜选项
        if(isset($this->where["exceptLoanid"]) && !empty($this->where["exceptLoanid"])) {
            $db->where("l.id",'neq',$this->where["exceptLoanid"]);
        }
        //排序
        if(isset($this->order) && !empty($this->order)){
            $db->order($this->order);
        }
        
        //字段
        $db->field("l.*,le.*,s.id as store_id,s.name,"
                . "case when l.status=1 and l.process_status=".self::TO_BE_SUBMITED." then '".self::TO_BE_SUBMITED_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_QUOTED." then '".self::TO_BE_QUOTED_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_SINGLE." then '".self::TO_BE_SINGLE_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_DOOR_FEEDBACK." then '".self::TO_DOOR_FEEDBACK_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_PEND_FEEDBACK." then '".self::TO_PEND_FEEDBACK_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_FINAL_PEND." then '".self::TO_BE_FINAL_PEND_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_DOOR_FINAL_FEEDBACK." then '".self::TO_DOOR_FINAL_FEEDBACK_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_PEND_FINAL_FEEDBACK." then '".self::TO_PEND_FINAL_FEEDBACK_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_FINAL2." then '".self::TO_BE_FINAL2_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_PEND." then '".self::TO_BE_PEND_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_FINAL3." then '".self::TO_BE_FINAL3_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_CONTRACT." then '".self::TO_BE_CONTRACT_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_PRODUCT." then '".self::TO_BE_PRODUCT_MSG."' "
                . " when l.status=1 and l.process_status=".self::TO_BE_PAYMONEY." then '".self::TO_BE_PAYMONEY_MSG."' "
                . " when l.status=2 then '已成单' "
                . " when l.status=3 and l.deny_status=".self::LOAN_FAIL_DOOR." then '门店拒单' "
                . " when l.status=3 and l.deny_status=".self::LOAN_FAIL_FINAL." then '终审拒单' "
                . "end as status_name"); 
        
        $db->table("loan l,store s,lender le");
        
        $db->where("l.storeid=s.id and l.id=le.loanid");
        
        //分页
        if(isset($params["isPage"]) && $params["isPage"]==1){
            $pageDb = clone $db;
            $page = $pageDb->paginate(15,false,['query'=>request()->param()]);
            $this->page = $page->render();
            $db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
        }
        
        $list = (array)$db->select();
        foreach($list as &$val){
            $val = array_merge($val,(array)json_decode($val["live_info"],true),
                            (array)json_decode($val["house"],true),
                            (array)json_decode($val["career_info"],true),
                            (array)json_decode($val["contact_info"],true)
                    );
            unset($val["live_info"],$val["career_info"],$val["contact_info"]);
        }//return $db->getLastSql();
        return $list;
    }
    /*
     * 创建编号
     *  @param $sn:sn编号
     *  @return string 编号
     */
    public function createSn($sn=null){
        if(!is_null($sn) && !static::get(["loan_sn"=>$sn])){
            return $sn;
        }
        $sn = "";
        for($i=0;$i<10;$i++){
            $sn = "sx".date("YmdHis");
            if(!static::get(["loan_sn"=>$sn]))break;
        }
        return $sn;
    }
    /*
     * 根据门店生成编码
     * @param $storeId 门店ID
     */
    public function createSnFromStore($storeId){
        $storeModel = new Store();
        $storeModel->where["id"] = $storeId;
        $storeInfo = $storeModel->storeList()[0];
        if(false==$storeInfo){
            return $this->createSn();
        }else{
            /*
             * 编码规则：A01 20171023 0001
             */
            $loanModel = new Loan();
            $loanModel->where["loan_sn"] = $storeInfo["number"];
            $loanModel->order = "l.id desc";
            $loanInfo = $loanModel->loanList()[0];
            $lastSn  = intval(substr($loanInfo["loan_sn"], strlen($storeInfo["number"])+8));
            $newLoanSn = $storeInfo["number"].date("Ymd").str_pad($lastSn+1,4,0,STR_PAD_LEFT);
            for($i=0;$i<10;$i++){
                if(!static::get(["loan_sn"=>$newLoanSn]))break;
                $lastSn++;
            }
            return $newLoanSn;
        }
    }
}
