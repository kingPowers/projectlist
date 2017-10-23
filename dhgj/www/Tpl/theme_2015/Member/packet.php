<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<link rel="stylesheet" href="_STATIC_/2015/member/css/packet.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li class="now"><a href="/member/packet">送红包</a></li>
            <li><a href="/member/giftcard">加息券</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
        <div class="hint">
<!--            <span class="c_left"><i></i>安全&nbsp;&nbsp;快捷&nbsp;&nbsp;免费手续</span>-->
            <span class="c_right">账户可用余额：<b>￥{$member['memberInfo']['availableAmount']|default=0.00}</b></span></div>
    </div>
    <div class="showtip"></div>
    <div class="user_box">
        <table class="pack_table">
            <tr>
                <th style="width:235px;">红包编号</th>
                <th style="width:88px;">红包名称</th>
                <th style="width:74px;">类型</th>
                <th>发放时间</th>
                <th>失效时间</th>
                <th style="width:74px;">金额</th>
                <th style="width:74px;">状态</th>
                <th style="width:74px;">备注</th>
            </tr>
            <foreach name="list" item="vo">
            <tr>
                <td class="td_fast">{$vo.sn}</td>
                <td class="td_name">{$vo.title}</td>
                <td>{$cate[$vo['codeid']]}</td>
                <td>{$vo.timeadd}</td>
                <td>{$vo.lasttime}</td>
                <td>￥{$vo.amount}</td>
                <td>
                    <?php if($vo['status'] == 1){ ?>
                        已使用
                    <?php }elseif(time() < strtotime($vo['lasttime'])){ ?>
                        未使用
                    <?php }else{ ?>
                        过期
                    <?php } ?>
                </td>
                <td><div class="pack_tip" t="投资现金，移动端，电脑端都可使用红包有效期30天"></div></td>
            </tr>
            </foreach>

        </table>

        <div>{$page}</div>
    </div>

    <script>
        $(function(){
            $(".pack_tip").each(function(i){
                $(this).hover(function(){
                    $(".showtip").html('<span class="tip_sp"></span>'+$(this).attr("t"));
                    var sL = $(this).position().left+6;
                    var sT = $(this).position().top+32;
                    $(".showtip").css({top:sT,left:sL});
                    $(".showtip").show();
                }, function(){
                    $(".showtip").hide();
                })
            })
        })
    </script>




<include file="Public:accountFooter"/>