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
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
       <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $("#headers").css("display","block");
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
    <title>{$pageseo.title}</title>
    <style type="text/css">
        .getqy{
            width: 55%;         
            position: absolute;
            background: #63abe8;
            border-radius: 5px;
            text-align: center;
            letter-spacing: 1px;
            font-size: 18px;
            display: block;
            height: 40px;
            line-height: 40px;           
            margin: 25% 0 0 3%;
            color: #fff;
            border: 0;
            -webkit-appearance: none;
            font-family: '微软雅黑';
        }
        .give_up{
            width: 35%;         
            position: absolute;
            background: #63abe8;
            border-radius: 5px;
            text-align: center;
            letter-spacing: 1px;
            font-size: 18px;
            display: block;
            height: 40px;
            line-height: 40px;            
            margin: 25% 0 0 63%;
            color: #fff;
            border: 0;
            -webkit-appearance: none;
            font-family: '微软雅黑';
        }
        .pay_jiage{
            color: #5495e6;
            position: absolute;            
        }
        /*iPhone 6 plus及以上*/
        @media (min-width: 414px) {
           .pay_jiage{
                color: #5495e6;
                position: absolute;
                font-size: 38px;            
            }
        }
         
        /* iPhone 6*/
        @media (max-width: 375px) {
             .pay_jiage{
                color: #5495e6;
                position: absolute;
                font-size: 36px;            
            }
        }
         
        /* iPhone 5及以下*/
        @media (max-width: 320px) { 
             .pay_jiage{
                color: #5495e6;
                position: absolute;
                font-size: 30px;            
            }
        }
        .pay_top{
            top:17%;
            left:2%;
        }
        .pay_down{
            top:44%;
            left:65%;
        }
        .apply_info{width: 94%; position: absolute;  height: 20px; left: 50%; margin:16% 0 0 -47%;}
        .apply_info span{color: #000; font-size: 12px; float: left; display: inline-block;}
        .apply_info span:nth-child(2){ float: right; }
        .apply_info span font{color: #5495e6;}
        .pic_tie{position: absolute; width: 28%; right: 5%; top: 8%;}
    </style>
</head>
<body style="background: #efefef;">
<header id="headers" style="display: none;">
	<a href="/index/index" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1>车友贷 </h1>
    <a style="right:8px; position:absolute;" href="/credit/orderlist"><font class="fl">借款记录</font></a>
</header>
<section class="content_div">
    <notempty name="order_info['activityUrl']">
      <img src="{$order_info['activityUrl']}" class="pic_tie">
    </notempty>
	<img src="_STATIC_/2015/image/yydai/index/bg_index.jpg">
    <span class="pay_jiage pay_top">￥{$order_info.minMoney}</span>
    <span class="pay_jiage pay_down">￥{$order_info.maxMoney|intval}</span>	
</section>
<section class="apply_info">
    <span>贷款总额：<font>{$order_info.credit_total_money}元</font></span>
    <span>正在申请：<font>{$order_info.credit_total_num}人</font></span>
</section>
<eq name='btn_status' value="1"><a class="get_money" href="/credit/apply">去借钱</a></eq>
<eq name='btn_status' value="2"><a class="get_sh">审核中请等待</a></eq>
<eq name='btn_status' value="3"><a class="get_money" href="/credit/repayment"><?php if(time()>strtotime($order_info['order_info']['back_time'])) echo "已逾期,";?>去还钱</a></eq>
<eq name='btn_status' value="4"><a class="getqy" href="/credit/creditAgreement3/order_id/{$btn_ids}">去签约</a>
<button class="give_up" myhref="/credit/give_up/order_id/{$btn_ids}">放弃订单</button>
</eq>
<eq name='btn_status' value="5"><a class="get_sh">已签约，等待打款</a></eq>
<section class="bottom_question">
    <a href="/credit/help">常见问题</a>
    <p>400-663-9066</p>
</section>
</body>
<script type="text/javascript">
$(function(){
	$(this).removeAttr("disabled").css({"background":"#63abe8"});
	$('.give_up').click(function(){
		if(!confirm("您确定要放弃订单吗")){
			return false;
		}
		$(this).attr("disabled",true).css({"background":"#ddd"});	
		
		$.post($(this).attr("myhref"),{'data':1},function(F){
	         var F = eval(F);
	          if(F.status==0){
	             alert(F.info);
	             $('.give_up').removeAttr("disabled").css({"background":"#63abe8"});
	          }else{
	              alert(F.info);
	              location.href="/credit/index";
	         }
	      },'json');
	});
});
</script>
</html>
{__NOLAYOUT__}