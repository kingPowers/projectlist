<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/borrow/css/add_borrow_success.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script src="_STATIC_/2015/borrow/js/add_borrow_success.js" type="text/javascript"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/index/index/" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz sizeOne">首页</span></a>
<!--            <span class="zxwx sizeOne">提交成功</span>-->
        </div>
        <!--head结束-->
        <!--提交成功部分-->
        <div class="topDiv">
            <div class="succWz onesize">提交成功</div>
            <div class="desinfo twosize">恭喜您已申请成功，我们的工作人员会尽快与您联系</div>
        </div>

        <div class="contentDiv">
            <div class="oneDiv">
                <div class="leftwords threesize">
                    申请人
                </div>
                <div class="rightwords threesize">{$order.names}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                <div class="leftwords threesize">
                    联系电话
                </div>
                 <div class="rightwords threesize">{$order.mobile}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                <div class="leftwords threesize">
                    贷款城市
                </div>
                <div class="rightwords threesize">{$order.city}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                <div class="leftwords threesize">
                    贷款金额
                </div>
                <div class="rightwords threesize">{$order.loanmoney}元</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                <div class="leftwords threesize">
                    借款期限
                </div>
                <div class="rightwords threesize"><span class="colornum foursize">{$order.loanmonth}</span>个月</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                <div class="leftwords threesize">
                    月需还款
                </div>
                <div class="rightwords threesize"><span class="colornum foursize">{$order.month_money}</span>元/月</div>
            </div>
             <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="oneDiv">
                 <div class="desinfos twosize">计算结果仅供参考，实际费用请以还款计划为准！</div>
            </div>
        </div>
        
        <div class="footDiv">
            <span class="wordsWz">如有疑问，请拨打免费客服热线</span>
            <span class="wordsCWz"><a href='tel:400-663-9066'>400-663-9066  </a></span>
        </div>
        
    </body>
</html>
{__NOLAYOUT__}