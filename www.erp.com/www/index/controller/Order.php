<?php
/**
 * 订单管理
 */
namespace www\index\controller;

use think\Controller;

class Order extends Common
{
    //订单列表
    public function myOrderList()
    {

        //分页
        if ($this->request->post('page')) {
            $mobile = new \manager\index\model\LenderMobile(['token' => session('token')]);
            $list   = $mobile->loanList(['isPage' => 1, 'page' => $_POST['page']]);
            if ($list) {
                // return $list;
                return ['status' => 1, 'msg' => '操作成功', 'data' => $list];
            } else {
                return ['status' => 0, 'msg' => '操作失败'];
            }
        }
        //借吧跳转  无token时跳回借吧
        if (empty($_GET['token'])) {
            $u  = config("view_replace_str")['_OS_'] ? config("view_replace_str")['_OS_'] : 'http://erpwww.lingqianzaixian.com';
            $ur = urlencode($u . '/order/myorderlist?token=');
            if (!empty($_GET['redirecturl'])) {
                //跳回借吧并给redirect为erp myorderlist
                $lianjie = $_GET['redirecturl'] . "?redirecturl=$ur";
                return $this->redirect($lianjie);
            } else {
                $url = config("view_replace_str")['_JIEBAWWW_'] ? config("view_replace_str")['_JIEBAWWW_'] : 'http://wx1.lingqianzaixian.com';
                return $this->redirect($url . "/member/login?redirecturl=$ur");
            }
        } else {
            session('token', null);
            session('token', $_GET['token']);
        }
        $this->redirecturlJieBA();
        $mobile = new \manager\index\model\LenderMobile(['token' => session('token')]);
        $list   = $mobile->loanList(['isPage' => 1]);
       // dump($list);die;
        $this->assign('list', $list);
        return $this->view->fetch();
    }

    public function addOrder()
    {

        if (!empty($_GET['token'])) {
            session('token', null);
            session('token', $_GET['token']);
        }
        $this->redirecturlJieBA('addOrder');
        //用于发短信验证
        if (empty(session('_register_mobile_'))) {
            session('_register_mobile_', md5(time()));
        }
        $this->assign('_register_mobile_', session('_register_mobile_'));
        if ($_POST && !empty($_POST['mobile'])) {
            if (empty(session('token'))) {
                $this->redirecturlJieBA();
            }
            $token          = session('token');
            $mobile         = new \manager\index\model\LenderMobile();
            $_POST['token'] = $token;
            if ($mobile->addLender($_POST)) {
                return ['status' => 1, 'msg' => '操作成功', 'data' => $mobile->id];
            } else {
                return ['status' => 0, 'msg' => $mobile->getError()];
            }
        }
        $this->assign('token',session('token'));
        return $this->view->fetch();
    }

    //发短信验证码
    public function sendSms()
    {
        if (empty($_POST['_register_mobile_']) || empty(session('_register_mobile_')) || $_POST['_register_mobile_'] != session('_register_mobile_')) {
            session('_register_mobile_', null);
            return ['status' => 0, 'msg' => '页面已失效，请刷新页面'];
        }
        $mobile = new \manager\index\model\LenderMobile();
        $res    = $mobile->sendSmsCode($_POST['mobile']);
        session('_register_mobile_', null);
        if ($res) {
            return ['status' => 1, 'msg' => '验证码已成功发送'];
        } else {
            return ['status' => 0, 'msg' => $mobile->getError()];
        }

    }  
    //订单详情
    public function orderInfo()
    {
        $this->redirecturlJieBA();
        $token          = session('token');
         if(empty($_REQUEST['id']))$this->redirect('myorderlist');
        $id             = $_GET['id'];
        $mobile         = new \manager\index\model\LenderMobile(['token' => $token]);
        $mobile->loanid = $id;
        $res            = $mobile->loanOne()[0]['list'][0];
        $this->assign('res', $res);
        return $this->view->fetch();
    }

    //判断是否有token 以及是否为有效 否则调回借吧登录界面
    protected function redirecturlJieBA($action = 'myorderlist')
    {
        $u           = config("view_replace_str")['_OS_'] ? config("view_replace_str")['_OS_'] : 'http://erpwww.lingqianzaixian.com';
        $ur          = urlencode($u . '/order/' . $action . '?token=');
        $url         = config("view_replace_str")['_JIEBAWWW_'] ? config("view_replace_str")['_JIEBAWWW_'] : 'http://wx1.lingqianzaixian.com';
        $redirecturl = $url . "/member/login?redirecturl=$ur";
        if (empty(session('token'))) {
            return $this->redirect($redirecturl);
        } else {
            //判断是否是有效token
            $mobile = new \manager\index\model\LenderMobile(['token' => session('token')]);
            header('Content-Type: text/html; charset=utf-8');
            $service_res = $mobile->getSellerInfo();
            // dump($service_res);
            if ($service_res['member_info']['is_staff'] !== '1') {
                // return $this->redirect($url . "/index/index");
                // dump($service_res['member_info']['is_staff']);die;
                return $this->redirect($url . "/member/login");
            }
        }
    }
}
