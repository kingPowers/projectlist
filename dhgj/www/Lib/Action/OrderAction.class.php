<?php

class OrderAction extends CommonAction {
	private $page_num = 10;
	//最新的订单列表
	public function newOrderList(){
    $params['_cmd_'] = "order";
    $params['type'] = "historyOrder";
    $params['num'] = $page_num;
    $params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
    $service_res = $this->service($params);
    //dump($service_res);
    $historyList = $service_res['dataresult']['list'];
    foreach ($historyList as &$value) {
        $value['sex'] = ($value['sex']==1)?"女":"男";
      }
    $count = $service_res['dataresult']['count'];
    $this->page['count'] = $count;
    $this->page['no'] = $this->_get('p', 'intval', 1);
    $this->page['num'] = $this->page_num;
    $this->page['total'] = ceil($count / $this->page['num']);
    $this->set_pages("/order/newOrderList/p/*");
    //dump($historyList);
    $this->assign("list",$historyList);
		$this->display();
	}
	
   /*
    * 新增订单
    * 	
    * */
   public function add_order(){
      //dump($_POST);
      if(false == ($member_info=$this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/order/add_order"));
      $params = array();
      $params['_cmd_'] = "order";
      $params['type'] = "addOrder";
      $service_res = $this->service($params);
      //dump($service_res);
      $add_info = $service_res['dataresult'];
      $_edit_session_ = $this->set_session("_edit_session_");
      $this->assign("_edit_session_",$_edit_session_);
      $this->assign("order_info",$add_info);
   		$this->display();
   }
   public function add_img()
   {
      if(false == ($member_info=$this->getMemberInfo()))$this->ajaxError("请重新登录",array("is_alert"=>1));
      if($_FILES)
      {
          $value = $_FILES['file']['tmp_name'];
          $dir = UPLOADPATH."/tmp";
          if(!is_dir($dir))mkdir($dir,0755);
          $imgUrl = $dir."/".time().rand(111,999).".jpg";
          if(false == ($add = move_uploaded_file($value,$imgUrl)))$this->ajaxError("上传失败");
          $params = array();
          $params['_cmd_'] = "order";
          $params['type'] = "addImg";
          $params['file_type'] = $this->_post("type","trim");
          $params['order_id'] = $this->_post("oid","trim");
          $params['file'] = new CURLFile($imgUrl);
          $service_res = $this->service($params);
          unlink($imgUrl);
          if($service_res['errorcode'] == 0)
          {
            $add_img['src'] = $service_res['dataresult'][0];
            $add_img['name'] = array_pop(explode("/",$add_img['src']));      
            $this->ajaxSuccess("上传成功",$add_img);
          }else{
            $this->ajaxError($service_res['errormsg']);
          }
      }
   }
   public function delete_img(){
      if(false == ($member_info=$this->getMemberInfo()))$this->ajaxError("请重新登录");
      if($_POST && !empty($_POST['name']))
      {
          $params = array();
          $params['_cmd_'] = "order";
          $params['type'] = "deleteImg";
          $params['file_type'] = $this->_post("type",'trim');
          $params['order_id'] = $this->_post("oid",'trim');
          $params['file_name'] = $this->_post("name",'trim');
          $service_res = $this->service($params);
          if($service_res['errorcode'] === 0)
          {
              $this->ajaxSuccess("删除成功");
          }
          $this->ajaxError($service['errormsg']);
      }
   }
   
   public function sub_edit_order()
   {
      if(false == ($member_info=$this->getMemberInfo()))$this->ajaxError("请重新登录");
      if($_POST && !empty($_POST['oid']))
      {
        //$this->ajaxSuccess(json_encode($_POST));
          if(false == $this->valid_session("_edit_session_"))$this->ajaxError("请不要重复提交"); 
          $params = array();
          $params['_cmd_'] = "order";
          $params['type'] = "editOrder";
          $params['order_id'] = $this->_post("oid",'trim');
          $params['names'] = $this->_post("names",'trim');
          $params['sex'] = $this->_post("sex",'trim');
          $params['certiNumber'] = $this->_post("certiNumber",'trim');
          $params['loan_city'] = $this->_post("loan_city",'trim');
          $params['loan_company'] = $this->_post("loan_company",'trim');
          $params['loan_money'] = $this->_post("loan_money",'trim');
          $params['mort_company'] = $this->_post("mort_company",'trim');
          $params['return_num'] = $this->_post("return_num",'trim');
          $params['car_brand'] = $this->_post("car_brand",'trim');
          $params['plate_num'] = $this->_post("plate_num",'trim');
          $params['frame_num'] = $this->_post("frame_num",'trim');
          $params['GPS_member'] = $this->_post("GPS_member",'trim');
          $params['GPS_password'] = $this->_post("GPS_password",'trim');
          $params['GPS_url'] = $this->_post("GPS_url",'trim');
          $params['trail_price'] = $this->_post("trail_price",'trim');
          if(!is_numeric($params['return_num']))$this->ajaxError("返还期数为数字");
          if(!is_numeric($params['loan_money']))$this->ajaxError("贷款金额为数字");
          $service_res = $this->service($params);
          if($service_res['errorcode'] === 0)
          {
             $this->set_session("_edit_session_",1);
             $this->ajaxSuccess("订单上传成功");
          }else{
             $this->ajaxError($service_res['errormsg']);
          }
      }
   }
   public function edit_order()
   {

      $order_id = $_REQUEST['oid'];
      if(empty($order_id))redirect("/member/my_order_list");
      if(false == ($member_info=$this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/order/edit_order/oid/".$order_id));
      $params = array();
      $params['_cmd_'] = "order";
      $params['type'] = "editInfo";
      $params['order_id'] = $order_id;
      $service_res = $this->service($params);
      //dump($service_res);
      $_edit_session_ = $this->set_session("_edit_session_");
      $this->assign("_edit_session_",$_edit_session_);
      $this->assign("order_info",$service_res['dataresult']);
      $this->assign("is_edit",1);
      $this->display("add_order"); 
   }
   public function my_order_list()
   {
      if(false == ($member_info=$this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/order/my_order_list"));
      //dump($member_info);
      $params = array();
      $params['_cmd_'] = "order";
      $params['type'] = "myOrderList";
      $params['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
      $params['number'] = $this->page_num;
      $service_res = $this->service($params);
      //dump($service_res);
      $orderList = $service_res['dataresult']['list'];
      foreach ($orderList as &$value) {
          $value['sex'] = ($value['sex']==1)?"女":"男";
          if(($value['status']==3)&&($member_info['usertype'] == 2))$value['status'] = 7;
      }//dump($orderList);
      $count = $service_res['dataresult']['count'];
      $this->page['count'] = $count;
      $this->page['no'] = $this->_get('p', 'intval', 1);
      $this->page['num'] = $this->page_num;
      $this->page['total'] = ceil($count / $this->page['num']);
      $this->set_pages("/order/my_order_list/p/*");
      $this->assign("list",$orderList);
      $this->display();
   }
   public function receive_order()
   {
      if(false == ($member_info=$this->getMemberInfo()))$this->ajaxError("请重新登录");
      if($_POST && ($_POST['is_ajax']==1))
      {
         $params = array();
         $params['_cmd_'] = "order";
         $params['type'] = "finishOrder";
         $params['order_id'] = $this->_post("order_id","trim");
         $service_res = $this->service($params);
         if($service_res['errorcode'] == 0)
         {
            $this->ajaxSuccess("接单成功");
         }else{
            $this->ajaxError($service_res['errormsg']);
         }
      }
   }
   public function order_info()
   {
      $order_id = $_REQUEST['oid'];
      if(false == ($member_info=$this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/order/order_info/oid/".$order_id));
      $params = array();
      $params['_cmd_'] = 'order';
      $params['type'] = ($member_info['usertype'] == 1)?"editInfo":"receiveOrderInfo";
      $params['order_id'] = $order_id;
      $service_res = $this->service($params);
      //dump($service_res);
      $orderInfo = $service_res['dataresult'];
      $orderInfo['sex'] = ($orderInfo['sex']==0)?"男":"女";
      $pic_url = array();
      foreach ($orderInfo['pic_url'] as $value) {
        foreach ($value as $val) {
          $pic_url[] = UPLOADPATH.array_pop(explode("/Upload/",$val));
        }
      }
      foreach ($orderInfo['pic_url'] as &$val) {
        //每4张图片分为一个数组
        $tmp= array();
        $num = ceil(count($val)/4);
        for($i=0;$i<$num;$i++)
        {
           $tmp[] = array_slice($val, $i * 4 ,4);
        }  
        $val = $tmp;
      }
      //生成ZIP文件
      if(($member_info['usertype'] == 2) || (in_array($orderInfo['status'], array(3,4))))
      {
        $zipUrl = $this->getZip($pic_url,$orderInfo['id']);
        $this->assign("zipUrl",$zipUrl);
      }      
      //dump($orderInfo);
      $this->assign("orderInfo",$orderInfo);
      $this->display();  
   }
   public function getZip($pathArray,$order_id)
   {
      $zipErr = '';
      $dir = UPLOADPATH.'orderZip';
      if(!is_dir($dir))mkdir($dir,0755);
      $name = "order".$order_id."Img.zip";
      $filename = $dir."/".$name;
      $uploadUrl = _STATIC_."/Upload/orderZip/".$name;
      if(file_exists($filename)) return $uploadUrl;  
      //重新生成文件   
      $zip = new ZipArchive();//使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释   
      if ($zip->open($filename, ZIPARCHIVE::CREATE)!==TRUE) {  
          return false;
      }  
      foreach( $pathArray as $val){   
          if(file_exists($val)){   
              $zip->addFile( $val, basename($val));//第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下   
          }   
      }  
      $zip->close();//关闭     
      if(!file_exists($filename)){     
          return false; //即使创建，仍有可能失败。。。。   
      } 
      return $uploadUrl;
   }
}
