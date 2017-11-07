<?php
/*
 * 客户个人信息管理
 * 
 */
namespace manager\index\model;
use manager\index\model\Common;
class Lender  extends Common{
    protected $table="lender";
    
    protected $rule = [
        ["sex",'require','msg'=>'请选择性别','scene'=>['add','edit']],
        ["sex",'in:男,女','msg'=>'性别不正确','scene'=>['add','edit']],
        ["age",'require','msg'=>'年龄不能为空','scene'=>['add','edit']],
        ["age",'between:18,70','msg'=>'年龄应在18到70岁之间','scene'=>['add','edit']],
        ["marriage_status",'require','msg'=>'请选择婚姻状况','scene'=>['add','edit']],
        ["education",'require','msg'=>'请选择学历','scene'=>['add','edit']],
        ["koseki_address",'require','msg'=>'请填写户籍地址','scene'=>['add','edit']],
        ["live_address",'require','msg'=>'请填写现居住地址','scene'=>['add','edit']],
        
        ["live_info2",'require','msg'=>'请填写借款人居住本地年份','scene'=>['add','edit']],
        
        ["house",'require','msg'=>'请选择房产类别','scene'=>['add','edit']],
        ["career_info1",'require','msg'=>'请选择单位性质','scene'=>['add','edit']],
        ["career_info2",'require','msg'=>'请填写工作单位','scene'=>['add','edit']],
        ["career_info3",'require','msg'=>'请填写所属行业','scene'=>['add','edit']],
        ["career_info4",'require','msg'=>'请填写职位','scene'=>['add','edit']],
        ["career_info5",'require','msg'=>'请选择工作时间','scene'=>['add','edit']],
        ["career_info8",'require|float','msg'=>'请填写月收入','scene'=>['add','edit']],
        ["career_info9",'require|float','msg'=>'请填写其他收入','scene'=>['add','edit']],
        ["career_info10",'require|float','msg'=>'请填写每月总收入','scene'=>['add','edit']],
        
        ["contact_info1",'marriage:modelcustom-\manager\index\model\Lender','msg'=>'请填写配偶姓名','scene'=>['add','edit']],
        ["contact_info2",'marriage:modelcustom-\manager\index\model\Lender','msg'=>'请填写配偶关系','scene'=>['add','edit']],
        ["contact_info3",'marriage:modelcustom-\manager\index\model\Lender','msg'=>'请填写配偶电话','scene'=>['add','edit']],
        ["contact_info4",'require','msg'=>'请填写直系亲属姓名','scene'=>['add','edit']],
        ["contact_info5",'require','msg'=>'请填写直系亲属关系','scene'=>['add','edit']],
        ["contact_info6",'relativeMobile:modelcustom-\manager\index\model\Lender','msg'=>'','scene'=>['add','edit']],
        ["contact_info10",'require','msg'=>'请选择家人是否知晓借款','scene'=>['add','edit']],
        ["contact_info11",'require','msg'=>'请选择客户类型','scene'=>['add','edit']],
    ];
    public function marriage ($value,$rule,$data,$type) 
    {
        $marriage_info = ['contact_info1'=>'配偶姓名','contact_info2'=>'配偶关系','contact_info3'=>'配偶联系电话'];
        if (($data['marriage_status'] == '已婚') && $type == 'contact_info3' && (preg_match('/^1[0-9]{10}$/', $value)==false)) {
            return '配偶联系电话格式错误';
        }
        if (($data['marriage_status'] == '已婚') && empty($value))
        {
            return $marriage_info[$type]."不能为空";
        } elseif (($data['marriage_status'] != '已婚') && !empty($value)) {
            return $marriage_info[$type]."不能填写";
        } else {
            return true;
        } 
    }
    public function relativeMobile ($value,$rule)
    {
        if(preg_match('/^1[0-9]{10}$/', $value)==false){
            return '直系亲属手机格式不正确';
        }
        return true;
    }
    /*
     * 添加订单
     * @param $data:待添加的数据
     * @return boolean
     *              eg.  $lenderModel = new Lender();
     *                   if($lenderModel->addLender($_POST))$this->sucess('新增成功');
     *                   else  $this->error($lenderModel->getError());
     */
    public function addLender($data){
        $loanModel = new Loan();
        if($this->checkMyValidate($data,"add") && $loanModel->checkMyValidate($data,"add")){
            $this->autoComplateData($data);
            $this->db()->startTrans();
            if(!$loanModel->allowField(true)->save($data)){
                $this->db()->rollback();
                $this->error="新增失败(101)";
                return false;
            }
            $data["loanid"] = $loanModel->getData("id");
            if(!$this->allowField(true)->save($data)){
                $this->db()->rollback();
                $this->error="新增失败(102)";
                return false;
            }
            $this->operateRecord("新增贷款申请表【{$data["loanid"]}】",true);
            $this->db()->commit();
            return $data['loanid'];
        }else{
            $this->error = $loanModel->error?$loanModel->error:$this->error;
            return false;
        }
    }
    
    /*
     * 修改订单
     * @param $data:待修改的数据
     * @return boolean
     *              eg.  $lenderModel = new Lender();
     *                   if($lenderModel->editLender($_POST))$this->sucess('修改成功');
     *                   else  $this->error($lenderModel->getError());
     */
    public function editLender($data){
        if(empty($data["id"])){$this->error = "订单id不能为空";return false;}
        $loanModel = Loan::get($data["id"]);
        $lenderModel = static::get(["loanid"=>$data["id"]]);
        if(false==$loanModel || false==$lenderModel){$this->error = "订单id错误";return false;}
        if($loanModel->checkMyValidate($data,"edit") && $lenderModel->checkMyValidate($data,"edit")){
            $this->autoComplateData($data);
            $this->db()->startTrans();
            if(!$loanModel->allowField(true)->save($data)){
                $this->db()->rollback();
                $this->error="修改失败(101)";
                return false;
            }
            $loanid = $data['id'];
            $data['id'] = $lenderModel->id;
            if(!$this->allowField(true)->save($data,["loanid"=>$loanid])){
                $this->db()->rollback();
                $this->error="修改失败(102)";
                return false;
            }
            $this->operateRecord("修改贷款申请表【{$data['id']}】",true);
            $this->db()->commit();
            return $loanid;
        }else{
            $this->error = $loanModel->error?$loanModel->error:$lenderModel->error;
            return false;
        }
    }
    
   
    //校验数据
    public function checkMyValidate(&$data, $scene = null, $batch = null) {
        if(isset($data["house"]) && $data["house"]=="租用房"  && empty($data["house1"])){
            $this->error = "请填写租用房的每月租金";
            return false;
        }
        $data["author"] = User::getUserInfo("names");
        return parent::checkValidate($data, $scene, $batch);
    }
    /*
     * 处理form数据
     */
    private function autoComplateData(&$data){
        //起始居住时间  时间、居住年份、亲属人数
        $live = [];
        for($i=1;$i<=3;$i++){
            $live["live_info{$i}"] = isset($data["live_info{$i}"])?$data["live_info{$i}"]:"";
            unset($data["live_info{$i}"]);
        }
        $data["live_info"] = json_encode($live);
        //房产类别  房产类别、每月租金
        $data["house"] = json_encode(["house"=>$data["house"],"house1"=>$data["house1"]]);
        unset($data["house1"]);
        //借款人职业信息
        $career = [];
        for($i=1;$i<=10;$i++){
            $career["career_info{$i}"] = isset($data["career_info{$i}"])?$data["career_info{$i}"]:"";
            unset($data["career_info{$i}"]);
        }
        $data["career_info"] = json_encode($career);
        //联系人信息
        $contact = [];
        for($i=1;$i<=11;$i++){
            $contact["contact_info{$i}"] = isset($data["contact_info{$i}"])?$data["contact_info{$i}"]:"";
            unset($data["contact_info{$i}"]);
        }
        $data["contact_info"] = json_encode($contact);
        unset($live,$career,$contact);
    }
}
