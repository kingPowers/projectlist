<?php
/**
 * 百度定位，APP位置暂存
 * 
 * 
 * */
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();

if($type=='appData'){
	$this->need('province','省份');
	$province  = $this->v('province');
	$city  = $this->v('city');
	$area  = $this->v('area');
	$remark  = $this->v('remark');
	$lat = $this->v('lat');
	$lon = $this->v('lon');
	$data = array(
		'memberid'=>$member_info['id'],
		'province'=>$province,
		'city'=>$city,
		'area'=>$area,
		'lat'=>$lat,
		'lon'=>$lon,
		'remark'=>$remark,
		'timeadd'=>date("Y-m-d H:i:s"),
	);
	
	$where = array(
			'memberid'=>$member_info['id'],
			'timeadd'=>array('between',date('Y-m-d H:i:s', time() -60),date("Y-m-d H:i:s")),
	);
	if(false!=M('position')->where($where)->find())
		$this->error("ADD_EXIST","对不起，您频繁操作，一分钟内只能保存一次！");
	$add_id = M('position')->add($data);
	if(!$add_id)$this->error("ADD_ERROR",'保存失败');
	$this->data = "保存成功";
    return true;
}


$this->error('TYPE_ERR','type类型错误');
?>