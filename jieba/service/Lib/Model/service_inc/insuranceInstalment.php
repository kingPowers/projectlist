<?php
/*
车险分期付款：分呗
 */
$type = $this->v("type");
$order_id = $this->v("order_id")?$this->v("order_id"):'';
//是否已登录
$member_info = $this->token_check_force();
import("Think.ORG.Util.InsuranceInstalment");
$instalment = new InsuranceInstalment($member_info['id'],$order_id);
//用户分期总信息
if ($type == "userInstalment") {
    if (false == ($instalment_info=$instalment->userInstalment($member_info['id']))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $instalment_info;
    return true;
}
//获取订单分期列表(申请全额|分期的数据)
elseif($type == "instalmentList") {
    $this->need("order_id","订单ID");
    if (false == ($list = $instalment->instalmentList())) {
            $this->error("LIST_ERROR",$instalment->getError());
    }
    foreach($list as &$val){
        $val["back_time"] = date("Y年m月d日",strtotime($val["back_time"]));
    }
    $this->data = $list;
    return true;
}
/*
 *  获取验证码
 *          -----申请分期|全额付款
 */
elseif ($type == "prePaySms") {
    $this->need("order_id","订单ID");
    $this->need("pay_type","请选择分期或付款");
    $pay_type = $this->v("pay_type");
    $order_id = $this->v("order_id");
    if($pay_type==1 && !is_file(UPLOADPATH."allot/{$order_id}_1.pdf")){$this->error("EQIAN_ERROR","对不起，请先签署协议！");}
    if($pay_type==2 && !is_file(UPLOADPATH."allot/{$order_id}_2.pdf")){$this->error("EQIAN_ERROR","对不起，请先签署协议！");}
    if (false == $instalment->getPaySms($this->v("pay_type"))) {
            $this->error("SMS_ERROR",$instalment->getError());
    }
    return true;
}
/*
 * 付款
 *      --分期|全额付款
 * 
 */
elseif($type=="payMoney"){
    $this->need("smscode","验证码");
    $this->need("order_id","订单ID");
    $this->need("pay_type","请选择全额或分期付款");
    $pay_type = $this->v("pay_type");
    $order_id = $this->v("order_id");
    if($pay_type==1 && !is_file(UPLOADPATH."allot/{$order_id}_1.pdf")){$this->error("EQIAN_ERROR","对不起，请先签署协议！");}
    if($pay_type==2 && !is_file(UPLOADPATH."allot/{$order_id}_2.pdf")){$this->error("EQIAN_ERROR","对不起，请先签署协议！");}
    if (false == $instalment->payMoney(["smscode"=>$this->v("smscode"),"pay_type"=>$pay_type])) {
            $this->error("SMS_ERROR",$instalment->getError());
    }
    $this->data = "付款成功";
    return true;
}
//已出账单数据
elseif($type=="outBillInfo"){
    if (false == ($outBillInfo=$instalment->outBillInfo($member_info['id']))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $outBillInfo;
    return true;
}
//未出账单数据
elseif($type=="unoutBillInfo"){
    if (false == ($unoutBillInfo=$instalment->unoutBillInfo($member_info['id']))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $unoutBillInfo;
    return true;
}
//车险订单分期详情
elseif($type=="orderInstalmentInfo"){
    $this->need("order_id","订单ID");
    if (false == ($orderInstalmentInfo=$instalment->orderInstalmentInfo($this->v("order_id")))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $orderInstalmentInfo["initial_time"] = date("Ymd",strtotime($orderInstalmentInfo["initial_time"]));
    foreach($orderInstalmentInfo["instalment"] as &$val){
        $val["back_time"] = date("Ymd",strtotime($val["back_time"]));
    }
    $this->data = $orderInstalmentInfo;
    return true;
}
//全部账单
elseif($type=="memberBill"){
    if (false == ($orderInstalmentInfo=$instalment->memberBill($member_info['id']))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $orderInstalmentInfo;
    return true;
}
//分期明细
elseif($type=="instalmentDetail"){
     $this->need("instalId","分期订单ID");
    if (false == ($orderInstalmentInfo=$instalment->instalmentDetail($this->v("instalId")))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $orderInstalmentInfo;
    return true;
}
//分期付款获取金额数据
elseif($type=="backInfo"){
    $this->need("back_type","还款方式");
    if(in_array($this->v("back_type"),["2","4"]) && empty($this->v("instalmentid"))){
        $this->need("instalmentid","分期ID");
    }
    if (false == ($orderInstalmentInfo=$instalment->backInfo($this->v("back_type"),$member_info["id"],$this->v("instalmentid")))) {
            $this->error('INFO_ERROR',$instalment->getError());
    }
    $this->data = $orderInstalmentInfo;
    return true;
}
/*
 *  获取验证码
 *          -----分期还款
 *          -----还款方式：1：本月全额；2：本月一期；3：下月全额：4：下月一期
 * 
 */
elseif ($type == "sendBackSms") {
    $this->need("back_type","还款方式");
    if(in_array($this->v("back_type"),["2","4"]) && empty($this->v("instalmentid"))){
        $this->need("instalmentid","分期ID");
    }
    if (false == $instalment->sendBackSms($this->v("back_type"),$this->v("instalmentid"),$member_info["id"])) {
            $this->error("SMS_ERROR",$instalment->getError());
    }
    return true;
}
/*
 * 付款
 *      -----分期还款
 *      -----还款方式：1：本月全额；2：本月一期；3：下月全额：4：下月一期
 * 
 */
elseif($type=="backMoney"){
    $this->need("back_type","还款方式");
    $this->need("back_smscode","验证码");
    if(in_array($this->v("back_type"),["2","4"]) && empty($this->v("instalmentid"))){
        $this->need("instalmentid","分期ID");
    }
    if (false == $instalment->backMoney($this->v("back_type"),$this->v("back_smscode"),$member_info["id"],$this->v("instalmentid"))) {
            $this->error("SMS_ERROR",$instalment->getError());
    }
    $this->data = "付款成功";
    return true;
}
//电子签章获取验证码
elseif($type=='eqian_sms'){
        $this->need("order_id","订单ID");
        $this->need('pay_type',"付款方式");//1:全额付款  2：分期付款
        $pay_type = $this->v("pay_type");
        import("Think.ORG.Util.InsuranceOrder");
        $insurance = new InsuranceOrder(["memberId"=>$member_info["id"]]);
        $detail = $insurance->orderDetail($this->v("order_id"));
        if($pay_type==1 && is_file(UPLOADPATH."allot/{$order_id}_1.pdf")){$this->error("EQIAN_ERROR","您已签过协议，不用再次签协议！");}
        if($pay_type==2 && is_file(UPLOADPATH."allot/{$order_id}_2.pdf")){$this->error("EQIAN_ERROR","您已签过协议，不用再次签协议！");}
	import("Think.ORG.Util.Eqian");
	$eqian = new Eqian();
	$certi = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	$return = $eqian->sendSignCode($certi['certiNumber']);
	if($return){
		$this->data = '短信发送成功';
		return true;
	}else{
		$this->error("EQIAN_ERROR",'短信发送失败'.$eqian->geterror());
	}
}
//签署电子签章
elseif($type=='eqian_submit'){
        $this->need('order_id',"订单id");
	$this->need('eqian_smscode',"验证码");
        $this->need('pay_type',"付款方式");//1:全额付款  2：分期付款
        
        $pay_type = $this->v("pay_type");
	$sms_code = $this->v('eqian_smscode');
        $order_id = $this->v("order_id");
        $this->need("order_id","订单ID");
        import("Think.ORG.Util.InsuranceOrder");
        $insurance = new InsuranceOrder(["memberId"=>$member_info["id"]]);
        $detail = $insurance->orderDetail($order_id);
        if($pay_type==1 && is_file(UPLOADPATH."allot/{$order_id}_1.pdf")){$this->error("EQIAN_ERROR","您已签过协议，不用再次签协议！");}
        if($pay_type==2 && is_file(UPLOADPATH."allot/{$order_id}_2.pdf")){$this->error("EQIAN_ERROR","您已签过协议，不用再次签协议！");}
        if (false == ($list = $instalment->instalmentList())) {
            $this->error("LIST_ERROR",$instalment->getError());
        }
        foreach($list as &$val){
            $val["back_time"] = date("Y.m.d",strtotime($val["back_time"]));
        }
        $pdfData["names"] = $member_info["bindinfo"]["names"];
        $pdfData["certiNumber"] = $member_info["bindinfo"]["certiNumber"];
        $pdfData["pay_type"] = $this->v("pay_type");
        $pdfData["order_id"] = $this->v("order_id");
        $pdfData["force_money"] = $detail["force_money"];
        $pdfData["business_money"] = round($detail["business_money"]*0.2,2);
        $pdfData["insurance_type"] = $detail["insurance_type"];
        $pdfData["list"] = $list;
        createAllotPdf($pdfData);
        $this->data = '签约成功';
        return true;
	import("Think.ORG.Util.Eqian");
	$eqian = new Eqian();
        if($pay_type==1){
            $fileName = UPLOADPATH."allot/{$order_id}_1.pdf";
        }else{
            $fileName = UPLOADPATH."allot/{$order_id}_2.pdf";
        }
        $return = $eqian->userSafeSignPDF($member_info["bindinfo"]['certiNumber'],$fileName,$fileName,4, '', 120, '', '委托人盖章','',$sms_code,0,$order_id,true);
	if($return){
                $eqian->userSafeSignPDF2($fileName,$fileName,16,150, '公司盖章');
                $this->data = '签约成功';
                return true;
        }else{
            @unlink($fileName);
            $this->error("EQIAN_ERROR","签约失败(".$eqian->geterror().")");
        }
        
}
/*
 * 创建协议
 *        --生成电子签章
 */
function  createAllotPdf($data){
    if(!is_dir(UPLOADPATH."allot")){
        mkdir(UPLOADPATH."allot",0755);
    }
    if($data["pay_type"]==1){//全额付款
        if(is_file(UPLOADPATH."allot/{$data["order_id"]}_1.pdf"))return true;
    }else{//分期付款
        if(is_file(UPLOADPATH."allot/{$data["order_id"]}_2.pdf"))return true;
    }
    import("Think.ORG.Util.PDF");
    return PDF::allot($data);
}