<?php
//banner  url
$static = _STATIC_;
$www = _WWW_;
$type = $this->v('type');
$type = $type?$type:'banner';
if($type=='banner'){
	$data_url = array(
			//banner-图片url
			////集卡活动
			// array(
			// 		'banner'=>$static.'/2015/index/image/xy@2x.jpg',
			// 		'banner_url'=>$www.'/activitycollectcard/index/?token=',
			// 		'state' => '0',//是否需要登陆  0：需要  1：不需要
			// ),
			//闪电周转
			// array(
			// 		'banner'=>$static.'/2015/index/image/zz@1.png',
			// 		'banner_url'=>"https://sdzz-h5-rt.fqgj.net?channel=lyrsdzz0718hd",
			// 		'state' =>'1',
			// ),
			// 免息活动
			// array(
			// 		'banner'=>$static.'/2015/index/image/mx@2x.jpg',
			// 		'banner_url'=>$www.'/credit/xjdlc/?token=',
			// 		'state' => '0',//是否需要登陆  0：需要  1：不需要
			// ),
			// 车险活动
			array(
					'banner'=>$static.'/2015/newindex/image/baoxian@3.jpg',
				    'banner_url'=>$www.'/Activity/insurance',
					'state' => '1',
			),
			//保险分期
			array(
					'banner'=>$static.'/2015/index/image/zxbx@2x.jpg',
				    'banner_url'=>$www.'/Carinsurance/apply?jieba=carinsurance&token=',
					'state' => '0',
			),
			//博金贷
			array(
					'banner'=>$static.'/2015/index/image/zxbjd@2x.jpg',
				    //'banner_url'=>$www.'/Index/bojin',
                                    'banner_url'=>'https://api.ecreditpal.cn/naked/app/index.html?partner=jiebaapp',
                                    //'banner_url'=>"https://www.baidu.com/index.html?partner=借吧APP",
					'state' => '1',
					'record'=>'博金贷',//是否记录点击次数及记录名称
			),
			//邀请好友-赠送车友贷券
			array(
                                    'banner'=>$static.'/2015/newindex/image/banner6.jpg',
				    'banner_url'=>$www.'/member/activity_share?jieba=credityq&token=',
                                    'state' => '0',
			),
			//车友贷
			array(
					'banner'=>$static.'/2015/newindex/image/banner.jpg',
					'banner_url'=>$www.'/credit/xjdlc/?token=',
					'state' => '0',//是否需要登陆  0：需要  1：不需要
			),
			//招商加盟
			array(
					'banner'=>$static.'/2015/newindex/image/banner3.jpg',
					'banner_url'=>$www.'/index/zhao',
					'state' => '1',
			),
	);
	$ac_map = [
		'status' => 1,
		'starttime' => ['ELT',date("Y-m-d H:i:s",time())],
		'endtime' => ['EGT',date("Y-m-d H:i:s",time())],
                "banner_url"=>["neq",""],
	];
	$ac_banner = getAcBanner($ac_map);
	foreach ($ac_banner as $value) {
		if ($value['banner_url'] != 'ac_banner_09/activity_91721506480900_0.jpg') {
				$tmp = [
					'banner' => $static."/Upload/activity/".$value['banner_url'],
					'banner_url' => $www.'/credit/xjdlc/?token=',
					'state' => '0',
				];
				array_unshift($data_url,$tmp);
		}		
	}
	//$data_url[] = M("activity_promote")->getLastSql();
	$this->data = $data_url;
	return true;
}elseif($type=='buy_borrow'){
	$data_url = array(
			'borrow_apply'=>$www.'/borrow/borrow_flow',//车贷宝介绍
			'buy_apply'=>$www.'/buy/buy_flow',//车租宝介绍
			'pinggu'=>$www.'/Pinggu/index?token=',//爱车估价
			'jieba'=>$www.'/member/jieba',//公司简介
			'sign'=>$www."/member/sign?token=",//签到
			'zhao'=>$www.'/index/zhao',//招商加盟
			'activity'=>$www."/member/activity?token=",//活动中心
			'agreement'=>$www."/member/agreement",//注册协议
			'agent'=>$www.'/agent/order_process',//金牌经纪人-甩单流程
			'xjdlc'=>$www.'/credit/xjdlc',//车友贷-甩单流程
			
	);
	
	$this->data = $data_url;
	return true;
}elseif($type=='more'){
	//更多
	$data_url = array(
			'activity'=>$www."/member/activity?token=",//活动中心
			'jieba'=>$www.'/member/jieba',//公司简介
			'aboutpartner'=>$www."/member/aboutpartner",//合作伙伴
			'aboutcontact'=>$www."/member/aboutcontact",//联系我们
			'helpcenter'=>$www."/member/helpcenter",//帮助中心
				
	);
	
	$this->data = $data_url;
	return true;
}elseif($type=='credit'){
	//信用贷
	$data_url = array(
			'help'=>$www.'/credit/help',//常见问题,需要传递token
			'creditagreement'=>$www."/credit/creditagreement?money=",//个人借款协议，  money的值为借款金额，需要拼接到后面,需要传递token
			'creditagreement2'=>$www."/credit/creditagreement2?money=",//居间服务协议，  money的值为借款金额，需要拼接到后面,需要传递token
			'creditagreement4'=>$www."/credit/creditagreement4?money=",//员工承诺书协议，  money的值为借款金额，需要拼接到后面,需要传递token
			'creditagreement5'=>$www."/credit/agree_supple?money=",//员工承诺书协议，  money的值为借款金额，需要拼接到后面,需要传递token
				
	);
	
	$this->data = $data_url;
	return true;
}
function getAcBanner($map = [])
{
	return M('activity_promote')->field('banner_url')->where($map)->select();
}
?>