<?php
/*
新闻媒体
 */
class MediaAction extends CommonAction
{
	private $page_num = 3;
	public function index()
	{
		$params = array();
		$params['_cmd_'] = "media_news";
		$params['type'] = "mediaNews";
		$params['page'] = $_REQUEST['p']?$_REQUEST['p']:1;
		$params['number'] = $this->page_num;
		$service_res = $this->service($params);
		$list = $service_res['dataresult']['list'];
		//dump($service_res);
		$count = $service_res['dataresult']['count'];
		$this->page['count'] = $count;
        $this->page['no'] = $this->_get('p', 'intval', 1);
        $this->page['num'] = $this->page_num;
        $this->page['total'] = ceil($count / $this->page['num']);
		foreach ($list as &$value) {
			$value['intro'] = (mb_strlen($value['intro'])<150)?$value['intro']:(mb_substr($value['intro'],0,150,'utf-8')."...");
		}
		//dump($this->page);
		$this->set_pages("/media/index/p/*");
		$this->assign("news_list",$list);
		$this->display();
	}
	public function news()
	{
		$params = array();
		$params['_cmd_'] = "media_news";
		$params['type'] = "mediaNewsInfo";
		$params['news_id'] = $_REQUEST['id']?$_REQUEST['id']:1;
		$service_res = $this->service($params);
		//dump($service_res);
		$news_info = $service_res['dataresult']['newsInfo'];
		//dump($news_info);
		$this->assign("info",$news_info);
		$this->display();
	}
}
?>