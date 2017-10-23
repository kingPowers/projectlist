<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<link rel="stylesheet" href="_STATIC_/2015/member/css/packet.css">
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/packet">送红包</a></li>
            <li class="now"><a href="/member/giftcard">加息券</a></li>
        </ul>
            <span class="rech_na_r c_right"><a href="/member/recharge_rec">查看充值记录</a> | <a href="/member/carry_rec">查看提现记录</a></span>
        <div class="hint">
<!--            <span class="c_left"><i></i>安全&nbsp;&nbsp;快捷&nbsp;&nbsp;免费手续</span>-->
            <span class="c_right">账户可用余额：<b>￥{$member['memberInfo']['availableAmount']|default=0.00}</b></span></div>
    </div>
    <div class="user_box">
        <table class="gif_table">
            <tr>
                <th style="width:72px;"></th>
                <th>加息券序号</th>
                <th>加息券名称</th>
                <th>加息券发放时间</th>
                <th>加息券失效时间</th>
                <th style="width:175px;">加息券状态</th>
            </tr>
            <foreach name="list" item="vo">
            <tr>
                <td><span class="gift_sp">{$vo.plusrate}%</span></td>
                <td>{$vo.sn}</td>
                <td>加息券,加息{$vo.plusrate}%</td>
                <td>{$vo.timeadd}</td>
                <td>{$vo.lasttime}</td>
                <td>
                    <?php if($vo['status'] == 1){ ?>
                        已使用
                    <?php }elseif(time() < strtotime($vo['lasttime'])){ ?>
                        未使用
                    <?php }else{ ?>
                        过期
                    <?php } ?>
                </td>
            </tr>
            </foreach>
        </table>
    </div>
    <div>{$page}</div>
<include file="Public:accountFooter"/>