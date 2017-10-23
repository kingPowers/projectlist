<?php  
/**
 * Description of CommonAction
 * 订单管理
 */
class OrderAction extends CommonAction {
	private $_ids = array('admin'=>'1','customer'=>'2','store'=>'3');
	private $_keys = array( 'realname' => '真实姓名','name' => '申请人姓名', 'username' => '注册手机号','store'=>'门店');
	private $_status = array('no'=>'','none' => '未操作', 'store_first' => '已发门店','store_second'=>'门店接单');
	
	protected function _initialize() {
		if(in_array($_SESSION['user']['groupid'],array(19,22))){
			$_SESSION['user']['groupid'] = 2;
		}
		//$_SESSION['user']['groupid'] = 3;
	}
	//买车贷首页
	public function index(){
		$_REQUEST['order_type'] = 2;
		$list = $this->index_data(true,'buy');
		$this->setPage("/Order/index{$list['params']}/p/*.html");
		$this->assign('list', $list);
		$this->assign('keys', array_merge($this->_keys,array('dealer'=>'经销商')));
		$this->assign('_ids',$this->_ids);
		$this->assign('_status', $this->_status);
		$this->display();
	}
	//导出买车贷订单
	public function export_buy(){
		$_REQUEST['order_type'] = 2;
		$data = $this->index_data(false,'buy');
		$this->export_data(array('data'=>$data['data'],'cols'=>$data['column']));
	}
	
	//车抵贷首页
	public function borrow_index(){
		$_REQUEST['order_type'] = 1;
		$list = $this->index_data(true,'borrow');
		$this->setPage("/Order/borrow_index{$list['params']}/p/*.html");
		$this->assign('list', $list);
		$this->assign('keys', $this->_keys);
		$this->assign('_ids',$this->_ids);
		$this->assign('_status', $this->_status);
		$this->display();
	}
	//导出车抵贷订单
	public function export_borrow(){
		$_REQUEST['order_type'] = 1;
		$data = $this->index_data(false,'borrow');
		$this->export_data(array('data'=>$data['data'],'cols'=>$data['column']));
	}
	
	//新增买车贷订单
	public function add_buy_order(){
		if(isset($_POST) && !empty($_POST['data']['mobile'])){
			if(!($is_resister = M('member')->where("mobile='{$_POST['data']['mobile']}'")->find())){
				$this->error("申请人手机号未注册！");
			}
			$params = array();
			$params = $_POST['data'];
			$params['_cmd_'] = "order";
			$params['type'] = "buy_add";
			$params['allow_no_check_login'] = md5("#no900428login#");
			$params['memberid'] =$is_resister['id'];
			$params['sms_code'] = '000000';
			$params['allow_no_sms_key'] = md5("#wes900428wes#");
			$params['status'] = 1;
			$params['source'] = $_SESSION['user']['username']."添加";
			$service_res = $this->service($params);
			if(0===$service_res['errorcode'])
				$this->success("添加成功");
			else 
				$this->error($service_res['errormsg']);
		}
		$this->assign('dealer',M('dealer')->where("status=1")->order("sort desc")->select());	
		$this->display();
	}
	//新增车抵贷订单
	public function add_borrow_order(){
		if(isset($_POST) && !empty($_POST['mobile'])){
			if(false==($is_resister = M('member')->where("mobile='{$_POST['mobile']}'")->find())){
				$this->error("申请人手机号未注册！");
			}
			$params = array();
			$params = $_POST;
			$params['_cmd_'] = "order";
			$params['type'] = "borrow_add";
			$params['allow_no_check_login'] = md5("#no900428login#");
			$params['memberid'] =$is_resister['id'];
			$params['sms_code'] = '000000';
			$params['allow_no_sms_key'] = md5("#wes900428wes#");
			$params['status'] = 1;
			$params['source'] = $_SESSION['user']['username']."添加";
			$imgUrl = UPLOADPATH."tmp/".$is_resister['id']."_".time()."_".$drive_pic['name'];
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize  = 20145728 ;// 设置附件上传大小
			$upload->allowExts  = array('jpg', 'png', 'jpeg');// 设置附件上传类型
			$upload->savePath =  UPLOADPATH."tmp/";// 设置附件上传目录
			$upload->saveRule = time();//设置文件保存规则唯一
			//$upload->thumb = true;
			$upload->zipImages = true;
			$upload->thumbRemoveOrigin = true;
			$upload->uploadReplace = true;//同名则替换
			//$upload->thumbMaxWidth = '2000,100';
			//$upload->thumbMaxHeight = '2000,100';
			//$upload->thumbPrefix = 'm_,s_';
			if(!$upload->upload()) {
				$this->error($upload->getErrorMsg());
			}else{
				$info = $upload->getUploadFileInfo();
			}
			$params['driverLicense'] =  new CURLFile(UPLOADPATH."tmp/".$info[0]['savename']);
			$service_res = $this->service($params);
			if(0===$service_res['errorcode'])
				$this->success("添加成功");
			else
				$this->error($service_res['errormsg']);
		}
		$this->assign('dealer',M('dealer')->where("status=1")->order("sort desc")->select());
		$this->display();
	}
	
	//订单处理--接单
	public function order_process_allow(){
		//车租宝
		if($_POST && !empty($_POST['data']['buy_id'])){
			if($_SESSION['user']['groupid']==$this->_ids['customer']){
				//客服
				if(empty($_POST['data']['city'])){
					$this->error('贷款城市不能为空');
				}
				if(empty($_POST['data']['dealer'])){
					$this->error('经销商不能为空');
				}
				if(empty($_POST['data']['store_id'])){
					$this->error('分发门店不能为空');
				}
				$order_id = M('order')->where("id='{$_POST['data']['buy_id']}'")->save(array('city'=>$_POST['data']['city'],'dealer'=>$_POST['data']['dealer']));
				$process_id = M('order_process')->where("order_id='{$_POST['data']['buy_id']}'")->save(array('customer_status'=>1,'store_id'=>$_POST['data']['store_id'],'customer'=>$_SESSION['user']['username'],'customer_time'=>date("Y-m-d H:i:s")));
				if($order_id && $process_id)
					$this->success("接单并分发成功");
				$this->error("保存失败");
			}
			//门店
			if($_SESSION['user']['groupid']==$this->_ids['store']){
				if($_POST['data']['first']){
					$process_id = M('order_process')->where("order_id='{$_POST['data']['buy_id']}'")->save(array('store_status'=>1,'store_time'=>date("Y-m-d H:i:s")));
					if($process_id)
						$this->success("接单成功");
					$this->error("保存失败");
				}
				
				if(empty($_POST['data']['names'])){
					$this->error('客户真实姓名不能为空');
				}
				if(empty($_POST['data']['certiNumber'])){
					$this->error('客户身份证号不能为空');
				}
				if(empty($_POST['data']['applyCode'])){
					$this->error('合同编号不能为空');
				}
				if(empty($_POST['data']['backtotalmoney'])){
					$this->error('客户实际贷款金额不能为空');
				}
				if(empty($_POST['data']['dealer'])){
					$this->error('经销商不能为空');
				}
				if(empty($_POST['data']['loanmonth'])){
					$this->error('借款期限不能为空');
				}
				if(empty($_POST['data']['memberid'])){
					$this->error('memberid不能为空');
				}
				if(empty($_POST['data']['store_manager'])){
					$this->error('业务员姓名不能为空');
				}
				if(false==$this->isCreditNo($_POST['data']['certiNumber'])){
					$this->error("身份证号不正确");
				}
				if(false!=M('member_info')->where("memberid!='{$_POST['data']['memberid']}' and certiNumber='{$_POST['data']['certiNumber']}'")->find()){
					$this->error("对不起，此身份证已被绑定过了");
				}
				$is_exists = M('member_info')->where("memberid='{$_POST['data']['memberid']}' and memberid!=''")->find();
				if($is_exists && !empty($is_exists['certiNumber']) && strtoupper(trim($is_exists['certiNumber']))!=strtoupper(trim($_POST['data']['certiNumber']))){
					$this->error("对不起，此身份证号与注册账号的身份证号不一致");
				}
				if($is_exists && !empty($is_exists['names']) && trim($is_exists['names'])!=trim($_POST['data']['names'])){
					$this->error("对不起，此姓名与注册账号的真实姓名不一致");
				}
				$member_id = M('member_info')->where("memberid='{$_POST['data']['memberid']}' and memberid!=''")->save(array('names'=>$_POST['data']['names'],'certiNumber'=>$_POST['data']['certiNumber']));
				$order_save = array('status'=>'2','backtotalmoney'=>$_POST['data']['backtotalmoney'],'dealer'=>$_POST['data']['dealer'],'loanmonth'=>$_POST['data']['loanmonth'],'month_money'=>$this->repayment($_POST['data']['buy_id'],$_POST['data']['backtotalmoney'],$_POST['data']['loanmonth']));
				$order_id = M('order')->where("id='{$_POST['data']['buy_id']}'")->save($order_save);
				$process_id = M('order_process')->where("order_id='{$_POST['data']['buy_id']}'")->save(array('store_status'=>2,'applyCode'=>$_POST['data']['applyCode'],'store_manager'=>$_POST['data']['store_manager'],'store_time'=>date("Y-m-d H:i:s")));
				//推荐贷款成功
				import("Think.ORG.Util.wxMessage");
				$msg = new wxMessage;
				$msg->applyOrderSuccessMessage($_POST['data']['memberid'],$_POST['data']['buy_id']);
				
				if($member_id && $order_id && $process_id)
					$this->success("订单处理成功");
				$this->error("保存失败");
			}
			$this->error('对不起，您没有权限处理此订单！');
		}
		
		//车贷宝
		if($_POST && !empty($_POST['data']['borrow_id'])){
			//客服
			if($_SESSION['user']['groupid']==$this->_ids['customer']){
				if(empty($_POST['data']['city'])){
					$this->error('贷款城市不能为空');
				}
				if(empty($_POST['data']['store_id'])){
					$this->error('分发门店不能为空');
				}
				$order_id = M('order')->where("id='{$_POST['data']['borrow_id']}'")->save(array('city'=>$_POST['data']['city']));
				$process_id = M('order_process')->where("order_id='{$_POST['data']['borrow_id']}'")->save(array('customer_status'=>1,'store_id'=>$_POST['data']['store_id'],'customer'=>$_SESSION['user']['username'],'customer_time'=>date("Y-m-d H:i:s")));
				if($order_id && $process_id)
					$this->success("接单并分发成功");
				$this->error("保存失败");
			}
			//门店
			if($_SESSION['user']['groupid']==$this->_ids['store']){
				
				if($_POST['data']['first']){
					$process_id = M('order_process')->where("order_id='{$_POST['data']['borrow_id']}'")->save(array('store_status'=>1,'store_manager'=>$_POST['data']['store_manager'],'store_time'=>date("Y-m-d H:i:s")));
					if($process_id)
						$this->success("接单成功");
					$this->error("保存失败");
				}
				
				if(empty($_POST['data']['names'])){
					$this->error('客户真实姓名不能为空');
				}
				if(empty($_POST['data']['certiNumber'])){
					$this->error('客户身份证号不能为空');
				}
				/*if(empty($_POST['data']['backtotalmoney'])){
					$this->error('客户实际贷款金额不能为空');
				}*/
				if(empty($_POST['data']['applyCode'])){
					$this->error('合同编号不能为空');
				}
				if(empty($_POST['data']['store_manager'])){
					$this->error('业务员姓名不能为空');
				}
				if(empty($_POST['data']['memberid'])){
					$this->error('memberid不能为空');
				}
				
				if(false==$this->isCreditNo($_POST['data']['certiNumber'])){
					$this->error("身份证号不正确");
				}
				if(false!=M('member_info')->where("memberid!='{$_POST['data']['memberid']}' and certiNumber='{$_POST['data']['certiNumber']}'")->find()){
					$this->error("对不起，此身份证已被绑定过了");
				}
				
				$is_exists = M('member_info')->where("memberid='{$_POST['data']['memberid']}' and memberid!=''")->find();
				if($is_exists && !empty($is_exists['certiNumber']) && strtoupper(trim($is_exists['certiNumber']))!=strtoupper(trim($_POST['data']['certiNumber']))){
					$this->error("对不起，此身份证号与注册账号的身份证号不一致");
				}
				if($is_exists && !empty($is_exists['names']) && trim($is_exists['names'])!=trim($_POST['data']['names'])){
					$this->error("对不起，此姓名与注册账号的真实姓名不一致");
				}
				
				$member_id = M('member_info')->where("memberid='{$_POST['data']['memberid']}' and memberid!=''")->save(array('names'=>$_POST['data']['names'],'certiNumber'=>$_POST['data']['certiNumber']));
				$order_save = array('status'=>'2','month_money'=>$this->repayment($_POST['data']['borrow_id'],$_POST['data']['backtotalmoney']));
				$order_id = M('order')->where("id='{$_POST['data']['borrow_id']}'")->save($order_save);
				$process_id = M('order_process')->where("order_id='{$_POST['data']['borrow_id']}'")->save(array('store_status'=>2,'applyCode'=>$_POST['data']['applyCode'],'store_manager'=>$_POST['data']['store_manager'],'store_time'=>date("Y-m-d H:i:s")));
				//推荐贷款成功
				import("Think.ORG.Util.wxMessage");
				$msg = new wxMessage;
				$msg->applyOrderSuccessMessage($_POST['data']['memberid'],$_POST['data']['borrow_id']);
				
				if($member_id && $order_id && $process_id)
					$this->success("订单处理成功");
				$this->error("保存失败");
			}
			$this->error('对不起，您没有权限处理此订单！');
		}
        //信用贷
        if($_POST && !empty($_POST['data']['recint_id'])){
            if($_SESSION['user']['groupid']==$this->_ids['customer']){
                if(!M('order_process')->inTrans()){
                    M('order_process')->startTrans();
                }
                try{
                    $order_sn = 'sn'.$_POST['data']['recint_id'].'_'.time();
                    $process_id = M('order_process')->where("order_id='{$_POST['data']['recint_id']}'")->save(array('customer_status'=>1,'customer'=>$_SESSION['user']['username'],'customer_time'=>date("Y-m-d H:i:s")));
                       
                    $credit_id = M('order_credit')->where("order_id='{$_POST['data']['recint_id']}'")->save(array('pass_time'=>date("Y-m-d H:i:s"),'order_sn'=>$order_sn));
                    if($process_id===false || $credit_id===false){
                        $this->error("保存失败");
                        throw new Exception('保存失败');
                    }
                    //生成pdf文件
                    import('Think.ORG.Util.PDF');
                    $wh['order.id'] = $_POST['data']['recint_id'];
                    $pdf_info = M('order')
                        ->join('member_info on member_info.memberid=order.memberid')
                        ->join('order_credit on order_credit.order_id = order.id')
                        ->field('`order`.id,`order`.timeadd,certiNumber,member_info.names names,pay_time,back_time,order.loanmoney loanmoney,order_credit.contract_num,`order`.mobile')
                        ->where($wh)
                        ->find();
                    if(empty($pdf_info)){
                        $this->error("订单信息有误");
                        throw new Exception('保存失败');
                    }
                    $pdf_info['pay_time'] = date('Y年m月d日');
                    $pdf_info['back_time'] = date('Y年m月d日',strtotime("+7 days"));
                    $pdf_info['big_loanmoney'] = cny($pdf_info['loanmoney']);//大写人民币
                    $pdf_info['order_sn'] = $order_sn;
                    
                    $order_id = $_POST['data']['recint_id'];
                    $is_staff = M('staff s')
                    ->join('member_info mi on mi.certiNumber=s.certiNumber')
                    ->join('`order` o on o.memberid=mi.memberid')
                    ->where("o.id='{$order_id} and s.status=0'")
                    ->find();
                    if($is_staff){
                       $pdf_info['contract_str'] = " ";	
                    }else{
                       $pdf_info['contract_str'] = "<p class='sp'>8.借款人承诺：因乙方在丙方的撮合下与甲方有其他借款行为（借款协议编号：{$pdf_info['contract_num']}），且乙方已提供了抵押车辆担保，故为了保障本借款协议的履行，现乙方同意以其现有财产作为本借款协议的保证（包括但不限于上述其他借款行为所抵押登记的车辆），若乙方违反本协议约定逾期还款，则乙方同意甲方或其授权方采取拖回抵押车辆等方式实现资产变现以偿还甲方的借款。</p>";
                    }
                    if(time() > strtotime("2017-03-27 00:00:00")){
                    	$pdf_info['delay_text'] = '借款人逾期达1至5日（含），逾期滞纳金=剩余总本金*1%*逾期天数；借款人逾期达6日（含）以上，逾期滞纳金=剩余总本金*1%*5+剩余总本金*2%*（逾期天数-5），自逾期之日起按本协议约定按日计收逾期罚息直至清偿完毕之日止（不含清偿完毕之日）。';
                    }else{
                    	$pdf_info['delay_text'] = '借款人逾期的，逾期罚息=剩余总本金*1%*逾期天数，自逾期之日起按本协议约定按日计收逾期罚息直至清偿完毕之日止（不含清偿完毕之日）。';
                    }
                    PDF::makenew($pdf_info);//生成个人协议pdf
                    if(time()>=strtotime('2017-02-06')){
	                     $pdf_info['rate'] = 4;
                     }else{
                     	 $pdf_info['rate'] = 3;
                     }
                    PDF::makenew1($pdf_info);//生成居间协议
                    PDF::makenew3($pdf_info['id']);//补充协议
                    M('order_process')->commit();
                    PDF::makenew2($pdf_info['id']);//还款承诺书，需要commit以后
                    $this->success("接单成功");
                }catch (Exception $ex){
                    M('order_process')->rollback();
                    $this->error("保存失败");
                }
            }
            $this->error('对不起，您没有权限处理此订单！');
        }
		$order =M('order')->table("`order` o,order_process p")->field("o.id as o_id,o.*,p.*")->where("o.id=p.order_id and o.id='{$_GET['id']}'")->find();
		$this->assign('order',$order);
		$this->assign('dealer',M('dealer')->where("status=1")->order("sort desc")->select());
		$this->assign('member_info',M('member_info')->where("memberid='{$order['memberid']}' and memberid!=''")->find());
		$type = $order['order_type']==1?2:1;		
		$this->assign('store',M('store')->where("status=1 and type='{$type}'")->order("(case when name like '%{$order['city']}%' then 1 else 0 end ) desc ,sort desc")->select());
		$this->assign('_ids',$this->_ids);
		$this->display();
	}
	
	//修改订单
	public function editOrder(){
		$config = array(
			'first_max_days'=>7,//首单7天内可以修改身份证号、真实姓名
			'other_max_days'=>7,//非首单7天内可以修改实际放款金额、合同编号
			'per_order_nums'=>1,//每一单限制修改次数
			'allow_role_edit'=>"{$this->_ids['store']}",//允许修改订单的角色,多个角色逗号隔开
		);
		$order_id = $_REQUEST['id']?$_REQUEST['id']:$_POST['data']['order_id'];
		$order_info  = M('order')->table("`order` o,order_process p,member_info i")->field("o.id,o.order_type,i.names,i.certiNumber,o.timeadd,o.memberid,p.edit_order_num,p.applyCode,o.backtotalmoney,o.loanmoney")->where("o.id=p.order_id and o.memberid=i.memberid and o.id='{$order_id}' ")->find();
		$is_first = M('order')->where("memberid='{$order_info['memberid']}' and status=2 and order_type in(1,2)")->order("timeadd asc")->find();
		$_first = ($is_first['id']==$order_info['id']);
		$_is_allow_edit = $order_info['edit_order_num']<$config['per_order_nums']?1:0;
		if($_POST && !empty($_POST['data']['order_id'])){
			//门店修改订单
			if($_SESSION['user']['groupid']==$this->_ids['store']  && $_is_allow_edit){
				if($_first){
					if(empty($_POST['data']['names'])){
						$this->error('客户真实姓名不能为空');
					}
					if(empty($_POST['data']['certiNumber'])){
						$this->error('客户身份证号不能为空');
					}
					if(false==$this->isCreditNo($_POST['data']['certiNumber'])){
						$this->error("身份证号不正确");
					}
					if(false!=M('member_info')->where("memberid!='{$order_info['memberid']}' and certiNumber='{$_POST['data']['certiNumber']}'")->find()){
						$this->error("对不起，此身份证已被绑定过了");
					}
					M('member_info')->where("memberid='{$order_info['memberid']}' and memberid!=''")->save(array('names'=>$_POST['data']['names'],'certiNumber'=>$_POST['data']['certiNumber']));
				}
				if(empty($_POST['data']['applyCode'])){
					$this->error('合同编号不能为空');
				}
				if(2==$order_info['order_type'] && empty($_POST['data']['backtotalmoney'])){
					$this->error('客户实际贷款金额不能为空');
				}
				if(2==$order_info['order_type']){
					$order_save = array('month_money'=>$this->repayment($_POST['data']['order_id'],$_POST['data']['backtotalmoney']),'backtotalmoney'=>$_POST['data']['backtotalmoney']);
					M('order')->where("id='{$_POST['data']['order_id']}'")->save($order_save);
				}
				$process_id = M('order_process')->where("order_id='{$_POST['data']['order_id']}'")->save(array('applyCode'=>$_POST['data']['applyCode'],'edit_order_num'=>1));
				if($process_id)
					$this->success("订单修改成功");
				$this->error("保存失败");
			}
				$this->error('对不起，您没有权限处理此订单！');
		 }//post
		 
		$this->assign('allow_edit_num',$_is_allow_edit);//是否允许修改订单
		$_is_allow_edit = $_first?($_is_allow_edit && time()<strtotime("+{$config['first_max_days']} days",strtotime($order_info['timeadd']))):
								  ($_is_allow_edit && time()<strtotime("+{$config['other_max_days']} days",strtotime($order_info['timeadd'])));
		$this->assign('config',$config);
		$this->assign('order_info',$order_info);
		$this->assign('is_allow_edit',$_is_allow_edit);//是否允许修改订单
		$this->assign("is_first",$_first?1:0);//是否首单
		$this->assign('allow_first_days',time()<strtotime("+{$config['first_max_days']} days",strtotime($order_info['timeadd']))?1:0);//首单是否5天内
		$this->assign('allow_other_days',time()<strtotime("+{$config['other_max_days']} days",strtotime($order_info['timeadd']))?1:0);//其他单是否5天内
		$this->display();
	}
	
	/*
	 * 客服修改订单，次数不限制
	 * */
	public function _customer_order(){
		$order_id = $_REQUEST['id']?$_REQUEST['id']:$_POST['data']['order_id'];
		if($_POST && !empty($_POST['data']['order_id'])){
			if(empty($_POST['data']['applyCode'])){
				$this->error("合同编号不能为空");
			}
			$process_id = M('order_process')->where("order_id='{$order_id}'")->save(array('applyCode'=>$_POST['data']['applyCode']));
			if($process_id)
				$this->success("订单修改成功");
			$this->error("订单修改失败");
		}
		$order_info  = M('order')->table("`order` o,order_process p,member_info i")->field("o.id,o.order_type,i.names,i.certiNumber,o.timeadd,o.memberid,p.edit_order_num,p.applyCode,o.backtotalmoney,o.loanmoney")->where("o.id=p.order_id and o.memberid=i.memberid and o.id='{$order_id}' ")->find();
		$this->assign('order_info',$order_info);
		$this->display();
	}
	
	//订单处理--拒单
	public function order_process_refuse(){
		if($_POST && !empty($_POST['data']['order_id'])){
            if(!empty($_POST['data']['statuss']) && $_POST['data']['statuss']){
                $statuss = $_POST['data']['statuss'];
                $error_message = '请登录客服账户拒单';
            }else{
                $statuss = 2;
                $error_message = '请登录客服账户或门店账户拒单';
            }
			if($_SESSION['user']['groupid']==$this->_ids['customer']){
				if(empty($_POST['data']['remark']))
					$this->error("请填写拒单理由");
				//客服拒单
				$order_id = M('order')->where("id='{$_POST['data']['order_id']}'")->save(array('status'=>3));
				$save_arr = array(
						'customer_status'=>$statuss,
						'customer'=>$_SESSION['user']['username'],
						'customer_time'=>date("Y-m-d H:i:s"),
						'customer_remark'=>$_POST['data']['remark'],
				);
				$process_id = M('order_process')->where("order_id='{$_POST['data']['order_id']}'")->save($save_arr);
				if($order_id && $process_id){
					/*
					 * 拒单，回调此公共方法
					* */
					import("Think.ORG.Util.RunCommon");
					$orderInfo = M('order')->where("id='{$_POST['data']['order_id']}'")->find();
					RunCommon::runApplyOrderRefuse(["order_type"=>$orderInfo['order_type'],'order_id'=>$orderInfo['id'],'memberid'=>$orderInfo["memberid"]]);
					$this->success("拒单成功");
				}
					
				$this->error("保存失败");
			}
			if($_SESSION['user']['groupid']==$this->_ids['store']){
				//门店拒单
				if(empty($_POST['data']['remark']))
					$this->error("请填写拒单理由");
				$proce_info = M('order_process')->where("order_id='{$_POST['data']['order_id']}'")->find();
				$process_status = $proce_info['store_status']==1?4:3;
				$order_id = M('order')->where("id='{$_POST['data']['order_id']}'")->save(array('status'=>3));
				$save_arr = array(
						'store_status'=>$process_status,
						//'store_manager'=>$_SESSION['user']['username'],
						'store_time'=>date("Y-m-d H:i:s"),
						'store_remark'=>$_POST['data']['remark'],
				);
				$process_id = M('order_process')->where("order_id='{$_POST['data']['order_id']}'")->save($save_arr);
				if($order_id && $process_id)
					$this->success("拒单成功");
				$this->error("保存失败");
			}
			$this->error($error_message);
		}
		//$this->error("非法操作");
		$this->display();
	}
	
//----------------------------------------工具方法------------------------------------------	
	//订单数据
	private function index_data($is_page,$order_type = 'borrow'){
		empty($_REQUEST['status'])?($_REQUEST['status']=1):'';
		$key = $this->_get('k', 'trim');
		$value = $this->_get('v', 'trim');
		
		$starttime = $_REQUEST['starttime'];
		$endtime = $_REQUEST['endtime'];
		
		
		if (!empty($value)) {
			switch($key){
				case 'username':
					$where[] = "m.mobile = '{$value}'";
					break;
				case 'name':
					$where[] = "o.names = '{$value}'";
					break;
				case 'realname':
					$where[] = "mi.names = '{$value}'";
					break;
				case 'cellphone':
					$where[] = "o.mobile = '{$value}'";
					break;
				case 'dealer':
					$where[] = "o.dealer = '{$value}'";
					break;
				case 'store':
					$where[] = "p.store_id in(select id from store where name like '%{$value}%')";
					break;
			}
			$params .= "/k/{$key}/v/{$value}";
		}
		
		$status_key = $this->_get('status_k', 'trim');
		if (!empty($status_key)) {
			switch($status_key){
				case 'none':
					$where[] = "p.customer_status =0 and p.store_status=0";
					break;
				case 'store_first':
					$where[] = "p.customer_status =1 and p.store_status=0";
					break;
				case 'store_second':
					$where[] = "p.customer_status =1 and p.store_status=1";
					break;
			}
			$params .= "/status_k/{$status_key}/";
		}
		
		if (!empty($_REQUEST['status'])) {
			$params .= "/status/{$_REQUEST['status']}";
			$where[] = "o.status = '{$_REQUEST['status']}'";
		}
		
		if (!empty($_REQUEST['order_type'])) {
			$params .= "/order_type/{$_REQUEST['order_type']}";
			$where[] = "o.order_type = '{$_REQUEST['order_type']}'";
		}
		
		if($starttime){
			$where[] = ' Date(o.timeadd) >= "'.$starttime.'"';
			$params .= '/starttime/'.$starttime;
		}
		if($endtime){
			$where[] = ' Date(o.timeadd) <= "'.$endtime.' 23:59:59"';
			$params .= '/endtime/'.$endtime;
		}
		
		if($_SESSION['user']['groupid']==$this->_ids['store']){
			$where[] = "o.id in(select o.id from `order` o,order_process p,store s where s.manager='{$_SESSION['user']['username']}' and s.id=p.store_id and  p.order_id=o.id)";
		}elseif($_SESSION['user']['groupid']==24){//该权限组内可以查看指定门店的订单
                    	$where[] = "o.id in(select o.id from `order` o,order_process p,store s where s.type=2 and s.id=p.store_id and  p.order_id=o.id and (s.name not like '%长圣%' and s.name not like '%荣耀%' and s.name not like '%六盘水%' and s.name not like '%攀枝花%' and s.name not like '%隆泰%'  and s.name not like '%郑州%'))";
                }
		
		$_car_type = "case when o.car_type=1 then '个人车' when o.car_type=2 then '公司车' end  as car_type_name";
		$_status_name = "case 	when p.customer_status=0 and p.store_status=0 then '未操作' 
									when p.customer_status=1 and p.store_status=0 then '已发门店' 
									when p.customer_status=2 and p.store_status=0 then '客服拒单'  
									when p.customer_status=1 and p.store_status=1 then  '门店接单'
									when p.customer_status=1 and p.store_status=2 then  '贷款成功'
									when p.customer_status=1 and p.store_status=3 then  '门店直接拒单'
									when p.customer_status=1 and p.store_status=4 then  '门店审核后拒单'
									end as status_name";
		$field = array(
			$this->_ids['admin']=>array(
				'buy_column'=>array(//买车贷
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								array('分发门店','store_name','replace_ignore',1),
								//array('来源','source','source','0'),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('客服拒单','customer_remark','p.customer_remark',1),
								array('门店拒单','store_remark','p.store_remark',1),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
					
				),
				'borrow_column'=>array(
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('推荐人','recintcode','m.recintcode',1),
								array('车辆种类','car_type_name','car_type_name',1),
								array('贷款金额','loanmoney','loanmoney',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('申请次数','apply_count','m.timeadd as apply_count','1'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('驾驶证','driver_license','o.driver_license',1),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('合同金额','ApplayMoney','loanmoney as lo',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('客服拒单','customer_remark','p.customer_remark',1),
								array('门店拒单','store_remark','p.store_remark',1),
								array('驾驶证','driver_license','o.driver_license',1),
								array('状态','status_name','status_name',1),
								array('订单来源','resource','o.resource',1),
								array('操作','ddd','ddd','0'),
						),
				),
			),
			//客服
			$this->_ids['customer']=>array(
				'buy_column'=>array(//买车贷
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('客服拒单','customer_remark','p.customer_remark',1),
								array('门店拒单','store_remark','p.store_remark',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
					
				),
				'borrow_column'=>array(
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('推荐人','recintcode','m.recintcode',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('申请次数','apply_count','m.timeadd as apply_count','1'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('合同金额','ApplayMoney','loanmoney as lo',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('客服拒单','customer_remark','p.customer_remark',1),
								array('门店拒单','store_remark','p.store_remark',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
				),
					
			),
			//门店
			$this->_ids['store']=>array(
				'buy_column'=>array(//买车贷
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								//array('真实姓名','real_names','mi.names as real_names',1),
								//array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								//array('真实姓名','real_names','mi.names as real_names',1),
								//array('身份证号','certiNumber','mi.certiNumber',1),
								array('车商报价(元)','loanmoney','loanmoney','1'),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('经销商','dealer','dealer','1'),
								array('车辆品牌','car_brand','car_brand','1'),
								array('车辆型号','car_class','car_class','1'),
								//array('首付比例(%)','first_percent','first_percent','0'),
								//array('首付金额(元)','first_money','first_money','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('拒单理由','store_remark','p.store_remark',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
					
				),
				'borrow_column'=>array(
						1=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('推荐人','recintcode','m.recintcode',1),
								//array('真实姓名','real_names','mi.names as real_names',1),
								//array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								array('驾驶证','driver_license','o.driver_license',1),
								//array('来源','source','source','0'),
								array('分发门店','store_name','replace_ignore',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						2=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								array('真实姓名','real_names','mi.names as real_names',1),
								array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('合同金额','ApplayMoney','loanmoney as lo',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								array('合同编号','applyCode','p.applyCode',1),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('分发门店','store_name','replace_ignore',1),
								array('业务员姓名','store_manager','store_manager','1'),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
						3=>array(
								array('用户id','memberid','o.memberid','0'),
								array('手机号','reg_mobile','m.mobile as reg_mobile','1'),
								array('申请人姓名','names','o.names','1'),
								//array('真实姓名','real_names','mi.names as real_names',1),
								//array('身份证号','certiNumber','mi.certiNumber',1),
								array('贷款金额','loanmoney','loanmoney',1),
								array('车辆种类','car_type_name','car_type_name',1),
								//array('实际贷款金额(元)','backtotalmoney','backtotalmoney','1'),
								array('贷款期限(月)','loanmonth','loanmonth','1'),
								array('贷款城市','city','city','1'),
								//array('月还款金额(元)','month_money','month_money','0'),
								//array('月利率(%)','fee_point','fee_point','0'),
								array('添加时间','timeadd','o.timeadd','1'),
								//array('来源','source','source','0'),
								array('驾驶证','driver_license','o.driver_license',1),
								array('分发门店','store_name','replace_ignore',1),
								array('拒单理由','store_remark','p.store_remark',1),
								array('状态','status_name','status_name',1),
								array('操作','ddd','ddd','1'),
						),
				),
			),
		);
                $groupId = $_SESSION['user']['groupid']==24?3:$_SESSION['user']['groupid'];
		$arr_fields = array_column($field[$groupId]["{$order_type}_column"][$_REQUEST['status']],2);array_pop($arr_fields);
		$str_field = str_replace(array('car_type_name','status_name','replace_ignore'),array($_car_type,$_status_name,'o.lasttime'),implode(",",$arr_fields));
		
		$SQL = "select o.id,o.order_type,p.customer_status,p.store_status,p.store_id,{$str_field} from `order` o,order_process p,member m,member_info mi where o.memberid=m.id and m.id=mi.memberid  and p.order_id=o.id ";
		$SQL.= (count($where)>0?" and ":"").implode(' AND ',$where);
		if($is_page){
			$count = M()->query("SELECT COUNT(*) AS count FROM ({$SQL}) AS c");
			$count = intval($count[0]['count']);
			$this->page['count'] = $count;
			$this->page['no'] = $this->_get('p', 'intval', 1);
			$this->page['num'] = 12;
			$this->page['total'] = ceil($count / $this->page['num']);
			$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
			if($_REQUEST['status']==3)
				$SQL.= "ORDER BY o.timeadd desc  LIMIT {$limit}";
			else
			$SQL.= " ORDER BY p.customer_status asc,p.customer_time desc,p.store_status asc,o.timeadd asc LIMIT {$limit}";
			$list = M()->query($SQL);//dump($SQL);
		}else{
			$list = M()->query($SQL);
		}
		foreach($list as &$v){
			$v['store_id']?($v['store_name'] = M('store')->where("id='{$v['store_id']}'")->getField("name")):'';
			$v['apply_count'] = intval(M('order')->where("memberid='{$v['memberid']}' and order_type='{$_REQUEST['order_type']}'")->count());
			if($v['order_type']==1 && !empty($v['applyCode']) && !empty($v['certiNumber'])){
				$v['applyCode'] = false==$this->isErp($v['certiNumber'],$v['applyCode'])?"<b style='color:red'>{$v['applyCode']}</b>":$v['applyCode'];
				$v['ApplayMoney'] = false==($erp_data = $this->isErp($v['certiNumber'],$v['applyCode']))?'':$erp_data['ApplayMoney'];
			}
		}
                $groupId = $_SESSION['user']['groupid']==24?3:$_SESSION['user']['groupid'];
		return array('column'=>$field[$groupId]["{$order_type}_column"][$_REQUEST['status']],'data'=>$list,'params'=>$params);
	}
	
	//等额本息的计算公式
	private function repayment($order_id,$money,$loanmonth = 12){
		if(false==($order = M('order')->where("id='{$order_id}'")->find()))return 0;
		return $money * $order['fee_point']*0.01 *pow(1 + $order['fee_point']*0.01, $loanmonth) / ( pow(1 + $order['fee_point']*0.01, $loanmonth) - 1 ); 
	}
	//导出数据到EXCEL
	private function export_data($result){
		vendor('PHPExcel.PHPExcel');
		vendor('PHPExcel.PHPExcel.IOFactory');
		vendor('PHPExcel.PHPExcel.Writer.Excel5');
		$PHPExcel = new PHPExcel();
		//输出内容如下：
		$word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
		$cols = $result['cols'];
		foreach($cols as $key => $value){
			$PHPExcel->getActiveSheet()->setCellValue($word[$key].'1',$cols[$key][0]);
		}
		
		if($result['data']){
			$i = 2;
			$cols_count = count($cols);
			foreach($result['data'] as $val){
				for($j=0;$j<$cols_count;$j++){
					$PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][1]],PHPExcel_Cell_DataType::TYPE_STRING);
					$PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
				}
				$i++;
			}
		}
	
		$outputFileName = date('YmdHis').'数据.xls';
		ob_end_clean();//清除缓冲区,避免乱码
		$xlsWriter = new PHPExcel_Writer_Excel5($PHPExcel);
		header("Content-Type: application/force-download");
		header("Content-Type: application/octet-stream");
		header("Content-Type: application/download");
		header('Content-Disposition:inline;filename="'.$outputFileName.'"');
		header("Content-Transfer-Encoding: binary");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Pragma: no-cache");
		$xlsWriter->save( "php://output" );
	}
	
	
	public function isCreditNo($vStr)
	{
		$vCity = array(
				'11','12','13','14','15','21','22',
				'23','31','32','33','34','35','36',
				'37','41','42','43','44','45','46',
				'50','51','52','53','54','61','62',
				'63','64','65','71','81','82','91'
		);
	
		if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;
	
		if (!in_array(substr($vStr, 0, 2), $vCity)) return false;
	
		$vStr = preg_replace('/[xX]$/i', 'a', $vStr);
		$vLength = strlen($vStr);
	
		if ($vLength == 18)
		{
			$vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
		} else {
			$vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
		}
	
		if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
		if ($vLength == 18)
		{
			$vSum = 0;
	
			for ($i = 17 ; $i >= 0 ; $i--)
			{
			$vSubStr = substr($vStr, 17 - $i, 1);
			$vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
			}
	
			if($vSum % 11 != 1) return false;
	}
	
	return true;
	}
	
	//合同编号核查，核查erp合同编号是否正确
	public function isErp($certiNumber,$applyCode){
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
			foreach($data['customer'] as $k=>$v){
				$data['customer'][$k]['customerinfo'] = json_decode($v['customerinfo'],true);
				$data['customer'][$k]['repaymentplan'] = json_decode($v['repaymentplan'],true);
			}
		}
		
		foreach($data['customer'] as $k=>$v){
			foreach($v['customerinfo'] as $key=>$val){
				$ContractNo = $val['ContractNo'];
				if(trim($ContractNo)==trim($applyCode))
					return $val;
			}
			
		}
		return false;
	}
 
}
