<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recomm.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/recomm">邀请方式</a></li>
            <li><a href="/member/friend">好友管理</a></li>
            <li class="now"><a href="/member/privilege">特权收益</a></li>
        </ul>
    </div>
    <div class="priv_box">
        <table class="fir_table">
            <tr>
                <th style="width:129px;">序号</th>
                <th style="width:128px;">投资人</th>
                <th style="width:151px;">投资期限</th>
                <th style="width:151px;">投资利益</th>
                <th style="width:151px;">投资金额</th>
                <th style="width:148px;">特权收益率</th>
                <th style="width:143px;">特权收益</th>
                <th style="width:131px;">发放时间</th>
            </tr>
            <volist name="list" id="vo" key="k" >
            <tr>
                <td>{$nums+$k}</td>
                <td>{$vo.username}</td>
                <td>
                    <if condition="$vo.isloanday eq 1">
                        <if condition="$vo['loanday'] eq 90">3月<elseif condition="$vo['loanday'] eq 30" />1月<else/>{$vo['loanday']} 天</if>
                    <else/>{$vo.loanmonth}月</if></td>
                <td>{$vo.l_rate}%</td>
                <td>{$vo.tendermoney}元</td>
                <td>{$vo.rate}%</td>
                <td>{$vo.money}元</td>
                <td>{$vo.timeadd|substr=0,10}</td>
            </tr>
            </volist>
        </table>

        <div>{$page}</div>
        <p>累计推荐人数：<span>{$ucount|default=0}</span> | 累计推荐投资奖励：<span>￥{$privilegemoney|default=0.00}</span></p>
        <p>注：活动时间：2015年11月11日——2016年3月28日</p>
        <p>推荐活动特权金1%已结束，感谢您的参与！</p>
    </div>

<include file="Public:accountFooter"/>