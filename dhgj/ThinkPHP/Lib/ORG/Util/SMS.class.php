<?php

class SMS {

    public static $errcode;
    /*
     * 添加短信类型 zwd
     * 2016/06/07
     */
    static function buildverify($targe, $content = false,$type=1,$sms_type) {
        if (!$content) {
            $code = self::genrandcode(); $code = '000000';
            $id = rand(10, 99);
            $content = $type == 1 ? '您本次操作验证码为：'.$code.'，验证码序号（'.$id.'）请妥善保管。【吉祥果】' : $code;
            session('smscode', md5($code));
        }
		return true;
        $send = self::send($targe, $content,$type,$sms_type);
        return $send === true ? $id : false;
    }

    static function genrandcode() {
        return  rand(100000, 999999);
    }
    /*
     * 添加短信类型 zwd
     * 2016/06/07
     */
    static function send($to, $content,$type,$sms_type) {return true;
        $type = $type ? $type : 1;
        if (empty($to) || empty($content)) {
            return false;
        }
        $content = str_replace(array('抵押', '投资', '借款', '转让', '贷款'), array('**', '**', '**', '**', '**'), $content);
        /*if (APP_TEST) {
            SmsRecord::add($to, $content, 0);
            return true;
        }*/

        $result = self::api_zhuoyun($to, $content);
        self::$errcode = $result['message'];
        SmsRecord::add($to, $content, $result['returnstatus'], $result['message'],$sms_type);
        return $result['returnstatus'] == "Success" ?true  : false;
    }

    static function api_zhuoyun($mobile, $message) {

        $post_data = array();
        $post_data['userid'] = 1471;
        $post_data['account'] = 'ZXCF';
        $post_data['password'] = '123456';
        $post_data['content'] = urlencode($message); //短信内容需要用urlencode编码下
        $post_data['mobile'] = $mobile;
        $post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
        $url='http://115.29.242.32:8888/sms.aspx?action=send';
//        $url='http://218.244.136.70:8888/sms.aspx?action=send';       //原来短信地址
        $o='';
        foreach ($post_data as $k=>$v)
        {
            //$o.="$k=".urlencode($v).'&';
            $o.="$k=".$v.'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
        $result = curl_exec($ch);
        curl_close($ch);
        $xml = (array)simplexml_load_string($result);

        return $xml;
    }

}

//短信记录
class SmsRecord {

    /**
     * @brief add 
     *
     * @param $mobile  接收人
     * @param $content 内容
     * @param $result 发送状态
     * @param $extend 返回信息
     *
     * @return boolean
     */
 /*
 * 添加短信类型 zwd
 * 2016/06/12
 */
    public static function add($mobile = 0, $content = '', $result = false, $extend = '',$sms_type) {
        $data = array();
        $data['mobile'] = trim($mobile);
        $data['content'] = $content;
        $data['extend'] = $extend;
        $data['status'] = ($result === false) ? 0 : ( $result == 0 ? 1 : 0);
       /* $SQL=("SELECT * FROM sms_type WHERE code = '{$sms_type}' and status = 1 LIMIT 1");
        $list = M()->query($SQL);
        if($list){
            $data['s_type'] = $sms_type;
        }else{
            $data['s_type'] = 0;
        }*/

        M('SmsRecord')->add($data);
    }

}
