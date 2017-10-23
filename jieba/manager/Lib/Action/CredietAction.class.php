<?php
class CredietAction extends CommonAction {
    private $page_num = 12;  
    private $_keys = array( 'name' => '姓名', 'username' => '会员名',  'cellphone' => '手机号','certiNumber'=>'身份证号','store_id'=>'门店');
    /**
     *      index list
     * */
    public function credietIndex(){
        
        $return = $this->getParam();
        $where = $return[0];
        $params = $return[1];
        $where['o.status'] = 1;
        $where['o.order_type'] = 3;
        $value = $this->_get('v', 'trim');
        if($this->_get('k', 'trim') == 'store_id' && !empty($value)){
           $where = "(order_credit.department like '%{$value}%' or ou.department like '%{$value}%') and o.status='1' and o.order_type='3'";
        }
        $count = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join("(select department,cer_card,GROUP_CONCAT(member_status SEPARATOR '|'),GROUP_CONCAT(delay_num SEPARATOR '|'),GROUP_CONCAT(return_num SEPARATOR '|'),GROUP_CONCAT(total_num SEPARATOR '|'),GROUP_CONCAT(product SEPARATOR '|') from order_upload  GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
            ->join('(select memberid,count(*) num from `order` where order_type=3 GROUP BY memberid ) as times on times.memberid=o.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->where($where)
            ->field('count(member.username) as num')
            ->order('o.id desc')
            ->find();
        $this->page['count'] = $count['num'];
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count['num'] / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $result = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('(select sum(score) as score,memberid from sign group by memberid) as sign on sign.memberid=o.memberid')
            ->join("(select department,cer_card,pay_money,GROUP_CONCAT(member_status SEPARATOR '|') member_status,GROUP_CONCAT(delay_num SEPARATOR '|') delay_num,GROUP_CONCAT(return_num SEPARATOR '|') return_num,GROUP_CONCAT(total_num SEPARATOR '|') total_num,GROUP_CONCAT(product SEPARATOR '|') product from order_upload  GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
            ->join('(select memberid,count(*) num from `order` where order_type=3 GROUP BY memberid ) as times on times.memberid=o.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')    
            ->where($where)
            ->field('member.username,member.recintcode,order_process.customer_status,if(order_credit.department is not null,order_credit.department,ou.department) as department,o.status,o.backtotalmoney,times.num,o.loanmonth,o.id,o.memberid,o.mobile,o.names,o.loanmoney,o.city,o.timeadd,o.resource,o.lasttime,o.timeadd,sign.score,member_info.certiNumber,ou.member_status,ou.delay_num,ou.return_num,ou.total_num,ou.product,ou.pay_money,sign.score'.$this->_department_ou)
            ->order('o.id desc')
            ->limit($limit)
            ->select();
            //var_dump(M()->getLastSql());exit;
        
        foreach($result as &$v){
        	//客户逾期记录数
        	$v['late_count'] = M()->table("`order` o,order_credit c")->where("o.id=c.order_id and c.status=1 and c.back_time<c.back_real_time and c.late_fee>0 and o.memberid='{$v['memberid']}'")->count("distinct o.id");
        	//客户拒单次数
        	$v['refuse_count'] = M()->table("`order` o,order_credit c")->where("o.id=c.order_id and o.status=3 and o.order_type=3 and o.memberid='{$v['memberid']}'")->count("distinct o.id");
            $v['activityName'] = (false == ($activityName = $this->avtivityName($v['id'],1)))?'暂无参加的活动':$activityName;
        }      
        $result = empty($result)?(array()):$result;
        $this->setPage("/Crediet/credietIndex{$params}/p/*.html");
        $this->assign('list',$result);
        $this->assign('keys',$this->_keys);
        $status = array('0'=>'未处理','1'=>'已接单','2'=>'已拒单','3'=>'接单后拒单');
        $this->assign('status',$status);
        $this->assign("resource",['ios'=>"IOS",'android'=>'安卓','browser'=>"微信"]);
        $this->setPage("/Crediet/credietIndex{$params}/p/*.html");
        $this->display();
    }
    /*
    获取用户提额活动的详情
    $order_id:订单id
    $status:活动券状态
     */
    public function avtivityName($order_id,$status)
    {
        $isApplyTicket = M("credit_ticket")->field("up_percent")->where("order_id={$order_id} and status={$status}")->find();
        $isApplyTurntable = M("activity_turntable")->field("prize,up_percent")->where("order_id={$order_id} and status=1" )->find();
        $activity = '';
        $orderInfo = M("order")->where("id='{$order_id}'")->find();
        if($isApplyTicket)//邀请注册提额
        {
           $activityName .= "邀请注册提额".round($isApplyTicket['up_percent'],0)."%；";
        }
        if($isApplyTurntable)//幸运大转盘提额
        {
           $activityName .= "幸运大转盘：额度提升".$isApplyTurntable['up_percent']."%";
        }
        if(strtotime($orderInfo['timeadd'])>=strtotime("2017-07-07 18:00:01") && strtotime($orderInfo['timeadd'])<strtotime("2017-08-01")){
            $activityName .= "7月提额50%;";
        }
        //提额活动
        $ac_map = [
            'status' => 1,
            'starttime' => ["ELT",$orderInfo['timeadd']],
            'endtime' => ["EGT",$orderInfo['timeadd']]
        ];
        $pro_ac_list = M('activity_promote')->field("keyword,pro_percent")->where($ac_map)->select();
        foreach ($pro_ac_list as $value) {
            $activityName .= $value['keyword']."提额".$value['pro_percent']."%";
        }
        if($activityName == '')return false;
        return $activityName;
    }

    /**
     *      拒单list
     * */
    public function refuseList(){
        $return = $this->getParam();
        $where = $return[0];
        $params = $return[1];
        $where['o.status'] = 3;//拒单
        $where['o.order_type'] = 3;//信贷
        
        if(!empty($where['o.timeadd'])){
        	$where['order_process.customer_time'] = $where['o.timeadd'];
        	unset($where['o.timeadd']);
        }
        $count = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->where($where)
            ->field('count(member.username) num')
            ->order('order_process.customer_time desc')
            ->find();
        $count = $count['num'];
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->join('order_credit on order_credit.order_id=o.id')
            ->where($where)
            ->field('member.username,order_credit.department,order_process.customer_status,order_process.customer_remark,order_process.customer,o.id,o.timeadd,o.status,o.memberid,o.mobile,o.names,o.backtotalmoney,member_info.certiNumber,order_process.customer_time')
            ->order('order_process.customer_time desc')
            ->limit($limit)
            ->select();
        //dump(M('`order` o')->getLastSql());
        $lists = empty($lists)?(array()):$lists;
        $this->setPage("/Crediet/refuseList{$params}/p/*.html");
        //$result = array_slice($lists,$limit,$this->page['num']);
        $this->assign('list',$lists);
        $this->assign('keys',$this->_keys);
        $status = array('2'=>'拒单','3'=>'接单后拒单');
        $this->assign('status',$status);
        $this->display();
    }

    /**
     * @return array
     * finish list
     */
    public function finishList($menu=''){
        $return = $this->getParam();
        $where = $return[0];
        $params = $return[1];
        $where['o.order_type'] = 3;//信贷
        if(empty($menu)){
            $menu = $this->_get('menu');
        }
        
            if(!empty($where['o.timeadd'])){
                $where['order_credit.pay_time'] = $where['o.timeadd'];
                unset($where['o.timeadd']);
            }
            if($where['order_process.customer_status'] != NULL){
                $where['order_credit.status'] = $where['order_process.customer_status'];
                unset($where['order_process.customer_status']);
            }
        
        $where['order_process.customer_status'] = array('in','4,5');//打款
        $count = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->where($where)
            ->field('count(member.username) as num')
            ->order('order_credit.pay_time desc')
            ->find();
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $count = $count['num'];
        $this->page['count'] = $count;
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->join('(select sum(score) as score,memberid from sign group by memberid) as sign on sign.memberid=o.memberid')
            ->where($where)
            ->field('member.username,if(order_credit.department is not null,order_credit.department,ou.department) as department,ou.pay_money as p,order_process.customer_status,o.id,o.timeadd,o.memberid,o.mobile,o.names,o.backtotalmoney,sign.score,order_credit.fee,order_credit.plat_fee,order_credit.pay_money,order_credit.ratemoney,order_credit.back_money,order_credit.status return_status,order_credit.back_real_time,order_credit.late_fee,member_info.certiNumber,order_credit.pay_time'.$this->_department)
            ->order('order_credit.pay_time desc')
            ->limit($limit)
            ->select();
        foreach ($lists as &$value) 
        {
            if(strtotime($value['timeadd']) > strtotime("2017-04-07 10:30"))
            {
                $value['activityName'] = (false == ($activityName = $this->avtivityName($value['id'],2)))?'暂无参加的活动':$activityName;
            }else{
                $value['activityName'] = '暂无参加的活动';
            }
        }
        //dump(M()->getLastSql());
        $lists = empty($lists)?(array()):$lists;
        $this->setPage("/Crediet/finishList{$params}/p/*.html");
        //$result = array_slice($lists,$limit,$this->page['num']);
        $this->assign('list',$lists);
        $this->assign('keys',$this->_keys);
        $total = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->where($where)
            ->field('count(member.username) as total_num,sum(order_credit.pay_money) as total_money')
            ->order('order_credit.pay_time desc')
            ->find();
        $this->assign('total',$total);//var_dump(M()->getLastSql(),$total);exit;
        if(!empty($menu)){
            $status = array('0'=>'未还款','1'=>'已还款');
            $this->assign('status',$status);
            $this->display('finishList');
        }else{
            $this->display();
        }
    }

    /**
     *       旧版还款列表
     */
    /*public function returnList(){
        $this->finishList(1);
    }*/


    /**
     *       新版还款列表
     */
    public function returnList(){
    	//dump($_REQUEST);
    	//$this->getStore();//获取成单门店
        $return = $this->getParam();
        $where = $return[0];
        $params = $return[1];
        $where['o.order_type'] = 3;//信贷
        $value = $this->_get('v', 'trim');
        if($this->_get('k', 'trim') == 'store_id' && !empty($value)){
           unset($where["order_credit.store_id"]);
            //$where["_string"] =  "(order_credit.department like '%{$value}%')  OR (order_upload.department like '%{$value}%')" ;
            $where["_string"] =  "(order_credit.department like '%{$value}%') " ;
         } 
        if($_SESSION['user']['groupid']==3){
             $citys = M('store')->where("type=2 and manager='{$_SESSION['user']['username']}'")->getField('city');
             $where['order_credit.department'] =["like","%$citys%"];
        }elseif($_SESSION['user']['groupid']==24){//该权限组内可以查看指定门店的订单
            //$where['order_credit.store_id'] = ["exp"," in(SELECT `id` FROM `store` WHERE type=2 and (name not like '%长圣%' or name not like '%荣耀%' or name not like '%六盘水%' or name not like '%攀枝花%' or name not like '%隆泰%'  or name not like '%郑州%')) "];
            $where["order_credit.department"] = ["exp"," not like '%长圣%' and order_credit.department not like '%荣耀%' and order_credit.department not like '%六盘水%' and order_credit.department not like '%攀枝花%' and order_credit.department not like '%隆泰%'  and order_credit.department not like '%郑州%'"];
        }
        if(!empty($where['o.timeadd'])){
            $where['order_credit.back_time'] = $where['o.timeadd'];
            unset($where['o.timeadd']);
        }
        $where['order_process.customer_status'] = array('in','4,5');//打款
        
        $count =  M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->where($where)
            //->field('count(member.username) num')
            //->order('o.id desc')
            ->count();
        //$count = $count['num'];
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        
        //逾期未还款的用户，自动计算滞纳金，逾期天数
        $is_late_field = " case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 if(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)>5,
        					 (round((ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)-5)*0.02*o.loanmoney,2)+
        					 round(5*0.01*o.loanmoney,2)),
        					 round(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)*0.01*o.loanmoney,2)
        					 ) 
        					 when order_credit.status=1 then order_credit.late_fee
        					 end as late_fee,
        					case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)
        		             when   order_credit.status=1 then order_credit.late_days
        					 end as late_days";
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            //->join('order_upload  on order_upload.cer_card=member_info.certiNumber')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            //->join("(select department city,cer_card from order_upload GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
            ->where($where)
            ->limit($limit)
            ->field("member.username,order_process.customer_status,o.id,o.timeadd,o.memberid,o.mobile,o.resource,order_credit.department as city,o.names,o.loanmoney,o.backtotalmoney,order_credit.pay_money,order_credit.pay_time,order_credit.back_time,order_credit.fee,order_credit.plat_fee,order_credit.ratemoney,order_credit.back_money,order_credit.status return_status,order_credit.back_real_time,member_info.certiNumber,{$is_late_field}")
            ->order('order_credit.back_time desc,member_info.names desc')
            ->select();//var_dump(M()->getLastSql());
        
	
        $lists = empty($lists)?(array()):$lists;
        $this->page['count'] = $count;
        $this->page['total'] = ceil($count / $this->page['num']);
        $this->setPage("/Crediet/returnList{$params}/p/*.html");
        $this->assign('list',$lists);
        $this->assign('keys',$this->_keys);
        $status = array('0'=>'未还款','1'=>'已还款');
        $is_return = array('1' => '未还款','2' => '已还款');
        $this->assign('status',$status);
        $this->assign("resource",['ios'=>"IOS",'android'=>'安卓','browser'=>"微信"]);
        $this->assign('is_return',$is_return);
        $this->display();
    } 
    public function export_return_list(){
        $type = $this->_get('type', 'trim');
        $this->export_data('return_list',$type);
    }
    public function export_data_return_list(){
        $return = $this->getParam();
        $where = $return[0];
        $where['o.order_type'] = 3;//信贷
        $type = $this->_get('type', 'trim');
        if(!empty($where['o.timeadd'])){
            if(empty($type)){
            	//fry(范若燕)账号按照“还款截止日期”导出，其他账号按照“实际还款日期”导出
            	if($_SESSION['user']['username']=='fry')
                	$where['order_credit.back_time'] = $where['o.timeadd'];
            	else 
            		$where['order_credit.back_real_time'] = $where['o.timeadd'];
            	
            }else{
                $where['order_credit.pay_time'] = $where['o.timeadd'];
            }
            unset($where['o.timeadd']);
        }
        $where['order_process.customer_status'] = array('in','4,5');//打款
        
        //逾期未还款的用户，自动计算滞纳金，逾期天数
        $is_late_field = " case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 if(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)>5,
        					 (round((ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)-5)*0.02*o.loanmoney,2)+
        					 round(5*0.01*o.loanmoney,2)),
        					 round(ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)*0.01*o.loanmoney,2)
        					 ) 
        					 when order_credit.status=1 then order_credit.late_fee
        					 end as late_fee,
        					case
        		             when  order_credit.back_time<(NOW()) and order_credit.status=0 then
        					 ceil((unix_timestamp(NOW())-unix_timestamp(order_credit.back_time))/86400)
        		             when   order_credit.status=1 then order_credit.late_days
        					 end as late_days";
        
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join("(select department,cer_card from order_upload GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
            ->where($where)
            ->field("member.username,order_process.customer_status,o.id,o.timeadd,o.memberid,o.mobile,if(order_credit.department is not null,order_credit.department,ou.department) as city,o.names,o.loanmoney,o.backtotalmoney,order_credit.plat_fee,order_credit.pay_money,order_credit.pay_time,order_credit.back_time,order_credit.fee,order_credit.ratemoney,order_credit.back_money,order_credit.status return_status,order_credit.back_real_time,member_info.certiNumber,{$is_late_field}")
            ->order(empty($type)?"order_credit.back_real_time desc":"order_credit.pay_time desc")
            ->select();
        foreach($lists as &$list){
            $list['returnnmoney'] = $list['loanmoney']+$list['late_fee'];
        }
        foreach($lists as &$list){
            $list['late_days'] = $list['late_days']?$list['late_days']:0;
        }
        $cols = array(
            array('city','门店'),
            array('names','	客户姓名'),
            array('mobile','客户手机号码'),
            array('loanmoney','	借款金额'),
            array('fee','手续费'),
            array('plat_fee','平台管理费'),
            array('ratemoney','利息'),
            array('pay_money','到账金额'),
            array('pay_time','放款日期'),
        );

        if(empty($type)){
            $cols[] = array('back_money','还款金额');
            $cols[] = array('late_fee','滞纳金');
            $cols[] = array('returnnmoney','应还金额');
            $cols[] = array('pay_time','还款起始日期');
            $cols[] = array('back_time','还款截止日期');
            $cols[] = array('back_real_time','客户还款日期');
            $cols[] = array('late_days','逾期天数');
        }

        return array('data'=>$lists,'cols'=>$cols);
    }
    private function getParam(){
        $params = '';$where = array();
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $is_return = $this->_get('is_return','trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        
        //是否员工  1:客户车友贷   2：员工车友贷
        if($_REQUEST['is_staff']=='2'){
        	$where["o.memberid"] = array('exp'," in(select i.memberid from member_info i,staff s where i.certiNumber=s.certiNumber and s.status=0)");
        	$params .= "/is_staff/{$_REQUEST['is_staff']}";
        }elseif($_REQUEST['is_staff']=='1'){ 
        	$where["o.memberid"] = array('exp'," not in(select i.memberid from member_info i,staff s where i.certiNumber=s.certiNumber and s.status=0)");
        	$params .= "/is_staff/{$_REQUEST['is_staff']}";
        }
        if(!empty($is_return)) {
            $where['order_credit.status'] = ($is_return == 1)?'0':'1';
            $params .="/is_return/".$is_return;
        }
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $where['member.username'] = $value;
                    break;
                case 'name':
                    $where['o.names'] = $value;
                    break;
                case 'cellphone':
                    $where['member.mobile'] = $value;
                    break;
                case 'certiNumber':
                    $where['member_info.certiNumber'] = $value;
                    break;
                case 'store_id':
                    $where['order_credit.store_id'] = array('exp'," in(select id from store where type=2 and name like '%{$value}%')");
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        if($starttime && !$endtime){
            $where['o.timeadd'] = array('egt',$starttime);
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $where['o.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $where['o.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        $order_status = $this->_get('order_status','trim');//状态
        if($order_status !=''){
            $where['order_process.customer_status'] = $order_status;
            $params .= '/order_status/'.$order_status;
        }
        //最新筛选门店
        $value = $this->_get('v', 'trim');
        if($this->_get('k', 'trim') == 'store_id' && !empty($value)){
           unset($where["order_credit.store_id"]);
            $where["_string"] =  "(order_credit.department like '%{$value}%')  OR (ou.department like '%{$value}%')" ;
         } 
        return array($where,$params);
    }
    /**
     *      导入订单信息
     * */
    public function orderUpload(){
        $this->display();
    }
    public function orderUploadCommit(){
        $type = 'orderstatus';
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->allowExts  = array('xls', 'xlsx');// 设置附件上传类型
        $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
        $upload->maxSize  = 8145728 ;// 设置附件上传大小
        $upload->saveRule = 'order_'.time();
        if(!$upload->upload()) {// 上传错误提示错误信息
            echo $upload->getErrorMsg();
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
            vendor("PHPExcel.PHPExcel");
            vendor('PHPExcel.PHPExcel.IOFactory');
            vendor('PHPExcel.PHPExcel.Writer.Excel5');
            $file_name = $info[0]['savepath'].$info[0]['savename'];           //'../manager/aaa.xlsx';
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            if(!$objReader->canRead($file_name)){
                $objReader = PHPExcel_IOFactory::createReader('Excel5');
                if(!$objReader->canRead($file_name)){
                    $this->redirect('/Staff/addStaff');
                    return;
                }
            }
            $objPHPExcel = $objReader->load($file_name,$encode='utf-8');
            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow(); // 取得总行数
            //M('order_upload')->where("1=1")->delete(); //这样能删除成功
            M('order_upload')->execute('truncate table order_upload');
            $j = $k = 0;
            for($i=2;$i<=$highestRow;$i++) {
                $mobile = $objPHPExcel->getActiveSheet()->getCell("G".$i)->getValue();//手机号
                $cer_card = $objPHPExcel->getActiveSheet()->getCell("H".$i)->getValue();//身份证号码
                $total_num = $objPHPExcel->getActiveSheet()->getCell("I".$i)->getValue();//总期数
                $return_num = $objPHPExcel->getActiveSheet()->getCell("J".$i)->getValue();//已还期数
                $delay_num = $objPHPExcel->getActiveSheet()->getCell("K".$i)->getValue();//逾期期数
                $member_status = $objPHPExcel->getActiveSheet()->getCell("L".$i)->getValue();//账户状态
                $name = $objPHPExcel->getActiveSheet()->getCell("C".$i)->getValue();//客户名称
                $department = $objPHPExcel->getActiveSheet()->getCell("A".$i)->getValue();//签约部门
                $product = $objPHPExcel->getActiveSheet()->getCell("B".$i)->getValue();//签约产品
                $car_num = $objPHPExcel->getActiveSheet()->getCell("D".$i)->getValue();//车牌号
                $per_money = $objPHPExcel->getActiveSheet()->getCell("F".$i)->getValue();//每期还款
                $contract_num = $objPHPExcel->getActiveSheet()->getCell("E".$i)->getValue();//合同编号
                if(empty($total_num) || !in_array(trim($member_status),array('正常','M1','M2','M3+','M3+固定'))){
                    $j = $j + 1;
                    continue;
                }
                $data['mobile'] = $mobile;
                $data['cer_card'] = trim($cer_card);
                $data['total_num']= trim($total_num);
                $data['return_num']= trim($return_num);
                $data['delay_num']= empty($delay_num)?0:$delay_num;
                $data['member_status']= trim($member_status);
                $data['name']= $name;
                $data['department']= $department;
                $data['product']= $product.','.$car_num;
                $data['pay_money']= trim($per_money);
                $data['date']= date('Y-m-d H:i:s');
                $data['contract_num'] = $contract_num;
                $uploadExist = M("order_upload")->where(["cer_card"=>$data['cer_card']])->find();
                if(false==$uploadExist){
                    M('order_upload')->add($data);
                    $k = $k + 1;
                }elseif(intval($data['return_num'])>intval($uploadExist["return_num"])){
                    M('order_upload')->where(["id"=>$uploadExist["id"]])->save(["return_num"=>$data['return_num'],"pay_money"=>$data["pay_money"]]);
                }

            }
            M()->query("update order_upload   set  return_num=3  where name like '%续贷%' and (return_num is null or return_num<3)");
            echo '导入成功';echo "未导入".$j;echo "导入".$k;
        }
    }

    /**
     *
     */
     public function export_crediet_list(){
         $this->export_data('crediet_list');
     }
    public function export_data_crediet_list(){
        $return = $this->getParam();
        $where = $return[0];
        $where['o.status'] = 1;
        $where['o.order_type'] = 3;
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('(select sum(score) as score,memberid from sign group by memberid) as sign on sign.memberid=o.memberid')
            ->join("(select department,cer_card,pay_money,GROUP_CONCAT(member_status SEPARATOR '|') member_status,GROUP_CONCAT(delay_num SEPARATOR '|') delay_num,GROUP_CONCAT(return_num SEPARATOR '|') return_num,GROUP_CONCAT(total_num SEPARATOR '|') total_num,GROUP_CONCAT(product SEPARATOR '|') product from order_upload  GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
            ->join('(select memberid,count(*) num from `order` where order_type=3 GROUP BY memberid ) as times on times.memberid=o.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')    
            ->where($where)
            ->field('member.username,member.recintcode,order_process.customer_status,if(order_credit.department is not null,order_credit.department,ou.department) as department,o.status,o.backtotalmoney,times.num,o.loanmonth,o.id,o.memberid,o.mobile,o.names,o.loanmoney,o.city,o.timeadd,o.lasttime,o.timeadd,sign.score,member_info.certiNumber,ou.member_status,ou.delay_num,ou.return_num,ou.total_num,ou.product,ou.pay_money,sign.score'.$this->_department_ou)
            ->order('o.id desc')
            ->select();
            foreach($lists as &$v){
            //客户逾期记录数
            $v['late_count'] = M()->table("`order` o,order_credit c")->where("o.id=c.order_id and c.status=1 and c.back_time<c.back_real_time and c.late_fee>0 and o.memberid='{$v['memberid']}'")->count("distinct o.id");
            //客户拒单次数
            $v['refuse_count'] = M()->table("`order` o,order_credit c")->where("o.id=c.order_id and o.status=3 and o.order_type=3 and o.memberid='{$v['memberid']}'")->count("distinct o.id");
            //dump(M()->getLastSql());exit;
        }
        foreach($lists as &$list){
            if($list['customer_status']==0){
                $list['customer_status'] = '未处理';
            }elseif($list['customer_status']==1){
                $list['customer_status'] = '已接单';
            }elseif($list['customer_status']==2){
                $list['customer_status'] = '已拒单';
            }elseif($list['customer_status']==3){
                $list['customer_status'] = '通过后拒单';
            }elseif($list['customer_status']==4){
                $list['customer_status'] = '已打款';
            }elseif($list['customer_status']==5){
                $list['customer_status'] = '已还款';
            }elseif($list['customer_status']==6){
                $list['customer_status'] = '已签约';
            }else{
                $list['customer_status'] = '未知';
            }
        }
        $cols = array(
            array('department','门店'),
            array('mobile','手机号码'),
            array('username','申请人名称'),
            array('names','真实姓名'),
            array('certiNumber','身份证号码'),
            array('late_count','车友贷逾期次数'),
            array('refuse_count','拒单次数'),
            array('recintcode','推荐人'),
            array('backtotalmoney','贷款金额'),
            array('loanmonth','期限'),
            array('product','抵贷记录'),
            array('return_num','已还款期数'),
            array('delay_num','已逾期期数'),
            array('pay_money','每期还款'),
            array('score','积分'),
            array('num','申请次数'),
            array('timeadd','申请时间'),
            array('customer_status','状态'),
        );
        return array('data'=>$lists,'cols'=>$cols);
    }
    public function export_refuse_list(){
        $this->export_data('refuse_list');
    }
    public function export_data_refuse_list(){
        $return = $this->getParam();
        $where = $return[0];
        $where['o.status'] = 3;//拒单
        $where['o.order_type'] = 3;//信贷
        if(!empty($where['o.timeadd'])){
        	$where['order_process.customer_time'] = $where['o.timeadd'];
        	unset($where['o.timeadd']);
        }
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
            ->join('order_credit on order_credit.order_id=o.id')
            ->where($where)
            ->field('member.username,if(order_credit.department is not null,order_credit.department,ou.department) as department,order_process.customer_status,order_process.customer_remark,order_process.customer,o.id,o.timeadd,o.status,o.memberid,o.mobile,o.names,o.backtotalmoney,member_info.certiNumber,order_process.customer_time')
            ->order('order_process.customer_time desc')
            ->select();
        foreach($lists as &$list){
            if($list['customer_status']==2){
                $list['customer_status'] = '已拒单';
            }else{
                $list['customer_status'] = '接单后拒单';
            }
        }
        $cols = array(
            array('department','门店'),
            array('mobile','手机号码'),
            array('names','真实姓名'),
            array('certiNumber','身份证号码'),
            array('backtotalmoney','贷款金额'),
            array('customer_remark','拒单理由'),
            array('customer','拒单人'),
            array('customer_status','拒单类型'),
            array('customer_time','拒单时间'),
        );
        return array('data'=>$lists,'cols'=>$cols);
    }
    public function export_finish_list(){
        $this->export_data('finish_list');
    }
    public function export_data_finish_list(){
        $return = $this->getParam();
        $where = $return[0];
        $menu = $this->_get('menu');
        
       if(!empty($where['o.timeadd'])){
                $where['order_credit.pay_time'] = $where['o.timeadd'];
                unset($where['o.timeadd']);
       }
       if($where['order_process.customer_status'] != NULL){
                $where['order_credit.status'] = $where['order_process.customer_status'];
                unset($where['order_process.customer_status']);
       }
       
        $where['order_process.customer_status'] = array('in','4,5');//打款
        $where['o.order_type'] = 3;//信贷
        $lists = M('`order` o')
            ->join('member_info on member_info.memberid=o.memberid')
            ->join('member on member.id=member_info.memberid')
            ->join('order_process on order_process.order_id=o.id')
            ->join('order_credit on order_credit.order_id=o.id')
            ->join('order_upload ou on ou.cer_card=member_info.certiNumber')
           ->join('(select sum(score) as score,memberid from sign group by memberid) as sign on sign.memberid=o.memberid')
            ->where($where)
            ->field('member.username,if(order_credit.department is not null,order_credit.department,ou.department) as department,ou.pay_money as p,order_process.customer_status,o.id,o.timeadd,o.memberid,o.mobile,o.names,o.backtotalmoney,sign.score,order_credit.fee,order_credit.plat_fee,order_credit.pay_money,order_credit.ratemoney,order_credit.back_money,order_credit.status return_status,order_credit.back_real_time,order_credit.late_fee,member_info.certiNumber,order_credit.pay_time'.$this->_department)
            ->order('order_credit.pay_time desc')
            ->select();//var_dump(M()->getLastSql());exit;
        foreach($lists as &$list){
            if($list['return_status']==1){
                $list['return_status'] = '已还款';
            }else{
                $list['return_status'] = '未还款';
            }
            $list['taotl_money'] = $list['backtotalmoney']+$list['ratemoney']+$list['fee']+$list['late_fee']+$list['plat_fee'];
        }
        $cols = array(
            array('department','门店'),
            array('mobile','手机号码'),
            array('names','真实姓名'),
            array('certiNumber','身份证号码'),
            array('backtotalmoney','贷款金额'),
            array('ratemoney','利息'),
            array('fee','手续费'),
            array('plat_fee','平台管理费'),
            array('taotl_money','总额'),
            array('pay_money','	放款金额'),
            array('p','每期还款'),
            array('score','积分'),
        );
        if(!empty($menu)){
            $cols[] = array('late_fee','滞纳金');
            $cols[] = array('return_status','还款状态');
            $cols[] = array('back_real_time','还款时间');
        }else{
            $cols[] = array('pay_time','成单时间');
        }
        return array('data'=>$lists,'cols'=>$cols);

    }
     /**
     *       下载数据
     *
     */
    public function export_data($type="",$return_type=''){
        $export_type = array('crediet_list'=>'信贷申请中列表','finish_list'=>'成单列表','return_list'=>'还款列表');
        if(!empty($return_type)){
            $export_type['return_list'] = '打款列表';
        }
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
    public function downpact(){
         //判断模版
        //$protocal_bycate = M('ProtocolTemplate')->where(array('status'=>1))->find();
//        import('Think.ORG.Util.html2pdf');
//        HTMLPDF::makenew(array());
        /*import('Think.ORG.Util.Mail');
        $address = 'yinpingyao11@126.com';
        $title = 'test';
        $content = 'testss';
        Mail::send($address, $title, $content);*/
        // 由文件或 URL 创建一个新图象，这里的text.jpg就是效果1显示的预设图片
       /* $rImg = imagecreatefrompng(APP_PATH."../static/Upload/bk.png");
        // 设置最终生成图片的宽度以及文字相对于图片所在的高度
        $color = imagecolorallocate($rImg, 255, 0, 0);
        $font_size = APP_PATH."../static/Upload/msyh.ttc";
        $name = 'michelle';
        //($image, $size, $angle, $x, $y, $color, $fontfile, $text)
        imagettftext($rImg,89,0,20,110,$color,$font_size,"姚银萍");
        //imagecopymerge($rImg,$rImg,120,405,0,0,$rImg,$rImg,100);// 输出图象到浏览器或文件，quality 为可选项，范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）
        imagepng($rImg,APP_PATH."../static/Upload/qrcode/michelle_1.png");*/
        $sealdata = '{"data":"PFNlYWw+PEhlYWRlcj48SUQ+RVM8L0lEPjx2ZXJzaW9uPjMwMDE8L3ZlcnNpb24+PFZpZD5UaW1ldmFsZTwvVmlkPjxsZW5ndGg+MDwvbGVuZ3RoPjwvSGVhZGVyPjxTZWFsSW5mbz48ZXNJRD5udWxsPC9lc0lEPjxQcm9wZXJ0eUluZm8+PHR5cGU+MzwvdHlwZT48bmFtZT7nn7PlkJHliY08L25hbWU+PGNlcnQ+NDAwMTM4Nzg2OTwvY2VydD48dmFsaWRTdGFydD48L3ZhbGlkU3RhcnQ+PHZhbGlkRW5kPjwvdmFsaWRFbmQ+PC9Qcm9wZXJ0eUluZm8+PFBpY3RydWVJbmZvPjx0eXBlPlBORzwvdHlwZT48ZGF0YT5pVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBWDRBQUFGK0NBTUFBQUN5QklIT0FBQUFERkJNVkVYLy8vLy9BQUFBQVA4QUFBQnZ4Z2ozQUFBQUFYUlNUbE1BUU9iWVpnQUFCRTFKUkVGVWVOcnQzYzEybzBZUUJsQVg0ZjFmdVpLY0xHWnlQQWdhK2hmZnU1akZXRWJvbzdyb2xoRCsrZ0lBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUQ0TGtJR0EyMGlFTC80RWIvNEViLzQ2V0gvOTUrVXc0Z2xsK3JYZk1TUCtNVy8zcW5yRlRPZlJjT1Bibk8yT0pzZXhzMEo1TDUyOWZRN0F2ODhWVDUreEF0N2Y3OVBMS0wrdnJ6aDFOditBT1RsczAzaHZtemRvNHBtNlhUSVA4N0hSVXdjZjlTZnNXVDI2UDU1T2R5Y04vNVlmOFlZVld0aEc3SHJVVEdGWGhPZi9KRC8vWjNaSml5aFpldS92QlMyay9Oa3RObnZxRjZVL2NxL1pxSHRWMzQvNnhkTnZPZ3poamc4UUhHMkZ2c1lmMGE3WHZFcy8raGUvTC9TS0IwZW4xN3BObXhveDh1S1B3OS9Fak91ZW5PU2JYUW9zTGpiKy9QQ2UxcXhSaGpYQXN2aVl4NFhYdnhoLzltWHJLOW9zM054LzVTVW41dnIwV2IzdWJwSlVUWG05V09WNVIyajdCRm52M2FRLzFhckVoc1YrZk9GUi95M2dwbnpWREwxRzg3NWNaR2Y5YXNrbTVaU2krYlQvc1JiWjRWUXVwaytJMkdGRHh2alFUMFhIc2FNdmgxb203NzQreTcwc3UvcmNKM1BVUHNTeFo4MVNqN2ZGLzg2NW15TzJ3ckYvMTU2LzdyeEszN1ZQK1g4OWVxbnRMdmkvK01yeXc4L3UzWmg2YVZIMWFqK043YWV3K290dVZUcGZBaHNiUWZoNnc1QTRhVktaMDFvVi96MzZxdk9tME9iNG04YlFMYUozNlN6Um12WUcyKy9UZFhsY1VsazIvRGkrdzgvZmJIb2JHKzJwaU92MVppUHhqdDI1Wk9CdkRYUnJ4UC82TmJ6WVZiUzU4dEdlV2VhV1gzZW55UEh3TkYveFVRNzFXVFZHOFVQcjNlYzhuemhFNFBLSXZ0VWYySHJpYStmY2NmRUc4ZDhhLzk4TWJnbFRKeityZVp6TThqS2wvVG5HNVlpKzVQMFkyais4ODBIZWpTZkIwM0VMWE9ycjNwZldwYXpWbjlJZjJEMVIzbWlJZjFhMWE5NUw5ejdseE56VmI4K01qSis2WStNWC9vamUvOFBURDlibncwMjZTL1JmS1EvTW43cGo0eGYraVBqbC80TTgzN3BENGsvcEQ4d2Z1bVBqRi82SStPWC9zajRwZC9TdnV5TU05NGYvMDh2L2JFZnQyZzhJNnQvN3ZTemY2MTJqVi90VC9CKy80THBMelFLdHBQZzlmMWh2WC9WNkhPZHNiYmVkVDR4VmZodGwxMEtYL3h2elg3ZCtCc25IYjEyYVpmOHlOMXlpZTNRbXRqN2o5SzNKZi9rVzhTcWYyZzNkQ3U5bFdjK0pWOHVRdldMSDZmZWhpdTFMT2kzNG0rNVpENDlOV28rVnIzTnFxL241dkxPYzIvTHhqSmxXZi8vQm52NUU2cy9LeC9xUDVYMWNiQlo5TlF2UGZWK20zM2s0dzFHaTNIVHZ2bU11c2RVN1Q4QjlmdjJMdjBseVN2UHZ6MGZsMDJuQms5K05aOXVJWi9zejZVSDdYM0NIM1JqeDZpYWZrYnQ5Ty9leDdOUDJjZmpZUk5WU3o4djNTUTZDMjViK3RmVTA4SEg5MStOeWdPdjV0MEFZL3I0WC8vbWhEY2RoaEsvK01XUCtNV1ArTVdQK01XUCtNV1ArTVdQK04vRFg3TlIvZUpIL09KSC9PSkgvQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBTUF3ZndNU0J0R0ZkM0ZZQ3dBQUFBQkpSVTVFcmtKZ2dnPT0KPC9kYXRhPjx3aWR0aD4xMzY8L3dpZHRoPjxoZWlnaHQ+MTM2PC9oZWlnaHQ+PC9QaWN0cnVlSW5mbz48L1NlYWxJbmZvPjxTaWduSW5mbz48Y2VydD5NSUlEeVRDQ0EyMmdBd0lCQWdJSWRNRUFNd0FBQUd3d0RBWUlLb0VjejFVQmczVUZBREJTTVFzd0NRWURWUVFHRXdKRFRqRXZNQzBHQTFVRUNnd21XbWhsYW1saGJtY2dSR2xuYVhSaGJDQkRaWEowYVdacFkyRjBaU0JCZFhSb2IzSnBkSGt4RWpBUUJnTlZCQU1NQ1ZwS1EwRWdUME5CTVRBZUZ3MHhOREExTURrd09UQXdNakZhRncweE56QTFNRGd3T1RBd01qRmFNSUdFTVFzd0NRWURWUVFHRXdKRFRqRU1NQW9HQTFVRUNBd0RNVEV4TVFzd0NRWURWUVFIREFJeE1URWNNQm9HQTFVRUNnd1Q1YVNwNkxDM1grYTFpK2l2bFRJeU1ERXpPREVOTUFzR0ExVUVDd3dFZW1wallURWRNQnNHQ1NxR1NJYjNEUUVKQVJZT01USXpNak16TWtCeGNTNWpiMjB4RGpBTUJnTlZCQU1NQldObGMyaHBNRmt3RXdZSEtvWkl6ajBDQVFZSUtvRWN6MVVCZ2kwRFFnQUVSK0hseXphMGdCY3NwdVZ4SGRKTWM1YkY4MVM5S094RjhwMnRrTU9kRWFyN3gyZUFRM2ZpWFVpaWwwck9OeFpUQUtaaXVKSm0rblBjdmdYQk9SQ0RZNk9DQWZZd2dnSHlNQXdHQTFVZEV3UUZNQU1CQVFBd0hRWURWUjBsQkJZd0ZBWUlLd1lCQlFVSEF3SUdDQ3NHQVFVRkJ3TUVNQXNHQTFVZER3UUVBd0lBd0RBUkJnbGdoa2dCaHZoQ0FRRUVCQU1DQUlBd0VRWUlLb0VjMEJRRUFRRUVCVEV4TVRFeE1COEdBMVVkSXdRWU1CYUFGS2ZUc1NTUUlCMDl0RlR1U3pjb1VwR3VMR29pTUlHcEJnTlZIUjhFZ2FFd2daNHdnWnVnZ1ppZ2daV0dnWkpzWkdGd09pOHZOakF1TVRrd0xqSTFOQzR4TVRvek9Ea3ZRMDQ5V2twRFFTQlBRMEV4TEVOT1BWcEtRMEVnVDBOQk1Td2dUMVU5UTFKTVJHbHpkSEpwWW5WMFpWQnZhVzUwY3l3Z2J6MTZhbU5oUDJObGNuUnBabWxqWVhSbFVtVjJiMk5oZEdsdmJreHBjM1EvWW1GelpUOXZZbXBsWTNSamJHRnpjejFqVWt4RWFYTjBjbWxpZFhScGIyNVFiMmx1ZERDQm93WUlLd1lCQlFVSEFRRUVnWll3Z1pNd2daQUdDQ3NHQVFVRkJ6QUNob0dEYkdSaGNEb3ZMell3TGpFNU1DNHlOVFF1TVRFNk16ZzVMME5PUFZwS1EwRWdUME5CTVN4RFRqMWFTa05CSUU5RFFURXNJRTlWUFdOQlEyVnlkR2xtYVdOaGRHVnpMQ0J2UFhwcVkyRS9ZMEZEWlhKMGFXWnBZMkYwWlQ5aVlYTmxQMjlpYW1WamRFTnNZWE56UFdObGNuUnBabWxqWVhScGIyNUJkWFJvYjNKcGRIa3dIUVlEVlIwT0JCWUVGRmF4V1hiS2k3Um9wZTcyWitLdUJjM0c4UERNTUF3R0NDcUJITTlWQVlOMUJRQURTQUF3UlFJZ05EaVpVODgwRTN0WVNaUzh6TTNKeVpIZExaOGNiLzUzeXYvTXVvL0tNRTBDSVFDdUpNRTZpanNwd1FzVzRjcjZFTk9RZGE0VUVVbklIRVVBT3VMdWYwZEVIdz09CjwvY2VydD48c2lnbmF0dXJlQWxnb3JpdGhtPlNHRF9TSEEyNTZfUlNBPC9zaWduYXR1cmVBbGdvcml0aG0+PHNpZ25EYXRhPlQ3Wkc3dGZzU1JlSlE0bHFvUUt2WFpEQnZvdE9OOHhqR2FXSGtMUUVkNngzQVBjdnZKbzMxTkw3cG1lSXBuVnV0UGdua0NRNXV5OVF5SWh4MlJBMG5zTmZjRm1NNmRRbjZydFcvU2FWMHlJa0poTFA3WG0vOUNrQ05XWjlGU0NDaHpVeG1BRlBwbmRTQTk3KzdScHpxOXc5a3BaRjJsZUF6Q1h2bncwNExJMi9zY0g1YWV0enVqN29Xa3Z2RFpLRndFRkpVWWVVR2J3SisrT0hiL1l5akNNVk5sR2h5VVNQL3FqSGxWakRwQ290ZDhpOUhnM2tpR2xIdkhyYmdseWlPMDluNUVub1prc0NVdWkvQ21HNmlLVXpnOENBZlJUQitmUWU3WXZJL2liWkJnQllVVUJjVnQ2YnlCM3BqaVZKNHVrVGRXYzI1MWlyRzBBTm9oZE5UZz09Cjwvc2lnbkRhdGE+PHNpZ25EYXRlPjIwMTYtMDktMjMgMTY6MDQ6MzQ8L3NpZ25EYXRlPjwvU2lnbkluZm8+PC9TZWFsPg==","certB64":"MIIEzTCCA7WgAwIBAgIFQAE4eGkwDQYJKoZIhvcNAQELBQAwWDELMAkGA1UEBhMCQ04xMDAuBgNVBAoMJ0NoaW5hIEZpbmFuY2lhbCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eTEXMBUGA1UEAwwOQ0ZDQSBBQ1MgT0NBMzEwHhcNMTYwOTIyMDYzNjM1WhcNMTgwOTIyMDYzNjM1WjB9MQswCQYDVQQGEwJDTjEXMBUGA1UECgwOQ0ZDQSBBQ1MgT0NBMzExDjAMBgNVBAsMBXRzaWduMRUwEwYDVQQLDAxJbmRpdmlkdWFsLTExLjAsBgNVBAMMJXRzaWduQOefs+WQkeWJjUBaMzcxMzI0MTk5MTAyMTQ0MzE2QDEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQCF9nlEXtOQdeR8uETxgvH17HdqGssMIcjtTiHOcQARGk+36BBtxJ77RJ1iXPgFuZzuGPbTjFsGeXrfi27wKUnEW3GwSzOvcJs9k6E3rSE1I4tbLAln0+pTQ7t9xqv9Z7PYyYgMMTbMHE2fEb\/Zrf8jKAmIdbvSbA2RZ4nnN1jIwOfDSsxWDJ6UUJH\/DA7oi9NbikWmbfa0uA0S\/eC2yz13xqgTICK6vim5B\/m7jX3si8G3O8SYsdHQ3yUy1EmAQ3TFtwYQMFYL7hf6u2S\/EyLVFFzSEpVwGKerKkykYNwTvWz+Qle7G5q9waLU7gSKMbLMqCo2hjxT9BXfnMZILCZ9AgMBAAGjggF3MIIBczBsBggrBgEFBQcBAQRgMF4wKAYIKwYBBQUHMAGGHGh0dHA6Ly9vY3NwLmNmY2EuY29tLmNuL29jc3AwMgYIKwYBBQUHMAKGJmh0dHA6Ly9jcmwuY2ZjYS5jb20uY24vb2NhMzEvb2NhMzEuY2VyMB8GA1UdIwQYMBaAFOK0CcvNYaFzSnl\/8YqDC920fowdMEgGA1UdIARBMD8wPQYIYIEchu8qAQQwMTAvBggrBgEFBQcCARYjaHR0cDovL3d3dy5jZmNhLmNvbS5jbi91cy91cy0xNC5odG0wDAYDVR0TAQH\/BAIwADA8BgNVHR8ENTAzMDGgL6AthitodHRwOi8vY3JsLmNmY2EuY29tLmNuL29jYTMxL1JTQS9jcmwyNzguY3JsMA4GA1UdDwEB\/wQEAwIGwDAdBgNVHQ4EFgQUUbxRsRMqvZE8BRRS6OTdAvN+xtMwHQYDVR0lBBYwFAYIKwYBBQUHAwIGCCsGAQUFBwMEMA0GCSqGSIb3DQEBCwUAA4IBAQByyBr8+eHnKGLVY1wS4CazQ+WYmgZk\/xAIqU7CBcXoA\/\/+vN5XwxiYagnFuMjmYSwP+rUX\/3S5BB9jouMqMXiXZrdwLfb5eumYtmlXituuFpU6RQHfXnL8pNh\/zHWdnK0hg4HLq3BIyv0fGqvM3WAhou1ApuIAvOLVs27BiDxA\/oT7T8SKb5bB8eClhMJm3Um1NYULBn6lRIFzTtuy8NQcQjJrGTF8il5ORUv\/zHkM0Ehx1MVfK8ywQFEU67\/4gdXbIMADgy0cH\/wPnbeE7xkl1p8gIJcONHbSiQnQ7FskyQT1Mk6ZnHy0Ide8V2V9g+csWWYJnx7ybewG0OdOF9u1","certId":45820}';
        $sealdata_array = json_decode($sealdata,true);
        $secd = $sealdata_array['data'];
        $third = base64_decode($secd);
        preg_match("/<(data.*?)>(.*?)<(\/data.*?)>/si",$third,$matched);
        $img=base64_decode($matched[2]);
        $file_path = APP_PATH."../static/Upload/test.png";
        file_put_contents($file_path,$img);
        //echo $img;
        //var_dump($matched[2],base64_decode($matched[2]));exit;
     }
    public function order_process_money(){
        $oid = $this->_post('recint_id');
        if(empty($oid)){
            $this->error('订单id为空');
        }
        $where['id'] = $oid;
        $order_info = M('order')->where($where)->find();
        if(empty($order_info)){
            $this->error('订单信息错误');
        }
        //是否存在没还款的申请记录
        $param['member_info.memberid'] = $order_info['memberid'];
        $param['order.order_type'] = 3;
        $param['order_credit.status'] = 0;
        $param['order.id'] = array('neq',$oid);
        $reulsts = M('order')
            ->join('`order_credit` on order_credit.order_id=`order`.id')
            ->join('member_info on member_info.memberid=order.memberid')
            ->field('`order`.`status`,order_credit.status sts')
            ->where($param)
            ->find();
        if(!empty($reulsts)){
            if($reulsts['status']==1 || ($reulsts['status']==2 && $reulsts['sts']!=1)){
                $this->error('有未还款或者未完成的订单信息');
            }
        }
        import('Think.ORG.Util.Credit');
        $obj = new Credit($order_info['memberid']);
        $return = $obj->company2person($oid);
        if(false==$return){
            $this->error($obj->getError());
        }else{
        	//推荐贷款成功
        	import("Think.ORG.Util.RunCommon");
        	RunCommon::runCreditSuccess(['memberid'=>$order_info['memberid'],'order_id'=>$oid]);
            $this->success('打款成功');  
        }
    }
    public function boxInfo(){
        $memberid = $this->_get('memberid','intval', 0);
        $order_id = $this->_get('order_id','intval',0);
        if(empty($memberid) || empty($order_id)){
            $this->error('用户信息错误');
        }
        $where['order.id'] = array('neq',$order_id);
        $where['order.memberid'] = $memberid;
        $where['order_type'] = 3;
        $lists = M('order')->join('order_credit on order.id = order_credit.order_id')
            ->field('order.timeadd,order.status,order_credit.status sts')
            ->where($where)
            ->select();
        $lists = empty($lists)?(array()):($lists);
        $this->assign('lists',$lists);
        $this->display();
    }
    
    /*
     * 车友贷，根据借贷门店绑定
     * */
    private function getStore(){
    	$where['o.order_type'] = 3;
    	$where['o.status'] = 2;
    	$where['order_credit.store_id'] = array('exp','is null');
    	$lists = M('`order` o')
			    	->join('member_info on member_info.memberid=o.memberid')
			    	->join('order_credit on order_credit.order_id=o.id')
			    	->join("(select department city,cer_card from order_upload GROUP BY cer_card) as ou on member_info.certiNumber=ou.cer_card")
			    	->where($where)
			    	->field("ou.city,order_credit.id,substr(ou.city,'1','2') as city_name")
			    	->select();
    	//dump(M('`order` o')->getLastSql());exit;
    	if(!empty($lists)){
    		$citys = array(
    				'21'=>array('南通公司','南通公司系统','南通长安系统','南通长安'),//南通一店
    				'23'=>array('南通二店系统','南通二店长安','南通二店长安系统','南通二店'),//南通二店
    				'60'=>array('南通飞驰长安系统','南通飞驰'),//南通飞驰
    		);
    		$stores = M('store')->where("type=2 and id not in(21,23,60)")->select();
    		foreach($lists as $k=>$v){
    			$store_id = '';
    			foreach($citys as $key=>$val){
    				if(in_array($v['city'],$val)){$store_id = $key;break;}
    			}
    			if(empty($store_id)){
    				$store_id = M('store')->where("type=2 and name like '%{$v['city_name']}%'")->getField('id');
    			}
    			if(!empty($store_id))
    				M('order_credit')->where("id='{$v['id']}'")->save(array('store_id'=>$store_id));	
    		}
    	}
    }

}
?>