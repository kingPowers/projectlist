<?php
/*
 * 合同生成PDF，并包含用户信息管理类
 * 
 *      示例：
 *          $contactPdf = new ContactPdf();
 *          $contactPdf->loanId = 22;
 *          $contactPdf->createPdf();
 * 
 */
namespace manager\index\model;

class ContactPdf extends Contact{
    
    public $loanId;//订单id
    
    public $contactId;//合同ID
    
    private $_content;//合同内容
    
    
    
    /*
     * 生成PDF合同
     */
    public function createPdf(){
        $successList = [];
        $loanInfo = $this->getLoanInfo();
        
        //合同模板列表
        $templeteList = $this->getContactList();
        foreach($templeteList as $key=>$contact){
            $pdfFileName = "{$loanInfo["names"]}_{$contact["name"]}_{$loanInfo["id"]}.pdf";
            //模板变量处理
            $replace = $this->resolveVeriable($contact["variable_replace"]);
            $this->_content[$key] = strtr($contact["content"],$replace);
            
            //开始生成PDF
            $pdf = new PDF(["fileName"=>$pdfFileName,"content"=>$this->_content[$key]]);
            $result = $pdf->createPDF();
            
            if($result!==false){
              $successList[] = $result;  
            }
        }
        
        //合并打印所有文件
        $pdf = new PDF(["fileName"=>"打印全部_{$loanInfo["names"]}_{$loanInfo["id"]}.pdf","content"=>$this->_content]);
        $result = $pdf->createPDF();
        if($result!==false){
              $successList[] = $result;  
        }
        
        
        
        return $successList;
        
    }
    
    /*
     * 订单信息
     *      根据订单ID获取订单数据信息
     * @return array
     */
    private $_loanInfo = [];
    public function getLoanInfo(){
        if($this->loanId!==null && empty($this->_loanInfo)){
            $loanModel = new Loan();
            $loanModel->where["id"] = $this->loanId;
            $this->_loanInfo = $loanModel->loanList()[0];
            
            
            
        }
        return $this->_loanInfo;
    }
    
   
    /*
     * 获取合同列表
     * @return array
     */
    public function getContactList(){
        $this->getLoanInfo();
        if(empty($this->_loanInfo))return [];
        $contactManagerModel = new ContactManager();
        $contactManagerModel->where["product_name"] = $this->_loanInfo["product"];
        $contactManagerModel->where["id"] = $this->contactId;
        return $contactManagerModel->contactList();
    }
    
    /*
     * 处理模板变量
     * $veriable:模板变量
     *          veriable示例：【姓名】=>names    【身份证号】=>certiNumber
     * return array
     *          [
     *          "【姓名】"=>张三,
     *          "【身份证号】"=>"37021019901000",
     *      ]     
     *         
     *          
     */
    protected function resolveVeriable($veriables) {
        $replace = [];
        $loanInfo = $this->getLoanInfo();
        preg_match_all("/(\【.*?\】)=>(\w+)/",$veriables,$matches);
        foreach($matches[1] as $key=>$verName){
            $replace[$verName] = (string)$loanInfo[$matches[2][$key]];
        }
       return $replace;
    }
}
