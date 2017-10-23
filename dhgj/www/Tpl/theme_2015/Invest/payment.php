<style>
    .pay_content{background:#eeeeec;padding-bottom:50px;}
    .pay_center{height:562px;border-radius:5px;border:1px #d1d1d1 solid;background:#fff;}
    .pay_padd{padding:2px 10px 0;}
    .pay_h4{font:18px/41px "微软雅黑";}
    .pay_title{height:34px;line-height:34px;border:1px #dedddd solid;}
    .pay_title>p{text-indent:7px;}
    .pay_title .pay_sp_r{margin-right:6px;}
    .pay_title .pay_sp_r>em{color:#c88d10;font-size:16px;font-family:"微软雅黑";}
    .pay_b{overflow:hidden;}
    .pay_ul{max-width:750px;margin-top:38px;}
    .pay_ul>li{height:32px;line-height:32px;margin-bottom:16px;}
    .pay_ul>li.pay_last{margin:32px 0 0;height:33px;line-height:33px;}
    .pay_ul>li .pay_sp_l{width:135px;height:32px;display:block;float:left;text-align:right;}
    .pay_ul>li .pay_sp_l em{color:#ed0000;}
    .pay_ul>li .input{width:240px;height:30px;text-indent:13px;border:1px #c9c9c9 solid;font-size:12px;vertical-align:middle;}
    .pay_ul .button{width:150px;height:33px;line-height:33px;text-align:center;color:#fff;background:#a97502;border:none;border-radius:3px;}
    .pay_ul .button:hover{background:#ba8613;}
    .pay_code{width:79px;height:32px;vertical-align:middle;}
    .pay_ul_r{width:227px;margin-top:38px;padding:13px 0 13px 27px;font-size:12px;border-left:1px #bdbcbc solid;}
    .pay_ul_r>li{height:24px;}
    .pay_ul_r>li.r_first{font-weight:bold;}
    #imgcodebtn{display:inline-block;width:23px;height:32px;line-height:28px;vertical-align:middle;background:url(_STATIC_/2015/member/image/rech_re2.png) no-repeat center center;}
    #pay_qu{display:inline-block;width:20px;height:32px;line-height:28px;vertical-align:middle;background:url(_STATIC_/2015/invest/image/pay_qu.png) no-repeat center center;}
    #loan_qu{display:inline-block;width:20px;height:32px;line-height:28px;vertical-align:middle;background:url(_STATIC_/2015/invest/image/pay_qu.png) no-repeat center center;}
    .showtip{border:1px #666 solid;background:#f5f5f5;padding:5px;position:absolute;z-index:9;display:none;font-size:12px;width:155px;height:auto;line-height:18px;}
    .pay_spm_r{font-size:12px;color:#f54040;}
</style>
<!--content-->
<div class="pay_content">
    <div class="c_nav">
        <p>当前位置：<a href="/">首页</a> &gt; <a href="/invest">我要投资</a> &gt; <a href="/invest/payment" class="c_nav_now">确认支付 &gt;</a></p>
    </div>
    <div class="center pay_center">
        <div class="pay_padd">
            <h4 class="pay_h4">支付信息</h4>
            <div class="pay_title">
                <p class="c_left">{$loan.title}</p>
                <span class="c_right pay_sp_r">投资金额：<em>￥{$tenders.money}</em></span>
            </div>
            <div class="showtip"></div>
            <div class="pay_b">
                <!--支付-->
                <ul class="pay_ul c_left">
                    <li>
                        <span class="pay_sp_l">预期收益：</span>
                        <span><b>￥{$loan.ratemoneys}</b></span>
                    </li>
                    <li>
                        <span class="pay_sp_l">实际支付：</span>
                        <span><b>￥{$tenders.money}</b></span>
                    </li>
                    <li>
                        <span class="pay_sp_l"><em>*</em>交易密码：</span>
                        <input type="password" class="input" name="tradepwd">
                        <a href="javascript:;" id="pay_qu" t='原始默认交易密码为登录密码，请到我的账户修改'></a>
                    </li>
                    <if condition="$loan.password  neq ''">
                        <li>
                            <span class="pay_sp_l"><em>*</em>投标密码：</span>
                            <input type="password" class="input" name="loanpwd">
                            <a href="javascript:;" id="loan_qu" t='加密的标 请索取密码'></a>
                        </li>
                    </if>
                    <li>
                        <span class="pay_sp_l"><em>*</em>验证码：</span>
                        <input type="text" name="code" class="input" style="width:111px;">
                        <img src="/public/verifyCode.html" alt="" id="verifyCode" class="pay_code" onclick="refreshverifycode('verifyCode')" alt="验证码">
                        <a href="javascript:;" id="imgcodebtn"></a>
                    </li>
                    <li class="pay_last">
                        <span class="pay_sp_l"></span>
                        <button type="button" class="button" id="submit">确认支付</button>
                    </li>
                </ul>
                
                <!--支付信息-->
                <ul class="pay_ul_r c_right">
                    <li class="r_first">项目详情</li>
                    <li></li>
                    <li>借款金额：￥{$loan['loanmoney']|number_format=2}</li>
                    <li>剩余投标金额：￥{$loan['loanmoney']-$loan['tendermoney']|number_format=2}</li>
                    <li>借款年利率：{$loan.yearrate}%</li>
                    <li>已完成：{$loan.tenderspeed}%</li>
                    <li>借款期限：<if condition="$loan.isloanday eq 1">
                            <if condition="$loan['loanday'] eq 90">3月<elseif condition="$loan['loanday'] eq 30" />1月<else/>{$loan['loanday']} 天</if>
                            <else/>
                            {$loan['loanmonth']} 个月
                        </if>
                    </li>
                    <li>还款方式：{$repayment[$loan['repayment']]}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<script>

    var isday = {$loan.isloanday};
    var loanmonth = {$loan.loanmonth};
    var loanday = {$loan.loanday};
    var ratemoneys = {$loan.ratemoneys};
    var tendermoneys = {$tenders.money};
    var yearrate = {$loan.yearrate};
    var sn = '{$loan.loansn}';
    var sendform = false;

    $(function(){
        $("#imgcodebtn").click(function(){
            $("#verifyCode").click();
        })

        $('#submit').click(function(){
            if(sendform){
                return alert('数据传送中，请等待');
            }

            var tradepwd = $("input[name='tradepwd']").val();
            if(!tradepwd){
                return jdbox.alert(0, '交易密码不能为空');
            }
            var code =  $("input[name='code']").val();
            if(!code){
                return jdbox.alert(0, '验证码不能为空');
            }
            var loanpwd = $("input[name='loanpwd']").val();
            sendform = true;
            $.post('/invest/loantender',{'code':code,"tradepwd":tradepwd,"loanpwd":loanpwd},function(response){
                sendform = false;
                if(response.status){
                    alert(response.info);
                    location.href = "/invest/detail/sn/"+sn;
                }else{
                    jdbox.alert(response.status,response.info);
                }

            }, "json");
        })
        
        $("#pay_qu,#loan_qu").each(function(i){
            $(this).hover(function(){
                $(".showtip").html($(this).attr("t"));
                var sL = $(this).position().left+26;
                var sT = $(this).position().top-8;
                $(".showtip").css({top:sT,left:sL});
                $(".showtip").show();
            }, function(){
                $(".showtip").hide();
            })
        })
    })
</script>