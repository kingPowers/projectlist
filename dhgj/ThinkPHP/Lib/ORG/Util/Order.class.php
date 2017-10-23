<?php
class Order
{
	private $error = '';
	private $_static_ = '';
	private $order_setting = [
				'max_pic_num'=>'30', //每次上传最大图片数量
				'max_edit_num'=>'1'//修改次数上限
			];
	private $file_type = array("certi_pic","user_pic","loanContract_pic","mortContract_pic","travelLicense_pic","driveLicense_pic","carRegistration_pic","certiBack_pic");
  public function __construct()
  {
		$this->_static_ = defined(_STATIC_)?_STATIC_:"http://daistatic.lingqianzaixian.com";
	}
  /*
  获取订单配置
   */
  public function getSetting()
  {
      return $this->order_setting;
  }
	/*
	 添加拖车订单
	 $memberid:用户id
	 返回新的订单id或者未完成订单的详情
	 */
	public function addOrder($memberid)
	{
        if (false == $this->isAllowAdd($memberid)) return false;
        if(false == ($order_info = $this->incomplete_order($memberid)))
        {
           if(false == ($new_order_id = $this->addNewOrder($memberid)))return false;
           $order_info['order_id'] = $new_order_id;
           return $order_info;
        }
        $order_info['order_id'] = $order_info['id'];
        return $order_info;
	}
	/*
	判断用户是否存在不完整订单
	$memberid :用户id
	返回：不完整订单的详情
	 */
	public function incomplete_order($memberid)
	{
		if(false == ($order_id = M("`order`")->where("memberid={$memberid} and status=0")->getField('id')))return false;
		if(false == ($order_info = $this->isOrder($order_id)))return false;	
		return $order_info;
	}
	/*
	生成一个新的空订单
	$memberid:用户id
	成功返回新订单的id
	*/
	public function addNewOrder($memberid)
	{	
		try
		{
			if(!D('common')->inTrans())
			{
				D('common')->startTrans();
				$trans = true;
			}
			//数据处理
			$data['memberid'] = $memberid;
			$data['timeadd'] = date("Y-m-d H:i:s",time());
			$data['lasttime'] = $data['timeadd'];
			$data['status'] = 0;//订单状态：不完整
			$data['edit_num'] = $this->order_setting['max_edit_num'];
      $data['contract_num'] = $memberid.rand(111,999).time();//合同编号
			if(false == ($add_id = M("`order`")->add($data)))
			{
				throw new Exception("用户添加信息订单失败,请稍后再试");
			}
			//数据处理
			unset($data['memberid']);unset($data['status']);
		    $data['order_id'] = $add_id;
			if(false == M('order_pic')->add($data))
			{
				throw new Exception("用户添加图片订单失败,请稍后再试");
			}
			if($trans)
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
          $this->error = '该用户已被冻结,禁止发单';return false;
       }
        elseif ($memberinfo['usertype'] != 1)
       { 
          $this->error = "该用户不允许发单";return false;
       } 
       return true;
	}
	/*
	添加订单图片
	$memberid:用户id
	$order_id:订单id
	$type:图片类型
	 */
	public function addImg($memberid,$order_id,$type)
	{
	   if (false == $this->isAllowAdd($memberid)) return false;
       if(false == in_array($type,$this->file_type))
       {
       	  $this->error = '上传图片类型不允许';
       	  return false;
       }
       if(false == ($order_info = $this->isOrder($order_id)))
       {
       	  $this->error = "订单不存在";
       	  return false;
       }
       if(false == ($pic_urls = M('order_pic')->where("order_id={$order_id}")->find()))
       {
       	  $this->error = "图片订单不存在";
       	  return false;
       }
       //是否允许修改
       if (false == $this->isAllowEdit($order_id))return false;
       //当存储图片为空时才允许多文件上传
       $file_num = $this->countFiles($_FILES);
       if(($pic_urls[$type] != '') && ($file_num >1)){$this->error = '一次能上传一张图片';return false;}
       if(!is_dir(UPLOADPATH.$type))mkdir(UPLOADPATH.$type,0755);
       if(false == ($pic_url = $this->uploadImg($memberid,$type)))return false;
       //保存到数据库
       $data = array();
       $data[$type] = ($pic_urls[$type] == '')?$pic_url:($pic_urls[$type]."|".$pic_url);
       $data['lasttime'] = date("Y-m-d H:i:s",time());
       try
       {
       	 if(!D('common')->inTrans())
       	 {
       	 	D('common')->startTrans();
       	 	$trans = true;
       	 }
       	 //保存订单图片信息
       	 if(false == M('order_pic')->where("order_id={$order_id}")->save($data))
       	 {
       	 	throw new Exception("上传图片失败");     	 	
       	 }
       	 //保存订单信息
       	 // $edit_num = $this->editNum($order_id);
       	 //$order_data['edit_num'] = ($edit_num > 0)?$edit_num:0;//(($order_info['edit_num']-1) < 0)?0:(($order_info['edit_num']-1));
       	 $order_data['lasttime'] = date("Y-m-d H:i:s",time());
         M("`order`")->where("id={$order_id}")->save($order_data);
       	 if($trans)
       	 {
       	 	D("common")->commit();
       	 }
         $url_array = explode("|",$pic_url);
         foreach ($url_array as &$value) {
           $value = $this->_static_."/Upload/".$type."/".$memberid."/".$value;
         }
       	 return $url_array;
       }catch (Exception $ex)
       {
          $this->error = $ex->getMessage();
          if($trans)
          {
          	D("common")->rollback();
          }
          return false;
       }       
	}
	/*
	 判断订单是否允许修改
	 $order_id:订单id
	 */
	public function isAllowEdit($order_id)
	{
       $order_info = M("`order`")->field('status,edit_num')->where("id={$order_id}")->find();
       if($order_info['status'] == 0) return true;
       //订单修改次数为0：不可修改
       if($order_info['edit_num'] == 0){$this->error = "订单修改次数达到上限"; return false;}
       //订单已分发、完成或删除不可修改
       if(in_array($order_info['status'],array(3,4,5)))
       {
       	   $err = array('3'=>'分发','4'=>'完成','5'=>'删除');
		       $this->error = "对不起，订单已【{$err[$order_info['status']]}】,不能修改";
		       return false;
       }
       return true;
	}
	/*
	订单操作后的可修改次数
	$order_id:订单id;
	返回订单可修改次数
	 */
	public function editNum($order_id)
	{
		$order_info = M("`order`")->field('status,edit_num')->where("id={$order_id}")->find();
		//订单不完整返回原修改次数
		if($order_info['status'] == 0) return intval($order_info['edit_num']);
		//订单修改次数减一
		$edit_num = (($order_info['edit_num']-1) < 0)?0:(($order_info['edit_num']-1));
		return intval($edit_num);
	}
	/*
	删除订单的图片
	$memberid:用户id
	$order_id:订单id
	$type:图片类型
	$name:图片名称
	 */
    public function deleteImg ($memberid,$order_id,$type,$name)
    {
       if (false == $this->isAllowAdd($memberid)) return false;
       if (false == in_array($type,$this->file_type))
       {
       	  $this->error = '删除图片类型不允许';
       	  return false;
       }
       if (false == ($order_info = $this->isOrder($order_id)))
       {
       	  $this->error = "订单不存在";
       	  return false;
       }
       if (false == $this->isAllowEdit($order_id))return false;
       if (false == ($pic_urls = M('order_pic')->where("order_id={$order_id}")->find()))
       {
       	  $this->error = "图片订单不存在";
       	  return false;
       }
       $pic_arr = explode('|',$pic_urls[$type]);
       if (false == in_array($name,$pic_arr))
       {
       	  $this->error = '该图片已删除';
       	  return false;
       }
       foreach ($pic_arr as $key => &$value) 
	     {
	       if ($value == $name)
	       { 
	         unset($pic_arr[$key]);
	       }
	     }
       $data[$type] = implode("|",$pic_arr);
       try
       {
       	  if (!D("common")->inTrans())
       	  {
       	  	D("common")->startTrans();
       	  	$trans = true;
       	  }
       	  if(false == M('order_pic')->where("order_id={$order_id}")->save($data))throw new Exception("图片删除失败");
       	  //保存订单信息
       	  //$edit_num = $this->editNum($order_id);
       	 // $order_data['edit_num'] = ($edit_num > 0)?$edit_num:0;
       	  $order_data['lasttime'] = date("Y-m-d H:i:s",time());
       	  if (false == M("`order`")->where("id={$order_id}")->save($order_data))throw new Exception("订单保存失败");	 
	        if ($trans)
	        {
	       	  D("common")->commit();
            //删除本地文件
            unlink(UPLOADPATH.$type."/".$name);
            unlink(UPLOADPATH.$type."/"."m_".$name);
            unlink(UPLOADPATH.$type."/"."s_".$name);
	        }
	        return true;
       }catch (Exception $ex)
       {
          $this->error = $ex->getMessage();
          if ($trans)
          {
          	D("common")->rollback();
          }
          return false;
       }
    }
    /*
    修改订单文本信息
    $memberid:用户id
    $order_id：订单id
    $data:修改详情
     */
    public function editOrder ($memberid,$order_id,$data)
    {
       if (false == $this->isAllowAdd($memberid)) return false;
       if (false == ($order_info = $this->isOrder($order_id)))
       {
       	  $this->error = "订单id错误";
       	  return false;
       }
       if (false == $this->isAllowEdit($order_id)) return false;
       if (false == $this->checkData($data,$order_id)) return false; 
       //数据处理
       $data['frame_num'] = strtoupper($data['frame_num']);
       $data['status'] = 1;//订单状态：未审核
       if($order_info['status'] == 0){
         $data['timeadd'] = date("Y-m-d h:i:s",time());
       } 
       $data['lasttime'] = date("Y-m-d h:i:s",time());
       $edit_num = $this->editNum($order_id);
       $data['edit_num'] = ($edit_num > 0)?$edit_num:0;
       if (false == M('`order`')->where("id={$order_id}")->save($data))
       {
       	  $this->error = "订单上传失败，请稍后再试";
       	  return false;
       }
       return true;
    }
    /*
    校验文本信息（前提：图片上传完全）
    $data:校验信息
    $order_id:订单id
     */
    public function checkData ($data,$order_id)
    {
    	  if ($data['names'] == '') {$this->error = "客户姓名不能为空";return false;}
        if (($data['sex'] == '') || !(in_array($data['sex'],array(0,1)))) {$this->error = "客户性别不正确";return false;}
        if ($data['certiNumber'] == ''){$this->error = "客户身份证不能为空";return false;}
        if (false == $this->checkCerti($data['certiNumber'])){$this->error = '客户身份证格式不正确';return false;}
        if ($data['loan_city'] == ''){$this->error = "贷款城市不能为空";return false;}
        if ($data['loan_company'] == ''){$this->error = "贷款公司不能为空";return false;}
        if ($data['loan_money'] == ''){$this->error = "贷款金额不能为空";return false;}
        if (false == (preg_match('/^\d+$/i',$data['loan_money']))){$this->error = "贷款金额为数字";return false;}
        if ($data['mort_company'] == ''){$this->error = "抵押公司不能为空";return false;}
        if ($data['return_num'] == ''){$this->error = "已还期数不能为空";return false;}
        if (false == (preg_match('/^\d+$/i',$data['return_num']))){$this->error = "已还期数为数字";return false;}
        if ($data['car_brand'] == ''){$this->error = "车辆型号不能为空";return false;}
        if ($data['plate_num'] == ''){$this->error = "车牌号码不能为空";return false;}
        if ($data['frame_num'] == ''){$this->error = "车架号码不能为空";return false;}
        if (strlen($data['frame_num']) != '17'){$this->error = "车架号码必须17位";return false;}
        if ($data['GPS_member'] == ''){$this->error = "GPS账号不能为空";return false;}
        if ($data['GPS_password'] == ''){$this->error = "GPS账号登录密码不能为空";return false;}
        if ($data['GPS_url'] == ''){$this->error = "GPS平台地址不能为空";return false;}
        if ($data['trail_price'] == ''){$this->error = "拖车报价不能为空";return false;}
        if (false == (preg_match('/^\d+$/i',$data['trail_price']))){$this->error = "拖车报价为数字";return false;}
        if (false == ($picInfo = $this->picInfo($order_id))){$this->error = "请先上传图片";return false;}
        if ($picInfo['user_pic'] == ''){$this->error = "请上传客户照片";return false;}
        if ($picInfo['certi_pic'] == ''){$this->error = "请上传身份证正面照片";return false;}
        if ($picInfo['certiBack_pic'] == ''){$this->error = "请上传身份证反面照片";return false;}
        if ($picInfo['loanContract_pic'] == ''){$this->error = "请上传贷款合同照片";return false;}
        if ($picInfo['mortContract_pic'] == ''){$this->error = "请上传抵押合同照片";return false;}
        if ($picInfo['travelLicense_pic'] == ''){$this->error = "请上传行驶证照片";return false;}
        if ($picInfo['driveLicense_pic'] == ''){$this->error = "请上传驾驶证照片";return false;}
        if ($picInfo['carRegistration_pic'] == ''){$this->error = "请上传车辆登记证照片";return false;}
        return true;
    }
    /*
     获取订单图片详情
     $order_id:订单id
     返回订单图片详情
     */
    public function picInfo($order_id)
    {
    	return M("order_pic")->where("order_id={$order_id}")->find();
    }
	/*
	获取文件的数量
     $file:上传的文件变量
	 */
	public function countFiles($files) {
       $fileArray = array();
       $n = 0;
       foreach ($files as $file){
           if(is_array($file['name'])) {
               $keys = array_keys($file);
               $count	 =	 count($file['name']);
               for ($i=0; $i<$count; $i++) {
                   foreach ($keys as $key)
                       $fileArray[$n][$key] = $file[$key][$i];
                   $n++;
               }
           }else{
               $fileArray[$n] = $file;
               $n++;
           }
       }
       return count($fileArray);
    }
	/*
	上传图片
	$memberid:用户id
	$type:图片类型
	 */
	public function uploadImg($memberid,$type)
	{
	   if(!empty($_FILES)){
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			$upload->savePath =  UPLOADPATH.$type."/".$memberid.DIRECTORY_SEPARATOR;
			$upload->thumb = true;
			$upload->saveRule = $memberid."_".time().rand(1111,9999);           
			$upload->uploadReplace = true;                 
			$upload->thumbPrefix = 'm_,s_';  
			$upload->thumbMaxWidth = '400,100';
			$upload->thumbMaxHeight = '400,100';
			if(!$upload->upload()) {
				$this->error = $upload->getErrorMsg();
				return false;
			}
			$info =  $upload->getUploadFileInfo();
			$data = implode("|",array_column($info,"savename"));
      return $data;
		}
    $this->error = "请选择上传图片";
    return false; 
	}
  /*
   获取要修改订单的详情
   $order_id:订单id
   $memberid:需要获取详情的用户
   $usertype:用户类型(默认发单用户)
   */
  public function editInfo($order_id,$memberid='',$usertype=1)
  {
    if($order_id=='' || false==($editInfo = $this->isOrder($order_id)))
    {
      $this->error = "订单id错误";
      return false;
    }
    $order_belong = $this->order_belong($order_id);
    $belong_id = ($usertype==1)?$order_belong['releaseId']:$order_belong['receiveId'];
    if($memberid != $belong_id)
    {
      $this->error = "改订单与用户不匹配";
      return false;
    }
    $editInfo['is_complete'] = ($editInfo['status'] == 0)?1:0;//是否需要补充信息
    $editInfo['is_edit'] = (false == $this->isAllowEdit($order_id))?0:1;//是否允许修改
    return $editInfo;
  }
  /*
  判断订单归属
  $order_id:订单id
   */
  public function order_belong($order_id)
  {
    $releaseId = M("order")->where("id={$order_id}")->getField("memberid");
    $receiveId = M("order_distribute")->where("order_id={$order_id}")->getField("memberid");
    return array("releaseId"=>$releaseId,"receiveId"=>$receiveId);
  }
  /*
  获取订单信息
  $order_id:订单id
  */
  public function isOrder($order_id)
  {
    $where = array();
    $where['id'] = $order_id;
    $_status_name = "case when status=0 then '补充' when status=1 then '待审核' when status=2 then '修改' when status=3 then '已分发' when status=4 then '已完成' when status=5 then '订单无效' when status=6 then '订单已删除' end as status_name";
    if(false ==($order_info = M("`order`")->field("*,{$_status_name}")->where($where)->find()))return false;
    $pic_url = M('order_pic')
    ->field("user_pic,certi_pic,certiBack_pic,loanContract_pic,mortContract_pic,travelLicense_pic,driveLicense_pic,carRegistration_pic")
    ->where("order_id={$order_id}")
    ->find();
    $pic = array();
    foreach ($pic_url as $key => $value) 
    {
      $pic[$key] = array();
      if($value != '')
      {
     	  $pic[$key] = explode("|",$value);
	      array_walk(
	        $pic[$key],
          function(&$value, $k, $prefix){$value = $prefix.$value;},
          $this->_static_."/Upload/".$key."/".$order_info['memberid']."/"
	      );
      }//if        
    }//foreach
    $order_info['pic_url'] = $pic;
    return $order_info;
  }
  /*
   获取我的订单列表
   $memberid:用户id
   $page:分页
   $number:每页显示数量
   */
  public function myOrderList($memberid,$page,$number)
  {
     if(intval($page)<1)$page = 1;
     if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
     if($memberid=='' || false==($memberInfo = M('member')->where("id='{$memberid}'")->find())){$this->error = "用户id错误";return false;}
     $orderList = $this->orderList($memberid,$page,$number,$memberInfo['usertype']);
 	   return !empty($orderList)?$orderList:[];
  }
  /*
  订单列表
  $memberid:用户id
  $page:分页
  $number:每页数量
  $type:用户类型 1：发单人 2：接单人
  $where:筛选条件
   */
  public function orderList($memberid,$page,$number,$type)
  {
      if($type == 1)
      {
      	$where['o.memberid'] = $memberid;
      	$where['o.status'] = array("in","1,2,3,4");
      	$count = M("`order` o")->join("order_pic op on op.order_id=o.id")->where($where)->count();
      	//return M("`order` o")->getLastSql();
      	if($count>0)
      	{
      		$_status_name = "case when o.status=1 then '待审核' when o.status=2 then '修改' when o.status=3 then '已分发' when o.status=4 then '已完成' end as status_name";
      		$lists = M("`order` o")
      		->join("order_pic op on op.order_id=o.id")
      		->field("o.*,{$_status_name}")
      		->where($where) 
      		->limit(($page-1)*$number.",".$number)
      		->order("o.status asc,o.lasttime desc,o.timeadd desc")
      		->select();
      		foreach ($lists as &$value) 
 	        {
 		       $value['timeadd'] = date("m月d日 H:i",strtotime($value['timeadd']));
           $value['is_edit'] = ($value['edit_num']>0)?1:0;
           unset($value['edit_num']);
 	        }
      		//return M("`order` o")->getLastSql();
      	}
      }elseif($type == 2)
      {
         $where['od.memberid'] = $memberid;
         $where['o.status'] = array("in","3,4");
         $count = M("order_distribute od")->join("`order` o on o.id=od.order_id")->where($where)->count();
         if($count>0)
         {
         	$_status_name = "case when o.status=3 then '接单' when o.status=4 then '已接单' end as status_name";
         	$lists = M("order_distribute od")
         	->join("`order` o on o.id=od.order_id")
         	->field("od.order_id,od.memberid as distribute_mid,od.timeadd as distribute_time,o.*,{$_status_name}")
         	->where($where)
         	->limit(($page-1)*$number.",".$number)
         	->order("o.status asc,od.timeadd desc")
         	->select();
         	foreach ($lists as &$value) 
         	{
         		$value['distribute_time'] = date("m月d日 H:i",strtotime($value['distribute_time']));
            $value['is_finish'] = ($value['status']==4)?1:0;
            unset($value['trail_price']);unset($value['edit_num']);
         	}
         }
      }
      $list['list'] = $lists;
      $list['count'] = $count;
      return $list;
  }
  /*
  获取最新订单列表
  $number:显示的列表数量
   */
  public function newOrderList($number)
  {
  	if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
  	$where = array();
  	$where['o.status'] = 3;
  	$count = M("`order` o")
    ->join("order_pic op on op.order_id=o.id")
    ->join("order_distribute od on od.order_id=o.id")
    ->where($where)->count();
    if($count < 2)
    {
    	$where['o.status'] = array("in","3,4");
    } 
    if(false == ($orderList = $this->passOrderList($number,1,$where['o.status'])))return false;
    return $orderList;
  }
  /*
  获取历史订单列表
  $number:获取数量
   */
  public function historyOrder($number,$page)
  {
    if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
    $page = ($page == '')?1:$page;
    if(false == ($orderList = $this->passOrderList($number,$page))) return false;
    return $orderList;
  }
  /*
  获取已审核通过的订单列表
  $page:分页
  $number:每页数量
  $status:订单状态：默认已分发、已完成
   */
  public function passOrderList($number,$page='',$status=array("in","3,4"))
  {
     $page = ($page == '')?1:$page;
     if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
     $where = array();
     $where['o.status'] = $status;
     $count = M("`order` o")
     ->join("order_pic op on op.order_id=o.id")
     ->join("order_distribute od on od.order_id=o.id")
     ->where($where)->count();
     if($count > 0)
     {
        $_status_name = "case when o.status=3 then '已分发' when o.status=4 then '已完成' end as status_name";
        $orderList = M("`order` o")
        ->join("order_pic op on o.id=op.order_id")
        ->join("order_distribute od on od.order_id=o.id")
        ->field("o.*,{$_status_name},od.timeadd as distribute_time")
        ->where($where)
        ->limit(($page-1)*$number.",".$number)
        ->order("od.timeadd desc")
        ->select();
        foreach ($orderList as &$value) 
        {
          $value['timeadd'] = date("Y-m-d H:i",strtotime($value['timeadd']));
          $value = $this->hideData($value);
        }   
     }
     $list['list'] = $orderList;
     $list['count'] = $count;
     return !empty($list)?$list:[];
  }
  /*
  隐藏部分信息
  $value:订单详情
   */
  public function hideData($value)
  {
      $value['names'] = mb_substr($value['names'],0,1,'utf8').str_repeat("*",mb_strlen($value['names'],'utf8')-1);
      $value['frame_num'] = substr($value['frame_num'],0,2).str_repeat("*",strlen($value['frame_num'])-2);
      $value['plate_num'] = mb_substr($value['plate_num'],0,3,'utf8').str_repeat("*",mb_strlen($value['plate_num'],'utf8')-3);
      $value['GPS_member'] = '***';$value['GPS_password'] = '***';$value['GPS_url'] = '***';unset($value['trail_price']);
      return $value;
  }
  /*
  完成订单（接单人接单）
  $order_id:订单id
  $memberid:接用户id
   */
  public function finishOrder($order_id,$memberid)
  {
     if(false == $this->isAllowReceive($memberid,$order_id))return false;
     if(($order_id == '') || (false == ($orderInfo = $this->isOrder($order_id))))
     {
        $this->error = "订单id错误";
        return false;
     }
     if($orderInfo['status'] != 3)
     {
         $this->error = "该订单尚未分发";
         return false;
     }
     if(false == M("order_distribute")->where("memberid={$memberid} and order_id={$order_id}")->find())
     {
       $this->error = "接单用户与订单不匹配";
       return false;
     }
     try
     {
       if(!D("common")->inTrans())
       {
          D("common")->startTrans();
          $trans = true;
       }
       if(false == M("`order`")->where("id={$order_id}")->save(array("status"=>4)))
       {
         throw new Exception("接单失败，请稍后再试");  
       }
       if(false == M("order_distribute")->where("order_id={$order_id} and memberid={$memberid}")->save(array("receive_time"=>date("Y-m-d H:i:s",time()))))
       {
         throw new Exception("接单失败，请稍后再试!");
       }
       if($trans)
       {
         D("common")->commit();
         return true;
       }
     }catch(Exception $ex)
     {
       $this->error = $ex->getMessage();
       if($trans)
       {
         D("common")->rollback();
         return false;
       }
     }
  }
  /*
  判断用户是否允许接单
  $memberid:用户id
   */
  public function isAllowReceive($memberid)
  {
       if (false == ($memberinfo = M('member')->where("id={$memberid}")->find()))
       {
         $this->error = '该用户尚未注册';return false;
       }
        elseif ($memberinfo['status'] != 1)
       {
          $this->error = '该用户已被冻结，禁止接单';return false;
       }
        elseif ($memberinfo['usertype'] != 2)
       { 
          $this->error = "该用户不允许接单";return false;
       } 
       return true;
  }
  /*
  接单详情
  $memberid:接单人用户id
  $order_id:订单id
   */
  public function receiveOrderInfo($memberid,$order_id)
  {
     if(false == $this->isAllowReceive($memberid))return false;
     if(false == ($orderInfo = $this->editInfo($order_id,$memberid,2))) return false;
     //数据处理
     unset($orderInfo['is_edit']);unset($orderInfo['is_complete']);
     $orderInfo['trail_price'] = M("order_distribute")->where("order_id={$order_id}")->getField("dis_price");
     return $orderInfo;
  }
  /*
  获取订单图片的路径
  $order_id:订单ID
   */
  public function getImgUrl($order_id)
  {
      if(($order_id == '') || (false == M("order_pic")->where("order_id={$order_id}")))
     {
        $this->error = "订单id错误";
        return false;
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

}
?>