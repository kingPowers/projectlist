<?php
/*
 * 门店系统申请资料图片管理控制器 
 */
namespace manager\index\controller;

use manager\index\controller\Common;
use manager\index\model\Loan;
use think\Db;

class Showphotos extends Common
{
    //人员信息
    public $base_table = [
        '1'  => ["title" => "申请表", 'url' => "/Storesystem/applyTable.html?readonly=1&loanid="],
        '2'  => ["title" => "身份证", 'field' => "card_photo"],
        '3'  => ["title" => "银行卡", 'field' => "bank_card"],
        '4'  => ["title" => "流水", 'field' => "account_statement"],
        '5'  => ["title" => "征信", 'field' => "credit_report"],
        '6'  => ["title" => "话单", 'url' => "/Image/phonebill.html?fordownload=1&loanid="],
        '7'  => ["title" => "网查信息", 'field' => "net_info"],
        '8'  => ["title" => "生活照合影", 'field' => "life_photo"],
        '9'  => ["title" => "资产信息", 'field' => "property_info"],
        '10' => ["title" => "面审表", 'url' => "/Storesystem/interviewTable.html?readonly=1&loanid="],
        '11' => ["title" => "其他类", 'field' => "other"],
    ];
    //车辆信息
    public $car_table = [ 
        '12' => ["title" => "评估表", 'url' => "/Storesystem/carevalTable.html?readonly=1&loanid="],
        '13' => ["title" => "合影", 'field' => "group_photo"],
        '14' => ["title" => "行驶证", 'field' => "driving_license"],
        '15' => ["title" => "驾驶证", 'field' => "driver"],
        '16' => ["title" => "车辆登记证", 'field' => "vehicle_regi"],
        '17' => ["title" => "保险及理赔查询", 'field' => "insurance"],
        '18' => ["title" => "车辆照片", 'field' => "car_photos"],
        '19' => ["title" => "违章查询", 'field' => "car_net_info"],
        '20' => ["title" => "钥匙", 'field' => "key"],
        '21' => ["title" => "合同照片", 'field' => "contact"], 
    ];
    public function index()
    {
    $loanid = $this->request->get('loanid');
    $field  =$this->request->get('field');
    if (!$field) {
        $field = 'card_photo';
    }
    $Showphotos = \manager\index\model\Showphotos::get(['loanid'=>$loanid]);
    $photos     = $Showphotos->$field;
    if($photos){
        $photos     = explode('|', $photos);
        $this->assign('photos', $photos);
    }
    $loanres =  \manager\index\model\Loan::get(['id'=>$loanid]);
    $material = $loanres->material;
    $material = explode(',',$material);
    $this->assign('base_table', $this->base_table);
    $this->assign('car_table', $this->car_table);
    $this->assign('loanid', $loanid);
    $this->assign('field',$field);  
    $this->assign('material',$material);
    return $this->view->fetch();

}
}
