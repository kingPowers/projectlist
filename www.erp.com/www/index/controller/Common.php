<?php
/*
 * 公共Common
 */
namespace  www\index\controller;

class Common extends \think\Controller{
    protected function _initialize() {
        //自动加载manager项目
        \think\Loader::addNamespace("manager",ROOT_PATH."manager/");
    }
}
