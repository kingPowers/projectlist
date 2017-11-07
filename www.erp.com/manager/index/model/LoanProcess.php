<?php
/*
 * 订单进度管理
 */
namespace manager\index\model;

class LoanProcess  extends Common{
    protected $table="loan_process";
    
    protected $rule = [
        ["message","require","msg"=>"进度说明(message)不能为空","scene"=>["add"]],
        ["loanid","require","msg"=>"loanid不能为空","scene"=>["add"]],
    ];
    
    /*
     * 新增进度
     *  @param $data:新增的数据
     *  @return boolean
     * 
     */
    public static function addLoanProcess($data){
        $loanProcessModel = new LoanProcess();
        $loanProcessModel->auto(["timeadd"=>date("Y-m-d H:i:s")]);
        if($loanProcessModel->checkValidate($data,"add") && $loanProcessModel->save($data)){
            return true;
        }else{
            return false;
        }
    }
}
