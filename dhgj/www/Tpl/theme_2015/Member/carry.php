<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<link rel="stylesheet" href="_STATIC_/2015/member/css/carry.css">

   <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/recharge">充值</a></li>
            <li class="now"><a href="/member/carry">提现</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
        <div class="hint"><span class="c_left"><i></i>安全&nbsp;&nbsp;快捷</span><span class="c_right">账户可用余额：<b>￥{$member['memberInfo']['availableAmount']|default=0.00}</b></span></div>
    </div>

<if condition="$member['memberInfo']['bankStatus'] eq 1">
    <!--已绑定银行卡-->
    <div class="carry_t">
        <ul class="pay_ul">
            <li><span class="pay_sp_l">账户名：</span>
                <span>{$member.memberInfo.names|hide_username}</span></li>
            <li><span class="pay_sp_l">银行账号：</span>
                <span>{$member.memberInfo.bindBankProvince} {$member.memberInfo.bindBankCity} {$member.memberInfo.bindBank} {$member.memberInfo.bindBankSubbranch} {$member.memberInfo.bindBankNum|substr=0,5}****{$member.memberInfo.bindBankNum|substr=12}</span></li>
            <li><span class="pay_sp_l"><em>*</em> 提现金额：</span>
                <input style="display: none;">
                <input type="text" placeholder="单笔最高提现5万元" name="amount" class="car_input" autocomplete="off"> 元</li>
            <!-- <li><span class="pay_sp_l"><em>*</em> 交易密码：</span>
                <input type="password" placeholder="" name="password" autocomplete="off"> <a href="/member/getpaypwd" style="color:#ea5413;" class="car_input" >忘记密码？</a></li>
            <li><span class="pay_sp_l"><em>*</em> 手机验证码：</span>
                <input type="text" placeholder="" name="smscode" class="car_input car_input_min" autocomplete="off"><button type="button" id="smsbutton" class="car_btn">获取验证码</button></li> -->
            <li class="li_last"><span class="pay_sp_l"></span><button type="button" class="car_pay_btn">确认提交</button></li>
        </ul>
    </div>

<else/>

    <div class="newcash">
        <p class="newc_p_t">您还没有绑定银行账户，请先设置。</p>
        <div class="newc_link"><a href="/member/bankacc" id="newc_link"></a></div>
        <!--<p class="newc_p_b">此银行卡无需开通网银</p>-->
    </div>
</if>

<div class="carry_explain">
    <p>* 温馨提示：</p>
    <p>1、每笔提现手续费2元；</p>
    <p>2、提现额度限制：<span  style="color:red;">根据您所使用的银行卡额度单笔5W,每日最高50W；</span></p>
    <p>3、请务必确保填写账户本人的银行卡、身份证等相关信息，填写有误将不能正常提现；</p>
    <p>4、我们将严格对您的所有资料进行保密；</p>
    <p style="color:red;">5、工作日15:00前申请提现，T+1到账；15:00后申请提现，T+2到账，具体到账时间以实际为准。</p>
</div>
<script language="javascript">
    var bindbank = "{$member.memberInfo.bankStatus}";
    if(bindbank == 0){
        alert('请先绑定银行卡');
        location.href="/member/bankacc";
    }


    //发验证码操作
    var sendsecond = 120,
        sendtimmer = null,
        smsbutton = $('#smsbutton'),
        defaultHtml = smsbutton.html(),
        mobileobj = $("input[name='mobile']");
    var exists = "{$exists}",
        fee = 2,
        bindstatus = "<?php echo ($member['memberInfo']['nameStatus']==1&&$member['memberInfo']['bankStatus']==1);?>",
        //是否实名认证，绑定银行卡
        moneyaccount = parseFloat("<?php echo $memberInfo['availableAmount'];?>");//账户余额

    smsbutton.click(function() {
        if(bindstatus != 1){
            return jdbox.alert(0,'请进行实名和银行卡绑定');
        }
        //getSmsCode();
        sendSms();//调用var sendSms
    });

    $(function(){
        $("input[name='amount']").blur(function(){
            var val = $(this).val();
            if(!/^(\d+|\d+\.\d+)$/.test(val)){
                $(this).val('');
                return false;
            }
//            val = parseFloat(val);
            
//            if(val >= 1000) {
//                fee = 0;
//            }
//            if(fee+val > moneyaccount){
//                val = moneyaccount - fee;
//            }

            if(val <= 2){
                $(this).val('');
                return false;
            }
            $(this).val( parseFloat(val) );
        });

        $('button.car_pay_btn').click(function(){
            if(!check_input()){
                return false;
            }
            var fee = smscode = password = '';
            /*var smscode = $("input[name='smscode']").val();
            if(!smscode || isNaN(smscode)){
                return jdbox.alert(0,'请正确输入验证码');
            }
            var password = $("input[name='password']").val();
            if(!password){
                return jdbox.alert(0,'请输入您的交易密码');
            }*/
            var amount = $("input[name='amount']").val();
            jdbox.alert(1,'提交中...');
            $.post("/uajax/ajaxcashout",{'amount':$("input[name='amount']").val(),'fee':fee,'password':password,'smscode':smscode},function(result){
                //传入的值有体现的金额
                if(result.status){
                	$(result.data.data).appendTo('body');
                 }else{
                	 jdbox.alert(result.status,result.info);
                  }
                /*jdbox.alert(result.status,result.info);
                if(result.status){
                	
                    window.location.href='/member/funds.html';
                }*/
            },'json');
        });
    });
    var check_input = function(){
        if(bindstatus != 1){
            jdbox.alert(0,'请进行实名认证和银行卡绑定');

            return false;
        }
        if($("input[name='amount']").val() == ''){
            jdbox.alert(0,'请输入提现金额');
            return false;
        }
        /*if(exists == 1){
            jdbox.alert(0,'您还有未处理完的提现订单');
            return false;
        }*/
        return true;
    };
    var getSmsCode = function() {
        if (sendtimmer != null) {
            return false;
        }
    }
    var sendSms = function() {
        jdbox.alert(2);
        var data = {}
        $.post('/public/verify.html', {
            trade: true
        }, function(result) {
            $('input[name="smscode"]').focus();
            jdbox.alert(1,result.info);
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
            smsbutton.html('重新发送 ' + sendsecond + '');
            sendsecond -= 1;
        } else {
            smsbutton.html(defaultHtml);
            clearInterval(sendtimmer);
            sendtimmer = null;
            sendsecond = 119;
            smsbutton.removeAttr('disabled');
        }
    }

    setTimeout("$('input[name=\"password\"]').val('')",1000);

</script>
<include file="Public:accountFooter"/>