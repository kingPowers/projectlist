<?php
class MediaAction extends CommonAction
{
	private $keys = array();
	private $err = '';
	private $page_num = 5;
	private $_static_ = '';
	public function __construct()
    {
    	parent::__construct();
		$this->_static_ = defined(_STATIC_)?_STATIC_:"http://daistatic.lingqianzaixian.com";
	}
	public function index()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$params = $return['params'];
		$count = M("media_news")
		->where($where)
		->count();
		$this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
        $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
        $lists = M("media_news")
        ->where($where)
        ->order("status desc,sort asc,lasttime desc")
        ->limit($limit)
        ->select();
        foreach ($lists as &$value) {
        	$value['s_pic_urls'] = _STATIC_."/Upload/news/s_".$value['pic_urls'];
        	$value['m_pic_urls'] = _STATIC_."/Upload/news/m_".$value['pic_urls'];
        	$value['pic_urls'] = _STATIC_."/Upload/news/".$value['pic_urls'];
        	$value['intro'] = mb_substr($value['intro'], 0,100,'utf-8');
        }
        $cream_status = array("0"=>"否","1"=>"是");
        $news_status = array("0"=>"禁用","1"=>"使用");
        $this->setPage("/Media/index{$params}/p/*.html");
        $this->assign('list', $lists);//dump($lists);
        $this->assign("keys",$this->keys);
        $this->assign("cream_status",$cream_status);
        $this->assign("status",$news_status);
		$this->display();
	}
	public function getParams()
	{
       $where = array();
       $params = '';
       $starttime = $this->_get("starttime","trim");
       $endtime = $this->_get("endtime","trim");
       if($starttime && !$endtime)
       {
       	  $where['lasttime'] = array("egt",$starttime);
       	  $params .= "/starttime/".$starttime;
       }elseif (!$starttime && $endtime)
       {
       	  $endtime = date("Y-m-d 23:59:59",strtotime($endtime));
       	  $where['lasttime'] = array("elt",$endtime);
       	  $params .= "/endtime/".$endtime;
       }elseif ($starttime && $endtime)
       {
       	  $endtime = date("Y-m-d 23:59:59",strtotime($endtime));
       	  $where['lasttime'] = array("between",array($starttime,$endtime));
       	  $params .= "/starttime/".$starttime."/endtime/".$endtime;
       }
       return array("where"=>$where,"params"=>$params);
	}
	public function is_cream()
	{
		if($_POST['is_ajax'] == 1)
		{
			$status = array("0"=>"去精华","1"=>"加精");
			$is_cream = $this->_post("is_cream","trim");
			$news_id = $this->_post("news_id","trim");
			if(false == ($news_info = M("media_news")->where("id='{$news_id}'")->find()))$this->ajaxError("该订单不存在");
			if($news_info['is_cream'] == $is_cream)$this->ajaxError("新闻已【$status[$is_cream]】,请不要重复操作");
			if(fase == M("media_news")->where("id={$news_id}")->save(array("is_cream"=>$is_cream)))$this->ajaxError("操作失败，请稍后再试");
			$this->ajaxSuccess("操作成功");
		}
	}
	public function changeStatus()
	{
		if($_POST['is_ajax'] == 1)
		{
			$status_name = array("0"=>"禁用","1"=>"使用");
			$status = $this->_post("status","trim");
			$news_id = $this->_post("news_id","trim");
			if(false == ($news_info = M("media_news")->where("id='{$news_id}'")->find()))$this->ajaxError("该订单不存在");
			if($news_info['status'] == $status)$this->ajaxError("新闻已【$status_name[$status]】,请不要重复操作");
			if(fase == M("media_news")->where("id={$news_id}")->save(array("status"=>$status)))$this->ajaxError("操作失败，请稍后再试");
			$this->ajaxSuccess("操作成功");
		}
	}
	public function edit_news()
	{
		$news_id = trim($this->_request("id","trim")," /t/n/r");
		if($news_id)
		{
			$news_info = M("media_news")->where("id={$news_id}")->find();
			$news_info['s_pic_urls'] = _STATIC_."/Upload/news/s_".$news_info['pic_urls'];	
			$news_info['m_pic_urls'] = _STATIC_."/Upload/news/m_".$news_info['pic_urls'];
			$news_info['pic_urls'] = _STATIC_."/Upload/news/".$news_info['pic_urls'];
			$this->assign("news",$news_info);
		}
		$this->display();
	}
	public function sub_edit_news()
	{	
		
		$data = array();
		$data['title'] = $title = $this->_post("title","trim");
		$data['keys'] = $this->_post("keys","trim");
		$data['intro'] = $this->_post("intro","trim");
		$data['sort'] = $this->_post("sort","trim");
		$data['is_cream'] = $this->_post("is_cream","trim");
		$data['content'] = urldecode($this->_post("content","trim"));
		$data['timeadd'] = date("Y-m-d H:i:s",time());
		$data['lasttime'] = $data['timeadd'];
		//修改新闻
		if($_POST && !empty($_POST['news_id']))
		{
			$news_id = $this->_post("news_id","trim");
            if(false == $this->edit_info($news_id,$data))
            {
            	$this->ajaxError($this->err);
            }
            $this->ajaxSuccess("修改成功");
		}
		//添加新闻
		try{
			if(!D("common")->inTrans())
			{
				D("common")->startTrans();
				$trans = true;
			}
			if(M("media_news")->where("title='{$title}'")->find())
			{
				throw new Exception("该新闻已存在！请不要重复添加");
			}
			if (false == ($add_id = M("media_news")->add($data)))
			{
				throw new Exception("新闻添加失败");
			}
			if (false == $this->uploadImg("news",$add_id))
			{
				throw new Exception($this->err);
			}
			if($trans)
			{
				D("common")->commit();
				$this->ajaxSuccess("添加成功");
			}
		}catch(Exception $ex)
		{
			if($trans)
			{
				D("common")->rollback();
				$this->ajaxError($ex->getMessage());
			}
		}
	}
	public function edit_info($news_id,$data)
	{
		$data['status'] = 0;
		if(false == M("media_news")->where("id={$news_id}")->find())
		{
			$this->err = "新闻ID错误";
			return false;
		}
		unset($data['timeadd']);
		$data['lasttime'] = date("Y-m-d H:i:s",time());
		try
		{
			if(!D("common")->inTrans())
			{
				D("common")->startTrans();
				$trans = true;
			}
			if(false == M("media_news")->where("id={$news_id}")->save($data))
			{
				throw new Exception("修改内容失败");			
			}
			if(!empty($_FILES['pic_urls']['tmp_name']))
			{
				if(false == $this->uploadImg("news",$news_id))
				{
                    throw new Exception($this->err);
				}			
			}
			if($trans)
			{
				D("common")->commit();
				return true;
			}
		}catch(Exception $ex)
		{
            if($trans)
			{
				D("common")->rollback();
				$this->err = $ex->getMessage();
				return false;
			}
		}
	}
	public function news_info()
	{
		$news_id = trim($_REQUEST['id']," /t/r/n");
		$news_info = M("media_news")->field("title,pic_urls,content,intro")->where("id={$news_id}")->find();
		$news_info['s_pic_urls'] = _STATIC_."/Upload/news/s_".$news_info['pic_urls'];
		$news_info['m_pic_urls'] = _STATIC_."/Upload/news/m_".$news_info['pic_urls'];
		$news_info['pic_urls'] = _STATIC_."/Upload/news/".$news_info['pic_urls'];
		//$news_info['content'] = strip_tags($news_info['content']);
		$this->assign("info",$news_info);//dump($news_info);
		$this->display();
	}
	public function uploadImg($type,$news_id)
	{
		if($_FILES)
		{
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');
			$upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;
			$upload->thumb = true;
			$upload->saveRule = $news_id."-".time();           
			$upload->uploadReplace = true;                 
			$upload->thumbPrefix = 'm_,s_';  
			$upload->thumbMaxWidth = '400,100';
			$upload->thumbMaxHeight = '400,100';
			if(!$upload->upload()) {
				$this->err = $upload->getErrorMsg();
				return false;
			}
			$info =  $upload->getUploadFileInfo();
			$name = implode("|",array_column($info,"savename"));
			if(false == M("media_news")->where("id={$news_id}")->save(array("pic_urls"=>$name)))
			{
				$this->err = "图片保存失败";
				//删除已上传的图片
				unlink(UPLOADPATH.$type."/".$name);
				unlink(UPLOADPATH.$type."/"."m_".$name);
                unlink(UPLOADPATH.$type."/"."s_".$name);
				return false;
			}
            return true;
		}
        $this->error = "请选择上传图片";
        return false; 
	}
	public function export_news_list()
	{
		$this->export_data("news_list");
	}
	public function export_data_news_list()
	{
		$return = $this->getParams();
		$where = $return['where'];
		$lists = M("media_news")->field("id,title,keys,intro,content,is_cream,status,sort,timeadd,lasttime")
		->where($where)
		->order("status desc,sort asc,timeadd desc")
		->select();
		foreach ($lists as &$value) {
			$value['status'] = ($value['status']==1)?"使用":"禁用";
			$value['is_cream'] = ($value['is_cream']==1)?"是":"否";
		}
		$cols = array(
            array("id","新闻ID"),
            array("title","新闻标题"),
            array("keys","新闻关键字"),
            array("intro","新闻简要"),
            array("content","新闻内容"),
            array("is_cream","是否精华"),
            array("status","新闻状态"),
            array("sort","排序"),
            array("timeadd","添加时间"),
            array("lasttime","修改时间"),
			);
		return array("data"=>$lists,"cols"=>$cols);
	}
	public function export_data($type='')
	{
		$export_type = array("news_list"=>"新闻列表");
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
}
?>