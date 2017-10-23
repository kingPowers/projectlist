<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recomm.css">
<script src="_STATIC_/2015/member/js/WdatePicker.js"></script>
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/recomm">邀请方式</a></li>
            <li class="now"><a href="/member/friend">好友管理</a></li>
            <li><a href="/member/privilege">特权收益</a></li>
        </ul>
    </div>
    <div class="fir_box_t">
        <form action="" method="get">
        查询范围：
            <input type="text" name="starttime" onclick="WdatePicker();" class="input" value="{$starttime}">
            到
            <input type="text" name="endtime" onclick="WdatePicker();" class="input" value="{$endtime}">
        <button class="button">查询</button>
        </form>
    </div>
    <div class="fir_box_b">
        <p>累计推荐人数：<span>{$count}</span> | 累计推荐投资奖励：<span>￥{$privilegemoney|default=0.00}</span></p>
        <table class="fir_table">
            <tr>
                <th style="width:118px;">序号</th>
                <th style="width:398px;">邀请好友</th>
                <th style="width:319px;">注册时间</th>
            </tr>
            <foreach name="list" item="vo" key="k">
            <tr>
                <th style="width:118px;">{$nums+$k+1}</th>
                <th style="width:398px;">{$vo.username}</th>
                <th style="width:319px;">{$vo.timeadd}</th>
            </tr>
            </foreach>
        </table>
    </div>

<div>{$page}</div>

<include file="Public:accountFooter"/>