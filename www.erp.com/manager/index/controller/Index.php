<?php
namespace manager\index\controller;
use think\Db;
use manager\index\controller\Common;
class Index extends Common
{
    /**
     * 主页index
     */
    public function index()
    {
        //$menu = getJsMenu();
        //$this->assign('menu',$menu);
        return $this->view->fetch();

    }
    /**
     * 系统统计
     */
    public function system() {
        $today = date('Y-m-d', time());
        $tonight = $today." 23:59:59";
        //$yesterday = date('Y-m-d', time() - 24 * 3600);
        $where = array();
        $where['time_between'] = array('between',array($today,$tonight));
        //今日注册人数
        $today_count = Db::table('user')->where(array('timeadd'=>$where['time_between']))->count();
        $countt = Db::table('user')->count();
        $this->assign('countt',$countt);
        $this->assign('today_count',$today_count);
        return $this->fetch('system');
    }

}
