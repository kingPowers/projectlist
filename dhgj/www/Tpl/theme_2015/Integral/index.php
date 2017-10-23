<link rel="stylesheet" href="_STATIC_/2015/integral/css/index.css">
<div class="content">
    <div class="c_nav">
        <p>当前位置：<a href="/">首页</a> &gt; <a href="/integral" class="c_nav_now">安币商城 </a></p>
    </div>
    <div class="center">
        <div class="inte_banner"><img src="_STATIC_/2015/integral/image/inte_banner.png" alt=""></div>
        <div class="inte_t">
            <ul class="inte_t_ul">
                <li><a href="/integral/index" <if condition="!$cid"> class="inte_curr"</if>>全部</a></li>
                <foreach name="cate" item="v" >
                <li><a href="/integral/index/cid/{$v['id']}" <if condition="$v['id'] eq $cid"> class="inte_curr"</if>>{$v['name']}</a></li>
                </foreach>
            </ul>
        </div>
        <div class="inte_b">
            <ul class="inte_b_ul">

                <foreach name="list" item="vo" >
                <li>
                    <a href="/integral/detail/id/{$vo['id']}">
                        <div class="inte_b_top"><img src="_STATIC_{$vo.filepath}{$vo.filename}" alt=""></div>
                        <div class="inte_b_middle">
                            <h5 class="inte_h5">{$vo['name']}</h5>
                            <p class="inte_p">
                                <em>领取条件：{$vo['integral']}安心金币</em>
                                <span class="inte_sp">{$vo['exchange']}</span>
                            </p>
                        </div>
                        <div class="inte_b_btm">
                            <div class="plen"><div class="speed"></div></div>
                            <div class="inte_time">
                                <div class="time_left c_left">
                                    <p>剩余时间</p>
                                    <p>{$vo['days']}天</p>
                                </div>
                                <div class="time_right c_right">
                                    <if condition="$vo['number'] gt 0">
                                    <p>剩余名额</p>
                                    <p>{$vo['number']}份</p>
                                    <else/>
                                        <p>抢完了</p>
                                    </if>
                                </div>
                                <if condition="$vo['qian'] eq 1">
                                <div class="time_center">
                                    <p class="time_pt"><img src="_STATIC_/2015/integral/image/inte_t.png" alt=""></p>
                                    <p>疯抢中</p>
                                </div>
                                </if>
                            </div>
                        </div>
                    </a>
                </li>
                </foreach>
            </ul>
        </div>
    </div>
</div>