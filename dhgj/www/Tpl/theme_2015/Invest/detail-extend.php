<foreach name="tenderlist" item="vo">
	<tr>
        <td class="ui-text-peagreen">
            <?php if($_SESSION['member']['username'] == $vo['username']){ ?>
            <span style="color:green;">{$vo['username']}</span>
            <?php }else{ ?>
                <span>{$vo['username']|hide_username}</span>
            <?php } ?></td>
        <td class="ui-text-orange">{$vo['realrate']}%</td>
		<td class="ui-text-orange">￥{$vo['money']}<em>元</em></td>
        <td>{$vo['timeadd']}</td>
		<td><if condition="$vo.type eq 1">
                网页
                <elseif condition="$vo.type eq 2"/>
                    自动投标
                    <elseif condition="$vo.type eq 3"/>
                        手机
            </if></td>
        <td></td>
	</tr>
</foreach>
{__NOLAYOUT__}