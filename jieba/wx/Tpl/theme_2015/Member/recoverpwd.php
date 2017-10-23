<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/recoverpwd.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/recoverpwd.js"></script>
    </head>
    <body onload="ft()" bgcolor="#efefef">
        <div class="maxDiv"><!--大盒子-->
            <div class="headers"><!--头-->
                <div class="rd">
                    <a href="/member/login" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/recoverpwd/return.png" class="retrunImg"/></a>
                    <span class="fhwz"><a href="/member/login" style="color:white;text-decoration:none;">返回</a></span>
                    <span class="zxwx">找回密码</span>
                </div>
            </div>
            <div class="centerDiv"><!--中-->
                <div class="center1"></div>
                <div class="center2">
                    <form action="recoverPwd" method="post" class="recoverPwdForm">
                        <span class="phone">手 机 号</span>
                        <input value="<notempty name="Think.request.mobile">{$Think.request.mobile}<else/>请输入手机号码</notempty>" onfocus="if(value=='请输入手机号码'){value=''}" onblur="if(value==''){value='请输入手机号码'}" name='mobile' class="input srsize" />
                        <img src="_STATIC_/2015/member/image/recoverpwd/solid.png" class="solidImg">
                        <span class="phone2">手机验证码</span>
                        <input value="请输入验证码" name='verify_code' onfocus="if (value == '请输入验证码') {
                                      value = ''
                                  }" onblur="if (value == '') {
                                              value = '请输入验证码'
                                          }" class="input2 srsize" />
<!--                          <img src="_STATIC_/2015/member/image/recoverpwd/getpass.png" class="getpassImg">-->
                        <input type="button" class="getpassImg sr1size"  value="获取验证码"/>
                        <script type="text/javascript">
                            var clock = '';
                            var nums = 60;
                            var btn;
                            function sendCode(thisBtn)
                            {
                                btn = thisBtn;
                                btn.disabled = true;
                                btn.style.color = "#cccccc";
                                btn.value = "重新发送(" + nums + ")";
                                clock = setInterval(doLoop, 1000);
                            }
                            function doLoop()
                            {
                                nums--;
                                if (nums > 0) {
                                    btn.value = "重新发送(" + nums + ")";
                                } else {
                                    clearInterval(clock);
                                    btn.disabled = false;
                                    btn.value = '获取验证码';
                                    nums = 60;
                                    btn.style.color = "#3880e7";
                                }
                            }
                        </script>
                        <input type="hidden" name='_recoverpwd_mobile_' value='{$_recoverpwd_mobile_}'/>
                    </form>
                </div>
                <div class='notice' style="color:red;"></div>
                <notempty name='error'><script type="text/javascript">alert("{$error}");</script></notempty>
                <div class="center3">
                    <img src="_STATIC_/2015/member/image/recoverpwd/next.png" class="nextImg">
                </div>

            </div>


        </div>
    </body>
    <script type="text/javascript">
        $(function () {
            $('.getpassImg').click(function () {
                var mobile = $("input[name='mobile']").val();
                var token = $("input[name='_recoverpwd_mobile_']").val();
                if (mobile == '' || mobile == '请输入手机号码') {
                    alert("请输入手机号码");
                    return false;
                }
                if (!/^1\d{10}$/.test(mobile)) {
                    alert("请正确输入手机号");
                    return false;
                }
                sendCode(this);
                $.ajax({
                    'type': 'post',
                    'dataType': 'json',
                    'url': "/member/recoverPwdSendSms",
                    "data": {'mobile': mobile, '_recoverpwd_mobile_': token},
                    success: function (json) {
                        alert(json.info);
                        if (json.status == 1) {
                        } else {
                            location.reload();
                        }

                    },
                });
            });

		$(".nextImg").click(function(){
			var mobile = $("input[name='mobile']").val();
			var verify_code = $("input[name='verify_code']").val();
			if(mobile=='' ||mobile=='请输入手机号码'){
				alert("请输入手机号码");
				return false;
			}
			if(!/^1\d{10}$/.test(mobile)){
				alert("请正确输入手机号");
				return false;
			}
			if(verify_code=='' ||verify_code=='请输入验证码'){
				alert("请输入验证码");
				return false;
			}
			$('.notice').css("color","orange").html("请稍后……");
			$('.recoverPwdForm').submit();
		});
	});
</script>
</html>   
{__NOLAYOUT__}