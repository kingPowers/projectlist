<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>借吧</title>
        <link rel="stylesheet" href="_STATIC_/2015/Carlife/css/detail.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <a href="/carlife/index/" style="color:white;text-decoration:none;">
                <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne">返回</span>
            </a>
            <span class="zxwx sizeOne">我与借吧</span>
            <input type="hidden" id="memberid" value="{$memberid}">
            <input type="hidden" id="assed_id" value="{$assed_id}">
        </div>
        <!--头结束-->
        <div class="noneDiv"></div>
        <!--中间部分-->
        <div class="centerDiv">
            <!--上半部分-->
            <div class="infoDiv">
                <div class="namesDiv">
                    <div class="leftDiv">
                        <if condition="$detail['avatar'] neq null">
                            <img src="{$detail['avatar']}" alt="" class="carlifeheadsImg"/>
                            <else/>
                            <img src="_STATIC_/2015/member/image/account/heads.png" alt="" class="carlifeheadsImg"/>
                        </if>
                        <span class="nameSapn sizeBig">{$detail['username']}</span>
                        <span class="timeSpan detaleclssize">{$detail['timeadd']|date='m月d日 H:i',###}</span>
                        <input type="hidden" value="{$detail['memberid']}" id="memberid">
                    </div>
                    <div class="rightDiv">
                        <if condition="$detail['memberid'] eq $memberid">
                            <span class="lookwz detaleclssize" id="delete_assed">删除</span>
                        </if>
                    </div>
                </div>
                <div class="contentDiv">
                    <div class="wzContent sizeOne">
                        <p class="mysay">
                            {$detail['content']}
                        </p>
                    </div>
                    <if condition="$detail['images'] neq null">
                        <div class="imgDiv">
                            <foreach name="detail.images" item="picture" key="pic">
                                <a href="/Carlife/imagedisplay/pic/<?php $exten=explode('.',basename($picture)); echo $exten[0];?>/lastname/<?php $exten=explode('.',basename($picture)); echo $exten[1];?>/index/2/detail/{$assed_id}" target="view_window"><img src="{$picture}" alt="" class="lifes{$pic+1}Img"/></a>
                            </foreach>
                        </div>
                    </if>
                </div>
                <div class="bottomsDiv">
                    <span class="placeWz sizeTwo">{$detail['location']}</span>
                    <div class="clickDiv">
                        <if condition="$is_point eq 0">
                            <img src="_STATIC_/2015/Carlife/image/loves.png" alt="" class="lovesImg" rel="like" id="pointClick" />
                            <else/>
                            <img src="_STATIC_/2015/Carlife/image/lovesBlue.png" alt="" class="lovesImg" rel="unlike" id="nopointClick"/>
                        </if>

                        <span class="clicknum sizeTwo" id="point_num">
                            <if condition="$detail['point_num'] neq null">
                                {$detail['point_num']}
                                <else/>
                                0
                            </if>
                        </span>
                    </div>
                    <div class="messageDiv ">
                        <img src="_STATIC_/2015/Carlife/image/message.png" alt="" class="messageImg"/>
                        <span class="messagenum sizeTwo">
                            <if condition="$detail['eval_num'] neq null">
                                {$detail['eval_num']}
                                <else/>
                                0
                            </if>
                        </span>
                    </div>
                </div>
            </div>
            <!--评论信息部分-->
            <div class="commentInfo">
                <foreach name="list" item="lis">
                    <div class="infoDetail detail{$lis['id']}" >
                        <div class="borderDiv">
                            <if condition="$lis['avatar'] neq null">
                                <img src="{$lis['avatar']}" alt="" class="cusHead"/>
                                <else/>
                                <img src="_STATIC_/2015/member/image/account/heads.png" alt="" class="cusHead"/>
                            </if>
                            <div class="nameandtime" onclick="reanswer({$lis['from_memberid']})">
                                <span class="DetailnameSapn sizeBig">{$lis['names']}</span>
                                <span class="rightDivlookwz sizeBig">{$lis['timeadd']|date='m月d日 H:i',###}</span>
                            </div>
                            <if condition="$lis['to_memberid'] neq 0">
                                <div class="DetailtimeSpan sizeBig">
                                    <span class="answerSpan">回复</span>
                                    <span class="othername">{$lis['to_names']}</span>
                                    <span>:</span>{$lis['content']}
                                </div>
                                <else/>
                                <span class="DetailtimeSpan sizeBig">{$lis['content']}</span>
                            </if>
                            <if condition="$memberid eq $lis['from_memberid']">
                                <input type="hidden" id="eval_id" value="{$lis['id']}">
                                <span class="delteWz sizeOne" onclick="delteWz({$lis['id']})">删除</span>
                            </if>
                        </div>
                        <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;position:relative;float:left;"></div>
                    </div>
                </foreach>
            </div>
        </div>
        <div class="bigbgDiv"></div>
        <div class="footerDiv" style="border:none;">
            <input placeholder="评论" class="commentS sizeBig" id="send_eval_content"/>
            <div class="seeds sizeBig" id="sessLoad">发送</div>
        </div>
        <div class="bg2Div"></div>
        <input type="hidden" id="from_member_id_hidden" value=""/>

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
            leftDiv = d.getElementsByClassName("leftDiv")[0];
            this.leftDiv(leftDiv);
            imgDiv = d.getElementsByClassName("imgDiv")[0];
            this.imgDiv(imgDiv);
            footerDiv = d.getElementsByClassName("footerDiv")[0];
            this.footerDiv(footerDiv);
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
            d.getElementsByClassName("sizeBig")[i].style.fontSize = parseInt(w) * 0.038 + "px";
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
                    infoDetail: function (infoDetail) {
                    for (var i = 0; i < d.getElementsByClassName("infoDetail").length; i++) {
                    var infoDetail = d.getElementsByClassName("infoDetail")[i];
                    infoDetail.style.height = getSize(100) + "px";
                    }

                    },
                    imgDiv: function (imgDiv) {
                    for (var i = 0; i < d.getElementsByClassName("imgDiv").length; i++) {
                    var imgDiv = d.getElementsByClassName("imgDiv")[i];
                    imgDiv.style.height = getSize(210) + "px";
                    //imgDiv.style.top = wzContent.offsetHeight + "px";
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
            
           /* $("#sessLoad").click(function(){
               window.location.reload();
            });*/
            var nums = 1;
            $(".messageDiv").click(function () {
            if (nums == 1)
            {
            $(".footerDiv").css("display", "block");
            nums = 2;
            } else {
            $(".footerDiv").css("display", "none");
            nums = 1;
            }
            });
            $("#sessLoad").click(function () {
                var data = {};
                $(data).attr('assed_id', $("#assed_id").val());
                $(data).attr('content', $("#send_eval_content").val());
                $(data).attr('from_memberid', $("#from_member_id_hidden").val());
            $.post('/Carlife/add_eval.html', data, function (F) {
            var F = eval(F);
                if (F.status == 0) {
                    alert(F.info);
                }else{
                    var befor_num = $('.messagenum').text();
                    var after_num = parseInt(befor_num) + 1;
                    $(".messagenum").html(after_num);
                    $(".commentInfo").append(F.data);
                    $("#send_eval_content").val("")
                    alert(F.info);
                    window.location.reload();
                }
            }, 'json');
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
            /*$(".pointClick").click(function(){
             alert('aaa');
             var data = {};
             $(data).attr('assed_id', $("#assed_id").val());
             $.post('/Carlife/goodpoint.html', data, function (F) {
             var F = eval(F);
             if (F.status == 0) {
             } else {
             $('.lovesImg').attr('src','_STATIC_/2015/Carlife/image/lovesBlue.png');
             var befor_num = $('#point_num').text();
             var after_num = 1 + parseInt(befor_num);
             $("#point_num").html(after_num);
             $(".pointClick").attr('class','lovesImg nopointClick');
             $(".pointClick").remove('class','pointClick');
             }
             }, 'json');
             });*/
            function delteWz(id){
                var r=confirm("是否确认删除！");
                if(r==true){
                    var data = {};
                    $(data).attr('eval_id', id);
                    $.post('/Carlife/del_eval.html', data, function (F) {
                        var F = eval(F);
                        if (F.status == 0) {
                        } else {
                            var detail_id = 'detail' + id;
                            $("." + detail_id).remove();
                            var befor_num = $('.messagenum').text();
                            var after_num = parseInt(befor_num) - 1;
                            $(".messagenum").html(after_num);
                        }
                        alert(F.info);
                    }, 'json');
                }else{
                    return false;
                }
            }
            $("#delete_assed").click(function(){
                var r=confirm("是否确认删除！");
                if(r==true){
                    var data = {};
                    $(data).attr('assed_id', $("#assed_id").val());
                    $.post('/Carlife/delete_assed.html', data, function (F) {
                        var F = eval(F);
                        if (F.status == 0) {
                        } else {
                            window.location = "/Carlife/index";
                        }
                        alert(F.info);
                    }, 'json');
                }else{
                    return false;
                }
            });
            $('.clickDiv').on("click", ".lovesImg", function(){
                var A = $(this).attr("id");
                var B = A.split("like");
                var messageID = B[1];
                var C = parseInt($("#likeCount" + messageID).html());
                $(this).css("background-position", "")
                        var D = $(this).attr("rel");
                if (D === 'like')
                { 
                var data = {};
                $(data).attr('assed_id', $("#assed_id").val());
                $.post('/Carlife/goodpoint.html', data, function (F) {
                var F = eval(F);
                if (F.status == 0) {
                } else {
                $('.lovesImg').attr('src', '_STATIC_/2015/Carlife/image/lovesBlue.png');
                var befor_num = $('#point_num').text();
                var after_num = 1 + parseInt(befor_num);
                $("#point_num").html(after_num);
                $(".lovesImg").addClass("heartAnimation").attr("rel", "unlike");
                }
                alert(F.info);
                }, 'json');
                }
                else
                {
                var data = {};
                $(data).attr('assed_id', $("#assed_id").val());
                $.post('/Carlife/canclepoint.html', data, function (F) {
                var F = eval(F);
                if (F.status == 0) {
                } else {
                $('.lovesImg').attr('src', '_STATIC_/2015/Carlife/image/loves.png');
                var befor_num = $('#point_num').text();
                var after_num = parseInt(befor_num) - 1;
                $("#point_num").html(after_num);
                $(".lovesImg").removeClass("heartAnimation").attr("rel", "like");
                }
                alert(F.info);
                }, 'json');
                }
            });
            function reanswer(from_memberid){
                if (nums == 1)
                {
                $(".footerDiv").css("display", "block");
                nums = 2;
                } else {
                $(".footerDiv").css("display", "none");
                nums = 1;
                }
                $("#from_member_id_hidden").val(from_memberid);
            }
          //  window.location.reload();
        </script>
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
            $(".sizeBig").css("font-size", parseInt(w * 0.035) + "px");
            for (var i = 0; i < d.getElementsByClassName("sizeBig").length; i++) {
            d.getElementsByClassName("sizeBig")[i].style.fontSize = parseInt(w) * 0.035 + "px";
            }
            }

            function jsh(a) {
            var j = (parseInt(w) * (parseInt(a) / yw));
            return j;
            }

            });
        </script>
    </body>
</html>
{__NOLAYOUT__}