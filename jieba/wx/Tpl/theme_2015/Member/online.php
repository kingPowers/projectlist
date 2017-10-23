<html>
    <head>
        <meta charset="utf-8">
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <style>
            body,div,img{margin:0;padding:0;}
            body{background: #efefef;width:100%;height:auto;position:relative;}
            .headers{width:100%;height:6.8%;position:relative;left:0;top:0;background-color:#5495e6;z-index: 1000;}
            .headers .retrunImg{width:3.2%;height:46.6%;position:absolute;left:3.2%;}
            .headers .fhwz{width:auto;height:auto;position:absolute;left:7%;top:0%;color:white;}
            .headers .zxwx{width:auto;height:auto;position:absolute;left:41.3%;top:0%;color:white;}

            .content{width:100%;height:85%;position:relative;left:0;overflow-x:hidden;z-index:50;}
            div.speech {
                margin: 10px 0;
                padding: 8px;
                table-layout: fixed;
                word-break: break-all;
                position: relative;
                /*background: -webkit-gradient( linear, 50% 0%, 50% 100%, from(#ffffff), color-stop(0.1, #ececec), color-stop(0.5, #dbdbdb), color-stop(0.9, #dcdcdc), to(#8c8c8c) );*/
                background: #ffffff;
                border: 1px solid #ccc;
                -webkit-border-radius: 8px;
                -moz-border-radius: 8px;
                border-radius: 8px;
            }
            div.speech.right {
                margin-right:10px;
                /*                box-shadow: -2px 2px 5px #CCC;*/
                max-width: 50%;
                text-align: left;
                /*background: -webkit-gradient( linear, 50% 0%, 50% 100%, from(#e4ffa7), color-stop(0.1, #bced50), color-stop(0.4, #aed943), color-stop(0.8, #a7d143), to(#99BF40) );*/
                float: right;
            }
            div.speech.left {
                margin-left:10px;
                box-shadow: 2px 2px 2px #CCCCCC;
                max-width: 50%;
                text-align: left;
                /*background: -webkit-gradient( linear, 50% 0%, 50% 100%, from(#ffffff), color-stop(0.1, #eae8e8), color-stop(0.4, #E3E3E3), color-stop(0.8, #DFDFDF), to(#D9D9D9) );*/
                float: left;
            }
            .leftd {
                clear: both;
                float: left;
                width:100%;
                padding-left: 10px;
            }
            .rightd {
                clear: both;
                float: right;
                width:100%;
                padding-right: 10px;
            }
            .clear {
                clear: both;
            }
            .leftd .leftImg{
                width:36px;
                height:36px;
                position: relative;
                float: left;
                margin: 10px 0;
                border-radius: 50%;
            }
            .rightd .rightImg{
                width:36px;
                height:36px;
                position: relative;
                float: right;
                margin: 10px 0;
                border-radius: 50%;
            }
            .footer {
                width:100%;
                height:50px;
                background:white;   
                position: absolute;  
                margin-top:13%;
                bottom: 0;
                z-index:1000;
                float:left;
            }   
            .footer input {   
                width:80%;
                height:auto;
                outline: none;   
                text-indent: 10px;   
                position: absolute;   
                border-radius: 6px;
                left:5px;
                float:left;

            }   
            .footer span {   
                display: inline-block;
                /*            background: #ccc;   */
                font-weight: 900;    
                cursor: pointer;   
                text-align: center;   
                position: absolute;   
                /*    right: 10px;   */
                left:85%;
                border-radius: 6px;   
                float:left;
                height:auto;
            }   

            div.speech:before {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                left: 15px;
                top: -20px;
                border: 10px solid;
                border-color: transparent transparent #ccc transparent;
            }
            div.speech:after {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                left: 17px;
                top: -16px;
                border: 8px solid;
                border-color: transparent transparent #fff transparent;
            }div.speech.right:before {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                top: 9px;
                bottom: auto;
                left: auto;
                right: -10px;
                border-width: 9px 0 9px 10px;
                border-color: transparent #ccc;
            }
            div.speech.right:after {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                top: 10px;
                bottom: auto;
                left: auto;
                right: -8px;
                border-width: 8px 0 8px 9px;
                border-color: transparent #fff;
            }div.speech.left:before {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                top: 9px;
                bottom: auto;
                left: -10px;
                border-width: 9px 10px 9px 0;
                border-color: transparent #ccc;
            }
            div.speech.left:after {
                content: '';
                position: absolute;
                width: 0;
                height: 0;
                top: 10px;
                bottom: auto;
                left: -8px;
                border-width: 8px 9px 8px 0;
                border-color: transparent #fff;
            }
        </style>
    </head>

    <body>
        <div class="headers">
            <a href="/member/customer_list/from_setmobile/{$_REQUEST['from_setmobile']}" style="color:white;text-decoration:none;">
                <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz size1">返回</span></a>
            <span class="zxwx size1">在线客服</span>
        </div>
        <input type="hidden" value="{$id}" id="hiddenid">
        <input type="hidden" value="{$avatar}" id="avatar_self">
        <div style="width:100%;position:relative;height:15px;display:none;filter:alpha(opacity=50);  
             -moz-opacity:1;  
             -khtml-opacity: 1;  
             opacity:1;  " class="kongDiv"></div>
        <div class="size3" id="history_list" style="position:relative;text-align:center;color:#999999;margin-top:2%;"><img src="_STATIC_/2015/member/image/xl.png" alt="" style="position:relative;left:28%;float:left;"/>点击可以查看更多历史记录</div>

        <div class="content">

            <foreach name="list" item="vo">
                <if condition="$vo['type'] eq 0">
                    <div class="rightd">
                        <img src="{$avatar}" class="rightImg"/>
                        <div class="speech right size2" > {$vo['msg']}</div>
                    </div>
                    <else/>
                    <div class="leftd">
                        <img src="{$server_avatar}" class="leftImg"/>
                        <div class="speech left size2" > {$vo['msg']}</div>
                    </div>
                </if>
            </foreach>
        </div>
        <div class="footer">  
            <input id="text" class="size1" type="text" style="border-top:0px;border:1px solid #cdcdcd;background:white;display:block;box-shadow:0px;-webkit-tap-highlight-color:rgba(255,0,0,0);-webkit-appearance: none;">  
            <span id="btn">发送</span>
            <!--   
            <div id="user_face_icon">  
                <img src="_STATIC_/2015/member/image/account/heads.png" alt="">  
            </div> 
            -->
        </div>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>

        <script type = "text/javascript">
            $(function () {
                var ua = navigator.userAgent.toLowerCase();
                if (/iphone|ipad|ipod/.test(ua)) {
                } else {

                    $("input").focus(function () {
                        $(".headers").css("display", "none");
                    });
                    $("input").blur(function () {
                        $(".headers").css("display", "block");
                    });
                }
            })
        </script>
        <script>
            /*var obj = document.getElementById('history_list');
            obj.addEventListener('touchmove', function (event) {
                // 如果这个元素的位置内只有一个手指的话
                if (event.targetTouches.length == 1) {
                    event.preventDefault();// 阻止浏览器默认事件，重要 
                    count = $('.content').find('div').length;
                    var data = {};
                    $(data).attr('id', $('#hiddenid').val());
                    $(data).attr('count', count);
                    $.post('/Member/historyMessage.html', data, function (F) {
                        console.log(F.data);
                        if (F.status == 0) {
                        } else {
                            str = '';
                            arr = eval(F.data);
                            var icon = "_STATIC_/2015/member/image/account/ke.png";
                            for (var k in arr) {
                                if (arr[k]['type'] == 1) {
                                    str += "<div class=\"leftd\">" +
                                        "<img src=\'" + icon + "'\ class=\"leftImg\"/>" +
                                        "<div class=\"speech left size2\" >" + arr[k].msg + "</div>" +
                                        "</div>";
                                } else {
                                    str += "<div class=\"rightd\">" +
                                        "<img src=\'" + $("#avatar_self").val() + "'\ class=\"rightImg\"/>" +
                                        "<div class=\"speech right size2\" >" + arr[k].msg + "</div>" +
                                        "</div>";
                                }
                            }
                            $('.content').prepend(str);
                        }
                    }, 'json');
                }
            }, false);*/
        </script>
        <script type="text/javascript">

            var d = document;
            var w = window.innerWidth || d.documentElement.clientWidth || d.body.clientWidth;
            var h = window.innerHeight || d.documentElement.clientHeight || d.body.clientHeight;

            jsCSS = function () {
                this.fontSize();
                var headers = d.getElementsByClassName("headers")[0];
                var retrunImg = d.getElementsByClassName("retrunImg")[0];
                var fhwz = d.getElementsByClassName("fhwz")[0];
                var zxwx = d.getElementsByClassName("zxwx")[0];
                this.heiads(headers, retrunImg, fhwz, zxwx);
                var leftImg = d.getElementsByClassName("leftImg")[0];
                var rightImg = d.getElementsByClassName("rightImg")[0];
                if (leftImg && rightImg) {
                    this.content(leftImg, rightImg);
                }
                var footer = d.getElementsByClassName("footer")[0];
                var text = d.getElementById("text");
                var btn = d.getElementById("btn");
                this.footer(footer, text, btn);
            }

            jsCSS.prototype = {
                fontSize: function () {
                    for (var i = 0; i < d.getElementsByClassName("size1").length; i++) {
                        d.getElementsByClassName("size1")[i].style.fontSize = parseInt(w) * 0.040 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("size2").length; i++) {
                        d.getElementsByClassName("size2")[i].style.fontSize = parseInt(w) * 0.045 + "px";
                    }
                    for (var i = 0; i < d.getElementsByClassName("size3").length; i++) {
                        d.getElementsByClassName("size3")[i].style.fontSize = parseInt(w) * 0.032 + "px";
                    }
                },
                heiads: function (headers, retrunImg, fhwz, zxwx) {
                    retrunImg.style.top = (headers.offsetHeight - retrunImg.offsetHeight) / 2;
                    fhwz.style.lineHeight = retrunImg.offsetHeight + "px";
                    zxwx.style.lineHeight = retrunImg.offsetHeight + "px";
                    fhwz.style.top = (headers.offsetHeight - retrunImg.offsetHeight) / 2;
                    zxwx.style.top = (headers.offsetHeight - retrunImg.offsetHeight) / 2;
                },
                content: function (leftImg, rightImg) {
                    leftImg.style.width = parseInt(w) * 0.10 + "px";
                    leftImg.style.height = parseInt(w) * 0.10 + "px";
                    rightImg.style.width = parseInt(w) * 0.10 + "px";
                    rightImg.style.height = parseInt(w) * 0.10 + "px";
                },
                footer: function (footer, text, btn) {
                    text.style.height = footer.offsetHeight * 0.9 + "PX";
                    text.style.lineHeight = footer.offsetHeight * 0.9 + "PX";
                    text.style.top = (parseInt(footer.offsetHeight - text.offsetHeight) / 2) + "px";

                    btn.style.height = footer.offsetHeight * 0.9 + "PX";
                    btn.style.lineHeight = footer.offsetHeight * 0.9 + "PX";
                    btn.style.top = (parseInt(footer.offsetHeight - btn.offsetHeight) / 2) + "px";
                }
            }

            new jsCSS();

            window.onload = function () {
                var arrIcon = ['_STATIC_/2015/member/image/account/heads.png', '_STATIC_/2015/member/image/account/heads.png'];
                var num = 0;     //控制头像改变   
                var iNow = -1;    //用来累加改变左右浮动  
                var btn = document.getElementById('btn');
                var text = document.getElementById('text');
                var content = document.getElementsByClassName('content')[0];

                btn.onclick = function () {
                    if (text.value == '') {
                        alert('不能发送空消息');
                    } else {
                        content.innerHTML += "<div class=\"rightd\">" +
                                "<img src=\'" + $("#avatar_self").val() + "'\ class=\"rightImg\"/>" +
                                "<div class=\"speech right size2\" >" + text.value + "</div>" +
                                "</div>";
                        //text.value = '';
                        // 内容过多时,将滚动条放置到最底端   
                        //contentcontent.scrollTop = content.scrollHeight;
                        var data = {};
                        var cont = text.value;
                        $(data).attr('content', cont);
                        $(data).attr('id', $('#hiddenid').val());
                        $.post('/Member/writeMessage.html', data, function (F) {
                            if (F.status == 0) {
                                return top.jdbox.alert(F.status, F.info);
                            } else {
                            }

                        }, 'json');
                        $("#text").val("");
                    }
                }
            }
            function getData() {
                var content = document.getElementsByClassName('content')[0];
                var data = {};
                $(data).attr('id', $('#hiddenid').val());
                $.post('/Member/getMessageJson.html', data, function (F) {
                    if (F.status == 0) {
                    } else {
                        var str = '';
                        arr = eval(F.data);
                        var icon = "_STATIC_/2015/member/image/account/ke.png";
                        for (var k in arr) {
                            console.log('for', arr[k].msg);
                            //str += "<tr><td></td><td>"+arr[k].msg+"</td></tr>";
                            content.innerHTML += "<div class=\"leftd\">" +
                                    "<img src=\'" + icon + "'\ class=\"leftImg\"/>" +
                                    "<div class=\"speech left size2\" >" + arr[k].msg + "</div>" +
                                    "</div>"
                        }
                    }
                }, 'json');
            }
            $(function () {
                window.setInterval(getData, 5000);
            });
            $("#history_list").click(function () {
                count = $('.content').find('div').length;
                var data = {};
                $(data).attr('id', $('#hiddenid').val());
                $(data).attr('count', count);
                $.post('/Member/historyMessage.html', data, function (F) {
                    console.log(F.data);
                    if (F.status == 0) {
                    } else {
                        str = '';
                        arr = eval(F.data);
                        var icon = "_STATIC_/2015/member/image/account/ke.png";
                        for (var k in arr) {
                            if (arr[k]['type'] == 1) {
                                str += "<div class=\"leftd\">" +
                                    "<img src=\'" + icon + "'\ class=\"leftImg\"/>" +
                                    "<div class=\"speech left size2\" >" + arr[k].msg + "</div>" +
                                    "</div>";
                            } else {
                                str += "<div class=\"rightd\">" +
                                    "<img src=\'" + $("#avatar_self").val() + "'\ class=\"rightImg\"/>" +
                                    "<div class=\"speech right size2\" >" + arr[k].msg + "</div>" +
                                    "</div>";
                            }
                        }
                        $('.content').prepend(str);
                    }
                }, 'json');
            });
       	</script>  
    </body>
</html>
{__NOLAYOUT__}