<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/password.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/uppwd">登录密码</a></li>
            <li class="now"><a href="/member/paypwd">交易密码</a></li>
        </ul>
    </div>
    <div class="pwd_b">
        <ul class="pwd_ul">

            <notempty name="member['memberInfo']['tradepwd']">

                <li><span class="pwd_sp_l">原始交易密码：</span><input type="password" id="oldpwd"> 如果您还未修改过交易密码,则默认原始交易密码为账户密码</li>
                <li><span class="pwd_sp_l">新交易密码：</span><input type="password" id="newpwd"></li>
                <li><span class="pwd_sp_l">确认交易密码：</span><input type="password" id="cfnewpwd"></li>
                <li><span class="pwd_sp_l">验证码：</span><input type="text" id="code" style="width:112px;">
                    <img src="/public/verifyCode.html" alt="" id="verifyCode" class="imgcode" onclick="refreshverifycode('verifyCode')" alt="验证码">
                    <a href="javascript:;" class="imgcodebtn"></a>
                </li>
                <li class="pwd_last"><span class="pwd_sp_l"></span>
                    <button type="button" id="tradeedit" class="button">确认提交</button>
                    <a href="/member/getpaypwd" class="getpaypwd">忘记交易密码？</a>
                </li>

            <else/>

                <li><span class="pwd_sp_l">交易密码：</span><input type="password" id="pwd"></li>
                <li><span class="pwd_sp_l">确认交易密码：</span><input type="password" id="cfpwd"></li>
                <li><span class="pwd_sp_l">验证码：</span><input type="text" id="code" style="width:112px;">
                    <img src="/public/verifyCode.html" alt="" id="verifyCode" class="imgcode" onclick="refreshverifycode('verifyCode')" alt="验证码">
                    <a href="javascript:;" class="imgcodebtn"></a>
                </li>
                <li class="pwd_last"><span class="pwd_sp_l"></span><button type="button" id="tradesubmit" class="button">确认提交</button><a href="/member/getpaypwd" class="getpaypwd">忘记交易密码？</a></li>

            </notempty>

        </ul>
        <div class="pwd_b_b">
            <p>* 温馨提示：我们将严格对用户的所有资料进行保密</p>
        </div>
    </div>
    <script>
        $(function(){
            $(".imgcodebtn").click(function(){
                $(".imgcode").click();
            })

            $('#tradesubmit').click(function(){
                var newpwd = $('#pwd').val();
                var cfpwd = $('#cfpwd').val();
                if(!newpwd || newpwd.length < 6){
                    return jdbox.alert(0,"交易密码不能少于6位");
                }
                if(cfpwd!=newpwd){
                    return jdbox.alert(0,"确认密码与交易密码不一致");
                }
                var code = $('#code').val();
                if(!code){
                    return jdbox.alert(0,"验证码不能为空");
                }
                $.post('/uajax/tradepwd', {
                    'newpwd': newpwd,cfpwd:cfpwd,code:code
                }, function(_result) {
                    jdbox.alert(_result.status,_result.info);
                    if(_result.status){
                        window.location.reload();
                    }
                }, 'json');
            })
            $('#tradeedit').click(function(){
                var oldpwd = $('#oldpwd').val();
                var newpwd = $('#newpwd').val();
                var cfnewpwd = $('#cfnewpwd').val();

                if(oldpwd==newpwd){
                    return jdbox.alert(0,"旧密码与新密码一致");
                }

                if(!newpwd || newpwd.length < 6){
                    return jdbox.alert(0,"交易密码不能少于6位");
                }
                if(cfnewpwd!=newpwd){
                    return jdbox.alert(0,"确认密码与交易密码不一致");
                }
                var code = $('#code').val();
                if(!code){
                    return jdbox.alert(0,"验证码不能为空");
                }
                $.post('/uajax/tradepwdedit', {
                    'oldpwd': oldpwd, 'newpwd': newpwd,cfnewpwd:cfnewpwd,code:code
                }, function(_result) {
                    jdbox.alert(_result.status,_result.info);
                    if(_result.status){
                        window.location.reload();
                    }
                }, 'json');

            })
        })
    </script>
<include file="Public:accountFooter"/>