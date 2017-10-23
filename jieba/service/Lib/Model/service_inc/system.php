<?php
//系统消息管理
$type = $this->v('type');
//检测用户是否登录
$member_info = $this->token_check_force();
//系统消息列表
if($type=='list'){
    $page = intval($this->v('page'))>0?intval($this->v('page')):1;
    $number=intval($this->v('number'))>0?intval($this->v('number')):10;
    $status = $this->v('status');
    if(!empty($status) && !in_array($status,array(1,0)))
        $this->error("STATUS_ERR",'status参数错误');
    if($number>100)
        $this->error('NUMBER_ERR','每页的条数不得大于100条');
    $where_sql = "s.status=1 and (s.touserid='0' or s.touserid='{$member_info['id']}') ";
    $where_sql .= empty($status)?" and m.id is null ":'';//未读：0  全部：1
    
    $count = M('system')->table('system s')
    					->field("s.*,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read")
    					->join("middle m on m.system_id=s.id and m.memberid='{$member_info['id']}'")
    					->where($where_sql)
    					->count();
    if($count>0){
    	$list = M('system')->table('system s')
		    	->field("s.id,s.title,s.keywords,s.touserid,s.content,s.code,s.type,s.sort,if(CHAR_LENGTH(s.summary)>27,concat(substring(s.summary,'0','27'),'...'),s.summary) as summary,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read,if(m.id is null,'0','1') as is_read_sort")
		    	->join("middle m on m.system_id=s.id and m.memberid='{$member_info['id']}'")
		    	->where($where_sql)
		    	->group("s.id")
		    	->limit((($page-1)*$number).",{$number}")
		    	->order("is_read_sort asc,s.sort desc,s.timeadd desc")
		    	->select();
    }
    empty($list)?$list = array():'';
    $this->data = array('count'=>intval($count),'systemlist'=>$list);
    return true;
}
//消息详情页面
if($type=='list_info'){
	$this->need('id','system_id');
    $id = $this->v('id');
    if(!empty($id)){
        $number = M('middle mi')->join('system s on mi.system_id= s.id')->where("system_id='{$id}'and memberid='{$member_info['id']}'")->find();
        if(empty($number)){
            //$number = M('middle')->where(" system_id='{$id}'")->field('memberid')->find();
            $number = M('middle')->where(" memberid='{$member_info['id']}'")->find();
            $member['system_id'] = $id;
            if(empty($number)){
                       $member['memberid'] = $member_info['id'];
                       $came = M('middle')->add($member);
            }
            if($number){
                $member['memberid'] = $member_info['id'];
                $came = M('middle')->add($member);
            }
        }
        $count = M('system')->where("id='{$id}'")->count();
        if($count>0){
            //先判断middle表中是否有这个消息id
            $number = M('middle mi')->join('system s on mi.system_id= s.id')->where("system_id='{$id}' and memberid='{$member_info['id']}'")->find();
            if($number){
                //根据消息id查找这个两个表的信息
                $systemList = M('system s')->join('middle mi on mi.system_id= s.id')->field("s.id,s.title,s.keywords,s.summary,s.content,s.timeadd,s.code,mi.memberid,mi.status,mi.system_id")->where("s.id='{$id}' and mi.memberid='{$member_info['id']}'")->find();
            }
            if($systemList['status'] == 0){
                $system['status'] = 1;
                $numberone = M('middle')
                    ->where("system_id ='{$id}' and memberid='{$member_info['id']}'")
                    ->save($system);
            }
        }
        $this->data = array('count'=>intval($count),'systemlist'=>$systemList);
        return true;
    }
}
//未读消息统计
if($type=='count'){
	$where_sql = "s.status=1 and (s.touserid='0' or s.touserid='{$member_info['id']}') ";
    $where_sql .= " and m.id is null ";//未读：0  全部：1
    $count = M('system')->table('system s')
    					->field("s.*,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read")
    					->join("middle m on m.system_id=s.id and m.memberid='{$member_info['id']}'")
    					->where($where_sql)
    					->count();
//待评价数量
$assed_count = M()->table("`order` o,order_process p")->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id not in(select order_id from order_assed where status!=2)")->order("timeadd desc")->count();
$asseded_count = M()->table("`order` o,order_process p")->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id in(select order_id from order_assed where status!=2)")->order("timeadd desc")->count();
$this->data = array('count'=>$count,'assed_count'=>$assed_count,'asseded_count'=>$asseded_count);
return true;
}
//个人中心接口
if($type=='countDetail'){
    $where_sql = "s.status=1 and (s.touserid='0' or s.touserid='{$member_info['id']}') ";
    $where_sql .= " and m.id is null ";//未读：0  全部：1
    $count = M('system')->table('system s')
        ->field("s.*,date_format(s.timeadd,'%Y-%m-%d') as timeadd,m.id as is_read")
        ->join("middle m on m.system_id=s.id and m.memberid='{$member_info['id']}'")
        ->where($where_sql)
        ->count();
    //待评价数量
    $assed_count = M()->table("`order` o,order_process p")->where("o.memberid='{$member_info['id']}' and o.id=p.order_id and o.status=2 and o.id not in(select order_id from order_assed where status!=2)")->order("timeadd desc")->count();
    $return['count'] = $count;
    $return['assed_count'] = $assed_count;
    import("Think.ORG.Util.Fuyou");
    $fuyou = new Fuyou();
    $balance = $fuyou->BalanceAction($member_info['id']);
    $return['balance'] = (false==$balance)?'0':($balance['ca_balance']/100);
    //生成个人名片
    import("Think.ORG.Util.myqrcode");
    $qrcode = new myqrcode($member_info['id']);
    $return['card_name'] =  _STATIC_."/Upload/qrcode/{$member_info['id']}".DIRECTORY_SEPARATOR."card_{$member_info['id']}.png";
    $avatart =  M('member')
        ->field("member_info.avatar,member.username,member.invitecode")
        ->join('member_info on member.id=member_info.memberid')
        ->where(array('memberid'=>$member_info['id']))
        ->find();
    if(!empty($avatart['avatar'])){
    	$avatar = _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$avatart['avatar'];
    }else{
    	$avatar = _STATIC_.'/2015/member/image/account/heads.png';
    }
    $return['avatar'] = $avatar;
    $return['username'] = $avatart['username'];
    $return['invitecode'] = $avatart['invitecode'];
    $this->data = array('info'=>$return);
    return true;
}
//上传头像
if($type=='avatar'){
    $type_img = 'avatar';
    $memberid = $member_info['id'];
    import('ORG.Net.UploadFile');
    $upload = new UploadFile();// 实例化上传类
    //$upload->maxSize  = 3145728 ;// 设置附件上传大小
    $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
    $upload->savePath =  UPLOADPATH.$type_img.DIRECTORY_SEPARATOR;// 设置附件上传目录
    $upload->saveRule = 'code_'.$memberid.time();           //设置文件保存规则唯一
    $upload->thumb = true;
    //$upload->saveRule = $memberid.time();           //设置文件保存规则唯一
    $upload->zipImages = true;
    $upload->thumbRemoveOrigin = true;
    // 设置引用图片类库包路径
    //$upload->imageClassPath = '@.ORG.Image';
    $upload->uploadReplace = true;                 //同名则替换
    //设置需要生成缩略图的文件后缀
    //$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
    //设置缩略图最大宽度
    $upload->thumbMaxWidth = '400,100';
    //设置缩略图最大高度F
    $upload->thumbMaxHeight = '400,100';
    if(!$upload->upload()) {// 上传错误提示错误信息
        $this->error('UPLOAD_ERR',$upload->getErrorMsg());
    }else{// 上传成功 获取上传文件信息
        $info =  $upload->getUploadFileInfo();
    }
    $filepath['url'] = '/Upload/'.$type_img.'/';
    $filepath['filename'] = $info[0]['savename'];
    $data['avatar'] = 'thumb_'.$filepath['filename'];
    $where['memberid'] = $memberid;
    $return = M('Member_info')->where($where)->save($data);
    //更新二维码
    import("Think.ORG.Util.myqrcode");
    $qrcode = new myqrcode($member_info['id']);
    
    $this->data = array('avatar'=>_STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$data['avatar']);
    return true;
}
?>