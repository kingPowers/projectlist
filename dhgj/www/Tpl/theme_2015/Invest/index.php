<link rel="stylesheet" href="_STATIC_/2015/invest/css/index.css">
<!--content-->
<div class="content">
    <div class="c_nav">
        <p>当前位置：<a href="/">首页</a> &gt; <a href="/invest" class="c_nav_now">我要投资 </a></p>
    </div>
    <div class="center">
<!--          <div style="line-height:22px;height:26px;width:800px;font-size:22px;font-family:微软雅黑;color:#ea5413">投资有风险,入市需谨慎！</div>-->
        <div class="inv_banner"></div>
        <div style="line-height:30px;height:30px;width:800px;"><a href="/about/disciplineinfo2">网络借贷中介平台出借人提示</a> </div>
        <div class="inv_choose">
            <foreach name="menu" item="vo" key="key">
            <ul class="inv_choose_list">
                <li class="inv_first">>{$vo['name']}：</li>
                <foreach name="vo['menu']" item="v" key="k">
                    <li><a href="/invest/index?{$vo.url}&{$key}={$k}" <eq name="vo['select']" value="$k">class="now"</eq>>{$v.html}</a></li>
                </foreach>
            </ul>
            </foreach>
        </div>
        <div class="inv_deta">
            <div class="date_chos <if condition='!$status'>date_now</if>"><a href="/invest/index">当前投资</a></div>
            <div class="date_chos <if condition='$status eq 1'>date_now</if>"><a href="/invest/index?status=1">历史投资</a></div>
            <div class="inv_tab c_right">
                <p class="c_left">排序：</p>
                <ul class="c_left inv_tab_ul">
                    <foreach name="order" item="vo" key="key">
                    <li><a href="/invest/index?{$vo.url}&{$key}={$vo.select}">{$vo.name} <em>{$vo.top}</em></a></li>
                    </foreach>
                </ul>
            </div>
        </div>
        <div class="inv_b_box">
            <table class="inv_table">
                <tr>
                    <th class="table_first">精选信息</th>
                    <th style="width:120px;">保障方式</th>
                    <th style="width:100px;">额度</th>
                    <th style="width:125px;">预期年化收益</th>
                    <th style="width:100px;">投资期限</th>
                    <th style="width:180px;">完成进度</th>
                    <th style="width:120px;">操作</th>
                </tr>
                <foreach name="list" item="vo">
                <tr class="table_tr">
                    <td class="table_first"><img src="_STATIC_/2015/index/image/iconindex.jpg" style="float:left;margin-right:5px;"/><a href="/invest/detail/sn/{$vo['loansn']}.html">{$vo.title}</a></td>
                    <td>{$vo['security']}</td>
                    <td>￥{$vo.loanmoney}</td>
                    <td>{$vo['year_rate']}<if condition="$vo['activity_rate'] gt 0">+{$vo['activity_rate']}</if>%</td>
                    <td>
                        <if condition="$vo.isloanday eq 1">
                            <if condition="$vo.loanday eq 90">3月<elseif condition="$vo.loanday eq 30" />1月<else/>{$vo.loanday}天</if>
                        <else/>
                            {$vo.loanmonth}月
                        </if></td>
                    <td>
                        <span class="num">{$vo['tenderspeed']}%</span>
                        <span class="c_plen"><span class="speed"></span></span>
                    </td>
                    <td>

                        <if condition="$vo['loanstatus'] eq 1">
                            <a class="detail_a" href="/invest/detail/sn/{$vo['loansn']}.html">立即投标</a></div>
                        <elseif condition="$vo['loanstatus'] eq 3" />
                            <a class="detail_a inv_com" href="/invest/detail/sn/{$vo['loansn']}.html" disabled="disabled">招标完成</a></div>
                        <elseif condition="$vo['loanstatus'] eq 4" />
                            <a class="detail_a inv_com" href="/invest/detail/sn/{$vo['loansn']}.html" disabled="disabled">还款中</a></div>
                        <elseif condition="$vo['loanstatus'] eq 5" />
                            <a class="detail_a inv_com" href="/invest/detail/sn/{$vo['loansn']}.html" disabled="disabled">已还款</a></div>
                        </if>

                    </td>
                </tr>
                </foreach>
            </table>
            <div>{$page}</div>
        </div>
    </div>
</div>
<script>
    $(function(){

    })
</script>