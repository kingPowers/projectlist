<include file="Public:accountMenu"/>
<style>
    .ba_min_nav{height:40px;box-sizing:border-box;border-bottom:1px #c9c9c9 solid;}
    .ba_min_nav>li{float:left;width:99px;height:38px;line-height:38px;border-top:2px #fff solid;text-align:center;cursor:pointer;position:relative;+bottom:-1px;}
    .ba_min_nav>li.now{border-top-color:#bf950d;font-weight:bold;border-left:1px #c9c9c9 solid;border-right:1px #c9c9c9 solid;background:#fff;color:#0f0e0e;}
    .auto_ul{margin:30px 0 34px;font-size:12px;}
    .auto_ul>li{height:32px;line-height:32px;margin-bottom:13px;}
    .auto_ul>li .auto_sp_l{width:108px;height:32px;float:left;text-align:right;display:block;padding-right:5px;}
    .auto_ul>li .auto_ten_sp{padding-left:8px;color:#a97502;}
    .auto_ul>li .input{width:240px;height:30px;border:1px #c9c9c9 solid;text-indent:8px;vertical-align:middle;font-size:12px;}
    .auto_ul>li .input::-webkit-input-placeholder{color:#d0bbbb;}
    .auto_ul>li .input:-ms-input-placeholder{color:#d0bbbb;}
    .auto_ul>li .input::-moz-placeholder{color:#d0bbbb;}
    .auto_ul>li .input:-moz-placeholder{color:#d0bbbb;}
    .auto_ul>li .radio{width:15px;height:15px;vertical-align:middle;cursor:pointer;}
    .auto_ul>li .check{width:14px;height:14px;vertical-align:middle;cursor:pointer;margin-left:10px;}
    .auto_ul>li select{height:32px;border:1px #c9c9c9 solid;text-indent:8px;color:#666;}
    .auto_ul>li .auto_rad{width:15px;height:15px;cursor:pointer;vertical-align:middle;}
    .auto_ul>li .sp_r{color:#a9a7a7;font-size:12px;}
    .auto_ul>li.auto_last{margin:27px 0 0;height:33px;line-height:33px;padding-left:22px;}
    .auto_ul>li .button{width:115px;height:33px;line-height:33px;text-align:center;color:#fff;background:#a97502;border:none;border-radius:3px;}
    .auto_ul>li .button:hover{background:#ba8613;}
    .auto_b{margin-top:34px;padding:26px 0 44px 23px;border-top:1px #dadada solid;font-size:12px;}
    .auto_b .auto_p_t{margin-bottom:18px;}
    .auto_b .auto_p_ind{text-indent:35px;}
</style>
<input name="rid" type="hidden" value="{$rs.id}">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li class="now">自动投资</li>
        </ul>
    </div>
    
    <!--自动投标-->
    <ul class="auto_ul">
        <li><span class="auto_sp_l">自动投资状态：</span>
            <if condition="$rs.status eq 0">
                <span class="auto_ten_sp">已关闭</span>
            <else/>
                <span class="auto_ten_sp">已开启</span>
            </if>

        </li>
        <li><span class="auto_sp_l">是否开启自动：</span>&nbsp;
            <input type="radio" name="auto" value="0" <if condition="$rs.status eq 0">checked="checked"</if> class="radio"> 关闭
            &nbsp;&nbsp;
            <input type="radio" name="auto" value="1" <if condition="$rs.status eq 1">checked="checked"</if> class="radio"> 开启
        </li>
        <li><span class="auto_sp_l">您的可用余额：</span>￥{$member['memberInfo']['availableAmount']}</li>
        <li><span class="auto_sp_l">最小投资金额：</span><input type="text" name="minmoney" value="{$rs.minmoney}" class="input" placeholder="必填"> 元(该数值须为100的倍数且不大于50,000的整数)</li>
        <!--<li><span class="auto_sp_l">最大投资金额：</span><input type="text" name="maxmoney" value="{$rs.maxmoney}" class="input" placeholder="必填"> 元</li>-->
        <li><span class="auto_sp_l">借款期限上限：</span><input type="text" name="loanmonth" value="{$rs.loanmonth}" class="input" placeholder="必填"> 月(借款期限范围为1月~12月)</li>
        <li><span class="auto_sp_l">年化利率下限：</span><input type="text" name="yearrate" value="{$rs.yearrate}" class="input" placeholder="必填"> %(利率范围为8%~15%)</li>
        <li><span class="auto_sp_l">标 的 类 型：</span>
            <input type="checkbox" class="check" name="st_type" value="1" <?php if(in_array(1,$rs['st_type']))echo "checked";?>> 安心安盈
            <input type="checkbox" class="check" name="st_type" value="2" <?php if(in_array(2,$rs['st_type']))echo "checked";?>> 安心稳盈
            <!--<input type="checkbox" class="check" name="st_type"> 安心转让-->
        </li>
        <li class="auto_last"><button type="button" class="button" onclick="addauto();">保存</button></li>
    </ul>
    <div class="auto_b">
        <p class="auto_p_t">自动投资说明</p>
        <p>1、自动投资适用于吉祥果平台所有标的类型项目；</p>
        <p>2、在平台充过值的用户可以开启自动投资；</p>
        <p>3、自动投资会根据用户设置的每次投资金额、最小投资金额，结合用户的账户可用余额向符合设置条件的项目进行投资，具体规则如下：</p>
        <p class="auto_p_ind">a、账户可用余额＜最小投资金额，不执行自动投资；</p>
        <p class="auto_p_ind">b、账户可用余额＜设置的最大投资金额时，将按照可用余额的100的最大倍数的金额进行自动投资</p>
        <p>4、用户开启自动投资功能，并完成条件设置后，自动投资功能将在五分钟内生效；</p>
        <p>5、如需了解更多请致电客服400-663-9066。</p>
    </div>

<script language="javascript">
    $(function() {
    })
    var addauto = function () {
        var data = {};
        $(data).attr('id', $('input[name="rid"]').val());
        $(data).attr('auto', $('input[name="auto"]:checked').val());

        var st_type =[];
        $('input[name="st_type"]:checked').each(function(){
            st_type.push($(this).val());
        });
        $(data).attr('st_type', st_type);

        var minmoney = parseInt($("input[name='minmoney']").val());
        var reg_str = /^[1-9]+[0-9]*$/;
        if (reg_str.test(minmoney) == false) {
            return jdbox.alert(0, '请输入正确的最小投标值');
        }
        if (minmoney%100>0 || minmoney>5000) {
            return jdbox.alert(0, '最小投标值必须为100整数!');
        }
        $(data).attr('minmoney',minmoney);
        $(data).attr('maxmoney', $("input[name='maxmoney']").val());
        $(data).attr('loanmonth', $("input[name='loanmonth']").val());
        $(data).attr('yearrate', $("input[name='yearrate']").val());
        /*if (reg_str.test(parseInt($(data).attr('maxmoney'))) == false) {
            return jdbox.alert(0, '请输入正确的最大投标值');
        }*/
        if ($(data).attr('yearrate')) {
            if ($(data).attr('yearrate') < 8 || $(data).attr('yearrate') > 15) {
                return jdbox.alert(0, '年化利率下限只能是8%~15%之间的正整数');
            }
        }
        if ($(data).attr('loanmonth')) {
            if ($(data).attr('loanmonth') < 1 || $(data).attr('loanmonth') > 12) {
                return jdbox.alert(0, '借款期限上限只能在1月~12月之间');
            }
        }
        jdbox.alert(2);
        $.post('/member/autotenderupdate.html', {data: genboxdata(data)}, function (result) {
            if (result.status) {
                return jdbox.alert(result.status, result.info, 'window.location.reload()');
            } else {
                return jdbox.alert(result.status, result.info);
            }
        }, 'json');
    }


</script>

<include file="Public:accountFooter"/>