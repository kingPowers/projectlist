<?php
/*
 * 角色管理
 */
namespace manager\index\model;

class Role extends Common{
    
    protected $table = "auth_group";
    
    public function roleList($params = []){
        $list =  db($this->table)->select();
        return $list;
    }
    /*
     * 查询用户所在角色
     *  @param $uid:用户id
     *  @return array
     */
    public function getUserRule($uid){
       return  (array)$this->db()->table($this->table." ag,auth_group_access aga")
                ->where("ag.id=aga.group_id")
                ->where("aga.uid",$uid)
                ->find()->toArray();
    }
}
