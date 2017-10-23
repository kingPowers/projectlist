<?php

/**
 * 基于memcache实现的全局锁，用法：
 * $lock = new MemcacheLock($name);  // 需要传入锁名称
 * $lock->lock();            // 偿试获取锁，也可用lockNonBlock方法获取
 * if($lock->locked()){  // 如果获取到锁
 *    // do some thing ...
 *    $lock->release();  // 释放锁,很重要，保证其他进程能及时获取到锁
 * }else{   // 没有获取到锁,表示该锁已被其他进程所获取
 *    // do other thing ...
 * }
 */
require_once 'Lock.class.php';
class MemcacheLock implements Lock{
	
	/**
	 * 锁的名称  
	 */
	private $name;
	
	/**
	 * 锁唯一签名，本实现使用微秒，用于释放锁时做签名验证
	 */
	private $signaure;
	
	/**
	 * 锁的缓存名称前缀
	 */
	private $prefix = 'lock.';
	
	/**
	 * memcache实例
	 */
	private $hander;
	
	/**
	 * 锁的有效期,加入有效期是为了防止release方法没有调用导致锁无法释放
	 */
	private $expire;
	
	/**
	 * 获取锁的超时时间 
	 */
	private $blockTimeout;
	
	/**
	 * 是否成功获取了锁
	 */
	private $locked = false;
	
	public function __construct($name, $expire = 0, $blockTimeout=0 , $prefix='lock.' , array $options=array()){

        $this->name = $name;
        $this->expire = $expire>0?$expire:60;  //有效期缺省为60秒
        $this->blockTimeout = $blockTimeout;
        $this->prefix       =   $prefix;
        
        $options = array_merge(array (
        		'host'        =>  C('MEMCACHE_HOST') ? C('MEMCACHE_HOST') : '127.0.0.1',
        		'port'        =>  C('MEMCACHE_PORT') ? C('MEMCACHE_PORT') : 11211,
        		'timeout'     =>  C('DATA_CACHE_TIMEOUT') ? C('DATA_CACHE_TIMEOUT') : false,
        		'persistent'  =>  false,
        ),$options);
        
        $func               =   $options['persistent'] ? 'pconnect' : 'connect';
        $this->handler      =   new \Memcache;
        $options['timeout'] === false ?
            $this->handler->$func($options['host'], $options['port']) :
            $this->handler->$func($options['host'], $options['port'], $options['timeout']);
	}
	
	/**
	 * 阻塞方式偿试获取锁，本方法将被阻塞直到获取到锁或者超时
	 * @return boolean   true--成功   false--失败
	 */
	public function lock(){
		return $this->_lock();
	}
	
	/**
	 * 非阻塞方式偿试获取锁
	 * @return boolean   true--成功   false--失败
	 */
	public function lockNonBlock(){
		return $this->_lock(false);
	}
	
	/**
	 * 是否获取到锁
	 * @return boolean
	 */
	public function locked(){
		return $this->locked;
	}
	
	private function _lock( $block = true ){
		if (!$this->locked){
			$start = time();
			do{
				$current = md5($this->name.microtime().mt_rand());
				$this->locked = $this->handler->add($this->prefix.$this->name,$current,false,$this->expire);
				if ($this->locked){
					$this->signaure = $current; 
				}else if($block){
					usleep(10000);
				}
			}while($block && !$this->locked && ($this->blockTimeout<=0 || (time()-$start)<$this->blockTimeout) );
		}
		return $this->locked;
	}
	
	/**
	 * 释放锁，本方法将检查签名以确定锁为该实例所获取
	 * @return boolean 
	 */
	public function release(){
		$locked = $this->locked;
		$signaure = $this->signaure;
		$this->locked = false;
		$this->signaure = null;
		if ($locked && $signaure==$this->handler->get($this->prefix.$this->name)){
			return $this->handler->delete($this->prefix.$this->name);	
		}else{
			return false;
		}
	}
	
}