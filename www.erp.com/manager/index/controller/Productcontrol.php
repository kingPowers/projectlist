<?php

namespace manager\index\controller;

use manager\index\controller\Common;

/**
 * 产品管理
 */
class Productcontrol extends Common
{

    public $base_title = [
        'id'              => "产品id",
        'employer'         => "资金方",
        'name'            => "产品名称",
        'month'           => "期数",
        'repayment_type'  => "还款方式 ",
        'year_rate'       => "年化利率",
        'comp_month_rate' => "综合月利率",
        'breach_rate'     => "提前结清违约金费率",
        'contact_rate'    => "合同金额利率",
        'gpsL'            => "GPS流量费",
        'gpsS'            => "GPS设备费",
        'gpsZ'            => "gps责任险",
        'insteadCredit'   => "代拉征信费用",
         'contract_info'   => "合同资料",
        'status'          => "状态",
        'timeadd'         => '更新时间',
        'operate'         => "操作",
    ];

    // public function _initialize()
    // {
    //     $product = new \manager\index\model\ProductManager();
    //     //还款方式
    //     $repaymentList = $product->repaymentList();
    //     $this->assign('repaymentList', $repaymentList);
    // }
    /*
     * 产品展示
     */
    public function index()
    {
        $product = new \manager\index\model\ProductManager();
        $list = $this->handleData($product->productList(), $this->base_title);
        $this->assign('list', $list);
        $this->assign('title', $this->getTitle());
        return $this->fetch();
    }
     
      /*
    添加合同
     */
    public function addContract()
    {
        $contactModel = new \manager\index\model\ContactManager();
        if(empty($_REQUEST["product_id"]))$this->redirect ("index");
        if($_POST){
           if($contactModel->addContact($_POST)){
               return $this->ajaxSuccess("操作成功");
           }else{
               return $this->ajaxError($contactModel->getError());
           }
        }
        return $this->fetch();
    }
    /*
     * 修改合同
     */
    public function editContract(){
        if(empty($_REQUEST["id"]))$this->redirect ("viewcontact");
        $contactModel = \manager\index\model\ContactManager::get($_REQUEST["id"]);
        if($_POST){
           if($contactModel->editContact($_POST)){
               return $this->ajaxSuccess("操作成功");
           }else{
               return $this->ajaxError($contactModel->getError());
           }
        }
        $this->assign("contactOne",$contactModel->getData());
        return $this->fetch();
    }
    /*
     * 查看合同
     */
    public function viewContact(){
        //下载合同模板
        if(isset($_REQUEST["type"]) && $_REQUEST["type"]=="download" && !empty($_REQUEST["fileName"])){
            $downloadModel = new \manager\index\model\Download();
            $downloadModel->downloadFile($_REQUEST["fileName"]);
        }
        if(empty($_REQUEST["id"]))$this->redirect ("index");
        $contactModel = \manager\index\model\ContactManager::get($_REQUEST["id"]);
        $this->assign("contactOne",$contactModel->getData());
        return $this->fetch();
    }


    
     //列表数据处理
    protected function handleData($data, $title)
    {
        if (empty($data) || empty($title)) {
            return [];
        }
        $return = [];
        foreach ($data as $dk => $dv) {
            foreach ($title as $tk => $tv) {
                if ($tk == "contract_info") {
                    $return[$dk]['contract_info'] = $this->productContactList($dv["contactList"]);
                }elseif($tk=="operate"){
                    $return[$dk][$tk] = ['edit' => '编辑', 'calculate' => " 计算器", 'addContract' => '添加合同'];
                }
                else{
                  $return[$dk][$tk] = $dv[$tk];
                }
            }
        }
        //dump($return);
        return $return;
    }
    /*
     * 产品新增
     */

    public function add()
    {
        $product = new \manager\index\model\ProductManager();
        // $repaymentList = $product->repaymentList();
        //还款方式
        $this->assign('repaymentList', $product->repaymentList());
        // 产品名称列表
        $this->assign('allProducts', $product->allProducts());
        if ($_POST) {
            $_POST['timeadd'] = date('Y-m-d H:i:s');
            if ($product->addProduct($_POST)) {
                return ['status' => 1, 'msg' => '操作成功'];
            } else {
                return ['status' => 0, 'msg' => $product->getError()];
            }
        }
        $this->assign("channelList",$product->allEmployers());
        return $this->fetch();
    }

    /*
     * 产品编辑
     */
    public function edit()
    {
        $product = new \manager\index\model\ProductManager();
        $res     = $product->getProductOne($_GET['id']);
        $this->assign('allProducts', $product->allProducts());
        $this->assign('repaymentList', $product->repaymentList());
        $this->assign('res', $res);
        if ($_POST) {
            if ($product->editProduct($_POST)) {
                return ['status' => 1, 'msg' => '操作成功'];
            } else {
                return ['status' => 0, 'msg' => $product->getError()];
            }
        }
        $this->assign("channelList",$product->allEmployers());
        return $this->fetch();
    }
    /*
    计算器页面
     */
    public function calculate()
    {
        $product = new \manager\index\model\ProductManager();
        $productId = $this->request->request("id");
        $res     = $product->getProductOne($productId);//dump($res);
        $this->assign("productInfo",$res);
        if ($this->request->post() && !empty($this->request->post("money"))) {
            $productObject = $product->createProductObject($this->request->post("productName"));//dump($productObject);
            $productObject->loanMoney = $this->request->post("money");//dump($productObject);
            $this->assign("startTime",$productObject->toCollection($productObject));
            $this->assign("contactMoney",$productObject->getContactMoney());
            $this->assign("arrivalMoney",$productObject->getArrivalMoney());
            $this->assign("endTime",$productObject->getEndTime());
            $this->assign("repaymentMoney",$productObject->getRepaymentMoney());
            $this->assign("monthRate",$productObject->getMonthRate());
            $list = $productObject->productList();
            //dump($list);
            $this->assign("list",$list);
        }
        return $this->view->fetch();
    }
    //产品列表中的合同
    private function productContactList($contactList){
        $redirectUrl = "viewcontact";
        $maContent = "<div class='list-btn'>查看</div><div class='list'><ul>";
        if(!empty($contactList)){
            foreach($contactList as $key=>$contact){
                $maContent.="<li><a href='{$redirectUrl}?id={$contact['id']}'>{$contact['name']}</a></li>";
            }
        }else{
            $maContent.="<li>空</li>";
        }
        
        $maContent .= "</ul></div>";
        return $maContent;
    }
}
