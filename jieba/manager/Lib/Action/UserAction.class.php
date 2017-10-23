<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 后台用户管理
 * @author Nydia
 */
class UserAction extends CommonAction {

    private $_M = array();

    protected function _initialize() {
        $this->_M = M('User');
    }

    //首页
    public function index() {
        $username = $this->_get('username','trim');
        $p = $this->_get('p', 'intval', 1);

        $map = array();
        $params = '';
        if($username){
            $map[] = "username like '%{$username}%'";
            $this->assign('username', $username);
            $params .= "/username/{$username}";
        }

        $count = $this->_M->where(implode(' AND ', $map))->count();
        if ($count) {
            $this->page['no'] = $p;
            $this->page['total'] = ceil($count / $this->page['num']);
            $limit = ($this->page['no'] - 1) * $this->page['num'] . ',' . $this->page['num'];

            $list = $this->_M->where(implode(" AND ",$map))->order('id ASC')
            	 ->join("(select g.title,ga.uid from auth_group g,auth_group_access ga where ga.group_id=g.id) as rule on rule.uid=user.id")
            	 ->field("user.*,rule.*")
                ->limit($limit)
                ->select();
        }
        $this->assign('list', $list);
        $this->setPage("/User/index{$params}/p/*.html");
        $this->display();
    }

    public function add() {
        $this->assign('roleList', R('Role/getRoleList'));
        $this->display('user');
    }

    public function edit() {
        $uid = $this->_get('id', 'intval');
        $user = $this->_M->where("id={$uid}")->find();
        $this->assign('uid', $uid);
        $this->assign('user', $user);
        $this->assign('roleList', R('Role/getRoleList'));
        $this->display('user');
    }

    public function dataToDb() {
        $uid = $this->_post('uid', 'intval');
        $data['username'] = $this->_post('username');
        $data['password'] = $this->_post('password');
        $data['mobile'] = $this->_post('mobile');
        $data['groupid'] = $this->_post('groupid');
        $data['status'] = $this->_post('status', 'intval', 0);
        $E = $F = false;
        if (empty($uid)) {
            $F = $this->_M->where("username='{$data['username']}' OR mobile='{$data['mobile']}'")->select();
            if (!empty($F)) {
                $this->error('用户名或手机号已存在');
            }
            $data['password'] = D('Common')->gen_password($data['password']);
            $F = $this->_M->add($data);
            if ($F) {
                $E = M('AuthGroupAccess')->add(array('uid' => $F, 'group_id' => $data['groupid']));
            }
        } else {
            //unset($data['username'], $data['mobile']);
            $F = $this->_M->where("(username='{$data['username']}' OR mobile='{$data['mobile']}') and id!='{$uid}'")->select();
            if (!empty($F)) {
            	$this->error('用户名或手机号已存在');
            }
            
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = D('Common')->gen_password($data['password']);
            }
            $F = $this->_M->where(array('id' => $uid))->save($data);
            if ($F) {
                $E = M('AuthGroupAccess')->where(array('uid' => $uid))->save(array('group_id' => $data['groupid']));
            }
        }
        if ($F === false) {
            $this->error('操作基本信息失败');
        }
        if ($E === false) {
            $this->error('操作权限信息失败');
        }
        $this->success('操作成功');
    }

}
