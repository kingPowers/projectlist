<?php
/**
 * Class chatAction
 * 在线客服后台管理
 */
class chatAction extends CommonAction{
    /**
     *        首页
     */
    public function index(){
        if(empty($this->user)){
            $this->error('亲！请登录');
        }
        $today = strtotime(date('Y-m-d'),time());
        $where['fro_to_id'] = array('like',$this->user['id'].'_%');
        $where['created'] = array('egt',$today);
        $where['type'] = 0;
        $lists = M('welive_msg')->where($where)->field('fro_to_id,max(created) max_created')->group('fro_to_id')->select();
        foreach($lists as $list){
            $key = 'msg'.$list['fro_to_id'];
            /*$last_time = $this->redis->hget($key,1);
            if(empty($last_time)){
                $last_time = 0;
            }
            if($list['created']<$last_time){
                continue;
            }*/
            list($list['serverid'],$list['userid']) = explode('_',$list['fro_to_id']);
            $lits = M('member m')
            ->join('member_info mi on mi.memberid = m.id')
            ->field('m.id,mi.names,m.username,m.mobile')
            ->where(array('m.id'=>$list['userid']))
            ->find();
            $lits['num'] = $this->redis->hget($key,'new');
            $lits['max_created'] = date('Y-m-d H:i:s',$list['max_created']);
            $messages[] = $lits;
        }
        array_multisort(array_column($messages,'max_created'),SORT_DESC,$messages);
        $this->assign('messages',$messages);
        $this->display();
    }
    public function refresh_index(){
        if(empty($this->user)){
            $this->error('亲！请登录');
        }
        $today = strtotime(date('Y-m-d'),time());
        $where['fro_to_id'] = array('like',$this->user['id'].'_%');
        $where['created'] = array('egt',$today);
        $where['type'] = 0;
        $lists = M('welive_msg')->where($where)->field('fro_to_id,max(created) max_created')->group('fro_to_id')->select();
        foreach($lists as $list){
            $key = 'msg'.$list['fro_to_id'];
            list($list['serverid'],$list['userid']) = explode('_',$list['fro_to_id']);
            $lits = M('member m')
            ->join('member_info mi on mi.memberid = m.id')
            ->field('m.id,mi.names,m.username,m.mobile')
            ->where(array('m.id'=>$list['userid']))
            ->find();
            $lits['num'] = $this->redis->hget($key,'new');
            $lits['max_created'] = date('Y-m-d H:i:s',$list['max_created']);
            $messages[] = $lits;
        }
        array_multisort(array_column($messages,'max_created'),SORT_DESC,$messages);
        $this->ajaxReturn($messages,'json');
    }
    /**
     * 初始化面板获取消息
     */
    public function getMessage(){
        $id = $this->_get('id', 'intval', 0);
        $mobile = $this->_get('mobile');
        $names = M('member_info')->where("memberid=".$id)->getField('names');
        $names = $names?$names:'客户'; 
        $where['fro_to_id']  = $this->user['id'].'_'.$id;
        $key = 'msg'.$where['fro_to_id'];
        $last_time = $this->redis->hget($key,1);
        if(empty($last_time)){
            $last_time = 0;
        }
        $where['created'] = array('egt',$last_time);
        $list = M('welive_msg')->where($where)->select();
        $this->redis->hMset($key,array('1'=>time(),'new'=>0));
        foreach($list as &$li){
            $li['created'] = date('Y-m-d H:i:s',$li['created']);
        }
        //dump($list);
        $this->assign('list',$list);
        $this->assign('id',$id);
        $this->assign('mobile',$mobile);
        $this->assign('names',$names);
        $this->assign('chat_time',date('Y-m-d H:i:s',time()));
        $this->display('box_chat_info');
    }

    /**
     *定时获取消息
     */
    public function getMessageJson(){
        $id = $this->_post('id', 'intval', 0);
        $where['fro_to_id']  = $this->user['id'].'_'.$id;
        $key = 'msg'.$where['fro_to_id'];
        $last_time = $this->redis->hget($key,1);
        if(empty($last_time)){
            $last_time = 0;
        }
        $where['created'] = array('egt',$last_time);
        $where['type'] = 0;
        $list = M('welive_msg')->where($where)->select();
        foreach($list as &$li){
            $li['created'] = date('Y-m-d H:i:s',$li['created']);
        }
        $this->redis->hset($key,1,time());
        $this->ajaxReturn($list,'json');
    }

    /**
     * 记录回复数据
     */
    public function writeMessage(){
        $id = $this->_post('id', 'intval', 0);
        $content = $this->_post('content');
        $data['fro_to_id'] = $this->user['id'].'_'.$id;
        $data['msg'] = $content;
        $data['created'] = time();
        $data['type'] = 1;
        $return = M('welive_msg')->add($data);
        if($return){
            import("Think.ORG.Util.Chat");
            $chat = new Chat();
            $where['id'] = $id;
            $users = M('Member')->field('mobile')->where($where)->find();
            $ret = $chat->yy_hxSend($this->user['id'], array($users['mobile']), $content, "users", '');//發送消息
            //$return = $chat->chatRecord();//查看记录
            //$return = $chat->addFriend('michelle','13764352975');//添加好友
            $this->ajaxReturn('发送成功','json');
        }else{
            $this->ajaxReturn('发送失败,请重新发送','json');
        }
    }

    /**
     * 查看历史聊天记录
     */
    public function historyMessage(){
        $id = $this->_post('id', 'intval', 0);
        $count = $this->_post('count');
        $count = $count/2;
        $where['fro_to_id'] = $this->user['id'].'_'.$id;
        $list = M('welive_msg')->where($where)->order('id desc')->limit("$count,10")->select();
        foreach($list as &$li){
            $li['created'] = date('Y-m-d H:i:s',$li['created']);
        }
        $list = array_reverse($list);
        $this->ajaxReturn($list,'json');
    }
    /*
     *       修改昵称
     * * */
    public function modifyName(){
        if($this->user['id']!=60){
            $this->error('亲！没有权限');
        }
        $where['groupid'] = 2;
        $lists = M('user')->where($where)->select();
        $this->assign('lists',$lists);
        $this->display();
    }
    /**
     *
     */
    public function modifyData(){
        if($this->user['id']!=60){
            $this->ajaxReturn('没有权限','json');
        }
        $content = $this->_post('content');
        $uid = $this->_post('id');
        $where['id'] = $uid;
        $data['nickname'] = $content;
        $return = M('User')->where($where)->save($data);
        if($return){
            $this->ajaxReturn('修改成功','json');
        }else{
            $this->ajaxReturn('修改失败,请重新发送','json');
        }
    }
    /**
     * history list
     */
    public function historyList(){
        if(empty($this->user)){
            $this->error('亲！请登录');
        }
        $today = strtotime(date('Y-m-d'),time());
        $where['fro_to_id'] = array('like',$this->user['id'].'_%');
        //$where['max_created'] = array('lt',$today);
        $where['type'] = 0;
        $lists = M('welive_msg')->where($where)->field('fro_to_id,max(created) max_created')->group('fro_to_id')->order('max_created desc')->select();
        foreach($lists as $list){
            if($list['max_created']>$today){
                continue;
            }
            list($list['serverid'],$list['userid']) = explode('_',$list['fro_to_id']);
            $lits = M('member m')->join('member_info mi on m.id=mi.memberid')->field('m.id,m.username,m.mobile,mi.names')->where(array('m.id'=>$list['userid']))->find();
            $lits['max_created'] = date('Y-m-d H:i:s',$list['max_created']);
            $key = 'msg'.$list['fro_to_id'];
            $lits['num'] = $this->redis->hget($key,'new');
            $messages[] = $lits;
        }
        array_multisort(array_column($messages,'max_created'),SORT_DESC,$messages);
        $this->assign('messages',$messages);
        $this->display();
    }
    //批量注册账号
    public function register(){
        import("Think.ORG.Util.Chat");
        $chat = new Chat();
        $users = M('Member')->field('mobile')->select();
        foreach($users as $user){
            $return = $chat->accreditRegister($user['mobile'],'123456');//创建用户
            if(!empty($return['error'])){
                echo $user['mobile'];
             }
        }
    }
}
?>