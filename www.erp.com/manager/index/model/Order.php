<?php
/*
 * 订单所有业务处理
 *       提交、拒单、信用评估   等等订单处理业务   
 *              
 */
namespace manager\index\model;

class Order  extends Common{
     /*
     * 门店提交
     *   @param loanId:订单id(loan表主键)
     *   @param $params  扩展参数
     *   @return boolean
     */
    public function  lenderSubmit($loanId,$params = []){
        $loanModel = new Loan();
        $lenderModel = new Lender();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        //必填资料
        $mustAllow = ["1"=>"请先完善贷款申请表再提交","2"=>"请先上传身份证图片再提交","6"=>"请先完善话单再提交",
                      "7"=>"请先完善网查信息再提交","10"=>"请先完善面审表资料再提交","12"=>"请填写车辆评估表再提交",
                      "14"=>"请先上传车辆行驶证图片再提交","16"=>"请先上传车辆登记证图片再提交",
                      "19"=>"请先完善车辆违章查询再提交"];
        foreach($mustAllow as $k=>$message){
            if(!in_array($k,explode(",",$loanInfo["material"]))){$this->error = $message;return false;}
        }
        if($loanInfo["process_status"]== Loan::TO_BE_SUBMITED && $loanInfo["status"]== Loan::LOAN_DEAL){
            $this->db()->startTrans();
            try {
                if(false==$loanModel->save(["process_status"=> Loan::TO_BE_QUOTED],["id"=>$loanId])){
                    throw new \Exception("订单未提交成功(101)");
                }
                if(false==$lenderModel->save(["submit_time"=>date("Y-m-d H:i:s")],["loanid"=>$loanId])){
                    throw new \Exception("订单未提交成功(102)");
                }
                $message = $params["remark"]?$params["remark"]:"提交审核，等待评估";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_QUOTED,"message"=>$message])){
                    throw new \Exception("订单未提交成功(103)");
                }
                $this->operateRecord("贷款订单【{$loanId}】提交",true);
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
            
        }else{
             $this->error = "订单处于【{$loanInfo['status_name']}】中,不可提交";
            return false;
        }
    }
    /*
     * 门店拒单
     *   @param loanId:订单id(loan表主键)
     *   @param $params  扩展参数
     *   @return boolean
     */
    public function lenderDeny($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if(in_array($loanInfo["process_status"],[loan::TO_BE_SUBMITED, Loan::TO_BE_QUOTED, Loan::TO_DOOR_FEEDBACK, Loan::TO_DOOR_FINAL_FEEDBACK, Loan::TO_PEND_FINAL_FEEDBACK, Loan::TO_BE_FINAL2]) && $loanInfo["status"]== Loan::LOAN_DEAL){
            $this->db()->startTrans();
            try { 
                if(false==$loanModel->save(["deny_status"=> Loan::LOAN_FAIL_DOOR,"status"=> Loan::LOAN_FAIL,"deny_time"=>date("Y-m-d H:i:s")],["id"=>$loanId])){
                    throw new \Exception("拒单失败(101)");
                }
                $message = $params["remark"]?$params["remark"]:"门店拒单";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_deny"=>Loan::LOAN_FAIL_DOOR,"message"=>$message])){
                    throw new \Exception("拒单失败(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
            
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可拒单";
            return false;
        }
    }
    /*
     * 总部报价
     *    总部评估师报价
     *    @param $loanId 订单id
     *    @param $money:报价金额
     *    @param $params 扩展参数
     *   @return boolean 
     */
    public function companyQuote($loanId,$money,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if(round($money,2)<=0){$this->error = "报价金额不正确";return false;}
        if($loanInfo["status"]== Loan::LOAN_DEAL && $loanInfo["process_status"]== Loan::TO_BE_QUOTED){
            $this->db()->startTrans();
            try{
                if(!(new CarReport())->save(["company_car_price"=>$money,"company_car_time"=>date("Y-m-d H:i:s"),"company_appraiser"=> User::getUserInfo("names")],["loanid"=>$loanId])){
                    throw  new \Exception("报价未保存成功(101)");
                }
                
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_SINGLE){
            $this->error = "您已报过价了";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可报价";
            return false;
        }
    }
    /*
     * 总部评估师提交
     *      评估师报过价后提交
     *   @param $loanId:订单id
     *   @param $params:扩展参数
     *   @return boolean
     */
    public function companyQuoteSubmit($loanId,$params = []){
        $loanModel = new Loan();
        $carReportModel = new CarReport();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        $carReportInfo = $carReportModel->carReportOne($loanId);
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if(round($carReportInfo["company_car_price"],2)<=0){$this->error = "请先报价再提交";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && $loanInfo["process_status"]== Loan::TO_BE_QUOTED){
            $this->db()->startTrans();
            try{
                if(!$loanModel->save(["process_status"=> Loan::TO_BE_SINGLE],["id"=>$loanId])){
                    throw new \Exception("未提交成功(101)");
                }
                if(!$carReportModel->save(["company_car_time"=>date("Y-m-d H:i:s")],["loanid"=>$loanId])){
                    throw new \Exception("未提交成功(102)");
                }
                $message = $params["remark"]?$params["remark"]:"等待初审";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_SINGLE,"message"=>$message])){
                    throw new \Exception("未提交成功(103)");
                }
                
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_SINGLE){
            $this->error = "您已报过价了";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可提交";
            return false;
        }
    }
    
    /*
     * 抢单
     *      初审人员抢单
     * @param $loanId  订单id
     * @param $params 扩展参数
     * @return boolean
     */
    public function competeOrder($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && $loanInfo["process_status"]== Loan::TO_BE_SINGLE){
            $this->db()->startTrans();
            try{
                if(!$loanModel->save(["pend_uid"=> User::getUid(),"pend_time"=>date("Y-m-d H:i:s"),"process_status"=> Loan::TO_BE_PEND],["id"=>$loanId])){
                    throw new \Exception("未抢单成功(101)");
                }
                $message = $params["remark"]?$params["remark"]:"初审中";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_PEND,"message"=>$message])){
                    throw new \Exception("未抢单成功(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_PEND){
            $this->error = "你来晚了，订单已被别人抢走了";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可抢单";
            return false;
        }
    }
    /*
     * 初审评审--通过|拒单
     *      初审开始评审贷款订单
     *  @params $loanId:订单id
     *  @params $reviewData:评审数据
     *  @return boolean 
     */
    public function  firstReview($loanId,$reviewData = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_BE_PEND, Loan::TO_PEND_FEEDBACK])){
            $this->db()->startTrans();
            try{
                $loanReview = new LoanReview();
                if(!$loanReview->firstEdit($reviewData)){
                    throw new \Exception($loanReview->getError());
                }
                //first_status  1:通过    2：拒单
                $processStatus = $reviewData["first_status"]==1?Loan::TO_BE_FINAL_PEND:Loan::TO_DOOR_FEEDBACK;
                if(!$loanModel->save(["process_status"=> $processStatus],["id"=>$loanId])){
                    throw new \Exception("初审保存失败(101)");
                }
                $message = $reviewData["first_result"]?$reviewData["first_result"]:"初审审理中";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>$processStatus,"message"=>$message])){
                    throw new \Exception("初审保存失败(102)");
                }
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
            $this->db()->commit();
            return true;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可评审";
            return false;
        }
    }
    /*
     *门店反馈初审订单
     *      门店人员反馈初审的订单情况
     * @param $loanId:订单id
     * @param $params:扩展参数
     * @return boolean
     */
    public function doorReview($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && $loanInfo["process_status"]== Loan::TO_DOOR_FEEDBACK){
            $this->db()->startTrans();
            try{
                if(!$loanModel->save(["process_status"=> Loan::TO_PEND_FEEDBACK],["id"=>$loanId])){
                    throw new \Exception("反馈失败(101)");
                }
                $message = $params["remark"]?$params["remark"]:"等待初审反馈";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_PEND_FEEDBACK,"message"=>$message])){
                    throw new \Exception("反馈失败(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_PEND_FEEDBACK){
            $this->error = "您已反馈过该订单";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可反馈";
            return false;
        }
    }
    /*
     * 终审复议--通过|拒单
     *      终审人员复议订单到门店
    *      @param $loanId:订单id
    *      @param $repeatData:扩展参数
    *      @return boolean
     */
    public function finalRepeat($loanId,$repeatData = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_BE_FINAL_PEND,])){
            $this->db()->startTrans();
            try{
                $loanReview = new LoanReview();
                if(!$loanReview->finalEdit($repeatData)){
                    throw new \Exception($loanReview->getError());
                }
                //final_status  1:通过    2：拒单
                $processStatus = $repeatData["final_status"]==1?Loan::TO_BE_FINAL2:Loan::TO_DOOR_FINAL_FEEDBACK;
                if(!$loanModel->save(["process_status"=> $processStatus],["id"=>$loanId])){
                    throw new \Exception("终审提交失败(101)");
                }
                $message = $repeatData["final_result"]?$repeatData["final_result"]:"终审审理中";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>$processStatus,"message"=>$message])){
                    throw new \Exception("终审提交失败(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_DOOR_FINAL_FEEDBACK){
            $this->error = "您已复议过该订单";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可复议";
            return false;
        }
    }
    /*
     *门店复议终审的订单
     *      门店人员复议的订单情况
     * @param $loanId:订单id
     * @param $params:扩展参数
     * @return boolean
     */
    public function doorRepeat($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if(empty($params["door_result"])){$this->error = "请填写复议意见";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_DOOR_FINAL_FEEDBACK, Loan::TO_BE_FINAL2])){
            $this->db()->startTrans();
            try{
                $loanReview = new LoanReview();
                if(!$loanReview->save(["door_result"=>$params["door_result"]],["loanid"=>$loanId])){
                    throw new \Exception("复议失败(101)");
                }
                if(!$loanModel->save(["process_status"=> Loan::TO_PEND_FINAL_FEEDBACK],["id"=>$loanId])){
                    throw new \Exception("复议失败(102)");
                }
                $message = $params["remark"]?$params["remark"]:"等待终审复议";
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_PEND_FINAL_FEEDBACK,"message"=>$message])){
                    throw new \Exception("复议失败(103)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_PEND_FINAL_FEEDBACK){
            $this->error = "您已复议过该订单";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可复议";
            return false;
        }
    }
    
    /*
     *风控经理复议门店的订单
     *      风控经理审理门店复议的订单情况
     * @param $loanId:订单id
     * @param $repeatFinalData:复议数据
     * @return boolean
     */
    public function doubleFinalRepeat($loanId,$repeatFinalData = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_PEND_FINAL_FEEDBACK,])){
            $this->db()->startTrans();
            try{
                $loanReview = new LoanReview();
                if(!$loanReview->repeatEdit($repeatFinalData)){
                    throw new \Exception($loanReview->getError());
                }
                //repeat_status  1:通过    2：拒单
                if($repeatFinalData["repeat_status"]==1){
                    if(!$loanModel->save(["process_status"=> Loan::TO_BE_FINAL3],["id"=>$loanId])){
                        throw new \Exception("复议提交失败(101)");
                    }
                    $message = $repeatFinalData["repeat_result"]?$repeatFinalData["repeat_result"]:"复议审理中";
                    if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_FINAL3,"message"=>$message])){
                        throw new \Exception("复议提交失败(102)");
                    }
                }else{
                    if(!$loanModel->save(["status"=> Loan::LOAN_FAIL,"deny_status"=>Loan::LOAN_FAIL_FINAL],["id"=>$loanId])){
                        throw new \Exception("复议提交失败(101)");
                    }
                    $message = $repeatFinalData["repeat_result"]?$repeatFinalData["repeat_result"]:"复议审理中";
                    if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_deny"=>Loan::LOAN_FAIL_FINAL,"message"=>$message])){
                        throw new \Exception("复议提交失败(102)");
                    }
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_FINAL3){
            $this->error = "您已复议过该订单";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可复议";
            return false;
        }
    }
   /*
    * 门店通过风控
    *       --门店同意，订单审核通过，流程进入稽核
    *   @param $loanId:订单id
    *   @param $params:扩展参数
    *   @return boolean
    */
    public function  passToContact($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_BE_FINAL2,Loan::TO_BE_FINAL3])){
            $this->db()->startTrans();
            try{
                
                if(!$loanModel->save(["process_status"=> Loan::TO_BE_CONTRACT],["id"=>$loanId])){
                        throw new \Exception("复议提交失败(101)");
                }
                $message = $params["remark"]?$params["remark"]:Loan::TO_BE_CONTRACT_MSG;
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_CONTRACT,"message"=>$message])){
                    throw new \Exception("复议提交失败(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_CONTRACT){
            $this->error = "您已复议通过，无需重复提交";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可复议通过";
            return false;
        }
    }
    /*
     * 稽核确定产品
     *      --风控通过，等待稽核确定产品
     * @params $loanId 订单id
     * @params $params 扩展参数
     * @return boolean
     */
    public function passToProduct($loanId,$params = []){
         $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){$this->error = "订单id错误";return false;}
        if(empty($params["product_id"]) || false==($productModel = ProductManager::get($params["product_id"]))){$this->error = "产品id错误";return false;}
        if($loanInfo["status"]==Loan::LOAN_DEAL && in_array($loanInfo["process_status"],[Loan::TO_BE_CONTRACT])){
            $this->db()->startTrans();
            try{
                
                if(!$loanModel->save(["process_status"=> Loan::TO_BE_PRODUCT,"product"=>$productModel->toArray()["name"]],["id"=>$loanId])){
                        throw new \Exception("确定产品提交失败(101)");
                }
                $message = $params["remark"]?$params["remark"]:Loan::TO_BE_PRODUCT_MSG;
                if(false == LoanProcess::addLoanProcess(["loanid"=>$loanId,"status_process"=>Loan::TO_BE_CONTRACT,"message"=>$message])){
                    throw new \Exception("确定产品失败(102)");
                }
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }elseif($loanInfo["process_status"]== Loan::TO_BE_CONTRACT){
            $this->error = "您已提交过产品，无需重复提交";
            return false;
        }else{
            $this->error = "订单处于【{$loanInfo['status_name']}】中,不可选择产品";
            return false;
        }
    }
    /*
     * 下载合同模板
     *      --执行此方法会自动下载zip文件
     *  @param $loanId:订单id
     *  @param $params:扩展参数
     *  @return void
     */
    public function downLoadContactTemplete($loanId,$params = []){
        $loanModel = new Loan();
        $loanModel->where["id"] = $loanId;
        $loanInfo = $loanModel->loanList()[0];
        if(false==$loanInfo){exit("订单不见了");}
        //合同列表
        $contactModel = new ContactManager();
        $contactModel->where["product_name"] = $loanInfo["product"];
        $contactList = $contactModel->contactList();
        if(empty($contactList)){
            exit("您选择的产品【{$loanInfo["product"]}】没有合同文件");
        }
        //合同压缩
        $zipModel = new Zip();
        foreach($contactList as $contact){
            $zipModel->addFile($contactModel->getUploadPath().$contact["contact_file"]);
        }
       $absoluteFile = $zipModel->createZip("合同模板_{$loanInfo["names"]}");
       if($zipModel->getError()){exit($zipModel->getError());}
        //开始下载
        (new Download())->downloadFile(basename($absoluteFile));
    }
    /*
     * 下载合同
     * --执行此方法会自动下载zip文件
     *  @param $loanId:订单id
     *  @param $params:扩展参数
     *  @return void
     */
    public function  downloadContact($loanId,$params = []){
        //生成PDF
        $contactPdf = new ContactPdf();
        $contactPdf->loanId = $loanId;
        $pdfList = $contactPdf->createPdf();
        
        $zipModel = new Zip();
        $zipModel->addFiles($pdfList);
        $absoluteFile = $zipModel->createZip($contactPdf->getLoanInfo()["names"].$loanId);
        
       if($zipModel->getError()){exit($zipModel->getError());}
        //开始下载
        (new Download())->downloadFile(basename($absoluteFile));
        
    }
    
}
