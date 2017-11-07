<?php

namespace manager\index\model;

class FuyouBank extends Fuyou{
    protected $table = "fuyou_bank";
    
    /*
     * 银行列表
     * @param $params扩展参数
     * @return array
     */
    public function bankList($params = []){
        $db = $this->db();
        
        if(empty($this->order)){
            //$this->order = "bank_name='中国工商银行' desc,bank_name=''";
        }
        return $db->field("bank_code,bank_name")->select()->toArray();
    }
        
    
}
