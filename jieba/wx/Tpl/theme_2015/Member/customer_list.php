<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/cust_list.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/cust_list.js"></script>
    </head>
<body style="background:#efefef;">
    <div class="maxDiv">
        <!--顶部 开始-->
        <div class="headers">
            <div class="rd">
                <a href="<eq name="_SESSION['from_index']" value='1'>/<else/><notempty name='_REQUEST["from_setmobile"]'>/member/setmobile<else/>/member/account</notempty></eq>" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz size2">返回</span>
                </a>
                <span class="orderwx size2">在线客服</span>
            </div>
        </div>
        <!--顶部结束-->
        <div class="d2">
        </div>
        <!--内容层-->
        <div class="center">
            <foreach name="list" item="vo">
                <if condition="$vo['ison'] eq 1">
                    <a href="/member/online?id={$vo['id']}&from_setmobile={$_REQUEST['from_setmobile']}" style="color:white;text-decoration:none;">
                        <div class="d3">
                            <img src="_STATIC_/2015/member/image/account/ke.png" class="ke1Img"/>
                            <span class="font1 size">{$vo['nickname']}</span>
                            <span class="font2 si">您好！</span>
                            <img src="_STATIC_/2015/member/image/account/solid.png" class="custImg"/>
                        </div>
                    </a>
                <else/>
                    <div class="d3">
                        <img src="_STATIC_/2015/member/image/account/black.png" class="ke1Img"/>
                        <span class="font1 size">{$vo['nickname']}</span>
                        <span class="font2 si">您好！</span>
                        <img src="_STATIC_/2015/member/image/account/solid.png" class="custImg"/>
                    </div>
                </if>
            </foreach>
        </div>
    </div>
</body>
</html>   
{__NOLAYOUT__}