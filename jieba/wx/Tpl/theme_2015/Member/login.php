<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/login.css" />
        <link rel="stylesheet" href="_STATIC_/2015/index/css/reset.css" />
        <style type="text/css">
               /* WebKit browsers */
                ::-webkit-input-placeholder {
                    color: #fff;
        </style>
    </head>
    <body>
        <div class="maxDiv">
            <img src="_STATIC_/2015/member/image/login/loginbg.png" class="bgImg">
            <div class="headers">
                <!--                    <div class="rd">-->
                <a href="/index/index"><img src="_STATIC_/2015/member/image/login/jt.png" class="retrunImg"/>
                    <span class="fhwz">返回</span></a>
                <!--                    </div>-->
            </div>
            <div class="centerDiv">
                <form action="login" method="post" name="weixin_login">
                    <img src="_STATIC_/2015/member/image/login/jiebalogo.png" class="chelogoImg"/>
                    <img src="_STATIC_/2015/member/image/login/phoneicon.png" class="phoneiconImg"/>
                    <input value="<notempty name='Think.post.mobile'>{$Think.post.mobile}<else/>请输入手机号码</notempty>" placeholder="请输入手机号码" onfocus="if (value == '请输入手机号码') {
                                value = ''
                            }" onblur="if (value == '') {
                                        value = '请输入手机号码'
                                    }" name='mobile' class="srsjnum srsize" />
<!--                    <img src="_STATIC_/2015/member/image/login/line1.png" class="lineImg"/>-->
                    <div class="lineImg"></div>
                    <img src="_STATIC_/2015/member/image/login/passicon.png" class="passiconImg"/>
                    <input type="password" placeholder="请输入密码" name='password' class="srmmnum srsize" />
                    <div class="linexImg" style='color:red;padding-top:15px;'>{$error}</div>
                    <input type="hidden"  name="redirecturl" value="{$Think.request.redirecturl}"/>
<!--                    <img src="_STATIC_/2015/member/image/login/line.png" class="linexImg"/>-->
                    <img src="_STATIC_/2015/member/image/login/loginsbut.png" class="loginsbutImg"/>
                    <img src="_STATIC_/2015/member/image/login/registerbtn.png" class="registerbtnImg" onclick="location.href = '/member/register'"/>
                    <img src="_STATIC_/2015/member/image/login/wjmm.png" class="wjmmImg" onclick="location.href = '/member/recoverpwd/'"/>
                </form>
            </div>
        </div>
    </body>



    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
                        $(function () {
                            changeNotice();
                            function changeNotice() {
                                setTimeout(function () {
                                    $(".linexImg").html("")
                                }, 2000);
                            }
                            $('.loginsbutImg').click(function () {
                                if ($('input[name="mobile"]').val() == '' || $('input[name="mobile"]').val() == '请输入手机号码') {
                                    $('input[name="mobile"]').focus();
                                    $('.linexImg').html("请输入手机号");
                                    changeNotice();
                                    return false;
                                }
                                if (!/^1\d{10}$/.test($('input[name="mobile"]').val())) {
                                    $('input[name="mobile"]').focus();
                                    $('.linexImg').html("请输入正确的手机号");
                                    changeNotice();
                                    return false;
                                }
                                if ($('input[name="password"]').val() == '' || $('input[name="password"]').val() == '请输入密码') {
                                    $('.linexImg').html("请输入密码");
                                    $('input[name="password"]').focus();
                                    changeNotice();
                                    return false;
                                }
                                $('.linexImg').css("color", 'orange').html("请稍后……");
                                $('form[name="weixin_login"]').submit();

                            });
                        });
    </script>
<!--    <script type="text/javascript" src="_STATIC_/2015/member/js/login.js"></script>-->
    <script type="text/javascript">
        $(function () {

            var yw = 750; //原始图片宽度
            var ys = 1290; //原始图片高度

            w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            h = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

            $(document).ready(function () {
                ft();
            });

            $(window).resize(function () {
                ft();
            });

            function ft() {
                $(".maxDiv").css("height", jsh(1294) + "px");
                $(".bgDiv").css("height", jsh(1294) + "px");
                $(".fhwz").css("font-size", parseInt(h * 0.032) + "px");
                $(".srsize").css("font-size", parseInt(w * 0.060) + "px");

            }

            function jsh(a) {
                var j = (parseInt(w) * (parseInt(a) / yw));
                return j;
            }

        });
    </script>
</html>  
{__NOLAYOUT__}