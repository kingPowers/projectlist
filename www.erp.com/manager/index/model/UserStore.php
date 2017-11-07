<?php
/*
 * 客户关联门店管理
 */
namespace manager\index\model;
class UserStore  extends Common{
    protected  $table="user_store";
    
    /*
     * 获取门店列表
     *  @param $uid:用户主键（user表）
     *  @return array
     */
    public static function getStores($uid){
        return (array)(new \think\db\Query())
                ->table("user_store us,store s")
                ->where("us.storeid=s.id and us.uid='{$uid}' and s.status=1")
                ->select();
    }
}
