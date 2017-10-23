<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>   
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <title>{$pageseo.title}</title>  
    <script type="text/javascript"> 
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }else{
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
        ul, ol, li, dl, dt, dd, h1, h2, h3, h4, h5, p{margin: 0;padding: 0; list-style: none;}
        .mui-content{padding-top:28%; overflow: hidden;}
        .page_con{ width: 96%; margin:0 auto; background: #fff; border-radius: 10px; padding: 20% 0 10%;}
        .page_con img{width: 28%; position: absolute; left: 50%; margin: -32% 0 0 -14%;}
        .page_con p{color: #3a3939; text-align: center; width: 100%; letter-spacing: 2px; font-size: 18px; }
        .page_con h1{font-size: 26px; color: #3b97e6; text-align: center; width: 100%; font-weight: normal; padding: 2% 0; letter-spacing: 4px;}
        .page_con span{font-size: 14px; color: #b4b4b4; text-align: center; width: 68%; height: 30px; line-height: 30px; background: #efefef; margin: 0 auto;display: block;border-radius: 15px; letter-spacing: 2px;}
        .page_con font{font-size: 12px; color: #a1a1a1; text-align: center; width: 68%; margin: 0 auto;display: block; padding: 3% 0;}
        .page_con h2{font-size:18px; color: #3a3939; text-align: center; width: 100%; border-top: 1px #efefef solid; border-bottom: 1px #efefef solid; font-weight: normal; padding: 3% 0;}
    </style>
</head>

<body style="background: #efefef;">
<header class="headers">
    <a href="/member/activity" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">活动专区</h1>      
</header>
<section class="mui-content">
    <section class="page_con">
        <img src="_STATIC_/2015/member/image/ico_jb.png">
        <p>车友贷提额券</p>
        <h1>提额{$ticket_info['up_percent']}%</h1>
        <span>成功邀请好友将自动提升额度</span>
        <font>有效期至<br/><?php echo date("Y年m月d日",strtotime($ticket_info['over_time']))?></font>
        <h2>指定产品：车友贷</h2>
    </section>
</section>
</body>
</html>
{__NOLAYOUT__}