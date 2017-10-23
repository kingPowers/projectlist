<?php

/* 
 *  吉祥果
 */

/**
 * Description of CommonAction
 * 无验证模块
 * @author Nydia
 */
class PublicAction extends Action {
    public function __CONSTRUCT(){
         header("Content-Type:text/html; charset=utf-8");
        parent::__CONSTRUCT();
        $this->redis = new Redis();
        $this->redis->connect(REDIS_SERVER, REDIS_PORT);
    }
    //提交登录
    public function doLogin() {
        $username = $this->_post('username');
        $password = $this->_post('password');
        $verify = $this->_post('verify');
        if (empty($username) || empty($password) || empty($verify)) {
            $this->error('登录信息不完整');
        }
        if (!$this->verifyCheck($verify)) {
            $this->error('验证码不正确');
        }
        $F = M('User')->where("username='{$username}'")->find();
        if (empty($F)) {
            $this->error('用户名错误');
        }
        if ($F['status'] != 1) {
            $this->error('帐号已禁用');
        }
        if (D('Common')->gen_password($password) != $F['password']) {
            $this->error('密码错误');
        }
        M('User')->where("id={$F['id']}")->setInc('login_count');
        session('uid', $F['id']);
        session('user', $F);
        session('verify', null);
        $this->redis->hset('kf_online',$F['id'],time());
        $this->success('登录成功');
    }

    //退出登录
    public function logout() {
        $uid = session('uid');
        $this->redis->hset('kf_online',$uid,0);
        session('uid', null);
        session('user', null);
        $this->success('退出成功');
    }

    //获取菜单
    public function getJsMenu() {
        $data = D('Common')->getMenuData('menu');
        //dump($data);
        exit(json_encode($data));
    }

    //生成验证码
    public function verifyCode() {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }

    //验证验证码
    public function verifyCheck($verify) {
        //return session('verify')."-".md5($verify);
        return session('verify') == md5($verify) ? true : false;
        return true;
    }
    
    //编辑器上传图片
    public function imageUpload(){
        import('Think.ORG.Util.EditorUpload');
        EditorUpload::save();
    }
    
}
