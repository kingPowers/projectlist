<?php

/* 
 * 智信创富金融
 */

/**
 * Description of MemberModel
 * 用户类
 * @author Nydia
 */
class MemberModel extends CommonModel {

    public $message = '';

    //查找用户Member和Member_info信息
    public function getMember($memberId = 0) {
        $member = $this->where(array('id' => $memberId))->find();
        if (empty($member)) {
            return null;
        }
        $member['memberInfo'] = M('MemberInfo')->where(array('memberid' => $memberId))->find();
        $borrow = M('BorrowerPersonal')->field('id')->where(array('memberid' => $memberId))->find();
        if($borrow) {
            $member['isBorrow'] = M('BorrowerPersonal')->getLastSql();
        } else {
            $member['isBorrow'] = 0;
        }
        return $member;
    }

    //检测唯一
    public function uniqueMember($username = '', $mobile = '') {
        return $this->where("username='{$username}' OR mobile = '{$mobile}'")->select();
    }

    //更新SESSION
    public function updateSession() {
        $member = session('member');
        if (empty($member['id'])) {
            return false;
        }
        $member = M('Member')->where(array('id' => $member['id']))->find();
        $member['memberInfo'] = M('MemberInfo')->where(array('memberid' => $member['id']))->find();
        $borrow = M('BorrowerPersonal')->field('id')->where(array('memberid' => $member['id'],'status'=>2))->find();
        if($borrow) {
            $member['isBorrow'] = 1;
        } else {
            $member['isBorrow'] = 0;
        }
        session('member', $member);
        return true;
    }

    //绑定信息
    public function bindIdent($post = array()) {
        $post['type'] = strtolower($post['type']);
        $fields = array(
            'name' => array('name' => 'names', 'number' => 'certiNumber'),
            'bank' => array('bank' => 'bindBank', 'branch' => 'bindBankSubbranch', 'province' => 'bindBankProvince', 'city' => 'bindBankCity', 'number' => 'bindBankNum'),
            'email' => array('email' => 'email', 'code' => 'code'),
        );
        $field = $fields[$post['type']];
        if (empty($field)) {
            $this->message = '绑定类型错误';
            return false;
        }
        $member = $_SESSION['member'];
        $memberInfo = $_SESSION['member']['memberInfo'];
        $data = array();
        foreach ($field as $key => $var) {
            if (isset($post[$key]) && !empty($post[$key])) {
                $data[$var] = $post[$key];
            }
        }
        if (count($data) != count($field)) {
            $this->message = '绑定数据不完整';
            return false;
        }
        $key = "{$post['type']}Status";
        if ($memberInfo[$key] > 0) {
            $this->message = '您已提交过认证信息';
            return false;
        }
        $data[$key] = 1; //修改为已认证
        if ($post['type'] == 'name') {
            if(D('Shenfenzheng')->checkIdentity($data['certiNumber'])===false){
                $this->message = '请正确输入身份证号';
                return false;
            }
            $has_this_certnum = M('member_info')->where(array('certiNumber'=>$data['certiNumber']))->find();
            if($has_this_certnum){
                $this->message = '此身份证已经绑定过了';
                return false;
            }
            /*
            if (!preg_match('/^\d{10,}\w?$/', $data['certiNumber'])) {
                $this->message = '请正确输入身份证号';
                return false;
            }
            */
        } else if ($post['type'] == 'bank') {
            $namestatus = M('member_info')->field('nameStatus,bindBaofuName')->where(array('memberid'=>$memberInfo['memberid']))->find();
            if($namestatus['nameStatus'] == 0){
                $this->message = '请先实名认证';
                return false;
            }
            if (!preg_match('/^\d{10,}$/', $data['bindBankNum'])) {
                $this->message = '请正确输入银行卡号';
                return false;
            }
            $five_banks = array('工商银行','招商银行','农业银行','兴业银行','建设银行');
            if(in_array($data['bindBank'],$five_banks)){
                $data['five_banks'] = 1;
            }
            if (FUND_CUSTODY){
                if($namestatus['bindBaofuName'] == 0){
	                $this->message = '请先绑定托管资金帐户';
	                return false;
            	}
            	// 绑定银行卡到宝付帐户
            	$user_id = $member['id'];
            	$bind_type = 1;// 0删除1新增
            	$bank_no = $post['number'];
            	$pro_value = $post['province'];
            	$city_value = $post['city'];
            	$bank_name = $post['bank'];
            	$bank_address = $post['branch'];
            	$validate_code = $post['verifycode'];
            	$bindbaofoobank_res = $this->baofoo_bindbank($user_id,$bind_type,$bank_no,$pro_value,$city_value,$bank_name,$bank_address,$validate_code);
            	if(!$bindbaofoobank_res){
            		$this->message = '绑定失败，请重新操作';
            		return false;
            	}
            	$data['bindBaofuBank'] = 1;
            }
        } else if ($post['type'] == 'email') {
            if (!isEmail($data['email'])) {
                $this->message = '请正确输入邮箱';
                return false;
            }
            $verify = session('emailVerify');
            if ($verify['email'] != $data['email'] || $verify['code'] != $data['code']) {
                $this->message = '请使用验证邮箱信息';
                return false;
            }
            session('emailVerify', null);
            unset($data['code']);
        }
        $result = M('MemberInfo')->where(array('memberid' => $memberInfo['memberid']))->save($data);
        if (!$result) {
            $this->message = '更新数据失败';
            return false;
        }
        $this->updateSession();
        return true;
    }

    //更新用户头像
    public function updateMemberAvatar($path = '') {
        $return = array('code' => 1, 'msg' => '更新缩略图信息失败');
        $member = session('member');
        if (empty($path)) {
            return $return;
        }
        $result = M('MemberInfo')->where(array('memberid'=>$member['id']))->save(array('avatar'=>$path));
        if ($result == false) {
            return $result;
        }
        $member['memberInfo']['avatar'] = $path;
        session('member',$member);
        $return['code'] = 0;
        $return['msg'] = $path;
        D('Sso')->rsync_avatar($member);
        return $return;
    }

    //取得邀请码发送人id
    public function checkInvcode($invcode) {
        $where['invitecode'] = $invcode;
        $invsender = M('member')->where($where)->field("id")->find();
        if($invsender){
           //判断是否是借款人
           $isBorrowerWhere['memberid'] = $invsender['id'];
           $isBorrower = M('BorrowerPersonal')->where($isBorrowerWhere)->field("id")->find();
           if($isBorrower){
               return false;
           }else{
               return $invsender['id'];
           }
        }else{
            return false;
        }
    }

    //生成邀请码
    public function createInvcode($mobile) {
        for($i=4;$i<=8;$i++){
            for($j=0;$j<10;$j++){
                $code=substr($mobile, -$i).array_rand(range(0,9)).array_rand(range(0,9)).array_rand(range(0,9));
                if(!$this->checkInvcode($code)){
                    return $code;
                }
            }
        }
    }

	/**
	 * 更新用户存钱罐收益与最新登陆时间
	 * @member     用户信息
	 */
    public function piggybank($member){
        $parameter['identity_id'] = $member['sinaid'];
        $F = D('Sinaport')->query_balance($parameter);
        $updata = array();
        $updata['lasttime'] =  date('Y-m-d H:i:s');
        if($F['status']==1){
            $bonus = explode('^',$F['data']['bonus']);
            $money = $bonus[2] - $member['piggybankAmount'];
            if($money){
                import('Think.ORG.Util.Money');
                $result = Money::getCollectMoney($member['id'],$money,8);
                if($result) {
                    $updata['piggybankAmount'] =  $bonus[2];
                    M('Member')->where(array('id' => $member['id']))->save($updata);
                }
            }
        }
        
    }

}
