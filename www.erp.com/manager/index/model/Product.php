<?php
/*
 * 产品管理
 */
namespace manager\index\model;

abstract class Product extends Common{
    const PRODUCT_NAME = "";
    const  REPAYMENT_EQUAL = 1;//等额本息
    
    const REPAYMENT_FIRST = 2;//付息还本
    
    public  $loanMoney;//借款金额
    
    public $yearRate;//年化利率
    
    public  $breachRate = 5;//提前结清费率
    
    public  $compMonthRate = 0;//综合月利率
    
    public  $contactRate=0;//合同金额利率
    
    public  $month;//借款期数
    
    public  $gpsL = 150;//GPS流量费（月）
    
    public  $gpsS = 1000;//GPS设备费用（含意外险）
    
    public  $gpsZ = 400;//GPS责任险
    
    public  $insteadCredit = 1000;//代拉征信费用
    
    public  $startTime;//起息日期
    
    public $repaymentType;//还款方式  1：等额本息  2：付息还本
    
    protected  $contactMoney;//合同金额
    
    protected  $arrivalMoney;//到账金额
    
    protected function initialize() {
        parent::initialize();
        $attributes = $this->attributes();
        foreach($this->getProductInfo() as $attrName=>$attrValue){
            in_array($attrName,$attributes)?($this->$attrName = $attrValue):'';
        }
    }
    //获取产品信息
    public function getProductInfo(){
        $config = (new \think\db\Query())->table($this->table)->where(["name"=> static::PRODUCT_NAME])->find();
        return [
            "repaymentType"=>$config["repayment_type"],//还款方式
            "month"=>$config["month"],//期数
            "contactRate"=>$config["contact_rate"],//合同金额利率，单位：%
            "yearRate"=>$config["year_rate"],//年利率,单位：%
            "compMonthRate"=>$config["comp_month_rate"],//综合月利率,单位：%
            "breachRate"=>$config["breach_rate"],//提前结清费率单位：%
            "gpsL"=>$config["gpsL"],//GPS流量费（月）
            "gpsS"=>$config["gpsS"],//GPS设备费用（含意外险）
            "gpsZ"=>$config["gpsZ"],//GPS责任险
            "startTime"=>date("Y-m-d"),//起息日期
        ];
        
    }
    

    //产品默认配置
    public function config(){
        return [];
    }
   /*
    * 产品列表
    *   @return array
    */ 
    private $productList;
    public function productList(){
       if($this->productList)return $this->productList;
       $this->getContactMoney();
       if($this->repaymentType== self::REPAYMENT_EQUAL){
           $list = Issue::getIssueEqual($this->contactMoney, $this->month, $this->yearRate);
       }elseif($this->repaymentType== self::REPAYMENT_FIRST){
           $list = Issue::getIssueFirstRate($this->contactMoney, $this->month, $this->yearRate);
       }else{
           $list = [];
       }
       $this->beforeProductList($list);
       foreach($list as $key=>&$product){
           $product["payTime"] = $this->payTimeList()[$key];//付款日期
           $product["GPSL"] = $key==0?0:$this->gpsL;//GPS流量费
           $product["serviceMoney"] = $this->serviceMoney()[$key];//服务费
           $this->calculateProduct($product);//某一期数据处理
       }
       $this->breachMoney($list);//提前还款违约金
       $this->afterProductList($list);
       return $this->productList = $list;
    }
    
    protected function beforeProductList(&$list){}
    protected function afterProductList(&$list){}
    
    /*
     * 提前结清
     *  @param $list:还款计划
     *  @return array $list 
     */
     protected function breachMoney(&$list){
         foreach($list as &$product){
             //余下的GPS流量费
             $restGPSL = array_sum(array_column(array_slice($list,$product["periode"]),"GPSL"));
             //余下的服务费
             $restServiceMoney = array_sum(array_column(array_slice($list,$product["periode"]),"serviceMoney"));
             //违约金
             $breachMoney = round($product["residueMoney"]*$this->breachRate/100,2);
             
             $product["earlySettle"] = [
               "restGPSL"=>$restGPSL,
               "restServiceMoney"=>$restServiceMoney,
                "breachMoney"=>$breachMoney,
                 "totalMoney"=>$restGPSL+$restServiceMoney+$breachMoney,
             ];
         }
     }
    /*
     * 服务费
     * @return array 
     */
     protected function serviceMoney(){
         return array_pad([],$this->month+1,0);
     }
    /*
     * 某一期数据处理
     *  @param array $product 某一期还款计划
     *  @return float
     */
     protected function calculateProduct(&$product){
         $product["monthMoney"] = $product["rateMoney"]+$product["perLoanMoney"];
         $product["preRepaymentMoney"] = $product["monthMoney"]+$product["serviceMoney"]+$product["GPSL"];
     }

    /*
     * 到账金额
     *      到账金额=借款金额-GPS流量费（月）-GPS设备费用-GPS责任险
     */
    public function  getArrivalMoney(){
        if($this->arrivalMoney===null){
            $this->arrivalMoney = $this->loanMoney-$this->gpsS-$this->gpsZ;
        }
        return $this->arrivalMoney;
    }
    /*
     * 合同金额
     *      合同金额=借款金额*（1+合同利率）
     */
    public function getContactMoney(){
        if($this->contactMoney===null){
            $this->contactMoney = $this->loanMoney*(1+$this->contactRate/100);
        }
        return $this->contactMoney;
    }
    
    
    
    /*
     * 类的属性列表
     * @return array
     */
    public function attributes(){
        $names = [];
        $class = new \ReflectionClass($this);
        foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property){
            if(!$property->isStatic()){
                $names[] = $property->getName();
            }
        }
        return $names;
    }
    /*
     * 结束日期
     */
    public function getEndTime(){
        return $this->payTimeList()[$this->month];
    }
    /*
     * 应收金额
     */
    public function getRepaymentMoney(){
        return array_sum(array_column($this->productList(),"preRepaymentMoney"));
    }
    /*
     * 月利率
     *      --利息(月)
     */
    public function getMonthRate(){
        return round(array_sum(array_column($this->productList(),"rateMoney"))/$this->month/$this->loanMoney*100,2);
    }
    
    //还款日期列表
    protected $payTimeList;
    protected function payTimeList(){
        if($this->payTimeList===null){
            $this->payTimeList = [$this->startTime];
            for($i=1;$i<=$this->month;$i++){
                $nextDate = date("Y-m-d",strtotime("+{$i} months",strtotime($this->startTime)));
                $nextDay = date('j',strtotime($nextDate));
                if($nextDay!=date('j',strtotime($this->startTime))){
                    $nextDate = date('Y-m-d', strtotime("-{$nextDay} days", strtotime($nextDate)));
                }
                $this->payTimeList[] = $nextDate;
            }
        }
        return $this->payTimeList;
    }
    
    //还款方式
    public function repaymentList(){
        return [
            self::REPAYMENT_EQUAL=>"等额本息",
            self::REPAYMENT_FIRST=>"付息还本",
        ];
    }
}
