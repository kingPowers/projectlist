<?php
/*
 * 产品期数计算
 *      eg. 1万元 12期，5%的年化利率
 *          Issue::getIssueEqual(10000,12,5);
 */
namespace manager\index\model;
class Issue  extends Common{
    
    public static $isIntPay = false;//取整支付
    
    public static $totalRate = 0;//利息和
    
    public static $totalMonthMoney = 0;//本息和

    protected  $table="";
    
    /*
     * 等额本息产品计算（区别于等额本金还款）
     * @param $loanMoney:借款金额
     * @param $month:月份
     * @param $yearRate:年化利率
     * @return array
     */
    public static function getIssueEqual($loanMoney,$month,$yearRate){
        $list = [];$i = 1;
        $monthRate = $yearRate/100/12;
        //每月月供额
        $perMonthMoney = self::issueEqualFormula($loanMoney, $month, $yearRate);
        self::$totalMonthMoney = round($perMonthMoney*$month,2);
        self::$totalRate = self::$totalMonthMoney-$loanMoney;
        
        $K = self::issueListKey();
        $list[0] = array_fill_keys($K,0);
        $list[0]["beginMoney"] = $list[0]["residueMoney"] = $loanMoney;
        while ($i <=$month) {
            $E = [];
            $D = $list[$i - 1]['residueMoney'];//剩余借款额
            $E["periode"] = $i;//期数
            $E['monthMoney'] = sprintf("%1\$.2f", $perMonthMoney);//每月月供
            $E['rateMoney'] = sprintf("%1\$.2f", $D * $monthRate);//每月利息
            $E['perLoanMoney'] = sprintf("%1\$.2f", $E['monthMoney'] - $E['rateMoney']);//还款本金
            $E['residueMoney'] = $i==$month?0:sprintf("%1\$.2f", $D - $E['perLoanMoney']);//期末本金余额
            $E["beginMoney"] = $D;//期初本金余额
            array_push($list, $E);
            $i++;
        }
        
        return self::intPay($list);
    }
    //等额本息计算公式
    public static function  issueEqualFormula($loanMoney,$month,$yearRate){
       $monthRate = $yearRate/100/12;
       return  $loanMoney * $monthRate * pow(1 + $monthRate, $month) / ( pow(1 + $monthRate, $month) - 1 );
    }
    
    
    /*
     * 先息后本产品计算
     * @param $loanMoney:借款金额
     * @param $month:月份
     * @param $yearRate:年化利率
     * @return array
     */
    public static function getIssueFirstRate($loanMoney,$month,$yearRate){
        $list = [];$i=1;
        $rateMoney = self::issueFirstRateFormula($loanMoney, $month, $yearRate);
        self::$totalMonthMoney = $loanMoney + $rateMoney * $month;
        self::$totalRate = $rateMoney*$month;
        
        $K = self::issueListKey();
        $list[0] = array_fill_keys($K,0);
        $list[0]["beginMoney"] = $list[0]["residueMoney"] = $loanMoney;
        while ($i <= $month) {
            $monthMoney = $i==$month?$loanMoney+$rateMoney:$rateMoney;
            $E['periode'] = $i;//期数
            $E['monthMoney'] = sprintf("%1\$.2f", $monthMoney);//每月月供
            $E['rateMoney'] = sprintf("%1\$.2f",$rateMoney);//每月利息
            $E['perLoanMoney'] = sprintf("%1\$.2f", $E['monthMoney']-$E['rateMoney']);//还款本金
            $E["beginMoney"] = $list[$i-1]["residueMoney"];//期初本金余额
            $E['residueMoney'] = sprintf("%1\$.2f",$E["beginMoney"]-$E['perLoanMoney']);//期末本金余额
            $i++;
            array_push($list, $E);
        }
        return self::intPay($list);
    }
    //付息还本计算公式
    public static function issueFirstRateFormula($loanMoney,$month,$yearRate){
        $monthRate = $yearRate/100/12;
        return round($loanMoney * $monthRate,2);
    }
    
    /*
     * Excel PV公式
     * * @param $loanMoney:借款金额
     * @param $month:月份
     * @param $yearRate:年利率 ，单位：%
     * @param $compMonthRate:综合月利率,单位：%
     * @return float
     */
    public static function excelPv($loanMoney,$month,$yearRate,$compMonthRate){
        $perMonthMoney = -($loanMoney*$compMonthRate/100+$loanMoney/$month);
        $monthRate = $yearRate/12/100;
        $pow = pow(1+$monthRate,$month);
        $pv = ((1-$pow)/$monthRate)*$perMonthMoney/$pow;
        return round($pv,2);
    }
    //列表key
    public static function issueListKey(){
        return [
            "periode",//期数
            "monthMoney",//每月月供
            "rateMoney",//每月利息
            "perLoanMoney",//还款本金
            "beginMoney",//期初本金余额
            "residueMoney",//期末本金余额
        ];
    }
    
    
    
    
    /*
     * 还款计划每期还款取整,最后一期全部还掉
     * 
     */
    private static function intPay($moneyList = []){
        if(!self::$isIntPay)return $moneyList;
        $pointMoney = 0;
        foreach($moneyList as &$list){
            $pointMoney+=$list["monthMoney"]-intval($list["monthMoney"]);
            $list["monthMoney"] = intval($list["monthMoney"]);
        }
        $moneyList[count($moneyList)-1]["monthMoney"]+=$pointMoney;
        return $moneyList;
    }
    
}
