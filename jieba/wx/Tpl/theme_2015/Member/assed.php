<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/assed.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/assed.js"></script>
        <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    </head>
<body>
    <div class="maxDiv">
        <!--顶部 开始-->
        <div class="headers">
            <div class="rd">
                <a href="/member/account" style="color:white;text-decoration:none;">
                    <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                    <span class="fhwz size3">返回</span>
                </a>
                <span class="orderwx size2">我要评价</span>
                <span class="placewz size3"></span>
                <img src="_STATIC_/2015/member/image/assed/dwlogo.png" alt="" class="dwlogoImg"/>
            </div>
        </div>
        <!--顶部 结束-->
        <!--中间白色背景区域-->
        <div class="centerDiv">
            <div class="center-topDiv">
                <if condition="$assed_type eq 1">
                    <img src="_STATIC_/2015/member/image/myorder/cars.jpg" alt="" class="bucarsImg"/>
                <elseif condition="$assed_type eq 2"/>
                    <img src="_STATIC_/2015/member/image/myorder/bucars.jpg" alt="" class="bucarsImg">
                <else/>
                    <img src="_STATIC_/2015/member/image/myorder/crcars.jpg" alt="" class="bucarsImg">
                </if>
                <span class="henum size3">
                    <if condition="$assed_type neq 3">
                        合同编号:<span>{$applyCode}</span>
                    </if>
                </span>
                <span class="moneynum size3">放款金额:<span>{$backtotalmoney}</span></span>
            </div>
            <form action="/Member/assedCommit" name="form_logo" id="form_logo" method="post" enctype="multipart/form-data">
                <input type="hidden" value="{$order_id}" name="uniqe_id">
                <input type="hidden" value="" name="map_url" id="map_url">
                <div class="textareaDiv">
                    <textarea class="textareaq size3" name="content" value=""  id="textarea" placeholder="亲！评价一下吧，您的评价对其他客户很重要哦~" onfocus="hqjd()" onblur="sqjd()"style="width:100%;height:100%;line-height:140%;border:none;color:#999999;"></textarea>
                </div>
                <div class="photoDiv">
                    <div id="thumb">
                        <img src="_STATIC_/2015/member/image/sc.jpg" alt="" id="img1"  class="imgicons"/>
                        <input type="file" name="logo[]"   onchange="preview(this,'img1')" class="fileCls"/>
                        <img src="_STATIC_/2015/member/image/sc.jpg" alt="" id="img2"   class="imgiconss"/>
                        <input type="file" name="logo[]"  onchange="preview(this,'img2')" class="fileClss"/>
                        <img src="_STATIC_/2015/member/image/sc.jpg" alt="" id="img3"  class="imgiconsss"/>
                        <input type="file" name="logo[]"  onchange="preview(this,'img3')" class="fileClsss"/>
                    </div>
                </div>
            </form>

        </div>
        <!--按钮部分-->
        <img src="_STATIC_/2015/member/image/myorder/surebtn.png" alt="" class="surebtnImg" id="submit"/>
    </div>
    <div class="hbgDiv"></div>
    <!--             弹出层内容-->
    <div class="centerinfomatinDiv">
        <img src="_STATIC_/2015/member/image/assed/pjt.png" alt="" class="tresetImg"/>
        <p class="infotwz size2">亲，本次评价还未完成,您确定要离开吗?</p>
        <div class="infoconter">
            <input type="button" value="取消" class="calbtn size2"/>
            <span class="lines"></span>
            <input type="button" value="确定"  class="surebtn size2"/>
        </div>
    </div>
    <style>
        /*.fileCls{position:relative;top:28px;left:-130px;z-index:1;filter: alpha(opacity=0);opacity: 0;float:left;}*/
        .fileCls{
            width:20%;
            height:80%;
            float:left;
            position:absolute;
            left:3%;
            top:0%;
            z-index:200;
            filter: alpha(opacity=0);opacity: 0;
            border:1px solid red;
        }
        .fileClss{
            width:20%;
            height:71px;
            float:left;
            position:absolute;
            left:25%;
            top:0%;
            z-index:200;
        filter: alpha(opacity=0);opacity: 0;
        }
        .fileClsss{
            width:20%;
            height:71px;
            float:left;
            position:absolute;
            left:47%;
            top:0%;
            z-index:200;
            filter: alpha(opacity=0);opacity: 0;
        }
        

    </style>
    <script>
        function preview(file,img_id)
        {
            if(!file.value=="")
            {
                //显示图片到页面
                if (file.files && file.files[0])
                {
                    var reader = new FileReader();
                    reader.onload = function(evt){
                        $('#'+img_id).attr('src',evt.target.result);
                    }
                }
                reader.readAsDataURL(file.files[0]);
            }
        }
        var  clickNum=1;
        $('#submit').click(function(){
            var content = $("#textarea").val();
            var value= "亲！评价一下吧，您的评价对其他客户很重要哦~";
            if(content == value){
                alert("亲！评价一下吧，您的评价对其他客户很重要哦~");
                return false;
            }
            if(clickNum==1)
            {
                var options = {
                    url: "/Member/assedCommit",
                    type: "post",
                    dataType: "json",
                    success: function(o) {
                        if (o.status == 0) {
                            alert(o.info);
                            location.reload(true);
                        } else {
                            /*arr=eval(o.data);
                            var path = "_STATIC_/Upload/avatar/"+arr.avatar;
                            $("#headsImg").attr('src',path);
                            $(".thirdDiv").hide();*/
                            alert('感谢您的这次评论！');
                            window.location.href="/Carlife/index";
                        }
                    }
                };
                clickNum=2;
                $("#form_logo").ajaxSubmit(options);
            }
        });

    </script>
    <script>
        var textareavalue = document.getElementById("textarea");
        textareavalue.value = "亲！评价一下吧，您的评价对其他客户很重要哦~";
        function hqjd() {
            if (textareavalue.value == "亲！评价一下吧，您的评价对其他客户很重要哦~")
            {
                textareavalue.value = " ";
                $("#textarea").css("color","black");
            }
        }
        function sqjd() {
            if (textareavalue.value == " ")
            {
                textareavalue.value = "亲！评价一下吧，您的评价对其他客户很重要哦~";
            }
        }
        

        $(".calbtn").click(function () {
            $(".hbgDiv").css("display", "none");
            $(".centerinfomatinDiv").css("display", "none");
        });
        $(".surebtn").click(function () {
            $(".hbgDiv").css("display", "none");
            $(".centerinfomatinDiv").css("display", "none");
        });
    </script>
    <script>
        wx.config({
            debug: false,
            appId: '{$signPackage.appId}',
            timestamp: '{$signPackage.timestamp}',
            nonceStr: '{$signPackage.nonceStr}',
            signature: '{$signPackage.signature}',
            jsApiList: [
                'checkJsApi',
                'onMenuShareTimeline',
                'onMenuShareAppMessage',
                'onMenuShareQQ',
                'onMenuShareWeibo',
                'hideMenuItems',
                'showMenuItems',
                'hideAllNonBaseMenuItem',
                'showAllNonBaseMenuItem',
                'translateVoice',
                'startRecord',
                'stopRecord',
                'onRecordEnd',
                'playVoice',
                'pauseVoice',
                'stopVoice',
                'uploadVoice',
                'downloadVoice',
                'chooseImage',
                'previewImage',
                'uploadImage',
                'downloadImage',
                'getNetworkType',
                'openLocation',
                'getLocation',
                'hideOptionMenu',
                'showOptionMenu',
                'closeWindow',
                'scanQRCode',
                'chooseWXPay',
                'openProductSpecificView',
                'addCard',
                'chooseCard',
                'openCard'
            ]
        });

        wx.error(function (res) {
            //alert(res);
        });
        var wx_location_config = function () {}
        wx_location_config.prototype.lat = '0';//维度
        wx_location_config.prototype.long = '0';//经度
        wx.ready(function () {
            wx.getLocation({
                type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                success: function (res) {
                    var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                    var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                    var speed = res.speed; // 速度，以米/每秒计
                    var accuracy = res.accuracy; // 位置精度
                    wx_location_config.prototype.lat = latitude;
                    wx_location_config.prototype.long = longitude;
                    console.log(latitude);
                    $.ajax({
                        'type': 'post',
                        'url': '/index/locationByGPS',
                        'dataType': 'json',
                        'data': {'lng': longitude, 'lat': latitude, 'is_ajax': 1},
                        success: function (json) {
                            if (json.status == 1)
                                $('.placewz').html(json.data.city);
                                $("#map_url").val(json.data.city);
                        }
                    });
                }
            });
        });
    </script>
    <eq name='isWeixin' value='0'>
    <div id="allmap" style="width:400px; height:400px; overflow:hidden; margin:0;display:none"></div>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4itF2ygdKkIfshFlQggs7DZA"></script>
    <script type="text/javascript">
        var map = new BMap.Map('allmap');              //创建Map实例
        map.centerAndZoom("上海市", 12);              //初始化地图(设置城市名和地图缩放级别)
        map.addControl(new BMap.NavigationControl());  //添加平移缩放控件
        map.addControl(new BMap.ScaleControl());       //添加比例尺控件
        map.addControl(new BMap.OverviewMapControl()); //添加缩略地图控件
        map.enableScrollWheelZoom();                   //启用滚轮放大缩小

        var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            console.log('浏览器定位的状态码为：' + this.getStatus());
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                var mk = new BMap.Marker(r.point);
                map.addOverlay(mk);
                map.panTo(r.point);
                $.ajax({
                    'type':'post',
                    'url':'/index/locationByGPS',
                    'dataType':'json',
                    'data':{'lng':r.point.lng,'lat':r.point.lat,'is_ajax':1},
                    success:function(json){
                        if(json.status==1)
                            $('.placewz').html(json.data.city);
                            $("#map_url").val(json.data.city);
                    }
                });
                //document.getElementById('lnglat').innerHTML = '1当前坐标：' + r.point.lng + ", " + r.point.lat;
            }else{
                new BMap.LocalCity().get(function(result){
                    console.log('IP定位获取当前城市：' + result.name);
                    map.setCenter(result.name);
                });
            }
        },{enableHighAccuracy:true});
    </script>
    </eq>
</body>
</html>   
{__NOLAYOUT__}