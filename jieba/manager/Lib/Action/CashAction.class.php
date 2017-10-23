<?php
  
/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 资金管理
 * @author Nydia
 */
class CashAction extends CommonAction {

    private $_M = array();
    private $_cashstatus = array(0 => '待付款', 1 => '提交成功', 2 => '付款成功', 3 => '付款失败');
    private $_cashoutstatus = array(0=>'未提交',1=>'提交成功','2'=>'提现到账','3'=>'提现失败');
    private $_cashoutkeys = array('sn' => '订单号', 'name' => '姓名', 'username' => '会员名',  'cellphone' => '手机号');
    private $_cashinkeys = array(  'name' => '姓名', 'username' => '会员名', 'cellphone' => '手机号');
    private $_repaystatus = array(0 => '全部' , 1 => '已还' , 2 => '未还','3'=>'还款失败');

    //初始化
    protected function _initialize() {
        $this->_M = M('CashIn');
    }

    //充值首页
    public function cashIn(){
        $params = '';
 //     $map = array('ci.id' => array('gt', 0));
        $map['ci.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('start_time', 'trim');
        $endtime = $this->_get('end_time', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        if($status != ''){
            $map['ci.status'] = $status;
            $params .= "/status/{$status}";
        }
        if(empty($starttime) && !empty($endtime)){
            $map['ci.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= "/end_time/{$endtime}";
        }else if(!empty($starttime) && empty($endtime)){
            $map['ci.timeadd'] = array('egt',$starttime);
            $params .= "/start_time/{$starttime}";
        }else if(!empty($starttime) && !empty($endtime)){
            $map['ci.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= "/start_time/{$starttime}";
            $params .= "/end_time/{$endtime}";
        }
        $count = M('cash_in ci')
            ->join('member m on m.id = ci.memberid')
            ->join('member_info mi on mi.memberid=ci.memberid')
            ->where($map)->count();
        $where = $map;
        $where['ci.status'] = array('eq',2);
        $sumMoney = M('cash_in ci')
            ->join('member m on m.id = ci.memberid')
            ->join('member_info mi on mi.memberid=ci.memberid')
            ->where($where)->field('sum(ci.amount) as money')->find();
        $total['amount'] = M('cash_in  ci')
            ->join('member m on m.id = ci.memberid')
            ->join('member_info mi on mi.memberid=ci.memberid')
            ->where($map)
            ->count('distinct(ci.memberid)');
        $total['sumMoney'] = sprintf('%.2f', $sumMoney['money']);
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list = M('cash_in  ci')
                ->join('member m on m.id = ci.memberid')
                ->join('member_info mi on mi.memberid=ci.memberid')->where($map)
                ->field('ci.id,ci.insn,ci.memberid,ci.amount,ci.extend,ci.timeadd,ci.lasttime,ci.arrivaltime,ci.status,ci.remark,m.username,m.mobile,mi.names')
                ->order('ci.id DESC')->limit($limit)->select();
            $this->assign('list', $list);
        }
        $this->setPage("/Cash/cashIn{$params}/p/*.html");
        $this->assign('status', $this->_cashstatus);
        $this->assign('keys', $this->_cashinkeys);
        $this->assign('totalNum', $total);
        $this->display();
    }

    //充值订单详情
    public function boxOrderInfo() {
        $id = $this->_get('id', 'intval', 0);
        if ($id) {
            $order = $this->_M->where("id={$id}")->find();
            $member = M('Member m')
                ->join('member_info mi on mi.memberid = m.id')
                ->where("m.id={$order['memberid']}")
                ->field('m.username,m.mobile,m.mobile_location,m.timeadd,mi.names')
                ->find();
        }
        $this->assign('member', $member);
        $this->display('box_order_info');//详情页面
    }

    //提现首页
    public function cashOut() {
        $params = '';
//      $map = array('co.id' => array('gt', 0));
        $map['co.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('start_time','trim');
        $endtime = $this-> _get('end_time','trim');
        if (!empty($value)) {
            switch($key){
                case 'sn':
                    $map['co.outsn'] = $value;
                    break;
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        if($status != ''){
            $map['co.status'] = $status;
            $params .= "/status/{$status}";
        }
        if(empty($starttime) && !empty($endtime)){
            $map['co.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= "/end_time/{$endtime}";
        }else if(!empty($starttime) && empty($endtime)){
            $map['co.timeadd'] = array('egt',$starttime);
            $params .= "/start_time/{$starttime}";
        }else if(!empty($starttime) && !empty($endtime)){
            $map['co.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= "/start_time/{$starttime}";
            $params .= "/end_time/{$endtime}";
        }
        $count = M('cash_out co')
            ->join('member_info mi on co.memberid=mi.memberid')
            ->join('member m on co.memberid = m.id')
            ->where($map)->count();
        $where = $map;
        $where['co.status'] = array('neq', 2);
        $sumMoney = M('cash_out co')
            ->join('member_info mi on co.memberid=mi.memberid')
            ->join('member m on co.memberid = m.id')
            ->where($where)->field('sum(co.amount+co.fee) as money')->find();
        $total['sumMoney'] = sprintf('%.2f', $sumMoney['money']);
        $total['amount'] = M('cash_out co')
            ->join('member_info mi on co.memberid=mi.memberid')
            ->join('member m on co.memberid = m.id')
            ->where($map)
            ->count('distinct(co.memberid)');
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list = M('cash_out co')
                ->join('member_info mi on co.memberid=mi.memberid')
                ->join('member m on co.memberid = m.id')
                ->where($map)
                ->field('co.id,co.outsn,co.memberid,co.total,co.amount,co.fee,co.extend,co.remark,co.timeadd,co.lasttime,co.canceltime,co.finishtime,co.status,m.username,m.mobile,mi.names')
                ->order('co.id DESC')->limit($limit)->select();
            $this->assign('list', $list);
        }
        $this->setPage("/Cash/cashOut{$params}/p/*.html");
        $this->assign('status', $this->_cashoutstatus);
        $this->assign('keys', $this->_cashoutkeys);
        $this->assign('totalNum', $total);
        $this->display();
    }


    //提现订单详情
    public function boxOutOrderInfo() {
        $id = $this->_get('id', 'intval', 0);
        if ($id) {
            $order = M('CashOut')->where("id={$id}")->find();
            $member = M('Member')
                ->where("id={$order['memberid']}")
                ->field('username,mobile,mobile_location,timeadd')->find();
            $memberinfo = M('MemberInfo')
                ->where("memberid={$order['memberid']}")
                ->find();
        }
        $this->assign('member', $member);
        $this->assign('memberinfo', $memberinfo);
        $this->assign('moneyList', unserialize($order['extend']));
        $this->assign('order', $order);
        $this->display('box_out_order_info');
    }

    //处理提现订单
    public function manageCashOutOrder() {
        $orderIds = $this->_post('id');
        $status = $this->_post('status');
        if (empty($orderIds) || empty($status)) {
            $this->error('获取参数失败');
        }
        if (!in_array($status, array(1,2,3,5))){
            $this->error('非法操作');
        }
        if (!is_array($orderIds)){
            $orderIds = array($orderIds);
        }
        $fails = array();

        foreach($orderIds as $orderId){
            $order = M('CashOut co')
                ->join('member m on co.memberid=m.id')
                ->join('member_info mi on m.id=mi.memberid')
                ->field('co.*,m.mobile as memmobile,mi.bindBankNum,m.sinaid,mi.bindSinaCardId')->where(array('co.id' => $orderId))->find();
            if (in_array($status, array(1, 3 , 5))) {
                $pass = false;
                $data = array('status' => $status);
                if ($status == 1 && $order['status'] == 0) {
                    $pass = true;
                    $data['submittime'] = date('Y-m-d H:i:s');
                }
                if ($status == 5 && ($order['status'] == 1 || $order['status'] == 5)) {
                    $pass = true;
                    $data['finishtime'] = date('Y-m-d H:i:s');
                }
                if ($status == 3 && ($order['status'] == 1 || $order['status'] == 5)) {
                    $pass = true;
                    $data['finishtime'] = date('Y-m-d H:i:s');
                }

                if ($pass) {
                    $trans = false;
                    if (!D('Common')->inTrans()) {
                        $trans = true;
                        D('Common')->startTrans();
                    }
                    try {
                        $F = M('CashOut')->where(array('id' => $order['id']))->save($data);
                        if ($F == false) {
                            throw new Exception('修改订单状态失败');
                        }
                        $map = array('memberid' => $order['memberid'], 'codeid' => 2, 'relationsn' => $order['outsn']);

                        $F = M('MoneyDetail')->where($map)->save(array('status' => $status));
                        if ($F == false) {
                            throw new Exception('修改流水订单状态失败');
                        }
                        if ($status == 5) {

                            $chdata = array();
                            $chdata['identity_id']  = $order['sinaid'];//发送给新浪的商户号
                            $chdata['out_trade_no'] = $order['outsn'];//提现单号
                            $chdata['amount']       = $order['amount'];//申请金额
                            //user_fee
                            $chdata['user_fee']     = $order['fee'];//申请手续费
                            $chdata['card_id']      = $order['bindSinaCardId'];//绑定新浪银行卡ID
                            $chdata['notify_url']   = "http://".WWW_DOMAIN_ROOT."/pay/cashout";
                            //request获取
//                            file_put_contents("cashout_submit.log",print_r($chdata,1));
                            $F = D('Sinaport')->create_hosting_withdraw($chdata);
//                            file_put_contents("cashout_submit.log",print_r($F,1));
                            if(!$F['status']){
                                throw new Exception($F['msg']);
                            }else{
                                $G = M('CashOut')->where("id={$order['id']}")->setField('sendsian',1);
                            }

                        }

                        if ($trans) {
                            D('Common')->commit();
                        }
                        //$this->success('操作成功');
                    } catch (Exception $ex) {
                        if ($trans) {
                            D('Common')->rollback();
                        }
                        $fails[]=array('id'=>$orderId,'error'=>$ex->getMessage());
                        //$this->error($ex->getMessage());
                    }
                }
            }

            //status的第二种可能拒绝状态
            if ($status == 2 && ($status > $order['status'] ||  $order['status']==5)) {
                import('Think.ORG.Util.Money');
                if (!Money::cashOutCancel($order)) {
                    $fails[]=array('id'=>$orderId,'error'=>Money::getError());
                    //$this->error(Money::getError());
                }
                //$this->success('操作成功');
            }
        }
        if (empty($fails)){
            $this->success('操作成功');
        }else{
            $this->error('操作失败');
        }
    }
    public function export_cashin(){
        $this->export_data('cashin');
    }

    public function export_cashout(){
        $this->export_data('cashout');
    }

    public function export_closing(){
        $this->export_data('closing');
    }

    public function export_tender(){
        $this->export_data('tender');
    }
    public function export_repayList(){
        $this->export_data('repayList');
    }
    public function export_loan_gone_list(){
        $this->export_data('loanGoneList');
    }
    /**
     * 导出还款详情excel
     * 2015/12/17 zh
     */
    public function repayment_data($type=""){
        $key = $this->_get('k','trim');
        $value = $this->_get('v','trim');
        $issuesn = $this->_get('issuesn');
        $status = $this->_get('status');
//        $where = " la.issuesn = '".$issuesn."' ";
        $where['la.issuesn'] = $issuesn;
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
        if($status){
            $isrepayment = $status == 1 ? 1 : 0 ;
            $where['la.status'] = $isrepayment;
        }

        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();

        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $result = array();
        $cols = array(
            array('title','标题'),
            array('username','投资人'),
            array('mobile','投资人手机'),
            array('names','投资人姓名'),
            array('tender_money','投资金额(元'),
            array('money','还款金额(元)'),
            array('tendermoney','还款本金(元)'),
            array('ratemoney','还款利息(元)'),
            array('status','还款状态'),
            array('repaytime','还款时间')
        );
        $status = array('未还款', '已还款');
        if(!empty($issuesn)){
            $result = M('loan_allot la')
                ->join('loan_issue li on la.issuesn = li.issuesn')
                ->join('loan_tender lt on la.tendersn = lt.tendersn')
                ->join('loan l on la.loansn = l.loansn')
                ->join('member m on la.memberid = m.id')
                ->join('member_info mi on mi.memberid = m.id')
                ->field('l.title,l.loansn,m.username,m.mobile,mi.names,la.status,lt.money as tender_money,(la.tendermoney + la.ratemoney) as money,la.tendermoney,la.ratemoney,IF(li.isrepayment = 1,li.repaymenttime,li.endtime) repaytime')
                ->where($where)->order('la.status desc,la.id desc,li.endtime asc,li.repaymenttime asc')->select();
        }
        foreach($cols as $k => $v){
            $PHPExcel->getActiveSheet()->setCellValue($word[$k].'1',$cols[$k][1]);
        }
        if($result){
            $i = 2;
            $cols_count = count($cols);
            foreach($result as &$val){
                $val['status'] = $status[$val['status']];
//                $val['repaytime'] = substr($val['repaytime'],0,10);
                for($j=0;$j<$cols_count;$j++){
                    $PHPExcel->getActiveSheet()->setCellValueExplicit($word[$j].$i,$val[$cols[$j][0]],PHPExcel_Cell_DataType::TYPE_STRING);
                    $PHPExcel->getActiveSheet()->getStyle($word[$j].$i)->getNumberFormat()->setFormatCode("@");
                }
                $i++;
            }
        }

        $outputFileName = '还款导出'.date('YmdHis').'.xls';
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

    //导出数据到EXCEL
    private function export_data($type=""){
        $export_type = array('cashin'=>'充值','cashout'=>'提现','closing'=>'结算','tender'=>'投资','repayList'=>'还款列表','loanGoneList'=>'流标列表');
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();
        $function = 'export_data_'.$type;
        $result = $this->$function();
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

        $outputFileName = $export_type[$type].date('YmdHis').'数据.xls';
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


    /**
     * @return array
     * 导出充值列表数据
     *
     */
    public function export_data_cashin(){
//      $map = array('ci.id' => array('gt', 0));
        $map['ci.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('start_time', 'trim');
        $endtime = $this->_get('end_time', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
        }
        if($status != ''){
            $map['ci.status'] = $status;
        }
        if(empty($starttime) && !empty($endtime)){
            $map['ci.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        }else if(!empty($starttime) && empty($endtime)){
            $map['ci.timeadd'] = array('egt',$starttime);
        }else if(!empty($starttime) && !empty($endtime)){
            $map['ci.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        $arr_status = array('代付款', '提交成功', '付款成功','付款失败');
        $data = M("cash_in ci")
            ->join('member_info mi on ci.memberid=mi.memberid')
            ->join('member m on m.id = ci.memberid')
            ->field('m.username,m.mobile,mi.names,ci.id,ci.insn,ci.amount,ci.extend,ci.timeadd,ci.arrivaltime,ci.status')
            ->where($map)->order('ci.id desc')->select();
        foreach($data as $key=>$value){
            $data[$key]['status'] = $arr_status[$value['status']];
        }

        $cols = array(
            array('username','会员名'),
            array('names','姓名'),
            array('mobile','手机号'),
            array('insn','订单号'),
            array('amount','充值金额/元'),
            array('timeadd','创建时间'),
            array('arrivaltime','到账时间'),
            array('status','状态'),
        );

        return array('data'=>$data,'cols'=>$cols);
    }
    /**
     * @return array
     * 导出提现列表数据
     *
     */
    public function export_data_cashout(){
//        $map = array('co.id' => array('gt', 0));
        $map['co.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('start_time','trim');
        $endtime = $this-> _get('end_time','trim');
        $co = M('cash_out co');
        if (!empty($value)) {
            switch($key){
                case 'sn':
                    $map['co.outsn'] = $value;
                    break;
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
        }
        if($status != ''){
            $map['co.status'] = $status;
        }
        if(empty($starttime) && !empty($endtime)){
            $map['co.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        }else if(!empty($starttime) && empty($endtime)){
            $map['co.timeadd'] = array('egt',$starttime);
        }else if(!empty($starttime) && !empty($endtime)){
            $map['co.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        $arr_status = array(0 => '待审核', 1 => '审核通过', 2 => '已拒绝', 5 => '放款中' , 3 => '已打款', 4 => '用户取消');
        $data = $co->join('member_info mi on co.memberid=mi.memberid')
            ->join('member m on m.id=co.memberid')
            ->field('m.username,m.mobile,mi.names,co.id,co.amount,co.fee,co.total,co.outsn,co.extend,co.timeadd,co.status')
            ->where($map)
            ->order('co.id desc')
            ->select();
        foreach($data as $key=>$value){
            $data[$key]['status'] = $arr_status[$value['status']];
        }
        foreach($data as &$v){
            $e = unserialize($v['extend']);
            $v['capital'] = 0;
            $v['allot'] = 0;
            $v['redpacket'] = 0;
            $field = 'capital';
            foreach($e as $et){
                switch($et['codeid']){
                    case 1:
                    case 4:
                        $field = 'capital';
                        break;
                    case 5:
                        $field = 'allot';
                        break;
                    case 6:
                        $field = 'redpacket';
                        break;
                }
                $v[$field] += $et['amount'];
            }
        }

        $cols = array(
            array('username','会员名'),
            array('names','姓名'),
            array('mobile','手机号'),
            array('outsn','订单号'),
            array('total','总额/元'),
            array('amount','提现金额/元'),
            array('fee','手续费/元'),
            array('timeadd','订单时间'),
            array('status','状态')
        );
        return array('data'=>$data,'cols'=>$cols);
    }
    /**
     * @return array
     * 导出还款列表数据
     *
     */
    public function export_data_closing(){
        $k = $this->_get('k','trim');
        $v = $this->_get('v','trim');
        $starttime = $this->_get('starttime1');
        $endtime = $this->_get('endtime1');
        $where = 'l.loanstatus >=3 and l.status = 1';
        $li = M('loan_allot la');
        if($starttime){
            $where .= " and la.lasttime >= '{$starttime} 00:00:00'";
        }
        if($endtime){
            $where .= " and la.lasttime <= '{$endtime} 23:59:59'";
        }
        if ($v){
            switch($k){
                case 'username':
                    $where .= " and m.username = '{$v}'";
                    break;
                case 'name':
                    $where .= " and mi.names = '{$v}'";
                    break;
                case 'cellphone':
                    $where .= " and m.mobile = '{$v}'";
                    break;
            }
        }
        $data = $li->join('loan_issue li on la.issuesn=li.issuesn')
            ->join('loan_tender lt on la.tendersn=lt.tendersn')
            ->join('loan l on la.loansn=l.loansn')
            ->join('member m on la.memberid=m.id')
            ->join('member_info mi on la.memberid=mi.memberid')
            ->field('m.username,m.mobile,mi.names,l.title,la.tendermoney,l.loanstatus,la.ratemoney,la.money,li.lasttime')
            ->where($where)->select();
        $cols = array(
            array('username','投资人'),
            array('names','投资人姓名'),
            array('title','标题'),
            array('mobile','手机号'),
            array('loanstatus','还款状态'),
            array('money','投资金额'),
            array('tendermoney','应还本金'),
            array('ratemoney','应还利息'),
            array('lasttime','还款日期'),
        );
        //array('endtime','止息日'),
        return array('data'=>$data,'cols'=>$cols);
    }
    /**
     * @return array
     * 导出投资列表数据
     *
     */
    public function export_data_tender(){
        $map['lt.id']  = array('gt', 0);
        $map['l.loanstatus']  = array('neq', 2);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $title = $this->_get('title','trim');
        $starttime = $this->_get('start_time', 'trim');
        $endtime = $this->_get('end_time', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;
                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
        }
        if($title){
            $map['l.title'] = $title;
        }
        if(empty($starttime) && !empty($endtime)){
            $map['lt.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        }else if(!empty($starttime) && empty($endtime)){
            $map['lt.timeadd'] = array('egt',$starttime);
        }else if(!empty($starttime) && !empty($endtime)){
            $map['lt.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        $data = M("loan_tender lt")
            ->join('member_info mi on lt.memberid=mi.memberid')
            ->join('member m on m.id = lt.memberid')
            ->join('loan l on l.loansn = lt.loansn')
            ->field('m.username,m.mobile,mi.names,mi.certiNumber,l.title,l.realrate,lt.ratemoney,l.loanmonth,lt.id,lt.money,lt.timeadd,lt.rate,lt.memberid,lt.timeadd')
            ->where($map)->order('lt.id desc')->select();
        foreach($data as &$_val){
        	$tender_count = M('loan_tender')->where("memberid='{$_val['memberid']}' and timeadd<'{$_val['timeadd']}'")->count();
        	$_val['tender_count'] = $tender_count?'是':'否';
        }
        $cols = array(
            array('username','会员名'),
            array('names','姓名'),
            array('mobile','手机'),
            array('certiNumber','身份证号'),
            array('title','投资项目'),
            array('realrate','项目利率/%'),
            array('money','金额/元'),
            array('timeadd','投资时间'),
            array('ratemoney','投资利息(元)'),
            array('rate','投资利率/%'),
            array('loanmonth','投资期限(月)'),
        	array('tender_count','是否续投')
        );
        return array('data'=>$data,'cols'=>$cols);
    }
    /*
     * act: 宝付提现(服务端接口)
     */
    private function baofoo_api_focharge($order_id,$user_id,$user_name,$amount,$fee,$banknum){
        vendor('baofooSdk.inc');
        $baofooRequestService = new BaofooRequestService(BAOFOO_MERCHANTID,BAOFOO_TERMINALID,BAOFOO_SIGNKEY,BAOFOO_TESTMODE);

        vendor('baofooSdk.dto.request.fochargeDto');

        $order_id = $order_id;
        $user_id = $user_id;
        $user_name = $user_name;
        $amount = $amount;
        $fee = $fee;
        $fee_taken_on = "1";//1平台2个人
        $bank_no = $banknum;
        $fochargeDto = new FochargeDto($order_id,$user_id,$user_name,$amount,$fee,$fee_taken_on,$bank_no);
        return $baofooRequestService->api_FoCharge($fochargeDto);
    }
    /**
     * 还款列表
     * 通过人
     * zwd 修改 5.11
     */
    public function repayList(){
        $params = '';
        $map['la.id']  = array('gt', 0);
        $map['l.loanstatus']  = array('gt', 2);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('content', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $title = $this->_get('title', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;

                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }

            $params = "/k/{$key}/content/{$value}";
        }
        if($status){
            $map['la.status'] = $status == 1 ? 1 : 0 ;
            $params .= "/status/{$status}";
        }
        if(empty($starttime) && !empty($endtime)){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) <= '$endtime'";
            $params .= "/endtime/{$endtime}";
        }else if(!empty($starttime) && empty($endtime)){
            $params .= "/starttime/{$starttime}";
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) >= '$starttime'";
        }else if(!empty($starttime) && !empty($endtime)){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) between '$starttime' and '$endtime'";
            $params .= "/starttime/{$starttime}";
            $params .= "/endtime/{$endtime}";
        }
        if($title){
            $title_like = "%$title%";
            $map['l.title'] = array('like',$title_like);
            $params .= '/title/'.$title;
        }
        $count = M('loan_allot la')
            ->join('loan l on la.loansn = l.loansn')
            ->join('loan_issue li on la.issuesn = li.issuesn')
            ->join('member m on la.memberid = m.id')
            ->join('member_info mi on mi.memberid = m.id')
            ->where($map)->count();
        $money = $repayMoney = 0;
        $results  = M('loan_allot la')
            ->join('loan l on la.loansn = l.loansn')
            ->join('loan_issue li on la.issuesn = li.issuesn')
            ->join('member m on la.memberid = m.id')
            ->join('member_info mi on mi.memberid = m.id')
            ->where($map)
            ->field('la.status,la.tendermoney,la.ratemoney')->select();
        foreach($results as $result){
            if($result['status'] == 1){
                $money += $result['tendermoney']+$result['ratemoney'];
            }else if($result['status'] == 0){
                $repayMoney += $result['tendermoney']+$result['ratemoney'];
            }
        }
        $total['sumMoney'] = sprintf('%.2f', $money);
        $total['repayMoney'] = sprintf('%.2f', $repayMoney);

        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list = M('loan_allot la')
                ->join('loan_issue li on la.issuesn = li.issuesn')
                ->join('loan_tender lt on la.tendersn = lt.tendersn')
                ->join('loan l on la.loansn = l.loansn')
                ->join('member m on la.memberid = m.id')
                ->join('member_info mi on mi.memberid = m.id')
                ->field('l.title,l.loansn,m.username,m.mobile,mi.names,la.status,lt.money as tender_money,(la.tendermoney + la.ratemoney) as money,la.tendermoney,la.ratemoney,IF(li.isrepayment = 1,li.repaymenttime,li.endtime) repaytime')
                ->where($map)->order('la.status desc,li.repaymenttime desc,li.endtime desc,la.id desc')->limit($limit)->select();
            $this->assign('list', $list);
        }
        $this->setPage("/Cash/repayList{$params}/p/*.html");
        $this->assign('status', $this->_repaystatus);
        $this->assign('keys', $this->_cashinkeys);
        $this->assign('totalNum', $total);
        $this->display();
    }

    /**
     *  还款列表
     * @return array
     * 导出数据
     *
     */
    public function export_data_repayList(){
        $map['la.id']  = array('gt', 0);
        $map['l.loanstatus']  = array('gt', 2);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('content', 'trim');
        $status = $this->_get('status');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $title = $this->_get('title', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    $map['m.username'] = $value;
                    break;
                case 'name':
                    $map['mi.names'] = $value;

                    break;
                case 'cellphone':
                    $map['m.mobile'] = $value;
                    break;
            }
        }
        if($status != 0){
            $map['la.status'] = $status == 1 ? 1 : 0 ;
        }
        if(empty($starttime) && !empty($endtime)){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) <= '$endtime'";
        }else if(!empty($starttime) && empty($endtime)){
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) >= '$starttime'";
        }else if(!empty($starttime) && !empty($endtime)){
            $endtime = date('Y-m-d 23:59:59',strtotime($endtime));
            $map['_string'] = "IF(li.isrepayment = 1,li.repaymenttime,li.endtime) between '$starttime' and '$endtime'";
        }
        if($title){
            $title_like = "%$title%";
            $map['l.title'] = array('like',$title_like);
        }
        $list = M('loan_allot la')
            ->join('loan_issue li on la.issuesn = li.issuesn')
            ->join('loan_tender lt on la.tendersn = lt.tendersn')
            ->join('loan l on la.loansn = l.loansn')
            ->join('member m on la.memberid = m.id')
            ->join('member_info mi on mi.memberid = m.id')
            ->field('l.title,l.loansn,m.username,m.mobile,mi.names,IF(la.status = 0, "未还款", "已还款") as status,lt.money as tender_money,(la.tendermoney + la.ratemoney) as money,la.tendermoney,la.ratemoney,IF(li.isrepayment = 1,li.repaymenttime,li.endtime) repaytime')
            ->where($map)->order('la.status desc,li.repaymenttime desc,li.endtime desc,la.id desc')->select();
        $cols = array(
            array('title','标题'),
            array('username','投资人'),
            array('mobile','投资人手机'),
            array('names','投资人姓名'),
            array('tender_money','投资金额'),
            array('money','还款金额'),
            array('tendermoney','还款本金'),
            array('ratemoney','还款利息'),
            array('status','还款状态'),
            array('repaytime','还款时间'),
        );
        return array('data'=>$list,'cols'=>$cols);
    }
    /**
     *
     * liu biao list
     * yao
     *
     */
    public function loanGoneList(){
        $params = '';
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $title = $this->_get('title', 'trim');
        if(empty($starttime) && !empty($endtime)){
            $where['lasttime'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= "/endtime/{$endtime}";
        }else if(!empty($starttime) && empty($endtime)){
            $where['lasttime'] = array('egt',$starttime);
            $params .= "/starttime/{$starttime}";
        }else if(!empty($starttime) && !empty($endtime)){
            $where['lasttime'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= "/endtime/{$endtime}";
            $params .= "/starttime/{$starttime}";
        }
        if($title){
            $title_like = "%$title%";
            $where['title'] = array('like',$title_like);
            $params .= "/title/{$title}";
        }
        $where['loanstatus'] = 2;
        $count = M('loan')
            ->where($where)->count();
        if ($count > 0) {
            $this->page['count'] = $count;
            $this->page['no'] = $this->_get('p', 'intval', 1);
            $this->page['total'] = ceil($count / $this->page['num']);
            if($this->page['total'] < $this->page['no']){
                $this->page['no'] = 1;
            }
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list = M('loan')
                ->where($where)
                ->field('title,loanmoney,timeadd as starttime,lasttime,loansn')
                ->order('lasttime desc')
                ->limit($limit)
                ->select();
            $this->assign('list', $list);
        }
        $this->setPage("/Cash/loanGoneList{$params}/p/*.html");
        $this->assign('status', $this->_repaystatus);
        $this->assign('keys', $this->_cashinkeys);
        $this->display();
    }
    /**
     * export liubiao list
     * yao
     */
    public function export_data_loanGoneList(){
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        $title = $this->_get('title', 'trim');
        if(empty($starttime) && !empty($endtime)){
            $where['lasttime'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        }else if(!empty($starttime) && empty($endtime)){
            $where['lasttime'] = array('egt',$starttime);
        }else if(!empty($starttime) && !empty($endtime)){
            $where['lasttime'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        if($title){
            $title_like = "%$title%";
            $where['title'] = array('like',$title_like);
        }
        $where['loanstatus'] = 2;
        $list = M('loan')
            ->where($where)
            ->field('title,loanmoney,timeadd as starttime,lasttime,loansn,IF(loanstatus = 2, "流标","") as status')
            ->order('lasttime desc')
            ->select();
        $cols = array(
            array('title','标题'),
            array('loanmoney','金额'),
            array('starttime','上线时间'),
            array('lasttime','流标时间'),
            array('status','状态'),
        );
        return array('data'=>$list,'cols'=>$cols);
    }
}
