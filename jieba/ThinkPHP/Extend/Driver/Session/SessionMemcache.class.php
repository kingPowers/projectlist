<?php
// +----------------------------------------------------------------------
// |
// +----------------------------------------------------------------------
// | Copyright (c) 2013-
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: richievoe <richievoe@163.com>
// +----------------------------------------------------------------------
/**
 * 自定义Memcache来保存session
 */
Class SessionMemcache{
    //memcache对象
    private $mem;
    //SESSION有效时间
    private $expire;
    //外部调用的函数
    public function execute(){
        session_set_save_handler(
            array(&$this,'open'),
            array(&$this,'close'),
            array(&$this,'read'),
            array(&$this,'write'),
            array(&$this,'destroy'),
            array(&$this,'gc')
        );
    }
    //连接memcached和初始化一些数据
    public function open($path,$name){
        $this->expire = C('SESSION_EXPIRE') ? C('SESSION_EXPIRE') :ini_get('session.gc_maxlifetime');
        $this->mem = new Memcache;
        echo CACHE_SERVER;
        return $this->mem->connect(CACHE_SERVER);
    }
    //关闭memcache服务器
    public function close(){
        return $this->mem->close();
    }
    //读取数据
    public function read($id){
        $id = C('SESSION_PREFIX').$id;
        $data = $this->mem->get($id);
        return $data ? $data :'';
    }
    //存入数据
    public function write($id,$data){
        $id = C('SESSION_PREFIX').$id;
        //$data = addslashes($data);
        return $this->mem->set($id,$data,0,$this->expire);
    }
    //销毁数据
    public function destroy($id){
        $id = C('SESSION_PREFIX').$id;
        return $this->mem->delete($id);
    }
    //垃圾销毁
    public function gc(){
        return true;
    }
}
