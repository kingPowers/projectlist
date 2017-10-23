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
	<a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>常见问题</h1>    
</header>
<section class="mui-repay-view">
<section class="ask">
    <section class="mui-help-view-cell">
        <font>车友贷借款问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <ul class="j_question">
        <li>
            <h2>一、车友贷申请需要什么条件？</h2>
            <p>已在智信创富借款，正常还款三个月以上并处于正在还款的状态。</p>
        </li>
        <li>
            <h2>二、车友贷的借款额度及期限？</h2>
            <p>额度为1000-30000元；期限为7天。</p>
        </li>
        <li>
            <h2>三、车友贷的费率？</h2>
            <p>周利率{$setting.week_percent}%，单笔服务费{$setting.fee}/笔，平台管理费{$setting.plat_fee}%。</p>
        </li>
        <li>
            <h2>四、车友贷可以借几次？</h2>
            <p>在结清上次车友贷借款后，用户可以继续申请车友贷。</p>
        </li>
        <li>
            <h2>五、车友贷受理时间</h2>
            <p>用户借款申请受理时间一般为每周一至周五9:00——17:30，节假日将延后处理。</p>
        </li>
        <li style="border:0;">
            <h2>六、车友贷放款账户及放款时间？</h2>
            <p>当用户申请通过后，款项将放款到用户富友金账户，届时将有短信通知，用户可在【个人中心】申请提现，提现可当天到帐。</p>
        </li>
    </ul>
</section> 
<section class="ask">   
    <section class="mui-help-view-cell">
        <font>车友贷还款问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <ul class="j_question">
        <li>
            <h2>一、车友贷多久还款？</h2>
            <p>用户需在借款发放七天后的还款日24时前归还借款，用户也可根据实际情况选择提前还款。</p>
        </li>
        <li>
            <h2>二、车友贷如何还款？</h2>
            <p>“借吧”首页点击【车友贷】-【去还钱】，点击确定即可还款。如用户金账户余额不足，将跳转到充值界面，充值完成后即可还款。</p>
        </li>
        <li style="border:0;">
            <h2>三、车友贷逾期会有什么影响？</h2>
            <p>逾期影响如下：
            1.逾期记录会记录到公司系统中，影响到用户的下次借款申请。
            2.逾期1-5天，滞纳金1%/天，逾期6天（含）以上，滞纳金2%/天。
            </p>
        </li>        
    </ul>
</section>
<section class="ask">    
    <section class="mui-help-view-cell">
        <font>车险分期常见问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <ul class="j_question">
        <li>
            <h2>一 、如何办理车险分期？</h2>
            <p>首页——个人账户——我的车险——订单详情——报价付款，当购买车险选择付款方式的时候，选择分期付款，该笔订单自动转入保险分期。</p>
        </li>
        <li>
            <h2>二、车险分期享受的权益和全款车险是一样的吗？</h2>
            <p>一样的。从车险首付那一天算起，即可生成保单，正常享受车险权益。</p>
        </li>
        <li>
            <h2>三、办理分期后每月如何还款？</h2>
            <p>借吧首页——点击“分呗”，可以看到每月应还账目、总账目。</p>
        </li>        
        <li>
            <h2>四、已经分期一笔，还可以再办理车险业务吗？</h2>
            <p>可以。只要正常还款，一个账号可以多次购买车险，也可办理多笔车险分期业务。还款时可以一次性还款多笔，也可分笔还清。</p>
        </li>
        <li>
            <h2>五、车险分期要收手续费吗？</h2>
            <p>0手续费，无任何附加费用。</p>
        </li>
        <li>
            <h2>六、车险分期的付费方式是怎样的？</h2>
            <p>车险分期只可分期商业险，交强险必须全款，首付计算方式：交强险全款+ 20%商业险，尾款分10期还清，还款周期为一个自然月。(例如，客户在1月31日办理了车险分期，那么，第一期的还款日为2月28日。)</p>
        </li> 
        <li>
            <h2>七、车险分期可以提前还款吗？</h2>
            <p>可以提前还一个月。如需全部提前结清，可致电400-663-9066转1号分机，联系客服办理。</p>
        </li>        
        <li>
            <h2>八、车贷想提前结清，车险分期怎么办？</h2>
            <p>客户可以选择将车险保费提前结清，致电400-663-9066转1号分机联系客服办理。或者保险继续分期，只要按时还款，一切保险服务都可以正常享受的。</p>
        </li>
        <li>
            <h2>九、可以逾期吗？逾期有什么手续费？</h2>
            <p>请留意最后还款日，避免逾期，逾期将加收1%逾期费。逾期超过5天，系统将自动退保，车险权益将不能再享受。
            </p>
        </li>
        <li style="border:0;">
            <h2>十、车贷已经结清了还可以买车险吗？可以办理分期吗？</h2>
            <p>车贷结清，可以买车险，也可以办理分期。 </p>
        </li>       
    </ul>
</section>
<section class="ask">
    <section class="mui-help-view-cell">
        <font>车险常见问题</font>
        <img src="_STATIC_/2015/image/yydai/index/ico_down.png">
        <img src="_STATIC_/2015/image/yydai/index/ico_up.png" style="display:none;">
    </section>
    <ul class="j_question">
        <li>
            <h2>一、 借吧平台车险种类</h2>
            <p>借吧与平安车险合作，可在车险申请页面选择交强险、三责险、车损险、盗抢险和不计免赔，如有更多需求，可拨打客服热线400-663-9066转1号分机加购。</p>
        </li>
        <li>
            <h2>二、已经购买过其他车险了，还可以再在借吧买吗？</h2>
            <p>交强险一年之内只能购买一次，商业险可以叠加购买，目前平台商业险有四种：第三者责任险、车损险、盗抢险和不计免赔，如有更多需求，可拨打客服热线400-663-9066转1号分机加购。</p>
        </li>
        <li>
            <h2>三、如何购买车险？</h2>
            <p>借吧首页——点击买车险，直接跳出“申请页面”，如实填写后提交，即进入购买流程。</p>
        </li>        
        <li>
            <h2>四、如何付款？付款方式有什么？</h2>
            <p>首页——账户——我的车险——订单详情——报价付款。
                付款方式有全款付清和分期付款，权益保障相同。
                提示：当保险公司报价后，尽快完成付款，24小时内如不付款，交易关闭。
            </p>
        </li>
        <li>
            <h2>五、购买申请提交后如何查看购买进度？</h2>
            <p>首页——账户——我的车险——订单详情，可查看自己车险购买进度。</p>
        </li>
        <li>
            <h2>六、已经完成付款，为什么看不到保单信息？</h2>
            <p>线上交易，有一定延时性，完成付款后，请耐心等待，保单将在24小时内上传</p>
        </li> 
        <li>
            <h2>七、购买车险，受益人是谁？</h2>
            <p>在车贷期间，受益人为公司；车贷结清后，受益人更改为客户。</p>
        </li>        
        <li>
            <h2>八、一个人可以买几次车险？</h2>
            <p>同一个客户，可分车、分批多次购买不同种类的车险项目，不做限制要求。（不计免赔不能单独购买）。</p>
        </li>
        <li>
            <h2>九、保单如何获取？</h2>
            <p>保险公司定期将保单快递到门店，由门店人员负责发放到客户手中。
            或者在账户——我的车险——我的订单——查看保单，查看自己的电子保单，同样有效。   
            </p>
        </li>
        <li class="mui-j_question-view-cell" style="border:0;">
            <h2>十、线上购买保险有法律保障吗？</h2>
            <p>电子签约，具有同等法律效应。进入“报价付款”页面后，勾选保险协议，输入手机验证码，协议签订成功。 </p>
        </li>       
    </ul>
</section>
</section>
<script type="text/javascript">
$(function(){   
   $(".ask").click(function(){
        $(this).find("img").toggle();
        $(this).find("ul").toggle();
   })
})
</script>
</html>
{__NOLAYOUT__}


