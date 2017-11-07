<?php
/*
 * 菜单管理Model
 */
namespace manager\index\model;

use manager\index\model\Common;
class Menu extends Common{
    protected $table = "auth_rule";
    protected $rule = [
       /*
        * 格式：[字段名称，验证名称，key=>val, ... ],
        *       msg：错误的提示信息   scene:表示场景名称
        *       login:登录场景    add：新增用户场景   edit:修改用户场景
         */
        ["title",'unique:auth_rule','msg'=>'标题已存在','scene'=>['add','edit']],
      
    ];

    

}