<?php
/*
 * Store 门店管理Model
 */
namespace manager\index\model;
use think\Db;
use manager\index\model\Common;
class Store extends Common{
    protected $table = "store";
    protected $rule = [
       /*
        * 格式：[字段名称，验证名称，key=>val, ... ],
        *       msg：错误的提示信息   scene:表示场景名称
        *       add：新增用户场景   edit:修改用户场景
         */
        ["name",'require','msg'=>'门店名不能为空','scene'=>['add','edit']],
        ["number",'require','msg'=>'门店编码不能为空','scene'=>['add','edit']],
        ["number",'unique:store','msg'=>'门店编码以存在','scene'=>['add','edit']],
        ["name",'unique:store','msg'=>'门店名已存在','scene'=>['add','edit']],
    ];
    /**
     * 门店列表
     * @param $params 参数数组
     * @return array|boolean
     */
    public function storeList($params = []){
        $db = db($this->table." store");$where = [];
        //门店id
        if(!empty($this->where["id"])){
            $where["id"] = $this->where["id"];
        }
        //门店名
        if(!empty($this->where["name"])){
            $where["name"] = $this->where["name"];
        }
         //门店编码
        if(!empty($this->where["number"])){
            $where["number"] = $this->where["number"];
        }
        //分页
        if(isset($params["isPage"]) && $params["isPage"]==1){
            $pageDb = clone $db;
            $page = $pageDb->paginate(15,false,['query'=>request()->param()]);
            $this->page = $page->render();
            $db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
        }
        $list = $db->where($where)->select();
        return $list;
    }
}



