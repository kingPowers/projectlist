<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="_STATIC_/2015/favicon.ico" rel="shortcut icon" type="image/x-icon"/>
    <meta name="baidu-site-verification" content="uz6erIygdi" />
    <meta name="360-site-verification" content="9cedb3175aaa0271d72ae048045eacba" />
    <meta name="keywords" content="<?php echo ($pageseo["keywords"]); ?>">
    <meta name="description" content="<?php echo ($pageseo["description"]); ?>">
    <title><?php echo ($pageseo["title"]); ?></title>
    <script type="text/javascript">var WWW = "_WWW_", STATIC = "_STATIC_/2015/", APP = "_APP_";</script>
    <link rel="stylesheet" href="_STATIC_/2015/css/reCSS.css">
    <link rel="stylesheet" href="_STATIC_/2015/css/public.css">
    <script src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/box/wbox.css" />
    <script src="_STATIC_/2015/js/jquery.box.js" type="text/javascript" charset="utf-8"></script>
    <script src="_STATIC_/2015/js/public.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "//hm.baidu.com/hm.js?980a2d3e99e048a8622eb266b479c3d9";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <style type="text/css" >
        .str{
         height:27px;
         width:75px;
         margin-left:20px;
    background: #474646;
        }
        .sdelete{
         position:absolute;
         font-size:12px;
/*         color:#999;*/
         width:10px;
         font-weight:900;
         padding:0px;
         border:none;
         cursor:pointer;
        }
        .surl{
         display:block;
         height:22px;
         line-height:22px;
         overflow:hidden;
         color:white;
         text-align: center;
        }
    </style>
    <script type="text/javascript" >
        function omover() {
//           var str = document.getElementById("mod_user");
//            str.style.background = "#d88f40";
             var listLink = document.getElementById("listLink");
            listLink.style.color = "gold";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "block";
        }
        function omout() {
            var listLink = document.getElementById("listLink");
            listLink.style.color = "gold";
            var mod_user = document.getElementById("mod_user");
            mod_user.style.background = "none";
            var divlist = document.getElementById("lishistory");
            divlist.style.display = "none";
        }
         
    </script>
</head>
<body>
<!--header-->
<div class="header">
    <div class="header_t">
        <div class="center">
            <ul class="head_t_l">
                <li class="head_t_tel">欢迎来到上海贷后管家服务有限公司</li>               
            </ul>
         
            <div class="head_t_r">
                <?php if(empty($_SESSION['member'])): ?><span class="t_login"><a href="/member/login">登录</a></span>
                      <span class="t_register"><a href="/member/register">注册</a></span>                        
                      <span class="t_info"><a href="/member/login">消息<font>(100)</font></a></span>
                <?php else: ?>
                <div class="myzh">
                        <div class="mod_user" id="mod_user" onmouseover ="omover();" onmouseout ="omout();" style="height:25px;text-align: center;float:left;line-height:15px;width:100px;margin-left:50px;z-index:100;position: relative; font-weight:normal; font-style:normal; font-size:13px;">
                            <p > <a class="list_hist" id="listLink" style="color:gold;margin-left:20px;top:5px;" href="/member/account">我的账户</a></p>
                            <p style="height:10px;"></p>
                            <div id="lishistory" class="user_operate_list" style="display: none">
                                 
                            </div>
                        </div>
                        <span class="t_register"><a href="/member/account"><?php echo ($_SESSION['member']['username']); ?></a></span>
                        <span class="t_login"><a href="javascript:void(0)" class="ui-account-reg logout">退出</a></span>
                </div><?php endif; ?>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div class="header_b">
        
        <div class="center">
            <div class="head_b_l">
                <a href="/" class="a c_left"></a>                
            </div>           
            <ul class="head_b_r">
                <li><a href="/" <?php $module = array('order');$action=array('neworderlist');if((strtolower(MODULE_NAME)=='index')||(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action))) echo 'class="curr"';?> >首页</a></li>
                <li><a href="/company/index" <?php if(strtolower(MODULE_NAME)=='company') echo 'class="curr"';?>>公司介绍</a></li>
                <li><a href="/media/index" <?php if(strtolower(MODULE_NAME)=='media') echo 'class="curr"';?> >媒体公告</a></li>
                <li class="acitve"><a href="/member/account" <?php $module = array('order');$action=array('my_order_list');if((strtolower(MODULE_NAME)=='member')||(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action))) echo 'class="curr"';?> >我的账户</a></li>
            </ul>
            <div style="clear:both;"></div>
        </div>
    </div>
    <script>
        $(function(){
            var lopic = 0;
            var lolength = $(".logo_r_ul>li").length-1;
            var loheight = $(".logo_r_ul>li").eq(0).height();
            setTimeout(function(){
                lopic++;
                if(lopic>lolength){
                    $(".logo_r_ul").stop().animate({top:0});
                    lopic = 0;
                }else{
                    $(".logo_r_ul").stop().animate({top:-loheight});
                }
                var _this=arguments.callee;
                setTimeout(_this,5000);
            },5000)
        })
    </script>
</div>

<div class="ui-wp <?php echo strtolower(MODULE_NAME.'-'.ACTION_NAME);?>-wp">
    <!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<style type="text/css">
img{
    height: 100px;
    width: 100px;
}
</style>
<script type="text/javascript">
 $(function(){
     $(".addImg").click(function(){
       $formClass = $(this).parent("form").attr("class");
       var formData = new FormData($("."+$formClass)[0]);
       $.ajax({  
          url: '/text/uploadImg' ,  
          type: 'POST',  
          data: formData,  
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (F) {
             console.log(F);
             //alert(typeof(F))
             var F = eval("("+F+")") ;
             alert(F.info);
             if(F.status == 1)
             {
                location.reload();
             }
             
          }  
        }); 
     })
     $(".addImgInput").click(function(){
        $name = $(this).prev("#fielArea").children("input").attr("name");
        $str = "<input type='file' multiple name='" + $name + "' class='as'>";
        //alert($(this).prev("input[type='file']").attr('name'));
        $(this).prev("#fielArea").append($str);
     })
    $(".sub").click(function(){
      //alert(1);
      var formData = new FormData($(".text")[0]);
      console.log(formData);
      $.ajax({  
          url: '/text/editOrder' ,  
          type: 'POST',  
          data: formData,  
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (F) {
             console.log(F);
             //alert(typeof(F))
            var F = eval("("+F+")") ;
             alert(F.info);  
          }  
        }); 
   })
})     
    function deleteImg (_this){
        $file_name = $(_this).attr("name");
        $file_type = $(_this).attr("type");
        $order_id = <?php echo ($order_id); ?>;
        var data = {};
        $(data).attr("file_name",$file_name);
        $(data).attr("file_type",$file_type);
        $(data).attr("order_id",$order_id);
        console.log(data);
        $.post("/text/deleteImg.html",data,function(F){
            console.log(F);
             //alert(typeof(F))
             //var F = eval("("+F+")") ;
             alert(F.info);
             if(F.status == 1)
             {
                location.reload();
             }
        },'json')

     }
 

</script>
<body>
<div style="width: 500px;float: left;background: #fff;">
<form class="user_pic">
<p>客户照片</p>
<?php if(is_array($orderInfo['pic_url']['user_pic'])): foreach($orderInfo['pic_url']['user_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="user_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="user_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="certi_pic">
<p>身份正面证照片</p>
<?php if(is_array($orderInfo['pic_url']['certi_pic'])): foreach($orderInfo['pic_url']['certi_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="certi_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="certi_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
身份证反面照
<form class="certiBack_pic">
<p>身份证照片</p>
<?php if(is_array($orderInfo['pic_url']['certiBack_pic'])): foreach($orderInfo['pic_url']['certiBack_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="certiBack_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="certiBack_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="loanContract_pic">
<p>贷款合同</p>
<?php if(is_array($orderInfo['pic_url']['loanContract_pic'])): foreach($orderInfo['pic_url']['loanContract_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="loanContract_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="loanContract_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="mortContract_pic">
<p>抵押合同</p>
<?php if(is_array($orderInfo['pic_url']['mortContract_pic'])): foreach($orderInfo['pic_url']['mortContract_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="mortContract_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="mortContract_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="travelLicense_pic">
<p>行驶证</p>
<?php if(is_array($orderInfo['pic_url']['travelLicense_pic'])): foreach($orderInfo['pic_url']['travelLicense_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="travelLicense_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="travelLicense_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="driveLicense_pic">
<p>驾驶证</p>
<?php if(is_array($orderInfo['pic_url']['driveLicense_pic'])): foreach($orderInfo['pic_url']['driveLicense_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="driveLicense_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="driveLicense_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
<form class="carRegistration_pic">
<p>车辆登记证</p>
<?php if(is_array($orderInfo['pic_url']['carRegistration_pic'])): foreach($orderInfo['pic_url']['carRegistration_pic'] as $key=>$vo): ?><img src="<?php echo ($vo); ?>"><a onclick="deleteImg(this)" class="deleteImg" type="carRegistration_pic" name="<?php echo array_pop(explode("/",$vo))?>">删除</a><?php endforeach; endif; ?>
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<section id="fielArea">
<input type="file" multiple="" name="carRegistration_pic[]"></section>
<a href="javascript:void(0)" class="addImgInput">加图片</a>
<a href="javascript:void(0)" class="addImg">提交</a>
</form>
</div>
<div style="width: 500px;height: 500px;float: left;background: #fff;">
<form class="text">
<input type="hidden" name="order_id" value="<?php echo ($order_id); ?>">
<p>姓名：<input type="text" name="names" value="<?php echo ($orderInfo['names']); ?>"></p>
<p>性别：<label><input type="radio" name="sex" value="0" checked="">男</label><label><input type="radio" name="sex" value="1">女</label></p>
<p>身份证：<input type="text" name="certiNumber" value="<?php echo ($orderInfo['certiNumber']); ?>"></p>
<p>贷款城市：<input type="text" name="loan_city" value="<?php echo ($orderInfo['loan_city']); ?>"></p>
<p>贷款公司：<input type="text" name="loan_company" value="<?php echo ($orderInfo['loan_company']); ?>"></p>
<p>贷款金额：<input type="text" name="loan_money" value="<?php echo ($orderInfo['loan_money']); ?>"></p>
<p>抵押公司：<input type="text" name="mort_company" value="<?php echo ($orderInfo['mort_company']); ?>"></p>
<p>已还期数：<input type="text" name="return_num" value="<?php echo ($orderInfo['return_num']); ?>"></p>
<p>车辆型号：<input type="text" name="car_brand" value="<?php echo ($orderInfo['car_brand']); ?>"></p>
<p>车牌号码：<input type="text" name="plate_num" value="<?php echo ($orderInfo['plate_num']); ?>"></p>
<p>车架号码：<input type="text" name="frame_num" value="<?php echo ($orderInfo['frame_num']); ?>"></p>
<p>GPS账号：<input type="text" name="GPS_member" value="<?php echo ($orderInfo['GPS_member']); ?>"></p>
<p>GPS密码：<input type="text" name="GPS_password" value="<?php echo ($orderInfo['GPS_password']); ?>"></p>
<p>GPS地址：<input type="text" name="GPS_url" value="<?php echo ($orderInfo['GPS_url']); ?>"></p>
<p>拖车价格：<input type="text" name="trail_price" value="<?php echo ($orderInfo['trail_price']); ?>"></p>
<a href="javascript:void(0)" class="sub">提交</a>
</form>
</div>
</body>
<script type="text/javascript">
</script>
</html>
    <div class="ui-clearfix"></div>
</div>
<div class="footer">
    <div class="center">
        <div class="footer_t">
            <ul class="c_fot_ul c_left">
                <li>
                    <h3>最新订单</h3> 
                    <h3>公司介绍</h3>                    
                </li>
                <li>
                    <h3>历史订单</h3> 
                    <h3>媒体公告</h3>                    
                </li> 
            </ul>
            <div class="fot_t_r c_right">                
                <div class="fot_t_r_r c_right">
                    <div class="fot_t_tel">                       
                        <div class="fot_t_ra">
                            <div>工作日9 00-18:00</div>
                            <div class="fot_t_ra_ff">节假日9:00-16:00</div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <div class="footer_m">
        <div class="center">
            <p><span class="c_left" style="margin-left:-60px;">友情链接:</span>
                <a href="http://www.cnfol.com/" target="_blank">中金在线</a>
                <a href="http://www.huagu.com/" target="_blank">华股财经</a>
                <a href="http://www.afinance.cn/" target="_blank">第一金融网</a>
                <a href="http://www.beelink.com/" target="_blank">百灵网</a>
                <a href="javascript:;" target="_blank">广州万隆网贷投资理财</a>
                <a href="javascript:;" target="_blank">保险财经</a>
                <a href="http://www.91jucai.com/" target="_blank">聚财网</a>
                <a href="http://www.southfi.com/index.html" target="_blank">南方财经网</a>
                <a href="http://www.p2peye.com/" target="_blank">网贷天眼</a>
                <a href="http://www.hifly.tv/" target="_blank">金鹰网</a>
                <a href="http://www.wdtianhe.com/" target="_blank">网贷天和</a>
                <a href="http://www.wangdaiclub.com/" target="_blank">网贷俱乐部</a>
                <a href="http://www.01p2p.net/" target="_blank">第一网贷</a>
                <a href="http://www.wangdaijiamen.com/" target="_blank">网贷家门</a>               
            </p>
        </div>
    </div>
    <div class="footer_b">
        <div class="center">
             <p class="footer_b_p"  style="margin:0;"><span>上海峡古科技有限公司|</span>Copyright © changsheng360.com<a href="http://www.miitbeian.gov.cn" target="_blank" style="color:#fff;">沪ICP备16049012号-1</a></p>
        </div>
        
    </div>
</div>
<script>
//   $('.calcIn').click(function(){
//      $(".syCalc").css("display","block");
//   });
//   $('.calcIn').mouseout(function(){
//      $(".syCalc").css("display","none");
//   });
//   
    $(function(){
        $('.c_plen').each(function(index){
            var speW = $(this).siblings('.num').text();
            $(this).children('.speed').width(speW);
        })

        $('.img_min_list li').on('click',function(){
            var self = $(this),
                idx = self.index(),
                src = self.data('linksrc'),
                bigimg = self.data('bigimg');
            self.addClass('current').siblings().removeClass('current');
            $('.news_b .img_holder').attr('href',src);
            $('.news_b .img_holder img').attr('src',bigimg);
            $('.news_b .ir dl').eq(idx).show().siblings().hide();
        });

        $('a.logout').click(function() {
            jdbox.alert(2, '退出中，请等候...');
            $.post('/member/loginOut.html',null, function(result) {
                if(result.data){
                    var jsobj = $(result.data),mvl = document.createElement('script');
                    mvl.type = 'text/javascript';
                    mvl.src = jsobj.attr('src');
                    var oHead = document.getElementsByTagName('HEAD').item(0);
                    oHead.appendChild(mvl);
                }
                setTimeout(function(){ referpage(); },1000);
            },'json');
        });
        $('.scroll-calculator').click(function(){
            return jdbox.iframe('/public/calculator.html');
        })
        
        var tiptext;
        $("#leftsead a").hover(function(){
            tiptext = $(this).attr('data-text');
            $(this).html(tiptext);
        },function(){
            $(this).html('');
        })
        
        $(".scrollT").click(function(){
            $("body,html").animate({scrollTop:0})
        })


    })
</script>



</body>
</html>