<foreach name="tenderlist" item="vo">
	<tr>
		<td class="ui-text-peagreen">{$vo['username']|hide_username}</td>
		<td>{:member_intergral_icon($vo['integral'])}</td>
		<td class="ui-text-orange">￥{$vo['money']}<em>元</em></td>
		<td class="ui-text-orange">{$vo['realrate']}%</td>
		<td>{:substr($vo['timeadd'],0,16)}</td>
	</tr>
</foreach>
{__NOLAYOUT__}