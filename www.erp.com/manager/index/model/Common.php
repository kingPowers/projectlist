<?php
/*
 * 所有Model的父类Model
 */
namespace manager\index\model;


class Common extends \think\Model{
    public $defaultRows = 15;
    public $where;
    public $order;
    public $page;
    public $count;
    public $fields;
    public function __construct($data = array()) {
        if(is_array($data)){
            foreach($data as $k=>$v){
                if(property_exists($this, $k)){
                    $this->$k = $v;
                }
            }
        }
        parent::__construct($data);
    }
    /*
     * 校验数据
     *  @param $data:待校验的数据
     *  @param $scene:待校验的场景名称
     *  @return boolean
     *              eg.(new User)->checkValidate($_POST,'add-edit');
     *      
     */
    public function checkValidate($data, $scene = null, $batch = null){
        $rule = $this->getRuleFromScene($scene);
        //dump($rule);
        if(empty($rule))$rule = null;
        return parent::validateData($data, $rule, $batch);
    }
    //获取数据表名称
    public function getTableName(){
        return $this->table;
    }
    //获取field属性
    public function getField(){
        if(empty($this->field)){
            $this->field = $this->db(false)->getTableInfo('', 'fields');
        }
        return $this->field;
    }
    /*
     * 根据场景名称组装验证规则
     * @return array
     *      
     */
    protected function  getRuleFromScene($scene){
        $rule = [];
        if(isset($this->rule) && !empty($this->rule)){
            foreach($this->rule as $key => $rules){
                if(in_array($scene,(array)$rules["scene"])){
                    if(!empty($rule["rule"][$rules[0]])){
                        $rule["rule"][$rules[0]].="|".$rules[1];
                    }else{
                         $rule["rule"][$rules[0]] = $rules[1];
                    }
                    if($pos = strpos($rules[1],":")){
                        $rule["msg"][$rules[0].".".substr($rules[1],0,$pos)] = $rules["msg"];
                    }else{
                        $rule["msg"][$rules[0].".".$rules[1]] = $rules["msg"];
                    }
                    
                }      
            }
        }
        return $rule;
    }
     //json编码
     protected function jsonEncodeData(&$data,$field,$num){
        $fieldData = [];
        for($i=1;$i<=$num;$i++){
            $fieldData[$field.$i] = $data[$field.$i];
            unset($data[$field.$i]);
        }
        $data[$field] = json_encode($fieldData);
        unset($fieldData);
    }
    //json解码
    protected function jsonDecodeData(&$data,$field){
        $data = array_merge($data,(array)json_decode($data[$field],true));
        unset($data[$field]);
    }
    /*
    用户操作记录
    @param:$remark:操作内容；
    @param:$status:操作结果(默认为false)；true,false；
    @param:$type:操作类型；1：系统管理；2：业务处理
    @param:$userid:用户id，默认登录用户
     */
    public function operateRecord ($remark,$status = false,$type = 2,$userid = '')
    {
        if (empty($remark))return false;
        $status = $status?1:0;
        $operate = new \manager\index\model\UserOperate();
        $data = [
            'userid' => $userid?$userid:User::getUid(),
            'remark' => $remark,
            'type'   => $type,
            'status' => $status,
            'ip'     => request()->ip(),
        ];
        return $operate->save($data);
    }
    
    /*
     * 类的共有属性
     * 
     */
    private $attribute;
    protected  function attributes(){
        if($this->attribute===null){
            $class = new \ReflectionClass($this);
            foreach($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property){
                if(!$property->isStatic()){
                    $this->attribute[] = $property->getName();
                }
            }
        }
       return $this->attribute;
    }
    //编码
    public function iconv($string){
        if(mb_detect_encoding($string,"UTF-8",true)){
            return iconv("utf-8","GBK",$string);
        }
        return $string;
    }
    /*
     * 是否包含中文
     *  @string string:待判断字符串
     *  @isAll 是否全部字符串判断
     * @return boolean  
     */
    public function isChinese($string,$isAll = false){
        if($isAll){
            return preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $string)>0;
        }
        return preg_match('/[\x{4e00}-\x{9fa5}]/u', $string)>0;
    }
    
}


