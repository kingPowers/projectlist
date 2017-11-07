<?php
/*
 * 用户门店关联
 */
namespace manager\index\controller;

use manager\index\controller\Common;
use think\Db;
use think\Request;

class Userstore extends Common
{
    const perline=2;//每行显示的门店个数
    /*
     * 新增用户门店关联 弹窗形式
     */
    public function add()
    {   
        $uid=$_REQUEST['uid'];
        if (Request::instance()->isPost()) {
            Db::table('user_store')->where(array('uid' => $uid))->delete();
            if(empty($_POST['chk_storeid_value'])){
                return ['status' => 1, 'msg' => '操作成功']; 
            }else{      
                $storeid = explode(',', $_POST['chk_storeid_value']);
                $count   = count($storeid);
                for ($i = 0; $i < $count; $i++) {
                    $data[$i]['uid']     = $uid;
                    $data[$i]['storeid'] = $storeid[$i];
                }
                $res = Db::table('user_store')->insertAll($data);
                if ($res) {
                    return ['status' => 1, 'msg' => '操作成功'];
                } else {
                    return ['status' => 0, 'msg' => '操作失败'];
                }
            }
        }
        $user      = Db::table('user')->field('id,username')->where('id', $uid)->find();
        $store     = Db::table('store')->field('id,name')->where('status',1)->select();
        $userstore = Db::table('user_store')->where('uid', $uid)->column('storeid');
        if ($userstore) {
            $this->assign('userstore', $userstore);
        }
        $number=count($store);
        $row=ceil($number/static::perline);
        $this->assign('row', $row);
        $this->assign('perline', static::perline);
        $this->assign('number', $number);
        $this->assign('user', $user);
        $this->assign('store', $store);
        return $this->fetch();
    }
    

}
