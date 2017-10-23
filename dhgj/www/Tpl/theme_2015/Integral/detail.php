<link rel="stylesheet" href="_STATIC_/2015/integral/css/detail.css">
<style>
    .button {
        background: #a97502 none repeat scroll 0 0;
        border: medium none;
        border-radius: 3px;
        color: #fff;
        height: 33px;
        line-height: 33px;
        margin:10px 0 0 10px;
        text-align: center;
        width: 115px;
    }
    a.button {
        display: block;
        text-decoration: none;
    }
</style>
<div class="content">
    <div class="c_nav">
        <p>当前位置：<a href="/">首页</a> &gt; <a href="/integral" class="c_nav_now">安币商城 </a></p>
    </div>
    <div class="center">
        <div class="de_banner" style="background-image:url(_STATIC_{$rs['filepath']}{$rs['bannername']});"></div>
        <div class="de_conten">
            <div class="de_left c_left">
                {$rs['content']}
                <!--<table>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_01.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_02.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_03.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_04.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_05.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_06.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_07.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_08.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_09.png" alt=""></td></tr>
                    <tr><td><img src="_STATIC_/2015/integral/image/de_10.png" alt=""></td></tr>
                </table>
                <div class="de_left_btm">
                    <p class="de_line"><br></p>
                    <p><em>活动时间：</em>2015.9.10-2015.9.28</p>
                    <p><em>礼品份数：</em>30份，每人兑一份；</p>
                    <p><em>礼品说明：</em>安币商城定制大众汽车头枕</p>
                    <p><br></p>
                    <p><em>领取条件：</em></p>
                    <p>1、投的很安心的用户；</p>
                    <p>2、仅需花1000安心金币兑换，全国包邮；</p>
                    <p>3、数量有限，每位用户限领一份；</p>
                    <p>4、领取成功后，将会在五个工作日内按用户预留的个人信息寄出，由安币商城快递配送。</p>
                    <p class="de_line"><br></p>
                    <p><em>注意事项：</em></p>
                    <p>1、请先在“我的账户”<span class="yell">完善收货信息</span>（<span class="red">地址越详细，到货速度越快哦</span>）后进行兑换，</p>
                    <p>2、领取成功后，不提供更改地址服务，</p>
                    <p>3、若因用户信息缺失或错误而联系不上，则视为用户放弃领奖资格，</p>
                    <p>4、如遇商家缺货，我们会更换新商家或及时和您联系，敬请谅解。</p>
                    <p class="de_line"><br></p>
                    <p style="color:#151515;">微信扫一扫，下月账单一键查收！</p>
                    <p><img src="_STATIC_/2015/integral/image/detail_01.png" alt=""></p>
                </div>
                -->
            </div>
            <div class="de_right c_right">
                <div class="de_r_t">
                    <div class="de_rt_top">
                        <div class="de_sp_l" style="position: relative">
                            <a href="javascript:;" class="shareweixin">分享给好友</a>
                            <div class="shareweixindiv" style="position:absolute;top:-150px;left:-15pxpx;display: none;"><img width="150" alt="微信订阅号" src="_STATIC_/2015/image/c_code_r.jpg"></div>
                        </div>
                        <span class="inte_sp">{$rs['exchange']}</span>
                    </div>
                    <if condition="$rs['integral'] gt $member['memberInfo']['integral']">
                        <!--积分不足-->
                        <div class="de_rt_btm">
                            <p>您的可用金币不足，根据下面方式赢取金币方可领取。</p>
                            <div class="de_btm_bg">
                                <img src="_STATIC_/2015/integral/image/detail_02.png" alt="">
                            </div>
                        </div>
                    <else/>

                        <!--积分足够兑换-->
                        <div>
                            <if condition="$rs['number'] gt 0">
                            <a class="de_btm_a" id="de_btm_a" href="javascript:;">兑换</a>
                                <else/>
                                <a class="de_btm_a">已抢完</a>
                                </if>
                        </div>
                    </if>
                </div>
                <div class="de_r_m">
                    <div class="de_rm_top">金币不够看这里</div>
                    <div class="de_rm_btm">
                        <div class="de_invest">
                            <p class="de_inv_pt"><em><b>{$loan['yearrate']}</b>%</em>纯收益</p>
                            <p class="de_inv_pb">剩余金额：<b>{$loan['loanmoney']-$loan['tendermoney']}</b></p>
                            <span class="plen"><span class="speed"></span><span class="num">{$loan['tenderspeed']}%</span></span>
                        </div>
                        <p>投资期限：<if condition="$loan.isloanday eq 1">
                                {$loan['loanday']} 天
                                <else/>
                                {$loan['loanmonth']} 个月
                            </if></p>
                        <p>收益说明：投资赢金币赢收益</p>
                        <p>保障方式：{$loan['security']}</p>
                        <a class="de_rm_a" href="/invest">我要投资</a>
                    </div>
                </div>

                <div class="de_r_m">
                    <h5 class="de_rm_h5">邀请好友赢金币</h5>
                    <div class="de_rm_btm">
                        <p>邀请别人注册且绑卡成功给10个金币，邀请别人投资按照1%年化给金币，即投10000元1年给100个金币。</p>
                        <a class="de_rm_a" <if condition="$member">href="/member/recomm"</if>>我要邀请</a>
                    </div>
                </div>
                <if condition="!$member">
                <div class="de_r_m">
                    <h5 class="de_rm_h5">注册成功赢金币</h5>
                    <div class="de_rm_btm">
                        <p>1.注册成功（紧注册成功给10个金币）</p>
                        <p>2.银行账户认证（银行账户认证成功给20个金币）</p>
                        <a class="de_rm_a" href="/member/register">我要注册</a>
                    </div>
                </div>
                </if>
                <div class="de_r_b">
                    <img src="_STATIC_/2015/integral/image/detail_04.png" alt="">
                </div>
            </div>
        </div>
    </div>
</div>

<div id="useraddress" class="intadr">
    <h3 class="h3">收货地址</h3>
    <img class="close" src="_STATIC_/2015/integral/image/x.png">
    <div style="padding:20px;">
    <table border="0" cellspacing="0" cellpadding="0" class="table">
        <thead>
        <tr>
            <th>选择</th>
            <th>收货人</th>
            <th>所在地区</th>
            <th>街道地址</th>
            <th>邮编</th>
            <th>电话/手机</th>
        </tr>
        </thead>

        <tbody id="addressList">        </tbody>

        <tfoot>

        <tr>
            <td colspan="6" style="text-align:center;">
                <a class="button" href="/member/address">新增收货地址</a>
                <a id="intsubmit" href="javascript:;"><img  src="_STATIC_/2015/integral/image/t.png" style="vertical-align: middle;"></a></td>
        </tr>
        </tfoot>
    </table>
    </div>
    <div class="msgpage"></div>
</div>

<script>
    var id = {$id};
    $(function(){
        $('.shareweixin').hover(function(){
            $('.shareweixindiv').show();
        },function(){
            $('.shareweixindiv').hide();
        })

        $(".plen .speed").animate({width:$(".num").text()},700);
        $('.close').click(function(){
            $('#useraddress').hide();
        })
        $('#de_btm_a').click(function(){
            divshow($('#useraddress'));
        })
        $('#intsubmit').click(function(){
            var addressid = $("input[name='addressid']:checked").val();
            if(!addressid){
                jdbox.alert(0,'请选择收货地址。');
                return false;
            }
            $.post('/integral/exchange.html',{'addressid':addressid,'id':id},function(response){
                jdbox.alert(response.status,response.info);
                if(response.status){
                    location.href = location.href;
                }
                return true;
            }, "json");

        })
    })
    function divshow(obj){
        obj.show();
        var leftpx = (parseInt($(window).width())-parseInt(obj.width()))/2;
        var toppx = parseInt($(document).scrollTop())+(parseInt($(window).height())-parseInt(obj.height()))/2;
        obj.css('left',leftpx).css('top',toppx);
    }
    mspage=0,msp=0,
    msajaxpage = function(type,count,pagenumber){
            var html = '第<span style="color:#c29540;">';
            html += msp +'</span>页 / 共<span style="color:#c29540;">'+ pagenumber +'</span>页';
            if(msp <= 1){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">上一页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'prev\',1);">首页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'prev\');">上一页</a>';
            }
            if(msp >= pagenumber ){
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#a5a5a5;padding:0 3px;text-decoration:none;">尾页</a>';
            }else{
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'last\');">下一页</a>';
                html+= '<a href="javascript:void(0)" style="color:#c29540;padding:0 3px;" onclick="return ajaxmsghtml(\'last\','+pagenumber+');">尾页</a>';
            }
            html+='';
            $('.msgpage').html(html);
    },ajaxmsghtml = function(type,page){
        msp = (type == 'last') ? ++msp : --msp;
        msp = page||msp;
        if(mspage > 0){
            jdbox.alert(2);
        }
        mspage++;
        $.post('/integral/memberAddress.html',{'p':msp},function(response){
            jdbox.close();
            $('#addressList').html(response.info);
            if(response.status){
                var count = response.data.count,pagenumber = response.data.pagenumber;
                msajaxpage(type,count,pagenumber);
            }
            return true;
        }, "json");
    };
    setTimeout(function(){ajaxmsghtml('last');},1000);

</script>