<?php 
//订单管理
$type = $this->v('type');

/*
 * 订单配置参数
 * */
$setting = array(
	'allow_no_check_login'=>'#no900428login#',//允许不检测用户登录key
	//车抵贷配置
	'borrow_setting'=>array(
			'allow_no_sms_key'=>'#wes900428wes#',//允许不发送手机验证码，就可以新增订单
			'month_point'=>array('personal_car'=>'0.6','company_car'=>'0.6'),//费率，个人车费率：1.28%，公司车费率：1.68%
			'loanmoney'=>array('min'=>'10000','max'=>'100000000'),//借款金额的限制，单位：元
            'loanmonth'=>array(1,2,3,12)
	),
	//买车贷配置
	'buy_setting'=>array(
			'allow_no_sms_key'=>'#wes900428wes#',//允许不发送手机验证码，就可以新增订单
			'first_percent'=>array('12'=>30,'18'=>30,'24'=>30),//首付百分比,12/18/24期的百分比，单位：%
			'month_point'=>0.6,//费率，单位：%
			'loanmoney'=>array('min'=>'1','max'=>'100000000'),//借款金额的限制，单位：元
	),	
	//信用贷配置
	'credit'=>array(
		'loanmoney'=>array('min'=>'1000','max'=>'10000'),//信用贷的贷款范围
	),
);



if(allow_no_check_login($setting,$this->v('allow_no_check_login'),$this->v('memberid')))
	$member_info['id'] = $this->v('memberid');
else
	$member_info = $this->token_check_force();
//配置参数
if($type=='setting'){
	$borrow_total_money = M('order')->where("status=2 and order_type=1")->field("sum(if(backtotalmoney>0,backtotalmoney,loanmoney)) as backtotalmoney")->find();
	$buy_total_money = M('order')->where("status=2 and order_type=2")->field("sum(if(backtotalmoney>0,backtotalmoney,loanmoney)) as backtotalmoney")->find();
	$borrow_count = M('order')->where("status=1 and order_type=1")->count('distinct memberid');
	$buy_count = M('order')->where("status=1 and order_type=2")->count('distinct memberid');
    $dealers = M('dealer')->where("status=1")->order("sort desc,lasttime desc,id desc")->select();
    $my_dealers = array();
    foreach($dealers as $key=>$val)$my_dealers["name{$key}"] = $val;
    $setting['dealers'] = $dealers;
    //,'dealers'=>$dealers
    $setting['DealersAndroid'] = $my_dealers;
	$this->data = array_merge($setting,array('borrow_money'=>$borrow_total_money['backtotalmoney'],'borrow_count'=>$borrow_count,'buy_money'=>$buy_total_money['backtotalmoney'],'buy_count'=>$buy_count));
	return true;
}
//订单列表
if($type=='list'){
	$page = intval($this->v('page'))>0?intval($this->v('page')):1;
	$number=intval($this->v('number'))>0?intval($this->v('number')):90;
	$status = $this->v('status');
	if(!empty($this->v('status')) && !in_array($this->v('status'),array(1,2,3)))
		$this->error("STATUS_ERR",'status参数错误');
	if($number>100)
		$this->error('NUMBER_ERR','每页的条数不得大于100条');
	$status = $status?" and o.status='{$status}'":'';
	
	$count = M('order')->table("`order` o")->where("memberid='{$member_info['id']}' {$status}")->count();
	if($count>0){
		$orderList = M('order')->table("order_process p,`order` o")
					->join("order_assed a on a.order_id=o.id")
					->field("o.*,p.customer_status,p.store_status,p.applyCode,a.id as is_assed,a.status assed_status")
					->where("o.id=p.order_id and o.memberid='{$member_info['id']}' {$status}")
					->limit((($page-1)*$number).",{$number}")
					->order("timeadd desc")->select();
		
	}
	empty($orderList)?$orderList = array():'';
    foreach($orderList as &$listsss){
        if($listsss['order_type']==1){
            $listsss['imgs'] = _STATIC_."/2015/member/image/myorder/cars.jpg";
            $listsss['titles'] = "车贷宝";
        }else if($listsss['order_type']==2){
            $listsss['imgs'] = _STATIC_."/2015/member/image/myorder/bucars.jpg";
            $listsss['titles'] = "车租宝";
        }else if($listsss['order_type']==3){
            $listsss['imgs'] =  _STATIC_."/2015/member/image/myorder/crcars.jpg";
            $listsss['titles'] = "车友贷";
        }
        if($listsss['status']==1){
            $listsss['head_title']= '审核中';
            if($listsss['customer_status']==0){
                $listsss['order_status'] = "订单完成0%";
            }elseif($listsss['store_status']==0){
                $listsss['order_status'] = "订单完成25%";
            }else{
                $listsss['order_status'] = "订单完成50%";
            }
            if($listsss['assed_status'] == null || $listsss['assed_status'] == 2){
                $listsss['ljpj'] = _STATIC_."/2015/member/image/myorder/ljpj.png";
            }else{
                $listsss['ljpj'] = _STATIC_."/2015/member/image/myorder/ypj.png";
            }
        }elseif($listsss['status']==2){
            $listsss['head_title']= '订单完成';
            $listsss['order_status'] = "订单已完成";
        }elseif($listsss['status']==3){
            $listsss['head_title']= '交易失败';
            $listsss['order_status'] = "订单关闭";
        }
    }
	$this->data = array('count'=>intval($count),'orderlist'=>$orderList);
	return true;
}
//车抵贷,新增订单
elseif($type=='borrow_add'){
	$this->need('car_type','公司车或个人车');
	$this->need('city','贷款城市');
	$this->need('loanmoney','贷款金额');
	$this->need('loanmonth','借款期限');
	$this->need('mobile','手机号');
	$this->need('names','姓名');
	$this->need('sms_code','验证码');
	//传输此码，可以不验证手机验证码
	if(!($this->v('allow_no_sms_key')==md5($setting['borrow_setting']['allow_no_sms_key'])) && !(md5($this->v('sms_code'))==$this->token('borrow_smscode') && $this->token('borrow_smsmobile')==md5($this->v('mobile').$this->token('borrow_smscode')))){
		return $this->error('VERIFY_ERROR','验证码不正确');
	}
	if(false!=M('order')->where("memberid='{$member_info['id']}' and order_type=1 and status=1")->find())$this->error('ERR','您已提交过申请，请勿重复操作，如有疑问请联系客服！');
	if(!in_array($this->v('loanmonth'),array(1,2,3,12)))
		$this->error('ERR','借款期限不正确');
	$order = array();
	$order['memberid'] = $member_info['id'];
	$order['order_type'] = 1;
	$order['car_type'] = in_array($this->v('car_type'),array(1,2))?$this->v('car_type'):1;
	$order['city'] = $this->v('city');
	$order['loanmoney'] = $this->v('loanmoney');
	$order['loanmonth'] = $this->v('loanmonth');
	$order['fee_point'] = $order['car_type']==1?$setting['borrow_setting']['month_point']['personal_car']:$setting['borrow_setting']['month_point']['company_car'];
	$order['month_money'] = $order['loanmoney'] * $order['fee_point']*0.01 *pow(1 + $order['fee_point']*0.01, $order['loanmonth']) / ( pow(1 + $order['fee_point']*0.01, $order['loanmonth']) - 1 ); 
	$order['mobile'] = $this->v('mobile');
	$order['names'] = $this->v('names');
	$order['sms_code'] = $this->v('sms_code');
	$order['source'] = $this->v('source');
	$order['customer'] = $this->v('customer');
	$order['status'] = $this->v('status')?$this->v('status'):1;
	$order['resource'] = $this->_client_;
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();
		$upload->maxSize  = 20145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  UPLOADPATH."driverLicense/";// 设置附件上传目录
		$upload->saveRule = $member_info['id'].time();//设置文件保存规则唯一
		$upload->thumb = true;
		$upload->zipImages = true;
		$upload->thumbRemoveOrigin = true;
		$upload->uploadReplace = true;//同名则替换
		$upload->thumbMaxWidth = '2000,100';
		$upload->thumbMaxHeight = '2000,100';
		$upload->thumbPrefix = 'm_,s_';
		if(!$upload->upload()) {
			$this->error('UPLOAD_ERR',"哎呀，行驶证没保存成功".$upload->getErrorMsg());
		}else{
			$info =  $upload->getUploadFileInfo();
		}
		$order['driver_license'] = $info[0]['savename'];
	//}
	if($add_id = M('order')->add($order)){
		$this->data = array('add_id'=>$add_id);
		//推荐贷款
		import("Think.ORG.Util.wxMessage");
		$msg = new wxMessage;
		$msg->applyOrder($member_info['id'],$add_id);
		//站内
		/*import("Think.ORG.Util.GetuiMessage");
        $getui = new GetuiMessage;
        $getui->applyOrder($member_info['id'],$add_id);*/
		return true;
	}
	$this->error("SYS_ERR",'系统错误稍后重试');
//买车贷,新增订单
}elseif($type=='buy_add'){
	$this->need('city','贷款城市');
	$this->need('dealer','经销商');
	$this->need('loanmoney','车商报价');
	$this->need('car_brand','车辆品牌');
	$this->need('car_class','车辆型号');
	$this->need('loanmonth','借款期限');
	$this->need('mobile','手机号');
	$this->need('names','姓名');
	$this->need('sms_code','验证码');
	//传输此码，可以不验证手机验证码
	if(!($this->v('allow_no_sms_key')==md5($setting['buy_setting']['allow_no_sms_key'])) && !(md5($this->v('sms_code'))==$this->token('buy_smscode') && $this->token('buy_smsmobile')==md5($this->v('mobile').$this->token('buy_smscode')))){
		return $this->error('VERIFY_ERROR','验证码不正确');
	}
	if(!in_array($this->v('loanmonth'),array(12,18,24)))
		$this->error('ERR','借款期限不正确');
	if(false!=M('order')->where("memberid='{$member_info['id']}' and order_type=2 and status=1")->find())$this->error('ERR','对不起，您已提交过申请，敬请等待审核结果！');
	$order = array();
	$order['memberid'] = $member_info['id'];
	$order['order_type'] = 2;
	$order['city'] = $this->v('city');
	$order['dealer'] = $this->v('dealer');
	$order['loanmoney'] = $this->v('loanmoney');
	$order['car_brand'] = $this->v('car_brand');
	$order['car_class'] = $this->v('car_class');
	$order['loanmonth'] = $this->v('loanmonth');
	$order['fee_point'] = $setting['buy_setting']['month_point']; 
	$order['first_percent'] = $setting['buy_setting']['first_percent'][$order['loanmonth']];
	$order['month_money'] = round(($order['loanmoney'] * $order['fee_point']*0.01*$order['loanmonth']+ $order['loanmoney'])/$order['loanmonth']);
	$order['first_money'] = round($order['loanmoney']*$order['first_percent']*0.01,2);
	$order['mobile'] = $this->v('mobile');
	$order['names'] = $this->v('names');
	$order['sms_code'] = $this->v('sms_code');
	$order['source'] = $this->v('source');
	$order['customer'] = $this->v('customer');
	$order['status'] = $this->v('status')?$this->v('status'):1;
	$order['resource'] = $this->_client_;
	if($add_id = M('order')->add($order)){
		$this->data = array('add_id'=>$add_id);
		//推荐贷款
		import("Think.ORG.Util.wxMessage");
		$msg = new wxMessage;
		$msg->applyOrder($member_info['id'],$add_id);
		//站内
		import("Think.ORG.Util.GetuiMessage");
        $getui = new GetuiMessage;
        $getui->applyOrder($member_info['id'],$add_id);
		return true;
	}	
	$this->error("SYS_ERR",'系统错误稍后重试');
//我的订单,删除订单
}elseif($type=='order_del'){
	$this->need('id','订单id');
	if(M('order')->where("id='{$this->v('id')}'")->delete()){
		$this->data = "删除成功";
		return true;
	}
	$this->error('SYS_ERR','系统错误');
//我的订单,修改订单
}elseif($type=='order_edit'){
	$this->need('id','订单id');
	if(M('order')->where("id='{$this->v('id')}'")->save($this->v('data'))){
		$this->data = "修改成功";
		return true;
	}
	$this->error('SYS_ERR','系统错误');
	
	
//车抵贷发送验证码
}elseif($type=='borrowSendSms'){
	$this->need('mobile','手机号');
	$mobile = $this->v('mobile');
	if($mobile!=$member_info['mobile']){
		return $this->error('MOBILE_ERROR','请用您注册手机号申请');
	}
	if($this->token('borrow_smssend_flag')==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
		return $this->error('MOBILE_ERROR','手机号不正确');
	}
	if(false==D('Member')->where(array("mobile"=>$mobile))->field('id')->find()){
		return $this->error('ACCOUNT_NOT_EXIST','手机号未注册');
	}
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token('borrow_smscode',$smscode,600);
	$this->token('borrow_smsmobile',md5($mobile.$smscode),600);
	$this->token('borrow_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
//买车贷发送验证码	
}elseif($type=='buySendSms'){
	$this->need('mobile','手机号');
	$mobile = $this->v('mobile');
	if($mobile!=$member_info['mobile']){
		return $this->error('MOBILE_ERROR','请用您注册手机号申请');
	}
	if($this->token('buy_smssend_flag')==1){
		$this->error('SEND_TOO_OFTEN','您点的太频繁了');
	}
	if(preg_match('/^1[0-9]{10}$/', $mobile)==false){
		return $this->error('MOBILE_ERROR','手机号不正确');
	}
	if(false==D('Member')->where(array("mobile"=>$mobile))->field('id')->find()){
		return $this->error('ACCOUNT_NOT_EXIST','手机号未注册');
	}
	import('Think.ORG.Util.SMS');
	$sms_res = SMS::buildverify($mobile);
	if($sms_res==false){
		return $this->error('VERIFY_SEND_FALSE','验证码发送失败');
	}
	$smscode = session('smscode');
	$this->token('buy_smscode',$smscode,600);
	$this->token('buy_smsmobile',md5($mobile.$smscode),600);
	$this->token('buy_smssend_flag',1,60);
	$this->data = '验证码发送成功,有效期为10分钟';
	return true;
}
//贷款详情
elseif($type=='loanerp'){
	$this->need('ContractNo','合同编号');
	$ContractNo = $this->v('ContractNo');
	$certinumber = M('member_info')->where("memberid='{$member_info['id']}'")->find();
	if(!empty($certinumber['certiNumber'])){
		$erpdata = getErp($certinumber['certiNumber']);
                
		foreach($erpdata['customer'] as $v){
			$_ContractNo = $v['customerinfo'][0]['ContractNo'];
			if($ContractNo==$_ContractNo){
				$loanerp = $v['customerinfo'][0];
				$loandetail = $v['repaymentplan'];
				$loanerp['is_repayment'] = 0;
				$loanerp['leftMoney'] = 0;//array_sum(array_column($loandetail,'BackTotalMoney'));//剩余还款金额
				$loanerp['periodes'] = count($loandetail);
				$loanerp['BackDate'] = '';//到期还款日
				$loanerp['BackTotalMoney'] = 0;//本期还款金额
				foreach($loandetail as $val){
					if($val['IsBacked']==1){
						$loanerp['is_repayment'] = 1;
						$loanerp['BackDate'] = $val['BackDate'];
						$loanerp['BackTotalMoney'] = 0;//$val['BackTotalMoney'];
						break;
					}else{
						$loanerp['leftMoney'] = 0;//$val['BackTotalMoney'];
					}
				}
					
			}
		}
	}
        //2017-07-04 9:30 停用erp数据
	$this->data = array('loanerp'=>$loanerp,'loandetail'=>$loandetail);
        //$this->data = array('loanerp'=>[],'loandetail'=>[]);
	return true;
}elseif($type=='assed'){
	//待评价
	$page = intval($this->v('page'))>0?intval($this->v('page')):1;
	$number=intval($this->v('number'))>0?intval($this->v('number')):10;
	if($number>100)
		$this->error('NUMBER_ERR','每页的条数不得大于100条');
	$where = array();
	$count = M()->table("`order` o,order_process p")->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id not in(select order_id from order_assed where status!=2)")->order("timeadd desc")->count();
	if($count>0)
		$list = M()->table("`order` o,order_process p")->field("o.id,o.order_type,date_format(o.timeadd,'%Y-%m-%d') as timeadd,p.applyCode,o.loanmoney,o.backtotalmoney")->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id not in(select order_id from order_assed where status!=2)")
            ->limit((($page-1)*$number).",{$number}")
            ->order("timeadd desc")->select();
	empty($list)?$list = array():'';
    foreach($list as &$lis){
        if($lis['order_type'] == 1){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/cars.jpg';
        }else if($lis['order_type'] == 2){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/bucars.jpg';
        }else if($lis['order_type'] == 3){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/crcars.jpg';
        }else{
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/cars.jpg';
        }
    }
	$this->data = array('count'=>intval($count),'assedlist'=>$list);
	return true;
}elseif($type=='asseded'){//已评价
    $page = intval($this->v('page'))>0?intval($this->v('page')):1;
    $number=intval($this->v('number'))>0?intval($this->v('number')):10;
    $where = array();
    $list = M('order')->table("order_process p,`order` o")
            ->join("order_assed a on a.order_id=o.id")
            ->field("o.id,o.order_type,date_format(o.timeadd,'%Y-%m-%d') as timeadd,p.applyCode,o.loanmoney,o.backtotalmoney,a.id as is_assed,a.status assed_status")
            ->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id in(select order_id from order_assed where status!=2)")
            ->order("timeadd desc")
            ->limit((($page-1)*$number).",{$number}")
            ->select();
    empty($list)?$list = array():'';
    foreach($list as &$lis){
        if($lis['order_type'] == 1){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/cars.jpg';
        }else if($lis['order_type'] == 2){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/bucars.jpg';
        }else if($lis['order_type'] == 3){
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/crcars.jpg';
        }else{
            $lis['logo'] = _STATIC_.'/2015/member/image/myorder/cars.jpg';
        }
    }
    $this->data = array('count'=>intval(count($list)),'assedlist'=>$list);
    return true;
}elseif($type=='buydetail'){
	$this->need('order_id','order_id');
	$order_id = $this->v('order_id');
	$detail = M()->table("`order` o,order_process p")->field("o.id,o.order_type,o.city,o.dealer,o.car_class,o.car_brand,date_format(o.timeadd,'%Y年%m月%d日') as timeadd,p.applyCode,o.loanmoney,o.backtotalmoney,o.loanmonth")->where("o.id='{$order_id}' and  o.id=p.order_id")->find();
	$this->data = array('data'=>$detail);
	return true;
}else if($type == 'is_self_order'){
    $this->need('order_id','order_id');
    $order_id = $this->v('order_id');
    $whe['memberid'] = $member_info['id'];
    $whe['id'] = $this->v('order_id');
    $is_self_order = M('order')->where($whe)->find();
    if(!$is_self_order){
        $this->error('TYPE_ERR','参数错误');
        return false;
    }
    $wh['order_id'] = $order_id;
    $wh['customer_status'] = array('in','1,4,5,6');
    $is_order_status = M('order_process')->where($wh)->find();
    if(!$is_order_status){
        $this->error('TYPE_ERR','参数错误');
        return false;
    }
    $logo = '';
    if($is_self_order['order_type'] == 1){
        $logo = _STATIC_.'/2015/member/image/myorder/cars.jpg';
    }else if($is_self_order['order_type'] == 2){
        $logo = _STATIC_.'/2015/member/image/myorder/bucars.jpg';
    }else if($is_self_order['order_type'] == 3){
        $logo = _STATIC_.'/2015/member/image/myorder/crcars.jpg';
        $credit = M('order_process')->where("order_id='{$order_id}'")->find();
        $is_order_status['applyCode'] = $credit['order_sn'];
    }else{
        $logo = _STATIC_.'/2015/member/image/myorder/cars.jpg';
    }
    $this->data = array('data'=>array('logo'=>$logo,'applyCode'=>$is_order_status['applyCode'],'backtotalmoney'=>$is_self_order['backtotalmoney']?$is_self_order['backtotalmoney']:$is_self_order['loanmoney']));
    return true;
}

$this->error('TYPE_ERR','type类型错误');

//允许略过检测登陆
function allow_no_check_login($setting,$allow_key,$memberid){
	return md5($setting['allow_no_check_login']) ==$allow_key && $memberid && M('member')->where("id='{$memberid}'")->find();
}

//获取erp数据
function getErp($certiNumber){
	$certijson = "{\"IDCard\":'{$certiNumber}'}";
	$hmac = md5($certijson."123456");
	$url = "http://zxcf.imwork.net:86/Handler/LoanExternalInterface.ashx?merCode=ZhiXin&interfaceName=sqidcard&content={$certijson}&hmac={$hmac}";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, array());
	$data = curl_exec($ch);
	curl_close($ch);
	$data = json_decode($data,true);
	if($data['customer']){
		$data['customer'] = json_decode($data['customer'],true);
		foreach($data['customer'] as &$v){
			$v['customerinfo'] = json_decode($v['customerinfo'],true);
			$v['repaymentplan'] = json_decode($v['repaymentplan'],true);
		}
	}
		
	$data = change($data);
        
        
	return $data;
}
//去除非订单提交过来erp数据
function change($data){
	foreach($data['customer'] as $k=>$v){
		foreach($v['customerinfo'] as $key=>$val){
			$ContractNo = $val['ContractNo'];
			if(false==M('order_process')->where("applyCode='{$ContractNo}' and applyCode!=''")->find())
				unset($ContractNo);
		}
		
	}
	return $data;
}
?>