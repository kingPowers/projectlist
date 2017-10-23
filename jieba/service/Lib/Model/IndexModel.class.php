<?php

class IndexModel extends CommonModel {

    public $errorcode= 0;
    public $errormsg = '';
    public $_params_ = array();
    public $_version_currect_ = '';
    public $_deviceid_='';
    public $_client_;
    public $_token_  = '';
    public $data     = '';
    public $_cmd_ = '';
    public $_wx_openid_ = '';

    private function v($name){
        return isset($this->_params_["$name"])?trim(htmlspecialchars($this->_params_["$name"])):'';
    }
    private function need($name,$desc=''){
        $value = $this->v($name);
        if(empty($value)){
            $this->errorcode = 'REQUIRE_SOME_VALUE';
            throw new Exception($desc?($desc."不能为空"):($name.'can not be empty'));
        }
        return $value;
    }
    private function error($err_code,$err_msg=''){
        $this->errorcode = $err_code;
        $this->errormsg = $err_msg;
        throw new Exception($err_msg);
    }
    private function gen_token($memberid,$username,$password,$mobile,$time){        
        return md5($memberid.$username.$password.$mobile.C('SECURE_KEY').$time);
    }
    //token设置，token缓存清除
    public function token($key,$val=false,$time=0){
        if(false===$val){
            return S('token_'.$this->_token_.'_'.$key);            
        }else{
            if($time>0){
                S('token_'.$this->_token_.'_'.$key,$val,$time);
            }else {
                S('token_'.$this->_token_.'_'.$key,$val);
            }
            return true;
        }
    }
    //token销毁
    private function token_destory(){
        //delete from db
        $res = M('Token')->where(array('token'=>$this->_token_))->delete();
        //清除缓存
        $this->token('member_info',null);
        //$this->token('station',null);
        if($res){
            return true;
        }else {
            return false;
        }
    }
    private function normal_token_check_force($return){
         //正常登陆级别验证
        if($this->token('member_info')){
            return $this->token('member_info');
        }else{
            if($this->_wx_openid_){
                //微信登陆
                $wx_res = $this->login_by_wx();
                if($wx_res){
                    return $wx_res;
                }else {
                    if($return){
                        return false;
                    }else {
                        $this->errorcode = 'NOT_LOGIN';
                        throw new Exception('您还未登录');
                    }
                }
            }else {
                if($return){
                    return false;
                }else {
                    $this->errorcode = 'NOT_LOGIN';
                    throw new Exception('您还未登录');
                }
            }
            
        }
    }
    //token校验
    public function token_check_force($return=false,$level='normal'){
        if($level=='normal'){
            return $this->normal_token_check_force($return);
        }else {
            //强制token验证

            //先验证缓存有没有
            if($this->normal_token_check_force($return)==false){
                return false;
            }
            
            $_token_info = M('Token')->where(array('token'=>$this->_token_))->find();
            if(!$_token_info){
                //清除缓存
                $this->token('member_info',null);
                //$this->token('station',null);
                
                if($return){
                    return false;
                }else {
                    $this->errorcode = 'NOT_LOGIN';
                    throw new Exception('您还未登录');
                }
            }
            $new_mem_info = M('Member')->where(array('id'=>$_token_info['memberid']))->find();
            $new_token = $this->gen_token($new_mem_info['id'],$new_mem_info['username'],$new_mem_info['password'],$new_mem_info['mobile'],strtotime($_token_info['login_time']));
            if($this->_token_!=$new_token){
                 //清除缓存
                $this->token('member_info',null);
                //$this->token('station',null);

                $this->errorcode = 'NOT_LOGIN';
                throw new Exception('登录已过期，请重新登录');
            }
            //$new_mem_info['bindinfo'] = M('member_info')->where(array('memberid'=>$_token_info['memberid']))->find();

            return $this->token('member_info');
        }
    }

    private function login_by_wx(){
        $member = D('Member')->where(array('wx_openid'=>$this->_wx_openid_))->find();

        if($member){
            $now_time = time();
            $token = $this->gen_token($member['id'],$member['username'],$member['password'],$member['mobile'],time());
            $this->_token_ = $token;
            $this->save_token_todb($token,$member['id'],$member['username'],$now_time);
            $member_login_data = $this->token('member_info');
            return $member_login_data;
        }else {
            return false;
        }
    }
    private function save_token_todb($token,$memberid,$username,$login_time,$autologin=0){
        //查询库中有没有旧记录
        $old_info = M('Token')->where(array('memberid'=>$memberid))->find();
        $update_arr = array(
                    'token'     =>$token,
                    'username'   =>$username,
                    'login_time' =>date('Y-m-d H:i:s',$login_time),
                    'autologin'  => $autologin
                    );
        if($old_info){
        	S('token_'.$old_info['token'].'_member_info',null);
            $res = M('Token')->where(array('memberid'=>$memberid))->save($update_arr);
        }else {
            $update_arr['memberid'] = $memberid;
            $res = M('Token')->add($update_arr);
        }
        //查询qianduoduo.member_info中有没有信息 
        $mi_res = M('member_info')->where(array('memberid'=>$memberid))->find();
        $update_mem_info_res = true;
        if(!$mi_res){
           $update_mem_info_res =  M('member_info')->add(array('memberid'=>$memberid));
        }
        //member_info 入缓存
        $member_info = M('Member')->where(array('id'=>$memberid))->find();
        $member_bind_info = M('member_info')->where(array('memberid'=>$memberid))->find();
        $member_info['bindinfo'] = $member_bind_info;
        
        $this->token('member_info',$member_info,3600*24);
        //更新station_token
        //$this->get_station();

        if($res && $update_mem_info_res){
            return true;
        }else {
            return false;
        }
    }
    private function get_station() {
    	$station = $this->token('station');
    	if (empty($station)) {
    		$ip = APP_TEST ? '116.226.209.143' : get_client_ip();
    		$iplocation = $this->get_area_detail_by_ip($ip);
    		$city = empty($iplocation['city']) ? '上海' : $iplocation['city'];
    		$city = str_replace('市', '', $city);
    		$station = M('Station')->where("name='{$city}'")->field('id,name')->find();
    		$station['location'] = implode('-', $iplocation);
    		 $this->token('station',$token_station,3600*24);
    	}
    	empty($station) ? array('id' => 0, 'name' => '总站') : $station;
    	return $station;
    }
    

/*     private function get_station(){
        if($this->token('station')){
            return $this->token('station');
        }
        $station = $this->station_suggest_fetch();
       
        $token_station = array('id'=>$station['id'],'name'=>$station['name']);

        $this->token('station',$token_station,3600*24);
        return $token_station;
    } */
    private function station_suggest_fetch(){
        $data = $this->get_area_by_ip(get_client_ip());
        $station = false;
        if($data['province']){
            $stations = D('Station')->where("tag_province='".$data['province']."' and status=1")->select();
        }
        if(!$stations){
            return $station;
        }
        if(count($stations)==1){
            $station = $stations[0];
            return $station;
        }
        $hascitys = array();
        foreach($stations as $sta){
            if($sta['tag_city']==$data['city']){
                $hascitys[] = $sta;
            }
        }
        if(!$hascitys){
            $station = $stations[0];
            return $station;
        }
        $hasdistricts = array();
        foreach($hasdistricts as $sta){
            if($sta['tag_district']==$data['city']){
                $hasdistricts[] = $sta;
            }
        }
        if(!$hasdistricts){
            $station = $hascitys[0];
            return $station;
        }
    }
   
    //根据ip获取归属地
    private function get_area_by_ip($ip){
        $data = array('province'=>'','city'=>'','district'=>'');
        $json = iconv('GBK','UTF-8',file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?callback=testJson&ip='.$ip));
        $json = str_replace("if(window.testJson) {testJson(","",$json);
        $json = str_replace(");}","",$json);
        $arr = json_decode($json,true);
        
        //$arr = $this->get_area_detail_by_ip($ip);
        $data['province'] = substr($arr['pro'],0,6);
        $data['city'] = substr($arr['city'],0,6);
        $data['district'] = substr($arr['region'],0,6);
        if($data['province']==$data['city']){
            $data['city'] = $data['district'];
            $data['district'] = '';
        }
        $this->iplocation = implode('',$data);
        return $data;
    }
    
    private function get_area_detail_by_ip($ip) {
    	$data = array('province' => '', 'city' => '', 'district' => '');
    	$json = iconv('GBK', 'UTF-8', file_get_contents('http://whois.pconline.com.cn/ipJson.jsp?callback=testJson&ip=' . $ip));
    	$json = str_replace("if(window.testJson) {testJson(", "", $json);
    	$json = str_replace(");}", "", $json);
    	$arr = json_decode($json, true);
    	$data['province'] = $arr['pro'];
    	$data['city'] = $arr['city'];
    	$data['district'] = $arr['region'];
    	return $data;
    }
    private function filter_telephone_inners($string){
            $string = str_replace('-','',$string);
            $string = str_replace(' ','',$string);
            $string = str_replace('、','',$string);
            $string = str_replace(array('一','二','三','四','五','六','七','八','九'),array(1,2,3,4,5,6,7,8,9),$string);
            $string = str_replace(array('壹','贰','叁','肆','伍','陆','漆','捌','玖'),array(1,2,3,4,5,6,7,8,9),$string);
            $string = str_replace(array('①','②','③','④','⑤','⑥','⑦','⑧','⑨'),array(1,2,3,4,5,6,7,8,9),$string);
            $string = str_replace(array('１','２','３','４','５','６','７','８','９','０'),array(1,2,3,4,5,6,7,8,9),$string);
            return $string;
    }

    public function run(){
        $filename = dirname(__FILE__).DIRECTORY_SEPARATOR.'service_inc'.DIRECTORY_SEPARATOR.$this->_cmd_.'.php';
        if(!file_exists($filename)){
            return $this->error(400,'接口未实现');
        }
        define('QIANDB','chedai');
        include_once($filename);
    }
    
    private function checkImageVerifyCode(){
    	$this->need('vcode','验证码');
    	$vcode = $this->v('vcode');
    	if ($vcode!=$this->token('imageVerifycode')){
    		$this->error('VERIFYCODE_ERROR','验证码不正确');
    	}
    	$this->token('imageVerifycode',null);
    }
}

?>