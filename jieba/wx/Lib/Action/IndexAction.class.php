<?php

/*
 * 智信创富金融
 */

/**
 * Description of IndexAction
 * @author Nydia
 */
class IndexAction extends CommonAction {

    public function index() {
    	import("Think.ORG.Util.wxjs");
    	$wxjs = new wxjs;
    	$signPackage = $wxjs->GetSignPackage();
    	$this->assign('signPackage',$signPackage);
    	$this->assign('isWeixin',strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false?0:1);
    	$this->assign('islogin',$this->isLogin()?1:0);
    	unset($_SESSION['from'],$_SESSION['from_index']);//标识是否首页进入到更多链接
        //车生活相关
        $params = array();
        $params['_cmd_'] = "carlife";
        $params['type'] = "list";
        $params['is_cream'] = 1;
        $service_res = $this->service($params);
        $lists = $service_res['dataresult']['assed_list'];
        $lists = empty($lists)?(array()):$lists; 
        $limit = 5;//评论个数
        $limitLists = array_slice($lists, 0,$limit);
        $memberid = $this->getMemberInfo()['memberid']?$this->getMemberInfo()['memberid']:'null';
        //最新甩单详情
        $params = array();
        $params['_cmd_'] = "goldAgent";
        $params['type'] = "orderList";
        $params['page'] = 1;
        $params['number'] = 4;
        //$num = 4;//轮巡个数
        $limit = 35;//限制显示的字数
        $service_res = $this->service($params);
        //$agent_order = array_slice($service_res['dataresult'],0,$num); 
        //dump($agent_order);         
        foreach($agent_order as &$val){
            $val['remark'] = (mb_strlen($val['remark'],'utf8')>$limit)?(mb_substr($val['remark'],0,$limit,'utf8')."....."):$val['remark'];
        }
        //首页banner
        $params = [];
        $params['_cmd_'] = 'banner';
        $params['_type_'] = 'banner';
        $res = $this->service($params);
        $this->assign("banner",$res['dataresult']);
        $this->assign("agent_order",$agent_order);
        $this->assign('memberid',$memberid);                             
        $this->assign('car_list',$limitLists);
        $this->display();
    }
    //点赞
    public function  goodpoint(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/index/index"));
        $params = array();
        $params['_cmd_'] = "carlife";
        $params['type'] = ACTION_NAME;
        $params['id'] = $_REQUEST['assed_id'];
        $params['memberid'] = $member_info['id'];
        $service_res = $this->service($params);
        if(0===$service_res['errorcode'])
            $this->success("点赞成功");
        $this->error($service_res['errormsg']);
    }
    //取消点赞
    public function canclepoint(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/index/index"));
        $params = array();
        $params['_cmd_'] = "carlife";
        $params['type'] = ACTION_NAME;
        $params['id'] = $_REQUEST['assed_id'];
        $params['memberid'] = $member_info['id'];
        $service_res = $this->service($params);
        if(0===$service_res['errorcode'])
            $this->success("取消点赞成功");
        $this->error($service_res['errormsg']);
    }
    public function locationByGPS($lng = '', $lat = ''){
	$lng = !empty($_REQUEST['lng'])?$_REQUEST['lng']:$lng;
	$lat = !empty($_REQUEST['lat'])?$_REQUEST['lat']:$lat;
	$params = array(
			'coordtype' => 'bd09ll',//GPS获取坐标，坐标类型，可选参数，默认为bd09经纬度坐标。允许的值为bd09ll、bd09mc、gcj02、wgs84。bd09ll表示百度经纬度坐标，bd09mc表示百度墨卡托坐标，gcj02表示经过国测局加密的坐标，wgs84表示gps获取的坐标。
			'location' => $lat . ',' . $lng,//纬度、经度
			'ak' => 'wZcPObxiUHlV77wuusKnUIUhsg4IntI7',
			'output' => 'json',//输出类型
			'pois' => 1,//是否显示周边
			'src'=>'借吧'
	);
	$resp = $this->async('http://api.map.baidu.com/geocoder/v2/', $params, false);
	$data = json_decode($resp, true);
	if ($data['status'] != 0)
	{
		$this->error($data['message']);
	}
	$data = array(
			'address' => $data['result']['formatted_address'],
			'province' => $data['result']['addressComponent']['province'],
			'city' => $data['result']['addressComponent']['city'],
			'street' => $data['result']['addressComponent']['street'],
			'street_number' => $data['result']['addressComponent']['street_number'],
			'city_code'=>$data['result']['cityCode'],
			'lng'=>$data['result']['location']['lng'],
			'lat'=>$data['result']['location']['lat'],
			'pois'=>$data['result']['pois']['0']['name'],
			'formatted_address'=>$data['result']['formatted_address'],
			'pc'=>$data['result']['addressComponent']['city'].$data['result']['addressComponent']['district'],
	);
	if($_REQUEST['is_ajax'])
		$this->ajaxReturn($data,'请求成功');
	return $data;
}
    
    //更新时间戳
    public function getTimes() {
        $this->ajaxReturn(1, time() * 1000, null, 'json');
    }
    //HTTP下载协议
    public function downpact() {
        $loansn = htmlspecialchars(trim($_GET['_URL_'][2]));
        $memberid = htmlspecialchars(trim($_GET['_URL_'][3]));
        if (empty($loansn) || empty($memberid)) {
            exit('Empty Info');
        }
        $Invest = A('Invest');
        $Invest->downpactdownload($loansn, $memberid);
    }
    public function testService(){

    }
    
    public function async($url, $params = array(), $encode = true, $method = 1)
    {
    	$ch = curl_init();
    	if ($method == 1)
    	{
    		$url = $url . '?' . http_build_query($params);
    		$url = $encode ? $url : urldecode($url);
    		curl_setopt($ch, CURLOPT_URL, $url);
    	}
    	else
    	{
    		curl_setopt($ch, CURLOPT_URL, $url);
    		curl_setopt($ch, CURLOPT_POST, true);
    		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    	}
    	curl_setopt($ch, CURLOPT_REFERER, '百度地图referer');
    	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_0 like Mac OS X; en-us) AppleWebKit/537.51.1 (KHTML, like Gecko) Version/7.0 Mobile/11A465 Safari/9537.53');
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$resp = curl_exec($ch);
    	curl_close($ch);
    	return $resp;
    }
    
    //招商加盟
    public function zhao(){
    	$this->display();
    }
    //banner点击记录
    public function bannerRecord ()
    {
        if (!$_POST['is_record'])$this->ajaxError();
        $record = M('banner_record');
        $info = $record->where("title='{$_POST['is_record']}'")->find();
        if (false == $info) {
            $record->add(['title'=>$_POST['is_record'],'num'=>1,'timeadd'=>date("Y-m-d H:i:s"),'lasttime'=>date("Y-m-d H:i:s")]);
        } else {
            $record->where("title='{$_POST['is_record']}'")->setInc('num');
        }
        $this->ajaxSuccess();
    }
    //博金贷
    public function bojin(){
        $this->assign("url","https://api.ecreditpal.cn/naked/app/index.html?partner=借吧app");
        $this->display();
    }

}
