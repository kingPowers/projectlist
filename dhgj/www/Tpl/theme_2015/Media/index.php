<link rel="stylesheet" href="_STATIC_/2015/css/order.css">
<style type="text/css">
.media_img:hover{cursor: pointer;}
</style>
<div class="content">
	<div class="center_div">
		<div class="d_nav_box">
			<p>
				当前位置
				<a href="/">贷后管家首页</a>
				> <a href="/order/neworderlist">媒体公告</a>
			</p>
		</div>
		<div class="w1200 bgf">
			<div class="tit_media"></div>
			<p class="info_tit">贷后管家正快速成长和迅速发展，吸引了各界人士的关注，通过媒体的报道则使更多人加深了对我们的了解。</p>
			<foreach name="news_list" item="vo">
			<div class="media_one">
				<div class="media_img"><img src="{$vo['pic_urls'][0]}" onclick="javascript:window.location.href='/media/news/id/{$vo.id}'"></div>
				<div class="w615">
					<div class="tit_blue">{$vo['title']}</div>
					<div class="data_blue">{$vo['lasttime']}</div>
					<div class="h135">{$vo['intro']}
					<a onclick="javascript:window.location.href='/media/news/id/{$vo.id}'">【详情】</a> 
					</div>
				</div>				
			</div>
			</foreach>
			<div>{$page}</div>
		</div> 
	</div>
</div>