<?php
//资金处理
class MoneyModel extends CommonModel
{
    //用户ID
    private $_M = 0;
    //资金CODE
    private $_C = null;
    //资金金额
    private $_A = 0;
    //关联SN
    private $_S = null;
    //操作说明
    private $_R = null;
    //扩展信息
    public $_EX= array();
    //操作前可用金额
    private $_BA=0;
    //操作后可用金额
    private $_AA=0;
    //前台计算的手续费，比较使用
    public $fee = 0;
    //提现优惠券
 //   public $coupon = '';
    //是否申请免收管理费
    public $checkfee = 0;
    /**
     * @brief e 资金操作入口
     *
     * @param $code
     * @param $memberid
     * @param $amount
     * @param $relationsn
     * @param $remark
     *
     * @return 
     */
    public function e($code=null, $memberid=0, $amount = 0, $relationsn=null, $remark = null)
    {
        $code = strtolower($code);
        if(!$this->_check_input($code,$memberid,$amount,$relationsn,$remark))
        {
            return false;
        }
        $R = $T = false;
        $function = "_e_$code";
        if(!method_exists($this,$function)){
            $this->error = '请求方法不存在';
            return false;
        }
        if(!$this->inTrans()){
            $this->startTrans();
            $T = true;
        }
        $R = $this->$function();
        if($R)
        {
            if($T) $this->commit();
            return true;
        }
        else
        {
            if($T) $this->rollback();
            return false;
        }
    }

    //输入信息检测
    private function _check_input($c,$m,$a,$s,$r)
    {
        if(is_null($c) || !in_array($c,array_keys($this->_get_money_type())))
        {
            $this->error = '请正确输入操作类型';
            return false;
        }
        if(empty($m) || $m <= 0 )
        {
            $this->error = '请正确输入用户ID';
            return false;
        }
        if(empty($a) || $a <= 0)
        {
            $this->error = '请正确输入资金金额';
            return false;
        }
        if(is_null($s))
        {
            $this->error = '请正确输入关联SN号';
            return false;
        }
        if(is_null($r))
        {
            $r = $s;
        }
        $this->_C = $c;
        $this->_M = $m;
        $this->_A = $a;
        $this->_S = $s;
        $this->_R = $r;
        return true;
    }

    //充值入口
    private function _e_cashin()
    {
        $memberinfo = M('member_info')->where("memberid={$this->_M}")->find();
        if(empty($memberinfo))
        {
            $memberinfo = M('member_info')->add(array('memberid'=>$this->_M));
        }
        if(empty($memberinfo))
        {
            $this->error = '查找用户扩展信息失败';
            return false;
        }
        $R = $this->_get_member_account($this->_M,$this->_C);
        if(empty($R))
        {
            $R = $this->_get_money_type($this->_C);
        }
        if(empty($R))
        {
            $this->error = '获取充值资金类型错误';
            return false;
        }
        $A = $D = false;
        if(isset($R['account'])&&isset($R['memberid']))
        {
            $map['memberid'] = $this->_M;
            $map['codeid'] = $R['codeid'];
            $A = M('money_account')->where($map)->setInc('account',$this->_A);
        }
        else
        {
            $data['memberid'] = $this->_M;
            $data['codeid'] = $R['id'];
            $data['account'] = $this->_A;
            $A = M('money_account')->add($data);
        }
        if(!$A)
        {
            $this->error = '修改用户充值资金账户失败';
            return false;
        }
        if($this->_e_update_member())
        {
            return $this->_write_money_detail();
        }
        return false;
    }

    //提现入口
    private function _e_cashout()
    {
        $m = 0;
        $l = array();
        $cl = $this->_get_member_account();
        foreach($cl as $v)
        {
            if($v['isrecover']==1)
            {//可提现
                $m += $v['account'];
                $l[] = $v;
            }
        }
        if($m<=0)
        {
            $this->error = '账户无资金';
            return false;
        }
        $FE = $this->getCarryOrderRealMoney($this->_M,$this->_A,$m);
        $amount = $this->_A;
        $fee = $FE['fee'];
        $this->_A = $total = sprintf("%.2f",$amount + $fee);
        if($total>$m)
        {
            $this->error = '资金不足';
            return false;
        }
        /*
        if($fee!=$this->fee)
        {
            $this->error = '计算手续费不同['.$fee.'!='.$this->fee.']';
            return false;
        }
        */
        if($this->_e_cashout_fun($l,$cl))
        {
            if($this->_e_update_member())
            {
                if(empty($this->_EX)||!is_array($this->_EX))
                {
                    $this->error = '获取资金扩展信息失败';
                    return false;
                }
                $this->_EX = array('A'=>$this->_EX);
                if($this->_e_cashout_cashin_order($FE))
                {
                    $data = array();
                    $data['memberid'] = $this->_M;
                    $data['total'] = $total;
                    $data['amount'] = $amount;
                    $data['fee'] = $fee;
                    $data['applyfee'] = $this->checkfee;
                    $data['outsn'] = $this->_S;
                    if($this->coupon){
                        $data['is_coupon'] = 1;
                    }
                    $data['extend']= serialize($this->_EX);
                    $F = M('cash_out')->add($data);
                    if($F)
                    {
                        $WMD = $this->_write_money_detail();
                        if($WMD){
                            //使用优惠劵后，修改优惠劵表字段状态
                            if($this->coupon)
                            {
                                $integral_coupons = M("integral_coupons");
                                $data = array();
                                $data['status'] = 1;
                                $data['outsn'] = $this->_S;
                                $data['spendingtime'] = date("Y-m-d H:i:s",time());
                                $IC_RES = $integral_coupons->where(array('consn'=>$this->coupon))->save($data);
                                if($IC_RES){
                                    return true;
                                }else {
                                    $this->error = '优惠券消费失败';
                                    return false;
                                }
                            }
                            return true;
                        }
                        return false;
                    }
                    $this->error = '生成提现订单失败';
                    return false;
                }
                return false;
            }
            return false;
        }
        return false;
    }

    //投标入口
    private function _e_tender()
    {
        $m = 0;
        $l = array();
        $cl = $this->_get_member_account();
        foreach($cl as $v)
        {
            if($v['ispay']==1)
            {//可消费
                $m += $v['account'];
                $l[] = $v;
            }
        }
        if($m <= 0 || $this->_A > $m)
        {
            $this->error = '您账户可用资金不足';
            return false;
        }
        if($this->_e_tender_pay($l,$cl))
        {
            if($this->_e_update_member())
            {
                return $this->_write_money_detail();
            }
            return false;
        }       
        return false;
    }

    /**
     * @brief 获取资金类型
     *
     * @param $code 查询单条资金类型
     * @param $key 返回类型
     *
     * @return 
     */
    private function _get_money_type($code=null, $key='code', $l=array())
    {
        static $r = array();
        if(empty($r))
        {
            $r = M('money_type')->select();
        }
        foreach($r as $v)
        {
            $l[$v[$key]] = $v;
        }
        if(is_null($code))
        {
            return $l;
        }
        return $l[$code];
    }

    //获取用户资金列表
    private function _get_member_account($memberid=0, $code=null, $l=array())
    {
        $memberid = $memberid ? $memberid : $this->_M;
        if(empty($memberid))
        {
            return 0;
        }
        $r = M('money_account')->where("memberid={$memberid}")->select();
        if(empty($r))
        {
            return null;
        }
        $m = $this->_get_money_type(null,'id');
        foreach($r as $v)
        {
            $t = $m[$v['codeid']];
            unset($t['id']);
            $l[$t['code']] = array_merge($v,$t);
        }
        if(is_null($code))
        {
            return $l;
        }
        return $l[$code];
    }

    //写入资金详情
    private function _write_money_detail($allTyep=20)
    {
        $data               = array();
        $code               = $this->_get_money_type($this->_C);
        $data['codeid']     = $code['id'];
        $data['memberid']   = $this->_M;
        $data['beforeamount']= $this->_BA;
        $data['amount']    = $this->_A;
        $data['afteramount']= $this->_AA;
        $data['relationsn'] = $this->_S;
        $data['remark']     = $this->_R;
        $data['status']     = $this->_get_write_detail_default_status();
        $data['detailsn']   = create_sn('detail',($allTyep-$code['id']));
        $F                  = M('money_detail')->add($data);
        if(!$F)
        {
            $this->error = '添加流水详情失败';
            return false;
        }
        return true;
    }

    //详情默认状态
    private function _get_write_detail_default_status()
    {
        $S = array('tender'=>1, 'cashin'=>2, 'cashout'=>0);
        return isset($S[$this->_C]) ? $S[$this->_C] : 0;
    }

    private function _e_tender_pay($p = array(), $cl=array())
    {
        $l = $m = array();
        //优先级排序
        for($i=0;$i<(count($p)-1);$i++)
        {
            for($j=$i+1;$j<count($p);$j++)
            {
                if($p[$i]['paysort']>$p[$j]['paysort'])
                {
                    $m = $p[$i];
                    $p[$i] = $p[$j];
                    $p[$j] = $m;
                }
            }
        }
        //循环扣款
        $B = $D = $F = false;
        $A = $this->_A;
        $C = $T = 0;
        $Model = M('money_account');
        foreach($p as $v)
        {
            if($v['account']<=0)
            {
                continue;
            }
            $C = $A - $v['account'];
            if($C > 0)
            {//不够
                $A = $C;
                $C = $v['account'];
            }
            else
            {
                $D = true;
                $C = $A;
            }
            $T += $C;
            $map['id']       = $v['id'];
            $map['memberid'] = $v['memberid'];
            $map['codeid']   = $v['codeid'];
            $data['account'] = array('exp',"account-{$C}");
            $F               = $Model->where($map)->save($data);
            $ex = array('codeid'=>$v['codeid'],'code'=>$v['code'],'amount'=>$C);
            array_push($this->_EX,$ex);
            if(!$F)
            {
                $B = true;
                break;
            }
            if($D)
            {//已扣完
                break;
            }
        }
        if($B || !$D)
        {
            $this->error = '扣除投标资金账户失败';
            return false;
        }
        if($T != $this->_A)
        {
            $this->error = '扣除投标资金不正确';
            return false;   
        }
        $tender = $cl[$this->_C];
        if(isset($tender))
        {
            $F = $Model->where("memberid={$this->_M} AND codeid={$tender['codeid']}")->setInc('account',$this->_A);
        }
        else
        {
            $tender = $this->_get_money_type($this->_C);
            $F = $Model->add(array('memberid'=>$this->_M,'codeid'=>$tender['id'],'account'=>$this->_A));
        }
        if(!$F)
        {
            $this->error = '变更投标资金账户失败';
            return false;
        }
        return true;
    }

    private function _e_cashout_fun($p = array(), $cl=array())
    {
        $l = $m = array();
        //优先级排序
        for($i=0;$i<(count($p)-1);$i++)
        {
            for($j=$i+1;$j<count($p);$j++)
            {
                if($p[$i]['recoversort']>$p[$j]['recoversort'])
                {
                    $m = $p[$i];
                    $p[$i] = $p[$j];
                    $p[$j] = $m;
                }
            }
        }
        //循环扣款
        $B = $D = $F = false;
        $A = $this->_A;
        $C =  $T = 0;
        $Model = M('money_account');
        foreach($p as $v)
        {
            if($v['account']<=0)
            {
                continue;
            }
            $C = $A - $v['account'];
            if($C > 0)
            {//不够
                $A = $C;
                $C = $v['account'];
            }
            else
            {
                $D = true;
                $C = $A;
            }
            $T += $C;
            $map['id']       = $v['id'];
            $map['memberid'] = $v['memberid'];
            $map['codeid']   = $v['codeid'];
            $data['account'] = array('exp',"account-{$C}");
            $F               = $Model->where($map)->save($data);
            $ex = array('codeid'=>$v['codeid'],'code'=>$v['code'],'amount'=>$C);
            array_push($this->_EX,$ex);
            if(!$F)
            {
                $B = true;
                break;
            }
            if($D)
            {//已扣完
                break;
            }
        }
        if($B || !$D)
        {
            $this->error = '扣除提现资金账户失败';
            return false;
        }
        if($T != $this->_A)
        {
            $this->error = '扣除提现资金不正确';
            return false;   
        }
        $cashout = $cl[$this->_C];
        if(isset($cashout))
        {
            $F = $Model->where("memberid={$this->_M} AND codeid={$cashout['codeid']}")->setInc('account',$this->_A);
        }
        else
        {
            $cashout = $this->_get_money_type($this->_C);
            $F = $Model->add(array('memberid'=>$this->_M,'codeid'=>$cashout['id'],'account'=>$this->_A));
        }
        if(!$F)
        {
            $this->error = '变更提现资金账户失败';
            return false;
        }
        return true;
    }

    //充值订单扣除提现资金
    private function _e_cashout_cashin_order($FE=array())
    {
        $I = false;
        $B = array();
        if($FE['all']>0)
        {
            $H = $G = false;
            $TA = $FE['ta'];
            $n = $i = 0;
            foreach($FE['list'] as $v)
            {
                $n = $TA - $v['laveamount'];
                if($n>0)
                {//不够
                    $TA = $n;
                    $n = $v['laveamount'];
                }
                else
                {
                    $H = true;
                    $n = $TA;
                }
                $_m = $_d = array();
                $_m['memberid'] = $v['memberid'];
                $_m['insn'] = $v['insn'];
                $_d['laveamount'] = array('exp',"laveamount - {$n}");
                $B[] = array_merge($_m,array('amount'=>$n));
                $F = M('cash_in')->where($_m)->save($_d);
                if(!$F)
                {
                    $this->error = '更新充值订单提现金额失败';
                    $G = true;
                    break;
                }
                if($H)
                {
                    break;
                }
                $i++;
                if($i == count($FE['list']))
                {
                    $H = true;
                    break;
                }
            }
            if(!$G && $H){
                $I = true;
                $this->_EX['B'] = $B;
            }
        }
        else
        {
            $I = true;
        }
        return $I;
    }

    //更新用户资金
    private function _e_update_member($memberid=0)
    {
        $memberid = $memberid ? $memberid : $this->_M;
        if(!$memberid)
        {
            $this->error = '获取用户ID失败';
            return false;
        }
        $m = 0;
        $l = $this->_get_member_account($memberid);
        foreach($l as $v)
        {
            if( ($v['ispay']==1) && ($v['account']>0))
            {
                $m += $v['account'];
            }
        }
        $data = $map = array();
        $map['memberid'] = $this->_M;
        $Model = M('member_info');
        $MI = $Model->where($map)->find();
        $data['availableAmount'] = $m;
        $this->_BA = $MI['availableAmount'];
        $this->_AA = $m;
        if($this->_C =='cashout')
        {
            $data['cashoutAmount'] = array('exp',"cashoutAmount + {$this->_A}");
        }
        $F = $Model->where($map)->save($data);
        if(!$F)
        {
            $this->error = '更新用户基础信息失败';
            return false;
        }
        return true;
    }

    public function getCarryOrderRealMoney($memberid=0,$amount=0,$T=0,$fee=0,$mfee=5,$point=0.003)
    {
        $mfee = C('CARRY_COUNTER_FEE');
        $point = sprintf("%1\$.3f",C('CARRY_MANFEE_POINT'));
        
        $map['memberid'] = $memberid;
        $map['status'] = 2;
        $map['timeadd'] = array('gt',date('Y-m-d H:i:s',strtotime('-15 day')));
        $list = M('cash_in')->where($map)->order('id DESC')->select();
        $M = $A = 0;
        $data = $cil = array();
        if(!empty($list))
        {
            foreach($list as $v)
            {
                if($v['laveamount']>0)
                {
                    $M += $v['laveamount'];
                    array_push($cil,$v);
                }
            }
        }
        $A = (($T-$M) > 0) ? ($T-$M):0;
        if($amount>$A)
        {
            $fee = sprintf("%1\$.2f",($amount-$A)*$point);
        }
        if($this->coupon){
            $mfee = 0;
        }
        $fee += $mfee;
        $cashin_kouchu = ($amount +$fee-$A>0)?(sprintf("%.2f",$amount+$fee-$A)):0;
        $data['fee'] = $fee;
        $data['list'] = $cil;
        $data['all'] = $M;
        $data['ta'] = $cashin_kouchu;
        return $data;
    }

}
?>
