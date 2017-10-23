<?php

/**
 * @file myqrcode.class.php
 * @brief 生成个人名片
 */
class myqrcode {
	//用户memberid
	private $memberid = '';
	//用户信息
	private $member_info = "";
	//用户默认头像路径
	private $default_head ="";
	//用户个人头像路径
	private $my_head = "";
	//名片背景图片路径，必须为.png
	private $background = "";
	//二维码路径
	private $save_qrcode = "";
	//二维码的url
	private $qrcode_url = "";
	//个人名片路径
	private $my_card = "";
	//金牌经纪人路径
	//金牌经纪人二维码路径
	private $agent_save_qrcode = "";
	//金牌经纪人二维码的url
	private $agent_qrcode_url = "";
	
	private $error = "";
	
	function __construct($memberid){
		$this->memberid = $memberid;
		$member_info = M('member_info')->table("member m,member_info mi")->where("mi.memberid='{$this->memberid}' and m.id=mi.memberid")->find();
		if(false==$member_info){
			$this->error = "用户信息不存在";
			return false;
		}
		$this->member_info = $member_info;
		$this->default_head =  APP_PATH."../static/2015/member/image/account/heads.png";
		$this->background = APP_PATH."../static/Upload/background.png";
		$this->save_qrcode = APP_PATH."../static/Upload/qrcode/{$this->memberid}/qrcode_{$this->memberid}.png";
		$this->qrcode_url = C("TMPL_PARSE_STRING._WWW_")."/member/register?jieba=register&recintcode={$member_info['mobile']}";
		$this->my_card = APP_PATH."../static/Upload/qrcode/{$this->memberid}/card_{$this->memberid}.png";
		$this->createqrcode();
		$this->qrcode2background();
		$this->createGold();//生成金牌经纪人二维码
	}
	
	/*
	 * 背景图和二维码组合
	 * */
	public function qrcode2background(){
		if(!is_file($this->background)){
			$this->error = "背景图片不存在";
			return false;
		}
		if(!is_file($this->save_qrcode)){
			$this->error = "二维码图片不存在";
			return false;
		}
		
		//if(is_file($this->my_card))return false;
		if(is_file($this->my_card) && S("card_{$this->memberid}"))return false;
		S("card_{$this->memberid}",1,3*60*60);
		
		$desc_srouce = imagecreatefrompng($this->background);
		$src_srouce = imagecreatefrompng($this->save_qrcode);
		list($src_width, $src_height) = getimagesize($this->save_qrcode);
		$desc_srouce = $this->word2png($desc_srouce);
		imagecopymerge($desc_srouce,$src_srouce,162,405,0,0,$src_width,$src_height,100);
		imagepng($desc_srouce,$this->my_card);
	}
	
	//个人名片填充汉字
	public function word2png($source){
		$color = imagecolorallocate($source, 255, 255, 255);
		$font_size = APP_PATH."../static/Upload/msyh.ttc";
		$name = $this->member_info['names']?$this->member_info['names']:$this->member_info['username'];
		imagettftext($source,16,0,205,135,$color,$font_size,"姓       名：{$name}");
		imagettftext($source,16,0,205,175,$color,$font_size,"手机号码：{$this->member_info['mobile']}");
		return $source;
	}
	
	//个人名片填充汉字
	public function word2pngGold($source,$goldInfo){
		$color = imagecolorallocate($source, 255, 255, 255);
		$font_size = APP_PATH."../static/Upload/msyh.ttc";
		$name = $this->member_info['names']?$this->member_info['names']:$this->member_info['username'];
		imagettftext($source,16,0,205,95,$color,$font_size,"姓       名：{$name}");
		imagettftext($source,16,0,205,135,$color,$font_size,"公       司：{$goldInfo['company_name']}");
		imagettftext($source,16,0,205,175,$color,$font_size,"手机号码：{$this->member_info['mobile']}");
		return $source;
	}
	
	/*
	 * 生成个人二维码
	 * 
	 * */
	public function createqrcode(){
		vendor("phpqrcode.phpqrcode");
		$dir= dirname($this->save_qrcode);
		if(!is_dir($dir))mkdir($dir,0755);
		if(!is_file($this->save_qrcode))
			QRcode::png($this->qrcode_url,$this->save_qrcode,"H",5);
	}
	public function createagentqrcode()
	{
		vendor("phpqrcode.phpqrcode");
		$dir= dirname($this->agent_save_qrcode);
		if(!is_dir($dir))mkdir($dir,0755);
		if(!is_file($this->agent_save_qrcode))
			QRcode::png($this->agent_qrcode_url,$this->agent_save_qrcode,"H",5);
	}
	
	public function createGold(){
		$goldInfo = M('gold_agent ga')
		->field("ga.*,m.mobile")
		->join("member m on m.id=ga.memberid")
		->where("memberid='{$this->memberid}'")
		->find();
		if(false==$goldInfo)return false;
		if(empty($goldInfo['mobile']) || empty($goldInfo['company_name']))return false;
		$this->agent_save_qrcode = APP_PATH."../static/Upload/qrcode/{$this->memberid}/agent_{$this->memberid}.png";
		$mobile = $goldInfo['mobile'];
        $this->agent_qrcode_url = C("TMPL_PARSE_STRING._WWW_")."/agent/bind_agent?jieba=webpage&agentRecintcode={$mobile}";
        $this->createagentqrcode();
		$card_name = "gold_{$this->memberid}.png";
		$background = APP_PATH."../static/Upload/goldBackground.png";
		$saveUrl = APP_PATH."../static/Upload/qrcode/{$this->memberid}/{$card_name}";
		if(!is_dir(APP_PATH."../static/Upload/qrcode/{$this->memberid}"))mkdir($dir,0755);
		if(!is_file($background)){
			$this->error = "背景图片不存在";
			return false;
		}
		if(!is_file($this->agent_save_qrcode)){
			$this->error = "二维码图片不存在";
			return false;
		}
		
		if(is_file($saveUrl) && S("gold_{$this->memberid}"))return false;
		S("gold_{$this->memberid}",1,3*60*60);
		$desc_srouce = imagecreatefrompng($background);
		$src_srouce = imagecreatefrompng($this->agent_save_qrcode);
		list($src_width, $src_height) = getimagesize($this->agent_save_qrcode);

		$desc_srouce = $this->word2pngGold($desc_srouce,$goldInfo);
		imagecopymerge($desc_srouce,$src_srouce,140,375,0,0,$src_width,$src_height,100);
		imagepng($desc_srouce,$saveUrl);
		if(false==$goldInfo['pic_card'])
			M("gold_agent")->where("memberid='{$this->memberid}'")->save(array('pic_card'=>$card_name));
	}
	
}

?>
