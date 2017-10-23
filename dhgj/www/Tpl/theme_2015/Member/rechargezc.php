<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li class="now"><a href="/member/recharge">充值</a></li>
            <li><a href="/member/carry">提现</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
        <div class="hint"><span class="c_left"><i></i>安全&nbsp;&nbsp;快捷&nbsp;&nbsp;免手续费</span><span class="c_right">账户可用余额：<b>￥{$member['memberInfo']['availableAmount']|default=0.00}</b></span></div>
    </div>
    <div class="rec_b">
        <ul class="ba_min_nav rec_b_curr">
            <li><a href="/member/recharge">快捷支付</a></li>
            <li class="now">网银支付</li>
        </ul>

        <div class="ba_b_con bank_pay ba_min_show">
            <!--<div class="inputrow"><span class="pay_sp_l c_left">银行账户类型：</span><div class="c_left">
                <input type="radio" checked="checked" name="pay_type" class="rec_radio" onclick="showBank('B2C');"> 个人账户
                <input type="radio" name="pay_type" class="rec_radio" onclick="showBank('B2B');"> 企业账户
            </div></div>-->
            <div class="wrap">
                <p>请选择充值银行：</p>
                <div class="pay_banks" id="B2C_bank" style="display:block;">
                    <ul>
                        <li><input type="radio" name="backcode" class="rec_radio" checked="checked" value="CCB">
                        <img src="_STATIC_/2015/member/image/bank/ccb.gif" alt="中国建设银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="BOC">
                        <img src="_STATIC_/2015/member/image/bank/boc.gif" alt="中国银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="ICBC">
                        <img src="_STATIC_/2015/member/image/bank/icbc.gif" alt="中国工商银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="ABC">
                        <img src="_STATIC_/2015/member/image/bank/abc.gif" alt="中国农业银行"></li>


                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="CIB">
                        <img src="_STATIC_/2015/member/image/bank/cib.gif" alt="兴业银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="SPDB">
                        <img src="_STATIC_/2015/member/image/bank/spdb.gif" alt="浦发银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="CEB">
                        <img src="_STATIC_/2015/member/image/bank/ceb.gif" alt="中国光大银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="PSBC">
                        <img src="_STATIC_/2015/member/image/bank/psbc.gif" alt="中国邮政储蓄银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="CMB">
                        <img src="_STATIC_/2015/member/image/bank/cmb.gif" alt="招商银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="CMBC">
                        <img src="_STATIC_/2015/member/image/bank/cmbc.gif" alt="中国民生银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="COMM">
                        <img src="_STATIC_/2015/member/image/bank/ocom.gif" alt="交通银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="CITIC">
                        <img src="_STATIC_/2015/member/image/bank/citic.gif" alt="中信银行"></li>
                        
                        <li><input type="radio" name="backcode" class="rec_radio" value="GDB">
                        <img src="_STATIC_/2015/member/image/bank/gdb.gif" alt="广发银行"></li>

                        <li><input type="radio" name="backcode" class="rec_radio" value="SZPAB">
                        <img src="_STATIC_/2015/member/image/bank/pab.gif" alt="平安银行"></li>
                    </ul>
                </div>

                <ul class="pay_ul" style="margin-top:20px;">
                    <li><span class="pay_sp_l">充值金额：</span><input type="text" name="money" > 元</li>
                    <li><span class="pay_sp_l">验证码：</span><input type="text" name="code" class="pay_code">
                        <img src="/public/verifyCode.html" alt="" id="verifyCode" class="imgcode" onclick="refreshverifycode('verifyCode')" alt="验证码">
                        <a href="javascript:;" class="imgcodebtn"></a>
                    </li>
                <li>
                    <input type="checkbox"  style="margin-left:50px;float:left;width:20px;border:1px solid red;" name="rigisteragree" value="1"></input>我已阅读并同意<a href="/about/userprotocal" style="color:#4d7bd9;">《新浪支付快捷支付协议》</a>
                </li>
                    <li class="ul_last">
                        <span class="pay_sp_l"></span><button type="button" id="binding_pay" class="pay_button">充值</button>
                    </li>
                </ul>

            </div>
        </div>

        <div class="bank_explain">
            <p>* 温馨提示：</p>
            <p>投资有风险，购买需谨慎!</p>
        </div>
    </div>
    
    <script>

        var bindname = "{$member.memberInfo.nameStatus}";
        var bindbank = "{$member.memberInfo.bankStatus}";

        $(function(){
            /*var rec_index = 0;
            $(".rec_b_curr>li").click(function(){
                rec_index = $(this).index();
                $(this).addClass("now").siblings("li").removeClass("now");
                $(".ba_b_con").eq(rec_index).addClass("ba_min_show").siblings(".ba_b_con").removeClass("ba_min_show");
                $(".account_l").height($(".account_r").height());
            })*/
            
            $(".imgcodebtn").click(function(){
                $(".imgcode").click();
            })
        })

        $('#binding_pay').click(function(){
            if(bindname==0){
                return jdbox.alert(0,'请先进行资金托管实名认证并绑定银行卡');
            }
            if(bindbank!=1){
                return jdbox.alert(0,'请先认证银行卡');
            }

            var backcode = $('input[name="backcode"]:checked').val();
            if(!backcode){
                return jdbox.alert(0,'请选择银行');
            }

            var money = $('input[name="money"]').val();
            if(!money || money<0){
                return jdbox.alert(0,'请输入正确的冲值金额');
            }
            var code = $('input[name="code"]').val();
            if(!code){
                return jdbox.alert(0,'请输入验证码');
            }
            jdbox.alert(2);
            $.post('/uajax/ebankpayment', {
               'backcode':backcode, 'money': money,'code':code
            }, function(_result) {
                document.write(_result);
            });

        })

        function showBank(bank){
            var bankId = bank+"_bank";
            $("#"+bankId+"").show().siblings(".pay_banks").hide();
            $("#"+bankId+"").find(".rec_radio").eq(0).click();
        }
    </script>

<include file="Public:accountFooter"/>