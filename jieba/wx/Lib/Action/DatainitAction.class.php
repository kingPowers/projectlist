<?php
class DataInitAction extends CommonAction{
    public function __construct(){
        parent::__construct();
    }
    public static $cacheList = array(
        'car_brand' => 'initCarBrand',
        'car_brand_sec' => 'initCarBrandSec',
        'car_brand_third' => 'initCarBrandThird',
        'car_city' => 'initCarCity'
    );
    public function index(){
        foreach(self::$cacheList as $tb => $func){
            $this->$func();
        }
    }
    public function initCarBrand(){
        $data = array();
        $lists = M('car_brand')->order('id asc')->select();
        foreach($lists as $list){
            $data[$list['first_code']][] = $list;
        }
        foreach($data as &$da){
            $da = json_encode($da);
        }
        $this->redis->hMset('car_brand:all',$data);
    }
    public function initCarBrandSec(){
        $data = array();
        $lists = M('car_brand_sec')->select();
        foreach($lists as $list){
            $data[$list['first_id']][$list['sec_code']][] = $list;
        }
        foreach($data as &$da){
            $da = json_encode($da);
        }
        $this->redis->hMset('car_brand_sec:all',$data);
    }
    public function initCarBrandThird(){
        $data = array();
        $lists = M('car_brand_third')->select();
        foreach($lists as $list){
            $data[$list['sec_id']][$list['thr_code']][] = $list;
            $write[$list['thr_id']] = $list;
        }
        foreach($data as &$da){
            $da = json_encode($da);
        }
        foreach($write as &$wri){
            $wri = json_encode($wri);
        }
        $this->redis->hMset('car_brand_third:all',$data);
        $this->redis->hMset('car_brand_third',$write);
    }
    public function initCarCity(){
        $data = $provinces = $citys = array();
        $lists = M('car_city')->select();
        foreach($lists as $list){
            if($list['city_code'] == 0){
                $first_param = explode(' ',$list['province_name']);
                $list['names'] = $first_param[1];
                $provinces[$first_param[0]][] = $list;
            }else{
                $data[$list['province_code']][] = $list;
                $citys[$list['city_code']] = $list;
            }
        }
        foreach($provinces as &$province){
            $province = json_encode($province);
        }
        foreach($data as &$da){
            $da = json_encode($da);
        }
        foreach($citys as &$city){
         $city = json_encode($city);
        }
        $this->redis->hMset('car_city:provinces',$provinces);
        $this->redis->hMset('car_city:city',$data);
        $this->redis->hMset('car_city:citysid',$citys);
    }
}
?>