<include file="Public:accountMenu"/>
<link rel="stylesheet" href="_STATIC_/2015/member/css/recharge.css">
<style>
    .rec_h5{margin:16px 0 18px;height:19px;line-height:19px;border-left:10px #c8c8c8 solid;text-indent:8px;}
    .rec_data{overflow:hidden;margin-bottom:27px;}
    .data_l{padding-left:18px;line-height:25px;}
    .data_l select{width:84px;height:23xp;border:1px #c8c8c8 solid;outline:none;}
    .rec_table{width:840px;}
    .rec_table th,.rec_table td{text-align:center;border:1px #c8c8c8 solid;line-height:39px;}
    .rec_table th{font-weight:bold;}
    .rec_table td{font-size:12px;}
</style>
    <div class="rec_t">
        <ul class="ba_min_nav">
            <li><a href="/member/integral">积分</a></li>
            <li class="now"><a href="/member/gift">兑换</a></li>
        </ul>
    </div>
    <div style="margin-top:20px;">
    <table class="rec_table">
        <tr>
            <th>收货人</th>
            <th>联系方式</th>
            <th>金币</th>
            <th>商品</th>
            <th>收货地址</th>
            <th>状态</th>
            <th>快递公司</th>
            <th>快递单号</th>
        </tr>
        <volist name="list" id="vo">
            <tr>
                <td>{$vo['name']}</td>
                <td>{$vo['mobile']}</td>
                <td>{$vo['integral']}</td>
                <td>{$vo['giftname']}</td>
                <td>{$vo['address']}</td>
                <td><if condition="$vo['status'] eq 1">已发货<else/>未处理</if></td>
                <td>{$vo['emsname']}</td>
                <td>{$vo['emssn']}</td>
            </tr>
        </volist>

    </table>
    </div>
    <div>{$page}</div>
<script>
$(function(){
    $("#status").change(function(){
        var id = $(this).val();
        location.href="/member/recharge_rec/status/"+id;
    })
})
</script>
<include file="Public:accountFooter"/>