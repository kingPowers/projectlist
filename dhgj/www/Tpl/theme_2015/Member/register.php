<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">
<div class="content">
    <div class="center reg_center"> 
        <ul class="reg_prog">            
        </ul>
        <div class="reg_b">
            <form class="ui-form">
                <input type="hidden" name="channel" class="login-reg-input" value="{$channel}" />
                <div class="reg_b_l">
                    <ul>
                        <li>
                            <div class="reg_input_l"><span>* </span>手机号码：<input type="text" value="{$data['mobile']}" class="login-reg-input" name="mobile" rel="手机号不正确" placeholder="请输入手机号码" autocomplete="off"></div>
                            <label class="reg_input_r"> 请输入正确的手机号</label>
                        </li>
                        <li>
                            <div class="reg_input_l"><span>* </span>登录密码：<input type="password"  class="login-reg-input" name="password" rel="密码不正确" placeholder="请输入密码" autocomplete="off"></div>
                            <label class="reg_input_r"> 6-16个字符的组合密码</label>
                        </li>
                        <li>
                            <div class="reg_input_l"><span>* </span>确认密码：<input type="password" class="login-reg-input" name="re_password" rel="请再次输入密码" placeholder="请再次输入密码" autocomplete="off"></div>
                            <label class="reg_input_r"> 请再次输入密码</label>
                        </li>
                        <li style="padding-left:14px;">
                            <div class="reg_input_l"><span>* </span>验证码：<input type="text" class="login-reg-input" name="verify_code" placeholder="请输入验证码" rel="请输入验证码" style="width:128px;" autocomplete="off"></div>
                            <span style="float:left;" alt="" id="verifyCode" class="reg_code getpassImg">点击获取验证码</span>
                            <label class="reg_input_r"></label>
                        </li>
                        <li class="check">
                            <div class="c_left"><input type="checkbox" class="checkbox" name="rigisteragree" id="rigisteragree"  checked value="1"> 同意注册协议<font>(请仔细阅读条款)</font></div>
                            <label class="reg_input_r" style="float: left;width: 150px;line-height: 40px;"></label>
                        </li>
                         <input name="_register_mobile_" type="hidden" value="{$_register_mobile_}"/>
                        <li class="reg_btn"><button type="button" class="reg_button">同意协议并注册</button></li>
                    </ul>
                </div>               
            </form>
        </div>
    </div>
</div>
<script>
    /*$(".reg_button").click(function(){
         var chk = document.getElementById('rigisteragree');
        if(chk.checked){
            // 提交
        }else{
            // 不提交
           alert("请阅读注册协议");
        }
    });*/
	//发送验证码
	var isSend = 0;
    $('.getpassImg').click(function () {
        if(isSend==1)return false;
        isSend = 1;
        var _this = this;
        var mobile = $("input[name='mobile']").val();
        var token = $("input[name='_register_mobile_']").val();
        if (mobile == '' || mobile == '请输入手机号码') {
            alert("请输入手机号码");
            isSend = 0;
            return false;
        }
        if (!/^1\d{10}$/.test(mobile)) {
            alert("请正确输入手机号");
            isSend = 0;
            return false;
        }
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': "/member/registerSendSms",
            "data": {'mobile': mobile, '_register_mobile_': token},
            success: function (json) {
                alert(json.info);
                if (json.status == 1) {
                    //倒计时
                	sendCode(_this);
                } else {
                	isSend = 0;
                    location.reload();
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
$(function() {
    $('form.ui-form').constract(true);
    $('form.ui-form button.reg_button').submitfrom($('form.ui-form'));
    $(".reg_p_tj>.reg_tj_pbox").click(function(){
        $(this).parent("li").toggleClass("re_p_tjshow");
        $(this).parent("li").next(".reg_recom").toggle();
    })
    
    $(".login-reg-input").keypress(function(e){
        e = window.event || e;
        if(e.keyCode == 13){
            $(".reg_button").click();
        }
    })
})
</script>
<script src="_STATIC_/2015/js/login-register.js?v=20150526" type="text/javascript" charset="utf-8"></script>