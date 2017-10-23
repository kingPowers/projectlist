<?php

/* 
 * 吉祥果
 */

/**
 * Description of CommonAction
 * 继承基类
 * @author Nydia
 */
class CommonAction extends Action {

    //登录用户
    public $user = array();
    //分页
    public $page = array();

    //初始化，子类用_initialize
    public function __construct() {
        $this->page = M()->page;
        $this->page['num'] = 13;
        parent::__construct();
        $uid = session('uid');
        if (empty($uid)) {
            header('Location:/login.html');
            exit;
        }
        import('@.ORG.Util.Auth');
        $st = '';
        if((MODULE_NAME=='Order' || MODULE_NAME=='Store') && $_REQUEST['status'])
        	$st = "/status/{$_REQUEST['status']}";
        //现金贷,1:客户现金贷   2：员工现金贷
        if((MODULE_NAME=='Crediet') && $_REQUEST['is_staff'])
       	  $st = "/is_staff/{$_REQUEST['is_staff']}";
        
        if (!Auth::check(MODULE_NAME . '-' . ACTION_NAME.$st, $uid)) {
            $this->error('亲！您没有权限');
        }
        $this->user = session('user');
        $this->redis = new Redis();
        $this->redis->connect(REDIS_SERVER, REDIS_PORT);
    }

    //设置分页
    public function setPage($pageurl) {
        $this->assign('page_vars', $this->page);
        $this->assign('page_url', $pageurl);
        $pagehtml = $this->fetch('Public:page');
        $this->assign('page', $pagehtml);
    }

    //获取请求数据
    public function boxpost() {
        $data = $this->_request('data');
        if (empty($data)) {
            return null;
        }
        $postring = explode('&amp;', $data);
        $post = array();
        foreach ($postring as $po) {
            $poitem = explode('=', $po);
            if ($poitem[0] == 'r')
                continue;
            $post[$poitem[0]] = $poitem[1];
        }
        return $post;
    }

    protected function ajaxSuccess($message='',$data=''){
        $this->ajaxReturn($data,$message,1,'',$this->pagestring);
    }

    protected function ajaxError($message='',$data=''){
        $this->ajaxReturn($data,$message,0,'',$this->pagestring);
    }

    protected function ajaxAssign(&$result){
        $result['msg'] = $result['info'];
    }

    public function systemConfig(){
        $system = S('system');
        if(!$system){
            $system = M('system')->where('id=1')->find();
        }
        return $system;
    }

    /**
     * 标的列表
     */
    protected function getLoan(){
        $data = M("loan") -> field('id,loansn as sn,title') ->where('status = 1')->order('timeadd desc')->select();
        return $data;
    }
    
    /*
     * 定义调用的接口
    *  $param  $data['_cmd_'] 方法名,必填
    *          ---
    *          1.接口的其他参数，也是必填的
    *          2.接口的公共参数可以不填
    *
    *  example:调用登录接口
    * 		$this->service(array('_cmd_'=>'member_login','mobile'=>'13600000000','password'=>'123456'));
    *
    * */
    public function service($data){
    	$service = C('TMPL_PARSE_STRING');
    	$url = !empty($service["_SERVICE_"])?$service["_SERVICE_"]:"http://service.".DOMAIN_ROOT;
    	return  $this->curlpost($url,array_merge($data,$this->setParams($data['_cmd_'])));
    }
    
     
    private function setParams($_cmd_){
    	$_client_keys_ = array('IOS'=>'123456','ANDROID'=>'123456','BROWSER'=>'123456','SMS'=>'SMSAPI201408');
    	empty(session('_deviceid_'))?session('_deviceid_',uniqid()):'';
    	return array(
    			'_deviceid_'=>session('_deviceid_'),//设备id
    			'_client_'=>'BROWSER',//设备类型，browser表示微信
    			'_sign_'=>md5("BROWSER|{$_client_keys_['BROWSER']}|{$_cmd_}"),//签名
    			'_token_'=>$this->getToken(),
    	);
    }
     
    public function getToken(){
    	return isset($_SESSION['token']) && !empty(S("token_{$_SESSION['token']}_member_info"))?$_SESSION['token']:'';
    }
    
    public function curlpost($url,$array){
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
    	$data = curl_exec($ch);
    	curl_close($ch);
    	$data = json_decode(trim($data,chr(239).chr(187).chr(191)),true);
    	return $data;
    }
}
