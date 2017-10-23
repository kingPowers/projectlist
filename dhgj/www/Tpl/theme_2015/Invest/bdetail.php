<style>
    .content{background:#eeeeec;padding-bottom:50px;}
    .c_nav{height:46px;}
    .de_center{background:#fff;border:1px #d1d1d1 solid;border-radius:7px;padding-bottom:67px;}
    .de_padd{padding:0 10px;}
    .de_top{height:43px;border-bottom:1px #dedddd solid;}
    .de_top_p{font-size:18px;font-family:"微软雅黑";padding-top:11px;}
    .de_btm{width:480px;margin:0 auto;}
    .de_h6{color:#0f0e0e;font-size:18px;font-family:"微软雅黑";padding:43px 0 25px;text-align:center;margin-left:-28px;}
    .de_ul>li{height:30px;line-height:30px;}
    .de_sp_l{width:197px;display:inline-block;text-align:right;}
    .red{color:#e51818!important;}
    .de_ul>.de_li_pay{height:60px;}
    .de_div_r{width:282px;font-size:12px;}
    .de_div_r .de_input{width:180px;height:27px;border:1px #cbcbcb solid;text-indent:8px;font-size:12px;}
    .de_div_r .de_sp_r{padding-left:12px;}
    .de_li_last{margin-top:45px;height:33px;padding-left:128px;}
    .de_li_btn{height:33px;width:175px;color:#fff;background:#a97502;border:none;border-radius:3px;text-align:center;line-height:33px;}
    .de_li_btn:hover{background:#ba8613;}
</style>
<div class="content">
    <div class="c_nav_box">
        <div class="c_nav">
            <p>当前位置：<a href="/">首页</a> &gt; <a href="/invest">我要投资</a> &gt; <a href="/invest/bdetail" class="c_nav_now">安心转让</a> </p>
        </div>
    </div>
    <div class="center de_center">
        <div class="de_padd">
            <div class="de_top"><p class="de_top_p">安心转让</p></div>
            <div class="de_btm">
                <h6 class="de_h6">{$transfer.title}</h6>
                <ul class="de_ul">
                    <li><span class="de_sp_l">年化利率：</span><span>{$transfer['year_rate']}<if condition="$transfer['activity_rate'] gt 0">+{$transfer['activity_rate']}</if>%</span></li>
                    <li><span class="de_sp_l">偿还方式：</span>{$repayment[$transfer['repayment']]}</li>
                    <li><span class="de_sp_l">投资期限：</span><span><if condition="$transfer.isloanday eq 1">{$transfer.loanday}天<else/>{$transfer.loanmonth}个月</if></span></li>
                    <li><span class="de_sp_l">剩余期限：</span>{$transfer.difftime}</li>
                    <li><span class="de_sp_l">投资本金：</span><span class="red">{$transfer.money}</span> 元</li>
                    <li><span class="de_sp_l">当前应收收益：</span><span>{$transfer['zrmoney']-$transfer['money']|round=###,2}</span> 元</li>
                    <li><span class="de_sp_l">支付金额：</span><span class="red">{$transfer.zrmoney}</span> 元</li>
                    <li><span class="de_sp_l">账户余额：</span><span>{:number_format($_SESSION['member']['memberInfo']['availableAmount'],2)}</span> 元</li>
                    <li class="de_li_pay">
                        <span class="de_sp_l c_left">交易密码：</span>
                        <input type="hidden" name="sn" value="{$transfer.tendersn}">
                        <input type="hidden" name="money" value="{$transfer.zrmoney}">
                        <div class="de_div_r c_left">
                            <input type="password" class="de_input" name="tradepwd">
                            <a href="/member/getpaypwd" class="red">忘记交易密码？</a>
                            <span class="de_sp_r">初始交易密码与登录密码一致 </span>
                        </div>
                    </li>
                    <li><span class="de_sp_l">挂牌时间：</span>72个小时 <span style="font-size:12px;">*备注：72小时之后自动撤销转账</span></li>
                    <li class="de_li_last">
                        <button type="button" class="de_li_btn" id="submit">支付</button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php
$memberJson = json_encode(array('id'=>$_SESSION['member']['id'],'amount'=>$_SESSION['member']['memberInfo']['availableAmount']));
?>
<script>

    var memberStr = '{$memberJson}',
        memberJson = eval( '(' + memberStr + ')' );
    var sendform = false;

    $(function(){
        $('#submit').click(function(){
            if(sendform){
                return alert('数据传送中，请等待');
            }

            if (!memberJson.id) {
                return jdbox.alert(0, '请登录后进行投标');
            }
            var money = $("input[name='money']").val();

            if (parseInt(memberJson.amount) < parseInt(money)) {
                return jdbox.alert(0, '账户余额不足');
            }

            var tradepwd = $("input[name='tradepwd']").val();
            if(!tradepwd){
                return jdbox.alert(0, '交易密码不能为空');
            }

            var sn = $("input[name='sn']").val();
            if(!sn){
                return jdbox.alert(0, '找不到订单');
            }

            sendform = true;
            jdbox.alert(2);
            $.post('/uajax/transfer',{'tradepwd':tradepwd,'sn':sn},function(response){
                sendform = false;
                if(response.status){
                    alert(response.info);
                    location.href = "/invest/blist";
                }else{
                    jdbox.alert(response.status,response.info);
                }

            }, "json");

        })
    })
</script>