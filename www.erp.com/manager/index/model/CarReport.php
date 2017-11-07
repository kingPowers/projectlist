<?php
/*
 * 车辆评估管理
 */

namespace manager\index\model;

class CarReport extends Common{
    protected $table="car_report";
    
    protected $rule = [
        ["loanid",'require','msg'=>'loanid不能为空','scene'=>['add','edit']],
        ["car_number",'require','msg'=>'请填写车牌号','scene'=>['add','edit']],
        ["car_brand",'require','msg'=>'请填写品牌型号','scene'=>['add','edit']],
        ["car_vin",'require','msg'=>'请填写VIN码','scene'=>['add','edit']],
        ["car_color",'require','msg'=>'请填写车身颜色','scene'=>['add','edit']],
        ["car_fuel",'require','msg'=>'请填写燃油种类','scene'=>['add','edit']],
        ["car_gearbox",'require','msg'=>'请填写变速箱种类','scene'=>['add','edit']],
        ["other_attribute1",'require','msg'=>'请填写排量','scene'=>['add','edit']],
        ["other_attribute2",'require','msg'=>'请填写车身型式','scene'=>['add','edit']],
        ["other_attribute3",'require','msg'=>'请填写车身型式','scene'=>['add','edit']],
        ["other_attribute4",'require','msg'=>'请填写驱动型式','scene'=>['add','edit']],
        ["other_attribute5",'require','msg'=>'请填写注册日期','scene'=>['add','edit']],
        ["other_attribute6",'require','msg'=>'请填写制造日期','scene'=>['add','edit']],
        ["other_attribute7",'require','msg'=>'请填写过户次数','scene'=>['add','edit']],
        ["other_attribute8",'require','msg'=>'请填写使用性质','scene'=>['add','edit']],
        ["other_attribute9",'require','msg'=>'请填写使用里程','scene'=>['add','edit']],
        ["other_attribute10",'require','msg'=>'请填写年审日期','scene'=>['add','edit']],
        ["car_traffic1",'require','msg'=>'请选择是否有结构性事故','scene'=>['add','edit']],
        ["car_traffic2",'require','msg'=>'请选择是否有一般性事故','scene'=>['add','edit']],
        ["car_traffic3",'require','msg'=>'请选择是否有泡水','scene'=>['add','edit']],
        ["car_traffic4",'require','msg'=>'请选择是否有火烧','scene'=>['add','edit']],
        ["car_traffic5",'require','msg'=>'请选择是否有调表嫌疑','scene'=>['add','edit']],
        ["car_traffic6",'require','msg'=>'请选择是否有液体泄露','scene'=>['add','edit']],
        ["car_traffic7",'require','msg'=>'请选择是否有改色','scene'=>['add','edit']],
        ["car_traffic8",'require','msg'=>'请选择是否有改装','scene'=>['add','edit']],
        ["car_traffic9",'require','msg'=>'请选择是否有限制交易','scene'=>['add','edit']],
        ["car_traffic10",'require','msg'=>'请选择VIN钢是否原厂','scene'=>['add','edit']],
        ["car_traffic11",'require','msg'=>'请选择前挡玻璃是否原厂','scene'=>['add','edit']],
        ["car_traffic12",'require','msg'=>'请选择后挡玻璃是否原厂','scene'=>['add','edit']],
        ["car_traffic13",'require','msg'=>'请填写车险截止日期','scene'=>['add','edit']],
        ["car_traffic14",'require','msg'=>'请填写车辆违章情况','scene'=>['add','edit']],
        ["car_traffic15",'require','msg'=>'请填写车辆违章情况','scene'=>['add','edit']],
        ["car_traffic16",'require','msg'=>'请填写新车指导价','scene'=>['add','edit']],
        ["car_traffic17",'require','msg'=>'请填写发动机工况','scene'=>['add','edit']],
        ["car_traffic18",'require','msg'=>'请填写变速箱工况','scene'=>['add','edit']],
        ["car_price",'require','msg'=>'请填写评估价格','scene'=>['add','edit']],
        ["car_price",'number','msg'=>'评估价格为数字','scene'=>['add','edit']],
        ["car_price",'gt:0','msg'=>'请填写评估价格','scene'=>['add','edit']],
        ["car_appearance",'require','msg'=>'请填写评估报告','scene'=>['add','edit']],
    ];
    //初始化数据
    protected function initialize() {
        $this->auto();
        $this->autoUpdateField();
        $this->autoInsertField();
        parent::initialize();
    }
  
    /*
     * 修改评估报告
     * @param $data:评估报告数据
     * @return boolean
     */
    public function editCarReport($data){
        $carReportModel = static::get(["loanid"=>$data["loanid"]]);
        if(false==$carReportModel){$this->error = "loanid错误";return false;}
        if (empty($carReportModel->timeadd))$data['timeadd'] = date("Y-m-d H:i:s");
        $data["appraiser"] = User::getUserInfo("names");
        if($carReportModel->checkMyValidate($data,"edit")){
            $this->autoChaneData($data);
            $this->db()->startTrans();
            try {
                if(false==$carReportModel->allowField(true)->save($data)){
                    throw new \Exception("评估报告未修改成功(101)");
                }
                //更新贷款订单材料
                $loan = Loan::get($data['loanid']);
                if (!in_array('12',explode(',',$loan->material))) {
                    if (false == $loan->allowField(true)->save(['material'=>$loan->material.",12"])) {
                        throw new \Exception("订单修改失败");  
                    }
                }
                $this->operateRecord("修改评估报告【{$carReportModel->id}】",true);
                $this->db()->commit();
                return true;
            } catch (\Exception $ex) {
                $this->db()->rollback();
                $this->error = $ex->getMessage();
                return false;
            }
        }else{
            $this->error = $carReportModel->getError();
            return false;
        }
    }
    /*
     * 查询一条数据
     *  @param $loanId：订单id（loan主键）
     * @return array
     */
    public function carReportOne($loanId){
        $carReportModel = static::get(["loanid"=>$loanId]);
        if(false==$carReportModel)return [];
        $data = $carReportModel->getData();
        $this->autoChaneData($data,false);
        return $data;
    }
    
    //数据校验
    public function checkMyValidate($data,$scene){
        //if($data[""])
        return parent::checkValidate($data, $scene);
    }
    
    //自动完成，更新
    protected function autoUpdateField(){
        $this->update = [
            "lasttime"=>date("Y-m-d H:i:s"),
        ];
    }
    //自动完成，新增
    protected function autoInsertField(){
        $this->insert = [
            "timeadd"=>date("Y-m-d H:i:s"),
        ];
    }
    /*
     * 修改数据自动json化
     */
    protected function autoChaneData(&$data,$isJsonEncode = true) {
        $fields = ["other_attribute"=>["num"=>10],"car_traffic"=>["num"=>19]];
        foreach($fields as $field=>$config){
            if($isJsonEncode){
                $this->jsonEncodeData($data, $field,$config["num"]);
            }else{
                $this->jsonDecodeData($data, $field);
            }
        }
    } 
}
