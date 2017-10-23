<!--<script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>-->
<script type="text/javascript" src="_STATIC_/2015/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_STATIC_/2015/js/jquery.form.js"></script>
<link rel="stylesheet" href="_STATIC_/2015/member/css/account.css" />
<script type="text/javascript" src="_STATIC_/2015/member/js/jquery.zclip.min.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<style type="text/css">
    body{background: #efefef; font-family: "微软雅黑";}
</style>
<script type="text/javascript">
$(function(){
     $isAgentAlert = <?php echo ($_REQUEST['is_alert'])?$_REQUEST['is_alert']:0;?>;
     if($isAgentAlert == 1)$(".js_div").show();
})
</script>
<!--金牌经纪人介绍开始-->
<div class="js_div">
    <div class="jsimg_div">
        <img src="_STATIC_/2015/image/yydai/agent/js_01.jpg">
        <img src="_STATIC_/2015/image/yydai/agent/js_02.jpg">
        <img src="_STATIC_/2015/image/yydai/agent/js_03.jpg">
        <img src="_STATIC_/2015/image/yydai/agent/js_04.jpg">
    </div>
    <div class="ready_div">
        <input type="checkbox" name="agree_agent">
        <span>同意<a href="/agent/contract">金牌经纪人服务合同</a>（勾选之后才可认证）</span>
    </div>
    <div class="tof_div">
        <a onclick="javascript:$('.js_div').hide();">取消</a>
        <if condition="$agent_info['is_enough']['status'] eq 1">
           <a onclick="is_pay();">立即认证</a>
        <else/>
            <a onclick="carry({$agent_info['is_enough']['extra']});">余额不足，请充值！</a>
        </if>
        
    </div>
</div>
<!--金牌经纪人介绍结束-->
<div class="hbgDiv"></div>
 <section class="tkjin_bg" style="z-index: 110;">        
    <h2>提示</h2>
    <p>您已同意“借吧”金牌经纪人服务合同<br/>
        认证将扣除账户余额{$agent_info['pay_money']}元</p>
    <section class="btn_tkjin" id="tl_cancel" onclick="pay_cancel();">取消</section>  
    <section class="btn_tkjin" id="tl_close" onclick="pay_money();" url =" ">确认</section>        
</section>

<!--我的账户 首页部分 开始-->
<div class="maxDiv" style="position:relative; font-family:'微软雅黑';">
    <!--banner开始-->
    <div class="bannerDiv">
        <img src="_STATIC_/2015/member/image/account/banner.jpg" alt="" class="bannerImg"/>
        <a href='/member/systemnews/status/0'>
            <img src="_STATIC_/2015/member/image/account/messageicon.png" alt="" class="messageiconImg"/>
            <img src="_STATIC_/2015/member/image/account/d.png" alt="" class="diconImg"/>
            <span class="dwz">{$count}</span>
        </a>
        <div class="photoname">
            <div class="headdiv">
                <div  id="headsImg" class="headsImg" style="background:url(<notempty name="member_info.avatar">_STATIC_/Upload/avatar/{$member_info.avatar}<else/>_STATIC_/2015/member/image/account/heads.png</notempty>)no-repeat center center; background-size: cover;  background-position-y: 50%;"></div>
            <!-- <img src="" alt="" class="headsImg"/> -->
            </div>            
            <div class="phonenum">
                <font>{$member_info.username}</font>
                <p>普通会员</p>
            </div>
            <img src="_STATIC_/2015/member/image/account/ewmicon.png" alt="" class="ewmiconImg"/>
        </div>      
        <img src="_STATIC_/2015/member/image/account/bluel.png" alt="" class="bluedImg"/>
        <a href='/member/sign'>
            <div class="qDiv">
                <img src="_STATIC_/2015/member/image/account/qdicon.png" alt="" class="qdiconImg"/>
                <span class="qdspan qsize">签到</span>
            </div>
        </a>
        <div class="yqDiv">
            <img src="_STATIC_/2015/member/image/account/yahyicon.png" alt="" class="yqiconImg"/>
            <span class="yqspan qsize">邀请</span>
        </div>
    </div>
    <!--banner结束-->
    <div class="moneydiv-view">
        <div class="moneydiv-view-cell" style="display:none;">
            <img src="_STATIC_/2015/member/image/account/ico_money.png">
            <font>账户余额</font>
            <span><!-- {$member_info.balance}元 --></span>
        </div>
        <div class="cztx-view-cell">
            <a href='javascript:void(0);'>充值</a>
            <a href='/credit/cashout?returnurl=<?php echo urlencode('/member/account/');?>'>提现</a>
        </div>
    </div>
    <!--我的订单部分 开始-->
    <div class="ddDiv">
        <a href='/member/myorder'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/myordericon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">我的订单</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a>        
        <div class="dpjDiv">
            <a href='/member/myassed'>待评价 <font>{$data.assed_count}</font></a>
            <a href='/member/myasseded'>已评价<font>{$data.asseded_count}</font></a>
        </div>        
    </div>
    <!--我的订单结束-->
    <!--个人中心部分 开始-->
    <div class="ddDiv">
        <a href='/member/accountcenter'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/zhzxicon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">个人中心</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a>        
        <div class="dpjDiv">
            <a href='/credit/realname?returnurl=<?php echo urlencode('/member/account');?>' style="border-bottom: 1px solid #e3e3e3;">实名认证</a>
            <a href='/credit/bindCard?returnurl=<?php echo urlencode('/member/account');?>' style="border-bottom: 1px solid #e3e3e3;">绑定银行卡</a>                     
        </div>        
    </div>
    <!--个人中心部分 结束-->    
    <!--账户中心 开始-->
    <div class="ddDiv">
    <if condition="$agent_info['isOpenGold'] eq 0">
        <a href='javascript:void(0)' class="goldAgent">
            <div class="myorderDiv" >
                <img src="_STATIC_/2015/member/image/account/ico_jj.png" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">认证金牌经纪人</span>               
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
    <else/>
        <a href="/agent/agent_account">
            <div class="myorderDiv" >
                <img src="_STATIC_/2015/member/image/account/ico_jj_blue.png" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize" style="color:#46abef;" >金牌经纪人</span> 
                <img src="_STATIC_/2015/member/image/account/jticon_blue.png" alt="" class="jtImg"/>
            </div>
        </a> 
    </if>
         <a href='/Carinsurance/myCarInsurance'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/insurance.png" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">我的车险</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div> 
        </a>    
        <a href='http://zxcf.imwork.net:86/M/login.aspx'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/ludan@21.png" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">车辆录单</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
        <a href='/member/recommList'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/yqjlicon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">邀请记录</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
        <a href='/member/activity'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/hdzqicon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">活动专区</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
        <a href='/member/customer_list'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/zxkficon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">在线客服</span>
                <font class="ssfu">随时为您服务哦</font>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
        <a href='/member/more'>
            <div class="myorderDiv">
                <img src="_STATIC_/2015/member/image/account/moreicon.jpg" alt="" class="myodeiconImg"/>
                <span class="myorderwz numsize">更多</span>
                <img src="_STATIC_/2015/member/image/account/jticon.jpg" alt="" class="jtImg"/>
            </div>
        </a> 
    </div>
    <!--账户中心 结束-->
   
    <!--客服电话部分 开始-->
    
    <div class="ddDiv">
        <a href='tel:4006639066' style="height:40px; display:block; line-height:40px; color:#46abef; outline:none; font-size:14px;">
            <span>客服电话：400 663 9066</span>
        </a>
    </div>    
    <!--客服电话部分 结束-->
    <!--服务时间部分--> 
   <div class="futime"  style="margin-bottom:15%;">
        <a class="futimewz qsize" id="copyBtn">服务时间：法定工作日 9:00-18:00</a>
    </div>
</div>
<!--我的账户 首页部分 结束-->
<!--黑色背景层   弹1-->

<div class="fxDiv">
    <div class="hbgDiv">        
    </div>
    <!--分享  弹2-->
    <div class="shareDiv">
        <div class="shareupDiv" >
            <p class="sharewz qsize">分享</p>
            <a href="#" class="ewmhref"> 
                <img src="_STATIC_/2015/member/image/account/ewm.png" alt="" class="ewmImg"/>
                <span class="rwmwz sharesize">二维码</span>
            </a>
        </div>
        <div class="sharecenterDiv">
            <form><input type="hidden" name="is_share" class="is_share" value="{$is_share}"></form>
            <a href="#"> 
                <img src="_STATIC_/2015/member/image/account/wxlogoimg.png" alt="" class="wxImg shareWeixin"/>
                <span class="wxwz sharesize">微信</span> 
            </a>
            <a href="#"> 
                <img src="_STATIC_/2015/member/image/account/pyqlogo.png" alt="" class="pyqImg shareFriend"/>
                <span class="pyqwz sharesize">微信朋友圈</span> 
            </a>
            <a href="#"> 
                <img src="_STATIC_/2015/member/image/account/qqlogo.png" alt="" class="qqImg shareQQ"/>
                <span class="qqwz sharesize">QQ</span> 
            </a>
            <a href="#"> 
                <img src="_STATIC_/2015/member/image/account/kjlogo.png" alt="" class="kjImg shareQroom"/>
                <span class="kjwz sharesize">QQ空间</span> 
            </a>
        </div>
        <div class="calcDiv ">
            <p class="calcwz cqsize">取消</p>
        </div>
    </div>
    <!--复制成功部分 开始-->
    <div class="copySuccful">
        <img src="_STATIC_/2015/member/image/account/copyyes.png" alt="" class="copyyesimg"/>
        <p class="copyyeswz csize">复制成功</p> 
    </div>
    <!--复制成功部分 结束-->
    <!--弹出二维码  部分 开始-->
    <style type="text/css">
    .QRcodeDiv span{border-radius: 5px; text-align: center; height: 30px; line-height: 27px; color: #fff; display: block; margin-top: 5%; position: absolute; left: 50%; width: 50%; margin-left: -25%; border:1px solid #fff;}
    </style>
    <div class="QRcodeDiv">
        <img src="_STATIC_/Upload/qrcode/{$card_name}" style="width: 100%; display: block;">
        <span>长按名片保存到相册</span>
        <!--二维码图片 信息部分-->
        <div class="infodiv">           
                    
        </div>
        <!--保存相册 部分-->
        <div class="savediv">
            <img src="_STATIC_/2015/member/image/account/savebtn.png" alt="" class="savebtnImg"/>
        </div>
    </div>

    <!--弹出二维码  部分 结束-->
</div>
<!--黑色背景层 分享部分  结束-->
<div class="deDiv">
    <div class="secondDiv"></div><!--弹出灰色弹窗-->
    <div class="thirdDiv">
        <form action="/Member/avatar" name="form_logo" id="form_logo" method="post" enctype="multipart/form-data">
            <p class="notice tsize" id="upload" style="text-align:center;width:100%;">从相册中选取</p>
            <input type="file"  name="file" id="file" class="fileCls"/>
        </form>
        <a href="javascript:void(0)"  class='jump_url'><input type="button" value="取消" class="surebtn ssize"/></a>
    </div><!--弹出内容-->
</div>
<div  class="zdBgDiv" style="width:100%;height:100%;position: absolute;z-index:1000;display:none;">
    <img src="_STATIC_/2015/member/image/account/zdbg.png" alt="" style="width:100%;height:100%;position: absolute;left:0%;top:0%;" />
</div>
<style>
    .fileCls{position:absolute;z-index:1000;left:0%;top:0%;width:100%;height:40%;filter: alpha(opacity=0);opacity: 0;}
    /*filter: alpha(opacity=0);opacity: 0;   onchange="preview(this)"*/
</style>
<script>
    //上传图片部分
    var  clickNum=1;
    $('#file').change(function () {
        if(clickNum==1){
            var options = {
                url: "/Member/avatar",
                type: "post",
                dataType: "json",
                success: function (o) {
                    console.log(o);
                    if (o.status == 0) {
                        //alert(o.info);
                    } else {
                        arr = eval(o.data);
                        var path = "_STATIC_/Upload/avatar/" + arr.avatar;
                        $("#headsImg").attr('src', path);
                        $("#headsImg").css("background-image","url("+path+")");
                        $(".thirdDiv").hide();
                        $(".secondDiv").hide();
                        $(".deDiv").hide();
                        $("body").unbind("touchmove");
                        //return top.jdbox.alert(1, o.info,'window.reload()');
                    }
                    alert(o.info);
                }
            };
            clickNum=2;
            $("#form_logo").ajaxSubmit(options);
        }else{
            alert('请稍微等 正在上传！');
        }
    });
</script>

<script type="text/javascript">
//    点击邀请时触发
    $(".yqDiv").click(function () {
        $(".shareDiv").css("display", "block");
        $(".hbgDiv").css("display", "block");
        $(".fxDiv").css("display", "block");
//      关闭父窗体滚动
        $('body').on('touchmove', function (event) {
            event.preventDefault();
        });
    });
//点击取消按钮时触发
    $(".calcDiv").click(function () {
        $(".shareDiv").css("display", "none");
        $(".hbgDiv").css("display", "none");
        $(".fxDiv").css("display", "none");
        //      启用父窗体滚动
        $("body").unbind("touchmove");
    });
//点击复制链接时触发
    $(".copyhref").click(function () {
        $(".shareDiv").css("display", "none");
        $(".hbgDiv").css("display", "block");
        $(".fxDiv").css("display", "block");
        $(".copySuccful").css("display", "block");
        $('#copyBtn').click();
        if ($(".copySuccful").css("display") == "block")
        {
            setTimeout(function ()
            {
                $(".shareDiv").css("display", "none");
                $(".hbgDiv").css("display", "none");
                $(".fxDiv").css("display", "none");
                $(".copySuccful").css("display", "none");
            }, 1000)
        }

    });
//点击 分享 二维码链接时触发 
    $(".ewmhref").click(function () {
        $(".shareDiv").css("display", "none");
        $(".copySuccful").css("display", "none");
        $(".hbgDiv").css("display", "block");
        $(".fxDiv").css("display", "block");
        $(".QRcodeDiv").css("display", "block");
        $(".infodiv").css("display", "block");
        $(".savediv").css("display", "block");
    });

//    点击 我的首页 二维码 触发
    $(".ewmiconImg").click(function () {
        $(".shareDiv").css("display", "none");
        $(".copySuccful").css("display", "none");
        $(".fxDiv").css("display", "block");
        $(".hbgDiv").css("display", "block");
        $(".QRcodeDiv").css("display", "block");
        $(".infodiv").css("display", "block");
        $(".savediv").css("display", "none");
    });

    //    点击 灰色层 触发
    $(".hbgDiv").click(function () {
        $(".shareDiv").css("display", "none");
        $(".copySuccful").css("display", "none");
        $(".fxDiv").css("display", "none");
        $(".hbgDiv").css("display", "none");
        $(".QRcodeDiv").css("display", "none");
        $(".infodiv").css("display", "none");
        $(".maxDiv").css("display", "block");
    });
    
    //   点击头像修改头像
    $(".headsImg").click(function () {
        $(".secondDiv").css("display", "block");
        $(".thirdDiv").css("display", "block");
        $(".deDiv").css("display", "block");
//      关闭父窗体滚动
        $('body').on('touchmove', function (event) {
            event.preventDefault();
        });
    });
    $('.surebtn').click(function () {
        $(".secondDiv").css("display", "none");
        $(".thirdDiv").css("display", "none");
        $(".deDiv").css("display", "none");
        //启用父窗体滚动
        $("body").unbind("touchmove");
    });
//点击指导的时候触发
    $(".zdBgDiv").click(function () {
        $(".zdBgDiv").css("display", "none");
      
    });
//认证金牌经纪人判断
    $(".goldAgent").click(function(){
        $open_status = "{$agent_info['open_status']}";
        $is_pay = "{$agent_info['isPayGold']}";
        if($is_pay == 1){
            window.location.href = "/agent/agent_authentication";
            return false;
        }
        if($open_status == 1){
            window.location.href = "/member/openAgent";
        }else{
            $(".js_div").show();   
        }
    })
function is_pay (){
    if(false == $("input[name='agree_agent']").prop('checked')){
        alert("请先同意金牌经纪人服务合同！");
        return false;
    }
    $('.hbgDiv').show();
    $('.tkjin_bg').show();
}
function pay_cancel(){
    $(".tkjin_bg").hide();
    $('.hbgDiv').hide();
}
//金牌经纪人认证扣款
function pay_money ($pay_money) {
    $(".tkjin_bg").hide();
    $('.hbgDiv').hide();
    $('.js_div').hide();   
    var data = {};
    $(data).attr("is_pay",1);
    $.post("/agent/pay_money",data,function(F){
        console.log(F);
        var F = eval("(" + F + ")");
        if(F.status == 1){
            alert(F.info);
            window.location.href = "/agent/agent_authentication";
        }else{
            alert(F.info);
        }
    })
}
//充值
function carry ($carry_money){
    window.location.href = "/credit/carry/?money="+ $carry_money +"&returnurl="+'<?php echo urlencode("/member/account/is_alert/1")?>';
}
</script>


<script>
    //分享成功调用函数
    function share_success(){
        $share = $(".is_share").val();
        var data = {};
        $(data).attr('is_share', $share);
        $.post('/Member/addScore.html', data, function (F) { 
            alert(F.info);             
        },'json');
    }
    // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
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

    wx.ready(function () {
        wx.onMenuShareAppMessage({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
                share_success();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareTimeline({
            title: '借吧--注册', // 分享标题
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                share_success();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
        wx.onMenuShareQQ({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                share_success();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

        wx.onMenuShareWeibo({
            title: '借吧--注册', // 分享标题
            desc: '欢迎注册借吧-平台，快速借款给你', // 分享描述
            link: '_WWW_/member/register/recintcode/{$member_info.invitecode}', // 分享链接
            imgUrl: '_STATIC_/2015/member/image/account/heads.png', // 分享图标
            success: function () {
                // 用户确认分享后执行的回调函数
                share_success();
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });

    });

//分享给朋友
    $('.shareWeixin').click(function () {
           $(".zdBgDiv").css("display", "block");
        if (typeof WeixinJSBridge == "undefined") {
            // alert("对不起，请通过右上方微信分享 ");
            // alert("123"):
         
            //      关闭父窗体滚动
            $('body').on('touchmove', function (event) {
                event.preventDefault();
            });
        } else {
            WeixinJSBridge.invoke('sendAppMessage', {
                "title": "借吧",
                "link": "_WWW_/member/register/recintcode/{$member_info.invitecode}",
                "desc": "欢迎注册借吧-平台，快速借款给你",
                "img_url": "_STATIC_/2015/member/image/account/heads.png"
            });
        }
    });

//分享到朋友圈
    $('.shareFriend').click(function () {
            $(".zdBgDiv").css("display", "block");
        if (typeof WeixinJSBridge == "undefined") {
            //alert("对不起，请通过右上方微信分享 ");
        
            //      关闭父窗体滚动
            $('body').on('touchmove', function (event) {
                event.preventDefault();
            });
        } else {
            WeixinJSBridge.invoke('shareTimeline', {
                "title": "借吧",
                "link": "_WWW_/member/register/recintcode/{$member_info.invitecode}",
                "desc": "欢迎注册借吧-平台，快速借款给你",
                "img_url": "_STATIC_/2015/member/image/account/heads.png"
            });
        }
    });
    //分享到QQ
    $('.shareQQ').click(function () {
          $(".zdBgDiv").css("display", "block");
        if (typeof WeixinJSBridge == "undefined") {
            //alert("对不起，请通过右上方微信分享 ");
          
            //      关闭父窗体滚动
            $('body').on('touchmove', function (event) {
                event.preventDefault();
            });
        } else {
            WeixinJSBridge.invoke('shareTimeline', {
                "title": "借吧",
                "link": "_WWW_/member/register/recintcode/{$member_info.invitecode}",
                "desc": "欢迎注册借吧-平台，快速借款给你",
                "img_url": "_STATIC_/2015/member/image/account/heads.png"
            });
        }
    });
    //分享到QQ空间
    $('.shareQroom').click(function () {
        $(".zdBgDiv").css("display", "block");
        if (typeof WeixinJSBridge == "undefined") {
            // alert("对不起，请通过右上方微信分享 ");
            
            //      关闭父窗体滚动
            $('body').on('touchmove', function (event) {
                event.preventDefault();
            });
        } else {
            WeixinJSBridge.invoke('shareTimeline', {
                "title": "借吧",
                "link": "_WWW_/member/register/recintcode/{$member_info.invitecode}",
                "desc": "欢迎注册借吧-平台，快速借款给你",
                "img_url": "_STATIC_/2015/member/image/account/heads.png"
            });
        }
    });


    //分享到朋友圈
    $('.shareWeibo').click(function () {
        $(".zdBgDiv").css("display", "block");
        if (typeof WeixinJSBridge == "undefined") {
            
            //      关闭父窗体滚动
            $('body').on('touchmove', function (event) {
                event.preventDefault();
            });
            // alert("对不起，请通过微信分享 ");
        } else {
            WeixinJSBridge.invoke('shareTimeline', {
                "title": "借吧",
                "link": "_WWW_/member/register/recintcode/{$member_info.invitecode}",
                "desc": "欢迎注册借吧-平台，快速借款给你",
                "img_url": "_STATIC_/2015/member/image/account/heads.png"
            });
        }
    });

</script>