<include file="About:aboutMenu"/>
<style>
    .about_h5{padding:20px 0;margin:0 20px;}
    .about_r_box p{padding:0 17px 0 25px;margin-bottom:10px;text-indent:28px;line-height:30px;}
    .about_h6{color:#313130;font-size:24px;font-family:"微软雅黑";text-align:center;padding-top:112px;}
    .about_r_box .about_indent{text-indent:0;margin:60px 0 3px;}
    img{max-width:95%;}
</style>
<div class="c_right about_r">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/about">关于我们</a> &gt; <a href="{$catelink[$catename['id']]}">{$catename['name']}</a>  &gt; 内容详情</p>
        </div>
    </div>

    <div class="about_r_box">
        <h5 class="about_h5" style="border-bottom:1px #aaa dotted;">{$rs['title']} <span style="line-height: 20px;margin-top:15px;font-size: 12px;float: right;">{$rs.timeadd}</span></h5>
        <div style="padding-top: 20px;">
            {$rs['content']}
        </div>
    </div>
</div>

<include file="About:aboutFooter"/>