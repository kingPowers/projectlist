<?php
/*
    公共管理类
 *      所有Controller的父类
 */
namespace manager\index\controller;

use think\Controller;
class Common extends Controller {
    public function __construct() {
        parent::__construct();
        $uid = session('uid');
        if (empty($uid)) {
            $this->redirect("/publics/login");
        }
        \think\Loader::import("extend.Auth",ROOT_PATH);
        $auth = new \extend\Auth();
        $request = \think\Request::instance();
        //dump($request->controller() . '-' . $request->action());
        if(!$auth->check($request->controller() . '-' . $request->action(),$uid)){
            header("content-type:text/html;charset=utf-8");
            if ($this->request->isAjax()) {
                exit(json_encode(['info'=>"亲！您没有权限！",'status' => 0]));
            }
            exit('<div style="margin:0 auto;margin-top:200px;color:red;width:200px;height:50px;"><b>亲！您没有权限！</b></div>');
        }
    }
    public function ajaxSuccess ($msg = '',$data = '')
    {
        return ["status" => 1,"info" => $msg,'data' => $data];
    }
    public function ajaxError ($msg = '',$data = '')
    {
        return ["status" => 0,"info" => $msg,'data' => $data];
    }
    /*获取显示列表
     $base_title:全部显示的列表
     $unset_title:需要删除的列表
    */
    protected function getTitle($unset_title,$base_title = [])
    {
        if (empty($base_title))$base_title = $this->base_title;
        foreach ($base_title as $key => &$value) {
            foreach ($unset_title as $val) {
                if ($key == $val) {
                    unset($base_title[$key]);
                }
            }
        }
        return $base_title;
    }
    //获取贷款列表
    public function getLoanList($params = []) 
    {
        $where = $params['where']?$params['where']:[];
        $loan = new \manager\index\model\Loan();
        if (!empty($params['is_apply_where'])) {
            $where = array_merge($where,\manager\index\model\UserSearch::getSearch());//dump($where);
        }
        $isPage = [];
        if (empty($params['isPage'])) {
            $isPage = ['isPage'=>1];
        }
        $loan->where = $where;
        $list = $loan->loanList($isPage);
        if (empty($params['isPage'])) {
            $this->assign("page",$loan->page);
        }
        return $list;
    }
}