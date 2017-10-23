<?php
//最新的版本号
$newAndroidVersion = "1.9";//安卓版本号

$newIosVersion = "2.0";//IOS版本号
$type = $this->v('type');
$type = $type?$type:'android';
if($type=='android'){
	$this->need('version','版本号');
	$version = $this->v('version');
	$is_update = $newAndroidVersion<=$version?0:1;
	$this->data = array("newVersion"=>$newAndroidVersion,'is_update'=>$is_update,'force'=>0);
        M("wxmessage_log")->add(["message"=>serialize(["post"=>$_REQUEST,"resp"=>$this->data])]);
	return true;
}elseif($type=='ios'){
	$this->need('version','版本号');
	$version = $this->v('version');
	$is_update = $newIosVersion<=$version?0:1;
	$this->data = array("newVersion"=>$newIosVersion,'is_update'=>$is_update,'force'=>0);
	return true;
}

$this->error('TYPE_ERR','type类型错误');
?>