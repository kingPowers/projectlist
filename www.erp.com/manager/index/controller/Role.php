<?php
/*
 * 角色管理
 */
namespace manager\index\controller;

use manager\index\controller\Common;
use think\Db;
use think\Request;

class Role extends Common
{
    public $list = [];
    /**
     * 角色列表
     */
    public function index()
    {
        $res_role = Get_table('auth_group', 'id,title,status,pid,rules', 'id desc');
        $this->roleGradeList($res_role, 0); //dump($this->list);
        $this->assign('res_role', $this->list);
        return $this->view->fetch();
    }
    /*
    角色分级列表
     */
    public function roleGradeList($list, $pid=0, $grade = 0, $num = 0)
    {
        foreach ($list as $key => $value) {
            if ($value['pid'] == $pid) {
                $value['title'] = str_repeat("&nbsp;&nbsp;", $grade * 2) . $value['title'];
                $this->list[]   = array_merge($value, ['grade' => $grade]);
                $this->roleGradeList($list, $value['id'], $grade + 1, strlen($value['title']));
            }
        }
    }
    /**
     * 测试
     */
    /*public function addrole_ajax()
    {
    header("Content-type:text/html;charset=utf-8");
    $request = Request::instance();
    $menu = getMenuData1();
    $res = Db::table('auth_group')->where(['pid'=>0])->select();
    $this->assign('res',$res);
    if($request->isAjax()){
    $list = Db::table('auth_group')->where(['pid'=>$_POST['pid']])->select();
    $this->assign('list',$list);
    if($list){
    $data = $this->view->fetch('Role/ajax');
    return ['status'=>1, 'info'=>'ok', 'data' => $data];
    }else{
    return ['status'=>0, 'info'=>'no', 'data' => ''];
    }

    }
    return $this->view->fetch('Role/role_ajax');
    }*/

    /**
     * 添加角色
     */
    public function addrole()
    {
        //查询菜单
        $res_add = getMenuData1();
        $this->assign('res_add', $res_add);
        $idd  = '';
        $list = Db::table('auth_group')->where('pid', '0')->field('id,title')->select();
        $this->assign('list', $list);
        $this->assign('idd', $idd);
        $request = Request::instance();
        //添加
        if ($request->isAjax()) {
            if ($request->isPost()) {
                $data['title']  = $request->param('title');
                $data['status'] = $request->param('status');
                $data['rules']  = $_POST['node'];
                $data['pid']    = $_POST['pid'];
                $res = Db::table('auth_group')->insert($data);
                (new \manager\index\model\Common())->operateRecord("添加角色【{$data['title']}】", $res, 1);
                if ($res) {
                    return ['status' => '1', 'info' => '操作成功', 'data' => ''];
                } else {
                    return ['status' => '0', 'info' => '操作失败', 'data' => ''];
                }
            } else {
                $list  = Db::table('auth_group')->where('pid', $_GET['pid'])->select();
                $rules = Db::table('auth_group')->where('id', $_GET['pid'])->field('rules')->find();
                $rules = explode(',', $rules['rules']);
                foreach ($res_add as $k => $v) {
                    foreach ($v['child'] as $key => $val) {
                        $inArray = in_array($val['id'], $rules);
                        if (!$inArray) {
                            unset($res_add[$k]['child'][$key]);
                        }
                    }
                }
                foreach ($res_add as $key => $value) {
                    if (!$value['child']) {
                        unset($res_add[$key]);
                    }
                }
                if ($list) {
                    $this->assign('list', $list);
                    $data = $this->fetch('ajax');
                    return ['status' => '1', 'rules' => $res_add, 'info' => '操作成功', 'data' => $data];
                } else {
                    return ['status' => '0', 'rules' => $res_add, 'info' => '没有下级分类', 'data' => ''];
                }
            }
        }
        return $this->fetch('role');
    }
    /**
     * 更新角色
     */
    public function editrole()
    {
        //查询菜单
        $res_role = Get_table('auth_group', 'id,title,status,pid,rules', 'id desc');
        $this->roleGradeList($res_role, 0); //dump($this->list);
        $this->assign('list', $this->list);

        $res_edit = getMenuData1();
        $this->assign('res_edit', $res_edit);
        $request = Request::instance();
        $idd     = $request->param('id');

        $rows = Db::table('auth_group')->where("id = '" . $idd . "'")->find();
        //根据当前id,查找pid
        $nowid = Db::table('auth_group')->where('id', $idd)->field('pid')->select();
        $nowid = $nowid[0]['pid'];
        $rules = Db::table('auth_group')->where('id', $nowid)->field('rules')->find();
        $rules = explode(',', $rules['rules']);
        //菜单
        foreach ($res_edit as $k => $v) {
            foreach ($v['child'] as $key => $val) {
                $inArray = in_array($val['id'], $rules);
                if (!$inArray) {
                    unset($res_edit[$k]['child'][$key]);
                }
            }
        }
        foreach ($res_edit as $key => $value) {
            if (!$value['child']) {
                unset($res_edit[$key]);
            }
        }
        $this->assign('nowid', $nowid);
        $this->assign('rules', $rules);
        $this->assign('res', $res_edit);
        $this->assign('row', $rows);
        $this->assign('idd', $idd);
        if ($request->isAjax()) {
            if ($request->isPost()) {
                $data['title']  = $request->param('title');
                $data['status'] = $request->param('status');
                $data['rules']  = $_POST['node'];
                $data['pid']    = $_POST['pid'];
                $res = Db::table('auth_group')->where("id = '" . $idd . "'")->update($data);
                //清空子类权限
                    

                (new \manager\index\model\Common())->operateRecord("编辑角色【{$data['title']}】", $res, 1);
                if ($res) {
                    return json_encode(['status' => $res, 'info' => '操作成功', 'data' => '']);
                } else {
                    return json_encode(['status' => $res, 'info' => '操作失败', 'data' => '']);
                }
            } else {
                $res_add = getMenuData1();
                if ($_GET['pid'] != 0) {
                    $rules = Db::table('auth_group')->where('id', $_GET['pid'])->field('rules')->find();
                    $rules = explode(',', $rules['rules']);
                    foreach ($res_add as $k => $v) {
                        foreach ($v['child'] as $key => $val) {
                            $inArray = in_array($val['id'], $rules);
                            if (!$inArray) {
                                unset($res_add[$k]['child'][$key]);
                            }
                        }
                    }
                    foreach ($res_add as $key => $value) {
                        if (!$value['child']) {
                            unset($res_add[$key]);
                        }
                    }

                }
                
                return ['status' => '1', 'rules' => $res_add, 'info' => '操作成功'];

            }
        }
        return $this->fetch('role');
    }

}
