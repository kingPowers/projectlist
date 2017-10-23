<link rel="stylesheet" href="_STATIC_/2015/css/reg.css">

<div class="content">
    <form class="ui-form">
    <div class="center login_bg login_center">       
        <div class="login_r">
            <h2>欢迎登录贷后管家</h2>                
            <ul>
                <li class="login_name"><img src="_STATIC_/2015/image/icon_name.png">
                <input type="hidden" name="redirecturl" value="{$redirecturl}">
                <input type="text" class="login-reg-input" autocomplete="off" placeholder="请输入您的手机号码" name="mobile" rel="用户名不正确"></li>
                <li class="login_psd"><img src="_STATIC_/2015/image/icon_pwd.png"><input class="login-reg-input" type="password" autocomplete="off" placeholder="请输入您的登录密码" name="password" rel="密码不正确"></li>
                <li class="login_lg"><a href="/member/recoverpwd">忘记密码？</a></li>
                <button type="button" class="login_btn">登录</button>
                <li><p class="login_p">还没有加入贷后管家？<a href="/member/register">立即注册</a></p></li>
            </ul>
        </div>        
    </div>
    </form>{__TOKEN__}
</div>
<script language="javascript">
    $(function(){
        if(top.location.href != location.href){
            parent.location.reload();
        }
        $('form.ui-form').constract(false);
        $('form.ui-form button.login_btn').submitfrom($('form.ui-form'));
        
        $(".login-reg-input").keyup(function(e){
            e = window.event || e;
            if(e.keyCode == 13){
                $(".login_btn").click();
            }
        })
    });
</script>
<script src="_STATIC_/2015/js/login-register.js?v=20150526" type="text/javascript" charset="utf-8"></script>