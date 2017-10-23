<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/buy/css/index.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/Index/index" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne">返回</span></a>
            <span class="zxwx sizeOne">车租宝</span>
        </div>
        <!--头结束-->
        <div class="noneDiv"></div>
        <div class="banner">
            <img src="_STATIC_/2015/buy/image/banner.jpg" class="bannerImg"/>
        </div>
        <div class="threeDiv">
            <div class="citycls tDiv">
                <div class="bt sizeFour">贷款城市</div>
                <input type="text" name='city' class="ipt sizeTwo"  placeholder="请输入贷款城市" />
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="moneycls tDiv">
                <div class="bt sizeFour">经销商</div>
                <input type="text" name='dealer' autocomplete="off"  readonly="true" class="ipt sizeTwo jxsSapn"  placeholder="请选择经销商" style="width:45%;left:32%;" />
                <span class="yf ipts sizeTwo"><img src="_STATIC_/2015/buy/image/down.png" alt="" id="down1Img" class="downsImg"/></span></span>
            </div>
            <div class="selectsDiv" id="jxsSelect">
                <foreach name='dealer' item='vo'>
                    <div class="jxsdiv sizeTwo dealerSelect">{$vo.name}</div>
                </foreach>

            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="timecls tDiv">
                <div class="bt sizeFour">车商报价</div>
                <input type="text"  name='loanmoney' maxlength=8 autocomplete="off"  class="ipt sizeTwo"  placeholder="请输入车商报价"/><span class="yf ipts sizeTwo">元</span>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="citycls tDiv">
                <div class="bt sizeFour">车辆品牌</div>
                <input type="text"  name='car_brand' class="ipt sizeTwo"  placeholder="请输入车辆品牌"/>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="citycls tDiv">
                <div class="bt sizeFour">车辆型号</div>
                <input type="text" name='car_class' class="ipt sizeTwo"  placeholder="请输入车辆型号"/>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="timecls tDiv">
                <div class="bt sizeFour">借款期限</div>
                <input type="text" name='loanmonth_input' autocomplete="off" readonly="true" class="ipt sizeTwo jxqxSapn" style="width:45%;left:32%;color:#a9a9a9;"  placeholder="请选择借款期限"/>
                <span class="yf ipts sizeTwo" >
                    <img src="_STATIC_/2015/buy/image/down.png" alt="" id="down2Img" class="downsImg"/></span>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="selectsDiv" id="blSelect">
                <foreach name='setting.buy_setting.first_percent' item='vo' key='k'>
                    <div class="jxsdiv jxsdiv_loanmonth"> 
                        <input type="radio" name="loanmonth"  value='{$k}'  percent='{$vo}' class="regular-checkbox"   autocomplete="off"/>
                        <span class="qxwz sizeFour qi">{$k}期</span>
                        <span class="sizeTwo percent" style="margin-left:30%;">首付百分比{$vo}%</span>
                    </div>
                </foreach>
            </div>
            <div class="timecls tDiv">
                <div class="bt sizeFour">月需还款</div>
                <span  class="ipt sizeTwo diffwzsize month_money" placeholder="" style="left:31%;width:35%;color:#5495e6;">0</span><span class="yf ipts sizeTwo">元/月</span>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="timecls tDiv">
                <div class="bt sizeFour">首付金额</div>
                <span  class="ipt sizeTwo diffwzsize first_money" placeholder="" style="left:31%;width:35%;color:#5495e6;">0</span><span class="yf ipts sizeTwo">元</span>
            </div>
        </div>
        <div class="fourDiv">
            <div class="detalecls detaleclssize">计算结果仅供参考，实际费用请以还款计划为准！</div>
            <div class="applyBtnDiv">
                <img src="_STATIC_/2015/borrow/image/index/apply.jpg" alt="" class="applyImg applyfirst"/>
            </div>
            <div class="showinfoDiv">
                <p class="moneyinfo detaleclssize">贷款总额 <span class="moneyinfovcolor">{$setting.buy_money|default='0.00'}元</span></p>
                <p class="nowapply detaleclssize">正在申请 <span class="moneyinfovcolor">{$setting.buy_count|default='0'}人</span></p>
            </div>
        </div>
        <div class="fiveDiv">
            <img src="_STATIC_/2015/buy/image/mcdlc.jpg" alt="" class="fiveImg"/>
        </div>
        <div class="sixDiv">
            <img src="_STATIC_/2015/buy/image/fwcs.jpg" alt="" class="fiveImg"/>
        </div>

        <script type = "text/javascript">
            $(function () {
                 $(".headers").css("display", "block");
                var ua = navigator.userAgent.toLowerCase();
                if (/iphone|ipad|ipod/.test(ua)) {
                    $(".headers").css("width", "100%");
                    $(".headers").css("height", "6.8%");
//                    $(".headers").css("position", "fixed");
                    $(".headers").css("left", "0");
                    $(".headers").css("top", "0");
                    $(".headers").css("background-color", "#5495e6");
                    $(".headers").css("z-index", "10");
                } else {
                    $("input").focus(function () {
                        $("body").css("position", "absolute");
                        $(".headers").css("position", "absolute");
                        $(".headers").css("height", "4%");
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
            var headers, retrunImg, fhwz, zxwx, noneDiv, threeDiv, fourDiv, fiveDiv, selectsDiv, jxsdiv;

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
                threeDiv = d.getElementsByClassName("threeDiv")[0];
                this.threeDiv(threeDiv);
                fourDiv = d.getElementsByClassName("fourDiv")[0];
                this.fourDiv(fourDiv);
                fiveDiv = d.getElementsByClassName("fiveDiv")[0];
                this.fiveDiv(fiveDiv);
                sixDiv = d.getElementsByClassName("sixDiv")[0];
                this.sixDiv(sixDiv);
                selectsDiv = d.getElementsByClassName("selectsDiv")[0];
                this.selectsDiv(selectsDiv);
                jxsdiv = d.getElementsByClassName("jxsdiv")[0];
                this.jxsdiv(jxsdiv);


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
                    for (var i = 0; i < d.getElementsByClassName("sizeBig").length; i++) {
                        d.getElementsByClassName("sizeBig")[i].style.fontSize = parseInt(w) * 0.045 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("detaleclssize").length; i++) {
                        d.getElementsByClassName("detaleclssize")[i].style.fontSize = parseInt(w) * 0.035 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("diffwzsize").length; i++) {
                        d.getElementsByClassName("diffwzsize")[i].style.fontSize = parseInt(w) * 0.058 + "px";
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
                threeDiv: function (threeDiv) {
//                    threeDiv.style.height = getSize(809) + "px";
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
                fourDiv: function (fourDiv) {
                    fourDiv.style.height = getSize(300) + "px";
                    var detalecls = d.getElementsByClassName("detalecls")[0];
                    detalecls.style.height = getSize(100) + "px";
                    detalecls.style.lineHeight = getSize(100) + "px";
                    var applyBtnDiv = d.getElementsByClassName("applyBtnDiv")[0];
                    applyBtnDiv.style.height = getSize(94) + "px";
                    applyBtnDiv.style.lineHeight = getSize(94) + "px";
                    //applyBtnDiv.style.top = getSize(89) + "px";
                    var showinfoDiv = d.getElementsByClassName("showinfoDiv")[0];
                    showinfoDiv.style.height = getSize(155) + "px";
                    // applyBtnDiv.style.top =getSize(199)+ "px";
                },
                fiveDiv: function (fiveDiv) {
                    fiveDiv.style.height = getSize(295) + "px";
                },
                selectsDiv: function (selectsDiv) {
                    selectsDiv.style.height = getSize(304) + "px";
                },
                jxsdiv: function (jxsdiv) {
                    jxsdiv.style.height = getSize(100) + "px";
                },
                sixDiv: function (sixDiv) {
                    sixDiv.style.height = getSize(205) + "px";
                }

            }

            new jsCSS();

            //alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
            function getSize(value) {
                var v = parseInt(w) * (value / OriginalWidth);
                return v;
            }
            var clickstatu = 1;
            $("#down1Img").click(function () {
                if (clickstatu == 1)
                {
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/up.png");
                    $("#jxsSelect").css("display", "block");
                    clickstatu = 2;
                } else
                {
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                    $("#jxsSelect").css("display", "none");
                    clickstatu = 1;
                }

            });
            $(".jxsSapn").click(function () {
                this.style.color = "#dbdbdb";
                if (clickstatu == 1)
                {
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/up.png");
                    $("#jxsSelect").css("display", "block");
                    clickstatu = 2;
                } else
                {
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                    $("#jxsSelect").css("display", "none");
                    clickstatu = 1;
                }

            });

            $('.dealerSelect').click(function () {
                $(".jxsSapn").css("color", "black");
                $('input[name="dealer"]').val($(this).html());
                $("#down1Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                $("#jxsSelect").css("display", "none");
                clickstatu = 1;
            });

            $("#down2Img").click(function () {
                if (clickstatu == 1)
                {
                    $("#down2Img").attr("src", "_STATIC_/2015/buy/image/up.png");
                    $("#blSelect").css("display", "block");
                    clickstatu = 2;
                } else
                {
                    $("#down2Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                    $("#blSelect").css("display", "none");
                    clickstatu = 1;
                }

            });  
            $(".jxqxSapn").click(function () {
                this.style.color = "black";
                if (clickstatu == 1)
                {
                    $("#down2Img").attr("src", "_STATIC_/2015/buy/image/up.png");
                    $("#blSelect").css("display", "block");
                    clickstatu = 2;
                } else
                {
                    $("#down2Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                    $("#blSelect").css("display", "none");
                    clickstatu = 1;
                }

            });

            $(".jxsdiv_loanmonth").click(function () {
                $(this).find("input[type=radio]").attr("checked", 'checked');
                var val = $(this).find("span.qi").html() + $(this).find("span.percent").html();
                $("input[name='loanmonth_input']").val(val);
                $("#down2Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                $("#blSelect").css("display", "none");
                clickstatu = 1;
            });




            max_money = '{$setting.buy_setting.loanmoney.max}';
            min_money = '{$setting.buy_setting.loanmoney.min}';
            //$("input[name='loanmonth_input']").val($('input[name="loanmonth"]:checked').val() + "期首付百分之" + $('input[name="loanmonth"]:checked').attr("percent") + "%");

            $('.applyfirst').click(function () {
                var data = {};
                var img_dir = "_STATIC_/2015/borrow/image/index/";
                var img_src = $(this).attr('src');
                var img_name = img_src.substr(img_src.lastIndexOf("/") + 1);
                if (img_name != undefined && img_name == 'applyicon-up.png')
                    return false;
                var input_name = {
                    'city': '贷款城市',
                    'loanmoney': '车商报价',
                    'dealer': '经销商',
                    'car_brand': '车辆品牌',
                    'car_class': '车辆型号',
                };
                var input_attr = new Array();
                input_attr['city'] = '请输入贷款城市';
                input_attr['loanmoney'] = '请输入车商报价';
                input_attr['dealer'] = '请选择经销商';
                input_attr['car_brand'] = '请输入车辆品牌';
                input_attr['car_class'] = '请输入车辆型号';
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
                // || /^[1-9][0-9]*$/.test($(data).attr('loanmoney'))==false
                if (!$(data).attr('loanmoney')) {
                    alert('输入的车商报价不正确');
                    return false;
                }
                /*if($(data).attr('loanmoney')<Math.floor(min_money) ||  $(data).attr('loanmoney')>Math.floor(max_money)){
                 alert('请输入'+min_money+'-'+max_money+'元之间的车商报价);
                 return false;
                 }*/
                $(data).attr('loanmonth', $('input[name="loanmonth"]:checked').val());
                $(this).attr('src', img_dir + 'applyicon-up.png');
                $.ajax({
                    'type': 'post',
                    'dataType': 'json',
                    'url': "/Buy/index",
                    "data": {'data': data},
                    success: function (json) {
                        if (json.status == 1) {
                            location.href = '/Buy/add_buy_submit';
                        } else {
                            alert(json.info);
                            $('.applyfirst').attr('src', img_dir + 'apply.jpg');
                        }

                    },
                });
            });



            $(".jxsdiv").click(function () {
                var money = $('input[name="loanmoney"]').val();
                money = parseInt(money);
                if (isNaN(money)) {
                    money = '';
                } else if (money > max_money) {
                    //money = max_money;
                }
                $('input[name="loanmoney"]').val(money);
                calculate_money(money);
            });

            $('input[name="loanmoney"]').keyup(function () {
                var money = $('input[name="loanmoney"]').val();
                money = parseInt(money);
                if (isNaN(money)) {
                    money = '';
                } else if (money > max_money) {
                    //money = max_money;
                }
                $('input[name="loanmoney"]').val(money);
                calculate_money(money);
            });
            function calculate_money(money) {
                var fee_point = '{$setting.buy_setting.month_point}';
                var loanmonth = $('input[name="loanmonth"]:checked').val();
                var first_percent = $('input[name="loanmonth"]:checked').attr('percent');
                var month_money = 0;
                var first_money = 0;
                fee_point = fee_point * 0.01;
                money = Math.floor(money);
                month_money = (money * fee_point * loanmonth + money) / loanmonth;
                month_money = month_money.toFixed(2);
                first_money = money * first_percent * 0.01;
                first_money = first_money.toFixed(2);
                if (isNaN(month_money))
                    month_money = 0;
                if (isNaN(first_money))
                    first_money = 0;
                $('.month_money').html(month_money);//月还款
                $('.first_money').html(first_money);//首付
            }
        </script>
    </body>
</html>
{__NOLAYOUT__}