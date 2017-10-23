<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <title>{$pageseo.title}</title>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
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
</head>
<body bgcolor="#efefef">
    <header class="headers" style="display: none;">
        <a href="javascript:history.back();" class="btn_back">
            <img src="_STATIC_/2015/member/image/register/return.png">
            <font class="fl">返回</font>
        </a>
        <h1>更多</h1>    
    </header>
    <section class="mui-repay-view">
        <section class="mui-help-view-cell" id="btn_yh">
            <font>用户常见问题</font>
            <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
            <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
        </section>
        <section class="j_question" id="yh_div">
            <section class="mui-j_question-view-cell">
                <h2>*验证码无法获取:</h2>
                <p>10分钟内如未收取，可重新获取，如仍未收到，请联系客服（400-663-9066）。</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*修改密码:</h2>
                <p>【账户-账户中心-修改密码】直接修改登录密码</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*忘记密码:</h2>
                <p>【登录-忘记密码】点击“忘记密码”并根据提示找回密码</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*更改手机号码:</h2>
                <p>请直接联系400-663-9066客服。</p>
            </section>      
        </section>
        <section class="mui-help-view-cell" id="btn_czb">
            <font>车租宝常见问题</font>
            <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
            <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
        </section>
        <section class="j_question" id="czb_div">
            <section class="mui-j_question-view-cell">
                <h2>*借款期限：</h2>
                <p>12月、18月、24月;</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*借款利息：</h2>
                <p>0.6%/月;</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*申请人条件：</h2>
                <p>20-60周岁，具有完全民事行为能力的公民、且在当地有正当职业及还款能力的自然人;</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*申请流程：</h2>
                <p>在线申请——填写贷款城市、经销商、报价、车辆信息、贷款期限、姓名、手机号码——等待客服联系确认分配；</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*客服服务时间：</h2>
                <p>工作日时间：9:00——18:00 客服在您申请后10分钟之内与您联系确认分配；</p>
                <p>非工作时间：用户申请后，客服会在工作时间内尽快与您联系。</p>
            </section>           
        </section>
        <section class="mui-help-view-cell" id="btn_cdb">
            <font>车贷宝常见问题</font>
            <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
            <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
        </section>
        <section class="j_question" id="cdb_div">
            <section class="mui-j_question-view-cell">
                <h2>*借款期限：</h2>
                <p>12月;</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*借款利息：</h2>
                <p>0.6%/月;</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*申请人条件：</h2>
                <p>1) 20-60周岁，具有完全民事行为能力的公民；</p>
                <p>2) 申请人名下拥有在当地车管所登记的机动车，即申请人须拥有该车辆的所有权。</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*申请流程：</h2>
                <p>在线申请——填写贷款城市、经销商、报价、车辆信息、贷款期限、姓名、手机号码——等待客服联系确认分配；</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>*客服服务时间：</h2>
                <p>工作日时间：9:00——18:00 客服在您申请后10分钟之内与您联系确认分配；</p>
                <P>非工作时间：用户申请后，客服会在工作时间内尽快与您联系。</p>
            </section>  
            <section class="mui-j_question-view-cell">
                <h2>*申请所需材料（个人车）</h2>
                <p>1) 申请人有效身份证（非本地户籍需同时提供居住证或暂住证）〈必附〉；</p>
                <p>2) 机动车登记证（当地车管所核发的正式机动车登记证）〈必附〉；</p>
                <p>3) 机动车行驶证（当地车管所核发的正式机动车行驶证）〈必附〉；</p>
                <p>4) 申请人机动车驾驶证；〈必附〉；</p>
                <p>5) 保险单；</p>
                <p>6) 申请人须提供购车原始发票或二手车辆发票；</p>
                <p>7) 进口车辆还需提供《海关关税专用缴款书》、《海关代征消费税专用缴款书》或海关《征免税证明》；</p>
                <p>8) 车钥匙一把，原配钥匙由我司保留至合同期满〈必附〉；</p>
                <p>9) 企业法定代表人及个体工商户需提供营业执照，企业或商铺现场照片、企业或商铺水电煤账单二选一，（申请人20万以上）〈必附〉；</p>
                <p>10) 申请人本人近6个月的银行流水（信用卡对账单、支付宝及微信等第三方支付流水除外）；本人一周内人民银行出具的个人征信报告〈必附〉；</p>
                <p>11) 如提供房产证，必须提供房产证原件(1、2、3页拍摄或扫描件)。</p>
                <p>注: 通过对资料的补充或提供的完整性的考量，对额度进行适当调整；车辆登记证、车辆保险单必须由我司保存至合同期满。</p>
            </section>         
        </section>
        <section class="mui-help-view-cell" id="btn_jb">
            <font>我与借吧常见问题</font>
            <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
            <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
        </section>
        <section class="j_question" id="jb_div">
            <section class="mui-j_question-view-cell">
                <h2>如何发布 “我与借吧”</h2>
                <p>【账户-待评价】成功借款用户，才可发布“我与借吧”</p>
            </section>
            <section class="mui-j_question-view-cell">
                <h2>如何评论或点赞</h2>
                <p>【登录“借吧”——查看“我与借吧”】注册用户可随时为发布用户评论或点赞</p>
            </section>           
        </section>
    </section>
</body>
<script type="text/javascript">
$(function(){
    $("#btn_yh").click(function(){
         $("#btn_jk>img").toggle();
         $("#yh_div").toggle();
         $(".j_question").css("background","#efefef");
    })
    $("#btn_czb").click(function(){
         $("#btn_czb>img").toggle();
         $("#czb_div").toggle();
         $(".j_question").css("background","#efefef");
    })
    $("#btn_cdb").click(function(){
         $("#btn_cdb>img").toggle();
         $("#cdb_div").toggle();
         $(".j_question").css("background","#efefef");
    })
    $("#btn_jb").click(function(){
         $("#btn_jb>img").toggle();
         $("#jb_div").toggle();
         $(".j_question").css("background","#efefef");
    })
})
</script>
{__NOLAYOUT__}