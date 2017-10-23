<style>
    .news_p{text-indent:25px;padding-top:6px;}
    .about_r_box{padding-bottom:20px;font-family:"微软雅黑";}
    .news_ul{padding:28px 24px 0 25px;;}
    .news_ul>li{margin-bottom:40px;overflow:hidden;}
    .news_img,.news_img img{width:358px;height:233px;}
    .news_img{margin-right:25px;}
    .news_r{width:480px;}
    .news_r .news_r_t{font:18px/20px "微软雅黑";padding-bottom:7px;border-bottom:1px #ea5413 solid;}
    .news_r .news_r_t>a{color:#ea5413;text-decoration:none;}
    .news_r_m{overflow:hidden;}
    .news_r_m>a{color:#c0472f;}
    .news_r_m>span{color:#9a9a9a;}
    .news_r_b{margin-top:8px;}
    .news_r_b .news_rb_a{display:block;text-align:right;color:#ea5413;}
    .news_r_b .news_con{height:143px;overflow:hidden;}
</style>
<include file="About:aboutMenu"/>
<div class="c_right about_r">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/about/news" class="c_nav_now">媒体报道</a></p>
        </div>
    </div>
    <div class="about_r_box">
        <h5 class="about_h5">媒体报道</h5>
<!--        <p class="news_p">智信创富品牌快速成长和迅速发展，吸引了各界人士的关注，通过媒体朋友的报道则使更多人加深了对我们的了解。</p>-->
        <ul class="news_ul">

            <foreach name="news" item="vo">
            <li>
                <div class="c_left news_img"><img src="_STATIC_{$vo.filepath}{$vo.filename}" alt="媒体报道"></div>
                <div class="c_left news_r">
                    <div class="news_r_t"><a <eq name="$vo['islink']" value="1"> href="{$vo['linkurl']}"<else/>href="/about/detail/id/{$vo['id']}.html"</eq> target="_blank">{$vo.title}</a></div>
                    <div class="news_r_m">
                        <a <eq name="$vo['islink']" value="1"> href="{$vo['linkurl']}"<else/>href="/about/detail/id/{$vo['id']}.html"</eq> target="_blank" class="c_left">{$vo.source}</a>
                        <span class="c_right">{$vo.timeadd|substr=0,10}</span>
                    </div>
                    <div class="news_r_b">
                        <p class="news_con">{$vo.summary}</p>
                        <a <eq name="$vo['islink']" value="1"> href="{$vo['linkurl']}"<else/>href="/about/detail/id/{$vo['id']}.html"</eq> target="_blank" class="news_rb_a">【详情】</a>
                    </div>
               </div>
            </li>
            </foreach>

        </ul>
        <div>{$page}</div>
    </div>
</div>

<include file="About:aboutFooter"/>