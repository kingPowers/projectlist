<?php
/**
 * Description of CommonAction
 * 门店管理
 */
class MemberAction extends CommonAction {
    private $_keys = array('cellphone' => '手机号', 'name' => '姓名', 'username' => '会员名','certiNumber'=>'身份证号');
    private $page_num = 12;
    //首页
    public function index() {
        $params = $orderstr = '';
        $where['m.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $where['m.username'] = $value;
                    break;
                case 'name':
                    $where['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $where['m.mobile'] = $value;
                    break;
                case 'certiNumber':
                    $where['mi.certiNumber'] = $value;
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
        $is_cernum = $this->_get('is_cernum','trim');//是否设置身份证
        $is_cint = $this->_get('is_cint','trim');//是否被邀请
        if($is_cernum){
            if($is_cernum == 1){
                $where['mi.certiNumber'] = array('exp','is not null') ;
            }else if($is_cernum == 2){
                $where['mi.certiNumber'] = array('exp','is null') ;
            }
            $params .= '/cernum/'.$is_cernum;
        }
        if($is_cint){
            if($is_cint == 1){
                $where['m.recintcode'] = array('exp','is not null') ;
            }else if($is_cint == 2){
                $where['m.recintcode'] = array('exp','is null') ;
            }
            $params .= '/cint/'.$is_cint;
        }
        $where["_string"] = " m.id=mi.memberid ";
        $count = M('member m,member_info mi ')->where($where)->count();
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $list = M('member_info mi,member m')
        			->field("m.id,m.username,m.mobile,m.mobile_location,mi.`names`,mi.certiNumber,m.recintcode,m.timeadd,m.lasttime")
        			//->join("sign s on m.id=s.memberid")
        			//->join("(select count(id) countNum,memberid from `order` where order_type=3 and status=2 group by memberid) as oc on oc.memberid=m.id")
        			->limit($limit)
        			->where($where)
        			->select();			
        import("Think.ORG.Util.Baofu");
        foreach($list as &$val){
                $baofu = new Baofu($val["id"]);
        	$val["score"] = intval(M("sign")->where("memberid='{$val["id"]}'")->sum("score"));
        	$val["countNum"] = intval(M("order")->where("order_type=3 and status=2 and memberid='{$val["id"]}'")->count());
                if(false!=($bankInfo = $baofu->bindBankCardStatus())){
                    $val['capAcntNo'] = $bankInfo['acc_no'];
                    $val['bank_name'] = $bankInfo['bank_name'];
                }
                
        	/*$fuyou_info = $fuyou->isOpenFuyou($val['id']);
        	$balance = $fuyou->BalanceAction($val['id']);
        	if(false!=$balance){
        		$val['ca_balance'] = round(intval($balance['ca_balance'])/100,2);//富友账户余额
        	}
        	if(false!=$fuyou_info){
        		$val['capAcntNo'] = $fuyou_info['capAcntNo'];
        		$val['bank_name'] = $fuyou_info['bank_name'];
        	}*/
        }
        
        $cernums = array('1'=>'是','2'=>'否');
        $cints = array('1'=>'是','2'=>'否');
        $this->setPage("/Member/index{$params}/p/*.html");
        $this->assign('list', $list);
        $this->assign('keys', $this->_keys);
        $this->assign('cernums',$cernums);
        $this->assign('cints',$cints);
        $this->display();
    }
    public function edit_index(){
        if(!empty($this->_get('id','trim'))){
            $map['memberid'] = $this->_get('id','trim');
            $memberInfo = M('member_info')->field('memberid,names,certiNumber')->where($map)->find();
            if($memberInfo){
                $this->assign('info',$memberInfo);
            }
        }
        if(!empty($this->_post('memberid','trim'))){
            $map['memberid'] = $this->_post('memberid','trim');
            $data['names'] = $this->_post('names','trim');
            $data['certiNumber'] = $this->_post('certiNumber','trim');
            $map1['certiNumber'] = $this->_post('certiNumber','trim');
            $reCer = $this->_post('reCer','trim');
            foreach ($data as $key=>$val) {
               if ($val === '') {
                   $this->error('信息不完整');
                   exit;
               }
            }
            if(!($reCer == $map1['certiNumber'])){
                $is_repeat = M('member_info')->where($map1)->select();
                if ($is_repeat) {
                    $this->error('该身份证号已存在');
                }
            }
            
            $result = M('member_info')
            ->where($map)->data($data)->save();
            if($result){
                $this->success('信息修改成功');
            }else{
                $this->error('信息修改失败');
            }

        }
        $this->display();
    }
    //推荐列表
    public function recintList(){
        $params = $orderstr = '';
        $where['member.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $recintKey = $this->_get('recint_k', 'trim');
        $recintValue = $this->_get('recint_v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $proId = $this->_get('proid','trim');
        $status = $this->_get('order_status','trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $where['m.username'] = $value;
                    break;
                case 'cellphone':
                    $where['m.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        if (!empty($recintValue)) {
            switch($recintKey){
                case 'username':
                    $where['member.username'] = $recintValue;
                    break;
                case 'cellphone':
                    $where['member.mobile'] = $recintValue;
                    break;
            }
            $params .= "/recint_k/{$recintKey}/recint_v/{$recintValue}";
        }
        if($starttime && !$endtime){
            $where['order.timeadd'] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['order.timeadd'] = array('elt',$endtime);
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['order.timeadd'] = array('between',array($starttime,$endtime));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        if($proId){
            $where['order.order_type'] = $proId;
            $params .= '/proid/'.$proId;
        }
        if($status){
            $where['order.status'] = $status;
            $params .= '/order_status/'.$status;
        }
        $keys = array('username' => '被邀请人会员名',  'cellphone' => '被邀请人手机号');
        $key = array('username' => '邀请人会员名',  'cellphone' => '邀请人手机号');
        $proids = array('1'=>'车贷宝','2'=>'车租宝');
        $order_status = array('1'=>'审核中','2'=>'交易成功','3'=>'交易失败');
        $where['member.recintcode'] = array('exp','is not null');
        $list = M('member')
            ->join('(select mobile,username from member) as m on m.mobile=member.recintcode')
            ->join('`order` on `order`.memberid=member.id')
            ->where($where)
            ->field('member.username username,member.mobile mobile,m.username username_p,m.mobile mobile_p,`order`.status,`order`.backtotalmoney,`order`.timeadd,`order`.loanmonth,`order`.order_type,`order`.`names`')
            ->order("member.id desc")
            ->select();
        $count = count($list);
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $this->setPage("/Member/recintList{$params}/p/*.html");
        $result = array_slice($list,$limit,$this->page['num']);
        $this->assign('keys', $keys);
        $this->assign('key', $key);
        $this->assign('proids',$proids);
        $this->assign('list',$result);
        $this->assign('order_status',$order_status);
        $this->display();
    }
    //员工列表
    public function staffList(){
        $_keys = array( 'staff_name' => '员工姓名','certiNumber'=>'身份证号', 'staff_jobs' => '员工职位',  'staff_mobile' => '手机号');
        $map = array();
        $params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim'); 
        if (!empty($value)) {
            switch($key){
                case 'staff_name':
                    $map['staff_name'] = $value;
                    break;
                case 'certiNumber':
                    $map['certiNumber'] = $value;
                    break;
                case 'staff_jobs':
                    $map['staff_jobs'] = $value;
                    break;
                case 'staff_mobile':
                    $map['staff_mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        $count =  M('staff')
        ->where($map)
        ->count();
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['num'] = $this->page_num;
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
           $info = M('staff')
            ->field('id,staff_name,staff_jobs,certiNumber,staff_mobile,maxMoney,status,leave_time')
            ->order('id desc')
            ->where($map)
            ->limit($limit)
            ->select();
            $this->assign('insuranceInfo',$info);
        }
        $this->setPage("/Member/staffList{$params}/p/*.html");
        $this->assign('keys',$_keys);
        $this->display();
    }
    //添加员工
    public function addStaff(){
        $this->display('editStaff');
    }
    //编辑员工信息
    public function editStaff(){
        if(!empty($this->_get('id','trim'))){
           $staff_id = $this->_get('id','trim');
           $staff_info = M('staff')
           ->field('id,staff_name,staff_jobs,certiNumber,staff_mobile,maxMoney,status,leave_time')
           ->where('id='.$staff_id)
           ->find();
           $this->assign('info',$staff_info);
        }
        $this->display('editStaff');
    }
    //保存员工信息
    public function saveStaff(){        
        $data['staff_name'] = $this->_post('staff_name','trim');
        $data['staff_jobs'] = $this->_post('staff_jobs','trim');
        $data['certiNumber'] = $this->_post('certiNumber','trim');
        $data['staff_mobile'] = $this->_post('staff_mobile','trim');
        $data['maxMoney'] = $this->_post('maxMoney','intval');
        $data['status'] = $this->_post('status','intval');
        
        foreach ($data as $key=>$val) {
            if ($val === '') {
                $this->error('信息不完整');
                exit;
            }
        }
        if ($data['status'] == 1) {
           $data['leave_time'] = $this->_post('leave_time','trim');
        }else{
           $data['leave_time'] = null;
        }     
        $S = false;
        //修改员工信息
         if (!empty($this->_post('mid','intval'))) {
            $id = $this->_post('mid','intval');
             $S = M('staff')->where('id='.$id)->save($data);
             if($S){
                 $this->_reset_data();
                 $this->success('信息修改成功');
             }else{
                 $this->error('信息修改失败');
             }
         }else{//增加新员工信息
            $S = M('staff')->add($data);
            if ($S) {
                $this->_reset_data();
                $this->success('添加成功');
            }else{
                $this->error('添加失败');
            }
            
         }      
    }
    public function deleteStaff(){
        if(!empty($this->_post('mid'))){
            $id = $this->_post('mid');
            $S = M('staff')->where('id='.$id)->delete();
            if ($S) {
                $this->_reset_data();
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }  
        }else{
            $this->error('操作失败');
        }
    }
    //导出员工信息数据
    public function export_staffInfo(){
        $map = array();
        $params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim'); 
         if (!empty($value)) {
            switch($key){
                case 'staff_name':
                    $map['staff_name'] = $value;
                    break;
                case 'certiNumber':
                    $map['certiNumber'] = $value;
                    break;
                case 'staff_jobs':
                    $map['staff_jobs'] = $value;
                    break;
                case 'staff_mobile':
                    $map['staff_mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();
        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $result = array();
        $cols = array(
            array('id','序号'),
            array('staff_name','员工姓名'),
            array('staff_jobs','员工职位'),
            array('certiNumber','身份证号'),
            array('staff_mobile','电话'),
            array('maxMoney','贷款最大限额'),
            array('status','状态'),
            array('leave_time','离职时间'),
        );
        $arr_status = array('未离职', '已离职');
        $result = M('staff')
            ->field('id,staff_name,staff_jobs,certiNumber,staff_mobile,maxMoney,status,leave_time')
            ->order('id desc')
            ->where($map)
            ->select();
        foreach($cols as $key => $value){
            $PHPExcel->getActiveSheet()->setCellValue($word[$key].'1',$cols[$key][1]);
        }
        if($result){
            $i = 2;
            $cols_count = count($cols);
            foreach($result as $val){
                $val['status'] = $arr_status[$val['status']];
                for($j=0;$j<$cols_count;$j++){
                    $PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
                }
                $i++;
            }
        }
        $outputFileName = '员工信息'.date('YmdHis').'.xls';
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
    //员工管理
    public function staff(){
    	$this->display();   
    }
    //更新缓存菜单
    private function _reset_data() {
        F('global_auth_rule_all', null);
        F('global_auth_rule_menu', null);
    }
    

    /*
    
     经纪人信息

    */
   //经纪人列表
    public function agent_list(){
       $_keys = array('names'=>'姓名','username'=>'会员名','mobile'=>'手机号码','certiNumber'=>'身份证号');
        $map = array();
       $status = array('1'=>'启用','2'=>'禁用');
        $params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim'); 
        if (!empty($value)) {
            switch($key){
                case 'names':
                    $map['mi.names'] = $value;
                    break;
                case 'certiNumber':
                    $map['mi.certiNumber'] = $value;
                    break;
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'mobile':
                    $map['m.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
         if($starttime && !$endtime){
            $map['g.timeadd'] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.timeadd'] = array('elt',$endtime);
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.timeadd'] = array('between',array($starttime,$endtime));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        $count = M('gold_agent g')
            ->join('member_info mi on mi.memberid=g.memberid')
            ->join('member m on m.id=g.memberid')
            ->where($map)
            ->count();
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['num'] = $this->page_num;
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
           $info =  M('gold_agent g')
            ->join('member_info mi on mi.memberid=g.memberid')
            ->join('member m on m.id=g.memberid')
            ->where($map)
            ->field('g.*,m.username,m.mobile,mi.names,mi.certiNumber')
            ->limit($limit)
            ->select();
            //dump($info);
            $this->assign('lists',$info);
            $this->assign('status',$status);
        }
       $this->setPage("/Member/agent_list{$params}/p/*.html");
       $this->assign('keys',$_keys);
       $this->display();
    }
    //修改经纪人状态
    public function agent_status(){
        $agent_id = $this->_post('id','trim');
        $agent_status = $this->_post('status','trim');
        if(!empty($agent_id) && !empty($agent_status)){
            $data['id'] = $agent_id;
            $data['status'] = $agent_status;
            $res = M('gold_agent')
            ->save($data);
            if($res){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
        
    }
    //导出经纪人信息
    public function export_agent(){
        $map = array();
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim'); 
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim'); 
         if (!empty($value)) {
            switch($key){
                case 'names':
                    $map['mi.names'] = $value;
                    break;
                case 'certiNumber':
                    $map['mi.certiNumber'] = $value;
                    break;
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'mobile':
                    $map['m.mobile'] = $value;
                    break;
            }
        }
        if($starttime && !$endtime){
            $map['g.timeadd'] = array('egt',$starttime);
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.timeadd'] = array('elt',$endtime);
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.timeadd'] = array('between',array($starttime,$endtime));
        }
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();
        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $result = array();
        $cols = array(
            array('id','经纪人ＩＤ'),
            array('memberid','用户ID'),
            array('names','姓名'),
            array('username','会员姓名'),
            array('mobile','手机号码'),
            array('certiNumber','身份证号'),
            array('company_name','公司简称'),
            array('company_full_name','公司全称'),
            array('is_vip','是否为V'),
            array('status','状态'),
            array('is_pay','是否支付费用'),
            array('pay_money','支付金额'),
            array('timeadd','添加时间'),
            array('lasttime','最后一次更新时间')

        );
        $vip_status = array('1'=>'否','2'=>'是','3'=>'申请中');
        $status = array('1'=>'启用','2'=>'禁用');
        $is_pay = array('1'=>'否','2'=>'是');
        $result = M('gold_agent g')
            ->join('member_info mi on mi.memberid=g.memberid')
            ->join('member m on m.id=g.memberid')
            ->where($map)            
            ->field('g.id,g.memberid,mi.names,m.username,m.mobile,mi.certiNumber,g.company_name,g.company_full_name,g.is_vip,g.status,g.is_pay,g.pay_money,g.timeadd,g.lasttime')
            ->order('id ')
            ->select();
        foreach($cols as $key => $value){
            $PHPExcel->getActiveSheet()->setCellValue($word[$key].'1',$cols[$key][1]);
        }
        if($result){
            $i = 2;
            $cols_count = count($cols);
            foreach($result as $val){
                $val['status'] = $status[$val['status']];
                $val['is_vip'] = $vip_status[$val['is_vip']];
                $val['is_pay'] = $vip_status[$val['is_pay']];
                for($j=0;$j<$cols_count;$j++){
                    $PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
                }
                $i++;
            }
        }
        $outputFileName = '经纪人信息'.date('YmdHis').'.xls';
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
    public function agent_vip(){
        $_keys = array('names'=>'姓名','username'=>'会员名','mobile'=>'手机号码','certiNumber'=>'身份证号');
        $vip = array('1'=>"拒绝",'2'=>'通过','3'=>'审核中');
        $map = array();
        $params = '';
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim'); 
        if (!empty($value)) {
            switch($key){
                case 'names':
                    $map['mi.names'] = $value;
                    break;
                case 'certiNumber':
                    $map['mi.certiNumber'] = $value;
                    break;
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'mobile':
                    $map['m.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        $map['pic_card2'] = array('neq',' ');
         if($starttime && !$endtime){
            $map['g.vip_time'] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.vip_time'] = array('elt',$endtime);
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['g.vip_time'] = array('between',array($starttime,$endtime));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        $count = M('gold_agent g')
            ->join('member_info mi on mi.memberid=g.memberid')
            ->join('member m on m.id=g.memberid')
            ->where($map)
            ->count();
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['num'] = $this->page_num;
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
           $info =  M('gold_agent g')
            ->join('member_info mi on mi.memberid=g.memberid')
            ->join('member m on m.id=g.memberid')
            ->where($map)
            ->field('g.id,g.is_vip,g.pic_card2,g.vip_time,m.username,m.mobile,mi.names,mi.certiNumber')
            ->limit($limit)
            ->order("vip_time desc")
            ->select();
            //dump($info);
            $this->assign('lists',$info);
            $this->assign('vip',$vip);
        }
       $this->setPage("/Member/agent_vip{$params}/p/*.html");
       $this->assign('keys',$_keys);
       $this->display();
    }
    public function add_vip(){
        $agent_id = $this->_post('id','trim');
        $change_vip_status = $this->_post('vip','trim');
        $is_vip = M('gold_agent')->where("id=".$agent_id)->getField('is_vip');
        if(empty($is_vip)){
           $this->error('参数错误');
        }else{
            $data = array();
            $data['id'] = $agent_id;
            $data['is_vip'] = $change_vip_status;
            $res = M('gold_agent')->save($data);
            if($res){
                $this->success('审核成功');
            }else{
                $this->error('审核失败');
            }
        }
    }

    public function export_recint_list(){
        $this->export_data('recint_list');
    }
    public function export_data_recint_list(){
        $where['member.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $recintKey = $this->_get('recint_k', 'trim');
        $recintValue = $this->_get('recint_v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $proId = $this->_get('proid','trim');
        $status = $this->_get('order_status','trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $where['m.username'] = $value;
                    break;
                case 'cellphone':
                    $where['m.mobile'] = $value;
                    break;
            }
        }
        if (!empty($recintValue)) {
            switch($recintKey){
                case 'username':
                    $where['member.username'] = $recintValue;
                    break;
                case 'cellphone':
                    $where['member.mobile'] = $recintValue;
                    break;
            }
        }
        if($starttime && !$endtime){
            $where['order.timeadd'] = array('egt',$starttime);
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['order.timeadd'] = array('elt',$endtime);
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['order.timeadd'] = array('between',array($starttime,$endtime));
        }
        if($proId){
            $where['order.order_type'] = $proId;
        }
        if($status){
            $where['order.status'] = $status;
        }
        $where['member.recintcode'] = array('exp','is not null');
        $list = M('member')
            ->join('(select mobile,username from member) as m on m.mobile=member.recintcode')
            ->join('`order` on `order`.memberid=member.id')
            ->where($where)
            ->field('member.username username,member.mobile mobile,m.username username_p,m.mobile mobile_p,`order`.status,`order`.backtotalmoney,`order`.timeadd,`order`.loanmonth,`order`.order_type,`order`.`names`')
            ->select();
        $order_status = array('1'=>'审核中','2'=>'交易成功','3'=>'交易失败');
        $proids = array('1'=>'车贷宝','2'=>'车租宝');
        foreach($list as &$li){
            $li['status'] = $order_status[$li['status']];
            $li['order_type'] = $proids[$li['order_type']];
            $li['recint_money'] = 0;
        }

        $cols = array(
            array('username_p','邀请人会员名'),
            array('mobile_p','	邀请人手机'),
            array('username','被邀请人会员名'),
            array('mobile','被邀请人手机'),
            array('names','被邀请人真实姓名'),
            array('status','申请状态'),
            array('timeadd','申请时间'),
            array('order_type','项目'),
            array('loanmonth','借款时间(月)'),
            array('backtotalmoney','借款金额'),
            array('recint_money','提成金额'),
        );
        return array('data'=>$list,'cols'=>$cols);
    }
    public function export_index_list(){
        $this->export_data('index_list');
    }
    public function export_data_index_list(){
        $where['m.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $where['m.username'] = $value;
                    break;
                case 'name':
                    $where['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $where['m.mobile'] = $value;
                    break;
            }
        }
        if($starttime && !$endtime){
            $where['m.timeadd'] = array('egt',$starttime);
        }else if(!$starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['m.timeadd'] = array('elt',$endtime);
        }else if($starttime && $endtime){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $where['m.timeadd'] = array('between',array($starttime,$endtime));
        }
        $is_cernum = $this->_get('is_cernum','trim');//是否设置身份证
        $is_cint = $this->_get('is_cint','trim');//是否被邀请
        if($is_cernum){
            if($is_cernum == 1){
                $where['mi.certiNumber'] = array('exp','is not null') ;
            }else if($is_cernum == 2){
                $where['mi.certiNumber'] = array('exp','is null') ;
            }
        }
        if($is_cint){
            if($is_cint == 1){
                $where['m.recintcode'] = array('exp','is not null') ;
            }else if($is_cint == 2){
                $where['m.recintcode'] = array('exp','is null') ;
            }
        }
        $results = M('member m')
        	->field("m.*,mi.names,mi.certiNumber,m.lasttime,sum(s.score) score")
            ->join('member_info mi on m.id=mi.memberid')
            ->join("sign s on m.id=s.memberid")
            ->group("m.id")
            ->where($where)->select();
        /*import("Think.ORG.Util.Fuyou");
        $fuyou = new Fuyou();
        foreach($results as &$val){
        	$fuyou_info = $fuyou->FuyouStatus($val['id']);
        	if(false!=$fuyou_info){
        		$val['capAcntNo'] = $fuyou_info['capAcntNo'];
        		$val['bank_name'] = $fuyou_info['bank_name'];
        	}
        }*/
        /*foreach($results as &$result){
            $result['recintcode'] = empty($result['recintcode'])?('否'):('是');
        }*/

        $cols = array(
            array('id','用户ID'),
            array('username','用户名称'),
            array('mobile','手机号码'),
            array('names','姓名'),
            array('certiNumber','身份证号码'),
        	array('bank_name','开户银行'),
        	array('capAcntNo','银行卡号'),
            array('recintcode','是否被邀请'),
            array('score','积分'),
            array('timeadd','注册时间'),
            array('lasttime','最后登录时间'),
        );
        return array('data'=>$results,'cols'=>$cols);
    }
    
    //推荐提成
    public function recintOrderList(){
    	$list = $this->recintOrderData();
    	$count = count($list);
    	$this->page['count'] = $count;
    	$this->page['no'] = $this->_get('p', 'intval', 1);
    	$this->page['num'] = $this->page_num;
    	$this->page['total'] = ceil($count / $this->page['num']);
    	$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
    	$this->setPage("/Member/recintList{$params}/p/*.html");
    	$result = array_slice($list,$limit,$this->page['num']);
    	
    	$keys = array('salename' => '业务员姓名',  'store' => '门店');
    	$key = array('username' => '借款人姓名',  'cellphone' => '手机号');
    	$proids = array('1'=>'车贷宝','2'=>'车租宝',"3"=>"信用贷");
    	
    	$this->assign('keys', $keys);
    	$this->assign('key', $key);
    	$this->assign('proids',$proids);
    	$this->assign('list',$result);
    	$this->display();
    }
    //导出推荐提成
     public function recintOrderListExport(){
     	$list = $this->recintOrderData();
     	$cols = array(
     			array('names','姓名'),
     			array('mobile','手机'),
     			array('store_manager','业务员姓名'),
     			array('order_type_name','项目'),
     			array('loanmonth','期限'),
     			array('money','金额'),
     			array('roay_money','提成金额'),
     			array('name','门店'),
     			array('store_time','成单时间'),
     	);
     	$this->export_data1(array('data'=>$list,'cols'=>$cols));
     } 
     /**
      * 更改邀请码申请列表
      */
     public function recintAplyList(){
     	$status = array(
     			'1'=>'未审核',
     			'2'=>'拒绝',
     			'3'=>'通过'
     	);
     	$params = $orderstr = '';
     	$where = array();
     	$key = $this->_get('k', 'trim');
     	$value = $this->_get('v', 'trim');
     	$startTime = $this->_get('starttime', 'trim');
     	$endTime = $this->_get('endtime', 'trim');
     	$isStatus = $this->_get('isstatus','trim');
     	if (!empty($value)) {
     		switch($key){
     			case 'username':
     				$where['m.username'] = $value;
     				break;
     			case 'name':
     				$where['mi.names'] = $value;
     				break;
     			case 'cellphone':
     				$where['m.mobile'] = $value;
     				break;
     		}
     		$params .= "/k/{$key}/v/{$value}";
     	}
     
     	if(empty($startTime) && !empty($endTime)){
     		$endTime = date('Y-m-d 23:59:59',strtotime($endTime));
     		$where['mra.time'] = array('elt',$endTime);
     		$params .= "/endtime/{$endTime}";
     	}else if(!empty($startTime) && empty($endTime)){
     		$where['mra.time'] = array('egt',$startTime);
     		$params .= "/starttime/{$startTime}";
     	}else if(!empty($startTime) && !empty($endTime)){
     		$endTime = date('Y-m-d 23:59:59',strtotime($endTime));
     		$where['mra.time'] = array('between',array($startTime,$endTime));
     		$params .= "/starttime/{$startTime}";
     		$params .= "/endtime/{$endTime}";
     	}
     	if($isStatus){
     		$where['mra.status'] = $isStatus;
     		$params .= "/isstatus/{$isStatus}";
     	}
     	$member_ids = M('member_recint_apply mra')
     	->join('member m on mra.memberid = m.id')
     	->join('member_info mi on mi.memberid = m.id ')
     	->field('mra.*,m.username,m.mobile,mi.names')->where($where)->select();
     	$count = count($member_ids);
     	if ($count > 0) {
     		$this->page['count'] = $count;
     		$this->page['no'] = $this->_get('p', 'intval', 1);
     		$this->page['total'] = ceil($count / $this->page['num']);
     		$page_num = ($this->page['num']>$count)?($count):($this->page['num']);
     		$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $page_num;
     		$list = M('member_recint_apply mra')
     		->join('member m on mra.memberid = m.id')
     		->join('member_info mi on mi.memberid = m.id ')
     		->field('mra.*,m.username,m.mobile,m.mobile_location,mi.names,mi.certiNumber')->where($where)->limit($limit)->order("mra.status asc,mra.time desc")->select();
     		$this->assign('list', $list);
     	}
     	$this->setPage("/Member/recintAplyList{$params}/p/*.html");
     	$this->assign('keys', $this->_keys);
     	$this->assign('params',$params);
     	$this->assign('status',$status);
     	$this->display();
     }
     /**
      * change apply status
      */
     public function changeRecintStatus(){
     	if(!IS_AJAX){
     		halt('页面不存在');
     	}else{
     		$id = $this->_post('id');
     		$status = $this->_post('status');
     		$data['status'] = $status;
     		$where['id'] = $id;
     		$apply_info = M('MemberRecintApply')->where($where)->find();
     		if(empty($apply_info)){
     			$this->error('参数错误');
     		}
     		if($apply_info['status']>=$status){
     			$this->error('不能修改的状态');
     		}
     		D('Common')->startTrans();
     		try {
     			$result = M('MemberRecintApply')->where($where)->save($data);
     			if(!$result){
     				throw new Exception('参数错误');
     			}
     			if($status==3){
     				$find['mobile'] = $apply_info['recint_code'];
     				$recint_member_info = M('Member')->where($find)->find();
     				if(empty($recint_member_info)){
     					$this->error('参数错误');
     				}
     				$wh['id'] = $apply_info['memberid'];
     				$da['recintcode'] = $recint_member_info['mobile'];
     				//$da['recintmemberid'] = $recint_member_info['id'];
     				M('member')->where($wh)->save($da);
     			}
     			D('Common')->commit();
     			$this->ajaxReturn('审核成功','json');
     		} catch (Exception $ex) {
     			D('Common')->rollback();
     			$this->ajaxReturn('审核失败','json');
     		}
     	}
     
     }
     //表格导入的车贷用户列表
     public function uploadMember()
     {
        $select_key = ['names' => '姓名','mobile' => '手机号','certi_num' => '身份证号','store' => '门店'];
        $k = $this->_get("k","trim");
        $v = $this->_get("v","trim");
        $map = [];$params = '';
        if ($v) {
            switch ($k) {
                case 'names':
                    $map['name'] = ['like',"%{$v}%"];
                    break;
                case 'mobile':
                    $map['mobile'] = ['like',"%{$v}%"];
                    break;
                case 'certi_num':
                    $map['cer_card'] = $v;
                    break;
                case 'store':
                    $map['department'] = ['like',"%{$v}%"];
                    break;
            }
            $params .= "/k/{$k}/v/{$v}";
        }
        $count = M('order_upload')->where($map)->count();
        $lists = [];
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            $page_num = ($this->page['num']>$count)?($count):($this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $page_num;
            $lists = M('order_upload')
                ->where($map)
                ->order('id desc')
                ->limit($limit)
                ->select();
        }
        $this->setPage("/Member/uploadMember{$params}/p/*.html");
        $this->assign('lists',$lists);  
        $this->assign("keys",$select_key);
        $this->display();
     }
     //添加导入的用户
     public function addUpload()
     {
        $this->display('editUpload');
     }
     //编辑导入的用户
     public function editUpload()
     {
        if ($_POST && ($_POST['is_save'] == 1)) $this->uploadToDb();
        $uploadid = $this->_get("id","intval");
        if ($uploadid) {
            $info = M("order_upload")->where("id={$uploadid}")->find();
            $this->assign("info",$info);//dump($info);
        }
        $this->display();
     }
     //保存导入的车贷用户信息
     public function uploadToDb()
     {
        $pro_palte = ",";
        $uploadid = $this->_post("uid","intval");
        $data['mobile'] = $this->_post("mobile","trim");
        $data['cer_card'] = $this->_post("certi_num","trim");
        $data['total_num'] = $this->_post("total","trim");
        $data['return_num'] = $this->_post("return","trim");
        $data['member_status'] = $this->_post("status","trim");
        $data['name'] = $this->_post("names","trim");
        $data['department'] = $this->_post("store","trim");
        $data['car_product'] = $this->_post("product","trim");
        $data['car_plate'] = $this->_post("car_plate","trim"); 
        $data['contract_num'] = $this->_post("contract_num","trim"); 
        foreach ($data as $value) {
            if (empty($value)) {
                $this->ajaxError("信息不完整");
            }
        }
        $data['product'] = $data['car_product'] . $pro_palte . $data['car_plate'];
        $data['pay_money'] = $this->_post("pay_money","trim");
        $data['delay_num'] = $this->_post("delay","trim");
        if (!preg_match('/^1[0-9]{10}$/', $data['mobile'])) {
            $this->ajaxError("手机格式不正确");
        }
        if (!is_numeric($data['total_num']) || !is_numeric($data['return_num']) || !is_numeric($data['delay_num']) || !is_numeric($data['pay_money'])) {
            $this->ajaxError("期数或还款金额格式不正确");
        }
        if (false == $this->isCreditNo($data['cer_card'])) {
            $this->ajaxError("身份证号错误");
        }
        if ($uploadid) {
            $res = M('order_upload')->where("id={$uploadid}")->save($data);
        } else {
            if (M('order_upload')->where("mobile={$data['mobile']} or cer_card={$data['cer_card']}")->find()) $this->ajaxError("该手机号码或身份证号已存在");
            $data['date'] = date("Y-m-d H:i:s",time());
            $res = M("order_upload")->add($data);
        }
        if ($res) {
            $this->ajaxSuccess("操作成功");
        }
        $this->ajaxError("操作失败，请稍后再试");
     }
     //判断身份证是否合法
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
     private function recintOrderData(){

     	$params = $orderstr = '';
     	$key = $this->_get('k', 'trim');
     	$value = $this->_get('v', 'trim');
     	 
     	$recintKey = $this->_get('recint_k', 'trim');
     	$recintValue = $this->_get('recint_v', 'trim');
     	 
     	$starttime = $this->_get('starttime', 'trim');
     	$endtime = $this->_get('endtime', 'trim');
     	 
     	$proId = $this->_get('proid','trim');
     	 
     	if (!empty($value)) {
     		switch($key){
     			case 'username':
     				$where['o.names'] = array("like","%{$value}%");
     				break;
     			case 'cellphone':
     				$where['o.mobile'] =  array("like","%{$value}%");
     				break;
     		}
     		$params .= "/k/{$key}/v/{$value}";
     	}
     	if (!empty($recintValue)) {
     		switch($recintKey){
     			case 'salename'://业务员姓名
     				$where['p.store_manager'] = array("like","%{$recintValue}%");
     				break;
     			case 'store'://门店
     				$where['s.name'] = array("like","%{$recintValue}%");
     				break;
     		}
     		$params .= "/recint_k/{$recintKey}/recint_v/{$recintValue}";
     	}
     	if($starttime && !$endtime){
     		$where['p.store_time'] = array('egt',$starttime);
     		$params .= '/starttime/'.$starttime;
     	}else if(!$starttime && $endtime){
     		$endtime = date('Y-m-d 23:59:59',strtotime($endtime));
     		$where['p.store_time'] = array('elt',$endtime);
     		$params .= '/endtime/'.$endtime;
     	}else if($starttime && $endtime){
     		$endtime = date('Y-m-d 23:59:59',strtotime($endtime));
     		$where['p.store_time'] = array('between',array($starttime,$endtime));
     		$params .= '/starttime/'.$starttime;
     		$params .= '/endtime/'.$endtime;
     	}
     	//车租宝、车贷宝
     	if($proId){
     		$where['o.order_type'] = $proId;
     		$params .= '/proid/'.$proId;
     	}
     	$where['o.status'] = 2;
     	$list = M('order')
		     	->table("`order` o")
		     	->join('order_process p on p.order_id=o.id')
		     	->join("store s on s.id=p.store_id")
		     	->where($where)
		     	->field('o.names,o.mobile,p.store_manager,p.store_time,if(o.order_type=1,"车贷宝",if(o.order_type=2,"车租宝","")) as order_type_name,o.loanmonth,if(o.backtotalmoney>0,o.backtotalmoney,o.loanmoney) as money,if(o.backtotalmoney>0,o.backtotalmoney,o.loanmoney)*1 as roay_money,s.name')
		     	->select();
     	
     	return $list;
     }
    
    /**
     *       下载数据
     *
     */
    public function export_data($type=""){
        $export_type = array('index_list'=>'会员列表','recint_list'=>'推荐列表');
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
        $arr_status = array(0 => '否' ,1 => '是');
        $com_status = array('' => '否',2 => '是');
        if($result['data']){
            $i = 2;
            $cols_count = count($cols);
            foreach($result['data'] as &$val){
                if( $val['recintmemberid'] == 0){
                    $val['recintmemberid'] = $arr_status[$val['recintmemberid']];
                }else{
                    $val['recintmemberid'] = 1;
                    $val['recintmemberid'] = $arr_status[$val['recintmemberid']];
                }
                if( $val['staffId'] == ''){
                    $val['staffId'] = $com_status[$val['staffId']];
                }
                else{
                    $val['staffId'] = 2;
                    $val['staffId'] = $com_status[$val['staffId']];
                }
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
    
    //导出数据到EXCEL
    private function export_data1($result){
    	vendor('PHPExcel.PHPExcel');
    	vendor('PHPExcel.PHPExcel.IOFactory');
    	vendor('PHPExcel.PHPExcel.Writer.Excel5');
    	$PHPExcel = new PHPExcel();
    	//输出内容如下：
    	$word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    	$cols = $result['cols'];
    	foreach($cols as $key => $value){
    		$PHPExcel->getActiveSheet()->setCellValue($word[$key].'1',$cols[$key][1]);
    	}
    	foreach($result['data'] as $key=>$value){
    		$yearrate = round(12000*$value['ratemoney']/$value['money']/$value['loanmonth'])/10;
    		if ($yearrate!=floor($yearrate)) $yearrate = $value['yearrate'];
    		$result['data'][$key]['yearrate'] = $yearrate;
    	}
    	if($result['data']){
    		$i = 2;
    		$cols_count = count($cols);
    		foreach($result['data'] as $val){
    			for($j=0;$j<$cols_count;$j++){
    				$PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
    				$PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
    			}
    			$i++;
    		}
    	}
    
    	$outputFileName = date('YmdHis').'数据.xls';
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
}
