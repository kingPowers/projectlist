<?php
/*
 拖车订单管理
 */
class Order{
	private $error = '';
  private $_static_ = _STATIC_;
	/*
	 添加拖车订单
	 $memberid:用户id
	 $data:添加的订单信息
	 添加成功返回订单id
	 */
	public function addOrder ($memberid,$data)
	{
       if (false == $this->isAllowAdd($memberid)) return false;
       if (false == $this->checkData($data)) return false;
       //数据处理
       $data['car_frame_number'] = strtoupper($data['car_frame_number']);
       $data['status'] = 1;//订单状态：未审核
       $data['timeadd'] = date("Y-m-d h:i:s",time());
       $data['lasttime'] = $data['timeadd'];
       $data['contract_num'] = $memberid."-".rand(111,999).time();//合同编号
       $data['memberid'] = $memberid;
       $data['edit_num'] = 1;//允许修改次数
       try
       {
       	 if (!D('common')->inTrans())
       	 {
       	 	D('common')->startTrans();
       	 	$trans = true;
       	 }
       	 if (false == ($add_id = M('order')->add($data)))
         {
       	 	 throw new Exception("订单添加失败,请稍后再试");
       	 }
       	 //上传和保存图片
       $pic_data = array();
	     foreach ($_FILES as $key=>$value)
	     {
	       $pic_data[$key] = $this->uploadImage($value,$memberid,$key);
	     }
	     $pic_data['order_id'] = $add_id;
	     $pic_data['timeadd'] = date("Y-m-d h:i:s",time());
         $pic_data['lasttime'] = $pic_data['timeadd'];
	     if (false == ($add_pic = M('order_pic')->add($pic_data)))
	     {
             throw new Exception("文件保存失败，请稍后再试");
	     }
         if ($trans)
         {
         	D('common')->commit();
         }
         return $add_id;
       }catch (Exception $ex)
       {
       	$this->error = $ex->getMessage();
       	if ($trans)
       	{
           D('common')->rollback();          
       	}
       	return false;
       }
	}

	/*
	 判断是否允许用户添加订单
	 $member:用户id
	 */
	public function isAllowAdd ($memberid)
	{
       if (false == ($memberinfo = M('member')->where("id={$memberid}")->find()))
       {
       	 $this->error = '该用户尚未注册';return false;
       }
        elseif ($memberinfo['status'] != 1)
       {
          $this->error = '该用户已被冻结';return false;
       }
        elseif ($memberinfo['usertype'] != 1)
       { 
          $this->error = "该用户不允许发单";return false;
       } 
       return true;
	}
  /*
   获取订单的详情
   $memberid:订单用户id;为空时表示别人订单
   $order_id:订单id
   */
  public function editInfo($order_id,$memberid='')
  {
    if(empty($memberid))
    {
      if($order_id=='' || false==($editInfo = $this->isOrder($order_id)))
      {
        $this->error = "订单id错误";
        return false;
      }
    }
    else
    {
      if($order_id=='' || false==($editInfo = $this->isOrder($order_id,$memberid)))
      {
        $this->error = "该订单不存在";
        return false;
      }
    }  
    $editInfo['timeadd'] = date("m月d日",strtotime($editInfo['timeadd']));
    $editInfo['is_edit'] = ($editInfo['edit_num'] == 0)?0:1;
    return $editInfo;
  }
  /*
    提交我的修改的订单
    $order_id:需要修改的订单id
    $data:需要修改的订单内容；
   */
  public function editOrder ($order_id,$data)
  {
     if ($order_id=='' || false==($editInfo =  $this->isOrder($order_id)))
     {
       $this->error = "订单id错误";
       return false;
     }
     if(in_array($editInfo['status'],array(3,4,5))){
      $err = array('3'=>'已分发','4'=>'已完成','5'=>'已删除');
      $this->error = "对不起，订单已【{$err[$editInfo['status']]}】,不能修改";
      return false;
    }
     if($editInfo['edit_num'] <= 0){$this->error = "修改次数达到上限";return false;}
     //校验数据
     if(false === $this->checkData($data))return false;
     //数据处理
     $data['frame_number'] = strtoupper($data['frame_number']);
     unset($data['timeadd']);unset($data['order_id']);unset($data['id']);
     $data['edit_num'] = (intval($editInfo['edit_num']-1)<0)?0:intval($editInfo['edit_num']-1);
     $data['status'] = 1;//订单改为未审核状态
     $data['contract_num'] = $editInfo['contract_num'];
     $data['lasttime'] = date("Y-m-d H:i:s",time());
     try
     {
       if(!D('common')->inTrans())
       {
         D('common')->startTrans();
         $trans = true;
       }
       if(false == M('order')->where('id={$order_id}')->save($data))
       {
         throw new Exception("订单修改失败,请稍后再试");
       }
       //上传和保存图片
       $pic_data = array();
       foreach ($_FILES as $key=>$value)
       {
         $pic_data[$key] = $this->uploadImage($value,$memberid,$key);
       }
       $pic_data['order_id'] = $order_id;
       $pic_data['timeadd'] = date("Y-m-d h:i:s",time());
       $pic_data['lasttime'] = $pic_data['timeadd'];
       if (false == ($add_pic = M('order_pic')->add($pic_data)))
       {
             throw new Exception("文件保存失败，请稍后再试");
       }
     }
     
  }
 /*
  获取订单信息
  $order_id:订单id
  $memberid:用户id；为空表示他人，不为空表示自己
  */
  public function isOrder($order_id,$memberid='')
  {
    $where = array();
    $where['id'] = $order_id;
    if($memberid != '')$where['memberid'] = $memberid;
    $_status_name = "case when status=1 then '待审核' when status=2 then '待修改' when status=3 then '已分发' when status=4 then '已完成' when status=5 then '订单无效' when status=6 then '订单已删除' end as status_name";
    if(false ==($order_info = M('order')->field("*,{$_status_name}")->where($where)->find()))return false;
    $pic_url = M('order_pic')
  ->field("order_id,user_pic,certi_pic,loanContract_pic,mortContract_pic,travelLicense_pic,driveLicense_pic,carRegistration_pic")
  ->where("order_id={$order_id}")
  ->find();
  $pic = array();
    foreach ($pic_url as $key => $value) {    
      $pic[$key] = explode("|",$value);
      array_walk(
        $pic[$key], 
        function(&$value, $k, $prefix){$value = $prefix.$value;}, 
        $this->_static_."/Upload/".$key."/"
    );
    }
    $order_info['pic_url'] = $pic;
    return $order_info;
  }
	/*
	 校验订单数据是否正确
	 $data:订单数据
	 */
	public function checkData ($data)
	{
        if ($data['names'] == '') {$this->error = "客户姓名不能为空";return false;}
        if (($data['sex'] == '') || !(in_array($data['sex'],array(0,1)))) {$this->error = "客户性别不正确";return false;}
        if ($data['certiNumber'] == ''){$this->error = "客户身份证不能为空";return false;}
        if (false == $this->checkCerti($data['certiNumber'])){$this->error = '客户身份证格式不正确';return false;}
        if($data['loan_city'] == ''){$this->error = "贷款城市不能为空";return false;}
        if($data['loan_company' == '']){$this->error = "贷款公司不能为空";return false;}
        if($data['loan_money' == '']){$this->error = "贷款金额不能为空";return false;}
        if($data['mort_company' == '']){$this->error = "抵押公司不能为空";return false;}
        if($data['return_num' == '']){$this->error = "已还期数不能为空";return false;}
        if($data['car_brand' == '']){$this->error = "车辆型号不能为空";return false;}
        if($data['plate_num' == '']){$this->error = "车牌号码不能为空";return false;}
        if($data['frame_num' == '']){$this->error = "车架号码不能为空";return false;}
        if(strlen($data['frame_num']) != '17'){$this->error = "车架号码必须17位";return false;}
        if($data['GPS_member' == '']){$this->error = "GPS账号不能为空";return false;}
        if($data['GPS_password' == '']){$this->error = "GPS账号登录密码不能为空";return false;}
        if($data['GPS_url' == '']){$this->error = "GPS平台地址不能为空";return false;}
        if($data['trail_price' == '']){$this->error = "拖车报价不能为空";return false;}
        if(empty($_FILES['certi_pic'])){$this->error = "身份证照片不能为空";return false;}
        if(empty($_FILES['user_pic'])){$this->error = "客户照片不能为空";return false;}
        if(empty($_FILES['loanContract_pic'])){$this->error = "贷款合同照片不能为空";return false;}
        if(empty($_FILES['mortContract_pic'])){$this->error = "抵押合同照片不能为空";return false;}
        if(empty($_FILES['travelLicense_pic'])){$this->error = "行驶证照片不能为空";return false;}
        if(empty($_FILES['driveLicense_pic'])){$this->error = "驾驶证照片不能为空";return false;}
        if(empty($_FILES['carRegistration_pic'])){$this->error = "车辆登记证照片不能为空";return false;}
        if(false == $this->extra_file()){$this->error = "存在不需要图片";return false;}
        return true;
	}
	/*
	 分类上传图片
	 $file : 上传的文件
	 $memberid : 用户id;
	 $type:文件类型(上传文件所在文件夹)
	 $订单id
	 */
	public function uploadImage($file,$memberid,$type,$order_id){
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
         $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
         $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
         $upload->thumb = true;
         $upload->saveRule = $type."_".$memberid."_".time();           //设置文件保存规则唯一
         $upload->uploadReplace = true;                 //同名则替换
         //设置需要生成缩略图的文件后缀
         $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
         //设置缩略图最大宽度
         $upload->thumbMaxWidth = '400,100';
         //设置缩略图最大高度F
         $upload->thumbMaxHeight = '400,100'; 
          if(false == ($upinfo = $upload->uploadOne($file))) {// 上传错误提示错误信息        
             $this->error = $upload->getErrorMsg();
             return false;
          }else{// 上传成功 获取上传文件信息
             //$info =  $upload->getUploadFileInfo();
             $upname = implode("|",array_column($upinfo,"savename"));
             return $upname;
          }
		   
	}
	/*
	 校验身份证号是否正确
	 $vStr:身份证号
	 */
    public function checkCerti ($vStr)
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
    public function getError()
    {
    	return $this->error;
    }
    /*
     判断接受的文件中是否有自己不需要的类型图片
     */
    public function extra_file() 
    {
       $need_key = array("certi_pic","user_pic","loanContract_pic","mortContract_pic","travelLicense_pic","driveLicense_pic","carRegistration_pic");
       $key = array_keys($_FILES);
       if(empty(array_diff($key,$need_key)))return true;
       return false;
    }
}
?>