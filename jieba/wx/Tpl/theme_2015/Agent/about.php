<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $("#headers").css("display","block");
            }
            else{
                $(".mui-repay-view").css("margin-top","10px");
            }
        })
        //判断是否在微信中打开
        function isWeixin() {
            var ua = navigator.userAgent.toLowerCase();
            if (ua.match(/MicroMessenger/i) == "micromessenger") {
                return true;
            } else {
                return false;
            }
        }
    </script> 
    <title>{$pageseo.title}</title>   
</head>
<body style="background: #efefef;">
<header id="headers" style="display: none;">
	<a href="/agent/agent_account" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>常见问题</h1>    
</header>
<section class="mui-repay-view">
    <section class="mui-help-view-cell" id="btn_jk">
        <font>甩单常见问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <section class="j_question" id="jk_div">
        <section class="mui-j_question-view-cell">
            <h2>一、如何甩单？</h2>
            <p>甩单首页点击我要甩单，如实填写相关信息后点击“立即甩单”即可甩单，两小时后您的甩单即可在甩单列表中展示</p>
        </section>
        <section class="mui-j_question-view-cell">
            <h2>二、甩单上架</h2>
            <p>经纪人提交甩单后，客服审核无问题后，订单将于两小时后在甩单页展示。如甩单内容设计违规内容，客服将驳回甩单，请经纪人根据回复内容修改，修改完成后重新提交审核</p>
        </section>
        <section class="mui-j_question-view-cell">
            <h2>三、修改订单</h2>
            <p>甩单处于“审核中”或者“已上架无人解锁”状态时经纪人可选择修改订单，重新提交审核，无次数限制。</p>
        </section>
        <section class="mui-j_question-view-cell">
            <h2>四、订单收入</h2>
            <p>当别的经纪人解锁你的订单并支付费用后24小时后，解锁费用扣除手续费后将打到你的金账户余额中。</p>
        </section>
        <section class="mui-j_question-view-cell">
            <h2>五、订单完成</h2>
            <p>当您的订单寻找到合适的受让人后，请您尽快点击“完成订单”避免订单被他人误解锁导致被投诉，点击完成订单有助于提升您的经纪人等级。</p>
        </section>
        <section class="mui-j_question-view-cell" style="border:0;">
            <h2>六、甩单禁忌</h2>
            <p>甩单时请您填写正确完善的信息，注意：（1）不要出现借款人的隐私信息，如家庭住址、工作单位、联系电话。（2）不要出现广告内容，包括公司广告及经纪人联系方式。</p>
        </section>
    </section>
    <section class="mui-help-view-cell" id="btn_hk">
        <font>解锁订单常见问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <section class="j_question" id="hk_div">
        <section class="mui-j_question-view-cell">
            <h2>一、如何解锁订单？</h2>
            <p>在甩单首页选择好合适的订单，点击“解锁电话”支付费用后即可获得经济人联系方式。</p>
        </section>
        <section class="mui-j_question-view-cell">
            <h2>二、如何举报订单？</h2>
            <p>当你解锁订单24小时内，联系不上经纪人或者对订单内容真实性存疑的情况下，你可以在“已解锁订单”中对对应订单进行举报，请提供详细的证据或者截图，方便客服处理，客服确认后属实后，将返回您的解锁费用  请不要恶意举报，否则可能导致您的账户被封禁，无法解锁订单或甩单</p>
        </section>         
    </section>    
</section>
</section>
<script type="text/javascript">
$(function(){
    $("#btn_jk").click(function(){
         $("#btn_jk>img").toggle();
         $("#jk_div").toggle();
    })
    $("#btn_hk").click(function(){
         $("#btn_hk>img").toggle();
         $("#hk_div").toggle();
    })    
})
</script>
</html>
{__NOLAYOUT__}