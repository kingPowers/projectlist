<?php
/**
 * Description of CommonAction
 * 转账管理
 * @author Nydia
 */
class TransAction extends CommonAction {
	private $_transkeys = array(  'name' => '姓名', 'cellphone' => '手机号');
	
    //转账列表
    public function index(){
        $params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['ht.id'] = array("exp"," is not null and (in_mi.names like '%{$value}%' or out_mi.names like '%{$value}%')");
                    break;
                case 'cellphone':
                    $map['ht.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
     
      
        $count = M('hand_trans ht')
            ->join('member_info in_mi on ht.in_cust_no=in_mi.fuyou_login_id')
            ->join('member_info out_mi on ht.out_cust_no=out_mi.fuyou_login_id')
            ->where($map)->count();//dump(M()->getLastSql());exit;
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
             $list = M('hand_trans ht')
            		->join('member_info in_mi on ht.in_cust_no=in_mi.fuyou_login_id')
            		->join('member_info out_mi on ht.out_cust_no=out_mi.fuyou_login_id')
            		->field("ht.*,in_mi.names as in_names,out_mi.names as out_names")
            		->where($map)
            		->order("ht.id desc")
             		->select();
            $this->assign('list', $list);
        }
        $this->setPage("/Trans/index{$params}/p/*.html");
        $this->assign('keys', $this->_transkeys);
        $this->assign('totalNum', $total);
        $this->display();
    }

    //添加转账记录
    public function boxOrderInfo() {
    	$type = $_REQUEST['type']?$_REQUEST['type']:$_REQUEST['data']['type'];
    	if($type=='ajax_search'){
    		if($_REQUEST['cust_no']=='ZXCF001c07')$this->ajaxReturn(array('names'=>'公司账户'),'json');
    		$info = M('member_info')->where("fuyou_login_id='{$_REQUEST['cust_no']}'")->find();
    		if(false==$info)$this->error('查询为空');
    		$this->ajaxReturn($info,'json');
    	}elseif($type=='submit_trans'){
    		if(empty($_REQUEST['data']['out_cust_no']))$this->error("转出账户不能为空");
    		if(empty($_REQUEST['data']['in_cust_no']))$this->error("转出账户不能为空");
    		if(round($_REQUEST['data']['money'],2)<0.01)$this->error("转账金额不正确");
    		if(empty($_REQUEST['data']['pwd']))$this->error("秘钥不能为空");
    		if(md5($_REQUEST['data']['pwd'])!='1136ae657d0fd8c67c3f297c754b3007')$this->error("秘钥错误");
    		import("Think.ORG.Util.Fuyou");
    		$fuyou = new Fuyou;
    		$add_data = [];
    		$add_data['sn'] = time();
    		$add_data['out_cust_no'] = $_REQUEST['data']['out_cust_no'];
    		$add_data['in_cust_no'] = $_REQUEST['data']['in_cust_no'];
    		$add_data['money'] = $_REQUEST['data']['money'];
    		$add_data['author'] = "汤潮沛";
    		if(false==($info= M('member_info')->where("fuyou_login_id='{$_REQUEST['data']['out_cust_no']}'")->find())){
    			$info = M('member_info')->where("fuyou_login_id='{$_REQUEST['data']['in_cust_no']}'")->find();
    		}
    		if(false ==$fuyou->transferBu($info['memberid'],$add_data['out_cust_no'],$add_data['in_cust_no'],intval($add_data['money']*100),'【借吧】手动转账扣款',$add_data['sn']))
    			$this->error($fuyou->getError());
    		M('hand_trans')->add($add_data);
    		$this->success("转账成功");
    	}
    	
        $this->display('box_order_info');
    }
    
    //宝付转账列表
    public function baofu_trans_list(){
    	$params = '';
    	$key = $this->_get('k', 'trim');
    	$value = $this->_get('v', 'trim');
    	if (!empty($value)) {
    		switch($key){
    			case 'name':
    				$map['ht.id'] = array("exp"," is not null and (ht.names like '%{$value}%' or ht.names like '%{$value}%')");
    				break;
    			case 'cellphone':
    				$map['ht.mobile'] = $value;
    				break;
    		}
    		$params .= "/k/{$key}/v/{$value}";
    	}
    	 
    	
    	$count = M('baofu_hand_trans ht')->where($map)->count();
    	if ($count > 0) {
    		$this->page['count'] = $count;
    		$this->page['no'] = $this->_get('p', 'intval', 1);
    		$this->page['total'] = ceil($count / $this->page['num']);
    		$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
    		$list = M('baofu_hand_trans ht')
		    		->where($map)
		    		->order("ht.timeadd desc")
		    		->select();
    		$this->assign('list', $list);
    	}
    	$this->setPage("/Trans/baofu_trans_list{$params}/p/*.html");
    	$this->assign('keys', $this->_transkeys);
    	$this->assign('totalNum', $total);
    	$this->display();
    }
    //新增转账
    public function baofu_add_trans(){
	    $type = $_REQUEST['data']['type'];
	    if($type=='ajax_search'){
	    	$info = M("member_info mi,member m")->field("mi.*")->where("mi.memberid=m.id and m.mobile='{$_REQUEST['data']['mobile']}'")->find();
	    	if(false==$info)$this->error('查询为空');
	    	$this->ajaxReturn($info,'json');
	    }elseif($type=='submit_trans'){
	    	if(empty($_REQUEST['data']['trans_type']) || !in_array($_REQUEST['data']['trans_type'],[1,2]))$this->error("转账类型不正确");
	    	if(empty($_REQUEST['data']['mobile']))$this->error("客户手机号不能为空");
	    	if(round($_REQUEST['data']['money'],2)<0.01)$this->error("转账金额不正确");
	    	if(empty($_REQUEST['data']['pwd']))$this->error("秘钥不能为空");
	    	if(md5($_REQUEST['data']['pwd'])!='1136ae657d0fd8c67c3f297c754b3007')$this->error("秘钥错误");
	    	$memberInfo = M("member_info mi,member m")->field("mi.*")->where("mi.memberid=m.id and m.mobile='{$_REQUEST['data']['mobile']}'")->find();
		    if(false==$memberInfo){
		    	$this->error("查无此用户，请确认用户是否实名认证并绑卡");
		    }
		    if(false==$memberInfo["memberid"])$this->error("用户id不存在！");
		    
		    import("Think.ORG.Util.Baofu");
		    $baofu = new Baofu($memberInfo["memberid"]);
		    $add_data = [];
		    $add_data['sn'] = "ht-".time()."-".rand(111,999);
		    $add_data['memberid'] = $memberInfo["memberid"];
		    $add_data['money'] = $_REQUEST['data']['money'];
		    
		    if($_REQUEST['data']['trans_type']==1){
		    	//转账给用户--代付
		    	$add_data['pay_names'] = "商户账户";
		    	$add_data["collect_names"] = $memberInfo["names"];
		    	$remark = $_REQUEST['data']['remark']?$_REQUEST['data']['remark']:"【客服-主动扣款】";
		    	$result = $baofu->bfPay($add_data['sn'],$add_data['money'],$remark);
		    	if(false==$result)$this->error("转账失败(".$baofu->getError().")");
		    }else{
		    	//从用户账户转出--代扣
		    	$add_data["pay_names"] = $memberInfo["names"];
		    	$add_data['collect_names'] = "商户账户";
		    	$remark = $_REQUEST['data']['remark']?$_REQUEST['data']['remark']:"【客服-主动划款给客户】";
		    	$result = $baofu->bfCollect($add_data['sn'],$add_data['money'],$remark);
		    	if(false==$result)$this->error("转账失败(".$baofu->getError().")");
		    }
		    M('baofu_hand_trans')->add($add_data);
		    $this->success("宝付转账成功");
		 }//if
		 
	    $this->display();
	}
        
        //宝付还款列表
        public function baofuRepaymentList(){
            $params = '';
            $key = $this->_get('k', 'trim');
            $value = $this->_get('v', 'trim');
            if (!empty($value)) {
                    switch($key){
                            case 'cellphone':
                                    $map['ht.id'] = array("exp"," is not null and (ht.pay_mobile like '%{$value}%' or ht.repayment_mobile like '%{$value}%')");
                                    break;
                    }
                    $params .= "/k/{$key}/v/{$value}";
            }


            $count = M('baofu_hand_repayment ht')->where($map)->count();
            if ($count > 0) {
                    $this->page['count'] = $count;
                    $this->page['no'] = $this->_get('p', 'intval', 1);
                    $this->page['total'] = ceil($count / $this->page['num']);
                    $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
                    $list = M('baofu_hand_repayment ht')
                                    ->where($map)
                                    ->order("ht.timeadd desc")
                                    ->select();
                    $this->assign('list', $list);
            }
            $this->setPage("/Trans/baofuRepaymentList{$params}/p/*.html");
            $this->assign('keys', [ 'cellphone' => '手机号']);
            $this->assign('totalNum', $total);
            $this->display();
        }
        //新增宝付还款
        public function  addBaofuRepayment(){
             $type = $_REQUEST['data']['type'];
	    if($type=='ajax_search'){
                //付款人手机号
                if(!empty($_REQUEST["data"]["pay_mobile"])){
                    $info = M("member_info mi,member m")->field("mi.names")->where("mi.memberid=m.id and m.mobile='{$_REQUEST['data']['pay_mobile']}'")->find();
                    if(false==$info)$this->error('查询为空');
                    $this->ajaxReturn($info,'json');
                }
                //车友贷订单手机号
                if(!empty($_REQUEST["data"]["repayment_mobile"])){
                    $info = M("member_info mi,member m")->field("mi.memberid,mi.names")->where("mi.memberid=m.id and m.mobile='{$_REQUEST['data']['repayment_mobile']}'")->find();
                    if(false==$info)$this->error('查询为空');
                    import("Think.ORG.Util.Credit");
                    $credit = new Credit($info["memberid"]);
                    $repaymentInfo = $credit->repaymentInfo($info["memberid"]);
                    $this->ajaxReturn(array_merge($repaymentInfo,["names"=>$info["names"]]),'json');
                }
	    }elseif($type=='submit_trans'){
	    	if(empty($_REQUEST['data']['pay_mobile']))$this->error("付款人手机号不能为空");
                if(empty($_REQUEST['data']['repayment_mobile']))$this->error("还款订单手机号不能为空");
	    	if(empty($_REQUEST['data']['pwd']))$this->error("秘钥不能为空");
	    	if(md5($_REQUEST['data']['pwd'])!='1136ae657d0fd8c67c3f297c754b3007')$this->error("秘钥错误");
                import("Think.ORG.Util.Credit");
                $credit = new Credit($memberInfo["memberid"]);
                $repaymentResult = $credit->managerRepayment($_REQUEST['data']['pay_mobile'],$_REQUEST['data']['repayment_mobile'],$_REQUEST["data"]["remark"],$_REQUEST["data"]["trans_type"]);
                if(false==$repaymentResult)$this->error("转账失败(".$credit->getError().")");
                $this->success("宝付还款成功");
             }//if
		 
	    $this->display();
        }
    

}
