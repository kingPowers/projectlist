<link rel="stylesheet" href="_STATIC_/2015/invest/css/index.css">
<!--content-->
<div class="content">
    <div class="c_nav">
        <p>当前位置：<a href="/">首页</a> &gt; <a href="/invest" class="c_nav_now">项目介绍 </a></p>
    </div>
    <div class="center">
        <div class="inv_banner"></div>
        <div class="inv_choose">
            <foreach name="menu" item="vo" key="key">
                <ul class="inv_choose_list">
                    <li class="inv_first">>{$vo['name']}：</li>
                    <foreach name="vo['menu']" item="v" key="k">
                        <eq name="key" value="type">
                            <li><a href="/invest/index?{$vo.url}&{$key}={$k}">{$v.html}</a></li>
                        <else/>
                            <li><a href="/invest/blist?{$vo.url}&{$key}={$k}" <eq name="vo['select']" value="$k">class="now"</eq>>{$v.html}</a></li>
                        </eq>
                    </foreach>
                    <eq name="key" value="type">
                        <li><a href="/invest/blist" class="now">安心转让</a></li>
                    </eq>
                </ul>
            </foreach>
        </div>
        <div class="inv_b_box">
            <table class="inv_table">
                <tr>
                    <th class="table_first">项目名称</th>
                    <th style="width:120px;">期限</th>
                    <th style="width:100px;">预期年化</th>
                    <th style="width:125px;">转让金额</th>
                    <th style="width:100px;">剩余时间</th>
                    <th style="width:180px;">保障方式</th>
                    <th style="width:120px;">操作</th>
                </tr>
                <foreach name="list" item="vo">
                <tr class="table_tr">
                    <td class="table_first"><a href="/invest/bdetail/sn/{$vo['loansn']}.html">{$vo.title}</a></td>
                    <td><if condition="$vo.isloanday eq 1">{$vo.loanday}天<else/>{$vo.loanmonth}个月</if></td>
                    <td>{$vo['year_rate']}<if condition="$vo['activity_rate'] gt 0">+{$vo['activity_rate']}</if>%</td>
                    <td>￥{$vo.zrmoney}</td>
                    <td>{$vo.difftime}</td>
                    <td>{$vo['security']}</td>
                    <td>
                        <a href="/invest/bdetail/sn/{$vo.tendersn}" class="detail_a" target="_blank">立即投资</a>
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