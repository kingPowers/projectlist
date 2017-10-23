<?php

/*
 * 车生活
 */

/**
 * Description of IndexAction
 * @author Nydia
 */
class CarlifeAction extends CommonAction {
	//车生活列表
    public function index() {
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/".ACTION_NAME));
    		$this->assign('member_info',$member_info);
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = "list";
    	//$params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
    	$params['is_cream'] = $_REQUEST['is_cream']?$_REQUEST['is_cream']:'';
        $params['memberid'] = $member_info['id'];
        $params['is_list'] = 1;
    	$service_res = $this->service($params);
    	if($_REQUEST['is_ajax']){
    		$status = $service_res['dataresult']['list']?1:0;
    		$this->ajaxReturn($service_res['dataresult']['list'],"加载成功",$status);
    	}
        $lists = $service_res['dataresult']['assed_list'];
        $index = empty($params['is_cream'])?1:0;
        $this->assign('index',$index);
        $this->set_seo(array('title'=>'我与借吧'));
    	$this->assign('list',$lists);
        $this->display();

    }
    //车生活详情
    public function detail(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    		$this->assign('member_info',$member_info);
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = "evalList";
    	$params['page'] = $_REQUEST['page']?$_REQUEST['page']:1;
    	$params['assed_id'] = $_REQUEST['id'];
    	$service_res = $this->service($params);
    	if($_REQUEST['is_ajax']){
    		$status = $service_res['dataresult']['list']?1:0;
    		$this->ajaxReturn($service_res['dataresult']['list'],"加载成功",$status);
    	}
    	$this->assign('list',$service_res['dataresult']['list']);
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = "detail";
    	$params['id'] = $_REQUEST['id'];
        $params['memberid'] = $member_info['id'];
        $params['delete'] = empty($_REQUEST['delete'])?0:1;
    	$service_res = $this->service($params);
        $lists = $service_res['dataresult']['list'];
    	$this->assign('detail',$lists);
        $is_point = $service_res['dataresult']['is_point']?0:1;
        //echo $is_point;
        $this->assign('is_point',$is_point);
        $this->set_seo(array('title'=>'我与借吧'));
       // $this->assign('list',$lists);
        $this->assign('memberid',$member_info['id']);
        $this->assign('assed_id',$_REQUEST['id']);
    	if($_REQUEST['delete']==3){
            $this->display('detail_delete');
        }else{
            $this->display();
        }
    }
    
    //点赞
    public function  goodpoint(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = ACTION_NAME;
    	$params['id'] = $_REQUEST['assed_id'];
        $params['memberid'] = $member_info['id'];
    	$service_res = $this->service($params);
    	if(0===$service_res['errorcode'])
    		$this->success("点赞成功");
    	$this->error($service_res['errormsg']);
    }
    //取消点赞
    public function canclepoint(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = ACTION_NAME;
    	$params['id'] = $_REQUEST['assed_id'];
        $params['memberid'] = $member_info['id'];
    	$service_res = $this->service($params);
    	if(0===$service_res['errorcode'])
    		$this->success("取消点赞成功");
    	$this->error($service_res['errormsg']);
    }
    //新增评论
    public function add_eval(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = 'add_eval';
    	$params['to_memberid'] = $_REQUEST['from_memberid']?$_REQUEST['from_memberid']:'0';
    	$params['id'] = $_REQUEST['assed_id'];
    	$params['content'] = $_REQUEST['content'];
        $params['memberid'] = $member_info['id'];
    	$service_res = $this->service($params);
        if(0===$service_res['errorcode']){
            $id = $service_res['dataresult']['id'];
            $str = '<div class="infoDetail detail'.$id.'" >';
            $str .= '<div class="borderDiv">';
            if(!empty($member_info['avatar'])){
                $str .= ' <img src="_STATIC_/Upload/avatar/'.$member_info['avatar'].'" alt="" class="cusHead"/>';
            }else{
                $str .= '<img src="_STATIC_/2015/Carlife/image/carsheads.png" alt="" class="cusHead"/>';
            }
            $str .= '<div class="nameandtime" onclick="reanswer('.$member_info['id'].')">';
            $str .= '<span class="DetailnameSapn sizeBig">'.$member_info['names'].'</span>';
            $str .= '<span class="rightDivlookwz sizeBig">'.date('m月d日 H:i',time()).'</span>';
            $str .= '</div>';
            if(!empty($params['to_memberid'])){
                $w['id'] = $params['to_memberid'];
                $to_member_info = M('member')->where($w)->find();
                $str .= '<div class="DetailtimeSpan sizeBig">';
                $str .= '<span class="answerSpan">回复</span>';
                $str .= '<span class="othername">'.$to_member_info['username'].'</span>';
                $str .= '<span>:</span>';
                $str .= $params['content'];
                $str .= '</div>';
            }else{
                $str .= '<span class="DetailtimeSpan sizeBig">'.$params['content'].'</span>';
            }
            $str .= '<input type="hidden" id="eval_id" value="'.$id.'">';
            $str .= '<span class="delteWz sizeOne" onclick="delteWz('.$id.')">删除</span>';
            $str .= '</div><div style="width:97%;border-top:1px solid #efefef;margin-left:3%;position:relative;float:left;"></div></div>';
            $this->ajaxReturn($str,'新增评论成功',1,'',1);
        }
        //$this->success("新增评论成功");
    	$this->error($service_res['errormsg']);
    }
    
    //删除评论
    public function del_eval(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = 'del_eval';
    	$params['id'] = $_REQUEST['eval_id'];
        $params['memberid'] = $member_info['id'];
    	$service_res = $this->service($params);
    	if(0===$service_res['errorcode'])
    		$this->success('删除评论成功','Carlife/index');
    	$this->error($service_res['errormsg']);
    }
    //删除文章
    public function delete_assed(){
        if(false==($member_info = $this->getMemberInfo()))redirect("/member/login?redirecturl=".urlencode("/carlife/index"));
    	$params = array();
    	$params['_cmd_'] = "carlife";
    	$params['type'] = 'delete_assed';
        $params['assed_id'] = $_REQUEST['assed_id'];
        $params['memberid'] = $member_info['id'];
    	$service_res = $this->service($params);
    	if(0===$service_res['errorcode'])
            $this->success("删除评论成功");
    	$this->error($service_res['errormsg']);
    }
    public function imagedisplay(){
        $src = $this->_get('pic');
        $lastname = $this->_get('lastname');
        $src = '_STATIC_/Upload/assed/'.$src.'.'.$lastname;
        $this->assign('src',$src);
        $index = $this->_get('index');
        if($index==2){
            $link = '/Carlife/detail/id/'.$this->_get('detail');
        }elseif($index==1){
            $link = '/Carlife/index';
        }
        $this->assign('link',$link);
        $this->display();
    }
}
