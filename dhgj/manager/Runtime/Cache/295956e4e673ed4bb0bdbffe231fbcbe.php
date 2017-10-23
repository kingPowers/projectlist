<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
#page{clear:both;height:auto;background-color:#fff;padding:5px 0 5px 0;text-align: center;float:none;}
#page span{margin-left:5px;margin-right:5px;color:#999;border:1px solid #ccc;padding:2px 8px 2px 8px;}
#page span.nowpage{color:#fff;background-color:#0081a1;border:1px solid #ccc;}
#page span.list{border:0px;}
#page a{margin-left:5px;margin-right:5px;border:1px solid #ccc;padding:2px 8px 2px 8px;}
#page a:link,#page a:visited{color:#0081a1;background-color:#fff;}
#page a:hover{color:#f60;background-color:#0081a1;color:#fff;text-decoration:none;}
</style>
<script>
function search_page(){
	var insert_page = $('#search-page').val();
	var page_url = '<?php echo $page_url; ?>';
	var location_url=page_url.replace(/\*/,insert_page);
	window.location.href=location_url;
}
</script>
<?php if($page_vars['count']){ $count = '共'.$page_vars['count'].'条'; } $prev = $page_vars['no']>1?'<a href="'.str_replace('*',$page_vars['no']-1,$page_url).'">上一页</a>':'<span>上一页</span>'; $first = $left = $now = $right = $last = ''; if($page_vars['no']<4){ for($i=1;$i<4;$i++){ if($i<=$page_vars['total']){ $first.= ($page_vars['no']!=$i)?'<a href="'.str_replace('*',$i,$page_url).'">'.$i.'</a>':'<span class="current">'.$i.'</span>'; } } if($page_vars['total']>4){ $right = '<span>---</span>'; } }else{ $first = '<a href="'.str_replace('*',1,$page_url).'">1</a><span>---</span>'; $left = '<a href="'.str_replace('*',$page_vars['no']-1,$page_url).'">'.($page_vars['no']-1).'</a>'; $now = '<span class="current">'.$page_vars['no'].'</span>'; $right = $page_vars['no']+1<$page_vars['total']?'<a href="'.str_replace('*',$page_vars['no']+1,$page_url).'">'.($page_vars['no']+1).'</a><span>---</span>':''; } if($page_vars['no']<$page_vars['total'] && $page_vars['total']>=4){ $last = '<a href="'.str_replace('*',$page_vars['total'],$page_url).'">'.$page_vars['total'].'</a>'; } $next = $page_vars['no']<$page_vars['total']?'<a href="'.str_replace('*',$page_vars['no']+1,$page_url).'">下一页</a>':'<span>下一页</span>'; $search = '第<input type="text" id="search-page" style="vertical-align:middle;width:30px;height:17px;margin:0 5px;" value="'.$_GET['p'].'">页<input type="button" value="跳转" onclick="search_page()" class="btn btn-primary" style="margin-left:10px;">'; ?>
<div class="inline pull-right page" id="page">
<?php echo $count.$prev.$first.$left.$now.$right.$last.$next.$search; ?>
</div>