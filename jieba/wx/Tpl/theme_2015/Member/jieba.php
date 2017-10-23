<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/index.css">
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">  
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"> 
            $(function(){            
                if(isWeixin()) {
                    $(".headers").css("display","block");                
                }
                else{
                    $(".content_div").css("top","0px");
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
        <style type="text/css">
            .graydiv{width: 100%; height: 36px; background: #efefef; font-family: "微软雅黑";}
            .graydiv i{width: 4px; height: 24px; background: #46abef; display: inline-block; margin: 6px 12px 0 20px; float: left;}
            .graydiv font{float: left; display: inline-block; height: 36px; line-height: 36px; font-size: 18px;}
            #contxt{font-size: 14px; width: 96%; margin: 0 auto; padding:5px 0 20px; text-indent: 2em;}
            #contxt p{line-height: 20px;text-indent: 2em;}
        </style>
    </head>
    <body style="background: #fff;">
<header class="headers" style="display: none;">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>公司简介</h1>    
</header>
<section class="content_div">
    <section class="graydiv">
       <i></i><font>借吧简介</font>
    </section>
    <section id="contxt">
        <p style="padding-bottom: 5px;">“借吧”是智信创富推出的新互联网贷款工具，以“全透明、好掌握、易操作“作为工具的全部；客户只需在线注册及申请，客服就会在3分钟之内联系到用户并安排最近门店进行对接，为确保进度万无一失及门店服务质量，客服会根据后台数据实时监控及时向用户反馈借款结果，用户也可直接登录此工具直接查询订单状态</p>
        <img src="_STATIC_/2015/image/banner.jpg">
    </section>
    <section class="graydiv">
       <i></i><font>智信创富简介</font>
    </section>
    <section id="contxt">
        <p>智信创富金融信息服务（上海）有限公司是一家专业从事汽车金融的新型综合性机构，智信创富专注于车贷领域，选择车辆作为媒介源于车辆抵（质）押具有“变现能力快，便于实施抵（质）押物监管监控”的优势与特性，资金出借给有抵押保障的，有还款来源的，信誉良好的借款人，并获得利息回报。同时，继续秉承普惠金融的理念，用先进的金融服务理念和创新的互联网技术并以抵押产品为核心竞争力，打造中国汽车金融第一站。</p>
        <p>智信创富金融信息服务（上海）有限公司拥有自主运营的互联网平台，公司正在全力打造汽车抵押金融，汽车理财金融，汽车消费金融，
          汽车基金，二手汽车交易，全新汽车交易六大汽车产品生态圈，未来，将利用自身背景和资源优势并以汽车金融为核心布局汽车金融，汽车交易，
          汽车制造三架马车同步发展，深度挖掘每一环节，为中国汽车产业链发展贡献一份力量。</p>
    </section>
</section>
</body>
</html>
{__NOLAYOUT__}