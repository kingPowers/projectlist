<?php
class PingguAction extends CommonAction {
    public function __construct(){
        parent::__construct();
        if(false==($member_info = $this->getMemberInfo()) && !empty($_REQUEST['token'])){
        	$_SESSION['token'] = $_REQUEST['token'];
        }
    }
    public function index(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/Pinggu/index"));
        $list = $this->getCarBand();
        ksort($list);
        $this->assign('list',$list);
        $provinces = $this->getProvinces();
        ksort($provinces);
        $this->assign('provinces',$provinces);
        $this->display();
    }
    public function detail(){
        $data = array(
            'time' => date('Y年m月',strtotime($_POST['datepicker'])),
            'brand_id' => $_POST['brand_id'],
            'brand_name' => str_replace('-',' ',$_POST['brand_names']),//$_POST['brand_names'],//str_replace('-',' ',$_POST['brand_names']),
            'city_id' => $_POST['city_id'],
            'city_name' => str_replace('-','',substr($_POST['city_name'],0)),
            'run_km' => $_POST['run_km']
        );
        $brand_infos = M("car_brand_third")->where("thr_id={$_POST['brand_id']}")->find();
        $info = empty($brand_infos)?(array()):$brand_infos;
        $city_infos = M("car_city")->where("city_code={$_POST['city_id']}")->find();
        $ci_in = empty($city_infos)?(array()):$city_infos;
        if(!empty($info) && !empty($ci_in)){
            $data_url= 'bid='.$info['first_id'].'&sid='.$info['sec_id'].'&specId='.$info['thr_id'].'&registerYear='.date('Y',strtotime($_POST['datepicker'])).'&registerMonth='.date('m',strtotime($_POST['datepicker'])).'&pid='.$ci_in['province_code'].'&cid='.$ci_in['city_code'];//.'&selectCarName=310100';
            $param_url = 'pgbid='.$info['first_id'].'&pgsid='.$info['sec_id'].'&pgspid='.$info['thr_id'].'&pgyear='.date('Y',strtotime($_POST['datepicker'])).'&pgmonth='.date('m',strtotime($_POST['datepicker'])).'&pgpid='.$ci_in['province_code'].'&pgcid='.$ci_in['city_code'].'&pgtype=1&type=1&pgmlg='.$_POST['run_km'];
            $price = $this->howMoney($data_url,$param_url);
            $data['min_price'] = $price[0];
            $data['max_price'] = $price[1];
        }
        $this->assign('data',$data);
        $this->display();
    }
    public function howMoneyTest($data){
        $url = 'http://www.che168.com/Evaluate/v2/EvaCar.aspx'.'?'.$data;
        $res = $this->curl_post($url);
        preg_match("/[0-9]{8}/",$res,$matchs);
        $ticket = (int)$matchs[0];
        return $ticket;
    }
    public function howMoney($data_url,$param_url){
        $ticket = $this->howMoneyTest($param_url);
        $url = 'http://www.che168.com/pinggu/eva_'.$ticket.'/1.html'.'?'.$data_url;
        $res = $this->curl_post($url);
        preg_match('/minPrice \=(.*);/',$res,$matchs);
        $min_price = $matchs[1];
        preg_match('/maxPrice \=(.*);/',$res,$matchs);
        $maxPrice = $matchs[1];
        $price = array($min_price,$maxPrice);
        return $price;
    }

//----------------------------------------工具函数-----------------------------------------------------
    public function curl_post($url,$timeout = 5){
        if($url == "" || $timeout <= 0){
            return false;
        }
        //$url = $url.'?'.http_bulid_query($data);
        $con = curl_init((string)$url);
        curl_setopt($con, CURLOPT_HEADER, false);
        curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
        return curl_exec($con);
    }
    public function getCarBand(){
        $lists = M('car_brand')->order('id asc')->select();
        $data = array();
        foreach($lists as &$list){
            $data[$list['first_code']][] = $list;
        }
        return  $data;
    }
    public function getCardSec(){
        $id = $this->_post('id','trim');
        $data = array();
        $lists = M('car_brand_sec')->where("first_id={$id}")->select();
        foreach($lists as &$list){
            $data[$list['sec_code']][] = $list;
          }
        $info = isset($data)?$data:array();
        $this->ajaxReturn($info,'json');
    }
    public function getCardThird(){
        $id = $this->_post('id','trim');
        $data = array();
        $lists = M('car_brand_third')->where("sec_id={$id}")->select();
        foreach($lists as &$list){
            $data[$list['thr_code']][] = $list;
          }
        $info = isset($data)?$data:array();
        $this->ajaxReturn($info,'json');
    }
    public function getProvinces(){
       $lists = M('car_city')->where('city_code=0')->select();
       $data = array();
      foreach($lists as &$list){
        $province = explode(" ",$list['province_name']);     
        $list['province_name'] = $province[1];
        $list['names'] = $list['province_name'];
        $data[$province[0]][] = $list;
      }
        return  $data;
    }
    public function getCitys(){
        $id = $this->_post('id','trim');
        $lists = M('car_city')->where("city_code!=0 and province_code={$id}")->select();
        $info = isset($lists)?$lists:array();
        $this->ajaxReturn($info,'json');
    }
}
?>