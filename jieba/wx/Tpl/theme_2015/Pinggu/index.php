<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>爱车估价</title>
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/pinggu/css/pikaday.css"/>
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/pinggu/css/animate.css"/>
        <link rel="stylesheet" type="text/css" href="_STATIC_/2015/pinggu/css/index.css"/>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/pinggu/js/pikaday.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/pinggu/js/styleCalculation.js"></script>
        <style type="text/css">
            .select{width:90%;height:auto;position:relative;left:0%;border:none; margin-top: 40px; height: 40px; line-height: 40px;}
        </style>
    </head>
    
    <body bgcolor="#efefef">
        <div class="headers outer" style="display: none;">
            <a href="/index/index" class="changehref" style="color:white;text-decoration:none;">
                <img src="_STATIC_/2015/pinggu/image/return.png" class="retrunImg"/>
                <span class="fhwz sizeOne">返回</span>
                <span class="zxwx sizeOne">爱车估价</span>
            </a>
        </div>
        <div class="noneDiv outer"></div>
        <form action="/Pinggu/detail" name="param" method='post' id="param">
            <div class="datepickerDiv" style="position:absolute;z-index:700;display:none;top:0;left:0;" onclick=""></div>
            <div class="part1 outer">
                <div class="spTime sp">
                    <div class="bt sizeTwo">上牌时间</div>
                    <input type="text" name="datepicker" id="datepicker" class="btTime ipt sizeThree" readonly placeholder="请选择上牌时间" />
                </div>
                <script type="text/javascript">
                    var picker = new Pikaday({
                        field: document.getElementById('datepicker'),
                        firstDay: 1,
                        minDate: new Date('1990-01-01'),
                        maxDate: new Date('2016-12-31'),
                        yearRange: [1990,2016]
                    });
                </script>
                <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
                <div class="spModels sp">
                    <div class="bt sizeTwo">选择车型</div>
                    <div class="btModels ipt sizeThree" readonly onclick="part2Css()">请选择品牌&车型</div>
                    <img src="_STATIC_/2015/pinggu/image/righticon.png" class="righticonImg"/>
                </div>
                <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
                <div class="spCity sp">
                    <div class="bt sizeTwo">查询城市</div>
                    <div class="btCity ipt sizeThree" readonly onclick="part3Css()">请选择城市</div>
                    <img src="_STATIC_/2015/pinggu/image/righticon.png" class="righticonImg">
                </div>
                <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
                <div class="spKm sp">
                    <div class="bt sizeTwo">表里里程</div>
                    <input type="tel" name="run_km"  class="btKms ipts sizeThree" placeholder="请输入里程数" maxlength="6"/>
                    <div class="jw sizeTwo">万公里</div>
                </div>
            </div>
        <input class="select sizeOne outer" id="find_commit" type="submit" value="" style="background:url(_STATIC_/2015/pinggu/image/ljcx.png);background-size:100%;background-repeat: no-repeat;"/>
        </form>
        <!--<a href="/pinggu/detail" style="text-decoration:none;"><div class="select sizeOne outer" id="find_commit">立即查询</div></a>-->
        <script type="text/javascript" src="_STATIC_/2015/pinggu/js/index.js"></script>
        <!-- part2 车系明细-->
        <div class="part2 leftMove" id="part2" style="z-index:1;">
            <!-- 数据加载结构样式 -->
            <?php $j=0;?>
            <foreach name="list" item="vo" key="key">
                <div class="modelsDiv">
                    <div style="height:1px" id="qC{$key}"></div>
                    <div class="letter sizeTwo">{$key}</div>
                    <foreach name="vo" item="v" key="k">
                        <div class="letterValue sizeTwo" onclick="show3Level(<?php echo $j; ?>,{$v['first_id']})">{$v['first_name']}</div>
                        <?php $j = $j+1; ?>
                    </foreach>
                </div>
            </foreach>
        </div>
        <!-- 模拟数据加载 -->
        <script type="text/javascript">
        </script>
        <div class="part2_2 leftMove" style="z-index:3;">
        </div>
        <div class="part2_3 leftMove" style="z-index:4;">
        </div>
        <script type="text/javascript" src="_STATIC_/2015/pinggu/js/part2.js"></script>
        <!-- part3 城市明细-->
    <div class="part3 leftMove" id="part3" style="z-index:1;">
        <div class="cityDiv">
            <?php $i=0;?>
            <foreach name="provinces" item="province" key="key">
                <div id="cT{$key}"></div>
                <div class="letterCt sizeTwo">{$key}</div>
                <foreach name="province" item="pro" key="k">
                    <div class="letterCtValue sizeTwo" onclick="showCt3Level(<?php echo $i;?>,{$pro['province_code']})">{$pro['province_name']}</div>
                    <?php $i = $i+1; ?>
                </foreach>
            </foreach>
        </div>
    </div>
    <div class="part3_2 leftMove" style="z-index:3;">
    </div>
    <script type="text/javascript" src="_STATIC_/2015/pinggu/js/part3.js">
    </script>
    <!-- 数据加载结构样式 -->
    <div class="part4" style="z-index:2;">
        <!-- <div class="part4_1"><a>A</a></div> -->
    </div>
    <script type="text/javascript" src="_STATIC_/2015/pinggu/js/part4.js"></script>
    </body>
</html>
<!-- 模拟数据加载 -->

<script>

    $("#find_commit").click(function(){
        var datepicker = $("#datepicker").val();
        if(datepicker == ''){
            alert("请输入准确的日期！");
            return false;
        }
        var car_id = $(".btModels").text();
        if(car_id =='请选择品牌&车型'){
            alert("请输入准确的车型！");
            return false;
        }
        var city_id = $(".btCity").text();
        if(city_id =='请选择城市'){
            alert("请输入准确的城市！");
            return false;
        }
        var input = $(".btKms").val();
        if(!/^\d+(?:\.\d{1,2})?$/.test(input)){
            alert("请输入一个数字，最多只能有两位小数！");
            return false;
        }
        if(input>100){
            alert("请输入准确的公里数！");
            return false;
        }


    });
</script>
{__NOLAYOUT__}