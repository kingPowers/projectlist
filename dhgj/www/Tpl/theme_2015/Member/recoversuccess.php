<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">

<div class="content">
    <div class="center reg_center" style="height:562px;">
        <div class="reg_t">
            <span>找回密码</span>
        </div>
        <div class="login_box">
            <ul class="fored_prog">
            </ul>
            <div class="login_b for_b_la">
                <p class="p_finish"><img src="_STATIC_/2015/image/reg_finish.png" alt="">恭喜您密码修改成功!</p>
                <p class="for_p_b"><span id="for_time">5</span>秒后自动跳转到登录页面</p>
                <a href="/member/account" class="for_ck">立即查看</a>
            </div>
        </div>
        <div class="ts_div">
            <font>* 温馨提示：我们将严格对用户的所有资料进行保密</font>
        </div>
    </div>
</div>
<script>
	 var timeSecond = 5;
	 setInterval(function(){
		 timeSecond--;
		 if(timeSecond<=0){
			 location.href = '/member/login';
		 }
		$("#for_time").html(timeSecond);
		},1000);

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

            $.post('/member/changesubmit',{
                'mobile': mobile,
                'smscode': smscode,
                'password':password,
                'repassword':repassword
            }, function(_result) {
                jdbox.alert(_result.status, _result.info);
                if (_result.status) {
                    location.href = '/member';
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
        if(/^1[3|4|5|7|8][0-9]{9}$/.test(mobile)==false){
            return jdbox.alert(0,'请输入正确的手机号');
        }

        jdbox.alert(2);
        var data = {}
        $.post('/public/verifysms.html', {
            mobile:mobile,
            notmember: true
        }, function(result) {
            $('input[name="smscode"]').focus();
            jdbox.alert(1,result.info);
            if (result.status) {
                clearInterval(sendtimmer);
                sendsecond = 119;
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
            sendsecond = 119;
            smsbutton.removeAttr('disabled');
        }
    }
</script>
