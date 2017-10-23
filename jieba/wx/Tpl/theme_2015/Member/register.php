<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/register.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/register.js"></script>
    </head>
    <body onload="ft()" bgcolor="#efefef">
        <div class="maxDiv"><!--大盒子-->
            <div class="headers"><!--头-->
                <div class="rd">
                    <a href="/member/login" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/></a>
                    <span class="fhwz"><a href="/member/login" style="color:white;text-decoration:none;">返回</a></span>
                    <span class="zxwx">注册</span>
                </div>
            </div>
            <div class="centerDiv"><!--中-->

                <div class="center1"></div>
                <div class="center2">
                    <form action="/member/register" method="post" class="register_form">
                        <span class="phone">手 机 号</span>
                        <input value="<notempty name="Think.request.mobile">{$Think.request.mobile}<else/>请输入手机号码</notempty>" onfocus="if(value=='请输入手机号码'){value=''}" onblur="if(value==''){value='请输入手机号码'}" name='mobile' class="input size" />
                        <img src="_STATIC_/2015/member/image/register/solid.png" class="solidImg">
                        <span class="phone2">验 证 码</span>
                        <input type="hidden" name="redirecturl" value="{$redirecturl}">
                        <input value="请输入验证码" onfocus="if (value == '请输入验证码') {
                                    value = ''
                                }" onblur="if (value == '') {
                                            value = '请输入验证码'
                                        }" name='verify_code' class="input2 size" />
                        <input type="button" class="getpassImg size"  value="获取验证码"  />
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

                        <img src="_STATIC_/2015/member/image/register/tosolid.png" class="tosolidImg"><!--
                        <img src="_STATIC_/2015/member/image/register/getpass.png" class="getpassImg">
                        -->                <img src="_STATIC_/2015/member/image/register/solid.png" class="solid2Img">
                        <span class="phone3">密&nbsp;&nbsp;码</span>
                        <input placeholder="6-10位字母、数字结合" type="password"  name='password' class="input3 size" />
                        <input placeholder="6-10位字母、数字结合" type="text"  name='password1' class="input3 size" style='display:none'/>
                        <img src="_STATIC_/2015/member/image/register/eyesb.png" class="eyeImg">
                        <img src="_STATIC_/2015/member/image/register/solid.png" class="solid3Img">
                        <span class="phone4">确认密码</span>
                        <input placeholder="请再次输入密码" type="password"  name='re_password' class="input4 size" />
                        <img src="_STATIC_/2015/member/image/register/solid.png" class="solid4Img">
                        <span class="phone5">好友推荐</span>
                        <input type="hidden" name="agentRecintcode" value="{$_REQUEST['agentRecintcode']}">
                        <input placeholder="好友手机号(可填选)"  name='recintcode'  <notempty name='recintcode.mobile'> value='{$recintcode.mobile}' readonly="readonly"<else/>value=""</notempty>  class="input5 size" />
                        <!--<img src="_STATIC_/2015/member/image/register/see.png" class="seeImg">-->
                        <input name="_register_mobile_" type="hidden" value="{$_register_mobile_}"/>
                        <input name="wx_openid" type="hidden" value="{$wx_openid}"/>
                        <input name="register" type="hidden" value="{$register}"/>
                        <input name="registeragree" type="hidden" value="0"/>
                    </form>
                </div>
                <div class="center3">
                    <input type="checkbox" class="checkbox" name="rigisteragree" id="rigisteragree"  value="1">
                    <span class="phone6">已经同意并阅读<a href="/member/agreement" style="color:#84b9e9;text-decoration:none;">《借吧用户注册协议》</a></span>
                    <img src="_STATIC_/2015/member/image/register/register.png" class="registerImg">
                </div>

            </div>
        </div>
    </body>
    <script type="text/javascript">
        $(function () {
            $(".registerImg").click(function () {
                var img_src = $('.eyeImg').attr('src');//获取没闭眼之前的图片属性
                var img_name = img_src.substr(img_src.lastIndexOf("/") + 1);//获取图片的名字
                var ipt_val = img_name == 'eyesb.png' ? ($("input[name='password']").val()) : ($("input[name='password1']").val());//判断是否要明文
                img_name == 'eyesb.png' ? $("input[name='password']").val(ipt_val).next("input[name='password1']").val(ipt_val) : $("input[name='password']").val(ipt_val).next("input[name='password1']").val(ipt_val);
                var mobile = $("input[name='mobile']").val();
                var verify_code = $("input[name='verify_code']").val();
                var password = $("input[name='password']").val();
                var re_password = $("input[name='re_password']").val();
                var token = $("input[name='_register_mobile_']").val();
                if (false == $('#rigisteragree').prop("checked")) {
                    $("input[name='registeragree']").val(0);
                    alert("请先同意注册协议");
                    return false;
                } else {
                    $("input[name='registeragree']").val(1);
                }
                if (mobile == '' || mobile == '请输入手机号码') {
                    alert("请输入手机号码");
                    return false;
                }
                if (!/^1\d{10}$/.test(mobile)) {
                    alert("请正确输入手机号");
                    return false;
                }
                if (verify_code == '') {
                    alert("请输入手机验证码");
                    return false;
                }
                if (password == '') {
                    alert("请输入密码");
                    return false;
                }
                if (re_password == '') {
                    alert("请输入确认密码");
                    return false;
                }
                if (password != re_password) {
                    alert("两次输入密码不一致");
                    return false;
                }
                $(".register_form").submit();

            });

            $('.getpassImg').click(function () {
                var mobile = $("input[name='mobile']").val();
                var token = $("input[name='_register_mobile_']").val();
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
                    'url': "/member/registerSendSms",
                    "data": {'mobile': mobile, '_register_mobile_': token},
                    success: function (json) {
                       // if(""==json.info || undefined(json.info)){
						//	json.info = "验证码已发送成功";
                       // }
                        alert(json.info);
                        if (json.status == 1) {
                        } else {
                            location.reload();
                        }

                    }

                });

            });

		$('.eyeImg').click(function(){
			var img_src = $(this).attr('src');
			var img_name = img_src.substr(img_src.lastIndexOf("/")+1);
			var img_dir = img_src.substr(0,img_src.lastIndexOf("/")+1);
			var ipt_val = img_name=='eyesb.png'?($("input[name='password']").val()):($("input[name='password1']").val());
			$(this).attr('src',img_dir+(img_name=='eye.png'?"eyesb.png":'eye.png'));
			img_name=='eyesb.png'?$("input[name='password']").val(ipt_val).hide().next("input[name='password1']").val(ipt_val).show():$("input[name='password']").val(ipt_val).show().next("input[name='password1']").val(ipt_val).hide();
		});

	});
</script>
</html>   
{__NOLAYOUT__}