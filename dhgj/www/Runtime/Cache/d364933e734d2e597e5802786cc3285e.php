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
     <link rel="stylesheet" href="_STATIC_/2015/index/css/index.css">
 <style type="text/css">
.info_div:hover{cursor: pointer;}
 </style>
    <div class="con_slide_box">
        <div class="center">
        <?php if(empty($_SESSION['member'])): ?><div class="tip_box">
                <a class="white_login" href="/member/login">登录</a>
                <a class="bule_reg" href="/member/register">注册</a>
            </div><?php endif; ?>
        </div>
        <div class="index_banner" id="bannerslide">
            <ul class="slides">                
                <li style="background:url(_STATIC_/2015/index/image/banner_01.jpg); background-position: center center;"></li> 
                <li style="background:url(_STATIC_/2015/index/image/banner_02.jpg); background-position: center center;"></li>                
            </ul>
        </div>
        <!--icon-->
        <div class="icon_div">
            <div class="icon_five">
                <ul>
                    <li><img src="_STATIC_/2015/index/image/icon_cz.png"><span>操作流程</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_reg.png"><span>注册会员</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_up.png"><span>上传订单</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_fd.png"><span>客服分单</span></li>
                    <li><img src="_STATIC_/2015/index/image/icon_jd.png"><span>接单成功</span></li>
                </ul>
            </div>
        </div>
        <!--iconed-->
        <input type="hidden" name="is_login" value="<?php echo ($_SESSION['member']['id']); ?>">
        <?php if(!empty($_SESSION['member'])): if($_SESSION['member']['usertype'] == 2): ?><div class="info_div" onclick="javascript:window.location.href='/order/my_order_list';" style="display: none;">
                <div class="pai_div" >
                    <img src="_STATIC_/2015/index/image/icon_info.png">
                    <span style="margin-left: 10px;">有派发给你的新订单，请您尽快接单。</span>
                </div>
            </div><?php endif; endif; ?>
    </div>
    <div class="order_div">
        <div class="order_con">
            <div class="order_up">
                <div class="uporder_left">
                    <a class="btn_upo" href="/order/add_order">点击上传</a>
                </div>
                <div class="uporder_right">
                    <img src="_STATIC_/2015/index/image/icon_news.png" style="position: absolute;">
                    <?php if(is_array($newList)): foreach($newList as $key=>$vo): ?><div class="order_one">
                        <div class="order_header">
                            <img src="_STATIC_/2015/index/image/pic_order.png">                            
                            <div class="order_tit">
                                <p>订单编号:<br><?php echo ($vo['contract_num']); ?></p>                            
                                <span style="color: #979797; font-size: 16px;"><?php echo ($vo['distribute_time']); ?></span>
                            </div>                            
                        </div>
                        <table>
                            <tr><td class="ls8">姓       名:</td><td><?php echo ($vo['names']); ?></td><td>车辆型号:</td><td><?php echo ($vo['car_brand']); ?></td></tr>
                            <tr><td class="ls8">性       别:</td><td><?php echo ($vo['sex']); ?></td><td>车牌号码:</td><td><?php echo ($vo['plate_num']); ?></td></tr>
                            <tr><td>借款城市:</td><td><?php echo ($vo['loan_city']); ?></td><td>车架号码:</td><td><?php echo ($vo['frame_num']); ?></td></tr>
                        </table>
                        <a class="more_ord" href="/order/newOrderList">更多订单</a>
                    </div><?php endforeach; endif; ?>
                </div>
            </div>
        </div>
        <div class="order_con">
            <div class="history">
                <div class="history_left">
                    <a class="btn_moreo" href="/order/newOrderList">更多订单</a>
                </div>
                <div class="history_right">
                    <table>
                        <tr>
                            <th>订单编号</th><th>姓名</th><th>性别</th><th>借款城市</th><th>车辆型号</th><th>车牌号码</th><th>车架号码</th>
                        </tr>
                        <?php if(is_array($historyList)): foreach($historyList as $key=>$vi): ?><tr><td class="order_num"><img src="_STATIC_/2015/index/image/icon_tc.png"><?php echo ($vi['contract_num']); ?></td><td><?php echo ($vi['names']); ?></td><td>女</td><td><?php echo ($vi['loan_city']); ?></td><td><?php echo ($vi['car_brand']); ?></td><td><?php echo ($vi['plate_num']); ?></td><td><?php echo ($vi['frame_num']); ?></td></tr><?php endforeach; endif; ?>
                    </table>
                </div>
            </div>
        </div> 
    </div>
    <div class="js_div">
        <div class="order_con">
            <div class="js_con">
                贷后管家是国内首家主打快捷、高效、合规的贷后催收平台，专业为客户提供互联网金融解决方案。贷后管家依托互联网大数据，通过与多家公司建立数据战略合作关系，凭借自身强大的数据技术实力，量身为自己团队定做了互联网数据管理和贷后催收系统，将每一笔案件的进展和历史记录都完整呈现，让不良资产的处置变得简单，充分提升工作效率。贷后管家以先进的互联网思维和过硬的技术实力做后盾，致力于打造业界效率最高的贷后管理方案，为互联网金融生态圈的健康发展与创新贡献自己的力量。
            </div>
        </div>
    </div>
    <div class="order_div" style="height: 770px;">
        <div class="order_con">
            <div class="tit_media"></div>
            <div class="media_div">
               <?php if(is_array($mediaList)): foreach($mediaList as $k=>$vn): ?><div class="mleft" nid="<?php echo ($vn['id']); ?>" style="display: <?php echo ($k==0)?"inline-block;":"none;";?>">
                        <img style="max-height:250px;margin: 10px auto;margin-bottom: 20px;" src="<?php echo ($vn['pic_urls'][0]); ?>">
                        <div class="mleft_con">
                            <div class="mleft_data">
                                <h2><?php echo ($vn['day']); ?></h2>
                                <span><?php echo ($vn['month']); ?>月<br><?php echo ($vn['year']); ?></span>
                            </div>
                            <div class="mleft_text">
                                <h2><?php echo ($vn['title']); ?></h2>
                                <p style="margin-top: 20px;"><?php echo ($vn['intro']); ?></p>
                                <a href="/media/news/id/<?php echo ($vn['id']); ?>">查看全文</a>
                            </div>
                        </div>
                     </div><?php endforeach; endif; ?>
                <div class="mright">
                    <div class="m_mouse_top" onclick="mouse_top(this);"></div>
                    <div class="m_banner">
                        <ol>
                          <?php if(is_array($mediaList)): foreach($mediaList as $key=>$vm): ?><li nid="<?php echo ($vm['id']); ?>">
                                <a onclick="changeNews(<?php echo ($vm['id']); ?>);"><img style="width: 290px;height: 152px;" src="<?php echo ($vm['pic_urls'][0]); ?>"></a>
                              </li><?php endforeach; endif; ?> 
                      </ol>   
                    </div>
                    <div class="m_mouse_down" id="down" ></div>
                </div>
            </div>
        </div>
    </div>
    <script src="_STATIC_/2015/index/js/uislider.js"></script>
    <script src="_STATIC_/2015/index/js/jquery.rotate.min.js" type="text/javascript"></script>
    <script>

    function changeNews(id) {
        // $(".mleft").css("display","none");
        // $("div[nid='"+id+"']").css("display","inline-block");
        $(".mleft").css("display","none");
        $("div[nid='"+id+"']").fadeIn(1000);
    }
    
    function mouse_top(_this)
    {
        var _this_ol = $(_this).next().children().first();
        var _field = _this_ol.find('li:first'); //此变量不可放置于函数起始处,li:first取值是变化的
        var news_id = _field.next("li").attr("nid");
        // $(".mleft").css("display","none");
        // $("div[nid='"+news_id+"']").css("display","inline-block");
        $(".mleft").css("display","none");
        $("div[nid='"+news_id+"']").fadeIn(1000);
        var last = _this_ol.find('li:last'); //此变量不可放置于函数起始处,li:last取值是变化的
        //last.prependTo(_wrap);
        var _h = last.height();
        _this_ol.css('marginTop', -_h + "px");
        last.prependTo(_this_ol);
        _this_ol.stop().animate({
            marginTop: 0
        },        
        function() { //通过取负margin值,隐藏第一行
            //$("#container ol").css('marginTop',0).prependTo(_wrap);//隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
        })
    }
function isNewOrder()
{
    var is_login = ($("input[name='is_login']").val())?1:0;
    if(!is_login)return false;
     $.post("<?php echo U('/index/isNewOrder');?>",{"is_ajax":1},function(F){
          if(F.status == 1){
              $(".info_div").fadeIn();
          }else{
              $(".info_div").fadeOut();
          }
     },'json');
    
}
isNewOrder();
$(function(){
    setInterval("isNewOrder()",10000);
    var down = document.getElementById("down");
    down.onclick = function()
    {
        var _this_ol = $(this).prev().children().first();
        var _field = _this_ol.find('li:first'); 
        
        //此变量不可放置于函数起始处,li:first取值是变化的
        var news_id = _field.next("li").attr("nid");
        $(".mleft").css("display","none");
        $("div[nid='"+news_id+"']").fadeIn(1000);
        var last = _this_ol.find('li:last'); //此变量不可放置于函数起始处,li:last取值是变化的
        var _h = _field.height();
        _field.stop().animate({
            marginTop: -_h + 'px'
        },
       
        function() { //通过取负margin值,隐藏第一行
            _field.css('marginTop', 0).appendTo(_this_ol); //隐藏后,将该行的margin值置零,并插入到最后,实现无缝滚动
        })
    }
   // down.click();
    var re = setInterval("down.click()",5000);
    $(".m_mouse_top,.m_mouse_down,.m_banner").hover(function(){
        clearInterval(re);
    },
    function(){
        re = setInterval("down.click()",3000);
    })
});
    $(function() {
        //setInterval('mouse_down(this)',1000);
        $('#bannerslide').uislider({
            directionNav: false,
            slideshowSpeed: 5000,
            animationDuration: 500,
            pauseOnAction: true,
            start: function () {
                $('#bannerslide').attr('style', 'background:none');
            }
        });
    });
    </script> 

<!--[if lte IE 9]>-->
<!--    <script>
        $(function(){
            $('#itop').click(function(){
                uploadhide();
            })
        })
    </script>-->
<!--<![endif]-->
    <div class="ui-clearfix"></div>
</div>
<div class="footer">
    <div class="center">
        <div class="footer_t">
            <ul class="c_fot_ul c_left">
                <li>
                    <h3 style="cursor: pointer;" onclick="window.location.href='/order/neworderlist'">最新订单</h3> 
                    <h3 style="cursor: pointer;"  onclick="window.location.href='/company/index'">公司介绍</h3>                    
                </li>
                <li>
                    <h3 style="cursor: pointer;" onclick="window.location.href='/order/neworderlist'">历史订单</h3> 
                    <h3 style="cursor: pointer;" onclick="window.location.href='/media/index'">媒体公告</h3>                    
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