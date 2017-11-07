<?php
/*
 * 用户管理
 */
namespace manager\index\controller;

use manager\index\controller\Common;
use manager\index\model\UserOperate;

class User extends Common{
    /*
     * 用户列表
     */
    public function index(){
        $user = new \manager\index\model\User();
        $user->where["username"] = request()->get('username');
        $this->assign('list', $user->userList(['isPage'=>1]));
        $this->assign("page",$user->page);
        return $this->view->fetch();
    }
    /*
     * 新增用户
     */
    public function add(){
        $user = isset($_REQUEST["id"]) && !empty($_REQUEST["id"])? \manager\index\model\User::get($_REQUEST["id"]):new \manager\index\model\User();
        if($_POST && !empty($_POST["sub"])){
            if($user->checkValidate($_POST,"add")){
                $_POST["password"] = $user->genPassword($_POST["password"]);
                $_POST["timeadd"] = date("Y-m-d H:i:s");
                $_POST["regip"] = $_SERVER["REMOTE_ADDR"];
                $result = $user->allowField(true)->save($_POST);
                (new \manager\index\model\Common())->operateRecord("新增用户{$_POST['username']}】",$result,1);
                if($result){
                    db("auth_group_access")->insert(["uid"=>$user->getData("id"),"group_id"=>$_POST["groupid"]]);
                    $this->success("操作成功");
                }else{
                    $this->error($user->getError());    
                }
            }else{
                $this->error($user->getError());
            }
        }
        if(empty($data = $user->getData())){
            $data = array_fill_keys($user->getField(),"");
        }
        $role = new \manager\index\model\Role();
        $this->assign('data',$data);//dump($role->roleList());exit;
        $this->assign("roleList",$role->roleList());
        return $this->view->fetch();
    }
    
    public function edit(){
        $uid = $_REQUEST["id"];
        if(empty($uid))$this->redirect ("user/index");
        $user =\manager\index\model\User::get($uid);
        if($_POST && !empty($_POST["sub"])){
            if($user->checkValidate($_POST,"edit")){
                if(!empty($_POST["password"])){
                     $_POST["password"] = $user->genPassword($_POST["password"]);
                }else{
                    unset($_POST["password"]);
                }
                $_POST["timeupdate"] = date("Y-m-d H:i:s");
                $result = $user->allowField(true)->save($_POST,$uid);
                (new \manager\index\model\Common())->operateRecord("编辑用户{$_POST['username']}】",$result,1);
                if($result){
                    db("auth_group_access")->where(["uid"=>$uid])->update(["group_id"=>$_POST["groupid"]]);
                    $this->success("操作成功");
                }else{
                    $this->error($user->getError());    
                }
            }else{
                $this->error($user->getError());
            }
        }
        $role = new \manager\index\model\Role();
        $this->assign('data',$user->getData());
        $this->assign("roleList",$role->roleList());
        return $this->view->fetch('add');
    }
    public function recordList()
    {
        $operate = new UserOperate();
        $uid = $this->request->get('uid');
        $where['userid'] = $uid;
        $where['starttime'] = $this->request->get("starttime");
        $where['endtime'] = $this->request->get("endtime");
        $list = $operate->getOperateList($where,['isPage'=>1]);//dump($list);
        $this->assign("list",$list);
        return $this->view->fetch();
    }
}
