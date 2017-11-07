<?php
/*
 * 初审终审管理
 * 
 */
namespace manager\index\model;
class LoanReview extends Common{
    const FIRST_SUCCESS = 1;const FIRST_SUCCESS_MSG = "初审通过";
    const FIRST_DENY = 2;const FIRST_DENY_MSG = "初审拒单";
    const FINAL_SUCCESS = 1;const FINAL_SUCCESS_MSG = "终审通过";
    const FINAL_DENY = 2;const FINAL_DENY_MSG = "终审拒单";
    const REPEAT_SUCCESS = 1;const REPEAT_SUCCESS_MSG = "复议通过";
    const REPEAT_DENY = 2;const REPEAT_DENY_MSG = "复议拒单";
    
    public $first_status;
    public $repeat_status;
    public $final_status;
    protected $table = "loan_review";
    protected $rule = [
        /*
        * 格式：[字段名称，验证名称，key=>val, ... ],
        *       msg：错误的提示信息   scene:表示场景名称
         *      firstReview:初审评审场景   finalRepeat:终审评审场景
         */
        ["loanid",'require','msg'=>'订单id不能为空','scene'=>['firstReview']],
        ["borrow_purpose",'require','msg'=>'借款用途不能为空','scene'=>['firstReview']],
        ["letter_liability",'require','msg'=>'个人资信及负债情况不能为空','scene'=>['firstReview']],
        ["work_experience",'require','msg'=>'工作情况及从业经验不能为空','scene'=>['firstReview']],
        ["family_asset",'require','msg'=>'家庭资产情况不能为空','scene'=>['firstReview']],
        ["repayment_ability",'require','msg'=>'还款能力及还款计划不能为空','scene'=>['firstReview']],
        ["other_income",'require','msg'=>'其他还款来源不能为空','scene'=>['firstReview']],
        ["remark",'require','msg'=>'备注不能为空','scene'=>['firstReview']],
        
        ["first_status",'require','msg'=>'请选择评审状态（通过|拒单）','scene'=>['firstReview']],
        ["first_status",'in:1,2','msg'=>'评审状态值不正确（通过|拒单）','scene'=>['firstReview']],
        
        
        ["final_status",'require','msg'=>'请选择评审状态（通过|拒单）','scene'=>['finalRepeat']],
        ["final_status",'in:1,2','msg'=>'评审状态值不正确（通过|拒单）','scene'=>['finalRepeat']],
        
        ["repeat_status",'require','msg'=>'请选择评审状态（通过|拒单）','scene'=>['repeatFinalRepeat']],
        ["repeat_status",'in:1,2','msg'=>'评审状态值不正确（通过|拒单）','scene'=>['repeatFinalRepeat']],
        
    ];
    /*
     * 初审评审表修改
     *    @param 
     */
    public function firstEdit(&$data){
        if(empty($data["loanid"])){$this->error = "订单id不能为空";return false;}
        $reviewModel = static::get(["loanid"=>$data["loanid"]]);
        if(false==$reviewModel){$this->error = "订单id错误";return false;}
        if($reviewModel->checkMyValidate($data,"firstReview",$reviewModel)){
            if(!$reviewModel->allowField(true)->save($data,["loanid"=>$data["loanid"]])){
                $this->error="初审修改失败";
                return false;
            }
            $this->operateRecord("初审评审表【{$data['loanid']}】",true);
            return true;
        }else{
            $this->error = $reviewModel->error;
            return false;
        }
    }
    /*
     * 终审评审表修改
     */
    public function finalEdit(&$data){
        if(empty($data["loanid"])){$this->error = "订单id不能为空";return false;}
        $reviewModel = static::get(["loanid"=>$data["loanid"]]);
        if(false==$reviewModel){$this->error = "订单id错误";return false;}
        if($reviewModel->checkMyValidate($data,"finalRepeat",$reviewModel)){
            if(!$reviewModel->allowField(true)->save($data,["loanid"=>$data["loanid"]])){
                $this->error="终审修改评审表失败";
                return false;
            }
            $this->operateRecord("终审评审表【{$data['loanid']}】",true);
            return true;
        }else{
            $this->error = $reviewModel->error;
            return false;
        }
    }
    
    /*
     * 终审评审表修改
     */
    public function repeatEdit(&$data){
        if(empty($data["loanid"])){$this->error = "订单id不能为空";return false;}
        $reviewModel = static::get(["loanid"=>$data["loanid"]]);
        if(false==$reviewModel){$this->error = "订单id错误";return false;}
        if($reviewModel->checkMyValidate($data,"repeatFinalRepeat",$reviewModel)){
            if(!$reviewModel->allowField(true)->save($data,["loanid"=>$data["loanid"]])){
                $this->error="复议修改评审表失败";
                return false;
            }
            $this->operateRecord("复议评审表【{$data['loanid']}】",true);
            return true;
        }else{
            $this->error = $reviewModel->error;
            return false;
        }
    }
    
    public function checkMyValidate(&$data, $scene = null,$model) {
        //初审
        if(!empty($data["first_status"])){
            if($data["first_status"]==1 && empty($data["first_quota"])){$this->error = "初审额度不能为空";return false;}
            $data["start_first_time"] = empty($model->getData("start_first_time"))?(date("Y-m-d H:i:s")):$model->getData("start_first_time");
            $data["first_result"] = empty($data["first_result"])?($data["first_status"]==1?LoanReview::FIRST_SUCCESS_MSG:LoanReview::FIRST_DENY_MSG):$data["first_result"];
            $data["end_first_time"] = empty($data["end_first_time"]) && $data["first_status"]==1?date("Y-m-d H:i:s"):$model->getData("end_first_time");
        }
        //终审
       if(!empty($data["final_status"])){
           if($data["final_status"]==1 && empty($data["final_quota"])){$this->error = "终审额度不能为空";return false;}
            $data["start_final_time"] = empty($model->getData("start_final_time"))?(date("Y-m-d H:i:s")):$model->getData("start_final_time");
            $data["final_result"] = empty($data["final_result"])?($data["final_status"]==1?LoanReview::FINAL_SUCCESS_MSG:LoanReview::FINAL_DENY_MSG):$data["final_result"];
            $data["end_final_time"] = empty($data["end_final_time"]) && $data["final_status"]==1?date("Y-m-d H:i:s"):$model->getData("end_final_time");
            $data["final_author"] = User::getUserInfo("names");
            $data["final_status"]==1?($data["repeat_quota"] = $data["final_quota"]):"";
        }
        //复议
        if(!empty($data["repeat_status"])){
            if($data["repeat_status"]==1 && empty($data["repeat_quota"])){$this->error = "复议额度不能为空";return false;}
            $data["repeat_result"] = empty($data["repeat_result"])?($data["repeat_status"]==1?LoanReview::REPEAT_SUCCESS_MSG:LoanReview::REPEAT_DENY_MSG):$data["repeat_result"];
        }
        return parent::checkValidate($data, $scene, $batch);
    }
}
