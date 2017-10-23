<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 角色管理
 * @author Nydia
 */
class RoleAction extends CommonAction {

    private $_M = array();

    //初始化
    protected function _initialize() {
        $this->_M = M('AuthGroup');
    }

    //首页
    public function index() {
        $this->assign('list', $this->getRoleList());
        $this->display();
    }

    public function add() {
        $this->assign('list', D('Common')->getMenuData());
        $this->display('role');
    }

    public function edit() {
        $id = $this->_get('id', 'intval');
        $role = $this->_M->where("id={$id}")->find();
        $this->assign('rid', $id);
        $this->assign('role', $role);
        $this->assign('list', D('Common')->getMenuData());
        $this->display('role');
    }

    public function dataToDb() {
        $rid = $this->_post('rid', 'intval');
        $data['title'] = $this->_post('title');
        $data['status'] = $this->_post('status', 'intval', 0);
        $data['rules'] = $this->_post('rules');
        $F = false;
        if (empty($rid)) {
            $F = $this->_M->add($data);
        } else {
            $F = $this->_M->where("id={$rid}")->save($data);
        }
        if ($F === false) {
            $this->error('操作失败');
        }
        $this->success('操作成功');
    }

    public function getRoleList() {
        return $this->_M->order('id ASC')->select();
    }

}
