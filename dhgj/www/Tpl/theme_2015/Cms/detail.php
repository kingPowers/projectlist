<!--container-->
<div class="ui-container">
	<include file="Cms:left" />
	<div class="ui-grid9 ui-right">
		<div class="ui-panel">
			<h1>{$news['title']}</h1>
			<hr/>
			<p class="ui-clearfix">
				<span class="ui-left ui-text-gray">{$cate['name']}</span>
				<span class="ui-right ui-text-gray">{:substr($news['timeadd'],0,16)}</span>
			</p>
			<div class="ui-artice ui-clearfix">{$news['content']}</div>
			<div class="ui-mt20 ui-right">
				<a href="/c-{$code}.html" class="ui-text-brown">返回列表</a>
			</div>
		</div>
	</div>
</div>