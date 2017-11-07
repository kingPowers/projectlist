<?php
/*
 * 智车贷(险资类)-1712
 */

namespace manager\index\model;

class ProductXZ1712 extends Product{
    protected $table = "product";
    const PRODUCT_NAME = "智车宝(险资类)-1712";
    public function config() {
        return [
            "repaymentType"=>1,
            "month"=>12,//期数
            "contactRate"=>5,//合同额利率，单位：%
            "yearRate"=>8,//年利率,单位：%
            "compMonthRate"=>0.9,//综合月利率,单位：%
            "breachRate"=>5,//提前结清费率单位：%
            "gpsL"=>"150",//GPS流量费（月）
            "gpsS"=>"1000",//GPS设备费用（含意外险）
            "gpsZ"=>"400",//GPS责任险
            "startTime"=>date("Y-m-d"),//起息日期
        ];
    }
    
    
    /*
     * 合同金额
     */
    public function getContactMoney() {
        if($this->contactMoney===null){
            $this->contactMoney = $this->loanMoney*(1+$this->contactRate/100);
        }
        return $this->contactMoney;
    }
    
    //服务费=[综合月利率*借款金额*期数-利息和]/2
    protected function serviceMoney() {
        $serviceMoneyList = parent::serviceMoney();
        
        $serviceMoneyList[1] = $serviceMoneyList[2] =
        round(($this->loanMoney*$this->compMonthRate*$this->month/100-Issue::$totalRate)/2,2);
        return $serviceMoneyList;
    }
    

}
