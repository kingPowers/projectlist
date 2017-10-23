<include file="About:aboutMenu"/>
<style>
    .nt_ul{padding:14px 14px 65px 25px;}
    .nt_ul>li{line-height:53px;height:53px;border-bottom:1px #aaa dotted;background:url(_STATIC_/2015/about/image/nt_y.png) no-repeat 0 center;text-indent:20px;}
    .nt_sp{color:#999;}
    .nt_p{max-width:700px;height:53px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;-ms-text-overflow:ellipsis;}
    .nt_p a:hover{color:#ea5413;text-decoration:none;}
</style>
<div class="c_right about_r">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/about/zxnews" class="c_nav_now">果粉资讯</a></p>
        </div>
    </div>
    <div class="about_r_box">
        <h5 class="about_h5">果粉资讯</h5>
        <ul class="nt_ul">
            <foreach name="news" item="vo">
            <li>
                <p class="c_left nt_p"><a <eq name="$vo['islink']" value="1"> href="{$vo['linkurl']}"<else/>href="/about/detail/id/{$vo['id']}.html"</eq> target="_blank">{$vo.title}</a></p>
                <span class="c_right nt_sp">{$vo.timeadd|substr=0,10}</span>
            </li>
            </foreach>
        </ul>
        <div>{$page}</div>
    </div>
</div>

<include file="About:aboutFooter"/>
