<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/identify.css">
    <div class="user_info">
        <div class="user_blank c_left">
            <img src="_STATIC_/2015/member/image/user_head.png" alt="头像">
        </div>
        <div class="id_mess c_left">
            <ul>
                <li>
                    <span class="id_label">用户名：</span><p>{$member.username}</p><a href="javascript:id_divshow('id_infom');">修改</a>
                </li>
                <li class="id_li_r">
                    <span class="id_label">姓&nbsp;&nbsp;名：</span><p>{$member.memberInfo.names|hide_username}</p><a href="javascript:id_divshow('id_name');">查看</a>
                </li>
                <li>
                    <span class="id_label">手&nbsp;&nbsp;机：</span><p>{$member['mobile']|substr=0,3}****{$member['mobile']|substr=7}</p><a href="/member/upphonenum" class="mobile_re">修改</a>
                </li>
                <li class="id_li_r">
                    <if condition="$member['memberInfo']['bankStatus'] eq 1">
                        <span class="id_label">银行卡：</span><p>银行卡已绑定</p><a href="/member/bankacc">点击查看</a>
                    <else/>
                        <span class="id_label">银行卡：</span><p>尚未绑定银行卡</p><a href="/member/bankacc">立即绑定</a>
                    </if>
                </li>
                <li>
                    <if condition="$member['memberInfo']['emailStatus'] eq 1">
                        <span class="id_label">邮&nbsp;&nbsp;箱：</span><p>{$member['memberInfo']['email']|substr=0,3}****</p><a href="javascript:id_divshow('id_email');">修改</a>
                    <else/>
                        <span class="id_label">邮&nbsp;&nbsp;箱：</span><p>未填写</p><a href="javascript:id_divshow('id_email');">立即认证</a>
                    </if>
                </li>
                <li>
                    <span class="id_label" >
                        <if condition="$apply_status eq '0'">
                            <a>借款人账号已禁用</a>
                        <elseif condition="$apply_status eq '1'"/>
                            <a>申请成功，请等待客服联系您！</a>
                        <elseif condition="$apply_status eq '2'"/>
                            <a>借款人账号审核通过</a>
                        <else/>
                            <a id="apply_submit">点击申请成为借款人</a>
                        </if>
                        <div hidden="hidden" id="hideen_val">{$member.id}</div>
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class="id_b">
        <ul>
            <!--修改用户名-->
            <li class="id_list">
                <h3 class="id_h3">
                    <span class="id_ok">用户信息</span>
                    <p>用户名只能修改一次</p>
                    <a href="javascript:;"><span>点击修改</span><span class="id_r_up"></span></a>
                </h3>
                <div class="id_div id_infom">
                    <if condition="$member['username'] eq $member['mobile']">
                        <p><span class="id_sp_l">用户名：</span><input type="text" id="nickname" value="{$member['username']}" class="input"><span>（用户名不能重复，一旦提交不可修改）</span></p>
                        <button type="button" class="id_btn" id="namesubmit">提交保存</button>
                    <else/>
                        <p><span class="id_sp_l">用户名：</span> {$member['username']} </p>
                    </if>
                </div>
            </li>
            
            <!--实名认证-->
            <li class="id_list">
                <h3 class="id_h3">
                    <if condition="$member['memberInfo']['nameStatus'] eq 1">
                        <span class="id_ok">实名认证</span>
                        <p>您已完成实名认证</p>
                    <else/>
                        <span>实名认证</span>
                        <p>您尚未完成实名认证</p>
                    </if>
                    <a href="javascript:;"><span>立即认证</span><span class="id_r_up"></span></a>
                </h3>
                <div class="id_div id_name">
                   <!--实名认证已提交-->
                    <!---->
                    <if condition="$member['memberInfo']['nameStatus'] eq 1">
                        <div class="name_ok">
                            <p class="name_ok_p">您的实名认证已提交</p>
                            <ul class="name_ok_ul">
                                <li><span class="name_sp_l">真实姓名：</span><span>{$member.memberInfo.names|hide_username}</span></li>
<!--                                <li><span class="name_sp_l">性别：</span><span><if condition="$member['memberInfo']['sex'] eq 1">男<else/>女</if></span></li>-->
                                <li><span class="name_sp_l">身份证：</span><span>{$member.memberInfo.certiNumber|substr=0,5}****{$member.memberInfo.certiNumber|substr=12}</span></li>
                            </ul>
                        </div>
                    <else/>
                        <!--实名认证-->
                        <div class="name_bind">
                            <ul class="name_bind_ul">
                                <li><span class="bind_sp_l"><em>*</em> 真实姓名：</span><input type="text" id="name_input" class="input"></li>
                                <li><span class="bind_sp_l"><em>*</em> 性别：</span>
                                    <input type="radio" name="sex" value="1" class="inp_rad" checked="cheched"> 男&nbsp;
                                    <input type="radio" name="sex" value="2" class="inp_rad"> 女
                                </li>
                                <li><span class="bind_sp_l"><em>*</em> 身份证号：</span><input type="text" id="certinum_input" class="input"></li>
                                <li class="name_last"><button type="button" id="identifysubmit" class="id_btn">提交保存</button></li>
                            </ul>
                            <div class="name_hint">
                                <p>* 温馨提示</p>
                                <p>1、一旦通过实名认证以上信息将不能修改</p>
                                <p>2、我们将严格对用户的所有资料进行保密</p>
                            </div>
                        </div>
                    </if>
                </div>
            </li>
            
            <!--手机认证-->
            <li class="id_list id_mobile">
                <h3 class="id_h3">
                    <span class="id_ok">手机认证</span>
                    <p style="line-height:18px;padding-top:12px;" id="mobile">您的手机已通过认证，认证的手机号码为：{$member['mobile']|substr=0,3}****{$member['mobile']|substr=7}温馨提示：若原手机丢失或停用，修改时，请联系客服。</p>
                </h3>
            </li>

            <!--邮箱认证-->
            <li class="id_list">
                <h3 class="id_h3">
                    <if condition="$member['memberInfo']['emailStatus'] eq 1">
                        <span class="id_ok">邮箱认证</span>
                        <p>您的邮箱已通过认证</p>
                        <a href="javascript:;"><span>点击查看</span><span class="id_r_up"></span></a>
                    <else/>
                        <span>邮箱认证</span>
                        <p>您的邮箱还没有通过认证</p>
                        <a href="javascript:;"><span>点击认证</span><span class="id_r_up"></span></a>
                    </if>
                </h3>
                
                <div class="id_div id_email">

                    <if condition="$member['memberInfo']['emailStatus'] eq 1">
                        <!--邮箱已通过验证-->
                        <div class="email_ok">
                            <p class="email_ok_t">您的邮箱已通过认证：</p>
                            <p class="email_ok_b">{$member['memberInfo']['email']|substr=0,3}****{$member['memberInfo']['email']|substr=7}</p>
                            <!--<button type="button" class="id_btn" id="ditemail">修改邮箱</button>-->
                        </div>
                        <!--修改邮箱-->
                        <div class="email_revise" style="display:none;">
                            <ul class="email_revise_ul">
                                <li><span class="revise_l">* 请输入原邮箱：</span><input type="text" class="input"></li>
                                <li><span class="revise_l">* 请输入新邮箱：</span><input type="text" class="input"></li>
                                <li><span class="revise_l">* 请输入验证码：</span><input type="text" class="input" style="width:70px;"><img src="_STATIC_/2015/member/image/test_code.png" alt="点击刷新" class="imgcode"> <a href="javascript:;" class="imgcodebtn"></a></li>
                                <li style="padding-left:95px;"><span>请输入正确的原邮箱地址</span></li>
                            </ul>
                            <button type="button" class="id_btn">提交保存</button>
                        </div>

                        <!--修改邮箱完成-->
                        <!--<div class="email_ok">
                            <p class="email_ok_t">您的邮箱已修改完成</p>
                        </div>-->
                    <else/>
                        <!--邮箱认证-->
                        <div class="email_no">
                            <p class="email_no_t">您的邮箱还没通过认证：</p>
                            <p class="email_no_b">您的邮箱：<input type="text" class="input" name="email"></p>
                            <button type="button" id="emailvalidation" class="id_btn">点击发送激活邮件</button>
                        </div>
                    </if>
                </div>
            </li>

            <!--银行卡绑定-->
            <li class="id_list">
                <h3 class="id_h3">
                    <if condition="$member['memberInfo']['bankStatus'] eq 1">
                        <span class="id_ok">银行卡</span>
                        <p>银行卡已绑定</p>
                        <a href="/member/bankacc"><span>点击查看</span><span class="id_r_up"></span></a>
                    <else/>
                        <span>银行卡</span>
                        <p>您尚未绑定银行卡，提现之前必须绑定</p>
                        <a href="/member/bankacc"><span>点击绑定</span><span class="id_r_up"></span></a>
                    </if>
                </h3>
            </li>
            <!--交易密码修改-->
           <!--  <li class="id_list">
                <h3 class="id_h3">
                    <eq name="member['memberInfo']['tradepwd']" value="$member['password']">
                        <span>交易密码</span>
                        <p>您的交易密码未设置，初始密码为登录密码，请尽快修改。</p>
                        <a href="/member/paypwd" style="width:90px;"><span>设置交易密码</span><span class="id_r_up"></span></a>
                    <else/>
                        <span class="id_ok">交易密码</span>
                        <p>您的交易码已设置</p>
                        <a href="/member/paypwd" style="width:90px;"><span>设置交易密码</span><span class="id_r_up"></span></a>
                    </eq>
                </h3>
            </li> -->
            <li class="id_list">
                <h3 class="id_h3">
                	<span>收银台交易密码</span>
                	<if condition="$member['memberInfo']['bankStatus'] eq 1">
                		<if condition="$is_set_paypass eq N">
                        <p>您的交易密码未设置</p>
                        <a id="set_sina_password" style="width:90px;"><span>设置交易密码</span><span class="id_r_up"></span></a><!--href="/Uajax/tradepwdsina"-->
	                    <else/>
	                        <span class="id_ok">交易密码</span>
	                        <p style="width: 200px;">您的交易码已设置</p>
	                        <a  id="modify_sina_password" style="width:90px;"><span>修改交易密码</span><span class="id_r_up"></span></a>
	                        <a  id="find_sina_password" style="width:90px;"><span>忘记交易密码</span><span class="id_r_up"></span></a>
	                    </if>
	                    <else/>
	                    <p>您尚未绑定银行卡，提现之前必须绑定
                        	<a href="/member/bankacc">,点击绑定</a>
                        </p>
                	</if>
                    
                </h3>
            </li>
        </ul>
    </div>
    <script>
        $(function(){
            $('#set_sina_password').click(function(){
                var query = $(this);
                var param = '';
                top.jdbox.alert(2);
                $.post('/Uajax/tradepwdsina/',param,function(result){
                    top.jdbox.close();
                    if(result.status == 1){
                        //query.parent().text(result.info);
                        window.location.href = result.info.redirect_url;
                    }else{
                        return alert(result.info);
                    }
                },'json');
            });
            $('#modify_sina_password').click(function(){
                var query = $(this);
                var param = '';
                top.jdbox.alert(2);
                $.post('/Uajax/modifySinaPwd/',param,function(result){
                    top.jdbox.close();
                    if(result.status == 1){
                        //query.parent().text(result.info);
                        window.location.href = result.info.redirect_url;
                    }else{
                        return alert(result.info);
                    }
                },'json');
            });

            $('#find_sina_password').click(function(){
                var query = $(this);
                var param = '';
                top.jdbox.alert(2);
                $.post('/Uajax/findSinaPwd/',param,function(result){
                    top.jdbox.close();
                    if(result.status == 1){
                        //query.parent().text(result.info);
                        window.location.href = result.info.redirect_url;
                    }else{
                        return alert(result.info);
                    }
                },'json');
            });
            $(".imgcodebtn").click(function(){
                $(".imgcode").click();
            })
            
            $(".id_h3 a").click(function(){
                $(this).parents(".id_list").children(".id_div").toggleClass("id_divshow");
                $(this).parents(".id_list").siblings(".id_list").children(".id_div").removeClass("id_divshow");
                $(".email_ok").show();
                $(".email_revise").hide();
            })
            
            $(".mobile_re").click(function(){
                $("body,html").animate({scrollTop:($(".id_mobile").offset().top-10)});
            })

            //修改用户名
            $('#namesubmit').click(function(){
                var nickname = $('#nickname').val();
                if(nickname.length<2){
                    return alert('用户名长度不正确');
                }

                $.post('/member/usernameSubmit.html',{nickname:$('#nickname').val()},function(result){
                    if(result.status == 1){
                        alert(result.info);
                        window.location.reload();
                    }else{
                        alert(result.info);
                    }
                },'json');
            })

            //身份证绑定
            $('#identifysubmit').click(function(){
                var data = {};
                var name_val = $('#name_input').val();
                var certinum_val = $('#certinum_input').val();
                var sex = $('input[name="sex"]').val();
                if(name_val.length<2){
                    return alert('姓名长度不正确');
                }
                $(data).attr('name',name_val);
                if(certinum_val.length != 15 && certinum_val.length != 18){
                    return alert('身份证长度不正确');
                }
                $(data).attr('certinum',certinum_val);
                if(!sex){
                    return alert('请选择性别!');
                }
                $(data).attr('type','names');
                jdbox.alert(2);
                $.post('/uajax/identifysubmit.html',{type:'name',name:name_val,number:certinum_val,sex:sex},function(R){
                    if(!R.status){
                        jdbox.alert(R.status,R.info);
                    }else{
                        alert(R.info);
                        window.location.reload();
                    }
                },'json');
            })

            //邮箱验证
            $('#emailvalidation').click(function(){
                var emailEreg = /^[a-z0-9_\.]{1,}@[a-z0-9-\.]{1,}\.[a-z]{2,}$/;
                var email = $('input[name="email"]').val();

                if(emailEreg.test(email)==false){
                    jdbox.alert(0,'请输入正确的邮箱');
                    return false;
                }
                jdbox.alert(2);
                $.post('/uajax/getEmailCode', {
                    'email': email
                }, function(_result) {
                    jdbox.alert(_result.status,_result.info);
                    /*if (_result.status) {
                     location.reload();
                     }*/
                }, 'json');

            })
            
            /*修改邮箱*/
            $("#ditemail").click(function(){
                $(".email_ok").hide();
                $(".email_revise").show();
            })
            $('#apply_submit').click(function(){
                var query = $(this);
                top.jdbox.alert(2);
                $.post('/Borrow/apply/',{id:$('#hideen_val').html()},function(result){
                    top.jdbox.close();
                    if(result.status == 1){
                        query.parent().text(result.info);
                    }else{
                        return alert(result.info);
                    }
                },'json');
            });

        })
        function id_divshow(s){
            var id_class = "."+s+"";
            $(".id_list .id_div").removeClass("id_divshow");
            $(id_class).addClass("id_divshow");
        }
    </script>

<include file="Public:accountFooter"/>