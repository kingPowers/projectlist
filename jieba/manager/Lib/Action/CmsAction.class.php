<?php
/**
 * Description of CommonAction
 * 内容管理
 */
class CmsAction extends CommonAction {

    //意见反馈首页
    public function view(){
        $p = $this->_get('p', 'intval', 1);
        $params = "";
        $mobile = $this->_get('mobile','trim');
        $map = array('v.id' => array('gt', 0));
        if(!empty($mobile)) {
            $map['m.mobile'] = $mobile;
            $params = "/Cms/{$mobile}";
        }
        $count = M('view v')->join('member m on m.id= v.memberid ')->where($map)->count();
        if ($count) {
            $this->page['count'] = $count;
            $this->page['no'] = $p;
            $this->page['total'] = ceil($count / $this->page['num']);
            if($this->page['total']<$p){
                $this->page['no'] = 1;
            }
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list  = M('view v')
                ->join('member m on m.id= v.memberid ')
                ->field('v.id,v.content,v.timeadd,m.mobile')
                ->where($map)
                ->order('timeadd desc')
                ->limit($limit)
                ->select();
        }
        $this->setPage("/Cms/view{$params}/p/*.html");
        $this->assign('list', $list);
        $this->display();
    }
    //意见反馈详情
    public function viewInfo() {
        $id = $this->_get('id', 'intval', 0);
        if ($id) {
            $member = M('view v')
                ->join('member m on v.memberid = m.id')
                ->where("v.id={$id}")
                ->field('m.mobile,v.content')
                ->find();
        }
        $this->assign('member', $member);
        $this->display("viewInfo");//详情页
    }
   //分类列表
    public function system_cate(){
        $_sta_tus =  array(
            '0' => "未审核",
            '1' => "启用",
            '2' => "禁用"
        );
        $p = $this->_get('p', 'intval', 1);
        $params = "";
        $is_cateid = $this->_get('cateid','trim');
        $map = array('s.id' => array('gt', 0));
        $is_status = $this->_get('status_one');
        //var_dump($is_cateid);exit;
        if($is_status !=''){
            $map['s.status'] = $is_status;
            $params .= "/status_one/{$is_status}";
        }
        if($is_cateid) {
            $map['s.code'] = $is_cateid;
            $params .= "/cateid/{$is_cateid}";
        }
        $count = M('system_cate s')->where($map)->count();
        if ($count) {
            $this->page['count'] = $count;
            $this->page['no'] = $p;
            $this->page['total'] = ceil($count / $this->page['num']);
            if($this->page['total']<$p){
                $this->page['no'] = 1;
            }
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list  = M('system_cate s')
                ->field('s.id,s.name,s.code,s.status')
                ->where($map)
                ->order('s.id desc')
                ->limit($limit)->select();
        }
        $cateInfo = M('system_cate')->select();
        $this->setPage("/Cms/system_cate{$params}/p/*.html");
        $this->assign('cate', $cateInfo);
        $this->assign('list', $list);
        $this->assign('status', $_sta_tus);
        $this->display();
    }
    /*
    * 消息分类列表状态审核
    */
    public function cateAudo() {
        $id = $this->_post('id');
        $status = $this->_post('status');
        $result = M('system_cate')->where(array('id' => $id))->save(array('status' => $status));
        if ($result === false) {
            $this->error('处理失败');
        }
        $this->success('处理成功');
    }
    //修改分类
    public function cateEdit(){
        $id = $this->_get('id', 'intval', 0);
        if($id){
        $list = M('system_cate s')
            ->where("s.id={$id}")
            ->field('s.name,s.code,s.id,s.status')
            ->find();
        }
        if(!$list){
            $this->redirect('system_cate');
        }
        $this->assign('list', $list);
        $this->display();
    }
    /*
    *     修改分类列表
    */
    public function updateCate(){
        //$post = $this->boxpost();
        $post['id'] = $_POST['id'];
        $post['code'] = $_POST['code'];
        $post['name'] = $_POST['name'];
        $cate = M('system_cate s')
            ->where(array('s.id' => $post['id']))
            ->field('s.id,s.name,s.code,s.status')
            ->find();
        if(empty($cate)) {
            $this->error('消息类型不存在');
        }
        if(empty($post['name'])) {
            $this->error('分类名称不能为空');
        }
        if(empty($post['code'])) {
            $this->error('分类code值不能为空');
        }
        $map['name'] = $post['name'] ;
        $map['code'] = $post['code'] ;
        $camp =  M('system_cate')->where(array('id' => $cate['id']))->save($map);
        if($camp){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }
    /*
    *       添加消息分类
    */
    public function addCate(){
        $system = M('system_cate s')
            ->field('s.id,s.name,s.status,s.code')
            ->select();
        $this->assign('system', $system);
        $this->display();
    }
    /*
     *  添加消息分类2
    */
    public function saveCate(){
        //$post = $this->boxpost();
        $post['code'] = $_POST['code'];
        $post['name'] = $_POST['name'];
        $system = M('system_cate s')
            ->where(array('s.name' => $post['name']))
            ->field('s.id,s.name,s.status,s.code')
            ->find();
        if(!empty($system)) {
            $this->error('消息类型已存在');
        }
        $data['name'] = $post['name'];
        $data['code'] = $post['code'];
        if(empty($data['name'])) {
            $this->error('消息类型不能为空');
        }
        if(empty($data['code'])) {
            $this->error('CODE值不能为空');
        }
        $camp = M('system_cate')->add($data);
        if($camp){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }
    /*
    * 获取消息分类
    */
    private function _getnews($getall = false){
        $map['st.id'] = array('gt', 0);
        if (!$getall) {
            $map['st.status'] = 1;
        }
        return M('system_cate st')
            ->where($map)
            ->field('DISTINCT(name) id,name,code')
            ->order('name desc')
            ->select();
    }
   //系统消息首页
    public function system(){
        $status = array(
            '0' => "未审核",
            '1' => "通过",
            '2' => "禁用"
        );
        $params = '';
        $map = array('s.id' => array('gt', 0));
        $is_status = $this->_get('status_one');
        $starttime = $this->_get('starttime','trim');
        $endtime = $this->_get('endtime','trim');
        $title = $this->_get('title', 'trim');
        $is_cateid = $this->_get('cateid','trim');
        if($is_status !=''){
            $map['s.status'] = $is_status;
            $params .= "/status_one/{$is_status}";
        }
        if($is_cateid ){
            $map['s.code'] = $is_cateid;
            $params .= "/cateid/{$is_cateid}";
        }
        if($title){
            $title_like = "%$title%";
            $map['s.title'] = array('like',$title_like);
            $params .= '/title/'.$title;
        }

        if(empty($starttime) && !empty($endtime)){
            $map['s.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
            $params .= "/endtime/{$endtime}";
        }else if(!empty($starttime) && empty($endtime)){
            $map['s.timeadd'] = array('egt',$starttime);
            $params .= "/starttime/{$starttime}";
        }else if(!empty($starttime) && !empty($endtime)){
            $map['s.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
            $params .= "/starttime/{$starttime}";
            $params .= "/endtime/{$endtime}";
        }
        $count = M('system s')->where($map)->count();
        if ($count > 0) {
            $p = $this->_get('p', 'intval', 1);
            $this->page['count'] = $count;
            $this->page['no'] = $p;
            $this->page['num'] = 10;
            $this->page['total'] = ceil($count / $this->page['num']);
            if($this->page['total']<$p){
                $this->page['no'] = 1;
            }
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
            $list = M('system s')
                ->join('system_cate sa on s.code= sa.code')
                ->field('s.id,s.code,s.title,s.keywords,s.summary,s.content,s.timeadd,s.lasttime,s.status,s.sort,sa.name')
                ->where($map)->limit($limit)->order('sort desc,s.id desc')->select();
        }
        $cateInfo = M('system_cate')->select();
        $this->assign('cate', $cateInfo);
        $this->setPage("/Cms/system{$params}/p/*.html");
        $this->assign('list', $list);
       // $this->assign('category', $this->getCategroy(false));
        $this->assign('status', $status);
        $this->assign('cateid',$this->_getnews());
        $this->display();
    }
    //导出消息记录excel
    public function export_system(){
        $map = array('s.id' => array('gt', 0));
        $is_status = $this->_get('status_one');
        $starttime = $this->_get('starttime','trim');
        $endtime = $this->_get('endtime','trim');
        $title = $this->_get('title', 'trim');
        $is_cateid = $this->_get('cateid','trim');
        if($is_status !=''){
            $map['s.status'] = $is_status;
        }
        if($is_cateid ){
            $map['s.code'] = $is_cateid;
        }
        if($title){
            $title_like = "%$title%";
            $map['s.title'] = array('like',$title_like);
        }
        if(empty($starttime) && !empty($endtime)){
            $map['s.timeadd'] = array('elt',date('Y-m-d 23:59:59',strtotime($endtime)));
        }else if(!empty($starttime) && empty($endtime)){
            $map['s.timeadd'] = array('egt',$starttime);
        }else if(!empty($starttime) && !empty($endtime)){
            $map['s.timeadd'] = array('between',array($starttime,date('Y-m-d 23:59:59',strtotime($endtime))));
        }
        vendor('PHPExcel.PHPExcel');
        vendor('PHPExcel.PHPExcel.IOFactory');
        vendor('PHPExcel.PHPExcel.Writer.Excel5');
        $PHPExcel = new PHPExcel();

        //输出内容如下：
        $word = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
        $result = array();
        $cols = array(
            array('id','编号'),
            array('code','分类'),
            array('title','标题'),
            array('content','内容'),
            array('timeadd','添加时间'),
            array('status','审核状态'),
        );

        $arr_status = array('未审核', '通过', '禁用');

        $result = M('system s')
            ->join('system_cate st on s.code = st.code')
            ->field('s.id,s.title,s.content,s.code,s.timeadd,s.status')
            ->where($map)
            ->order('s.id desc')
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
        $outputFileName = '消息导出'.date('YmdHis').'.xls';
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

    //消息内容添加
    public function addSystem(){
        $cateInfo = M('system_cate')->where("status=1")->select();
        $this->assign('cate', $cateInfo);
        $this->display();
    }
    /*
    *  消息内容添加2
    */
    public function saveSystem(){
        $post['title'] = $_POST['title'];
        $post['code'] = $_POST['cateid'];
        $post['keywords'] = $_POST['keywords'];
        $post['summary'] = $_POST['summary'];
        $post['sort'] = $_POST['sort'];
        $post['content'] = $_POST['content'];
        //$post['touserid'] = $_POST['touserid'];
        $system = M('system s')
            ->where(array('s.name' => $post['title']))
            ->find();
        if(!empty($system)){
            $this->error('该内容已存在');
        }
        $data['title'] = $post['title'];
       // $data['touserid'] = $post['touserid'];
        $data['code'] = $post['code'];
        $data['keywords'] = $post['keywords'];
        $data['summary'] = $post['summary'];
        $data['sort'] = $post['sort'];
        $data['content'] = urldecode($post['content']);
        $data['timeadd'] = date('Y-m-d H:i:s');
        if(empty($data['title'])) {
            $this->error('标题不能为空');
        }
        if(empty($data['code'])) {
            $this->error('消息类型不能为空');
        }
        if(empty($data['keywords'])) {
            $this->error('关键字不能为空');
        }
        if(empty($data['summary'])) {
            $this->error('内容简要不能为空');
        }
        if(empty($data['sort'])) {
            $this->error('排序不能为空');
        }
        if(empty($data['keywords'])) {
            $this->error('消息内容不能为空');
        }
        if($data['code']== "company") {
              $camp = M('system')->add($data);
              /*$sign['system_id'] = $camp;
              $system = M('middle')->field('system_id,memberid')->find();
              //var_dump(M()->getLastSql());exit;
              if(empty($system['system_id'])){
                   $member = M('middle')->join('system s on middle.system_id = s.id')->where('middle.memberid>0')
                           ->save($sign);
              }
              if($system['system_id']){
                   //$member = M('middle')->join('system s on middle.system_id = s.id')->where('middle.memberid>0')
                    //->save($sign);
                   $sign['memberid'] = $system['memberid'];
                   $camp = M('middle')->add($sign);
              }*/
        }
        if($camp){
            $this->success('添加成功');
        }else{
            $this->error('添加失败');
        }
    }
    //修改消息内容
    public function editSystem(){
        $id = $this->_get('id', 'intval', 0);
        if($id){
            $list = M('system s')->where("s.id={$id}")->find();
            //var_dump(M()->getLastSql());exit;
        }
        if(!$list){
            $this->redirect('system');
        }
        $cateInfo = M('system_cate')->where("status=1")->select();
        $this->assign('cate', $cateInfo);
        $this->assign('list', $list);
        $this->display();
    }
    /*
    *     修改分类列表
    */
    public function updateSystem(){
        $post['id'] = $_POST['id'];
        $post['title'] = $_POST['title'];
        $post['code'] = $_POST['cateid'];
        $post['keywords'] = $_POST['keywords'];
        $post['summary'] = $_POST['summary'];
        $post['sort'] = $_POST['sort'];
        $post['content'] = $_POST['content'];
        $system = M('system s')
            ->where(array('s.name' => $post['title']))
            ->find();
        if(!empty($system)){
            $this->error('该内容已存在');
        }
        $data['title'] = $post['title'];
        $data['code'] = $post['code'];
        $data['keywords'] = $post['keywords'];
        $data['summary'] = $post['summary'];
        $data['sort'] = $post['sort'];
        $data['content'] = urldecode($post['content']);
        //var_dump($data['content']);exit;
        $data['timeadd'] = date('Y-m-d H:i:s');
        if(empty($data['title'])) {
            $this->error('标题不能为空');
        }
        if(empty($data['code'])) {
            $this->error('消息类型不能为空');
        }
        if(empty($data['keywords'])) {
            $this->error('关键字不能为空');
        }
        if(empty($data['summary'])) {
            $this->error('内容简要不能为空');
        }
        if(empty($data['sort'])) {
            $this->error('排序不能为空');
        }
        if(empty($data['keywords'])) {
            $this->error('消息内容不能为空');
        }
        $camp =  M('system')->where(array('id' => $post['id']))->save($data);
        //var_dump(M()->getLastSql());exit;
        if($camp){
            $this->success('修改成功');
        }else{
            $this->error('修改失败');
        }
    }
    /*
    *     消息内容审核
    */
    public function systemAudo() {
        $id = $this->_post('id');
        $status = $this->_post('status');
        $result = M('system')->where(array('id' => $id))->save(array('status' => $status));
        if ($result === false) {
            $this->error('处理失败');
        }
        $system_info = M('system')->where(array('id' => $id))->find();
        if($status==1){
        	import("Think.ORG.Util.Getui");
        	$getui = new Getui;
        	$json = json_encode(array('id'=>$id,'content'=>$system_info['summary']));
        	$android_res = $getui->pushMessageToApp($system_info['title'],$json,'android');
        	$android_error = $getui->getError();
        	$ios_res = $getui->pushMessageToApp($system_info['title'],$json,'ios');
        	$error = $getui->getError();
        	$msg = ($android_res?"安卓设备推送成功":"安卓推送失败".serialize($android_error)).($ios_res?"IOS设备推送成功":"IOS推送失败".serialize($error));
        	 
        }
       $this->success('处理成功'.$msg);
    }
 }


