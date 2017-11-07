<?php
/*
 * 产品Manager
 *      --产品增删改查
 */

namespace manager\index\model;

class ProductManager extends Product{
    protected $table = "product";
    
    protected $rule = [
        ["employer",'require','msg'=>'请填写资方','scene'=>['add','edit']],
        ["name",'require','msg'=>'请填写产品名称','scene'=>['add','edit']],
        ["name",'unique:ProductManager,employer','msg'=>'产品名称已存在','scene'=>['add','edit']],
        ["month",'require','msg'=>'请填写产品期数','scene'=>['add','edit']],
        ["repayment_type",'require','msg'=>'请填写还款方式','scene'=>['add','edit']],
        ["comp_month_rate",'require','msg'=>'请填写综合月利率','scene'=>['add','edit']],
        ["breach_rate",'require','msg'=>'请填写提前结清费率','scene'=>['add','edit']],
        ["contact_rate",'require','msg'=>'请填写合同金额利率','scene'=>['add','edit']],
        ["gpsL",'require','msg'=>'请填写GPS流量费','scene'=>['add','edit']],
        ["gpsS",'require','msg'=>'请填写gps设备费','scene'=>['add','edit']],
        ["gpsZ",'require','msg'=>'请填写gps责任险','scene'=>['add','edit']],
        
        ["comp_month_rate",'number','msg'=>'综合月利率只能为数字','scene'=>['add','edit']],
        ["breach_rate",'number','msg'=>'提前结清费率只能为数字','scene'=>['add','edit']],
        ["contact_rate",'number','msg'=>'合同金额利率只能为数字','scene'=>['add','edit']],
        ["gpsL",'number','msg'=>'GPS流量费只能为数字','scene'=>['add','edit']],
        ["gpsS",'number','msg'=>'gps设备费只能为数字','scene'=>['add','edit']],
        ["gpsZ",'number','msg'=>'gps责任险只能为数字','scene'=>['add','edit']],
        ["status",'in:0,1','msg'=>'状态不正确','scene'=>['add','edit']],
    ];
    
    /*
     * 创建产品对象
     *  @param $productName:产品名称  
     *  @param $params 对象参数
     *  @return null|object
     */
    public function createProductObject($productName,$params = []){
        $products = array_column($this->allProducts(),"class","title");
        $className = $products[$productName];
        if($productName && isset($className)){
            return (new \ReflectionClass("manager\index\model\\".$className))->newInstanceArgs((array)$params);
        }
        throw new \think\Exception("产品：$productName 对应class不存在！");
        return null;
    }
    
    /*
     * 所有资方列表
     *  
     */
    public function allEmployers(){
        return [
            ["title"=>"博金贷",],
            ["title"=>"华安保险-精融汇",],
        ];
    }

    /*
     * 所有产品名称列表
     */
    public function allProducts(){
        return [
            //["title"=>"产品名称","class"=>"类名称"],
            ["title"=> Product1724x::PRODUCT_NAME,"class"=>"Product1724x"],
            ["title"=>Product1724::PRODUCT_NAME,"class"=>"Product1724",],
            ["title"=>Product1712r::PRODUCT_NAME,"class"=>"Product1712r"],
            ["title"=>Product1712::PRODUCT_NAME,"class"=>"Product1712"],
            ["title"=>ProductXZ1712::PRODUCT_NAME,"class"=>"ProductXZ1712"],
            ["title"=>Product1703::PRODUCT_NAME,"class"=>"Product1703"],
        ];
    }
    
    
    /*
     * 新增产品
     * @param $data:产品数据
     * return boolean
     *      
     */
    public function addProduct($data){
        if($this->checkValidate($data,"add")){
            if($this->save($data)){
                return true;
            }else{
                $this->error = "新增失败";
                return FALSE;
            }
        }
        return false;
    }
    
    /*
     * 修改产品
     *  @param $data
     *  @return boolean
     */
    public function editProduct($data){
        if(empty($data["id"])){$this->error = "订单id不能为空";return false;}
        if($this->checkValidate($data,"edit")){
            if($this->isUpdate(true)->save($data)){
                return true;
            }else{
                $this->error = "修改失败";
                return FALSE;
            }
        }
        return false;
    }
    //获取产品
    public function getProductOne($id){
        // return static::get($id);
         $res=db($this->table)->find($id);
         return $res;
    }
    
    
    //验证
    public function checkValidate($data, $scene = null, $batch = null) {
        return parent::checkValidate($data, $scene, $batch);
    }

    /*
     * 产品列表
     * 
     */
    public function productList($params = []){
        $db = $this->db();
        //状态
        if(isset($this->where["status"])){
            $db->where(["status"=>$this->where["status"]]);
        }
        
        //分页
        if(isset($params["isPage"]) && $params["isPage"]==1){
            $pageDb = clone $db;
            $page = $pageDb->paginate($params["listRows"]>0?:$this->defaultRows,false,['query'=>request()->param()]);
            $this->page = $page->render();
            $db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
        }
        
        $list=$db->field("*,if(status=1,'正常','禁用') as status")->select()->toArray();
        
        $contaceModel = new ContactManager();
        foreach($list as $k=>&$v){
              $contaceModel->where["product_id"] = $v["id"];
              $v["contactList"] = $contaceModel->contactList();
        }
        return $list;
    }
    
    
    protected function breachMoney(&$list) {
        
    }

}
