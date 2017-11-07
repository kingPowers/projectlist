<?php
use think\Db;
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +---------------------------------------------------------------------
// 应用公共文件
/**
 * @return false|PDOStatement|string|\think\Collection  所有公共函数
 */


//获取菜单
 function getJsMenu() {
    //$res = Db::table('auth_rule')->select();
     $where = 'pid = 0 and type = 1';
     $data = Db::table('auth_rule')->where($where)->select();
     return $data;

}
//获取菜单
 function getMenuData1($type = 'all',$groupid = 'all')
{
    $list = $return = array();
    $key = 'global_auth_rule_' . $type;

    if (empty($list)) {
        $map = array('type' => 1, 'status' => 1);
        if ($type == 'all') {
            $map = array('id' => array('gt', 0));
        }
        $list = Db::table('auth_rule')->where($map)->order('pid ASC, sort ASC')->select();
    }
    if ($type == 'menu' && ($groupid != 'all')) {
        $group_rule_str = Db::table('auth_group')->where("id={$groupid}")->find();
        $group_rule = explode(",",$group_rule_str["rules"]);
        foreach ($list as $var) {
            if ($var['pid'] == 0) {
                $return[$var['id']] = $var;
            } else {
                if (in_array($var['id'],$group_rule)) {
                    $return[$var['pid']]['child'][] = $var;
                }
            }
        }
        foreach ($return as $key => $value) {
            if (!isset($value['child']) || empty($value['child'])) {
                unset($return[$key]);
            }
        }
    } else {
        foreach ($list as $var) {
            if ($var['pid'] == 0) {
                $return[$var['id']] = $var;
            } else {
                $return[$var['pid']]['child'][] = $var;
            }
        }
    }
    return $return;
}

    /*
     * 获取所有用户
     */
    function Get_user()
    {
        $res = Db::table('user')->field('id,groupid,username,mobile,timeupdate')->order('id desc')->select();
        return $res;
    }
    /*
     * 获取任意表
     */
    function Get_table($table,$fields,$order = 'id desc')
    {
        $res = Db::table($table)->field($fields)->order($order)->select();
        return $res;
    }