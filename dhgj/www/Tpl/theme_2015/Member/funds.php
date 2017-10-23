<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/funds.css">
    <ul class="ba_b_t">
        <li>账户详情</li>
    </ul>
    <div class="fund_head">
        <p class="fund_title c_left"><i class="c_left"></i>个人资金详情</p>
        <span class="fund_h_r c_right"> 资产总额：<b>{$money+$member['memberInfo']['availableAmount']+$cashout|default=0.00}</b>元</span>
    </div>
    <ul class="fund_con">
        <li class="fund_list">
            <span><span class="fund_list_l">资产总额：</span>￥{$money+$member['memberInfo']['availableAmount']+$cashout|default=0.00}</span>
            <span><span class="fund_list_l">可用余额：</span>￥{$member['memberInfo']['availableAmount']|default=0.00}</span>
        </li>
        <li class="fund_list">
            <span><span class="fund_list_l">提现中：</span>￥{$cashout|default=0.00}</span>
            <span><span class="fund_list_l">投资中：</span>￥{$money|default=0.00}</span>
        </li>
        <li class="fund_list">
            <span><span class="fund_list_l">已充值：</span>￥{$cashin|default=0.00}</span>
            <span><span class="fund_list_l">已提现：</span>￥{$cashoutok|default=0.00}</span>
        </li>
    </ul>
    
    <div class="fund_head">
        <p class="fund_title c_left"><i class="c_left"></i>总收益</p>
        <span class="fund_h_r c_right"> 总收益：<b>{$ratemoney+$redpacketmoney+$privilegemoney|default=0.00}</b>元</span>
    </div>
    <ul class="fund_con">
        <li class="fund_list">
            <span><span class="fund_list_l">利息收益：</span>￥{$ratemoney-$ratecouponmoney|default=0.00}</span>
            <span><span class="fund_list_l">红包/返现：</span>￥{$redpacketmoney+$privilegemoney|default=0.00}</span>
        </li>
        <li class="fund_list">
            <span><span class="fund_list_l">加息收益：</span>￥{$ratecouponmoney|default=0.00}</span>
<!--            <span><span class="fund_list_l">特权收益：</span>￥{$privilegemoney|default=0.00}</span>-->
        </li>
    </ul>

<if condition="$member['isBorrow'] eq 1">
    <div class="fund_head">
        <p class="fund_title c_left"><i class="c_left"></i>放标资金详情</p>
        <span class="fund_h_r c_right"> 放标资金：<b>{$borrow_money['loan']+$borrow_money['repay']|default=0.00}</b>元</span>
    </div>
    <ul class="fund_con">
        <li class="fund_list">
            <span><span class="fund_list_l">借款总资金：</span>￥{$borrow_money['loan']|default=0.00}</span>
            <span><span class="fund_list_l">还款总资金：</span>￥{$borrow_money['repay']|default=0.00}</span>
        </li>
    </ul>
</if>
<!--元宝详情-->
<!--    <div class="fund_head">
        <p class="fund_title c_left"><i class="c_left"></i>元宝详情<span style='color: #C00'>(春节活动)</span></p>
        <span class="fund_h_r c_right"> 元宝总数：<b>{$goldingot['sum']}</b>个</span>
    </div>
    <ul class="fund_con">
        <li class="fund_list">
            <span><span class="fund_list_l">本人投资：</span>{$goldingot['tender']}个</span>
            <span><span class="fund_list_l">好友投资：</span>{$goldingot['recom_tender']}个</span>
        </li>
        <li class="fund_list">
            <span><span class="fund_list_l">邀请好友：</span>{$goldingot['recom_reg']}个</span>
            <span><span class="fund_list_l">当前排名：</span>第<strong style="color:#a47848">{$goldingot['ranking']}</strong>名</span>
        </li>
    </ul>-->

    <!--我的金币-->
<!--    <div class="fund_head">
        <p class="fund_title c_left"><i class="c_left"></i>总金币</p>
        <span class="fund_h_r c_right"> 总金币：<b>{$member['memberInfo']['integral']+$useintegral}</b>金币</span>
    </div>
    <ul class="fund_con">
        <li class="fund_list">
            <span><span class="fund_list_l">可用金币：</span>{$member['memberInfo']['integral']}金币</span>
            <span><span class="fund_list_l">已用金币：</span>{$useintegral}金币</span>
        </li>
    </ul>-->


<include file="Public:accountFooter"/>