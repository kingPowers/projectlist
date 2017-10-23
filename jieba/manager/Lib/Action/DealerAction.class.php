<?php
/**
 * Description of CommonAction
 * 经销商管理
 */
class DealerAction extends CommonAction {
	//经销商首页
	public function index(){
		$p = $this->_get('p', 'intval', 1);
		if (!empty($_REQUEST['name'])) {
			$params .= "/name/{$_REQUEST['name']}";
			$map['name'] = array('like',"%{$_REQUEST['name']}%");
		}
		$count = M('dealer')->where($map)->count();
		if ($count) {
			$this->page['count'] = $count;
			$this->page['no'] = $p;
			$this->page['total'] = ceil($count / $this->page['num']);
			if($this->page['total']<$p){
				$this->page['no'] = 1;
			}
			$limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];
			$list  = M('dealer')->where($map)->order('sort desc,id desc')->limit($limit)->select();
		}
		$this->setPage("/dealer/index{$params}/p/*.html");
		$this->assign('list', $list);
		$this->display();
	}
	//添加经销商
	 public function add_dealer(){
		if($_POST && !empty($_POST['data']['name'])){
			if(M('dealer')->where("name='{$_POST['data']['name']}'")->find()){
				$this->error("'{$_POST['data']['name']}'经销商已被添加过了！");
			}
			if(M('dealer')->add(array('name'=>$_POST['data']['name'],'sort'=>$_POST['data']['sort'],'status'=>1,'lasttime'=>date("Y-m-d H:i:s")))){
				$this->success("添加成功");
			}else{
				$this->error("添加失败");
			}
		}
		$this->display();
	}
	//修改经销商
	public function edit_dealer(){
		if($_POST && !empty($_POST['data']['name'])){
			if(M('dealer')->where("name='{$_POST['data']['name']}' and id!='{$_POST['data']['id']}'")->find()){
				$this->error("'{$_POST['data']['name']}'经销商已被添加过了！");
			}
			if(M('dealer')->where("id='{$_POST['data']['id']}'")->save(array('name'=>$_POST['data']['name'],'sort'=>$_POST['data']['sort'],'status'=>$_POST['data']['status'],'lasttime'=>date("Y-m-d H:i:s")))){
				$this->success("修改成功");
			}else{
				$this->error("修改失败");
			}
		}
		$this->assign('dealer',M('dealer')->where("id='{$_GET['id']}'")->find());
		$this->display();
	}
}
