<style>
    .c_nav_box{background:#eeeeec;}
    .active_banner{height:360px;}
    .active_banner>a{display:block;background-position:center 0!important;background-repeat:no-repeat!important;width:100%;height:100%;}
    .hdzq_hdTitle{height:70px;margin-top:1px;background:#f7f7f7;border-bottom:2px #cfcfcf solid;}
    #myTab1{line-height:60px;text-align:center;}
    #myTab1>.hdzq_tab_item{float:left;width:192px;margin:10px 13.5px 0;height:70px;cursor:pointer;z-index:2;position:relative;}
    #myTab1>.on{background:url(_STATIC_/2015/activity/image/hdzq_hdTabs.png) no-repeat bottom center;font-weight:bold;color:#ea5413;}
    .hdzq_slide_content{margin-top:8px;height:auto;}
    .myTab_Content{display:none;}
    .hdzq_tabslider{margin-top:48px;width:1150px;overflow:hidden;}
    .hdzq_hdBox{width:343px;height:349px;border:2px #e0e0e0 solid;margin:0 29px 26px 0;float:left;}
    .hdzq_hd_title{padding-top:4px;font:20px/57px "微软雅黑";color:#ea5413;text-indent:13px;}
    .hdzq_hd_title .hdzq_new{font-size:12px;font-family:Arial;color:#ed0000;margin-left:7px;text-transform:Uppercase;}
    .hdzq_hd_pic,.hdzq_hd_pic img{width:343px;height:164px;}
    .hdzq_hd_pic{margin-bottom:15px;}
    .hdzq_hdBox p{text-indent:10px;}
    .hdzq_hd_xq_btn{color:#ea5413;display:block;width:95px;height:32px;line-height:32px;text-align:center;border:1px #ea5413 solid;margin-left:14px;margin-top:14px;}
    .hdzq_bottom{margin:60px 0 50px;}
    .hdzq_bottom a{display:block;margin:0 auto;width:591px;height:95px;}
    .hdzq_bottom a>img{width:591px;height:95px;vertical-align:middle;}
</style>


<!--content-->
<div class="content">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/activity" class="c_nav_now">活动专区 </a></p>
        </div>
    </div>
    <div class="active_banner">
        <a href="/member/register" style="background:url(_STATIC_/2015/activity/image/active_banner.jpg);"></a>
    </div>
    <div class="hdzq_hdTitle">
        <div class="center">
            <ul id="myTab1">

                <foreach name="activity" item="vo" key="k">
                <li class="hdzq_tab_item <if condition='$k eq 0'>on</if>">{$vo.name}</li>
                </foreach>

                <div style="clear:both;"></div>
            </ul>
        </div>
    </div>
    <div class="hdzq_slide_content">
        <div class="center">
            <foreach name="activity" item="vo" key="k">
            <div id="myTab_Content{$k}" class="myTab_Content" <if condition='$k eq 0'>style="display:block;"</if>>
                <ul class="hdzq_tabslider">

                    <foreach name="vo.data" item="v" key="key">
                    <li class="hdzq_hdBox">
                        <h3 class="hdzq_hd_title">{$key+1}：{$v.title} <span class="hdzq_new">new</span></h3>
                        <div class="hdzq_hd_pic">
                            <if condition="$v['islink'] eq 1">
                                
                            <a <if condition="$v['islink'] eq 1">href="{$v['linkurl']}"<else/>href="/about/detail/id/{$v['id']}.html"</if> target="_blank">
                                <img src="_STATIC_{$v.filepath}{$v.filename}" alt="">
                            </a>
                            <else/>
                                <img src="_STATIC_{$v.filepath}{$v.filename}" alt="">
                            </if>
                        </div>
                        <if condition="$k eq 4">
                        <p>爱心主题：{$v.summary}</p>
                        <else/>
                        <p>活动时间：{$v.keywords}</p>
                        <p>活动主题：{$v.summary}</p>
                        </if>
                        
                        <if condition="$v['islink'] eq 1">
                            <a href="{$v['linkurl']}" target="_blank" class="hdzq_hd_xq_btn">
                                <if condition="$k eq 4">爱心<else/>查看</if>详情
                            </a>
                        <else/>
                            <span class="hdzq_hd_xq_btn">
                                <if condition="$k eq 3">筹备中
                                <elseif condition="$k eq 4"/>爱心详情
                                <else />查看详情</if>
                            </span>
                        </if>
                    </li>
                    </foreach>

                </ul>
            </div>
            </foreach>


            <div id="myTab_Content1" class="myTab_Content">
                <ul class="hdzq_tabslider">
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">2：老投资用户返现</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_02.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">2：聚沙成塔</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_03.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">2：首投返现</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_04.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">2：上海站巡回讲座</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_05.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">2：爱心助学</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_06.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                </ul>
            </div>
            <div id="myTab_Content2" class="myTab_Content">
                <ul class="hdzq_tabslider">
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">3：聚沙成塔</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_03.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">3：首投返现</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_04.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">3：上海站巡回讲座</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_05.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">3：爱心助学</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_06.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                </ul>
            </div>
            <div id="myTab_Content3" class="myTab_Content">
                <ul class="hdzq_tabslider">
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">4：首投返现</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_04.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">4：上海站巡回讲座</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_05.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">4：爱心助学</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_06.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                </ul>
            </div>
            <div id="myTab_Content4" class="myTab_Content">
                <ul class="hdzq_tabslider">
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">5：首投返现</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_04.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">5：上海站巡回讲座</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_05.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                    <li class="hdzq_hdBox"><h3 class="hdzq_hd_title">5：爱心助学</h3><div class="hdzq_hd_pic"><a href="" target="_blank"><img src="_STATIC_/2015/activity/image/active_06.jpg" alt=""></a></div><p>活动时间：永久</p><p>活动主题：注册一次，送一次打理</p><a href="" target="_blank" class="hdzq_hd_xq_btn">查看详情</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="hdzq_bottom">
        <a href="/member/register" target="_blank"><img src="_STATIC_/2015/activity/image/hdzq_btn.jpg" alt=""></a>
    </div>
</div>
<script>
    $(function(){
        $(".hdzq_tab_item").each(function(i){
            $(this).click(function(){
                var myID = "#myTab_Content"+i;
                if($(this).hasClass("on")){
                    return;
                }
                $(this).addClass("on").siblings(".hdzq_tab_item").removeClass("on");
                $(".hdzq_slide_content").find(myID).show().siblings(".myTab_Content").hide();
            })
        })
    })
</script>