<?php
class Eqian{
    public $project_id = '1111563806';//测试：1111563517
    public $project_secret = '0c4275a48e17b0652f5246a91dd27ef3';//测试：95439b0863c241c63a861b87d1e647b7
    public $error = array();
    public $font_type = array(
        'square',//-正方形印章，
        'rectangle',//-矩形印章，
        'fzkc',//-艺术字印章，
        'yygxsf',//-艺术字印章，
        'hylsf',//-艺术字印章，
        'borderless',//-无框矩形印章，
        'hwls',//-华文隶书，
        'hwxk',//-华文行楷，
        'hwxkborder',//-华文行楷带边框，
        'ygyjfcs',//-叶根友疾风草书，
        'ygymbxs',//-叶根友毛笔行书
    );
    public function __CONSTRUCT() {
        $this->error['message'] = '';
        $this->return = array();
        vendor('Eqian.eSignOpenAPI','','.class.php');
        vendor('Eqian.class.eSign','','.class.php');
        vendor('Eqian.class.ErrorConstant','','.class.php');
        $this->sign = new eSign();
    }
    /*
     * init接口
     * $return
     * */
    public function init() {
        $iRet = $this->sign->init($this->project_id, $this->project_secret);
        if(0 == $iRet){
            if($this->sign->projectid_login()){
                return true;
            }else{
                $this->error['message'] = '登录失败';
            }
        }else{
            $this->error['message'] = $iRet['msg'];
        }
        return false;
    }

    /**
     * @param $data
     * @return bool
     * 创建个人账户
     */
    public function addPersonAccount($idNo,$personarea=0){
        //判断是否已经创建
        $where['certiNumber'] = $idNo;
        $member_info = M('member_info')
            ->join('member on member.id=member_info.memberid')
            ->where($where)->field('eqian_id,mobile,names,email')->find();
        if(empty($member_info)){
            $this->error['message'] = '该用户不存在或者未实名认证';
            return false;
        }
        if(!empty($member_info) && !empty($member_info['eqian_id'])){
            return $member_info['eqian_id'];
        }
        $email = empty($email)?'':$email;
        $return = $this->sign->addPersonAccount($member_info['mobile'], $member_info['names'], $idNo,$personarea, $email, '', '', '');
        /**
         *      写入数据库       返回的id*
        */
        if($return['errCode']==0){
            $da['eqian_id'] = $return['accountId'];
            $ret = M('member_info')->where($where)->save($da);
            if($ret === false){
                $this->error['message'] = '修改用户数据失败';
            }else{
                return $return['accountId'];
            }
        }else{
           $this->error['message'] = $return['msg'];
        }
        return false;
    }
    //注销个人账户
    public function delUserAccount($accountId){
        $result = $this->sign->delUserAccount($accountId);
        if($result['errCode']==0){
            return true;
        }else{
            $this->error = $result['msg'];
            return false;
        }
    }

    /**
     * @param $eqian_id e签登录id
     * @return bool
     * 创建个人模板印章
     */
    public function addTemplateSeal($eqian_id){
        $where['eqian_id'] = $eqian_id;
        $member_info = M('member_info')->where($where)->field('eqian_data,memberid')->find();
        if(empty($member_info)){
            $this->error['message'] = '该用户不存在或者未实名认证';
            return false;
        }
        if(!empty($member_info) && !empty($member_info['eqian_data'])){
            return $member_info['eqian_data'];
        }
        $return = $this->sign->addTemplateSeal($eqian_id,'rectangle');
        /**
         *      写入数据库       *
         */
        if($return['errCode']==0){
            $da['eqian_data'] = json_encode($return['sealData']);
            $ret = M('member_info')->where($where)->save($da);
            if($ret === false){
                $this->error['message'] = '修改用户数据失败';
            }else{
                $this->savePic($da['eqian_data'],$member_info['memberid']);
                return json_encode($return['sealData']);
            }
        }else{
            $this->error['message'] = $return['msg'];
        }
        return false;
    }
    //写入图片函数
    public function savePic($eqian_data,$memberid='boss'){
        //写入图片
        $sealdata_array = json_decode($eqian_data,true);
        $secd = $sealdata_array['data'];
        $third = base64_decode($secd);
        preg_match("/<(data.*?)>(.*?)<(\/data.*?)>/si",$third,$matched);
        $img=base64_decode($matched[2]);
        $file_path = APP_PATH."../static/Upload/eqian_pic/".$memberid.".png";
        file_put_contents($file_path,$img);
    }
    /**
     * 平台用户摘要签署短信
     * @parm  	$certiNumber:身份证号
     * 			$srcPdfFile：源pdf文档路径
     * 			$dstPdfFile:目的Pdf文档路径
     * 			$signType:
     * 
     * @return boolean|
     * 			
     */
    
    public function userSafeSignPDF($certiNumber,$srcPdfFile, $dstPdfFile, $signType=4, $posPage='', $posX='', $posY='', $key='', $fileName='',$code,$type=1,$order_id,$self = false){
        //初始化
        $isInit = $this->init();
        if(empty($isInit)){
            return false;
        }
        //创建用户
        $eqian_id = $this->addPersonAccount($certiNumber);
        if(empty($eqian_id)){
            return false;
        }
        // 创建个人模板印章
        $eqian_data = $this->addTemplateSeal($eqian_id);
        
        if($type==0){
            $return = $this->sign->userSafeSignPDF($eqian_id,$eqian_data,$srcPdfFile,$dstPdfFile,$signType,$posPage,$posX,$posY,$key,$fileName,$code);
        }else{
            $return = $this->sign->userSignPDF($eqian_id,$eqian_data,$srcPdfFile,$dstPdfFile,$signType,$posPage,$posX,$posY,$key,$fileName);
        }
        $return['errCode'] = 0;
        if($return['errCode']==0 && $self)return true;
        if($return['errCode']==0){
            //创建李总和公司签章
            $return = $this->selfSignPDF($dstPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY , $fileName,$type);
            if($return){
                $pathss = explode('/',$dstPdfFile);
                $signer = ($type==1)?($eqian_id.','.$return):$eqian_id;
                //文档保全 服务
                $return = $this->sign->saveSignedFile($dstPdfFile,$pathss[count($pathss)-1], $signer);
                if($return['errCode']==0){
                    return true;
                }else{
                    $this->error['message'] = $return['msg'];
                    return false;
                }
            }else{
                return false;
            }
            return $return;
        }else{
            $this->error['message'] = $return['msg'];
            return false;
        }
    }
    
    //仅仅个人签章
    public function userSafeSignPDF1($certiNumber,$srcPdfFile, $dstPdfFile, $signType=4, $posPage='', $posX='', $posY='', $key='', $fileName='',$code,$type=1,$order_id){
    	//初始化
    	$isInit = $this->init();
    	if(empty($isInit)){
    		return false;
    	}
    	//创建用户
    	$eqian_id = $this->addPersonAccount($certiNumber);
    	if(empty($eqian_id)){
    		return false;
    	}
    	// 创建个人模板印章
    	$eqian_data = $this->addTemplateSeal($eqian_id);
    	if($type==0){
    		$return = $this->sign->userSafeSignPDF($eqian_id,$eqian_data,$srcPdfFile,$dstPdfFile,$signType,$posPage,$posX,$posY,$key,$fileName,$code);
    	}else{
    		$return = $this->sign->userSignPDF($eqian_id,$eqian_data,$srcPdfFile,$dstPdfFile,$signType,$posPage,$posX,$posY,$key,$fileName);
    	}
    	if($return['errCode']==0){
    		return $return;
    	}else{
    		$this->error['message'] = $return['msg'];
    		return false;
    	}
    }
    //公司章
    public function userSafeSignPDF2( $srcPdfFile,$dstPdfFile, $posX='', $posY='', $key=''){
    	$return = $this->sign->selfSignPDF($srcPdfFile, $dstPdfFile, '' , 4,"", $posX, $posY, $key,"");
        if($return['errCode']==0){
            return true;
        }else{
            $this->error['message'] = $return['msg'];
            return false;
        }
    }
    /**
     *发送验签短信
     */
    public function sendSignCode($certiNumber){
        //初始化
        $isInit = $this->init();
        if(empty($isInit)){
            return false;
        }
        //创建用户
        $eqian_id = $this->addPersonAccount($certiNumber);
        if(empty($eqian_id)){
            return false;
        }
        //发送短信
        $return = $this->sign->sendSignCode($eqian_id);
        if(0 == $return['errorCode']){
            return true;
        }else{
            $this->error['message'] = $return['msg'];
            return false;
        }
    }
    /**
     *       生成公司章和李总章
     */
    public function selfSignPDF($srcPdfFile, $dstPdfFile, $signType, $posPage, $posX, $posY , $fileName,$type){
        if($type==1){   
            // 创建李总模板印章
            $wh['mobile'] = '18917777723';
            $info = M('eqian')->where($wh)->find();
            if(empty($info['eqian_id'])){//创建李总账号
                $return = $this->sign->addPersonAccount('18917777723', '李建富', '320923198412254539',0, '', '', '', '');
                if($return['errCode']==0){
                    $da['eqian_id'] = $return['accountId'];
                    $ret = M('eqian')->where($wh)->save($da);
                    if($ret === false){
                        $this->error['message'] = '修改用户数据失败';
                        return false;
                    }else{
                        $info['eqian_id'] = $return['accountId'];
                    }
                }else{
                    $this->error['message'] = $return['msg'];
                    return false;
                }
            }
            if(empty($info['eqian_data'])){//创建李总模板
                $return = $this->sign->addTemplateSeal($info['eqian_id'], 'rectangle');
                if($return['errCode']==0){
                    $dat['eqian_data'] = json_encode($return['sealData']);
                    $ret = M('eqian')->where($wh)->save($dat);
                    if($ret === false){
                        $this->error['message'] = '修改用户数据失败';
                        return false;
                    }else{
                        $this->savePic($da['eqian_data']);
                        $info['eqian_data'] = $dat['eqian_data'];
                    }
                }else{
                    $this->error['message'] = $return['msg'];
                    return false;
                }
            }
            //签章  
            $key = '甲方(盖章)';
            $return = $this->sign->userSignPDF($info['eqian_id'],$info['eqian_data'],$srcPdfFile,$dstPdfFile,$signType,$posPage, $posX, $posY, $key, $fileName);
            if($return['errCode']!=0){
                $this->error['message'] = $return['msg'];
                return false;
            }
            $company_key = '丙方(盖章)';
        }else{
            $company_key = '乙方(盖章)';
        }
        //公司签章
        $return = $this->sign->selfSignPDF($dstPdfFile, $dstPdfFile, '' , $signType, $posPage, $posX, $posY, $company_key, $fileName);
        if($return['errCode']==0){
            if($type==1){
                return $info['eqian_id'];
            }else{
                return true;
            }
        }else{
            $this->error['message'] = $return['msg'];
            return false;
        }
    }
    public function geterror(){
        return $this->error['message'];
    }

    /**
     * @param int $docId
     * @return bool
     *      获取保全文件地址
     * http://esignoss.oss-cn-hangzhou.aliyuncs.com/keepsignedfile/PDF_0415e88d-de65-4c18-9287-d60cf4f3f9dc?Expires=1474624800&OSSAccessKeyId=FBzUaPMorqiiUAfb&Signature=kKUhuplmWOfp9CicR5sSW4M4QU0%3D
     * */
    public function getSignedFile ($docId=204697){
        //初始化
        $isInit = $this->init();
        if(empty($isInit)){
            return false;
        }
        $return = $this->sign->getSignedFile($docId);
        if($return['errCode']==0){
            return $return['downUrl'];
        }else{
            $this->error['message'] = $return['msg'];
            return false;
        }
    }
}
