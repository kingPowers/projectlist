<?php
/*
 *客服系统
 *
 */
$type = $this->v('type');
$redis = new Redis();
$redis->connect(REDIS_SERVER, REDIS_PORT);
$member_info = $this->token_check_force();
if($type =='customerList'){//客服列表
    $where['groupid'] = 2;
    $where['status'] = 1;
    $lists = M('User')->where($where)->field('id,username,nickname')->select();
    foreach($lists as &$list){
        $list['ison'] = $redis->hget('kf_online',$list['id']);
        $list['ison'] = ($list['ison']+28800>time())?1:0;
        if(empty($list['nickname'])){
            $list['nickname'] = $list['id'];
        }
    }
    foreach ($lists as $k => $v) {
        $edition[] = $v['ison'];
    }
    array_multisort($edition,SORT_DESC,$lists);
    $lists = count($lists)<1?array():$lists;
    $this->data = array('_token_'=>$this->_token_, 'list'=>$lists);
    return true;
}else if($type == 'online'){
    $server_id = $this->v('server_id');
    $where['fro_to_id'] = $server_id.'_'.$member_info['id'];
    $key = 'msg'.$where['fro_to_id'];
    $last_time = $redis->hget($key,0);
    if(empty($last_time)){
        $last_time = 0;
    }
    $where['created'] = array('egt',$last_time);
    $list = M('welive_msg')->where($where)->select();
    $redis->hset($key,0,time());
    count($list)<1?$list = array():'';
    $avatart = M('member_info')->where(array('memberid'=>$member_info['id']))->field('avatar')->find();
    if(empty($avatart['avatar'])){
        $return['avatar'] = _STATIC_.'/2015/member/image/account/heads.png';
    }else{
        $return['avatar'] =  _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$avatart['avatar'];
    }
    $this->data = array('_token_'=>$this->_token_, 'list'=>$list,'avatar'=>$return['avatar']);
    return true;
}else if($type == 'historyMessage'){//查看历史聊天记录
    $count = $this->v('count');
    $server_id = $this->v('server_id');
    //$count = $count/2;
    $where['fro_to_id'] = $server_id.'_'.$member_info['id'];
    $list = M('welive_msg')->where($where)->order('id desc')->limit("$count,10")->select();
    $list = array_reverse($list);
    count($list)<1?$list = array():'';
    $this->data = array('_token_'=>$this->_token_, 'list'=>$list);
    return true;
}else if($type == 'writeMessage'){//写数据
    $content = $this->v('content');
    $server_id = $this->v('server_id');
    $data['fro_to_id'] = $server_id.'_'.$member_info['id'];
    $data['msg'] = $content;
    $data['created'] = time();
    $data['type'] = 0;
    $return = M('welive_msg')->add($data);
    if($return===false){
        $this->error('ADD_ERR','写入数据库失败');
        return true;
    }
    $key = 'msg'.$data['fro_to_id'];
    $redis->hIncrBy($key,'new',1);
    $this->data = array('_token_'=>$this->_token_, 'return'=>$return);
    return true;
}else if($type == 'getMessageJson'){//定时获取消息
    $server_id = $this->v('server_id');
    $where['fro_to_id'] = $server_id.'_'.$member_info['id'];
    $key = 'msg'.$where['fro_to_id'];
    $last_time = $redis->hget($key,0);
    if(empty($last_time)){
        $last_time = 0;
    }
    $where['created'] = array('egt',$last_time);
    $where['type'] = 1;
    $list = M('welive_msg')->where($where)->select();
    $redis->hset($key,0,time());
    count($list)<1?$list = array():'';
    $this->data = array('_token_'=>$this->_token_, 'list'=>$list);
    return true;
}
$this->error('TYPE_ERR','type类型错误');
return true;


?>