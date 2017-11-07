<?php
namespace manager\index\model;

use think\Db;
/**
* 
*/
class UserOperate extends Common
{
	protected $table = "user_operate";
	protected $createTime = 'timeadd';
	protected $updateTime = 'lasttime';
	protected $autoWriteTimestamp = 'datetime';
	public $page;
	public function getOperateList ($where = [],$params = '')
	{
		$db = Db::table($this->table)->alias('uo');
		$db->join("user u","u.id=uo.userid",'LEFT');
		//开始时间
		if (isset($where['starttime']) && !empty($where['starttime'])) {
			$db->where('uo.timeadd','>=',$where['starttime']);
		}
		//结束时间
		if (isset($where['endtime']) && !empty($where['endtime'])) {
			$db->where('uo.timeadd','<=',$where['endtime']);
		}
		//用户id
		if (isset($where['userid']) && !empty($where['userid'])) {
			$db->where('uo.userid',$where['userid']);
		}
		if ($params['isPage'] == 1) {
			$pageDb = clone $db;
			$page = $pageDb->paginate(15,false,['query'=>request()->param()]);
			$this->page = $page->render();
			$db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
		}
		$status_name = "case uo.status when 1 then '成功' when 0 then '失败' end as status_name";
		$list = $db->field("user_operate.*,user.username,{$status_name}")->order('timeadd desc')->select();
		return collection($list)->toArray();
	}
}