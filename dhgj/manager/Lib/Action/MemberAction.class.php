<?php
class MemberAction extends CommonAction 
{
	private $keys = array("mobile"=>'手机号',"username"=>"会员名");
	private $page_num = 10;
	public function releaseMember()
	{
		$return = $this->getParams();
		//dump($return);
		$where = $return['where'];
		$params = $return['params'];
		$where['m.usertype'] = 1;
		$count = M("member m")
		->where($where)
		->count();
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
		$lists = M("member m")
		->field("m.id,m.username,m.mobile,m.timeadd,m.lasttime,m.mobile_location,m.status,case when oc.countNum is null then 0 when oc.countNum is not null then oc.countNum end as coNum")
		->join("(select count(id) countNum,memberid from `order` where status='4' group by memberid) as oc on oc.memberid=m.id")
		->where($where)
		->limit($limit)
		->select();
        $this->setPage("/Member/releaseMember{$params}/p/*.html");
        $this->assign('list', $lists);//echo M('member m')->getLastSql();
        $this->assign('keys', $this->keys);
        $this->display();
	}
	/*
	禁止用户发单
	 */
	public function ban_release()
	{
        if($_POST['is_ajax'] == 1)
        {
        	$memberid = $this->_post("memberid","trim");
        	$memberinfo = M('member')->where("id={$memberid}")->find();
        	if(false == $memberinfo)$this->ajaxError("该用户不存在");
        	if($memberinfo['usertype'] != 1)$this->ajaxError("该用户不是发单用户");
        	if($memberinfo['status'] != 1)$this->ajaxError("该用户已被禁止发单");	
        	if(false == M('member')->where("id={$memberid}")->save(array("status"=>9)))$this->ajaxError("操作失败，请稍后再试");
        	$this->ajaxSuccess("操作成功");
        }
	}
	/*
	允许用户发单
	 */
	public function allow_release()
	{
		if($_POST['is_ajax'] == 1)
        {
        	$memberid = $this->_post("memberid","trim");
        	$memberinfo = M('member')->where("id={$memberid}")->find();
        	if(false == $memberinfo)$this->ajaxError("该用户不存在");
        	if($memberinfo['usertype'] != 1)$this->ajaxError("该用户不是发单用户");
        	if($memberinfo['status'] == 1)$this->ajaxError("该用户已被允许发单");
        	if(false == M('member')->where("id={$memberid}")->save(array("status"=>1)))$this->ajaxError("操作失败，请稍后再试");
        	$this->ajaxSuccess("操作成功");
        }
	}
	public function receiveMember()
	{
		$return = $this->getParams();
		//dump($return);
		$where = $return['where'];
		$params = $return['params'];
		$where['m.usertype'] = 2;
		$count = M("member m")
		->where($where)
		->count();
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
		$lists = M("member m")
		->field("m.id,m.username,m.mobile,m.timeadd,m.lasttime,m.mobile_location,m.status,case when oc.countNum is null then 0 when oc.countNum is not null then oc.countNum end as coNum")
		->join("left join (select count(od.id) countNum,od.memberid from `order` o left join order_distribute od on od.order_id=o.id where o.status='4' group by od.memberid) as oc on oc.memberid=m.id")
		->where($where)
		->limit($limit)
		->select();
        $this->setPage("/Member/receiveMember{$params}/p/*.html");
        $this->assign('list', $lists);//dump($lists);echo M('member m')->getLastSql();
        $this->assign('keys', $this->keys);
        $this->display();
	}
	public function add_receive()
	{	
		$this->display();
	}
	/*
	禁止用户接单
	 */
	public function ban_receive()
	{
        if($_POST['is_ajax'] == 1)
        {
        	$memberid = $this->_post("memberid","trim");
        	$memberinfo = M('member')->where("id={$memberid}")->find();
        	if(false == $memberinfo)$this->ajaxError("该用户不存在");
        	if($memberinfo['usertype'] != 2)$this->ajaxError("该用户不是接单用户");
        	if($memberinfo['status'] != 1)$this->ajaxError("该用户已被禁止接单单");	
        	if(false == M('member')->where("id={$memberid}")->save(array("status"=>9)))$this->ajaxError("操作失败，请稍后再试");
        	$this->ajaxSuccess("操作成功");
        }
	}
	/*
	允许用户接单
	 */
	public function allow_receive()
	{
		if($_POST['is_ajax'] == 1)
        {
        	$memberid = $this->_post("memberid","trim");
        	$memberinfo = M('member')->where("id={$memberid}")->find();
        	if(false == $memberinfo)$this->ajaxError("该用户不存在");
        	if($memberinfo['usertype'] != 2)$this->ajaxError("该用户不是接单用户");
        	if($memberinfo['status'] == 1)$this->ajaxError("该用户已被允许接单");
        	if(false == M('member')->where("id={$memberid}")->save(array("status"=>1)))$this->ajaxError("操作失败，请稍后再试");
        	$this->ajaxSuccess("操作成功");
        }
	}
	/*
	接单用户数据入库
	 */
	public function receiveToDb()
	{
		if(!$_POST || empty($_POST['username'])) $this->ajaxError("请上传数据");
        $username = $this->_post("username","trim");
		$mobile = $this->_post("mobile","trim");
		$password = $this->_post("password","trim");
		$repassword = $this->_post("repassword",'trim');
        if(empty($username))$this->ajaxError("用户名不能为空");
        if(empty($mobile))$this->ajaxError("手机号码不能为空");
        if(empty($password))$this->ajaxError("密码不能为空");
        if(empty($repassword))$this->ajaxError("确认密码不能为空");
        if((preg_match('/^1[0-9]{10}$/', $mobile)==false) || ('' == ($mobile_location = $this->get_mobile_location($mobile))))$this->ajaxError("手机号码不正确");
        if($password != $repassword)$this->ajaxError("两次密码不一致");
        if(strlen($password)<6)$this->ajaxError("密码不能少于6个字符");
        if(false!=M('Member')->where(array("mobile"=>$mobile))->find())$this->ajaxError("手机号码已被注册");
        $add_array = array(
	        'username'      => $username,
	        'password'      => D('Common')->gen_password($password),
	        'mobile'        => $mobile,
	        'source_code'   => "BROWSER",
	    	'mobile_location' => $mobile_location,
	        'usertype' => 2//接单用户表类型
        );
        if(false == ($add_id = M('member')->add($add_array)))$this->ajaxError("注册失败，请稍后再试!");
		$this->ajaxSuccess("注册成功");
	}
	/*
	导出发单用户列表
	 */
	public function export_release_list()
	{
		$this->export_data('release_list');
	}
	public function export_data_release_list()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$params = $return['params'];
		$where['m.usertype'] = 1;
		$lists = M("member m")
		->field("m.id,m.username,m.mobile,m.timeadd,m.lasttime,m.mobile_location,m.status,case when oc.countNum is null then 0 when oc.countNum is not null then oc.countNum end as coNum")
		->join("(select count(id) countNum,memberid from `order` where status='4' group by memberid) as oc on oc.memberid=m.id")
		->where($where)
		->select();
		foreach ($lists as &$value) 
		{
           $value['status'] = ($value['status'] == 1)?"正常":"禁止发单";
		}
		$cols = array(
            array('id','用户id'),
            array('username','用户名'),
            array('mobile','手机号码'),
            array('mobile_location','电话归属地'),
            array('coNum','订单成单次数'),
            array('status','状态'),
            array('timeadd','注册时间'),
            array('lasttime','上次登录时间')
        );
        return array('data'=>$lists,'cols'=>$cols);
	}
	/*
	导出接单用户列表
	 */
	public function export_recrive_list()
	{
		$this->export_data('recrive_list');
	}
	public function export_data_recrive_list()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$params = $return['params'];
		$where['m.usertype'] = 2;
		$lists = M("member m")
		->field("m.id,m.username,m.mobile,m.timeadd,m.lasttime,m.mobile_location,m.status,case when oc.countNum is null then 0 when oc.countNum is not null then oc.countNum end as coNum")
		->join("left join (select count(od.id) countNum,od.memberid from `order` o left join order_distribute od on od.order_id=o.id where o.status='4' group by od.memberid) as oc on oc.memberid=m.id")
		->where($where)
		->select();
		foreach ($lists as &$value) 
		{
           $value['status'] = ($value['status'] == 1)?"正常":"禁止接单";
		}
		$cols = array(
            array('id','用户id'),
            array('username','用户名'),
            array('mobile','手机号码'),
            array('mobile_location','电话归属地'),
            array('coNum','接单次数'),
            array('timeadd','注册时间'),
            array('lasttime','上次登录时间')
        );
        return array('data'=>$lists,'cols'=>$cols);
	}
	/*
	获取筛选条件和分页条件
	 */
	public function getParams()
	{
		$where = array();$params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if(!empty($value))
        {
        	switch ($key) {
        		case 'mobile':
        			$where['m.mobile'] = $value;
        			break;
        		case 'username':
        			$where['m.username'] = array("like","%".$value."%");
        			break;
        	}
        	$params .= "/k/{$key}/v/{$value}";
        }
        if($starttime && !$endtime){
            $where['m.timeadd'] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['m.timeadd'] = array('elt',$endtime);
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['m.timeadd'] = array('between',array($starttime,$endtime));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        return $data = array("where"=>$where,"params"=>$params);
	}
	/*
	
	下载数据

	 */
	public function export_data($type=""){
       $export_type = array('release_list'=>'发单用户列表','recrive_list'=>'接单用户列表');
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();
        $function = 'export_data_'.$type;
        $result = $this->$function();
        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $cols = $result['cols'];
        foreach($cols as $k => $v){
            $PHPExcel->getActiveSheet()->setCellValue($word[$k].'1',$cols[$k][1]);
        }
        if($result['data']){
            $i = 2;
            $cols_count = count($cols);
            foreach($result['data'] as &$val){
                for($j=0;$j<$cols_count;$j++){
                    $PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
                }
                $i++;
            }
        }
        $outputFileName = $export_type[$type].date('Y-m-d H:i:s',time()).'数据.xls';
        $xlsWriter = new PHPExcel_Writer_Excel5($PHPExcel);
        ob_end_clean();//清除缓冲区,避免乱码
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
	public function get_mobile_location($mobile)
	{
		if (!isMobile($mobile)) {
			return false;
		}
		$return = array();
		$api = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel={$mobile}";
		$string = iconv('gb2312', 'utf-8', file_get_contents($api));
		$string = str_replace(array("\r", "\n", "\r\n", "'"), array('', '', '', ''), $string);
		$string = substr($string, strpos($string, '{') + 1);
		$string = substr($string, 0, strpos($string, '}'));
		$array = explode(',', $string);
		foreach ($array as $var) {
			$ex = explode(':', $var);
			$key = trim($ex[0]);
			$val = trim($ex[1]);
			$return[$key] = $val;
		}
		return $return['province'] . '-' . $return['catName'];
	}
}
?>