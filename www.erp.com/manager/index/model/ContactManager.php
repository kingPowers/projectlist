<?php
/*
 * 合同manager-合同增删改查
 */
namespace manager\index\model;

use think\File;
class ContactManager extends Contact{
    protected $table = "contact";
    protected $rule = [
        ["id",'require','msg'=>'id不能为空','scene'=>['edit']],
        ["name",'require','msg'=>'请填写合同名称','scene'=>['add','edit']],
        ["product_id",'require','msg'=>'product_id不能为空','scene'=>['add','edit']],
        ["content",'require','msg'=>'请填写合同内容','scene'=>['add','edit']],
        ["contact_file","require","msg"=>"请上传合同附件","scene"=>["add"]],
        ["contact_file","file","msg"=>"请上传合同附件","scene"=>["add","edit"]],
        ["contact_file","fileExt:doc,docx,pdf","msg"=>"请上传word或PDF文件","scene"=>["add","edit"]],
    ];
    /*
     * 添加合同
     */
    public function addContact($data){ 
        $data["contact_file"] = \think\Request::instance()->file("contact_file");
        $data["timeadd"] = date("Y-m-d H:i:s");
        $data["lasttime"] = date("Y-m-d H:i:s");
        $data["content"] = urldecode($data["content"]);
        if($this->checkValidate($data,"add")){
            if(!$this->uploadFile($data))return false;
            if($this->allowField(true)->save($data))return true;
        }
        return false;
    }
    /*
     * 修改合同
     *   eg.
     *     $contactManager = new ContactManager();
     *     $contactManager->editContact($_POST);
     */
    public function editContact($data){
        $data["contact_file"] = \think\Request::instance()->file("contact_file");
        $data["lasttime"] = date("Y-m-d H:i:s");
        $data["content"] = urldecode($data["content"]);
        if($this->checkValidate($data,"edit")){
            if($data["contact_file"]  instanceof File){
                if(!$this->uploadFile($data))return false;
            }else{
                unset($data["contact_file"]);
            }
            if($this->allowField(true)->save($data))return true;
        }
        return false;
    }
    /*
     * 合同列表
     *  @params 扩展参数
     */
    public function contactList($params = []){
        $db = $this->db();
        //产品名称
        if(isset($this->where["product_name"])){
            $db->where(["p.name"=>$this->where["product_name"]]);
        }
        //产品id
        if(isset($this->where["product_id"])){
            $db->where(["c.product_id"=>$this->where["product_id"]]);
        }
        //合同id
        if(isset($this->where["id"])){
            $db->where(["c.id"=>$this->where["id"]]);
        }
        
        $db->table("contact c,product p");
        $db->where("c.product_id=p.id");
        $db->field("c.*");
        
        //排序
        if(isset($this->order)){
            $db->order($this->order);
        }
        //分页
        if(isset($params["isPage"]) && $params["isPage"]==1){
            $pageDb = clone $db;
            $page = $pageDb->paginate($params["listRows"]>0?:$this->defaultRows,false,['query'=>request()->param()]);
            $this->page = $page->render();
            $db->limit(($page->getCurrentPage()-1)*$page->listRows().",".$page->listRows());
        }
        return $db->select()->toArray();
    }
    
    //开始上传文件
    protected function uploadFile(&$data){
        $file = \think\Request::instance()->file("contact_file");
        if (!($file instanceof File)) {$this->error = "请选择附件合同"; return false;}
        if(false==($productModel = ProductManager::get($data["product_id"]))){
            $this->error = "产品id错误";
            return false;
        }
        
        if(isset($data["id"]) && false==($contactModel = self::get($data["id"]))){
            $this->error = "合同id错误";
            return false;
        }elseif($contactModel){
            $contactInfo = $contactModel->toArray();
        }else{
            $contactInfo = [];
        }
        $productInfo = $productModel->toArray();
        
        
        $savePath = $this->getUploadPath();
        $saveName = str_replace(" ","",$productInfo["name"].$file->getInfo("contact_file")["name"]);
        
        if($file->move($savePath,$saveName)){
            @unlink($savePath.$contactInfo["contact_file"]);
            $data["contact_file"] = $saveName;
            return true;
        }
        $this->error = $file->getError();
        return false;
    }
    //合同附件所在路径
    public function getUploadPath(){
        return (new Download())->getContactTempletePath();
    }
}
