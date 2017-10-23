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
    <link rel="stylesheet" href="_STATIC_/2015/css/reg.css">
<div class="content">
    <div class="center reg_center"> 
        <ul class="reg_prog">            
        </ul>
        <div class="reg_b">
            <form class="ui-form">
                <input type="hidden" name="channel" class="login-reg-input" value="<?php echo ($channel); ?>" />
                <div class="reg_b_l">
                    <ul>
                        <li>
                            <div class="reg_input_l"><span>* </span>手机号码：<input type="text" value="<?php echo ($data['mobile']); ?>" class="login-reg-input" name="mobile" rel="手机号不正确" placeholder="请输入手机号码" autocomplete="off"></div>
                            <label class="reg_input_r"> 请输入正确的手机号</label>
                        </li>
                        <li>
                            <div class="reg_input_l"><span>* </span>登录密码：<input type="password"  class="login-reg-input" name="password" rel="密码不正确" placeholder="请输入密码" autocomplete="off"></div>
                            <label class="reg_input_r"> 6-16个字符的组合密码</label>
                        </li>
                        <li>
                            <div class="reg_input_l"><span>* </span>确认密码：<input type="password" class="login-reg-input" name="re_password" rel="请再次输入密码" placeholder="请再次输入密码" autocomplete="off"></div>
                            <label class="reg_input_r"> 请再次输入密码</label>
                        </li>
                        <li style="padding-left:14px;">
                            <div class="reg_input_l"><span>* </span>验证码：<input type="text" class="login-reg-input" name="verify_code" placeholder="请输入验证码" rel="请输入验证码" style="width:128px;" autocomplete="off"></div>
                            <span style="float:left;" alt="" id="verifyCode" class="reg_code getpassImg">点击获取验证码</span>
                            <label class="reg_input_r"></label>
                        </li>
                        <li class="check">
                            <div class="c_left"><input type="checkbox" class="checkbox" name="rigisteragree" id="rigisteragree"  checked value="1"> 同意注册协议<font>(请仔细阅读条款)</font></div>
                            <label class="reg_input_r" style="float: left;width: 150px;line-height: 40px;"></label>
                        </li>
                         <input name="_register_mobile_" type="hidden" value="<?php echo ($_register_mobile_); ?>"/>
                        <li class="reg_btn"><button type="button" class="reg_button">同意协议并注册</button></li>
                    </ul>
                </div>               
            </form>
        </div>
    </div>
</div>
<script>
    /*$(".reg_button").click(function(){
         var chk = document.getElementById('rigisteragree');
        if(chk.checked){
            // 提交
        }else{
            // 不提交
           alert("请阅读注册协议");
        }
    });*/
	//发送验证码
	var isSend = 0;
    $('.getpassImg').click(function () {
        if(isSend==1)return false;
        isSend = 1;
        var _this = this;
        var mobile = $("input[name='mobile']").val();
        var token = $("input[name='_register_mobile_']").val();
        if (mobile == '' || mobile == '请输入手机号码') {
            alert("请输入手机号码");
            isSend = 0;
            return false;
        }
        if (!/^1\d{10}$/.test(mobile)) {
            alert("请正确输入手机号");
            isSend = 0;
            return false;
        }
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': "/member/registerSendSms",
            "data": {'mobile': mobile, '_register_mobile_': token},
            success: function (json) {
                alert(json.info);
                if (json.status == 1) {
                    //倒计时
                	sendCode(_this);
                } else {
                	isSend = 0;
                    location.reload();
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
$(function() {
    $('form.ui-form').constract(true);
    $('form.ui-form button.reg_button').submitfrom($('form.ui-form'));
    $(".reg_p_tj>.reg_tj_pbox").click(function(){
        $(this).parent("li").toggleClass("re_p_tjshow");
        $(this).parent("li").next(".reg_recom").toggle();
    })
    
    $(".login-reg-input").keypress(function(e){
        e = window.event || e;
        if(e.keyCode == 13){
            $(".reg_button").click();
        }
    })
})
</script>
<script src="_STATIC_/2015/js/login-register.js?v=20150526" type="text/javascript" charset="utf-8"></script>
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