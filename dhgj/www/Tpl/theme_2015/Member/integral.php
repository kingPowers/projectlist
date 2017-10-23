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
            <li class="now"><a href="/member/integral">积分</a></li>
            <li><a href="/member/gift">兑换</a></li>
        </ul>
    </div>
    <div style="margin-top:20px;">
    <table class="rec_table">
        <tr>
            <th style="width:235px;">时间</th>
            <th>金币数量</th>
            <th style="width:376px;">备注</th>
        </tr>
        <volist name="list" id="vo">
            <tr>
                <td>{$vo['timeadd']|substr=0,10}</td>
                <td>{$vo['integral']}金币</td>
                <td>{$vo['remark']}</td>
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