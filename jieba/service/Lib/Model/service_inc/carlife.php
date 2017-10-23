<?php
/*
 * 我与车生活
 * 
 * */
$type = $this->v('type');

//车生活列表
if($type!='list'){
    $member_info = $this->token_check_force();
}
if($type=='list'){
	$page = intval($this->v('page'));
	$number=intval($this->v('number'))>0?intval($this->v('number')):10;
	$is_cream = $this->v('is_cream');
    $is_list = $this->v('is_list');
    $memberid = 0;
	if(!empty($is_cream)){
        $where['is_cream'] = 1;//是否精华
        $order = 'oa.order,oa.timeadd desc';
    }else{
        $order = 'oa.timeadd desc';
    }
    if(($member_info = $this->token('member_info'))){
        $memberid = $member_info['id'];
    }
	if($number>100){
        $this->error('NUMBER_ERR','每页的条数不得大于100条');
    }
    $where['oa.status'] = 1;
    
    
    
    $_usernames = " if(member.username=member.mobile,concat(substr(member.username,'1','3'),'****',substr(member.username,-4)),member.username) as names";
    if(empty($memberid)){
        if(isset($page) && $page>0){
            $lists = M('order_assed oa')
                ->join('(select assed_id, count(*) point_num from order_point GROUP BY  assed_id) as order_point_num on order_point_num.assed_id = oa.id')
                ->join('(select assed_id,count(*) eval_num from order_eval GROUP BY  assed_id) as order_eval_num on order_eval_num.assed_id = oa.id')
                ->join('member_info on oa.memberid=member_info.memberid')
                ->join('member on member.id=member_info.memberid')
                ->field('oa.*,order_point_num.point_num,order_eval_num.eval_num,member_info.avatar'.",{$_usernames}")
                ->where($where)
                ->order($order)
                ->limit(($page-1)*$number.",".$number)
                ->select();
        }else{
            $lists = M('order_assed oa')
                ->join('(select assed_id, count(*) point_num from order_point GROUP BY  assed_id) as order_point_num on order_point_num.assed_id = oa.id')
                ->join('(select assed_id,count(*) eval_num from order_eval GROUP BY  assed_id) as order_eval_num on order_eval_num.assed_id = oa.id')
                ->join('member_info on oa.memberid=member_info.memberid')
                ->join('member on member.id=member_info.memberid')
                ->field('oa.*,order_point_num.point_num,order_eval_num.eval_num,member_info.avatar'.",{$_usernames}")
                ->where($where)
                ->order($order)
                ->limit(20)
                ->select();
        }
    }else{
        if(isset($page) && $page>0){
            $lists = M('order_assed oa')
                ->join('(select assed_id, count(*) point_num from order_point GROUP BY  assed_id) as order_point_num on order_point_num.assed_id = oa.id')
                ->join('(select assed_id,count(*) eval_num from order_eval GROUP BY  assed_id) as order_eval_num on order_eval_num.assed_id = oa.id')
                ->join('member_info on oa.memberid=member_info.memberid')
                ->join('member on member.id=member_info.memberid')
                ->join("(select assed_id from order_point where memberid='{$memberid}') as my_ponits on my_ponits.assed_id=oa.id")
                ->field('oa.*,order_point_num.point_num,order_eval_num.eval_num,member_info.avatar,my_ponits.assed_id is_point'.",{$_usernames}")
                ->where($where)
                ->order($order)
                ->limit(($page-1)*$number.",".$number)
                ->select();
        }else{
            $lists = M('order_assed oa')
                ->join('(select assed_id, count(*) point_num from order_point GROUP BY  assed_id) as order_point_num on order_point_num.assed_id = oa.id')
                ->join('(select assed_id,count(*) eval_num from order_eval GROUP BY  assed_id) as order_eval_num on order_eval_num.assed_id = oa.id')
                ->join('member_info on oa.memberid=member_info.memberid')
                ->join('member on member.id=member_info.memberid')
                ->join("(select assed_id from order_point where memberid='{$memberid}') as my_ponits on my_ponits.assed_id=oa.id")
                ->field('oa.*,order_point_num.point_num,order_eval_num.eval_num,member_info.avatar,my_ponits.assed_id is_point'.",{$_usernames}")
                ->where($where)
                ->order($order)
                ->limit(20)
                ->select();
        }
    }
    $count = count($lists);
    empty($lists)?$lists = array():'';
    foreach($lists as &$list){
        $list['date_time'] = date('m月d日 H:i',$list['timeadd']);
        if(!empty($list['avatar'])){
            $list['avatar'] = _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$list['avatar'];
        }else{
            $list['avatar'] = _STATIC_.'/2015/member/image/account/heads.png';
        }
        $imagges = explode('|',$list['images']);
        unset($list['images']);
        $list['images'] = array();
        
         foreach($imagges  as $imagge){
             if(!empty($imagge)){
               $list['images'][] = _STATIC_.'/Upload/assed'.DIRECTORY_SEPARATOR.'m_'.$imagge;
            }
          }
          if(empty($list['images'])){
             $list['images']['0'] = _STATIC_.'/2015/index/image/carImg.png';
          }
        
        $list['is_point'] = M('order_point')->where("assed_id='{$list['id']}' and memberid='{$member_info['id']}'")->find()?1:0;
    }
    
	$this->data = array('count'=>$count,'assed_list'=>$lists);
	//S("_carlife_",$this->data,60*60);
	return true;
//车生活详情
}elseif($type=='detail'){
	$this->need('id','assed_id');
    //$this->need('memberid','memberid');
	$id = $this->v('id');
    //$delete = $this->v('delete');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
    $where['oa.id'] = $id;
    if(!$delete){
        //$where['oa.status'] = 1;
    }
    $list = M('order_assed oa')
        ->join('(select assed_id, count(*) point_num from order_point GROUP BY  assed_id) as order_point_num on order_point_num.assed_id = oa.id')
        ->join('(select assed_id,count(*) eval_num from order_eval GROUP BY  assed_id) as order_eval_num on order_eval_num.assed_id = oa.id')
        ->join('member_info on oa.memberid=member_info.memberid')
        ->join('member on member.id=member_info.memberid')
        ->field('oa.*,order_point_num.point_num,order_eval_num.eval_num,member_info.names,member.username,member_info.avatar')
        ->where($where)
        ->find();
    if(empty($list)){
        $this->error('DB_ERR','数据有误');
    }
    $wh['assed_id'] = $id;
    $wh['memberid'] = $memberid;
    $list['is_point'] = M('order_point')->where($wh)->find()?1:0;
    $imagges = explode('|',$list['images']);
    unset($list['images']);
    $list['images'] = array();
    foreach($imagges  as $imagge){
        if(!empty($imagge)){
            $list['images'][] = _STATIC_.'/Upload/assed'.DIRECTORY_SEPARATOR.'m_'.$imagge;
        }
    }
    $list['date_time'] = date('m月d日 H:i',$list['timeadd']);
    $list['can_delete'] = ($list['memberid'] == $memberid)?1:0;
    if(!empty($list['avatar'])){
    	$list['avatar'] = _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$list['avatar'];
    }else{
    	$list['avatar'] = _STATIC_.'/2015/member/image/account/heads.png';
    }
    //$list['avatar'] = _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$list['avatar'];
    
	$this->data = array('list'=>$list);
	return  true;
//删除车生活详情
}elseif($type=='detailDel'){
	$this->need('id','assed_id');
	$id = $this->v('id');
	$list = M('order_assed')->where("id='{$id}'")->find();
	if(false==$list)$this->error('ERR','天呐删不了，评价内容消失了');
	if($list['memberid']!=$member_info['memberid'])$this->error('ERR','对不起，这不是您发表的评价');
	$del = M('order_assed')->where("id='{$id}' and memberid='{$member_info['id']}'")->update(array('is_delete'=>1));
	if(false==$del)$this->error('ERR','您已删除此评价');
	return true;
//立即评价
}elseif($type=='assed'){
	$member_info = $this->token_check_force();
	
//点赞
}elseif($type=='goodpoint'){
	$this->need('id','assed_id');
	$assed_id = $this->v('id');
    //$this->need('memberid','memberid');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
	$is_exist = M('order_point')->where("assed_id='{$assed_id}' and memberid='{$memberid}'")->find();
	if($is_exist)$this->error('SYS_ERR','您已点过赞');
	$add_id = M('order_point')->add(array('assed_id'=>$assed_id,'memberid'=>$memberid));
	if($add_id)return true;
	$this->error('SYS_ERR','您已点赞');
//取消点赞
}elseif($type=='canclepoint'){
	$this->need('id','assed_id');
	$assed_id = $this->v('id');
    //$this->need('memberid','memberid');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
	$del = M('order_point')->where("assed_id='{$assed_id}' and memberid='{$memberid}'")->delete();
	if($del)return true;
	$this->error('SYS_ERR','您已取消点赞');
//评论列表	
}elseif($type=='evalList'){
    $this->need('assed_id','assed_id');
    $where['assed_id'] = $this->v('assed_id');
    $number=intval($this->v('number'))>0?intval($this->v('number')):10;
    $page = intval($this->v('page'));
    if(isset($page) && $page>0){
        $lists = M('order_eval')->where($where)->order("timeadd asc")->limit(($page-1)*$number.",".$number)->select();
    }else{
        $lists = M('order_eval')->where($where)->order("timeadd asc")->select();
    }
	if(count($lists)>0){
        foreach($lists as &$lis){
            $lis['date_time'] = date('m月d日 H:i',$lis['timeadd']);
            $whe['m.id'] = $lis['from_memberid'];
            $wh['m.id'] = $lis['to_memberid'];
            $from_memberinfo = M('member m')->join('member_info mi on m.id = mi.memberid')->where($whe)->find();
            $lis['names'] = $from_memberinfo['username'];
            $lis['avatar'] = _STATIC_.'/Upload/avatar'.DIRECTORY_SEPARATOR.$from_memberinfo['avatar'];
            if($wh['m.id']){
                $to_memberinfo = M('member m')->join('member_info mi on m.id = mi.memberid')->where($wh)->find();
                $lis['to_names'] = $to_memberinfo['username'];
            }else{
                $lis['to_names'] = '';
            }
            $lis['can_delete'] = ($lis['from_memberid'] == $member_info['id'])?1:0;
        }
	}
	empty($lists)?$lists = array():'';
	$this->data = array('count'=>count($lists),'list'=>$lists);//,'sql'=>M()->getLastSql()
	return true;
//评论
}elseif($type=='add_eval'){
	$this->need('id','assed_id');
	$this->need('content','评论内容');
    //$this->need('memberid','memberid');
	$assed_id = $this->v('id');
	$to_memberid = $this->v('to_memberid');
	$content = $this->v('content');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
	if(strlen($content) < 9){
		$this->error("SYS_ERR",'评论内容不能少于3个字');
	}
	if(strlen($content) > 90){
		$this->error("SYS_ERR",'评论内容在30个字以内就太完美了');
	}
	if(preg_match("/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/",$content)){
		$this->error("SYS_ERR",'您输入的内容有误，请重新发表评论！');
	}
	$add_id = M('order_eval')->add(array('assed_id'=>$assed_id,'from_memberid'=>$memberid,'to_memberid'=>$to_memberid,'content'=>$content,'timeadd'=>time()));
    //$this->data = array('sql'=>M()->getLastSql());
    $this->data = array('id'=>$add_id);
	if($add_id)return true;
	$this->error('SYS_ERR','您已评论过了');
//删除评论
}elseif($type=='del_eval'){
	$this->need('id','eval_id');
	$id = $this->v('id');
    //$this->need('memberid','memberid');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
    $where['id'] = $id;
	$list = M('order_eval')->where($where)->find();
	if(false==$list)$this->error('ERR','天呐删不了，评论内容消失了');
	if($list['from_memberid']!=$memberid)$this->error('ERR','对不起，这不是您的评论');
	$del = M('order_eval')->where($where)->delete();
	if(false==$del)$this->error('ERR','您已删除此评论');
	return true;
}elseif($type=='delete_assed'){//删除文章
    $this->need('assed_id','assed_id');
    $assed_id = $this->v('assed_id');
    //$this->need('memberid','memberid');
    //$memberid = $this->v('memberid');
    $memberid = $member_info['id'];
    $where['id'] = $assed_id;
    $list = M('order_assed')->where($where)->find();
    if(false==$list)$this->error('ERR','天呐删不了，评论内容消失了');
    if($list['memberid']!=$memberid)$this->error('ERR','对不起，这不是您的评论');
    $data['status'] = 2;
    $del = M('order_assed')->where($where)->save($data);
    if(false==$del)$this->error('ERR','您已删除此评论');
    return true;
}elseif($type=='assedCommit'){
    $memberid = $member_info['id'];
    //判断是否已经评论过
    $this->need('order_id','order_id');
    $where['order_id'] = $this->v('order_id');
    //$where['status'] = array('in','0,1,3');
    $is_assed = M('order_assed')->where($where)->find();
    if(!empty($is_assed)){
        if($is_assed['status']!=2){
            $this->error('REPEAT_ERR','不能重复评价');
        }
    }
    $this->need('content','content');
    $where['content'] = $this->v('content');
    $this->need('map_url','map_url');
    $where['map_url'] = $this->v('map_url');
    $where['memberid'] = $memberid;
    $info = array();
    if(!empty($_FILES)){
        $type = 'assed';
        import('ORG.Net.UploadFile');
        $upload = new UploadFile();// 实例化上传类
        $upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath =  UPLOADPATH.$type.DIRECTORY_SEPARATOR;// 设置附件上传目录
        $upload->thumb = true;
        $upload->saveRule = $memberid.time();           //设置文件保存规则唯一
        // 设置引用图片类库包路径
        //$upload->imageClassPath = '@.ORG.Image';
        $upload->uploadReplace = true;                 //同名则替换
        $upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
        //设置缩略图最大宽度
        $upload->thumbMaxWidth = '400,100';
        //设置缩略图最大高度F
        $upload->thumbMaxHeight = '400,100';
        if(!$upload->upload()) {// 上传错误提示错误信息
            $this->error('UPLOAD_ERR',$upload->getErrorMsg());
        }else{// 上传成功 获取上传文件信息
            $info =  $upload->getUploadFileInfo();
        }
        $file['images'] = '';
        foreach($info as $inf){
            $file['images'] = $file['images'].'|'.$inf['savename'];
        }
    }
    $file['order_id'] = $where['order_id'];
    $file['memberid'] = $memberid;
    $file['content'] = $where['content'];
    $file['location'] = $where['map_url'];
    $file['timeadd'] = time();
    $file['status'] = $file['is_cream'] = 0;
    if(empty($is_assed)){
        $return = M('order_assed')->add($file);
    }else{
        $order_where['order_id'] = $where['order_id'];
        $return = M('order_assed')->where($order_where)->save($file);
    }
    if($return===false){
        $this->error('DB_ERR','操作失败');
    }else{
        if(empty($is_assed)){
            //评论得积分，并推送通知（微信|站内信）
            import("Think.ORG.Util.wxMessage");
            $msg = new wxMessage;
            $msg->commentSuccess($memberid,$order_id);
        }
        return true;
    }
}
$this->error('TYPE_ERR','type类型错误');
?>