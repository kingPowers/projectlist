<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/bankacc.css">
<script src="_STATIC_/2015/js/province&bank.js"></script>

<div class="rec_t">
    <ul class="ba_min_nav">
        <li class="now">银行账号</li>
    </ul>
    <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
    <div class="hint"><span class="c_left"><i></i>安全&nbsp;&nbsp;快捷&nbsp;&nbsp;</span><span class="c_right">账户可用余额：<b>￥{$member['memberInfo']['availableAmount']|default=0.00}</b></span></div>
</div>

<if condition="$member['memberInfo']['bankStatus'] eq 1">
<!--银行账号已绑定-->
<div class="user_bank">
    <ul class="user_ul c_left">
        <li><span class="user_sp_l">真实姓名：</span><span>{$member.memberInfo.names|hide_username}</span></li>
        <li><span class="user_sp_l">开户行名称：</span><span>{$member.memberInfo.bindBank}</span></li>
        <li><span class="user_sp_l">银行账号：</span><span>{$member.memberInfo.bindBankNum|substr=0,5}****{$member.memberInfo.bindBankNum|substr=12}</span></li>

    </ul>
    <ul class="user_ul c_right">
        <li><span class="user_sp_l">开户行所在地区：</span><span>{$member.memberInfo.bindBankProvince},{$member.memberInfo.bindBankCity}</span></li>
        <li><span class="user_sp_l">开户行支行名称：</span><span>{$member.memberInfo.bindBankSubbranch}</span></li>
        <li><span class="user_sp_l">账户类型：</span><span>个人账户</span></li>
    </ul>
</div>
<div class="user_bank_m">
    <div class="user_btn">
        <button type="button" class="button" id="re_bank">修改账号</button>
        <button type="button" class="button" <if condition="$member['memberInfo']['availableAmount'] eq 0"> style="background:#888888;" <else/>onclick="window.location.href='/member/carry'"</if>>我要提现</button>
    </div>
    <div class="re_bank_b">
        <p>*温馨提示：若要修改账号，请联系客服。</p>
    </div>
</div>

<else/>

<!--银行账号未绑定-->
<ul class="bank_ul">
    <li><span class="bank_sp_l"><em>*</em> 用户名：</span><span>{$member.username}</span></li>
    <if condition="$member['memberInfo']['nameStatus'] eq 1">
        <li><span class="bank_sp_l"><em>*</em> 真实姓名：</span>{$member.memberInfo.names|hide_username}</li>
        <!--<li><span class="bank_sp_l"><em>*</em> 性别：</span>

            <eq name="member['memberInfo']['sex']" value="1">男</eq>
            <eq name="member['memberInfo']['sex']" value="2">女</eq>
            <eq name="member['memberInfo']['sex']" value="0">不限</eq>

        </li>-->
        <li><span class="bank_sp_l"><em>*</em> 身份证号：</span>
            {$member.memberInfo.certiNumber|substr=0,5}****{$member.memberInfo.certiNumber|substr=12}
        </li>
    <else/>

        <li><span class="bank_sp_l"><em>*</em> 真实姓名：</span><input type="text" id="names" name="name" value=""></li>
        <li><span class="bank_sp_l"><em>*</em> 性别：</span>
            <input type="radio" name="sex" value="1" checked class="bank_rad"> 男
            <input type="radio" name="sex" value="2" class="bank_rad"> 女
        </li>
        <li><span class="bank_sp_l"><em>*</em> 身份证号：</span>
            <input type="text" name="certinumber" id="certinumber">
        </li>

        <li class="bank_last" style="margin:0 30px 30px 0;padding-bottom: 30px;border-bottom:1px dashed #ccc;clear: both"><span class="bank_sp_l" ></span><button type="button" class="button" id="namessubmit">确认提交</button></li>


    </if>

    <li><span class="bank_sp_l"><em>*</em> 银行预留手机：</span><input type="text" id="mobile" name="mobile" value="{$bank.mobile}"></li>
    <li><span class="bank_sp_l"><em>*</em> 银行账号：</span><input type="text" name="banknum" id="banknum_input" value="{$bank.banknum}"><span class="sp_r"> （卡号必须与姓名匹配，信用卡无法提现）</span></li>
    <!--<li><span class="bank_sp_l"><em>*</em> 账户类型：</span>
        <input type="radio" name="bank_acc" class="bank_rad" checked="checked"> 个人账户
        <input type="radio" name="bank_acc" class="bank_rad"> 企业账户
    </li>-->
    <li><span class="bank_sp_l"><em>*</em> 开户行名称：</span>
        <select id="bankname" class="iselect">
            <option value="">选择银行</option>
            <option value="ICBC">工商银行</option>
            <option value="ABC">农业银行</option>
            <option value="CCB">建设银行</option>
            <option value="BOC">中国银行</option>
            <option value="CMBC">民生银行</option>
            <option value="CMB">招商银行</option>
            <!-- <option value="COMM">交通银行</option> -->
            <option value="PSBC">中国邮储银行</option>
            <option value="SZPAB">平安银行</option>
            <option value="CIB">兴业银行</option>
            <option value="CEB">光大银行</option>
            <option value="HXB">华夏银行</option>
            <option value="CITIC">中信银行</option>
            <option value="BCCB">北京银行</option>
            <option value="BOS">上海银行</option>
            <option value="SHRCB">上海农村商业银行</option>
            <option value="SPDB">浦东发展银行</option>
            <option value="GDB">广东发展银行</option>
        </select>
    </li>
    <li><span class="bank_sp_l"><em>*</em> 开户行所在地区：</span>
        <select name="rovince" id="rovince" style="width:205px;">
            <option value="0">请选择</option>
        </select>
        <select name="city" id="city" style="width:205px;margin-left:13px;">
            <option value="0">请选择</option>
        </select>
    </li>
    <li><span class="bank_sp_l"><em>*</em> 开户行支行名称：</span>
        <input type="text" style="width:429px;" class="zh_input" name="banksubbranch" id="banksubbranch">
    </li>

    <li id="sinacode" ><span class="bank_sp_l"><em>*</em> 新浪验证码：</span>
        <input type="text" style="width:112px;" class="zh_input" name="valid_code" id="valid_code">
        <button type="button" class="button sina_btn">获取验证码</button>
        <span id="sinamsg">请输入新浪发送至您手机短信验证码</span>
    </li>

    <li><span class="bank_sp_l"></span><span class="sp_r">（如不能确定，请拨打开户行的客服热线咨询）</span></li>
    <li class="bank_last"><span class="bank_sp_l"></span><button type="button" class="button" id="banksubmit">确认提交</button></li>
</ul>
</if>
<div class="bank_b">
    <p>* 温馨提示：</p>
    <p>吉祥果禁止信用卡套现、虚假交易等行为,一经发现将予以处罚,包 括但不限于：限制收款、冻结账户、永久停止服务,并有可能影响相关信用记录。</p>
</div>

<script>
    var sendtimmer = null;
    var sendsecond = 120;
    var smsbutton = $('.sina_btn');

    $(function(){
        var nameStatus = {$member['memberInfo']['nameStatus']};
        var options;
        $.each(GP,function(index,now){
            $("#rovince").append('<option value="'+(index+1)+'">'+now+'</option>');
        })
        $("#rovince").change(function(){
            $('#city')
            options = $("#rovince>option:selected").val()-1;
            $("#city option:gt(0)").remove();
            $.each(GT[options],function(index1,now1){
                $("#city").append('<option value="'+options+'_'+(index1+1)+'">'+now1+'</option>');
            })
        })

        $('#banksubmit').click(function(){
            var valid_code = $('#valid_code').val();
            if(!valid_code){
                jdbox.alert(0,'验证码不能为空');
                return false;
            }
            $.post('/uajax/bindbank_advance',{valid_code:valid_code},function(R){
                alert(R.info);
                if(R.status) {
                    window.location.reload();
                }
            },'json');

        })

        //身份证绑定
        $('#namessubmit').click(function(){
            var data = {};
            var name_val = $('#names').val();
            var certinum_val = $('#certinumber').val();
            var sex  = $('input[name="sex"]').val();

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
                    jdbox.alert(R.status,R.info);
//                    alert(R.info);
                    window.location.reload();
                }
            },'json');
        })




        $('.sina_btn').click(function(){

            if(nameStatus==0){
                return alert('请先绑定身份信息!');

                var names = $('#names').val();
                if(!names){
                    return jdbox.alert('真实姓名不能为空');
                }
                var sex = $('input[name="sex"]').val();
                if(!sex){
                    return jdbox.alert('性别不能为空');
                }
                var certinumber = $('#certinumber').val();
                if(!certinumber){
                    return jdbox.alert('身份证号不能为空');
                }

            }

            var bankcode = $('#bankname').val();
            var bankname = $('#bankname').find("option:selected").text();
            var province = $('#rovince').val();
            var city = $('#city').val();
            var province_name = $("#rovince").find("option:selected").text();
            var city_name = $("#city").find("option:selected").text();
            var mobile = $('#mobile').val();
            var banksubbranch = $('#banksubbranch').val();

            if(!bankcode){
                return alert('请选择银行');
            }
            if(province == 0){
                return alert('请选择省份');
            }
            if(city == 0){
                return alert('请选择城市');
            }
            if(!mobile){
                return alert('请输入银行预留手机号');
            }
            var banknum_input = $('#banknum_input').val();
            if(banknum_input.length <10 || isNaN(banknum_input)){
                return alert('银行卡号不正确');
            }
            jdbox.alert(2);
            $.post('/uajax/bindbank',{names:names,sex:sex,certinumber:certinumber,type:'bank',bankcode:bankcode,bankname:bankname,province:province_name,city:city_name,number:banknum_input,mobile:mobile,banksubbranch:banksubbranch},function(R){
                if(!R.status){
                    return jdbox.alert(R.status,R.info);
                }else{
                    jdbox.close();
                    $('#valid_code').focus();
                    $('#sinamsg').show();
                    clearInterval(sendtimmer);
                    sendsecond = 119;
                    smsbutton.attr('disabled',"disabled");
                    sendtimmer = setInterval('showTimemer()', 1000);

                    //window.location.reload();
                }
            },'json');


        })

        $(".zh_name").click(function(){
            $(".zh_select").hide();
            $(".zh_input").show();
        })
        $("#re_bank").click(function(){
            $(".re_bank_b").stop(true).slideToggle();
        })
    })

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