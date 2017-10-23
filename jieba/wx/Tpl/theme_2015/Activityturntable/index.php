<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/activity/css/new.css" />
 	 <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script src="_STATIC_/2015/activity/js/jQueryRotate.2.2.js" type="text/javascript" charset="utf-8"></script> 
    <script type="text/javascript">     
        $(function(){            
            if(isWeixin()) {
                $(".headers").css("display","block");
            }else{
                $(".big_div").css("padding-top","0px");
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
 
	$(function (){
		var rotateTimeOut = function (){
			$('#bg_zp').rotate({
				angle:0,
				animateTo:2170,
				duration:8000,
				callback:function (){
					alert('网络超时，请检查您的网络设置！');
				}
			});
		};
		var bRotate = false;

		var rotateFn = function (awards, angles, txt,obj){
			bRotate = !bRotate;
			$('#bg_zp').stopRotate();
			$('#bg_zp').rotate({
				angle:0,
				animateTo:angles+1800,
				duration:8000,
				callback:function (){					
					// alert(obj.prizeName);
					$(".hbgDiv").css("display","block");
					$(".tka_bg").css("display","block");
					$("#turnNotice").html(obj.prizeName);
					$(".ico_zz").html(obj.centerName);				
					bRotate = !bRotate;
				}
			})			
		};
		$(".btn_tk").click(function(){
			$(".hbgDiv").css("display","none");
			$(".tka_bg").css("display","none");
		})

		$('.ico_zz').click(function (){
			if(bRotate)return;
			//var item = rnd(0,7);
			$.ajax({  
                url: '/Activityturntable/beginTurn' ,  
                type: 'POST',  
                data: {"begin":1},  
                dataType: "json",
                //async: true,  
                success: function (F) {
                    if(F.status){
                    	item = F.data.prize;
                    	switch (item) {
		    				case 1:					
		    					rotateFn(0, 360, '提额10%',F.data);
		    					break;
		    				case 8:					
		    					rotateFn(1, 45, '现有积分翻倍',F.data);
		    					break;
		    				case 4:					
		    					rotateFn(2, 90, '提额50%',F.data);
		    					break;
		    				case 7:					
		    					rotateFn(3, 135, '免息特权',F.data);
		    					break;
		    				case 2:					
		    					rotateFn(4, 180, '提额20%',F.data);
		    					break;
		    				case 6:					
		    					rotateFn(5, 225, '5积分',F.data);
		    					break;
		    				case 3:					
		    					rotateFn(6, 270, '提额30%',F.data);
		    					break;
		    				case 5:			
		    					rotateFn(7, 315, '额度翻倍',F.data);
		    					break;
		    			}
                    }else{
                    	$(".hbgDiv").css("display","block");
    					$(".tka_bg").css("display","block");
    					$("#turnNotice").html(F.info);
						//alert(F.info);						
                    }
                	
                },  
                error: function (F) {  
                    alert("换个姿势，再转一次！");
                }

          });  
		});
	});
	function rnd(n, m){
		return Math.floor(Math.random()*(m-n+1)+n)
	}
	</script>
    <title>抽奖页</title>
</head>
<body>
<header class="headers" style="display: none;">
    <a href="javascript:history.back();" class="btn_back">
        <img src="_STATIC_/2015/member/image/register/return.png">
        <font class="fl">返回</font>
    </a>
    <h1 class="cdx">活动专区</h1>      
</header>
<div class="hbgDiv"></div>
<div class="tka_bg">        
	<h2>提示</h2>
	<p id='turnNotice'></p>	
	<div class="btn_tk" id="tl_close">确认</div>        
</div>
<div class="big_div">
	<img src="_STATIC_/2015/activity/image/turntable/bg_01.jpg" class="bg_01">
		<div class="zp_div">
			<div class="bg_zp">
				<div class="zp_con">
					<img src="_STATIC_/2015/activity/image/turntable/lunpan.png" class="bg_01" id="bg_zp">
					<img src="_STATIC_/2015/activity/image/turntable/zhizhen.png" class="ico_zz" id="ico_zz">
					<div class="ico_zz" id="btn_cj">
						<!-- 免费抽奖<br/>
						<font>每天2次</font> -->
						<!-- 5积分抽奖<br/>
						上限2次 -->
						{$centerName}
					</div>
				</div>
			</div>
		</div>
	<img src="_STATIC_/2015/activity/image/turntable/bg_02.jpg" class="bg_01">
	<img src="_STATIC_/2015/activity/image/turntable/bg_03.jpg" class="bg_01">
</div>   
</body>
</html>
{__NOLAYOUT__}