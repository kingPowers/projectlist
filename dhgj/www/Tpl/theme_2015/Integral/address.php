<if condition="address">
<foreach name="address" item="vo">
    <tr>
        <td><input type="radio" value="{$vo['id']}" name="addressid" <if condition="$vo['isdefault'] eq 1">checked</if>/></td>
        <td>{$vo['name']}</td>
        <td>{$vo['mobile']}</td>
        <td>{$vo['province']} {$vo['area']}</td>
        <td>{$vo['address']}</td>
        <td>{$vo['zip']}</td>
    </tr>
</foreach>
<else/>
    <tr>
        <td colspan="6" style="text-align:center;">
            您还没有提交收货地址！现在去<a href="/member/address">提交</a>。
        </td>
    </tr>
</if>
{__NOLAYOUT__}