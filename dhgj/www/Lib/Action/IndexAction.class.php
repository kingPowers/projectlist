<?php

class IndexAction extends CommonAction {

    public function index() {
      //最新订单列表 
      $params['_cmd_'] = "order";
      $params['type'] = "newOrderList";
      $params['num'] = 2;
      $service_res = $this->service($params);unset($params);
      $newsList = $service_res['dataresult']['list'];
      //dump($newsList);
      //历史订单列表
      $params['_cmd_'] = "order";
      $params['type'] = "historyOrder";
      $params['num'] = 4;
      $params['page'] = 1;
      $service_res = $this->service($params);unset($params);
      $historyList = $service_res['dataresult']['list'];
      //dump($historyList);
      //新闻列表
      $params['_cmd_'] = "media_news";
      $params['type'] = "mediaNews";
      $params['page'] = 1;
      $params['number'] = 10;
      $params['is_cream'] = 1;
      $service_res = $this->service($params);unset($params);
      $mediaList = $service_res['dataresult']['list'];
      foreach ($mediaList as &$value) {
         $time = explode(".",$value['lasttime']);
         $value['year'] = $time[0];
         $value['month'] = $time[1];
         $value['day'] = $time[2];
         $value['intro'] = (mb_strlen($value['intro'])<150)?$value['intro']:(mb_substr($value['intro'],0,150,'utf-8')."...");
      }
      //dump($mediaList);
      $this->assign("mediaList",$mediaList);
      $this->assign("newList",$newsList);
      $this->assign("historyList",$historyList);
		  $this->display();
    }
    public function isNewOrder()
    {
      if($_POST && ($_POST['is_ajax'] == 1))
      {
          if(false == ($member_info = $this->getMemberInfo()))$this->ajaxError('尚未登录');
          if($member_info['usertype'] != 2)$this->ajaxError('用户类型错误');
          $params = array();
          $params['_cmd_'] = "order";
          $params['type'] = "myOrderList";
          $params['page'] = 1;
          $params['number'] = 1;
          $service_res = $this->service($params);
          if($service_res['errorcode'] === 0)
          {
            $list = $service_res['dataresult']['list'][0];
            if($list['status'] == 3){
               $this->ajaxSuccess($list['status']);
            }else{
               $this->ajaxError("获取失败");
            }
          }else{
            $this->ajaxError("获取失败");
          }
      }
    }
    public function text1()
    {
      $this->ajaxError('123456');
    }
}
