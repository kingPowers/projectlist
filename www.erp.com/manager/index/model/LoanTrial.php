<?php
/*
 * 面审表管理
 */
namespace manager\index\model;
class LoanTrial extends Common{
    protected $table = "loan_trial";
    protected $rule = [
        /*
        * 格式：[字段名称，验证名称，key=>val, ... ],
        *       msg：错误的提示信息   scene:表示场景名称
         *      edit:修改场景
         */
        ["borrow_purpose",'require','msg'=>'借款详细用途不能为空','scene'=>['edit']],
        ["repayment_ability",'require','msg'=>'还款能力不能为空','scene'=>['edit']],
        ["repayment_plan",'require','msg'=>'还款计划不能为空','scene'=>['edit']],
        ["family_asset",'require','msg'=>'家庭情况不能为空','scene'=>['edit']],
        ["trial_result",'require','msg'=>'面审不能为空','scene'=>['edit']],
        
    ];


    /*
     * 面审表修改
     *  @param $data:面审数据
     *  @return boolean
     */
    public function editTrial($data){
        if(empty($data["loanid"])){$this->error = "订单id不能为空";return false;}
        $trialModel = static::get(["loanid"=>$data["loanid"]]);
        if(false==$trialModel){$this->error = "订单id错误";return false;}
        if($trialModel->checkMyValidate($data,"edit",$trialModel)){
            $this->db()->startTrans();
            try {
                if(!$trialModel->allowField(true)->save($data,["loanid"=>$data["loanid"]])){
                    throw new \Exception("面审修改失败(101)");
                }
                if(!Loan::addMaterial($data["loanid"],"10")){
                    throw new \Exception("面审修改失败(loan:material-error)");
                }
            } catch (Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
            $this->db()->commit();
            $this->operateRecord("面审表修改【{$data['loanid']}】",true);
            return true;
        }else{
            $this->error = $trialModel->error;
            return false;
        }
    }
    /*
     * 验证
     */
    public function checkMyValidate(&$data, $scene = null,$model) {
        if(empty($model->getData('timeadd'))){
            $data["timeadd"] = date("Y-m-d H:i:s");
        }
        $data["lasttime"] = date("Y-m-d H:i:s");
        return parent::checkValidate($data, $scene, $batch);
    }
}
