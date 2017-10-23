<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<style>
    .rec_h5{margin:16px 0 18px;height:19px;line-height:19px;border-left:10px #c8c8c8 solid;text-indent:8px;}
    .rec_data{overflow:hidden;margin-bottom:27px;}
    .rec_table{width:840px;}
    .rec_table th,.rec_table td{text-align:center;border:1px #c8c8c8 solid;line-height:39px;}
    .rec_table th{font-weight:bold;}
    .rec_table td{font-size:12px;}
    .rec_table td>a{color:#1a68c8;}
</style>
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/recharge">充值</a></li>
            <li class="now"><a href="/member/carry">提现</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
    </div>
    <h5 class="rec_h5">提现记录</h5>
    <div class="rec_data">
        <span class="c_left data_l" style="line-height: 25px;padding-left: 18px;">筛选：<select name="status" id="status">
            <option value="0" <if condition="$status eq 0">selected</if>>全部</option>
            <option value="3" <if condition="$status eq 3">selected</if>>已打款</option>
            <option value="1" <if condition="$status eq 1">selected</if>>通过</option>
            <option value="2" <if condition="$status eq 2">selected</if>>拒绝</option>
            <option value="4" <if condition="$status eq 4">selected</if>>个人取消</option>
            <option value="5" <if condition="$status eq 5">selected</if>>提现中</option>
        </select></span>
        <span class="c_right">提现成功总额：￥{$cashout}</span>
    </div>
    <table class="rec_table">
        <tr>
            <th>提现银行</th>
            <th>银行账号</th>
            <th>提现总额</th>
            <th>到账金额</th>
            <th style="width:173px;">提现时间</th>
            <th>状态</th>

        </tr>
        <volist name="list" id="vo">
        <tr>
            <td>{$member.memberInfo.bindBank}</td>
            <td>{$member.memberInfo.bindBankNum|substr=0,4}****{$member.memberInfo.bindBankNum|substr=12,18}</td>
            <td>￥{$vo.total}</td>
            <td>￥{$vo.amount}</td>
            <td>{$vo.timeadd|substr=0,10}</td>
            <td>
                <if condition="$vo.status eq 0">
                    <!-- <a href="javascript:;" class="cancel" onclick="return cancleCarry('{$vo.outsn}');">取消提现</a> -->    未审核
                <elseif condition="$vo.status eq 1"/>
                    通过
                <elseif condition="$vo.status eq 2"/>
                    拒绝
                <elseif condition="$vo.status eq 3"/>
                    已打款
                    <elseif condition="$vo.status eq 4"/>
                    个人取消
                    <elseif condition="$vo.status eq 5"/>
                    提现中
                </if>
            </td>

        </tr>
        </volist>

    </table>
<script>
    var cancleCarry = function(outsn){
        if(!outsn){
            return jdbox.alert(0,'获取订单号失败');
        }
        jdbox.alert(2);
        $.post("/member/cancelCarry.html",{'outsn':outsn},function(result){
            jdbox.alert(result.status,result.info);
            if(result.status){
                window.location.reload();
            }
        },'json');
    }
    $(function(){
        $("#status").change(function(){
            var id = $(this).val();
            location.href="/member/carry_rec/status/"+id;
        })
    })
</script>
<include file="Public:accountFooter"/>