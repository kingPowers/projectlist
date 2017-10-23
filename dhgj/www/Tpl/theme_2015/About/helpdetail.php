<include file="About:aboutMenu"/>
<style>
    .titleCl{width:800px;height:auto;margin:0 auto;padding-top:10px;}
    .titleCl img{width:21px;height:21px;float:left;}
    .titleCl span{margin-left:5px;font-size:16px;font-weight:bold;margin-bottom:10px;}
    .contentInfo{width:800px;height:auto;margin:0 auto;font-size:16px;margin-bottom:12px;}
    .contentInfo img{float:left;}
    .ceontinfode{width:700px;height:auto;margin-left:25px;line-height:25px;}
     .nextQus:hover{color:goldenrod}


</style>
<div class="c_right about_r">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/about">关于我们</a> &gt; <a href="{$catelink[$catename['id']]}">{$catename['name']}</a>  &gt; 内容详情</p>
        </div>
    </div>

    <div class="about_r_box" >
        <foreach name="info" item="help_info">
            <div class="titleCl"><img src="_STATIC_/2015/about/image/w.jpg" alt=""/><span>{$help_info['ask']}</span></div>
            <div  class="contentInfo">
                <img src="_STATIC_/2015/about/image/d.jpg" alt=""/>
                <p class="ceontinfode">{$help_info['answer']}</p>
            </div>
        </foreach>
    </div>
   
</div>

<include file="About:aboutFooter"/>