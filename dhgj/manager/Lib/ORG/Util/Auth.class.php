<?php
class Auth{
    static protected $config = array();
	/*权限检测
	* $name 字符串或数组用逗号分割
	* $uid 认证的用户id
	* $relation 若name为数组,当$relation=='or'只要数组中有一个条件通过则通过，否则需要全部条件通过
	*/
    static public function check($name, $uid, $relation='or') {
	    self::$config = C('AUTH_CONFIG');
        if (!self::$config['AUTH_ON'])
            return true;
        $authList = self::getAuthList($uid);
        if (is_string($name)) {
            if (strpos($name, ',') !== false) {
                $name = explode(',', $name);
            } else {
                $name = array($name);
            }
        }
        $list = array();
        foreach ($authList as $val) {
            if (in_array($val, $name))
                $list[] = $val;
        }
        if ($relation=='or' and !empty($list)) {
            return true;
        }
        $diff = array_diff($name, $list);
        if ($relation=='and' and empty($diff)) {
            return true;
        }
        return false;
    }
    //获得权限列表
    static protected function getAuthList($uid) {
        static $_authList = array();
        if (isset($_authList[$uid])) {
            return $_authList[$uid];
        }
        if(isset($_SESSION['_AUTH_LIST_'.$uid])){
            return $_SESSION['_AUTH_LIST_'.$uid];
        }
        $groups = self::getGroups($uid);
        $ids = array();
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        if (empty($ids)) {
            $_authList[$uid] = array();
            return array();
        }
        $map=array('id'=>array('in',$ids),'status'=>1);
        $rules = M()->table(self::$config['AUTH_RULE'])->where($map)->select();
        $authList = array();
        foreach ($rules as $r)
           $authList[] = $r['name'];
        $_authList[$uid] = $authList;
        if(self::$config['AUTH_TYPE']==2){
            $_SESSION['_AUTH_LIST_'.$uid]=$authList;
        }
        return $authList;
    }
	//获得用户组
    static protected function getGroups($uid) {
        static $groups = array();
        if (isset($groups[$uid]))
            return $groups[$uid];
        $user_groups = M()->table(self::$config['AUTH_GROUP_ACCESS'] . ' a')->where("a.uid='$uid' and g.status='1'")
			->join(self::$config['AUTH_GROUP']." g on a.group_id=g.id")->select();
        $groups[$uid]=$user_groups?$user_groups:array();
        return $groups[$uid];
    }
}
