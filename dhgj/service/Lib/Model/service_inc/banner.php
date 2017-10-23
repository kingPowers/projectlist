<?php
/*
 网站首页banner图
 */
$static = _STATIC_;
$www = "http://daiwww.lingqianzaixian.com";
$this->need('type','类型');
$type = $this->v('type');
if($type == "banner")
{
	$data_url = array(
			//banner-图片url
			//拖车订单
			'0'=>array(
					'banner'=>$static.'/2015/newindex/image/banner1.jpg',
					'banner_url'=>$www.'/index/index/?token=',
			)		
	);
	$this->data = $data_url;
	return true;
}
?>