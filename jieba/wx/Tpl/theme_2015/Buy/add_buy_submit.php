<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/borrow/css/add_borrow_submit.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/buy/index" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/></a>
            <span class="fhwz sizeOne"><a href="/buy/index" style="color:white;text-decoration:none;">返回</a></span>
            <span class="zxwx sizeOne">我要申请</span>
        </div>
        <!--head结束-->
        <div class="noneDiv"></div>
        <!--申请信息输入部分-->
        <div class="infosetDiv">
            <div class="namecls tDiv">
                <div class="bt sizeFour">姓名</div>
                <input type="text" name='names' class="ipt sizeTwo"  placeholder="请输入姓名"/>
            </div> 
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="phonecls tDiv">
                <div class="bt sizeFour">手机号码</div>
                <input type="text" class="ipt sizeTwo" name='mobile'  value="{$member_info.mobile}" readonly="readonly"/>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="phonenumcls tDiv">
                <div class="bt sizeFour">手机验证码</div>
                <input type="text" name='sms_code' class="ipt sizeTwo" placeholder="请输入验证码" style="width:39%;left:32%;" style="left:31%;width:35%;"/>
<!--                <span class="yf ipts sizeTwo getpassImg" style="border-left:2px solid #f7f7f7;">获取验证码</span>-->
                <input type="button" class="getpassImg sizeTwo"  value="获取验证码" onclick="sendCode(this)" />
                <script type="text/javascript">
                    var clock = '';
                    var nums = 60;
                    var btn;
                    function sendCode(thisBtn)
                    {
                        btn = thisBtn;
                        btn.disabled = true;
                        btn.style.color = "#cccccc";
                        btn.value = "重新发送(" + nums + ")";
                        clock = setInterval(doLoop, 1000);
                    }
                    function doLoop()
                    {
                        nums--;
                        if (nums > 0) {
                            btn.value = "重新发送(" + nums + ")";
                        } else {
                            clearInterval(clock);
                            btn.disabled = false;
                            btn.value = '获取验证码';
                            nums = 60;
                            btn.style.color = "#3880e7";
                        }
                    }
                </script>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="applybtn">
                <img src="_STATIC_/2015/borrow/image/add_borrow_submit/surebtn.png" alt="" class="surebtnImg"/>
            </div>
            <div class="applyconditionDiv">
                <img src="_STATIC_/2015/borrow/image/add_borrow_submit/sqtj.jpg" alt="" class="sqtjImg"/>
            </div>
            <div class="needconditionDiv">
                <img src="_STATIC_/2015/borrow/image/add_borrow_submit/need.jpg" alt="" class="needImg"/>
            </div>
            <div class="dyexperDiv">
                <img src="_STATIC_/2015/borrow/image/add_borrow_submit/dyjy.jpg" alt="" class="needImg"/>
            </div>
            <input name="_buy_mobile_" type="hidden" value="{$_buy_mobile_}"/>
        </div>
     <script type = "text/javascript">
            $(function () {
                var ua = navigator.userAgent.toLowerCase();
                if (/iphone|ipad|ipod/.test(ua)) {
                } else {
                    $("input").focus(function () {
                        $("body").css("position", "absolute");
                        $(".headers").css("position", "absolute");
                        $(".headers").css("height", "15%");
                        $(".retrunImg").css("margin-top", "1%");
                        $(".fhwz").css("margin-top", "1.2%");
                        $(".zxwx").css("margin-top", "1.2%");
                    });
                }
            })
        </script>
        <script type="text/javascript">
            var d = document;
            var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
            var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
            var headers, retrunImg, fhwz, zxwx, noneDiv, infosetDiv, applybtn, applyconditionDiv, needconditionDiv, dyexperDiv;

            var OriginalWidth = 750;//原始图纸的宽度

            jsCSS = function () {
                this.fontSize();
                headers = d.getElementsByClassName("headers")[0];
                retrunImg = d.getElementsByClassName("retrunImg")[0];
                fhwz = d.getElementsByClassName("fhwz")[0];
                zxwx = d.getElementsByClassName("zxwx")[0];
                this.heiads(headers, retrunImg, fhwz, zxwx);
                noneDiv = d.getElementsByClassName("noneDiv")[0];
                this.noneDiv(noneDiv, headers);
                infosetDiv = d.getElementsByClassName("infosetDiv")[0];
                this.infosetDiv(infosetDiv);
                applybtn = d.getElementsByClassName("applybtn")[0];
                this.applybtn(applybtn);
                applyconditionDiv = d.getElementsByClassName("applyconditionDiv")[0];
                this.applyconditionDiv(applyconditionDiv);
                needconditionDiv = d.getElementsByClassName("needconditionDiv")[0];
                this.needconditionDiv(needconditionDiv);
                dyexperDiv = d.getElementsByClassName("dyexperDiv")[0];
                this.dyexperDiv(dyexperDiv);

            }

            jsCSS.prototype = {
                fontSize: function () {
                    for (var i = 0; i < d.getElementsByClassName("sizeOne").length; i++) {
                        d.getElementsByClassName("sizeOne")[i].style.fontSize = parseInt(w) * 0.040 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("sizeTwo").length; i++) {
                        d.getElementsByClassName("sizeTwo")[i].style.fontSize = parseInt(w) * 0.038 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("sizeFour").length; i++) {
                        d.getElementsByClassName("sizeFour")[i].style.fontSize = parseInt(w) * 0.048 + "px";
                    }
                },
                heiads: function (headers, retrunImg, fhwz, zxwx) {
                    retrunImg.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                    fhwz.style.lineHeight = retrunImg.offsetHeight + "px";
                    zxwx.style.lineHeight = retrunImg.offsetHeight + "px";
                    fhwz.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                    zxwx.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                },
                noneDiv: function (noneDiv, headers) {
                    noneDiv.style.height = headers.offsetHeight + "px";
                },
                infosetDiv: function (infosetDiv) {
                    infosetDiv.style.height = getSize(429) + "px";
                    for (var i = 0; i < d.getElementsByClassName("tDiv").length; i++) {
                        d.getElementsByClassName("tDiv")[i].style.height = getSize(100) + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("bt").length; i++) {
                        var bt = d.getElementsByClassName("bt")[i];
                        bt.style.height = getSize(100) + "px";
                        bt.style.lineHeight = bt.offsetHeight + "px";

                    }
                    for (var i = 0; i < d.getElementsByClassName("ipt").length; i++) {
                        var ipt = d.getElementsByClassName("ipt")[i];
                        ipt.style.height = getSize(60) + "px";
                        ipt.style.lineHeight = getSize(60) + "px";
                        ipt.style.margin = getSize(20) + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("ipts").length; i++) {
                        var ipts = d.getElementsByClassName("ipts")[i];
                        ipts.style.lineHeight = bt.offsetHeight + "px";
                    }
                },
                applybtn: function (applybtn) {
                    applybtn.style.height = getSize(125) + "px";
                },
                applyconditionDiv: function (applyconditionDiv) {
                    applyconditionDiv.style.height = getSize(295) + "px";
                },
                needconditionDiv: function (needconditionDiv) {
                    needconditionDiv.style.height = getSize(244) + "px";
                },
                dyexperDiv: function (dyexperDiv) {
                    dyexperDiv.style.height = getSize(258) + "px";
                }

            }

            new jsCSS();

            //alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
            function getSize(value) {
                var v = parseInt(w) * (value / OriginalWidth);
                return v;
            }

            $('.surebtnImg').click(function () {
                var data = {};
                var img_dir = "_STATIC_/2015/borrow/image/add_borrow_submit/";
                var img_src = $(this).attr('src');
                var img_name = img_src.substr(img_src.lastIndexOf("/") + 1);
                if (img_name != undefined && img_name == 'surebtnicon-up.png')
                    return false;

                var input_name = {
                    'names': '姓名',
                    'mobile': '手机号码',
                    'sms_code': '验证码'
                };
                var input_attr = new Array();
                input_attr['names'] = '请输入姓名';
                input_attr['mobile'] = '手机号码';
                input_attr['sms_code'] = '请输入验证码';
                var is_checked = 1;
                $.each(input_name, function (i, val) {
                    $(data).attr(i, $('input[name="' + i + '"]').val());
                    if ($(data).attr(i) == '' || undefined == $(data).attr(i) || $(data).attr(i) == input_attr[i]) {
                        alert(val + "不能为空");
                        is_checked = 0;
                        return false;
                    }
                });
                if (is_checked == 0)
                    return false;
                $(this).attr('src', img_dir + 'surebtnicon-up.png');
                $.ajax({
                    'type': 'post',
                    'dataType': 'json',
                    'url': "/buy/add_buy_submit",
                    "data": {'data': data},
                    success: function (json) {
                        if (json.status == 1) {
                            location.href = '/buy/add_buy_success/id/' + json.data.id;
                        } else {
                            alert(json.info);
                            $('.surebtnImg').attr('src', img_dir + 'surebtn.png');
                            if (json.info == '对不起，请您重新提交')
                                location.href = '/buy/index/';
                        }

                    },
                });

            });

            is_send = 1;
            $('.getpassImg').click(function () {
                if (is_send == 0)
                    return false;
                var mobile = $("input[name='mobile']").val();
                var token = $("input[name='_buy_mobile_']").val();
                if (mobile == '' || mobile == '请输入手机号码') {
                    alert("请输入手机号码");
                    return false;
                }
                if (!/^1\d{10}$/.test(mobile)) {
                    alert("请正确输入手机号");
                    return false;
                }
                is_send = 0;
                $.ajax({
                    'type': 'post',
                    'dataType': 'json',
                    'url': "/buy/buySendSms",
                    "data": {'mobile': mobile, '_buy_mobile_': token},
                    success: function (json) {
                        is_send = 1;
                        alert(json.info);
                        if (json.status == 1) {
                        } else {
                            location.reload();
                        }

                    },
                });

            });

        </script>
    </body>
</html>
{__NOLAYOUT__}