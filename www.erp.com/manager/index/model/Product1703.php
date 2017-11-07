<?php
/*
 * 智车贷-1703
 *  
 */
namespace manager\index\model;

class Product1703 extends Product{
    protected $table = "product";

    const PRODUCT_NAME = "智车宝-1703";
    
    public function config() {
       return [
            "repaymentType"=>2,//还款方式
            "month"=>3,//期数
            "contactRate"=>0,//合同金额利率，单位：%
            "yearRate"=>0.67*12,//年利率,单位：%
            "compMonthRate"=>1.83,//综合月利率,单位：%
            "breachRate"=>5,//提前结清费率单位：%
            "gpsL"=>150,//GPS流量费（月）
            "gpsS"=>1000,//GPS设备费用（含意外险）
            "gpsZ"=>400,//GPS责任险
            "startTime"=>date("Y-m-d"),//起息日期
        ];
    }
    
    protected function afterProductList(&$list) {
        $list[0]["rateMoney"] = $list[3]["rateMoney"];$list[3]["rateMoney"] = 0;
        $list[0]["GPSL"] = $list[3]["GPSL"];$list[3]["GPSL"] = 0;
        $this->calculateProduct($list[0]);
        $this->calculateProduct($list[3]);
    }
    
    /*
     * 服务费
     * @return array
     */
    protected function serviceMoney() {
        $serviceMoneyList = parent::serviceMoney();
        $serviceMoneyList[0] = $serviceMoneyList[1] = $serviceMoneyList[2] = $this->contactMoney*$this->compMonthRate/100;
        return $serviceMoneyList;
    }

}
