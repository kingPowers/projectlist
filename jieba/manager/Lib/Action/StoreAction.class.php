<?php
/**
 * Description of CommonAction
 * 门店管理
 */
class StoreAction extends CommonAction {
	private $_ids = array('admin'=>'1','customer'=>'2','store'=>'3');
	//门店管理首页
	public function index(){
		$p = $this->_get('p', 'intval', 1);
        $params = "";
		if (!empty($_REQUEST['name'])) {
			$params .= "/index/{$_REQUEST['name']}";
			$map['name'] = array('like',"{$_REQUEST['name']}");//"name like '%{$_REQUEST['name']}%'";
		}
		if (!empty($_REQUEST['status'])) {
			$params .= "/status/{$_REQUEST['status']}";
			$map['type'] = $_REQUEST['status'];
		}
		$count = M('store')->where($map)->count();
        //var_dump(M()->getLastSql());exit;
       
		if ($count) {
			$this->page['count'] = $count;
			$this->page['no'] = $p;
			$this->page['total'] = ceil($count / $this->page['num']);
			if($this->page['total']<$p){
				$this->page['no'] = 1;
			}
			$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
			$list  = M('store')->where($map)->order('sort desc,id desc')->limit($limit)->select();
		}
		$this->setPage("/Store/index{$params}/p/*.html");
		$this->assign('list', $list);
		$this->display();
	}
	//添加门店
	 public function add_store(){
		if($_POST && !empty($_POST['data']['name'])){
			if(M('store')->where("name='{$_POST['data']['name']}' and  type='{$_REQUEST['status']}'")->find()){
				$this->error("'{$_POST['data']['name']}'此门店已被添加过了！");
			}
			if(empty($_POST['data']['name'])){
				$this->error("请填写门店名称");
			}
			if(empty($_POST['data']['manager'])){
				$this->error("请填写门店经理账号");
			}
			if(M('store')->add(array('name'=>$_POST['data']['name'],'sort'=>$_POST['data']['sort'],'type'=>$_REQUEST['status'],'status'=>1,'manager'=>$_POST['data']['manager'],'lasttime'=>date("Y-m-d H:i:s")))){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
		}
		$this->assign('username',M('user')->where("status=1 and groupid='{$this->_ids['store']}'")->order("timeadd desc")->select());
		$this->display();
	}
	//修改门店名称
	public function edit_store(){
		if($_POST && !empty($_POST['data']['name'])){
			if(M('store')->where("name='{$_POST['data']['name']}' and id!='{$_POST['data']['id']}' and  type='{$_REQUEST['status']}'")->find()){
				$this->error("'{$_POST['data']['name']}'门店已被添加过了！");
			}
			if(empty($_POST['data']['name'])){
				$this->error("请填写门店名称");
			}
			if(empty($_POST['data']['manager'])){
				$this->error("请填写门店经理账号");
			}
			if(M('store')->where("id='{$_POST['data']['id']}'")->save(array('name'=>$_POST['data']['name'],'sort'=>$_POST['data']['sort'],'manager'=>$_POST['data']['manager'],'status'=>$_POST['data']['status'],'lasttime'=>date("Y-m-d H:i:s")))){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
		}
		$this->assign('store',M('store')->where("id='{$_GET['id']}'")->find());
		$this->assign('username',M('user')->where("status=1 and groupid='{$this->_ids['store']}'")->order("timeadd desc")->select());
		$this->display();
	}
    /*
  * //门店状态审核zwd
  */
    public function storeAudo() {
        $id = $this->_post('id');
        $status = $this->_post('status');
        //var_dump($id,$status);exit;
        $result = M('store')->where(array('id' => $id))->save(array('status' => $status));
        if ($result === false) {
            $this->error('处理失败');
        }
        $this->success('处理成功');
    }
}
