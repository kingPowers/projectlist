<?php
//意见反馈
$type = $this->v('type');


//检测是否登录

    //$member_info['id'] = $this->v('memberid');
    $member_info = $this->token_check_force();

//新增意见内容
if($type=='view_add'){
    $this->need('content','意见反馈内容');
    $order = array();
    $order['memberid'] = $member_info['id'];
    if($order['memberid'] == ''){
        $this->error("SYS_ERR",'系统错误稍后重试');
    }
    $order['content'] = $this->v('content');
    if(strlen($order['content']) < 15){
        $this->error("SYS_ERR",'提交内容不能少于5个字');
    }
    if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$order['content'])){
        $this->error("SYS_ERR",'您输入的内容有误，请重新编辑！');
    }
    $order['timeadd'] = date('Y-m-d H:i:s');
    $order['count'] = 1;
    $timetoday = date("Y-m-d");//今天0点的时间点
    $timetoday1 = strtotime(date("Y-m-d"));
    $time2 = date("Y-m-d",$timetoday1 + 3600*24);//今天24点的时间点，两个值之间即为今天一天内的数据
    $map['timeadd'] = array('between',array($timetoday,$time2));
    $map['memberid'] = $order['memberid'];
    $count = M('view')->field('count')->where($map)->count();
       // $this->error("SYS_ERR",'系统错误稍后重试');
    if($count < 5){
        $add_id = M('view')->add($order);
        $this->data = array('add_id'=>$add_id);
        return true;
    }else{
         $this->error("SYS_ERR",'对不起，每位用户每天限制五次提交，谢谢配合！');
    }
    $this->error("SYS_ERR",'系统错误稍后重试');
}
?>