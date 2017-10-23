<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">
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
            <li class="now">快捷支付</li>
            <li><a href="/member/rechargezc">网银支付</a></li>
        </ul>
        <div class="ba_b_con fast_pay ba_min_show">
            <ul class="pay_ul">
                <li><span class="pay_sp_l">充值银行：</span><span>{$member.memberInfo.bindBank}</span></li>
                <li><span class="pay_sp_l">银行卡号：</span><span>{$member.memberInfo.bindBankNum|substr=0,5}****{$member.memberInfo.bindBankNum|substr=12}</span></li>
                <li><span class="pay_sp_l">持卡人姓名：</span><span>{$member.memberInfo.names|hide_username}</span></li>
                <li><span class="pay_sp_l">身份证号：</span><span>{$member.memberInfo.certiNumber|substr=0,5}****{$member.memberInfo.certiNumber|substr=12}</span></li>
            </ul>
            <ul class="pay_ul">
                <li><span class="pay_sp_l">充值金额：</span><input type="text" name="money" autocomplete="off"> 元</li>
				
                <!--  <li id="sinacode"><span class="pay_sp_l"><em>*</em> 新浪验证码：</span><input autocomplete="off" type="text" style="width:112px;" class="zh_input" name="valid_code" id="valid_code">
                    <button type="button" class="car_btn sina_btn">获取验证码</button>
                    <span id="sinamsg">请输入新浪发送至您手机短信验证码</span>
                </li>
                <li>
                    <input type="checkbox"  style="margin-left:50px;float:left;width:20px;border:1px solid red;" name="rigisteragree"  value="1"></input>我已阅读并同意<a href="/about/userprotocal" style="color:#4d7bd9;">《新浪支付快捷支付协议》</a>
                </li>-->
                <li class="ul_last">
                    <span class="pay_sp_l"></span><button type="button" id="binding_pay" class="pay_button">充值</button>
                </li>
            </ul>
        </div>
        
        
        <div class="bank_box">
            <table class="bank_table">
                <tr>
                    <th style="width:200px;">银行（借记卡）</th>
                    <th style="width:200px;">首次绑定支付（每笔）</th>
                    <th style="width:200px;">已绑定支付（每笔）</th>
                    <th style="width:200px;">日最高支付</th>
                </tr>
                <tr>
                <if condition="$bankPays">
                    <td>{$bankPays['bankname']}</td>
                    <td>{$bankPays['firstPay']|number_format=0} 元</td>
                    <td>{$bankPays['bindingPay']|number_format=0} 元</td>
                    <td>{$bankPays['maxPay']|number_format=0} 元</td>
                <else />
                    <td>{$member.memberInfo.bindBank}</td>
                    <td>不支持</td>
                    <td>不支持</td>
                    <td>不支持</td>
                </if>
                </tr>
            </table>
        </div>

        <div class="bank_explain">
            <p>* 温馨提示：</p>
            <p>投资有风险，购买需谨慎!</p>
<!--            <p>2、线上、线下充值手续费全免，充值金额由第三方平台收取。</p>
            <p>3、请注意您的银行卡充值限制，以免造成不便。</p>
            <p>4、如果充值金额没有及时到账，请与客服联系。（线下充值客户请务必通知客服）</p>
            <p>5、吉祥果平台禁止信用卡套现、虚假交易等行为，一经发现将予以处罚，包括但不限于:限制收款、冻结账户，永久停止服务等，并有可能影响相关信用记录。</p> -->
        </div>
    </div>

    <script>
        var sendtimmer = null;
        var sendsecond = 120;
        var smsbutton = $('.sina_btn');
        var bindname = "{$member.memberInfo.nameStatus}";
        var bindbank = "{$member.memberInfo.bankStatus}";

        if(bindbank == 0){
            alert('请先绑定银行卡');
            location.href="/member/bankacc";
        }

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

            var money = $('input[name="money"]').val();
            if(!money || money<0){
                return jdbox.alert(0,'请输入正确的充值金额');
            }
            
           /* var valid_code = $('#valid_code').val();
            if(!valid_code){
                jdbox.alert(0,'验证码不能为空');
                return false;
            }*/
            
            $.post('/uajax/quickpayment',{'money': money},function(R){
                $(R.data.data).appendTo('body');
            },'json');

        })

        $('.sina_btn').click(function(){

            if(bindname==0){
                return jdbox.alert(0,'请先进行资金托管实名认证并绑定银行卡');
            }
            if(bindbank!=1){
                return jdbox.alert(0,'请先认证银行卡');
            }

            var money = $('input[name="money"]').val();
            if(!money || money<0){
                return jdbox.alert(0,'请输入正确的冲值金额');
            }

            jdbox.alert(2);
            $.post('/uajax/quickpayment', {
                'money': money
            }, function(_result) {
                if(!_result.status){
                    return jdbox.alert(_result.status,_result.info);
                }else{
                    jdbox.close();
                    $('#valid_code').focus();
                    $('#sinamsg').show();
                    //$('#sinacode').show();
                    //$('#binding_pay').css('background-color','#999');
                    //$('#binding_pay').attr("disabled",'disabled');
                    clearInterval(sendtimmer);
                    sendsecond = 119;
                    smsbutton.attr('disabled',"disabled");
                    sendtimmer = setInterval('showTimemer()', 1000);

                }
            }, 'json');


        })
        var showTimemer = function() {
            if (sendsecond > 0) {
                smsbutton.html('重新发送 ' + sendsecond + '');
                sendsecond -= 1;
            } else {
                smsbutton.html('获取验证码');
                clearInterval(sendtimmer);
                sendtimmer = null;
                sendsecond = 119;
                smsbutton.removeAttr('disabled');
            }
        }

        function showBank(bank){
            var bankId = bank+"_bank";
            $("#"+bankId+"").show().siblings(".pay_banks").hide();
            $("#"+bankId+"").find(".rec_radio").eq(0).click();
        }
    </script>

<include file="Public:accountFooter"/>