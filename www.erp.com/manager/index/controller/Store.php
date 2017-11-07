<?php
/*
 * 门店管理控制器
 */
namespace manager\index\controller;

use think\Db;
use manager\index\controller\Common;
use think\Request;


class Store extends Common{
    /**
     * 门店展示
     * @return array
     */
    public function index()
    {

        $store = new \manager\index\model\Store();  //先实例化,在调用
        $list = $store->storeList();
        //检索门店
        $store->where['name'] = request()->get('name');
        $this->assign('list',$store->storeList(['isPage'=>1]));
        $this->assign("page",$store->page);
        return $this->view->fetch();

    }
    /**
     * 门店添加
     * @return array
     */
    public function addstore()
    {
        $store = new \manager\index\model\Store();
        $request = Request::instance();
        $name = $store->where['name'] = request()->param('name');
        if($request->isPost())
        {
            //dump($_POST);
           // exit;
            if($store->checkValidate($_POST,"add")){
                $data['name'] = $request->param('name');
                $data['status'] = $request->param('status');
                 $data['number'] = $request->param('number');
                $data["timeadd"] = date("Y-m-d H:i:s");
                $result = $store->allowField(true)->insert($data);
                if($result){ 
                    return json_encode(['status'=>'1','info'=>'操作成功','data'=>'']);
                }else{
                    return json_encode(['status'=>'0','info'=>'操作失败','data'=>'']);
                }
            }else{
                return json_encode(['status'=>'0','info'=>$store->getError(),'data'=>'']);
                //$this->error($store->getError(),'addstore','',1);
            }
        }
        $id = '';
        $this->assign('id',$id);
        $this->assign('name',$name);
        return $this->fetch('store');

    }
    /**
     * 门店更新
     * @return array
     */
    public function editstore()
    {
        $request = Request::instance();
        $id = $request->param('id');//点编辑的get 来过的id
        $row = \manager\index\model\Store::get($id);

        if($request->isPost()){
            if($row->checkValidate($_POST,'edit')){
                $_POST["lasttime"] = date("Y-m-d H:i:s");
                $result = $row->allowField(true)->save($_POST);
                if($result){
                    return ['status'=>'1','info'=>'操作成功','data'=>''];
                }else{
                    return ['status'=>'0','info'=>'操作失败','data'=>''];
                }
            }else{
                return ['status'=>'0','info'=>$row->getError(),'data'=>''];
                //$this->error($row->getError());
            }
        }
        $this->assign('id',$id);
        $this->assign('row',$row->getData());
        return $this->fetch('store');
    }

    public function test()
    {
        header("Content-type: text/html; charset=utf-8");
        $store = new \manager\index\model\Store();

        $table = $store->getTableName();
        return $table;
        exit;

        $store->where('status','>',1)->count();
        echo $store->getLastSql();
        echo "</br>";

        $store->save(['name'=>'彭强的门店','status'=>1],['id'=>1]);
        echo $store->getLastSql();

        exit;
        //$this->assign('num',$store);
        $this->view->fetch('test');
    }

}
