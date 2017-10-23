<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 权限菜单
 * @author Nydia
 */
class MenuAction extends CommonAction {

    private $_M = array();

    //初始化
    protected function _initialize() {
        $this->_M = M('AuthRule');
        $this->assign('list', D('Common')->getMenuData());
    }

    public function add() {
        $this->display('menu');
    }

    public function edit() {
        $id = $this->_get('id', 'intval');
        $menu = $this->_M->where("id={$id}")->find();
        list($menu['module'], $menu['action']) = explode('-', $menu['name']);
        $this->assign('pid', $menu['pid'] ? $menu['pid'] :0);
        $this->assign('menu', $menu);
        $this->display('menu');
    }

    //添加数据
    public function dataToDb() {
        $mid = $this->_post('mid', 'intval');
        $data['pid'] = $this->_post('pid');
        $data['title'] = $this->_post('title');
        $data['module'] = $this->_post('module');
        $data['type'] = $this->_post('type', 'intval', 1);
        $data['status'] = $this->_post('status', 'intval', 0);
        foreach ($data as $key=>$val) {
            if ($val === '') {
                $this->error('信息不完整');
                exit;
            }
        }
        if ($data['pid']) {
            $data['action'] = $this->_post('action');
            if (empty($data['action'])) {
                $this->error('请填写Action');
            }
            $data['name'] = $data['module'] . '-' . $data['action'];
        } else {
            $data['name'] = $data['module'];
        }
        unset($data['module'], $data['action']);
        $F = false;
        if (empty($mid)) {
            $F = $this->_M->add($data);
        } else {
            $F = $this->_M->where("id={$mid}")->save($data);
        }
        if ($F === false) {
            $this->error('操作失败');
        }
        $this->_reset_data();
        $this->success('操作成功');
    }

    //菜单排序
    public function sort() {
        $string = $this->_post('string');
        $sort = explode('|', $string);
        foreach ($sort as $var) {
            list($id, $sort) = explode(':', $var);
            M('AuthRule')->where("id={$id}")->save(array('sort' => $sort));
        }
        $this->_reset_data();
        $this->success('修改成功');
    }

    //更新缓存菜单
    private function _reset_data() {
        F('global_auth_rule_all', null);
        F('global_auth_rule_menu', null);
    }

}
