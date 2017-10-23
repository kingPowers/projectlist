<?php
define('APP_TEST',false);
return array(
	'SECURE_KEY'		=> 'V7h9TdW2r0Fce5w3Ld5280U0j3',
	'TMPL_PARSE_STRING'		=> array(
		'_WWW_'	 => 'http://wx.'.DOMAIN_ROOT,
		'_STATIC_' => 'http://static.'.DOMAIN_ROOT,
		'_GLOBAL_' => 'http://wx.'.DOMAIN_ROOT,
	//	'_QIANDUODUO_' => 'http://'.QIAN_DOMAIN_URI,
		'_OS_' => 'http://manager.'.DOMAIN_ROOT
	),
	'sitename' => '车生活',
	'SERVICE_QQ' => '4008636086',
	'SERVICE_TELEPHONE' => '4008636086',
	'SERVICE_EMAIL' => 'service@'.DOMAIN_ROOT,
	'STATION_TELEPHONE_SUB' => '400-86-360-86',
	'DATA_CACHE_TYPE' => 'Memcache',
	'MEMCACHE_HOST'   => '10.46.71.179',
	'DATA_CACHE_TIME' => '3600',
	'CARRY_COUNTER_FEE' => 2, //提现管理费
//	'CARRY_MANFEE_POINT' => 0.003, //提现手续费费率
	'DEFAULT_CARRY_FEE'=>2,
	//REDIS相关配置
	'REDIS_SERVER' => array(
		'HOST'=>'10.46.64.67',
		'PORT'=>'6379',
	),
	//缓存队列
	'REDIS_QIAN_CONFIG' => array(
		'QIAN_NAME_TENDER'=>'zhifu-tender',//标队列
		'QIAN_NAME_AUTO'=>'zhifu-auto',//自动投标队列
		'QIAN_LOAN_PREFIX'=>'loan-{loansn}',//标开启、关闭前缀
		'QIAN_LOAN_STATUS_PREFIX'=>'loan-status-{loansn}',//招标状态
	),
	//app版本相关信息
	'APP_LAST_VERSION' 		=>'1.0',
	'APP_LAST_VERSION_IOS'	=>'1.0',
	'APP_LAST_URI_ANDROID'	=>'http://static.'.DOMAIN_ROOT.'/app/apk/zhifu.apk',
	'APP_LAST_URI_IOS'		=>'http://wx.'.DOMAIN_ROOT.'/public/download_app.html',
	'APP_UPDATEDESC'		=> "初始版本",
	'APP_UPDATEDESC_IOS'	=> "部分显示细节优化",
	'APP_UPDATE_FORCE'		=> false,
	'APP_UPDATE_FORCE_IOS'	=> true,
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		'/^about$/' => 'Index/about',
		'/^protocol$/' => 'Index/protocol',
		'/^complete$/' => 'Index/complete',
		'/^baofoo_bindAccount$/' => 'Index/baofoo_bindAccount',
		'/^verifycode$/' => 'Index/verifycode',
	),
	
		'new_friend' => array(
				'startdate' => '2015-05-27 10:00:00',
				'enddate' => '2015-06-27 24:00:00',
				'benefitrate' =>'16.00',
				'threemonbrate' =>'18.00',
		),
);
?>
