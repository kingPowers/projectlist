<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>爱车估价</title>
        <link rel="stylesheet" href="_STATIC_/2015/pinggu/css/detail.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript"> 
            $(function(){            
                if(isWeixin()) {
                    $(".headers").css("display","block");
                }else{
                    $(".centerDiv").css("top","2.5%");
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
    </head>
    <body bgcolor="#efefef">
        <div class="headers" style="display: none;">
            <a href="/pinggu/index" style="color:white;text-decoration:none;">
                <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne" style="color:white;text-decoration:none;">返回</span>
            </a>
            <span class="zxwx sizeOne">爱车估价</span>
        </div>
        <!--头结束-->
        <div class="centerDiv">
            <div class="cxNamespan">
                <span class="wzInfo sizeBig">
                    {$data['brand_name']}
                </span>
            </div>
            <div class="centerContent">
<!--                <div class="CarImgDiv">-->
                    <img src="_STATIC_/2015/pinggu/image/carsImg.jpg" class="CarImgDiv"/>
<!--                </div>-->
                <span class="spTime sizeOne">{$data['time']}上牌</span>
                <span class="citySpan  sizeOne">城   市:<span>{$data['city_name']}</span></span>
                <span class="lcSpan sizeOne">里   程：<span>{$data['run_km']}万公里</span></span>
                <span class="ygjSpan sizeOne">预估价:<span style="color:red;">{$data['min_price']}-{$data['max_price']}万</span></span>
            </div>
            <a href="/borrow/index?jieba=gujia">
                <img src="_STATIC_/2015/pinggu/image/jkan.png" alt="" class="jkanImg"/>
            </a>

        </div>

        <script type="text/javascript">
            var d = document;
            var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
            var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
            var headers, retrunImg, fhwz, zxwx, noneDiv, centerDiv, namesDiv, contentDiv, bottomsDiv, leftDiv, wzContent, imgDiv, infoDetail, infoDetailleftDiv, footerDiv;
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

                namesDiv = d.getElementsByClassName("namesDiv")[0];
                this.namesDiv(namesDiv);
                contentDiv = d.getElementsByClassName("contentDiv")[0];
                this.contentDiv(contentDiv);
//                bottomsDiv = d.getElementsByClassName("bottomsDiv")[0];
//                this.bottomsDiv(bottomsDiv);
                leftDiv = d.getElementsByClassName("leftDiv")[0];
                this.leftDiv(leftDiv);
                footerDiv = d.getElementsByClassName("footerDiv")[0];
                this.footerDiv(footerDiv);
//                infoDetailleftDiv = d.getElementsByClassName("infoDetailleftDiv")[0];
//                this.infoDetailleftDiv(infoDetailleftDiv);
//                imgDiv = d.getElementsByClassName("imgDiv")[0];
//                this.imgDiv(imgDiv);
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
                        d.getElementsByClassName("sizeBig")[i].style.fontSize = parseInt(w) * 0.042 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("detaleclssize").length; i++) {
                        d.getElementsByClassName("detaleclssize")[i].style.fontSize = parseInt(w) * 0.035 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("diffwzsize").length; i++) {
                        d.getElementsByClassName("diffwzsize")[i].style.fontSize = parseInt(w) * 0.032 + "px";
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
                leftDiv: function (leftDiv) {
                    for (var i = 0; i < d.getElementsByClassName("leftDiv").length; i++) {
                        var leftDiv = d.getElementsByClassName("leftDiv")[i];
                        leftDiv.style.height = getSize(100) + "px";
                    }

                },
//                  infoDetailleftDiv: function (infoDetailleftDiv) {
//                    for (var i = 0; i < d.getElementsByClassName("infoDetailleftDiv").length; i++) {
//                        var infoDetailleftDiv = d.getElementsByClassName("infoDetailleftDiv")[i];
//                        infoDetailleftDiv.style.height = getSize(100) + "px";
//                    }
//
//                },
                infoDetail: function (infoDetail) {
                    for (var i = 0; i < d.getElementsByClassName("infoDetail").length; i++) {
                        var infoDetail = d.getElementsByClassName("infoDetail")[i];
                        infoDetail.style.height = getSize(100) + "px";
                    }

                },
                footerDiv: function (footerDiv) {
                    footerDiv.style.height = getSize(114) + "px";
                },
                namesDiv: function (namesDiv) {
                    for (var i = 0; i < d.getElementsByClassName("namesDiv").length; i++) {
                        var namesDiv = d.getElementsByClassName("namesDiv")[i];
                        namesDiv.style.height = getSize(100) + "px";
                    }
                },
                contentDiv: function (contentDiv) {
                }
            }


            new jsCSS();
            //alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
            function getSize(value) {
                var v = parseInt(w) * (value / OriginalWidth);
                return v;
            }

            var nums = 1;
            $(".infoDetail").click(function () {
                if (nums == 1)
                {
                    $(".deleteDiv").css("display", "block");
                    $(".bigbgDiv").css("display", "block");
                    nums = 2;
                } else {
                    $(".deleteDiv").css("display", "none");
                    $(".bigbgDiv").css("display", "none");
                    nums = 1;
                }
            });
            $(".deleteDiv").click(function () {
                $(".deleteDiv").css("display", "none");
                $(".bigbgDiv").css("display", "none");
            });
            $(".bigbgDiv").click(function () {
                $(".deleteDiv").css("display", "none");
                $(".bigbgDiv").css("display", "none");
            });

            $(".messageDiv").click(function () {
                $(".footerDiv").css("display", "block");
            });

            $(".seeds").click(function () {
                $(".footerDiv").css("display", "none");
            });


            $(function () {
                var parm = '{$_GET["parm"]}';
                if (parm == 3)
                {
                    $(".footerDiv").css("display", "block");
                    $(".bg2Div").css("display", "block");
                }
            });
            $(".bg2Div").click(function () {
                $(".footerDiv").css("display", "none");
                $(".bg2Div").css("display", "none");
            });



        </script>
    </body>
</html>
{__NOLAYOUT__}