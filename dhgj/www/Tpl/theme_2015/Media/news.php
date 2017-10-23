<link rel="stylesheet" href="_STATIC_/2015/css/order.css">
<style type="text/css">
.dbd_ud a{display: block;width: 20%;}
</style>
<div class="content">
	<div class="center_div">
		<div class="d_nav_box">
			<p>
				当前位置
				<a href="/">贷后管家首页</a>
				> <a href="/order/neworderlist">媒体公告</a>
				> 贷后管家拖车系统发布
			</p>
		</div>
		<div class="w1200 bgf">
			<div class="tit_media"></div>
			<h2 class="mcon_tit">{$info['title']}</h2>
			<div class="dbd_media">发布时间：{$info['lasttime']} <font>浏览次数：{$info['scan']}</font></div>
			<notempty name="info['pic_urls'][0]">
			   <div class="img_div" style="width:600px;margin:20px auto;text-align: center;"><img style="max-height: 200px; width: auto;" style="" src="{$info['pic_urls'][0]}"></div>
			</notempty>	
			<div class="dbd_con">{$info['content']}</div>
			<div class="dbd_ud">
			    <empty name="info['preNews']">
			     <a >上一篇：没有了 </a>
			     <else/>
			     <a href="/media/news/id/{$info['preNews']['id']}">上一篇：{$info['preNews']['title']} </a>
			    </empty>
				<empty name="info['nextNews']">
			    <a>下一篇：没有了 </a>
			     <else/>
			     <a href="/media/news/id/{$info['nextNews']['id']}">下一篇：{$info['nextNews']['title']} </a>
			    </empty>
			</div>
		</div> 
	</div>
</div>