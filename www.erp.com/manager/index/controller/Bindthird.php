<?php
namespace manager\index\controller;

use manager\index\model\Fuyou;
/**
* 绑定富有等账户
*/
class Bindthird extends Common
{
	//绑定页面
	public function bindAccount ()
	{
		if ($this->request->post("change_pro") == 1) {//修改省市
			$province_code = $this->request->post("province_code");
			$cityObj = new \manager\index\model\FuyouCity();
			$cityObj->where['province_code'] = $province_code;
			$cityList = $cityObj->cityList();
			return $this->ajaxSuccess('成功',$cityList);
		}
		$this->assign("banks",$this->getBanks());
		$this->assign("province",$this->getProvince());
		return $this->view->fetch();
	}
	//绑定富有
	public function bindFuyou ()
	{
		if ($this->request->post("is_fuyou") == 1) {
			$fuyou = new Fuyou();
			if (false == $fuyou->reg($this->request->post())) {
				return $this->ajaxError($fuyou->getError());
			}
			return $this->ajaxSuccess("绑定成功");
		}
	}
	//获取银行
	public function getBanks ()
	{
		$bank = new \manager\index\model\FuyouBank();
		return $bank->bankList();
	}
	//获取省市
	public function getProvince ()
	{
		$proObj = new \manager\index\model\FuyouCity();
		return $proObj->provinceList();
	}
	//获取区县
}