<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">

<div class="content">
    <div class="center reg_center" style="height:562px;">
        <div class="reg_t">
            <span>找回密码</span>
        </div>
        <div class="login_box">
            <ul class="for_prog">
            </ul>
            <div class="login_b">
                <form action="">
                    <ul class="login_b_ul">
                        <li>
                            <span><em>*</em> 手机号码：</span>
                            <input name="mobile" id="mobilenumber" autocomplete="off" type="text" placeholder="请输入您的手机号" class="input" value="">
                        </li>
                        <li>
                            <span><em>*</em> 手机验证码：</span>
                            <input name="smscode" type="text" autocomplete="off" placeholder="请输入手机验证码" value="" class="obtain_ipt">
                            <button type="button" class="obtain" id="smsbutton">获取验证码</button>
                        </li>
                        <li>
                            <span><em>*</em> 新密码：</span>
                            <input type="password" name="password" placeholder="请输入6-18位密码" class="input" value="">
                        </li>
                        <li>
                            <span><em>*</em> 确认密码：</span>
                            <input type="password" name="repassword" placeholder="和上面输入一致" class="input" value="">
                        </li>
                        <li class="last">
                        	<input name="_recoverpwd_mobile_" type="hidden" value="{$_recoverpwd_mobile_}"/>
                            <button type="button" class="for_next forgotpwd">下一步</button>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
        <div class="ts_div">
            <font>* 温馨提示：我们将严格对用户的所有资料进行保密</font>
        </div>
    </div>
</div>
<script>
    //发验证码操作
    var sendsecond = 120,
        sendtimmer = null,
        smsbutton = $('#smsbutton'),
        defaultHtml = smsbutton.html(),
        mobileobj = $("input[name='mobile']");

    smsbutton.click(function() {
        //getSmsCode();
        sendSms();
    });

    $(function(){
        $('.forgotpwd').click(function(){
            var mobile = $('#mobilenumber').val();
            if(/^1[3|4|5|7|8][0-9]{9}$/.test(mobile)==false){
                return jdbox.alert(0,'请输入正确的手机号');
            }

            var smscode = $('input[name="smscode"]').val();
            if(smscode.length < 4){
                return jdbox.alert(0,'请输入手机验证码');
            }

            var password = $('input[name="password"]').val();
            if(password.length < 6){
                return jdbox.alert(0,'请输入正确的密码');
            }

            var repassword = $('input[name="repassword"]').val();
            if(password != repassword){
                return jdbox.alert(0,'密码与确认密码必须一致');
            }
            jdbox.alert(2);

            $.post('/member/recoverPwd',{
                'mobile': mobile,
                'smscode': smscode,
                'password':password,
                'repassword':repassword
            }, function(_result) {
                if (_result.status) {
                    location.href = '/member/recoversuccess';
                }else{
                	jdbox.alert(_result.status, _result.info);
                }
            }, 'json');
        });
    })

    var getSmsCode = function() {
        if (sendtimmer != null) {
            return false;
        }       
    }
    var sendSms = function() {
        var mobile = $('#mobilenumber').val();
        var token = $("input[name='_recoverpwd_mobile_']").val();
        if(/^1[3|4|5|7|8][0-9]{9}$/.test(mobile)==false){
            return jdbox.alert(0,'请输入正确的手机号');
        }

        //jdbox.alert(2);
        var data = {}
        $.post('/member/recoverPwdSendSms.html', {
            mobile:mobile,
            '_recoverpwd_mobile_': token
        }, function(result) {
            $('input[name="smscode"]').focus();
            jdbox.alert(1,result.info);
            if (result.status) {
                clearInterval(sendtimmer);
                sendsecond = 60;
                smsbutton.attr('disabled',"disabled");
                sendtimmer = setInterval('showTimemer()', 1000);
                // return jdbox.alert(1, result.info);
            } else {
                //$("#smsbutton").attr('disabled',"disabled");
                //sendtimmer = setInterval('showTimemer()', 1000);
                //return jdbox.alert(0, result.info);
            }
        }, 'json');
    }
    var showTimemer = function() {
        if (sendsecond > 0) {
            smsbutton.html('重新发送 ' + sendsecond + '');
            sendsecond -= 1;
        } else {
            smsbutton.html('获取验证码');
            clearInterval(sendtimmer);
            sendtimmer = null;
            sendsecond = 60;
            smsbutton.removeAttr('disabled');
        }
    }
</script>
