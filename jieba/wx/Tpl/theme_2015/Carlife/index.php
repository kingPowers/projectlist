<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>借吧</title>
        <link rel="stylesheet" href="_STATIC_/2015/Carlife/css/index.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/index/index">
                <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne">返回</span>
            </a> 
            <span class="zxwx sizeOne">我与借吧</span>
        </div>
        <!--头结束-->
        <div class="noneDiv"></div>

        <!--选择 精华 全部-->
        <div class="chooseDiv">
            <p class="jxChoose sizeFour">
            <if condition="$index eq 0">
                <span class="chooseWz line-wz">
                    <else/>
                    <span class="chooseWz">
                        </if>
                        <a href="/Carlife/index/is_cream/1" style="color:#000000;text-decoration:none;">精选</a>
                    </span>
                    </p>
                    <p class="allChoose sizeFour">
                    <if condition="$index eq 1">
                        <span class="chooseWzall line-wz">
                            <else/>
                            <span class="chooseWzall">
                                </if>
                                <a href="/Carlife/index" style="color:#000000;text-decoration:none;">全部</a>
                            </span>
                            </p>

                            </div>
                            <!--中间部分-->
                            <div class="centerDiv">
                                <foreach name="list" item="vo">
                                    <div class="infoDiv">
                                        <div class="namesDiv">
                                            <div class="leftDiv">
                                                <if condition="$vo['avatar'] neq null">
                                                    <img src="{$vo['avatar']}" alt="" class="carlifeheadsImg"/>
                                                    <else/>
                                                    <img src="_STATIC_/2015/member/image/account/heads.png" alt="" class="carlifeheadsImg"/>
                                                </if>
                                                <span class="nameSapn detaleclssize">{$vo['names']}</span>
                                                <span class="timeSpan diffwzsize">{$vo['timeadd']|date='m月d日 H:i',###}</span>
                                            </div>
                                            <div class="rightDiv">
                                                <a href="/carlife/detail/id/{$vo['id']}">
                                                    <span class="lookwz detaleclssize"> 查看全文>></span>
                                                </a>
                                            </div>
                                        </div>
                                        <a href="/carlife/detail/id/{$vo['id']}">
                                            <div class="contentDiv">
                                                <div class="wzContent sizeOne">
                                                    <p class="mysay">
                                                        {$vo['content']}
                                                    </p>
                                                </div>
                                                <if condition="$vo['images'] neq null">
                                                    <div class="imgDiv">
                                                        <foreach name="vo.images" item="picture" key="pic">
                                                            <a href="/Carlife/imagedisplay/pic/<?php $exten=explode('.',$picture); echo $exten[0];?>/lastname/<?php $exten=explode('.',$picture); echo $exten[1];?>/index/1" target="view_window"><img src="{$picture}" alt="" class="lifes{$pic+1}Img"/></a>
                                                        </foreach>
                                                    </div>
                                                </if>
                                            </div>
                                        </a>
                                        <div class="bottomsDiv">
                                            <span class="placeWz sizeFour">{$vo['location']}</span>
                                            <div class="clickDiv" nid="{$vo['id']}" id="clickd{$vo['id']}">
                                                <if condition="$vo['is_point'] eq 0">
                                                    <img src="_STATIC_/2015/Carlife/image/loves.png" alt="" class="lovesImg" rel="like" id="pointClick" nid="{$vo['id']}"/>
                                                    <else/>
                                                    <img src="_STATIC_/2015/Carlife/image/lovesBlue.png" alt="" class="lovesImg" rel="unlike" id="nopointClick" nid="{$vo['id']}"/>
                                                </if>
                                                <span class="clicknum sizeTwo">
                                                    <if condition="$vo['point_num'] gt 0">
                                                        {$vo['point_num']}
                                                        <else/>
                                                        0
                                                    </if>
                                                </span>
                                            </div>
                                            <a href="/carlife/detail/id/{$vo['id']}/parm/3">
                                                <div class="messageDiv ">
                                                    <img src="_STATIC_/2015/Carlife/image/message.png" alt="" class="messageImg" onclick=""/>
                                                    <span class="messagenum sizeTwo">
                                                        <if condition="$vo['eval_num'] gt 0">
                                                            {$vo['eval_num']}
                                                            <else/>
                                                            0
                                                        </if>
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </foreach>
                            </div>

                            <script type="text/javascript">
                                var d = document;
                                var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
                                var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
                                var headers, retrunImg, fhwz, zxwx, noneDiv, centerDiv, namesDiv, chooseDiv, contentDiv, bottomsDiv, leftDiv, wzContent, imgDiv, infoDetail, infoDetailleftDiv, footerDiv;
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
                                    leftDiv = d.getElementsByClassName("leftDiv")[0];
                                    this.leftDiv(leftDiv);
//                                    footerDiv = d.getElementsByClassName("footerDiv")[0];
//                                    this.footerDiv(footerDiv);

                                    imgDiv = d.getElementsByClassName("imgDiv")[0];
                                    this.imgDiv(imgDiv);
                                    chooseDiv = d.getElementsByClassName("chooseDiv")[0];
                                    this.chooseDiv(chooseDiv);
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
                                    chooseDiv: function (chooseDiv) {
                                        chooseDiv.style.height = getSize(100) + "px";
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
                                    infoDetail: function (infoDetail) {
                                        for (var i = 0; i < d.getElementsByClassName("infoDetail").length; i++) {
                                            var infoDetail = d.getElementsByClassName("infoDetail")[i];
                                            infoDetail.style.height = getSize(100) + "px";
                                        }

                                    },
                                    namesDiv: function (namesDiv) {
                                        for (var i = 0; i < d.getElementsByClassName("namesDiv").length; i++) {
                                            var namesDiv = d.getElementsByClassName("namesDiv")[i];
                                            namesDiv.style.height = getSize(100) + "px";
                                        }
                                    },
                                    imgDiv: function (imgDiv) {
                                        for (var i = 0; i < d.getElementsByClassName("imgDiv").length; i++) {
                                            var imgDiv = d.getElementsByClassName("imgDiv")[i];
                                            imgDiv.style.height = getSize(210) + "px";
                                            //imgDiv.style.top = wzContent.offsetHeight + "px";
                                        }

                                    },
                                    contentDiv: function (contentDiv) {
                                    }
                                }


                                new jsCSS();
                                //            var d = document;
                                //            var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
                                //            var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;
                                //            var headers, retrunImg, fhwz, zxwx, noneDiv, chooseDiv, centerDiv, infoDiv, namesDiv, contentDiv, bottomsDiv, leftDiv, wzContent, imgDiv;
                                //            var OriginalWidth = 750; //原始图纸的宽度
                                //
                                //            jsCSS = function () {
                                //                this.fontSize();
                                //                headers = d.getElementsByClassName("headers")[0];
                                //                retrunImg = d.getElementsByClassName("retrunImg")[0];
                                //                fhwz = d.getElementsByClassName("fhwz")[0];
                                //                zxwx = d.getElementsByClassName("zxwx")[0];
                                //                this.heiads(headers, retrunImg, fhwz, zxwx);
                                //                noneDiv = d.getElementsByClassName("noneDiv")[0];
                                //                this.noneDiv(noneDiv, headers);
                                //                chooseDiv = d.getElementsByClassName("chooseDiv")[0];
                                //                this.chooseDiv(chooseDiv);
                                //                infoDiv = d.getElementsByClassName("infoDiv")[0];
                                //                this.infoDiv(infoDiv);
                                //                namesDiv = d.getElementsByClassName("namesDiv")[0];
                                //                this.namesDiv(namesDiv);
                                //                contentDiv = d.getElementsByClassName("contentDiv")[0];
                                //                this.contentDiv(contentDiv);
                                //                bottomsDiv = d.getElementsByClassName("bottomsDiv")[0];
                                //                this.bottomsDiv(bottomsDiv);
                                //                leftDiv = d.getElementsByClassName("leftDiv")[0];
                                //                this.leftDiv(leftDiv);
                                //                wzContent = d.getElementsByClassName("wzContent")[0];
                                //                this.wzContent(wzContent);
                                //                imgDiv = d.getElementsByClassName("imgDiv")[0];
                                //                this.imgDiv(imgDiv);
                                //            }
                                //
                                //            jsCSS.prototype = {
                                //                fontSize: function () {
                                //                    for (var i = 0; i < d.getElementsByClassName("sizeOne").length; i++) {
                                //                        d.getElementsByClassName("sizeOne")[i].style.fontSize = parseInt(w) * 0.040 + "px";
                                //                    }
                                //                    for (var i = 0; i < d.getElementsByClassName("sizeTwo").length; i++) {
                                //                        d.getElementsByClassName("sizeTwo")[i].style.fontSize = parseInt(w) * 0.038 + "px";
                                //                    }
                                //                    for (var i = 0; i < d.getElementsByClassName("sizeFour").length; i++) {
                                //                        d.getElementsByClassName("sizeFour")[i].style.fontSize = parseInt(w) * 0.048 + "px";
                                //                    }
                                //                    for (var i = 0; i < d.getElementsByClassName("sizeBig").length; i++) {
                                //                        d.getElementsByClassName("sizeBig")[i].style.fontSize = parseInt(w) * 0.045 + "px";
                                //                    }
                                //                    for (var i = 0; i < d.getElementsByClassName("detaleclssize").length; i++) {
                                //                        d.getElementsByClassName("detaleclssize")[i].style.fontSize = parseInt(w) * 0.035 + "px";
                                //                    }
                                //                    for (var i = 0; i < d.getElementsByClassName("diffwzsize").length; i++) {
                                //                        d.getElementsByClassName("diffwzsize")[i].style.fontSize = parseInt(w) * 0.032 + "px";
                                //                    }
                                //                },
                                //                heiads: function (headers, retrunImg, fhwz, zxwx) {
                                //                    retrunImg.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                                //                    fhwz.style.lineHeight = retrunImg.offsetHeight + "px";
                                //                    zxwx.style.lineHeight = retrunImg.offsetHeight + "px";
                                //                    fhwz.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                                //                    zxwx.style.top = ((headers.offsetHeight - retrunImg.offsetHeight) / 2) + "px";
                                //                },
                                //                noneDiv: function (noneDiv, headers) {
                                //                    noneDiv.style.height = headers.offsetHeight + "px";
                                //                },
                                //                chooseDiv: function (chooseDiv) {
                                //                    chooseDiv.style.height = getSize(100) + "px";
                                //                },
                                //                leftDiv: function (leftDiv) {
                                //                    for (var i = 0; i < d.getElementsByClassName("leftDiv").length; i++) {
                                //                        var leftDiv = d.getElementsByClassName("leftDiv")[i];
                                //                        leftDiv.style.height = getSize(100) + "px";
                                //                    }
                                //
                                //                },
                                ////                infoDiv: function (infoDiv) {
                                ////                    // infoDiv.style.height = getSize(550) + "px";
                                ////                    for (var i = 0; i < d.getElementsByClassName("infoDiv").length; i++) {
                                ////                        var infoDiv = d.getElementsByClassName("infoDiv")[i];
                                ////                        infoDiv.style.height = getSize(450) + "px";
                                ////                    }
                                ////                },
                                //                namesDiv: function (namesDiv) {
                                //                    for (var i = 0; i < d.getElementsByClassName("namesDiv").length; i++) {
                                //                        var namesDiv = d.getElementsByClassName("namesDiv")[i];
                                //                        namesDiv.style.height = getSize(100) + "px";
                                //                    }
                                //                }
                                ////                contentDiv: function (contentDiv) {
                                ////                    for (var i = 0; i < d.getElementsByClassName("contentDiv").length; i++) {
                                ////                        var contentDiv = d.getElementsByClassName("contentDiv")[i];
                                ////                        contentDiv.style.height = getSize(250) + "px";
                                ////                      //  contentDiv.style.top = namesDiv.offsetHeight + "px";
                                ////                    }
                                ////
                                ////                },
                                ////                wzContent: function (wzContent) {
                                ////                    wzContent.style.height = getSize(100) + "px";
                                ////                },
                                ////                imgDiv: function (imgDiv) {
                                ////                    for (var i = 0; i < d.getElementsByClassName("imgDiv").length; i++) {
                                ////                        var imgDiv = d.getElementsByClassName("imgDiv")[i];
                                ////                        imgDiv.style.height = getSize(210) + "px";
                                ////                        //imgDiv.style.top = wzContent.offsetHeight + "px";
                                ////                    }
                                ////
                                ////                },
                                ////                bottomsDiv: function (bottomsDiv) {
                                ////                    for (var i = 0; i < d.getElementsByClassName("bottomsDiv").length; i++) {
                                ////                        var bottomsDiv = d.getElementsByClassName("bottomsDiv")[i];
                                ////                        bottomsDiv.style.height = getSize(100) + "px";
                                ////                    //    bottomsDiv.style.top = (namesDiv.offsetHeight + contentDiv.offsetHeight) + "px";
                                ////                    }
                                ////                }
                                //            }
                                //
                                //
                                //            new jsCSS();
                                //alue传入图纸上你测量出来的高度;(此方法只能用于高度溢出时测了层高度)
                                function getSize(value) {
                                    var v = parseInt(w) * (value / OriginalWidth);
                                    return v;
                                }
                            </script>
                            <script>
                                $('.clickDiv').on("click", ".lovesImg", function () {
                                    var A = $(this).attr("id");
                                    var B = A.split("like");
                                    var messageID = B[1];
                                    var C = parseInt($("#likeCount" + messageID).html());
                                    $(this).css("background-position", "")
                                    var D = $(this).attr("rel");
                                    var idssssss = $(this).attr("nid");
                                    var detail_id = 'clickd' + idssssss;
                                    if (D === 'like')
                                    {
                                        var data = {};
                                        $(data).attr('assed_id', idssssss);
                                        $.post('/Carlife/goodpoint.html', data, function (F) {
                                            var F = eval(F);
                                            if (F.status == 0) {
                                            } else {
                                                $("#" + detail_id + " .lovesImg").attr('src', '_STATIC_/2015/Carlife/image/lovesBlue.png');
                                                var befor_num = $("#" + detail_id + " .clicknum").text();
                                                var after_num = 1 + parseInt(befor_num);
                                                $("#" + detail_id + " .clicknum").html(after_num);
                                                $("#" + detail_id + " .lovesImg").addClass("heartAnimation").attr("rel", "unlike");
                                            }
                                            alert(F.info);
                                        }, 'json');
                                    } else
                                    {
                                        var data = {};
                                        $(data).attr('assed_id', idssssss);
                                        $.post('/Carlife/canclepoint.html', data, function (F) {
                                            var F = eval(F);
                                            if (F.status == 0) {
                                            } else {
                                                $("#" + detail_id + " .lovesImg").attr('src', '_STATIC_/2015/Carlife/image/loves.png');
                                                var befor_num = $("#" + detail_id + " .clicknum").text();
                                                var after_num = parseInt(befor_num) - 1;
                                                $("#" + detail_id + " .clicknum").html(after_num);
                                                $("#" + detail_id + " .lovesImg").removeClass("heartAnimation").attr("rel", "like");
                                            }
                                            alert(F.info);
                                        }, 'json');

                                    }
                                });
                            </script>
                            </body>
                            </html>
                            {__NOLAYOUT__}