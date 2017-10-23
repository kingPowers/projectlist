<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">
<div class="content">
    <div class="center reg_center">
        <div class="reg_t"><span>账户注册</span></div>
        <ul class="reg_prog">
            <li class="reg_curr"><span>1</span><p>填写账户信息</p></li>
            <li class="reg_curr"><span>2</span><p>手机验证</p></li>
            <li><span>3</span><p>注册成功</p></li>
        </ul>
        <div class="reg_b">
            <form class="ui-form">
                <div class="reg_b_l reg_b_l2">
                    <ul>
                        <li class="reg_mobile">
                            <span class="reg_mobile_bg"></span>
                            <input type="text" mobile="{$data['mobile']}" value="{$data['mobile']}" disabled="disabled" id="mobile" name="mobile"><a id="editmobile" href="/member/register" class="revise_mobile">修改手机号</a>
                        </li>
                        <li class="reg_code">
                            <span class="reg_code_bg"></span>
                            <input type="text" placeholder="请输入手机验证码" rel="验证码不正确" name="smscode" autocomplete="off" class="reg_code_input">
                            <button type="button" class="reg_code_btn login-reg-input" id="smsbutton">重新获取</button>
                        </li>
                        <label class="reg_input_r" style="width: auto;line-height: 30px;"><if condition="$smsmobile">我们已将短信验证码发送至您手机，请注意查收。</if></label>
                        <li class="reg_finish">
                           <button type="button" class="reg_finish_btn">注册完成</button>
                        </li>
                    </ul>
                </div>
                <div class="reg_b_r"></div>
            </form>
        </div>
    </div>
</div>
<script src="_STATIC_/2015/js/login-register.js?v=20150526" type="text/javascript" charset="utf-8"></script>
<script>
    //发验证码操作
    var sendsecond = 59,
        sendtimmer = null,
        smsbutton = $('#smsbutton'),
        defaultHtml = smsbutton.html(),
        mobileobj = $("input[name='mobile']");


    $(function () {
        $('form.ui-form').constract(true);
        smsbutton.click(function() {
            //getSmsCode();
            sendSms();
        });

        sendsecond = 119;
        smsbutton.attr('disabled',"disabled");
        sendtimmer = setInterval('showTimemer()', 1000);

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
        $.post('/public/verifysms.html', {
            notmember: true
        }, function(result) {
            $('.sms_prompt').html(result.info);
            $('input[name="smscode"]').focus();
            jdbox.close();
            $('label.reg_input_r').html(result.info);
            if (result.status) {
                clearInterval(sendtimmer);
                sendsecond = 119;
                smsbutton.attr('disabled',"disabled");
                sendtimmer = setInterval('showTimemer()', 1000);

                //return jdbox.alert(1, result.info);
            } else {
                //return jdbox.alert(0, result.info);
            }
        }, 'json');
    }
    var showTimemer = function() {
        if (sendsecond > 0) {
            smsbutton.html('重新发送（' + sendsecond + '）');
            sendsecond -= 1;
        } else {
            smsbutton.html(defaultHtml);
            clearInterval(sendtimmer);
            sendtimmer = null;
            smsbutton.removeAttr('disabled');
        }
    }
    $(function(){

        $('.reg_finish_btn').click(function(){
            var smscode = $('input[name="smscode"]').val();
            var valid = $('input[name="smscode"]').attr('valid');
            if(!valid){
                jdbox.alert(0,'验证码错误!');
                return false;
            }
            if(!smscode){
                jdbox.alert(0,'验证码不能为空!');
                return false;
            }
            _sending = true;
            $.post('/member/regsubmit', {
                'smscode':smscode
            }, function(_result) {

                _sending = false;
                if (!_result.status) {
                    jdbox.alert(_result.status, _result.info);
                } else {

                    if(_result.data.syn){
                        var jsobj = $(_result.data.syn),mvl = document.createElement('script');
                        mvl.type = 'text/javascript';
                        mvl.async = true;
                        mvl.src = jsobj.attr('src');
                        var oHead = document.getElementsByTagName('HEAD').item(0);
                        oHead.appendChild(mvl);
                    }

                    if(_result.info.url){
                        jdbox.alert(1, _result.info.msg,"location.href ='"+ _result.info.url+"'");
                    }else{
                        location.href ="/member/regok";
                    }

                }
            }, 'json');
        })
        /*
        $(".reg_code_input").keyup(function(){
            e = window.event || e;
            if(e.keyCode = 13){
                $(".reg_code_btn").click();
            }
        })*/
    })
</script>