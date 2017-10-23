<?php 
$member_info = $this->token_check_force();
$type = $this->v('type');
import("Think.ORG.Util.InsuranceOrder");
$insurance = new InsuranceOrder(["memberId"=>$member_info["id"]]);
/*
 * 保险申请的数据
 * 
 * searchStoreName：门店名称检索
 */
if($type=='applyData'){
	$this->data =  $insurance->applyData($this->v("searchStoreName"));
        return true;
}
//添加订单
elseif($type=="addData"){
    $insurance->carType = $this->v("carType");
    $insurance->smsCode = $this->v("smsCode");
    //$insurance->storeName = $this->v("storeName");
    $insurance->insuranceType = $this->v("insuranceType");
    $insurance->address = $this->v("address");
    $insurance->recMobile = $this->v("recMobile");
    if(false==($addId = $insurance->addOrder()))$this->error("ERROR",$insurance->getError());
    $this->data = $addId;
    return true;
}
//添加订单发送验证码
elseif($type=="sendSms"){
    if(false==($insurance->sendSms()))$this->error("ERROR",$insurance->getError());
    $this->data = "验证码发送成功";
    return true;
}
//订单列表
elseif($type=="orderList"){
    $page = !empty($this->v("page"))?$this->v("page"):-1;
    $number = !empty($this->v("number"))?$this->v("number"):"12";
    $where = ["memberId"=>$insurance->memberId];
    if(!empty($this->v("status"))){
        $where["status"] = $this->v("status");
        if($this->v("status")==1 && $this->v("is_pay")){
            $where["is_pay"] = $this->v("is_pay");
        }
    }
    $list = $insurance->orderList(["where"=>$where],$page,$number);
    $this->data = $list;
    return true;
}
//订单详情
elseif($type=="orderDetail"){
    $this->need("id","订单ID");
    $detail = $insurance->orderDetail($this->v("id"));
    if(empty($detail))$this->error("ERROR",$insurance->getError());
    $this->data = $detail;
    return true;
}
//取消订单
elseif($type=="orderCancle"){
    $this->need("id","订单ID");
    $cancle = $insurance->editToDeny($this->v("id"),1,["message"=>"客户主动拒单"]);
    if(false==$cancle)$this->error("ERROR",$insurance->getError());
    return true;
}
$this->error("ERROR","type参数错误");
?>