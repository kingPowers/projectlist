<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/borrow/css/index.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/index/index"  style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne">返回</span></a>
            <span class="zxwx sizeOne">车贷宝</span>
        </div>
        <!--头结束-->
        <div class="noneDiv"></div>
        <div class="banner">
            <img src="_STATIC_/2015/borrow/image/index/banner.jpg" class="bannerImg"/>
        </div>
      <form name="applyForm" class="applyForm">
        <div class="chooseDiv">
            <img src="_STATIC_/2015/borrow/image/index/selfcaricon.png" alt="" class="leftImg"/>
            <img src="_STATIC_/2015/borrow/image/index/commpycar.png" alt="" class="rightImg"/>
            <input type="hidden" name='car_type' value='1'/>
            <input type="hidden" name='loanmonth' value='1'/>
        </div>
        <div class="threeDiv">
            <div class="citycls tDiv">
                <div class="bt sizeFour">贷款城市</div>
                <input type="text" name='city' class="ipt sizeTwo cityclick"  placeholder ="请输入贷款城市" />
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="moneycls tDiv">
                <div class="bt sizeFour">贷款金额</div>
                <input type="text" autocomplete="off"  maxlength='8'  name='loanmoney' class="ipt sizeTwo"  placeholder ="请输入贷款金额(3-50万)" style="width:45%;left:32%;"/>
                <span class="yf ipts sizeTwo">元</span>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="timecls tDiv" id="moneyClick">
                <div class="bt sizeFour">借款期限</div>
                <input  id="moenysValue" class="ipt sizeTwo" placeholder ="请输入借款期限" readonly="true"  style="left:32.5%;width:35%;"></input>
                <span class="yf ipts sizeTwo">
<!--                <span class="diffwz diffwzsize"></span>个月-->
                    <img src="_STATIC_/2015/buy/image/down.png" alt="" id="down1Img" class="downsImg" style="width:15%;height:auto;position:absolute;margin-top:22%;"/>
                </span>
            </div>
            <div class="selectsDiv" id="jxsSelect">
                <div class="oneDiv">
                    <input type="radio" name="loanmonth1" class="regular-checkbox-1" autocomplete="off" checked="checked" value="1"/>
                    <div class="jxsleftdiv sizeTwo">1月</div>
                </div>
                <div class="oneDiv">
                    <input type="radio" name="loanmonth1" class="regular-checkbox-2" autocomplete="off" value="2"/>
                    <div class="jxsrightdiv sizeTwo">2月</div>
                </div>
                <div class="oneDiv">
                    <input type="radio" name="loanmonth1"  class="regular-checkbox-3" autocomplete="off" value="3"/>
                    <div class="jxsleftdiv sizeTwo">3月</div>
                </div>
                <div class="oneDiv">
                    <input type="radio" name="loanmonth1" class="regular-checkbox-4" autocomplete="off" value="12"/>
                    <div class="jxsrightdiv sizeTwo">12月</div>
                </div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="timecls tDiv">
                <div class="bt sizeFour">月需还款</div>
                <p  class="ipt sizeTwo month_money diffwz" placeholder="" style="left:31%;width:35%;"></p>
                <span class="yf ipts sizeTwo">元/月</span>                
            </div>
            <div class="up_pic"><font class="bt sizeFour">行驶证</font> 
                <input type="file" name="drive_pic" id="drive_pic">
                <img src="_STATIC_/2015/image/yydai/agent/add_photo.png" class="btn_up">
                <img src="_STATIC_/2015/image/yydai/agent/pic_xsz.png" class="pic_xsz"> 
            </div>
        </div>
        <div class="fourDiv">
            <div class="detalecls detaleclssize">计算结果仅供参考，实际费用请以还款计划为准！</div>
            <div class="applyBtnDiv">
                <img src="_STATIC_/2015/borrow/image/index/apply.jpg" alt="" class="applyImg applyfirst"/>
            </div>
            <div class="showinfoDiv">
                <p class="moneyinfo detaleclssize">贷款总额 <span class="moneyinfovcolor">{$setting.borrow_money|default='0.00'}元</span></p>
                <p class="nowapply detaleclssize">正在申请 <span class="moneyinfovcolor">{$setting.borrow_count|default='0'}人</span></p>
            </div>
        </div>
        <div class="fiveDiv">
            <img src="_STATIC_/2015/borrow/image/index/five.jpg" alt="" class="fiveImg"/>
        </div>
        <div class="sixDiv">
            <img src="_STATIC_/2015/borrow/image/index/six.jpg" alt="" class="fiveImg"/>
        </div>
     </form>
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
//                        $(".retrunImg").css("margin-top", "1%");
//                        $(".fhwz").css("margin-top", "1.2%");
//                        $(".zxwx").css("margin-top", "1.2%");
                    });
                    $("input").blur(function () {
                        $("body").css("position", "absolute");
                        $(".headers").css("position", "fixed");
                        $(".headers").css("height", "6.8%");
                        $(".retrunImg").css("margin-top", "0%");
                        $(".fhwz").css("margin-top", "0%");
                        $(".zxwx").css("margin-top", "0%");
                    });
                }
            })
        </script>
        <script type="text/javascript">
            var d = document;
            var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
            var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
            var headers, retrunImg, fhwz, zxwx, noneDiv, chooseDiv, threeDiv, fourDiv, fiveDiv;
            var OriginalWidth = 750; //原始图纸的宽度

            jsCSS = function () {
                this.fontSize();
                headers = d.getElementsByClassName("headers")[0];
                retrunImg = d.getElementsByClassName("retrunImg")[0];
                fhwz = d.getElementsByClassName("fhwz")[0];
                zxwx = d.getElementsByClassName("zxwx")[0];
                this.heiads(headers, retrunImg, fhwz, zxwx);
                noneDiv = d.getElementsByClassName("noneDiv")[0];
                this.noneDiv(noneDiv, headers);
                chooseDiv = d.getElementsByClassName("chooseDiv")[0];
                leftImg = d.getElementsByClassName("leftImg")[0];
                rightImg = d.getElementsByClassName("rightImg")[0];
                this.chooseDiv(chooseDiv, leftImg, rightImg);
                threeDiv = d.getElementsByClassName("threeDiv")[0];
                this.threeDiv(threeDiv);
                fourDiv = d.getElementsByClassName("fourDiv")[0];
                this.fourDiv(fourDiv);
                fiveDiv = d.getElementsByClassName("fiveDiv")[0];
                this.fiveDiv(fiveDiv);
                sixDiv = d.getElementsByClassName("sixDiv")[0];
                this.sixDiv(sixDiv);
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
                chooseDiv: function (chooseDiv, leftImg, rightImg) {
                    chooseDiv.style.height = getSize(172) + "px";
                    leftImg.style.top = (chooseDiv.offsetHeight - getSize(84)) / 2 + "px";
                    rightImg.style.top = (chooseDiv.offsetHeight - getSize(84)) / 2 + "px";
                },
                threeDiv: function (threeDiv) {
                    //  threeDiv.style.height = getSize(410) + "px";
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
                sixDiv: function (sixDiv) {
                    sixDiv.style.height = getSize(295) + "px";
                }

            }

            new jsCSS();
            //alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
            function getSize(value) {
                var v = parseInt(w) * (value / OriginalWidth);
                return v;
            }


            $(".rightImg").click(function () {
                $(".leftImg").attr("src", "_STATIC_/2015/borrow/image/index/selfcar.png");
                $(".rightImg").attr("src", "_STATIC_/2015/borrow/image/index/commpycaricon.png");
                $("input[name='car_type']").val('2');
            });
            $(".leftImg").click();
            $(".leftImg").click(function () {
                $(".leftImg").attr("src", "_STATIC_/2015/borrow/image/index/selfcaricon.png");
                $(".rightImg").attr("src", "_STATIC_/2015/borrow/image/index/commpycar.png");
                $("input[name='car_type']").val('1');
            });
            max_money = '{$setting.borrow_setting.loanmoney.max}';
            min_money = '{$setting.borrow_setting.loanmoney.min}';
            /*$('input[name="loanmonth"]').blur(function () {
             var val = $(this).val();
             if (val == '')
             return false;
             if (!(val == 1 || val == 2 || val == 3 || val == 12)) {
             //  alert("借款期限不正确");
             $(this).val('');
             } else {
             $('input[name="loanmoney"]').keyup();
             }
             });*/
            $("input[name='drive_pic']").change(function(){
                var file=document.getElementById("drive_pic");
                var src = window.URL.createObjectURL(file.files[0]);
                $(".btn_up").attr("src",src);
            })
            $('.applyfirst').click(function () {
                if($("input[name='drive_pic']").val() == ''){alert("请上传驾驶证正面照！"); return false;}
                var data = {};
                var img_dir = "_STATIC_/2015/borrow/image/index/";
                var img_src = $(this).attr('src');
                var img_name = img_src.substr(img_src.lastIndexOf("/") + 1);
                if (img_name != undefined && img_name == 'applyicon-up.png')
                    return false;
                var input_name = {
                    'city': '贷款城市',
                    'loanmoney': '贷款金额',
                    'loanmonth': '借款期限'
                };
                var is_check = 1;
                $.each(input_name, function (i, val) {
                    $(data).attr(i, $('input[name="' + i + '"]').val());
                    if ($(data).attr(i) == '' || undefined == $(data).attr(i)) {
                        alert(val + "不能为空");
                        is_check = 0;
                        return false;
                    }
                });
                if (is_check == 0)
                    return false;
                if (!$(data).attr('loanmoney') || /^[1-9][0-9]*$/.test($(data).attr('loanmoney')) == false) {
                    alert('输入的贷款金额不正确');
                    return false;
                }
                if ($(data).attr('loanmoney') < Math.floor(min_money)) {
                    alert('输入金额至少' + min_money + "元");
                    return false;
                }

                var val = $('input[name="loanmonth"]').val();
                if (!(val == 1 || val == 2 || val == 3 || val == 12)) {
                    //alert("借款期限不正确");
                    return false;
                }
                /*if($(data).attr('loanmoney')<Math.floor(min_money) ||  $(data).attr('loanmoney')>Math.floor(max_money)){
                 alert('请输入'+min_money+'-'+max_money+'元之间的贷款金额');
                 return false;
                 }*/
                $(data).attr('car_type', $('input[name="car_type"]').val());
                $(data).attr('loanmonth', $('input[name="loanmonth"]').val());
                $(this).attr('src', img_dir + 'applyicon-up.png');
                var formData = new FormData($( ".applyForm" )[0]);
                console.log(formData);   
                $.ajax({  
                  url: '/Borrow/index' ,  
                  type: 'POST',  
                  data: formData,  
                  async: false,  
                  cache: false,  
                  contentType: false,  
                  processData: false,  
                  success: function (json) {  
                    var json = eval("(" + json + ")");
                     //alert(json.info);
                      if (json.status == 1) {
                            location.href = '/Borrow/add_borrow_submit';
                      } else {
                            alert(json.info);
                            $('.applyfirst').attr('src', img_dir + 'apply.jpg');
                      }   
                  }  
                }); 
            });
            $('input[name="loanmoney"]').keyup(function () {
                var type = $('input[name="car_type"]').val();
                var money = $('input[name="loanmoney"]').val();
                money = parseInt(money);
                if (isNaN(money)) {
                    money = '';
                } else if (money > max_money) {
                    //money = max_money;
                }
                $('input[name="loanmoney"]').val(money);
                calculate_money(money, type);
            });
            function calculate_money(money, type) {
                var personal_fee_point = '{$setting.borrow_setting.month_point.personal_car}';
                var company_fee_point = '{$setting.borrow_setting.month_point.company_car}';
                var fee_point = '';
                var loanmonth = $('input[name="loanmonth"]').val();
                var month_money = 0;
                if (loanmonth == '' || loanmonth == undefined)
                    return false;
                money = Math.floor(money);
                type = Math.floor(type);
                fee_point = (type == 1 ? personal_fee_point : company_fee_point) * 0.01;
                month_money = money * fee_point * Math.pow(1 + fee_point, loanmonth) / (Math.pow(1 + fee_point, loanmonth) - 1);
                month_money = month_money.toFixed(2);
                if (isNaN(month_money))
                    month_money = 0;
                $('.month_money').html(month_money);
            }
            var clickstatu = 1;
            $("#moneyClick").click(function () {

//                $("#down1Img").click(function () {
                if (clickstatu == 1)
                {
                    //  alert(clickstatu);
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/up.png");
                    $(".selectsDiv").css("display", "block");
                    clickstatu = 2;
                } else
                {
                    //  alert(clickstatu);
                    $("#down1Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                    $(".selectsDiv").css("display", "none");
                    clickstatu = 1;
                }

//                });

            });
            $("input[name='loanmonth1']").click(function () {
                $("#down1Img").attr("src", "_STATIC_/2015/buy/image/down.png");
                $(".selectsDiv").css("display", "none");
                $("#moenysValue").val($("input[name='loanmonth1']:checked").val());
                $('input[name="loanmonth"]').val($("input[name='loanmonth1']:checked").val());
                $('input[name="loanmoney"]').keyup();
            });

            $(".oneDiv").click(function () {
                $(this).children("input[type=radio]").attr("checked", 'checked').click();
            });


        </script>
       

    </body>
</html>
{__NOLAYOUT__}