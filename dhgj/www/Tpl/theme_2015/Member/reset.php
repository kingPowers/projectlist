<include file="Public:accountMenu"/>
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
                            <input name="mobile" id="mobilenumber" autocomplete="off" type="text" class="input" disabled="" value="{$mobile}" style="background: #fff;border: 0px">
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
                        	<input name="_reset_mobile_" type="hidden" value="{$_reset_mobile_}"/>
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
<include file="Public:accountFooter"/>