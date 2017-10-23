<?php
/**
 * Description of CommonAction
 * 门店管理
 */
class AssedAction extends CommonAction {
    private $_keys = array( 'name' => '姓名');//, 'username' => '会员名',  'cellphone' => '手机号'
    private $page_num = 12;
    //首页
    public function index() {
        $params = $orderstr = '';
        $where['oa.id'] = array('gt',0);
        $key = $this->_get('k', 'trim');
        $value = $this->_get('v', 'trim');
        $starttime = $this->_get('starttime', 'trim');
        $endtime = $this->_get('endtime', 'trim');
        if (!empty($value)) {
            switch($key){
                case 'username':
                    //$where['m.username'] = $value;
                    $where['mi.names'] = $value;
                    break;
                case 'name':
                    $where['mi.names'] = $value;
                    break;
                case 'cellphone':
                    //$where['m.mobile'] = $value;
                    $where['mi.names'] = $value;
                    break;
            }
            $params .= "/k/{$key}/v/{$value}";
        }
        if($starttime && !$endtime){
            $where['oa.timeadd'] = array('egt',strtotime($starttime));
            $params .= '/starttime/'.$starttime;
        }else if(!$starttime && $endtime){
            $endtime = strtotime(date('Y-m-d 23:59:59',strtotime($endtime)));
            $where['oa.timeadd'] = array('elt',$endtime);
            $params .= '/endtime/'.$endtime;
        }else if($starttime && $endtime){
            $starttime = strtotime($starttime);
            $endtime = strtotime(date('Y-m-d 23:59:59',strtotime($endtime)));
            $where['oa.timeadd'] = array('between',array($starttime,$endtime));
            $params .= '/starttime/'.$starttime;
            $params .= '/endtime/'.$endtime;
        }
        $order_status = $this->_get('order_status','trim');//状态
        $order_id = $this->_get('order_id','trim');//是否被邀请
        if($order_status !=''){
            $where['oa.status'] = $order_status;
            $params .= '/order_status/'.$order_status;
        }
        if($order_id){
            $where['order_id'] = $order_id;
        }
        $results = M('order_assed oa')
        	->field("oa.*,mi.names")
            ->join('member_info mi on mi.memberid=oa.memberid')
            ->where($where)
            ->order('oa.timeadd desc')
            ->select();
        $count = count($results);
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $list = array_slice($results,$limit,$this->page['num']);
        $cream_status = array('1'=>'是','0'=>'否');
        $status = array('0'=>'未审核','1'=>'审核通过','2'=>'自己删除','3'=>'管理员删除');
        $this->setPage("/Assed/index{$params}/p/*.html");
        $this->assign('list', $list);
        $this->assign('keys', $this->_keys);
        $this->assign('cream_status',$cream_status);
        $this->assign('status',$status);
        $this->display();
    }
    public function changeStatus(){
        $id = $this->_post('id');
        $status = $this->_post('status');
        $where['id'] = $id;
        $data['status'] = $status;
        $result = M('order_assed')->where($where)->save($data);
        if ($result === false) {
            $this->error('处理失败');
        }
        $this->success('处理成功');
    }
    public function changeCreamStatus(){
        $id = $this->_post('id');
        $status = $this->_post('status');
        $where['id'] = $id;
        $data['is_cream'] = $status;
        $result = M('order_assed')->where($where)->save($data);
        if ($result === false) {
            $this->error('处理失败');
        }
        $this->success('处理成功');
    }
    public function changeOrder(){
        $id = $this->_post('id');
        $order = $this->_post('order');
        $where['id'] = $id;
        $data['order'] = $order;
        $result = M('order_assed')->where($where)->save($data);
        if ($result === false) {
            $this->error('处理失败');
        }
        $this->success('处理成功');
    }
}
