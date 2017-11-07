<?php

namespace manager\Index\controller;

use think\Controller;
use think\Session;
use manager\index\model\User;
class Publics extends Controller
{       
        /*
         * 登录
         */
	public function login ()
	{
            if($_POST){
              $user = new User($_POST);
              if($user->checkValidate($_POST,"login") && ($result = $user->login())){
                    session('uid',$result->getData()["id"] );
                    session('user', $result->getData());
                  (new \manager\index\model\Common())->operateRecord("用户登录",true,1);
                  $this->success("登录成功");
              }else{
                  $this->error($user->getError());
              }
            }
            return $this->view->fetch();
	}
        //头部文件
        public function pageHeader(){
            return $this->view->fetch();
        }
        //尾部文件
        public function pageFooter(){
            return $this->view->fetch();
        }
        //system

        //获取菜单
        public function getJsMenu() {
            $data = getMenuData1('menu',session('user.groupid'));
            exit(json_encode($data));
        }
        public function logout()
        {
            session('uid',null);
            return "<script>alert('退出成功');window.location.href='login.html'</script>";
            //$this->success('退出成功','publics/login','',1);
        }

         public function test(){
            $loan = new \manager\index\model\ProductManager();
            dump($loan);
            dump($loan->getProductOne(1));
            dump($loan->getError());
            exit;
//            dump($loan->getError());
             
             //dump(\manager\index\model\Issue::getIssueFirstRate(100000,12,10.5));exit;
             //$test= new \manager\index\model\Product();
             //dump($test->getOneProduct("智车宝-1724-x"));
             
             //dump(\manager\index\model\Product::productXZ1712(["loanMoney"=>100000,"startTime"=>"2017-01-31"]));
             //dump(array_pad([], 12, 0));exit;
             $test = new \manager\index\model\productXZ1712(100000);
             $test->startTime = "2017-01-31";//dump($test);
             dump($test->productList());
        }
        
        public function pdf(){
            $pdf = new \manager\index\model\PDF(["fileName"=>"a.pdf","content"=>"<h1>青岛</h1><h1>山东</h1><p>青岛</p><p>山东</p>"]);
            dump($pdf->createPDF());exit;
        }
        public function eqian(){
            $eqian = new \manager\index\model\erpEqian();
            $files = ["0"=>["file"=>UPLOAD_PATH."pdf/42_1.pdf","key"=>"委托人"]];
            dump($eqian->userMultiSignPDF($files,"000000"));
            dump($eqian->getError());
        }
        
        public function sms(){
            $sms = new \manager\index\model\Sms(["mobile"=>"136","location"=>"D"]);
            dump($sms->sendVerify());
            dump($sms->getError());
        }
}