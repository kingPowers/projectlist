<?php

/* 
 * 智信创富金融
 */

/**
 * Description of CommonModel
 * 公用基类
 * @author Nydia
 */
class CommonModel extends Model {

    //计算密码
    public function gen_password($original) {
        return md5($original . C('SECURE_KEY'));
    }

    //获取站点
    public function getStation($stationId = 0) {
        $list = $return = array();
        $key = 'glabal_station';
        if (!APP_TEST) {
            $list = F($key);
        }
        if (empty($list)) {
            $list = M('Station')->field('id,name')->order('id ASC')->select();
            F($key, $list);
        }
        foreach ($list as $var)
            $return[$var['id']] = $var;
        return ($stationId > 0) ? $return[$stationId] : $return;
    }

    //查找单个站点
    public function getStationById($stationId = 0) {
        if ($stationId == 0) {
            return array('id' => 0, 'name' => '总站');
        }
        return $this->getStation($stationId);
    }

    //查找分类信息
    public function getCategory($cateId = 0) {
        $key = 'global_category';
        $category = F($key);
        if (empty($category)) {
            $category = array();
            $list = M('DemandCategory')->where(array('id'=>array('gt', 0)))->field('id,name')->order('id ASC')->select();
            foreach ($list as $cate) {
                $category[$cate['id']] = $cate;
            }
            F($key, $category);
        }
        return empty($cateId) ? $category : $category[$cateId];
    }
	
	//查找分类信息
	public function getLoanCategory($cateId = 0) {
		$global_loan_category = S('global_loan_category');
        if (empty($global_loan_category)) {
            $list = M('LoanCategory')->where(array('id'=>array('gt', 0),'status'=>1))->field('id,name')->order('id ASC')->select();
            foreach ($list as $cate) {
                $global_loan_category[$cate['id']] = $cate;
			}
			S('global_loan_category',$global_loan_category);
        }
		return empty($cateId) ? $global_loan_category : $global_loan_category[$cateId];
	}

	//查找标类型
	public function getLoanType($typeId = 0){
		$global_loan_type = S('global_loan_type');
        if (empty($global_loan_type)) {
            //$list = M('loan_category')->where(array('id'=>array('gt', 0),'status'=>1))->field('distinct(category_id) id,name')->order('id ASC')->select();
            $list = M('loan_category')->group('category_id ,big_category_id')->order('big_category_id')->where('status=1')->select();
            foreach ($list as $cate) {
                $global_loan_type[$cate['id']] = $cate;
			}
			S('global_loan_type',$global_loan_type);
        }
		return empty($typeId) ? $global_loan_type : $global_loan_type[$typeId];
	}

	//资金类型
	public function getMoneyType($codeId = 0){
		$global_money_type = S('global_money_type');
        if (empty($global_money_type)) {
            $list = M('MoneyType')->order('id ASC')->select();
            foreach ($list as $cate) {
                $global_money_type[$cate['id']] = $cate;
			}
			S('global_money_type',$global_money_type);
        }
		return empty($codeId) ? $global_money_type : $global_money_type[$codeId];
	}
}
