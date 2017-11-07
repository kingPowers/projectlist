<?php
/*
 * 验证码管理
 */
namespace manager\index\model;

class Sms extends Common{
    
    protected $rule = [
        //场景 send:发送普通短信，verify:验证码  checkVerify:校验验证码
        ["mobile","require","msg"=>"手机号不能为空",'scene'=>['send',"verify","checkVerify"]],
        ["location",'require','msg'=>'验证码发送位置不能为空','scene'=>['verify',"checkVerify"]],
        ["content",'require','msg'=>'短信内容不能为空','scene'=>['send']],
        ["smsCode",'require','msg'=>'验证码不能为空','scene'=>["checkVerify"]],
    ];
    
    public  $space = 60;//重新获取验证码的间隔时间，默认60s
    public  $verfiyNum = 6;//验证码位数，默认6位
    public  $validPeriode = 600;//验证码有效期,默认10分钟，单位：s
    public  $maxCheckNum = 3;//验证最大次数，超过该次数需要重新获取验证码
    public  $defaultVerify = "您本次操作的验证码为<smsCode>,请勿泄漏给其他人。";
    
    /*
     * 发送验证码
     *    eg.$smsModel = new Sms(["mobile"=>"","location"=>"","content"=>""]);
     *       $smsModel->sendVerify();//发送验证码
     *       $smsModel->checkVerify($smsCode);
     */
    public  function sendVerify(){
        if($this->checkValidate($this->getData(),"verify")){
            $sessions = $this->getVerifySession();
            if(!empty($sessions) && ($plus =$sessions["timeSpace"]-time()+$sessions["startTime"])>0){
                $this->error = "请{$plus}秒后再获取验证码";
                return false;
            }
            $this->data("content", $this->data["content"]?:str_replace("<smsCode>",$this->getSmsCode(),$this->defaultVerify));
            if($this->sendSms()){
                $this->setVerifySession();
                return true;
            }
        }
        return false;
        
    }
    //设置验证码存储
    private function setVerifySession($value = [],$isClear = false){
        if($isClear)return session($this->data["mobile"].$this->data["location"],null);
        if(empty($value)){
            $value = ["smsCode"=>$this->getSmsCode(),"mobile"=>$this->data["mobile"],"startTime"=>time(),"checkNum"=>0,"timeSpace"=>$this->space];
        }
        return session($this->data["mobile"].$this->data["location"],$value);
    }
    
    private function getVerifySession(){
        return session($this->data["mobile"].$this->data["location"]);
    }
    
    private function getSmsCode(){
        $smsCode = substr(str_shuffle(str_repeat("123456789", 4)),0,$this->verfiyNum);
        $smsCode = "000000";//测试验证码为6个0
       return $smsCode; 
    }
    /*
     * 验证短信正确性
     *  @param $mobile:
     */
    public  function checkVerify(){
        if(!$this->checkValidate($this->getData(),"checkVerify"))return false;
        $sessions = $this->getVerifySession();
        if(empty($sessions)){
            $this->error = "请先获取验证码";
            return false;
        }elseif($plus<=0 && $sessions["timeSpace"]-$plus>$this->validPeriode){
            $this->error = "验证码".intval($this->validPeriode/60)."分钟有效期，请重新获取";
            return false;
        }elseif($sessions["checkNum"]>=$this->maxCheckNum){
            $this->error = "验证码输入错误过多，请重新获取验证码";
            return false;
        }elseif(!($sessions["smsCode"]===$this->data["smsCode"] && $sessions["mobile"]===$this->data["mobile"])){
            $this->error = "验证码错误";
            $sessions["checkNum"]++;
            $this->setVerifySession($sessions);
            return false;
        }
        $this->setVerifySession([],true);
        return true;
    }
    
    
    /*
     * 开始发送短信
     *   @return boolean
     */
    public  function sendSms(){
       if(!$this->checkValidate($this->getData(),"send"))return false; 
       return true;   
    }
}
