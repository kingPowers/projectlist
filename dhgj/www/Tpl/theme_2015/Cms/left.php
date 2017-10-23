<div class="ui-grid2">
	<div class="ui-side ui-side-withicon">
		<ul class="ui-side-list">
			<li class="ui-side-item <eq name="code" value="about">active</eq>">
				<a class="ui-side-item-link" href="/about.html">关于我们</a>
			</li>
			<li class="ui-side-item <eq name="code" value="contact">active</eq>">
				<a class="ui-side-item-link" href="/contact.html">联系我们</a>
			</li>
			<li class="ui-side-item <eq name="code" value="team">active</eq>">
				<a class="ui-side-item-link" href="/team.html">团队介绍</a>
			</li>
			<foreach name="catelist" item="vo">
				<li class="ui-side-item <eq name="code" value="$vo['ode']">active</eq>">
					<a class="ui-side-item-link" href="/c-{$vo['code']}.html">{$vo['name']}</a>
				</li>
			</foreach>
		</ul>
	</div>
</div>