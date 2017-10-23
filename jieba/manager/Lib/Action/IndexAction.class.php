<?php
  
/*
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 布局首页
 * @author Nydia
 */
class IndexAction extends CommonAction {
	
	private $_ids = array('admin'=>'1','customer'=>'2','store'=>'3','insurance'=>'25');
	protected function _initialize() {
		//$_SESSION['user']['groupid'] = 3;
	}
    //框架加载
    public function index() {
    	//消息
    	$notice = array();$is_show = $buy = $staff = $borrow = $report = $crediet = $car_insurance = 0;
    	if($this->_ids['customer']==$_SESSION['user']['groupid']){
    		//客服显示
    		$buy = M('order_process')->table("`order` o,order_process p")->where("o.id=p.order_id   and o.order_type='2' and customer_status='0'")->count();
    		$borrow = M('order_process')->table("`order` o,order_process p")->where("o.id=p.order_id   and o.order_type='1' and customer_status='0'")->count();
    		$message = $this->getCustomerMessage();
    		$notice['message'] = array('title'=>"客户消息(".($message?$message:'0').")",'href'=>'/#205/206');
            $crediet = M('order_process')->table("`order` o,order_process p")->where("o.id=p.order_id   and o.order_type='3' and o.status=1 and customer_status in(0,6)")->count();
            $staff = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where("o.order_type='3' and s.status=0 and o.status=1 and p.customer_status in(0,6)")
            ->count();
            $report = M('gold_report')
            ->join("gold_order on gold_order.id = gold_report.order_id")
            ->where("gold_report.status = 1")
            ->count();
            $crediet = $crediet-$staff;
    		$is_show = 1;
    	}
    	if($this->_ids['store']==$_SESSION['user']['groupid']){
    		//门店显示
    		$store_id = "o.id in(select o.id from `order` o,order_process p,store s where s.manager='{$_SESSION['user']['username']}' and s.id=p.store_id and  p.order_id=o.id)";
    		$buy = M('order_process')->table("`order` o,order_process p")->where("o.id=p.order_id   and o.order_type='2' and customer_status='1' and store_status in(0,1)  and  {$store_id}")->count();
    		$borrow = M('order_process')->table("`order` o,order_process p")->where("o.id=p.order_id   and o.order_type='1' and customer_status='1'  and store_status in(0,1) and  {$store_id}")->count();
    		$is_show = 1;
    	}
    	
    	$notice['buy'] =array('title'=>"车租宝(".($buy?$buy:'0').")",'href'=>'/#171/172?status=1');
    	$notice['borrow'] = array('title'=>"车贷宝(".($borrow?$borrow:'0').")",'href'=>'/#190/179?status=1');
        $notice['crediet'] = array('title'=>"车友贷(".($crediet?$crediet:'0').")",'href'=>'/#257/261');
        $notice['staff'] = array('title'=>"员工车友贷(".($staff?$staff:'0').")",'href'=>'/#257/299');
        $notice['report'] = array('title'=>"甩单举报(".($report?$report:'0').")",'href'=>'/#327/343');

        //车辆保险
        if ($this->_ids['insurance'] == $_SESSION['user']['groupid'] || ($this->_ids['customer']==$_SESSION['user']['groupid'])) {
            $handle_status['status'] = 1;
            //客服
            if ($this->_ids['customer'] == $_SESSION['user']['groupid']) {
                $apply_status = array_merge($handle_status,['status_process'=>1]);
                $receive_status = array_merge($handle_status,['status_process'=>3]);
            } else {
                unset($notice);
                $apply_status = array_merge($handle_status,['status_process'=>2]);
                $receive_status = array_merge($handle_status,['status_process'=>4]);
            }
            $apply_insurance = M("insurance_order")
                ->where($apply_status)
                ->count();
            $receive_insurance = M("insurance_order")
                ->where($receive_status)
                ->count();
            $car_insurance = $apply_insurance + $receive_insurance;
            $url = $apply_insurance?"/#363/364":"/#363/365";
            $notice['car_insurance'] = ['title'=>"车险订单(".($car_insurance?$car_insurance:'0').")",'href'=>$url];
            $is_show = 1;
        }
    	$this->assign('notice',$notice);
    	$this->assign('notice_total',intval(($buy+$borrow+$message+$crediet+$staff+$report+$car_insurance)));
    	$this->assign('is_show',$is_show);
    	if($_REQUEST['is_ajax']){
    		$this->ajaxReturn(array('notice'=>$notice,'notice_total'=>intval(($buy+$borrow+$message+$crediet+$staff+$report+$car_insurance)),'is_show'=>$is_show),'json');
    	}
        $this->display();
    }
    //获取客户消息
    private function getCustomerMessage(){
    	$total = 0;
    	$today = strtotime(date('Y-m-d'),time());
    	$where['fro_to_id'] = array('like',$this->user['id'].'_%');
    	$where['type'] = 0;
    	$lists = M('welive_msg')->where($where)->field('fro_to_id,max(created) max_created')->group('fro_to_id')->select();
    	foreach($lists as $list){
    		$key = 'msg'.$list['fro_to_id'];
    		list($list['serverid'],$list['userid']) = explode('_',$list['fro_to_id']);
    		$lits = M('member')->field('id,username,mobile')->where(array('id'=>$list['userid']))->find();
    		if($this->redis)
    			$lits['num'] = $this->redis->hget($key,'new');
    		$lits['max_created'] = date('Y-m-d H:i:s',$list['max_created']);
    		$messages[] = $lits;
    		$total+=$lits['num'];
    	}
    	return $total;
    }

    //系统统计
    public function system() {
        $today = date('Y-m-d');
        $stat_data = $this->get_statistics_data();
        
        $this->assign('data', $stat_data);
        $params = array(
        		'timebegin' => $this->_post('timebegin') ? $this->_post('timebegin') : date('Y-m-d 00:00:00', time() - 3600 * 24 * 8),
        		'timeend' => $this->_post('timeend') ? $this->_post('timeend') : date('Y-m-d 23:59:59')
        );
        $this->assign('params', $params);
        $this->display();
    }

    public function rolling_data() {
        $arr = array();
        $stat_data = $this->get_statistics_data();
        $this->ajaxReturn($arr, 'ok', 1);
    }

    private function get_sum_tender(){
        $yesterday = date('Y-m-d',strtotime('-1day'));
        $history_tender = M('statistics_by_day')->field('sum_tender')->where("day='{$yesterday}'")->find();
        $today_tender = M('loan_tender')->where('status = 1 and left(timeadd,10) = "'.date('Y-m-d').'"')->sum('money');
        $total_tender = $history_tender['sum_tender'] + $today_tender;
        return $total_tender;
    }

    public function get_stat_data_login() {
        $timebegin = $this->date_format($_POST['begin']);
        $timeend = $this->date_format($_POST['end']);
        $SQL = "SELECT SUBSTRING(timeadd,6,5) AS day,count(id) AS num FROM member WHERE timeadd BETWEEN '{$timebegin}' AND '{$timeend}' GROUP BY SUBSTRING(timeadd,3,8)";
        $result = M()->query($SQL);
        $day = $num = array();
        foreach ($result as $var) {
            $day[] = $var['day'];
            $num[] = $var['num'];
        }
        $this->ajaxReturn(array('day' => $day, 'num' => $num), '查询成功', true);
    }

    public function get_stat_data_recharge() {
        $timebegin = $this->date_format($_POST['begin']);
        $timeend = $this->date_format($_POST['end']);
        $SQL = "SELECT SUBSTRING(timeadd,6,5) AS day,SUM(amount) AS num FROM cash_in WHERE `status` = 2 AND timeadd BETWEEN '{$timebegin}' AND '{$timeend}' ";
        $SQL.= "GROUP BY SUBSTRING(timeadd,3,8)";
        $result = M()->query($SQL);
        $day = $num = array();
        foreach ($result as $var) {
            $day[] = $var['day'];
            $num[] = $var['num'];
        }
        $this->ajaxReturn(array('day' => $day, 'num' => $num), '查询成功', true);
    }
    
	//信用贷信息
    public function get_stat_data_credit() {
        $timebegin = $this->date_format($_POST['begin']);
        $timeend = $this->date_format($_POST['end']);
        $SQL = "SELECT SUBSTRING(c.pay_time,6,5) AS day,SUM(loanmoney) AS num FROM `order` o,order_credit c WHERE o.id=c.order_id and o.order_type = 3 and o.status=2 AND c.pay_time BETWEEN '{$timebegin}' AND '{$timeend}' ";
        $SQL.= "GROUP BY SUBSTRING(c.pay_time,3,8)";
        $result = M()->query($SQL);
        
        $total_SQL = "SELECT SUBSTRING(c.pay_time,6,5) AS day,SUM(loanmoney) AS num FROM `order` o,order_credit c WHERE o.id=c.order_id and o.order_type = 3 and o.status=2 AND c.pay_time BETWEEN '{$timebegin}' AND '{$timeend}' ";
        $total_result = M()->query($total_SQL);
        
        $day = $num = array();
        foreach ($result as $var) {
            $day[] = $var['day'];
            $num[] = $var['num'];
        }
       
        $this->ajaxReturn(array('day' => $day, 'num' => $num,'credit_total'=>$total_result[0]['num']), '查询成功', true);
    }

    public function get_stat_data_cashout() {
        $timebegin = $this->date_format($_POST['begin']);
        $timeend = $this->date_format($_POST['end']);
        $SQL = "SELECT SUBSTRING(timeadd,6,5) AS day,SUM(amount) AS num FROM cash_out WHERE `status` = 3 AND timeadd BETWEEN '{$timebegin}' AND '{$timeend}' ";
        $SQL.= "GROUP BY SUBSTRING(timeadd,3,8)";
        $result = M()->query($SQL);
        $day = $num = array();
        foreach ($result as $var) {
            $day[] = $var['day'];
            $num[] = $var['num'];
        }
        $this->ajaxReturn(array('day' => $day, 'num' => $num), '查询成功', true);
    }

    private function get_statistics_data() {
        $today = date('Y-m-d', time());
        $tonight = $today." 23:59:59";
        $yesterday = date('Y-m-d', time() - 24 * 3600);
        
        $where = array();
        $where['time_between'] = array('between',array($today,$tonight));
        
        //今日注册人数
        $today_reg = M('member')->where(array('timeadd'=>$where['time_between']))->count();
        //总注册人数
        $total_reg = M('member')->count();
        
        //车贷宝--申请中
        $borrow_where = array();
        $borrow_where['order_type'] = 1;
        $borrow_where['status'] = 1;
        
        $total_borrow = M('order')->where($borrow_where)->count();
        $borrow_where['timeadd'] = $where['time_between'];
        $today_borrow = M('order')->where($borrow_where)->count();
        //车贷宝--成单
        $borrow_where['status'] = 2;unset($borrow_where['timeadd']);
        $total_borrow_allow = M('order')->where($borrow_where)->count();
        $borrow_where['timeadd'] = $where['time_between'];
        $today_borrow_allow = M('order')->where($borrow_where)->count();
        
        //信用贷--申请中
        $credit_where = array();
        $credit_where['order_type'] = 3;
        //$credit_where['status'] = 1;
        
        $total_credit = M('order')->where($credit_where)->count();
        $credit_where['timeadd'] = $where['time_between'];
        $today_credit = M('order')->where($credit_where)->count();
        //今日待处理
        $credit_where['status'] = 1;
        $today_credit_dai = M('order')->where($credit_where)->count();
        //今日拒单量
        $today_credit_ju = M('`order` o,order_process p')->where("o.status=3 and o.order_type=3 and o.id=p.order_id and  p.customer_time between '{$today}' and '{$tonight}'")->count();
        
        
        
        //信用贷--成单
        $credit_where['status'] = 2;unset($credit_where['timeadd']);
        $total_credit_allow = M('order')->where($credit_where)->count();
        $today_credit_allow = M('`order` o,order_credit c')->where("o.status=2 and o.order_type=3  and o.id=c.order_id and  c.pay_time between '{$today}' and '{$tonight}'")->count();
        
        //信用贷--放款
        $credit_where['status'] = 2;
        $credit_where['timeadd'] = $where['time_between'];
        $today_credit_fang = M('`order` o,order_credit c')->where("o.status=2 and o.id=c.order_id and  c.pay_time between '{$today}' and '{$tonight}'")->sum('loanmoney');
        unset($credit_where['timeadd']);
        $total_credit_fang = M('order')->where($credit_where)->sum('loanmoney');
        
        //利润
        //$sql = "select (sum(c.fee)+sum(c.plat_fee)+sum(c.ratemoney)-sum('2')-sum(o.loanmoney)*0.002+sum(if(c.status=1,c.late_fee,if(c.back_time>NOW(),0,round(ceil((unix_timestamp(NOW())-unix_timestamp(c.back_time))/86400)*0.01*o.loanmoney,2))))) as li
        //        from `order` o,order_credit c where o.id=c.order_id and o.status=2";
        $sql = "select (sum(c.fee)+sum(c.plat_fee)+sum(c.ratemoney)+sum(if(c.status=1,c.late_fee,if(c.back_time>NOW(),0,round(ceil((unix_timestamp(NOW())-unix_timestamp(c.back_time))/86400)*0.01*o.loanmoney,2))))) as li
                from `order` o,order_credit c where o.id=c.order_id and o.status=2";
        $li_arr = M('`order` o,order_credit c')->query($sql);
        $li = $li_arr[0]['li'];

        //员工贷--申请中
        $staff_where = array();
        $staff_where['o.order_type'] = 3;//员工车友贷订单
        $staff_where['s.status'] = 0;//员工在职
        $total_staff = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where($staff_where)
            ->count();
        $staff_where['o.timeadd'] = $where['time_between'];
        $today_staff = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where($staff_where)
            ->count();
        
        
        //今日待处理
        $staff_where['o.status'] = 1;
        $today_staff_dai = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where($staff_where)
            ->count();
        //今日拒单量
         $today_staff_ju = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where("o.status=3 and  p.customer_time between '{$today}' and '{$tonight}'")
            ->count();
        
        
        //员工贷--成单
        $staff_where['o.status'] = 2;unset($staff_where['o.timeadd']);
        $total_staff_allow = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where($staff_where)
            ->count();
        $today_staff_allow = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id") 
            ->join('order_credit c on o.id=c.order_id')
            ->where("o.status=2  and  c.pay_time between '{$today}' and '{$tonight}'")
            ->count();
        
        //员工贷--放款
        $staff_where['o.status'] = 2;
        $staff_where['o.timeadd'] = $where['time_between'];
        $today_staff_fang = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id") 
            ->join('order_credit c on o.id=c.order_id')
            ->where("o.status=2 and o.id=c.order_id and  c.pay_time between '{$today}' and '{$tonight}'")
            ->sum('o.loanmoney');
        unset($staff_where['o.timeadd']);
        $total_staff_fang = M('staff s')
            ->join("member_info mi on mi.certiNumber = s.certiNumber")
            ->join("`order` o on o.memberid = mi.memberid")
            ->join("order_process p on o.id=p.order_id")
            ->where($staff_where)
            ->sum('o.loanmoney');
        //车贷宝--门店(分发数、待处理、成单、拒单)
        $store_info = M('store')->where("type=2 and status=1")->order("sort desc")->select();
        foreach($store_info as &$v){
        	$v['store_fenfa_num'] = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  (o.timeadd between '{$today}' and '{$today} 23:59:59') and store_id='{$v['id']}'")->count();
        	$v['store_deing_num'] = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  (o.timeadd between '{$today}' and '{$today} 23:59:59') and p.store_status in(0,1) and store_id='{$v['id']}'")->count();
        	$v['store_allow_num'] = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  (o.timeadd between '{$today}' and '{$today} 23:59:59') and p.store_status=2 and store_id='{$v['id']}'")->count();
        	$v['store_desgn_num'] = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  (o.timeadd between '{$today}' and '{$today} 23:59:59') and p.store_status in(3,4) and store_id='{$v['id']}'")->count();
        	
        }
        //$borrow_store = array();
//         $store_fenfa_num = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  o.timeadd between '{$today}' and '{$today} 23:59:59'")->count();
//         $store_deing_num = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  o.timeadd between '{$today}' and '{$today} 23:59:59' and p.store_status=0")->count();
//         $store_allow_num = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  o.timeadd between '{$today}' and '{$today} 23:59:59' and p.store_status=2")->count();
//         $store_desgn_num = M('order')->table("`order` o,order_process p")->where("o.id=p.order_id and o.order_type=1 and p.customer_status=1 and  o.timeadd between '{$today}' and '{$today} 23:59:59' and p.store_status in(1,3,4)")->count();
        
        $total_credit = $total_credit - $total_staff ;
        $today_credit = $today_credit - $today_staff;
        $total_credit_allow = $total_credit_allow - $total_staff_allow;
        $today_credit_allow = $today_credit_allow - $today_staff_allow;
        $today_credit_dai = $today_credit_dai - $today_staff_dai;
        $today_credit_ju = $today_credit_ju - $today_staff_ju;
        $today_credit_fang= $today_credit_fang - $today_staff_fang;
        $total_credit_fang = $total_credit_fang - $total_staff_fang;
        $return = array(
            'today_reg'             => $today_reg, //今日注册人数
            'total_reg'             => $total_reg, //注册总人数
            'total_borrow'  		=> $total_borrow, //车贷宝-申请总数
            'today_borrow' 			=> $today_borrow,//车贷宝-今日申请
            'total_borrow_allow'    => $total_borrow_allow,//车贷宝-成单总数
            'today_borrow_allow'   	=> $today_borrow_allow,//车贷宝-今日成单
            
            'total_credit'  		=> $total_credit, //信用贷-申请总数
            'today_credit' 			=> $today_credit,//信用贷-今日申请
            'total_credit_allow'    => $total_credit_allow,//信用贷-成单总数
            'today_credit_allow'   	=> $today_credit_allow,//信用贷-今日成单
            'today_credit_dai'		=> $today_credit_dai,//信用贷-今日待处理
            'today_credit_ju'		=> $today_credit_ju,//信用贷-今日拒单
            'today_credit_fang'		=> $today_credit_fang,//信用贷-今日放款
            'total_credit_fang'		=> $total_credit_fang,//信用贷-总共放款

            'total_staff'          => $total_staff, //员工贷-申请总数
            'today_staff'          => $today_staff,//员工贷-今日申请
            'total_staff_allow'    => $total_staff_allow,//员工贷-成单总数
            'today_staff_allow'    => $today_staff_allow,//员工贷-今日成单
            'today_staff_dai'      => $today_staff_dai,//员工贷-今日待处理
            'today_staff_ju'       => $today_staff_ju,//员工贷-今日拒单
            'today_staff_fang'     => $today_staff_fang,//员工贷-今日放款
            'total_staff_fang'     => $total_staff_fang,//员工贷-总共放款
            
            'store_fenfa_num'		=>	$store_fenfa_num,//车贷宝--门店分发数
            'store_deing_num'		=>	$store_deing_num,//车贷宝--门店待处理
        	'store_allow_num'		=>	$store_allow_num,//车贷宝--门店成单
        	'store_desgn_num'		=>	$store_desgn_num,//车贷宝--门店拒单
        	'store_info'			=>$store_info,
            'total_li'              =>round($li,2)
            );
        return $return;
    }

    private function getvisitors() {
        $contents = file_get_contents('http://online.cnzz.com/online/online_v3.php?id=5867982&h=z9.cnzz.com&on=1&s=line&r=' . rand(100, 999));
        preg_match_all('/\[(\d+)\]/', $contents, $matches);
        $yestodaytime = date('Y-m-d 00:00:00', time() - 3600 * 24);
        $yestoday = M()->table('visitor_num')->field('num')->where("addtime='" . $yestodaytime . "'")->find();
        $month = M()->table('visitor_num')->field('sum(num) as total')->where("left(addtime,7)='" . date('Y-m') . "'")->find();
        $total = M()->table('visitor_num')->field('sum(num) as total')->find();
        return array('today' => $matches[1][0], 'online' => $matches[1][9], 'yestoday' => $yestoday['num'], 'month' => $month['total'], 'total' => $total['total']);
    }

    public function date_format($time) {
        $arr = explode('-', $time);
        $return = $arr[0];
        if (strlen($arr[1]) == 1) {
            $return .= '-0' . $arr[1];
        } else {
            $return .= '-' . $arr[1];
        }
        if (strlen($arr[2]) == 1) {
            $return .= '-0' . $arr[2];
        } else {
            $return .= '-' . $arr[2];
        }
        return $return;
    }

    public function basic(){
        $this->assign('system', $this->systemConfig());
        $this->display();
    }

    public function basicsubmit(){

        $title = $this->_post('title');
        $keywords = $this->_post('keywords');
        $description = $this->_post('description');
        $proportion = $this->_post('proportion');
        if(!$proportion){
            $this->error('积分比例不能为空');
        }
        $data = array();
        $data['title'] = $title;
        $data['keywords'] = $keywords;
        $data['description'] = $description;
        $data['proportion'] = $proportion;
        $F = M('system')->where('id=1')->save($data);
        if($F){
            S('system',$data);
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }

    }

    public function send_sms(){
        $this->display();
    }

    public function send_sms_submit(){

        echo "<pre>";
        $content = $this->_post('content','trim');
        $content .= "【吉祥果】";
        $p = 0;
        $n = 10000;
        echo $content;
        echo "\r\n";
        while (TRUE) {
            $l = $p*$n;
            $member = M('member')->field('mobile')->limit("{$l},{$n}")->select();
            echo M()->getLastSql();
            if(!$member){
                break;
            }
            $mobile = array();
            foreach($member as $k=>$v){
                $mobile[] = $v['mobile'];
            }
            $mobile = implode(",",$mobile);

            $post_data = array();
            $post_data['userid'] = 872;
            $post_data['account'] = 'TDAX';
            $post_data['password'] = '123456';
            $post_data['content'] = urlencode($content); //短信内容需要用urlencode编码下
            $post_data['mobile'] = $mobile;
            $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
//            $url='http://218.244.136.70:8888/sms.aspx?action=send';       //原来短信地址
            $url='http://115.29.242.32:8888/sms.aspx?action=send';
            $o='';
            foreach ($post_data as $k=>$v)
            {
                $o.="$k=".$v.'&';
            }
            $post_data=substr($o,0,-1);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
            $result = curl_exec($ch);
            curl_close($ch);
            $xml = (array)simplexml_load_string($result);
            print_r($xml);
            echo "\r\n";
            $p++;
        }
        echo "</pre>";


    }

}
