<?php

/* 
 * 菜单管理
 */
namespace manager\Index\controller; 
use manager\index\controller\Common;
use think\Db;
use think\Request;
class Menu extends Common {
     
    //初始化 公共函数获取菜单
    protected function _initialize() {
        $this->assign('list', getMenuData1());
    }

    public function index(){
        return $this->fetch();
    }
    
    //数据添加
    public function add(){
        if(Request::instance()->isPost()){
            $menu=new \manager\index\model\Menu();
              $data=$_POST;
              if($data['pid']){
               $data['name'] = $data['module'] . '-' . $data['action'];
              }else{
                $data['name'] = $data['module'];
              }
            if($menu->where('name',$data['name'])->find()){
                return ['status'=>0,'msg'=>'此操作已存在,请重写Module,action'];
            }
            if($menu->checkValidate($data,'add')){
                 if($menu->allowField(true)->save($data)){
                    return ['status'=>1,'msg'=>'操作成功'];
                 }
            }else{
                return ['status'=>0,'msg'=>$menu->getError()];
            }
        }      
        return $this->fetch();
    }
    
    //编辑数据
    public function edit($id){
        if(Request::instance()->isPost()){
            $menu=new \manager\index\model\Menu();
            $data=$_POST;
            if($data['pid']){
               $data['name'] = $data['module'] . '-' . $data['action'];
              }else{
                $data['name'] = $data['module'];
              }
              $map['name']=$data['name'];
              $map['id']=array('neq',$data['id']);
              $findName=$menu->where($map)->find();
               if($findName){
                return ['status'=>0,'msg'=>'此操作已存在,请重写Module,action'];
            }
            if($menu->checkValidate($data,'edit')){
                 if($menu->allowField(true)->save($data,$data['id'])){
                    return ['status'=>1,'msg'=>'操作成功'];
                 }
            }else{
                return ['status'=>0,'msg'=>$menu->getError()];
            }
        }
        $menu = Db::table('auth_rule')->where("id={$id}")->find();
        if(stripos($menu['name'],'-')){ 
            $m1=explode('-', $menu['name']);
            $menu['module']=$m1[0];
            $menu['action']=$m1[1];
        }else{
             $menu['module']=$menu['name'];
             $menu['action']='';
        }  
        $this->assign('pid', $menu['pid'] ? $menu['pid'] :0);
        $this->assign('menu', $menu);
        return $this->fetch();
    }

    //菜单排序
    public function sort() {
        $string = $_POST['string'];
        $sort = explode('|', $string); 
        foreach ($sort as $var) {
            list($id, $sort) = explode(':', $var);
            Db::table('auth_rule')->where("id={$id}")->update(array('sort' => $sort));
        }
      return  ['status'=>1, 'msg'=>'修改成功'];
    }

   
}
