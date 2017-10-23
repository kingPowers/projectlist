<?php

/**
 * Description of CommonAction
 * 布局首页
 * @author Nydia
 */
class IndexAction extends CommonAction {
	
	private $_ids = array('admin'=>'1','customer'=>'2');
	protected function _initialize() {
		//$_SESSION['user']['groupid'] = 3;
	}
    //框架加载
    public function index() {
    	//消息
    	$notice = array();$order = 0 ;
    	if($this->_ids['customer']==$_SESSION['user']['groupid']){
    		//客服显示
    		//$message = $this->getCustomerMessage();
            $order = M('`order`')->where("status=1")->count();
    		$is_show = 1;
    	}
        $notice['order'] = array('title'=>"订单申请(".($order?$order:'0').")",'href'=>'/#25/26');
    	$this->assign('notice',$notice);
    	$this->assign('notice_total',intval($order));
    	$this->assign('is_show',$is_show);
    	if($_REQUEST['is_ajax']){
    		$this->ajaxReturn(array('notice'=>$notice,'notice_total'=>intval(($order)),'is_show'=>$is_show),'json');
    	}
        $this->display();
    }
    //系统统计
    public function system() {
        $today = date('Y-m-d');
        $stat_data = $this->get_statistics_data();
        
        $this->assign('data', $stat_data);
        $params = array(
        		'timebegin' => $this->_post('timebegin') ? $this->_post('timebegin') : date('Y-m-d 00:00:00', time() - 3600 * 24 * 8),
        		'timeend' => $this->_post('timeend') ? $this->_post('timeend') : date('Y-m-d H:i:s')
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
        $return = array(
            'today_reg'             => $today_reg, //今日注册人数
            'total_reg'             => $total_reg, 
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
