<?php
namespace manager\index\model;

class FuyouCity extends Fuyou{
    protected $table = "fuyou_city";
    
    /*
     * 省份列表
     */
    public function provinceList($params = []){
        $db = $this->db();
        if(isset($this->where["province"])){
            $db->where(["province"=>$this->where["province"]]);
        }
        if(isset($this->where["province_code"])){
            $db->where(["province_code"=>$this->where["province_code"]]);
        }
        if(!$this->order){
            $this->order = "province='江苏省' desc,province='浙江省' desc,province='山西省' desc";
        }
        return $db->field("distinct  province_code,province")->order($this->order)->select()->toArray();
    }
    /*
     * 城市列表
     */
    public function cityList($params = []){
        $db = $this->db();
        if(isset($this->where["province"])){
            $db->where(["province"=>$this->where["province"]]);
        }
        if(isset($this->where["province_code"])){
            $db->where(["province_code"=>$this->where["province_code"]]);
        }
        
        return $db->field("city_code,city")->order($this->order)->select()->toArray();
    }
}
