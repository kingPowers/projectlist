<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<style>
    .rec_h5{margin:16px 0 18px;height:19px;line-height:19px;border-left:10px #c8c8c8 solid;text-indent:8px;}
    .rec_data{overflow:hidden;margin-bottom:27px;}
    .data_l{padding-left:18px;line-height:25px;}
    .data_l select{width:84px;height:23xp;border:1px #c8c8c8 solid;outline:none;}
    .rec_table{width:840px;}
    .rec_table th,.rec_table td{text-align:center;border:1px #c8c8c8 solid;line-height:39px;}
    .rec_table th{font-weight:bold;}
    .rec_table td{font-size:12px;}
</style>
   <div class="rec_t">
        <ul class="ba_min_nav">
            <li class="now"><a href="/member/recharge">充值</a></li>
            <li><a href="/member/carry">提现</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
    </div>
    <h5 class="rec_h5">充值记录</h5>
    <div class="rec_data">
        <span class="c_left data_l">筛选：<select name="status" id="status">
                <option value="0" <if condition="$status eq 0">selected</if>>全部</option>
                <option value="2" <if condition="$status eq 2">selected</if>>充值成功</option>
                <option value="1" <if condition="$status eq 1">selected</if>>未支付</option>
                <option value="3" <if condition="$status eq 3">selected</if>>充值失败</option>
            </select></span>
        <span class="c_right">充值成功总额：￥{$cashin}</span>
    </div>
    <table class="rec_table">
        <tr>
            <th style="width:200px;">订单号</th>
            <th>充值金额</th>
            <th>充值时间</th>
            <th style="width:240px;">详细</th>
            <th>状态</th>
        </tr>
        <volist name="list" id="vo">
        <tr>
            <td>{$vo.insn}</td>
            <td>￥{$vo.amount}</td>
            <td>{$vo.timeadd}</td>
            <td>{$vo.remark}</td>
            <td><if condition="$vo.status eq 0">
                    未支付
                    <elseif condition="$vo.status eq 1"/>
                    未付款
                    <elseif condition="$vo.status eq 2"/>
                    充值成功
                    <elseif condition="$vo.status eq 3"/>
                    充值失败
                </if></td>
        </tr>
        </volist>

    </table>
    <div>{$page}</div>
<script>
$(function(){
    $("#status").change(function(){
        var id = $(this).val();
        location.href="/member/recharge_rec/status/"+id;
    })
})
</script>
<include file="Public:accountFooter"/>