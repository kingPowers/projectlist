<?php

/**
 * Description of CommonAction
 *
 */
class ActivityAction extends CommonAction {
    public $page_num = 10;
	private $_I = array();
	private $_keys = array( 'name' => '姓名', 'username' => '会员名',  'mobile' => '手机号');
	//初始化
    protected function _initialize() {
        $this->_I = M('activity_conupon');
    }
    //2016双十二活动
	public function index(){
		$data = $this->get_index_data(true);
		$this->assign('list',$data);
        $this->setPage("/Activity/index{$data['params']}/p/*.html");
        $this->assign('keys',$this->_keys);
        //过期的
        M()->query("update activity_conupon set status=2 where timeadd<'".date("Y-m-d")."' and status=0");
		$this->display();
	}
    //编辑活动信息
    public function edit_activity(){
        $map['id'] = $this->_get('id');
        $res = M('activity_conupon')
        ->where($map)
        ->field('store')
        ->find();
        $res['id'] = $this->_get('id');
        $this->assign('store',$res);
        if(isset($_POST) && !empty($_POST['id'])){
            $data['id'] = $this->_post('id');
            $data['store'] = $this->_post('store');
            foreach ($data as $key=>$val) {
               if ($val === '') {
                   $this->error('信息不完整');
                   exit;
               }
            }
            $S = M('activity_conupon')->where('id='.$data['id'])->save($data);
            if($S){
                 $this->_reset_data();
                 $this->success('信息修改成功');
             }else{
                 $this->error('信息修改失败');
             }
         }
        
        $this->display();
    }
    //删除活动人物信息
    public function delete_activity(){
        if(!empty($this->_post('aid'))){
            $id = $this->_post('aid');
            $S = M('activity_conupon')->where('id='.$id)->delete();
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
    //提额活动列表
    public function proActivity()
    {
        $starttime = $this->_get("starttime","trim");
        $endtime = $this->_get("endtime","trim");
        $status = $this->_get("status","trim");
        $title = $this->_get("title","trim");
        $params = '';$map = [];
        if ($status) {
            $map['status'] = $status-1;
            $params .= 'status/{$status}';
        }
        if ($title) {
            $map['title'] = "%{$title}%";
            $params .= "/title/{$title}";
        }
        if ($starttime && !$endtime) {
            $map['starttime'] = ['EGT',$starttime];
            $params .= "/starttime/{$starttime}";
        } elseif (!$starttime && $endtime) {
            $map['starttime'] = ['ELT',$endtime];
            $params .= "/endtime/{$endtime}";
        } elseif ($starttime && $endtime) {
            $map['starttime'] = ['between',[$starttime,$endtime]];
            $params .= "/starttime/{$starttime}/endtime/{$endtime}";
        }
        $ac_module = M("activity_promote");
        $count = $ac_module->where($map)->count();
        $this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $list = [];
        if ($count) {
            $list = $ac_module
                ->where($map)
                ->order("timeadd desc")
                ->limit($limit)
                ->select();
        }
        $this->assign("list",$list);
        $this->assign("status",['0' => '全部','1' => '禁用','2' => '启用']);
        $this->display();
    }
    //新增活动
    public function activityAdd()
    {
        $this->display('edit');
    }
    public function activityEdit()
    {
        if ($_POST && ($_POST['is_save'] == 1)) $this->acToDb();
        $aid = $this->_get('aid','intval');
        $info = M('activity_promote')->where("id={$aid}")->find();
        $this->assign('info',$info);//dump($info);
        $this->display('edit');
    }
    public function acToDb()
    {
        $aid = $this->_post("aid",'intval');
        $data = [];
        $data['title'] = $this->_post("title",'trim');
        $data['keyword'] = $this->_post("keyword",'trim');
        $data['pro_percent'] = $this->_post("percent",'trim');
        $data['status'] = $this->_post("status",'trim')?1:0;
        $data['starttime'] = $this->_post("starttime",'trim');
        $data['endtime'] = $this->_post("endtime",'trim');
        if (empty($data['title'])) $this->ajaxError("活动名称不能为空");
        if (empty($data['keyword'])) $this->ajaxError("活动关键字不能为空");
        if (empty($data['pro_percent'])) $this->ajaxError("活动提升百分比不能为空");
        if (empty($data['starttime'])) $this->ajaxError("活动开始时间不能为空");
        if (empty($data['endtime'])) $this->ajaxError("活动截止时间不能为空"); 
        if (!in_array($data['status'],[0,1])) $this->ajaxError("活动状态不正确");     
        if ($aid) {
            $data['lasttime'] = date("Y-m-d H:i:s",time());
            if ($_FILES['banner']['tmp_name']) {
                $upload_res = $this->UploadBanner();
                if (false == $upload_res['status']) {
                    $this->ajaxError($upload_res['error']);
                }
                $data['banner_url'] = $upload_res['url'];
            }
            $res = M("activity_promote")->where("id={$aid}")->save($data);
        } else {  
            $data['timeadd'] = $data['lasttime'] = date("Y-m-d H:i:s",time());
           // if (!$_FILES['banner']) $this->ajaxError("请添加活动banner");
            if ($_FILES['banner']['tmp_name']){   
                $upload_res = $this->UploadBanner();
                if (false == $upload_res['status']) {
                    $this->ajaxError($upload_res['error']);
                }
                $data['banner_url'] = $upload_res['url'];
            }else{
                $data['banner_url'] = "";
            }
            
            $res = M("activity_promote")->add($data);
        }
        if ($res) {
            $this->ajaxSuccess("操作成功");
        }
        $this->ajaxError("操作失败");
    }
    private function UploadBanner()
    {
        if ($_FILES) {
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();// 实例化上传类
            $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $dir = UPLOADPATH.'activity';
            if (!is_dir($dir))mkdir($dir,0755);
            $path = 'ac_banner_'.date("m",time()).DIRECTORY_SEPARATOR;
             $upload->savePath =  $dir.DIRECTORY_SEPARATOR.$path;// 设置附件上传目录
             $upload->saveRule = 'activity_'.rand(0000,9999).time();           //设置文件保存规则唯一
             $upload->thumb = true;
             $upload->uploadReplace = true;                 //同名则替换
             //设置需要生成缩略图的文件后缀
             $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
             //设置缩略图最大宽度
             $upload->thumbMaxWidth = '400,100';
             //设置缩略图最大高度F
             $upload->thumbMaxHeight = '400,100'; 
              if(!$upload->upload()) {// 上传错误提示错误信息
                 return ['status' => false,'error' => $upload->getErrorMsg()];
              }else{// 上传成功 获取上传文件信息
                 $info =  $upload->getUploadFileInfo();
                 return ['status' => true,"url" => $path.implode("|",array_column($info,"savename"))];
                // $data['pic_card2'] = implode("|",array_column($info,"savename"));
              }
        }
    }
    //更新缓存菜单
    private function _reset_data() {
        F('global_auth_rule_all', null);
        F('global_auth_rule_menu', null);
    }
	//导出消息数据
    public function export_index_info(){
    	$data = $this->get_index_data(false);
		$this->export_data(array('data'=>$data['data'],'cols'=>$data['column']));
    }
    //添加礼券
    public function add_quan(){
    	if(!empty($_POST)){
    		$member = M('member')->where("mobile='{$_POST['data']['mobile']}'")->find();
    		if(false==$member)$this->error("手机号未被注册");
    		if(false!=M('activity_conupon')->where("memberid='{$member['id']}' and status=0 and date_format(timeadd,'%Y-%m-%d')=".date("Y-m-d"))->find()){
    			$this->error("对不起，此会员已存在礼券了！");
    		}
    		$sn = time().rand(111,999);
    		$add_data = array(
    				'activity_sn'=>$sn,
    				'memberid'=>$member['id'],
    				'money'=>'299',
    				'remark'=>'员工添加',
    				'store'=>$_POST['data']['store'],
    				'status'=>0,
    		);
    		$upon_id = M('activity_conupon')->add($add_data);
    		if($upon_id)$this->success('添加成功');
    		$this->error("添加失败");
    	}
    	$this->display();
    }
    
    //更改状态
    public function changeStatus(){
    	$id = $this->_post('id');
    	$status = $this->_post('status');
    	$where['id'] = $id;
    	$data['status'] = $status;
    	$result = M('activity_conupon')->where($where)->save($data);
    	if ($result === false) {
    		$this->error('状态修改失败');
    	}
    	$this->success('操作成功');
    }

    
//------------------------------工具方法---------------------------------------

    private function get_index_data($is_page = false){
    	$map = array();
    	$params = '';
    	$key = $this->_get('k', 'trim');
    	$value = $this->_get('v', 'trim');
    	if (!empty($value)) {
    		switch($key){
    			case 'username':
    				$map['m.username'] = $value;
    				break;
    			case 'name':
    				$map['mi.names'] = array('exp',"='{$value}' or ac.memberid in(select memberid from `order` o where names like '%{$value}%')");
    				break;
    			case 'mobile':
    				$map['m.mobile'] = $value;
    				break;
    		}
    		$params .= "/k/{$key}/v/{$value}";
    	}
    	$starttime = $_REQUEST['starttime'];
    	$endtime = $_REQUEST['endtime'];
    	
    	if($starttime && empty($endtime)){
    		$map['ac.timeadd'] = array('exp',">='{$starttime}'");
    		$params .= '/starttime/'.$starttime;
    	}
    	if($endtime && empty($starttime)){
    		$map['ac.timeadd'] = array("exp","<='{$endtime} 23:59:59'");
    		$params .= '/endtime/'.$endtime;
    	}
    	if($endtime && $starttime){
    		$map['ac.timeadd'] = array("exp",">='{$starttime}' and ac.timeadd<='{$endtime} 23:59:59'");
    		$params .= "/starttime/{$starttime}/endtime/{$endtime}";
    	}
    	$count =  M('activity_conupon ac')
		    	->join('member m on m.id = ac.memberid')
		    	->join('member_info mi on mi.memberid = m.id')
		    	->where($map)
		    	->count();
    	if($is_page){
    		$this->page['count'] = $count;
    		$this->page['no'] = $this->_get('p', 'intval', 1);
    		$this->page['total'] = ceil($count / $this->page['num']);
    		$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
    	}
    	$column = array(
    			array('id','ID'),
    			array('username','会员名'),
    			array('names','姓名'),
    			array('mobile','手机'),
    			array('store','门店'),
    			array('remark','备注'),
    			array('timeadd','领取日期'),
    			array('status_name','状态'),
    	);
    	$_status = "if(ac.status=0,'未使用',if(ac.status=1,'已使用',if(ac.status=2,'已过期',''))) as status_name";
    	$info = M('activity_conupon ac')
		    	->join('member m on m.id = ac.memberid')
		    	->join('member_info mi on mi.memberid = m.id')
		    	->field("ac.*,{$_status},m.username,m.mobile,mi.names")
		    	->order('ac.timeadd desc')
		    	->where($map)
		    	->limit($limit)
		    	->select();
    	foreach($info as &$val){
    		$order_info = M('`order` o,order_process p')
    					->join("store s on s.id=p.store_id and s.type=2")
    					->where("o.id=p.order_id and memberid='{$val['memberid']}'")
    					->field("o.names,s.name")
    					->order("o.timeadd desc")->find();
    		$val['store'] = $val['store']?$val['store']:$order_info['name'];
    		$val['names'] = $val['names']?$val['names']:$order_info['names'];
    	}
    	return array('column'=>$column,'data'=>$info,'params'=>$params);
    		
    }
    
    //导出数据到EXCEL
    private function export_data($result){
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