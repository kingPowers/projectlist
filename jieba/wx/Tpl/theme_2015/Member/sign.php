<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/sign.css" type="text/css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/sign.js" /></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/tankuang.js" /></script>
        <style type="text/css">
            /*iPhone 6 plus及以上*/
            @media (min-width: 414px) {
               .MonthDiv{                    
                    font-size: 24px;            
                }               
            }
             
            /* iPhone 6*/
            @media (max-width: 375px) {
                .MonthDiv{                    
                    font-size: 22px;            
                }               
            }
             
            /* iPhone 5及以下*/
            @media (max-width: 320px) { 
                .MonthDiv{                   
                    font-size: 18px;            
                }               
            }
            .tkLayBg{background:url("_STATIC_/2015/member/image/sign/tklaybg.png") repeat-y; width: 100%; position: fixed; left: 0px; top: 0px; z-index: 299;}
            .tkdiv_bg{width:70%; background:#fff; position:fixed; z-index:20; left:50%; margin:50% -35% 0; border-radius:8px; font-size: 14px;}
            #tl_close{width:100%; border-top:1px solid #ccc; display:block; padding:20px 0; color: #46abef; letter-spacing: 4px; font-weight: bold;}
            .tk_con{width:50%; padding: 10%; color: #000; margin: 0 auto; }
            .tk_con p{line-height: 30px; font-weight: bold;}
        </style>
</head>
<body style="background:#23ac80; overflow: hidden;">
    <div class="headers" style="display: none;">
        <div class="rd">
            <a href="/member/account" style="color:white;text-decoration:none;">
                <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz">返回</span>
            </a>
            <span class="zxwx">我要签到</span>
        </div>
    </div>
    <section class="tkLayBg" style="display:none;">
        <section class="tkdiv_bg">
            <section class="tk_con">
                <p>提示</p>
                <p id="qdinfo"></p>
            </section>
            <span id="tl_close">确定</span>            
        </section>
    </section>
    <div class="centerInfoDiv">
        <img src="_STATIC_/2015/member/image/sign/topImg.jpg" alt=""  class="topbgImg"/>
        <p class="MonthDiv"><span>{$data.month}</span>月签到</p>
        <img src="_STATIC_/2015/member/image/sign/bottomImg.jpg" alt=""  class="bottomImg"/>
        <img src="_STATIC_/2015/member/image/sign/headBg.jpg" alt="" class="headBgImg"/>
        <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsImg"/>
        <span class="cusNameSpan">{$data.username}</span>
        <img src="_STATIC_/2015/member/image/sign/lefmonethticon.png" alt="" class="lefmonethticon"/>
        <span class="signDay">签到<span>{$data.signNum}</span>天</span>
        <img src="_STATIC_/2015/member/image/sign/rightfenicon.png" alt="" class="rightfenicon"/>
        <span class="jfSapn">积分<span>{$data.signSum}</span></span>
        <!--时间轴部分-->
        <div class="timeAxisdDiv">
            <!--圆圈部分-->
            <img src="_STATIC_/2015/member/image/sign/PinkQ.jpg" alt="" class="PinkQImg"/>
            <img src="_STATIC_/2015/member/image/sign/orangeQ.jpg" alt="" class="orangeQImg"/>
            <img src="_STATIC_/2015/member/image/sign/blueQ.jpg" alt="" class="blueQImg"/>
            <img src="_STATIC_/2015/member/image/sign/greenQ.jpg" alt="" class="greenQImg"/>
            <img src="_STATIC_/2015/member/image/sign/bluesQ.jpg" alt="" class="bluesQImg"/>
            <!--圆圈部分-->

            <!--圆圈中心 小圆点 部分-->
            <img src="_STATIC_/2015/member/image/sign/pindD.jpg" alt="" class="PinkDImg <empty name='data.sign.0'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/orangeD.jpg" alt="" class="orangeDImg <empty name='data.sign.1'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/blueD.jpg" alt="" class="blueDImg <empty name='data.sign.2'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/greenD.jpg" alt="" class="greenDImg <empty name='data.sign.3'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/bluesD.jpg" alt="" class="bluesDImg <empty name='data.sign.4'>aixStatues</empty>"/>
            <!--圆圈中心 小圆点 部分  end-->

            <!--坐标轴  线-->
            <img src="_STATIC_/2015/member/image/sign/headsLine.png" alt="" class="headsLine"/>
            <img src="_STATIC_/2015/member/image/sign/lines.png" alt=""  class="linesImg"/>
            <img src="_STATIC_/2015/member/image/sign/lines.png" alt=""  class="lines2Img"/>
            <img src="_STATIC_/2015/member/image/sign/lines.png" alt=""  class="lines3Img"/>
            <img src="_STATIC_/2015/member/image/sign/lines.png" alt=""  class="lines4Img"/>
            <img src="_STATIC_/2015/member/image/sign/right.png" alt="" class="rightLine"/>
            
              <!--坐标背景 部分开始-->
            <img src="_STATIC_/2015/member/image/sign/pinkB.png" alt="" class="pinkBImg  <empty name='data.sign.0'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/orangeB.png" alt="" class="orangeBImg <empty name='data.sign.1'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/blueB.png" alt="" class="blueBImg <empty name='data.sign.2'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/greenB.png" alt="" class="greenBImg <empty name='data.sign.3'>aixStatues</empty>"/>
            <img src="_STATIC_/2015/member/image/sign/bluesB.png" alt="" class="blusBImg <empty name='data.sign.4'>aixStatues</empty>"/>
            <!--坐标背景 部分结束-->
            <!--坐标轴 上 头像 部分 开始-->
            <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsb1Img  <empty name='data.sign.0'>aixStatues</empty>"/>
            <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsb2Img <empty name='data.sign.1'>aixStatues</empty>"/>
            <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsb3Img <empty name='data.sign.2'>aixStatues</empty>"/>
            <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsb4Img <empty name='data.sign.3'>aixStatues</empty>"/>
            <img src="<notempty name="data.avatar">_STATIC_/Upload/avatar/{$data.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>" alt="" class="headsb5Img <empty name='data.sign.4'>aixStatues</empty>"/>
            <!--坐标轴 上 头像 部分 结束-->
          
            <!--坐标轴 上下小点点 部分 开始-->
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote1upImg <notempty name='data.sign.0'>aixStatues</notempty>"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote1downImg"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote2upImg <notempty name='data.sign.1'>aixStatues</notempty>"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote2downImg"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote3upImg <notempty name='data.sign.2'>aixStatues</notempty>"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote3downImg "/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote4upImg <notempty name='data.sign.3'>aixStatues</notempty>"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote4downImg"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote5upImg <notempty name='data.sign.4'>aixStatues</notempty>"/>
            <img src="_STATIC_/2015/member/image/sign/dote.jpg" alt="" class="dote5downImg"/>
            <!--坐标轴 上下小点点 部分 结束-->

            <!--立即领取按钮-->
            <img src="_STATIC_/2015/member/image/sign/<notempty name='data.sign.4'>receiveUp.png<else/>receive.png</notempty>" alt="" class="receiveImg receiveBtn"/>
            
            <!--天数-->
            <div class="daydiv">
                <span class="days1">01</span>
                <span class="days2">02</span>
                <span class="days3">03</span>
                <span class="days4">04</span>
                <span class="days5">05</span>
            </div>
            <!--今日签到 按钮-->
            <img src="_STATIC_/2015/member/image/sign/signicon.png" alt="" class="signiconImg signBtn"/>

        </div>
    </div>
</body>
<script type="text/javascript">
//立即领取
$('.receiveBtn').click(function(){
	var data = {};
	var img_dir = "_STATIC_/2015/member/image/sign/";
	var img_src = $(this).attr('src');
 	var img_name = img_src.substr(img_src.lastIndexOf("/")+1);
 	if(img_name!=undefined && img_name=='receive.png')return false;
 	$(data).attr('type','receive');
	$(this).attr('src',img_dir+'receive.png');
	$.ajax({
		'type':'post',
		'dataType':'json',
		'url':"/member/sign",
		"data":{'data':data},
		success:function(json){
			//alert(json.info);
            tk();
            $("#qdinfo").text(json.info);
			if(json.status==1){
				location.reload();
			}else{
				$('.surebtnImg').attr('src',img_dir+'receiveUp.png');
			}
		},
	});
	
});

//今日签到
$('.signBtn').click(function(){
	var data = {};
	var img_dir = "_STATIC_/2015/member/image/sign/";
	var img_src = $(this).attr('src');
 	var img_name = img_src.substr(img_src.lastIndexOf("/")+1);
 	if(img_name!=undefined && img_name=='signiconUp.png')return false;
 	$(data).attr('type','sign');
	$(this).attr('src',img_dir+'signiconUp.png');
	$.ajax({
		'type':'post',
		'dataType':'json',
		'url':"/member/sign",
		"data":{'data':data},
		success:function(json){
			// alert(json.info);
            tk();
            $("#qdinfo").text(json.info);
			if(json.status==1){
				location.reload();
			}else{
				$('.surebtnImg').attr('src',img_dir+'signicon.png');
			}
		},
		
	});
	
});
</script>
</html>   
{__NOLAYOUT__}