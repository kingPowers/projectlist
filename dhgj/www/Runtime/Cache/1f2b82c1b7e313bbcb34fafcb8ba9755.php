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
    <link rel="stylesheet" href="_STATIC_/2015/member/css/accountMenu.css">
    <!--content-->
    <div class="content">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/member/account" class="c_nav_now">我的账户</a></p>
        </div> 
        <div class="center user_center">
            <div class="account_l c_left">
                <div class="acc_now1">
                    <ul>
                        <li class="acc_first acc_first1"><a class="user_curr">我的账户</a></li>
                    </ul>
                </div>
                <div>                    
                    <a href="/member/account" class="choose_a <?php if((strtolower(MODULE_NAME)=='member')&&(strtolower(ACTION_NAME)=='account')) echo 'blue_line';?>">基本信息</a>
                    <a href="/member/reset" class="choose_a <?php $module = array('member');$action=array('reset','reset_success');if(in_array(strtolower(MODULE_NAME),$module)&&in_array(strtolower(ACTION_NAME),$action)) echo 'blue_line';?> ">修改密码</a>
                </div>
                <div class="acc_now1">
                    <ul>
                        <li class="acc_first acc_first2"><a class="user_curr">我的订单</a></li>
                    </ul>
                </div>
                <div>                                       
                    <a href="/order/my_order_list" class="choose_a <?php if((strtolower(MODULE_NAME)=='order')&&(strtolower(ACTION_NAME)=='my_order_list')) echo 'blue_line';?>">我的订单</a>                   
                </div>               
            </div>
            <div class="account_r c_right">
                
<link rel="stylesheet" href="_STATIC_/2015/member/css/account.css">
<script src="_STATIC_/2015/member/js/highcharts.js"></script>
<script src="_STATIC_/2015/member/js/reset.js"></script>
<style type="text/css">
.login_b_ul li{position: relative;}
.input_r{position: absolute;top: 0;left: 100%;color: #999;display: inline-block;width: 100%}
</style>
	<div class="user_info">
		 <h2>修改密码</h2>
		 <div class="login_box">
            <ul class="for_prog">
            </ul>
            <div class="login_b">
                <form action="" class="reset_form" name="12">
                    <ul class="login_b_ul">
                        <li>
                            <span><em>*</em> 手机号码：</span>
                            <input name="mobile" id="mobilenumber" autocomplete="off" type="text" class="input" disabled="" value="<?php echo ($mobile); ?>" style="background: #fff;border: 0px">
                        </li>
                        <li>
                            <span><em>*</em>手机验证码：</span>
                            <input name="smscode" type="text" autocomplete="off" placeholder="请输入手机验证码" value="" class="obtain_ipt" remind="手机验证码" needCheck="need" check_rule="fixLength:6">
                            <button type="button" class="obtain" id="smsbutton">获取验证码</button>
                            <label class="input_r"></label>
                        </li>
                        <li>
                            <span><em>*</em> 新密码：</span>
                            <input type="password" name="password" placeholder="请输入6-18位密码" class="input" value="" remind="密码" needCheck="need" check_rule="minLength:6">
                            <label class="input_r">长度大于5的字符</label>
                        </li>
                        <li>
                            <span><em>*</em> 确认密码：</span>
                            <input type="password" name="repassword" placeholder="和上面输入一致" class="input"  remind="确认密码" needCheck="need" check_rule="repassword">
                            <label class="input_r"></label>
                        </li>
                        <li class="last">
                        	<input name="_reset_mobile_" type="hidden" value="<?php echo ($_reset_mobile_); ?>"/>
                            <button type="button" class="for_next forgotpwd">重置</button>
                        </li>
                    </ul>
                </form>
            </div>
         </div>
         <div class="ts_div">
            <font>* 温馨提示：我们将严格对用户的所有资料进行保密</font>
         </div>
	</div>
<script type="text/javascript">
    //发送验证码
    var isSend = 0;
    $('.obtain').click(function () {
        if(isSend==1)return false;
        isSend = 1;
        var _this = this;
        var mobile = $("input[name='mobile']").val();
        var token = $("input[name='_reset_mobile_']").val();
        if (mobile == '' || mobile == '请输入手机号码') {
            alert("手机号码不正确");
            isSend = 0;
            return false;
        }
        if (!/^1\d{10}$/.test(mobile)) {
            alert("手机号格式错误");
            isSend = 0;
            return false;
        }
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': "/member/resetSendSms",
            "data": {'mobile': mobile, '_reset_mobile_': token},
            success: function (json) {
                jdbox.alert(json.status,json.info);
                if (json.status == 1) {
                    //倒计时
                    sendCode(_this);
                } else {
                    isSend = 0;
                }

            }

        });

    });
    
    function sendCode(thisBtn)
    {   
        var nums = 60;
        var btn = $(thisBtn);
        btn.html("重新发送(" + nums + ")");
        btn.css({"background":"#cccccc"});
        var clock = setInterval(function(){
            nums--;
            if (nums > 0) {
                btn.html("重新发送(" + nums + ")");
            } else {
                clearInterval(clock);
                btn.html('获取验证码');
                nums = 60;isSend = 0;
                btn.css({"background":"#01a6e9"});
            }
        }, 1000);
    }
$(function(){
    $('.reset_form').constract(true);
    $('.for_next').submitfrom($('.reset_form'));
})
</script>
            </div>
            <div style="clear:both;"></div>
        </div>
    </div>
    <script>
        $(function(){
            $(".account_l").height($(".account_r").height(""));
        })
    </script>
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