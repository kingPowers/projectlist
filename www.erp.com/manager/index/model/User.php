<?php
/*
 * User 用户管理Model
 */
namespace manager\index\model;

use manager\index\model\Common;
class User extends Common{
    const SECURE_KEY = "G2K3s248waz2V574rcG6F1U0Ba36";//验证码KEY
    protected $table = "user";
    protected $rule = [
       /*
        * 格式：[字段名称，验证名称，key=>val, ... ],
        *       msg：错误的提示信息   scene:表示场景名称
        *       login:登录场景    add：新增用户场景   edit:修改用户场景
         */
        ["username",'require','msg'=>'用户名不能为空','scene'=>["login",'add','edit']],
        ["names","require",'msg'=>'真实姓名不能为空','scene'=>["edit","add"]],
        ["password","require",'msg'=>'密码不能为空','scene'=>["login","add"]],
        ["verify","require",'msg'=>'验证码不能为空','scene'=>["login"]],
        ["mobile","require",'msg'=>'手机号不能为空','scene'=>["add","edit"]],
        ["groupid","require",'msg'=>'请选择角色','scene'=>["add","edit"]],
        ["username",'unique:user','msg'=>'用户名已存在','scene'=>['add','edit']],
        ["mobile",'unique:user','msg'=>'手机号已存在','scene'=>['add','edit']],
        ["certi_number","require",'msg'=>'身份证号不能为空','scene'=>["edit","add"]],
        ["certi_number",'unique:user','msg'=>'身份证号已存在','scene'=>['add','edit']],
    ];
    
    /*
    * 登录
    *   @return 验证成返回对象 
    *           否则返回FALSE
    */
    public function login(){
        if(!captcha_check($this->data["verify"])){$this->error = "验证码错误";return false;}
        $password = $this->genPassword($this->data["password"]);
        $one = static::get(["username"=>$this->data["username"],"password"=>$password]);
        if(false==$one){$this->error = "用户名或密码错误";return false;}
        if($one->data["status"]!=1){$this->error = "您的账号被禁用了";return false;}
        $one->save(["login_count"=>$one->data["login_count"]+1,'timeupdate'=>date("Y-m-d H:i:s"),'lastip'=>$_SERVER["REMOTE_ADDR"]]);
        $this->operateRecord("用户登录",1);
        return $one;
    }
    
    
    public static function getUid(){
        return (string)session('uid');
    }
    
    public static function getUserInfo($key = null){
        if(""==$key)return session("user");
        return (string)session("user.{$key}");
    }
    
    /*
     * 明文密码加密
     */
    public function genPassword($password){
        return md5($password.static::SECURE_KEY);
    }
    /*
     * 用户列表
     * @param $params 参数数组
     * @return array|boolean
     */
    public function userList($params = []){
        $db = db($this->table." user");$where = [];
        //用户名
        if(!empty($this->where["username"])){
            $where["username"] = $this->where["username"];
        }
        //身份证号
        if(!empty($this->where["certi_number"])){
            $where["certi_number"] = $this->where["certi_number"];
        }
        //分页
         if(isset($params["isPage"]) && $params["isPage"]==1){
             $page = $db->where($where)->paginate(15,false,['query'=>request()->param()]);
             $this->page = $page->render();
             $db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
         }
        $list = $db->join("(select g.title,ga.uid from auth_group g,auth_group_access ga where ga.group_id=g.id) as rule","rule.uid=user.id","LEFT")
                ->where($where)
                ->select();
        //dump($db->getLastSql());
        return $list;
    }
}


