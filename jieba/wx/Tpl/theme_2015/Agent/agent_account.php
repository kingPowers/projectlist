<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css"> 
    <title>{$pageseo.title}</title> 
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
    $(function(){
        $(".ewmiconImg").click(function () {       
        $(".bgg_div").css("display", "block");
        $(".fx_div").css("display", "block");         
        $(".ewm_div").css("display", "block");       
        $(".save_div").css("display", "none");
        });
        $(".fx_div").click(function(){
           $(".bgg_div").css("display", "none");
           $(".fx_div").css("display", "none");         
           $(".ewm_div").css("display", "none");       
           $(".save_div").css("display", "block"); 
        })
    })        
    </script>
</head>
<body style="background: #efefef;">
<!--弹出二维码  部分 开始-->
<section class="fx_div">
    <section class="bgg_div" style="display: block;"></section> 
    <section class="ewm_div">
        <img src="{$agent_info['pic_card']}" style="width: 100%; display: block;">
        <span>长按名片保存到相册</span>
        <!--保存相册 部分-->
        <section class="save_div">
            <img src="_STATIC_/2015/member/image/account/savebtn.png" alt="" class="savebtnImg"/>
        </section>
    </section>
</section>
<!--弹出二维码  部分 结束-->
<section class="bg_title">
    <img src="_STATIC_/2015/image/yydai/agent/ewmicon.png" alt="" class="ewmiconImg"/>
    <section class="head_div">
        <section class="img_div">
            <section style="background:url(<empty name='agent_info.avatar'>_STATIC_/2015/member/image/account/heads.png<else/>{$agent_info['avatar']}</empty>)no-repeat center center; background-size: cover;  background-position-y: 50%;" class="headsImg"></section>           
        </section>
        <section class="j_name">
            <span>{$agent_info['nickname']}</span>
            <font>{$agent_info['company_name']}</font>
            <p>金牌经纪人<eq name="agent_info['is_vip']" value="1"><img src="{$agent_info['vip_picurl']}"></eq></p>
        </section>       
    </section>
</section>
<section class="bg_ac">
    <img src="_STATIC_/2015/image/yydai/agent/bg_ac.png">
</section>
<div class="ddDiv">
    <a href="/agent/order_submit/from/1">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_shuai.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize" onclick="javascript:window.location.href='/agent/order_submit'">我要甩单</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a>
    <a href="/agent/my_order_lists">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_my.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize" onclick="javascript:window.location.href='/agent/my_order_lists'">我的订单</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a> 
    <a href="/agent/purchase_history">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_xiao.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize"  onclick="javascript:window.location.href='/agent/purchase_history'">消费记录</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a>
    <a href="/agent/recommand">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/member/image/account/yqjlicon.jpg" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize"  onclick="javascript:window.location.href='/agent/purchase_history'">邀请记录</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a>  
    <a href="/agent/edit_agent_info">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_jing.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize" onclick="javascript:window.location.href='/agent/edit_agent_info'">修改信息</span>
            <font>认证名片可加V</font>           
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a> 
    <a href="/agent/about">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_qu.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize" onclick="javascript:window.location.href='/agent/about'">关于甩单</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a> 
    <a href="/agent/grades_description">
        <div class="myorderDiv">
            <img src="_STATIC_/2015/image/yydai/agent/ico_lv.png" alt="" class="myodeiconImg"/>
            <span class="myorderwz numsize" onclick="javascript:window.location.href='/agent/grades_description'">等级说明</span>
            <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
        </div>
    </a> 
</div>
</body>

</html>