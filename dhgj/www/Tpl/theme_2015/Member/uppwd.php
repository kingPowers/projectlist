<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/password.css">
<div class="rec_t">
    <ul class="ba_min_nav">
        <li class="now"><a href="/member/uppwd">登录密码</a></li>
        <!-- <li><a href="/member/paypwd">交易密码</a></li> -->
    </ul>
</div>
<div class="pwd_b">
    <ul class="pwd_ul">
        <li><span class="pwd_sp_l"><em>*</em> 原始密码：</span><input type="password" name="pwd"></li>
        <li><span class="pwd_sp_l"><em>*</em> 新密码：</span><input type="password" name="newpwd"></li>
        <li><span class="pwd_sp_l"><em>*</em> 确认密码：</span><input type="password" name="cnfnewpwd"></li>
        <li class="pwd_last"><span class="pwd_sp_l"></span><button type="button" id="submitpwd" class="button">确认提交</button></li>
    </ul>
    <div class="pwd_b_b">
        <p>* 温馨提示：我们将严格对用户的所有资料进行保密</p>
    </div>
</div>

<script>
$(function(){
    $('#submitpwd').click(function(){
        var pwd = $('input[name="pwd"]').val();
        if (pwd.length < 6) {
            jdbox.alert(0,'原始密码不能小于6位');
            return false;
        }
        var newpwd = $('input[name="newpwd"]').val();
        var cnfnewpwd = $('input[name="cnfnewpwd"]').val();
        if (newpwd.length < 6) {
            jdbox.alert(0,'新密码不能小于6位');
            return false;
        }
        if (newpwd!=cnfnewpwd) {
            jdbox.alert(0,'新密码与确认密码不一致');
            return false;
        }

        if(pwd==newpwd){
            jdbox.alert(0,'原始密码与新密码不能相同');
            return false;
        }

        jdbox.alert(2);
        $.post('/member/updatepassword', {
            'pwd': pwd,
            'newpwd': newpwd,
            'cnfnewpwd': cnfnewpwd
        }, function(_result) {
            if (_result.status) {
                jdbox.alert(_result.status,_result.info,"location.reload()");
            }else{
                jdbox.alert(_result.status,_result.info);
            }
        }, 'json');
    })
})
</script>
<include file="Public:accountFooter"/>