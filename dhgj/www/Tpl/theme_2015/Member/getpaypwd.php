<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/password.css">
    <style>
        .pwd_ul{padding-left:240px;}
    </style>
    <div class="rec_t">
        <h5 class="pwd_h5">找回交易密码</h5>
        <ul class="reg_prog for_prog">
            <li class="reg_curr"><span>1</span><p>重置密码</p></li>
            <li><span>2</span><p>找回密码完成</p></li>
        </ul>
    </div>

    <ul class="pwd_ul">
        <li><span class="pwd_sp_l"><em>*</em>手机验证码：</span><input type="text" style="width:112px;" name="smscode" placeholder="请输入手机验证码"><button type="button" class="get_btn" id="smsbutton">获取验证码</button></li>
        <li><span class="pwd_sp_l"><em>*</em>新密码：</span><input type="password" name="password" placeholder="请输入6-18位密码"></li>
        <li><span class="pwd_sp_l"><em>*</em>确认新密码：</span><input type="password" name="repassword" placeholder="和上面输入一致"></li>
        <li class="pwd_last"><span class="pwd_sp_l"></span><button type="button" class="button forgotpwd">确 定</button</li>
    </ul>

    <!--<div class="for_b_la">
        <p class="p_finish"><img src="_STATIC_/2015/image/reg_finish.png" alt="">恭喜您交易密码修改成功!</p>
        <p class="for_p_b"><span id="for_time">3</span>秒后自动跳转原页面</p>
        <a href="/member/paypwd" class="for_ck">立即返回</a>
    </div>-->
    <div class="pwd_b">
        <div class="pwd_b_b">
            <p>* 温馨提示：我们将严格对用户的所有资料进行保密</p>
        </div>
    </div>
    
    <!--<script>
        var for_time = 3;
        setInterval(function(){
            for_time--;
            $("#for_time").text(for_time);
            if(for_time == 0){
                window.location = 'login.html';
                return false;
            }
        },1000)
    </script>-->

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
        $('.forgotpwd').click(function () {

            var smscode = $('input[name="smscode"]').val();
            if (smscode.length < 4) {
                return jdbox.alert(0, '请输入手机验证码');
            }

            var password = $('input[name="password"]').val();
            if (password.length < 6) {
                return jdbox.alert(0, '请输入正确的交易密码');
            }

            var repassword = $('input[name="repassword"]').val();
            if (password != repassword) {
                return jdbox.alert(0, '交易密码与确认密码必须一致');
            }
            jdbox.alert(2);

            $.post('/uajax/forgettradepwd', {
                'smscode': smscode,
                'password': password,
                'repassword': repassword
            }, function (_result) {
                jdbox.alert(_result.status, _result.info);
                if (_result.status) {
                    location.href = '/member/account';
                }
            }, 'json');

        });
    })

    var getSmsCode = function() {
        if (sendtimmer != null) {
            return false;
        }
        /*var val = mobileobj.val();
         if (val.length != 11 || val.indexOf(1) > 0) {
         $('.'+mobileobj.attr('name') +'_error').html('<i class="ui-icon fail"></i>'+ 手机号码格式不正确);
         //mobileobj.parent().find('label.labelwp').removeClass('valid').addClass('error').html('<i class="ui-icon fail"></i>手机号码格式不正确');
         return false;
         }
         if(parseInt(mobileobj.attr('valid')) != 1){
         return false;
         }
         box_verify('', 'top.sendSms');
         */
    }
    var sendSms = function() {
        jdbox.alert(2);
        var data = {}
        $.post('/member/verifysms.html', {
            trade: true
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
<include file="Public:accountFooter"/>
