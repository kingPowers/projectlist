<?php

//父类
class CommonModel extends Model {

    //计算密码
    public function gen_password($original) {
        return md5($original . C('SECURE_KEY'));
    }

    //获取菜单节点
    public function getMenuData($type = 'all') {
        $list = $return = array();
        $key = 'global_auth_rule_' . $type;
        if (!APP_TEST) {
            //$list = F($key);
        }
        if (empty($list)) {
            $map = array('type' => 1, 'status' => 1);
            if ($type == 'all') {
                $map = array('id' => array('gt', 0));
            }
            $list = M('AuthRule')->where($map)->order('pid ASC, sort ASC')->select();
            F($key, $list);
        }
        foreach ($list as $var) {
            if ($var['pid'] == 0) {
                $return[$var['id']] = $var;
            } else {
                $return[$var['pid']]['child'][] = $var;
            }
        }
        return $return;
    }

    //获取站点
    public function getStation() {
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
        return $return;
    }

    //查找单个站点
    public function getStationById($id = 0, $field = null) {
        $station = M('Station')->where("id={$id} AND id > 0")->find();
        return is_null($field) ? $station : $station[$field];
    }

    //贷款种类
    public function getDemandCategory() {
        $list = $data = array();
        $map = array_merge(array('id' => array('gt', 0)), $map);
        $key = 'demand_category_list';
        if (!APP_TEST)
            $data = F($key);
        if (empty($data)) {
            $data = M('DemandCategory')->where($map)->field('id,pid,name,comment,logo')->order('pid ASC,id ASC')->select();
            F($key, $data);
        }
        foreach ($data as $var)
            $list[$var['id']] = $var;
        return $list;
    }

    //获取标分类
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
    
    //获取用户扩展信息
    public function getMemberInfo($memberId = 0){
        if(empty($memberId)){
            return null;
        }
        $info = M('MemberInfo')->where(array('memberid'=>$memberId))->find();
        return $info;
    }
}
