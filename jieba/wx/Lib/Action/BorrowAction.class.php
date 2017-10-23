<?php

/*
 * 智信创富金融
 */

/**
 * Description of IndexAction
 * @author Nydia
 */
class BorrowAction extends CommonAction {

	//申请车抵贷第一步
    public function index() {
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/borrow/".ACTION_NAME));
    if($_POST && !empty($_POST['city'])){
    		if(empty($_POST['city'])){
					$this->error('贷款城市不能为空');
			}
			if(empty($_POST['loanmonth'])){
				$this->error('贷款期限不能为空');
			}
			if(empty($_POST['car_type'])){
				$this->error('请选择个人车或公司车');
			}
			if(!preg_match('/^[1-9]d*.d*|0.d*[1-9]d*|0?.0+|0$/',$_POST['loanmoney'])){
				$this->error('贷款金额不正确');
			}
			if(false!=M('order')->where("memberid='{$member_info['id']}' and order_type=1 and status=1")->find())$this->error('您已提交过申请，敬请等待审核结果！');
			//将驾照图片储存在一个临时文件中
      $drive_pic = $_FILES['drive_pic']; 
      $limit_type = array("jpg","JPG","jpeg","JPEG","png","PNG"); 
      $pathinfo = pathinfo($drive_pic['name']);
      $change_suf = 0;//0:不需要改变后缀名；1：需要改变图片后缀名
      if(empty($pathinfo['extension'])) $change_suf = 1;
      if($drive_pic['error'] === 0){
        $tmp_dir = UPLOADPATH."tmp";
        if(false == is_dir($tmp_dir)) mkdir($tmp_dir);
        //清除前一个小时上传的临时图片
        $this->clearPic($tmp_dir);
        $suf = ($change_suf == 0)?$drive_pic['name']:".jpg";
        $imgUrl = $tmp_dir."/".$member_info['memberid']."_".time()."_".$suf;
        if(false == move_uploaded_file($drive_pic["tmp_name"],$imgUrl))$this->error('图片保存失败，请重新上传！');
        $pathinfo1 = pathinfo($imgUrl);
        if(false == in_array($pathinfo1['extension'],$limit_type)) $this->error("上传图片类型(".$pathinfo1['extension'].")错误!允许类型:jpg,jpeg,png");
      }
			$_SESSION['borrow'] = $_POST;
      $_SESSION['borrow']['drive_pic'] = $imgUrl;
			$this->success('数据校验正确');
    	}
    	$params = array();
    	$params['_cmd_'] = "order";
    	$params['type'] = "setting";
    	$service_res = $this->service($params);
    	$this->assign('setting',$service_res['dataresult']);
        $this->display();

    }
    //申请车抵贷第二步
    public function add_borrow_submit(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/borrow/".ACTION_NAME));
    	if(empty($_SESSION['borrow']) && (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))$this->error('您已提交申请，请勿重复操作，如有疑问请联系客服！');
    	if(empty($_SESSION['borrow']) && !(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'))header("/borrow/index/");
    	if($_POST && !empty($_POST['data'])){
    		if(empty($_POST['data']['names'])){
    			$this->error('姓名不能为空');
    		}
    		if(empty($_POST['data']['mobile'])){
    			$this->error('手机号不能为空');
    		}
    		if(empty($_POST['data']['sms_code'])){
    			$this->error('验证码不能为空');
    		}
    		$params = array();
    		$params['_cmd_'] = "order";
    		$params['type'] = "borrow_add";
    		$params = array_merge($params,$_POST['data'],$_SESSION['borrow']);
        //$params['driverLicense'] = '@'.$_SESSION['borrow']['drive_pic']; 
        $params['driverLicense'] =  new CURLFile($_SESSION['borrow']['drive_pic']);
    		$service_res = $this->service($params);
        if($_SESSION['borrow']['drive_pic'])unlink($_SESSION['borrow']['drive_pic']);//清除临时图片
   			if(0===$service_res['errorcode']){
   				unset($_SESSION['borrow']);
   				$this->ajaxReturn(array('id'=>$service_res['dataresult']['add_id']), '提交成功',1);
   			}
    		$this->error($service_res['errormsg']);
    	}
    	$this->assign('member_info',$member_info);
    	if(empty(session('_borrow_mobile_')))session('_borrow_mobile_',md5(time()));
    	$this->assign('_borrow_mobile_',session('_borrow_mobile_'));
    	$this->display();
    }
    
	public function borrowSendSms(){
		if(false==($member_info = $this->getMemberInfo()))$this->error("请重新登录");
   		$params = array();
   		$params['_cmd_'] = "order";
   		$params['type'] = "borrowSendSms";
   		$params['mobile'] = $_POST['mobile'];
   		if(empty($_POST['_borrow_mobile_']) || empty(session('_borrow_mobile_')) || $_POST['_borrow_mobile_']!=session('_borrow_mobile_') || S(session('_borrow_mobile_'))>10){
   			session('_borrow_mobile_',null);
   			$this->ajaxError('页面已失效，请刷新页面');
   		}
   		S(session('_borrow_mobile_'),S(session('_borrow_mobile_'))+1,10*60);
   		$service_res = $this->service($params);
   		session('_borrow_mobile_',null);
   		if(0===$service_res['errorcode'])
   			$this->success("验证码已成功发送！");
   		else 
   			$this->error($service_res['errormsg']);
   		
   }
    
    //车抵贷提交成功
    public function add_borrow_success(){
    	if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/borrow/".ACTION_NAME));
    	$order = M('order')->where("id='{$_REQUEST['id']}'")->find();
    	$this->assign('order',$order);
    	$this->display();
    }
    //车抵贷流程
    public function borrow_flow(){
        $this->display();
    }
    //清除临时图片
    public function clearPic($dir){
      if(false == is_dir($dir)) return false;
      $pics = $this->scanD($dir);
      foreach ($pics as $value) {
         $pic_time = explode("_",$value)[1];
         if((false == is_dir($dir."/".$value)) && $pic_time < strtotime("-1 hour")){
              unlink($dir."/".$value);
         } 
      }
    }
    //遍历目录下所有文件
    public function scanD($dir){
      $fp = opendir($dir);
      $data = array();
      while($file = readdir($fp)){
        $data[] = $file;
      }
      return $data;
    }
}
