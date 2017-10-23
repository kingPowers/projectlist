<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/tender.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li <if condition="$status eq 1">class="now"</if>><a href="/member/tender/status/1">募集中</a></li>
            <li <if condition="$status eq 2">class="now"</if>><a href="/member/tender/status/2">计息中</a></li>
            <li <if condition="$status eq 3">class="now"</if>><a href="/member/tender/status/3">已到期</a></li>
            <li <if condition="$status eq 4">class="now"</if>><a href="/member/tender/status/4">未计息</a></li>
        </ul>
    </div>
    <div class="tender_div">
        <table class="tender_table">
            <tr>
                <th style="width:210px;">项目名称</th>
                <th style="width:71px;">类型</th>
                <th style="width:78px;">年利率</th>
                <th style="width:71px;">期限</th>
                <th style="width:100px;">投资金额</th>

                <if condition="$status eq 1">
                    <th style="width:92px;">项目进度</th>
                    <th style="width:94px;">投资时间</th>
                    <th style="width:106px;">投标来源</th>
                <elseif condition="$status eq 4"/>
                    <th style="width:92px;">投资时间</th>
                    <th style="width:94px;">状态</th>
                    <th style="width:106px;">操作</th>
                <else/>
                    <th style="width:92px;">总计本息</th>
                    <th style="width:94px;">到期时间</th>
                    <th style="width:106px;">操作</th>
                </if>
           
            </tr>

            <foreach name="list" item="vo">
            <tr>
                <td><a href="/invest/detail/sn/{$vo['loansn']}.html" target="_blank">{$vo.title}</a></td>
                <td>{$vo.name}</td>
                <td>
                    <if condition="$vo['ratecouponid'] eq 0">
                        {$vo['year_rate']}<if condition="$vo['activity_rate'] gt 0">+{$vo['activity_rate']}</if>%
                        <else/>
                        {$vo['year_rate']}<if condition="$vo['activity_rate'] gt 0">+{$vo['activity_rate']}</if>+{$vo['rate']-$vo.yearrate}%
                        </if>
                    </td>
                <td>
                    <if condition="$vo['isloanday'] eq 1">
                        <if condition="$vo['loanday'] eq 90">3月<elseif condition="$vo['loanday'] eq 30" />1月<else/>{$vo['loanday']} 天</if>
                    <else/>{$vo.loanmonth}个月</if>
                </td>
                <td>￥{$vo.money}</td>

                <if condition="$status eq 1">

                    <td>{$vo['tendermoney']/$vo['loanmoney']*100|round=2}%</td>
                    <td>{$vo['timeadd']|substr=0,10}</td>
                    <td>
                        <if condition="$vo.type eq 1">
                            网页
                            <elseif condition="$vo.type eq 2"/>
                            自动投标
                            <elseif condition="$vo.type eq 3"/>
                            手机
                        </if>
                    </td>
                <elseif condition="$status eq 4"/>
                    <td>{$vo.timeadd}</td>
                    <td>流标</td>
                    <td>
                        <if condition="$vo.money_status eq 1">
                            <font coler="red">款未回账户</font>
                        <else/>
                            <font>款已回账户</font>
                        </if>
                    </td>
                    <else/>

                    <td>{$vo['money']+$vo['ratemoney']}</td>
                    <td>
                        <if condition="$vo['endtime'] eq '0000-00-00 00:00:00'">
                            审核中
                        <else/>
                            {$vo['endtime']|substr=0,10}
                        </if>       
                    </td>
                    <td>
                        <a href="javascript:;" class="tender_detail">还款明细</a>

                        <div class="tender_tip">
                            <span class="tip_sp"></span>
                            <div class="tip_box">
                                <ul class="tip_ul">
                                    <li style="width:90px;">期数</li>
                                    <li style="width:134px;">还款时间</li>
                                    <li class="tip_li_money">还款金额</li>
                                    <li class="tip_last">状态</li>
                                </ul>
                                <div class="tip_b_scroll">
                                    <foreach name="vo.allotlist" item="v" key="k">
                                    <ul class="tip_ul_each">
                                        <li style="width:90px;">{$k+1}期</li>
                                        <li style="width:134px;">{$v['endtime']|substr=0,10}</li>
                                        <li class="tip_li_money">￥{$v.money}</li>
                                        <li class="tip_last">
                                            <span style="color:#009900;">
                                                <if condition="$v['status'] eq 1">
                                                    已还
                                                <else/>
                                                    未到期
                                                </if>
                                            </span>
                                        </li>
                                    </ul>
                                    </foreach>
                                </div>
                            </div>

                        </div>

                       <if condition="$vo['istransfer'] neq 2"> | <a href="/invest/downpact/tendersn/{$vo['tendersn']}">协议</a></if>
                    </td>
                </if>


            </tr>
            </foreach>

        </table>
    </div>
<div>{$page}</div>

<script>
    $(".tender_detail").hover(function(){
        var tW = $(".tender_tip").width()-55;
        var tR = $(this).position().left-tW;
        var tT = $(this).position().top+22;
        $(this).siblings(".tender_tip").css({top:tT,left:tR});
        $(this).siblings(".tender_tip").toggle();
    })
</script>

<include file="Public:accountFooter"/>