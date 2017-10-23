<?php
$this->need('type','类型');
$type = $this->v('type');
//获取媒体公告列表
if ($type == "mediaNews")
{
  $this->need("page","分页");
  $page = $this->v("page");
  $is_cream = $this->v("is_cream")?$this->v("is_cream"):'';
  $number = $this->v('number')?$this->v('number'):10;
  if(false === ($mediaNewsList = mediaNewsList($page,$number,$is_cream)))$this->error("MEDIA_BEWS_ERROR","系统错误,请稍后再试");
  $this->data = $mediaNewsList;
  return true;
}
//获取媒体新闻详情
if($type == "mediaNewsInfo")
{
  $this->need("news_id","新闻id");
  $news_id = $this->v("news_id");
  if(false == ($newsInfo = getNewsInfo($news_id)))$this->error("NEWS_INFO_ERROR","新闻id错误");
  $this->data['newsInfo'] = $newsInfo;
  return true;
}
$this->error('TYPE_ERROR','类型错误');
  /*
  获取媒体公告列表
  $page:分页
  $number:每页新闻数量
  $is_cream:是否为精华；1：是 (默认全部)
   */
  function mediaNewsList($page,$number,$is_cream='')
  {
  	 $_static_ = defined(_STATIC_)?_STATIC_:"http://daistatic.lingqianzaixian.com";
     $page = ($page == '')?1:$page;
     if($number=='' || $number>100){$this->error = "每页条数(参数：number)错误";return false;}
     $where = array();
     if($is_cream == 1)$where['is_cream'] = $is_cream;
     $where['status'] = 1;
     $count = M("media_news")->where($where)->count();
     if($count > 0)
     {
         $newsList = M("media_news")
         ->where($where)
         ->limit(($page-1)*$number.",".$number)
         ->order("status desc,sort asc,lasttime desc")
         ->select(); 
         foreach ($newsList as &$value) 
         {
           $value['timeadd'] = date("Y.m.d",strtotime($value['timeadd']));
           $value['lasttime'] = date("Y.m.d",strtotime($value['lasttime']));
           $pic_urls = explode("|",$value['pic_urls']);
	       array_walk($pic_urls,
	       function(&$value, $k, $prefix){$value = $prefix.$value;}, 
	       $_static_."/Upload/news/"
	       );
           $value['pic_urls'] = $pic_urls;
         } 
         
     }  
     $list['count'] = $count;
     $list['list'] = $newsList;
     return !empty($list)?$list:[]; 
  }
  /*
 获取新闻内容
 $news_id:新闻id
 */
  function getNewsInfo($news_id)
  {
  	  $_static_ = defined(_STATIC_)?_STATIC_:"http://daistatic.lingqianzaixian.com";
      if(false == ($newsInfo = M("media_news")->where("id={$news_id} and status=1")->find()))return false;
      if(!S("scan_add_".$news_id))M("media_news")->where("id={$news_id}")->setInc("scan",1);
      $newsInfo['timeadd'] = date("Y.m.d",strtotime($newsInfo['timeadd']));
      $newsInfo['lasttime'] = date("Y.m.d",strtotime($newsInfo['lasttime']));
      $pic_urls = explode("|",$newsInfo['pic_urls']);
	  array_walk($pic_urls,
	  function(&$value, $k, $prefix){$value = $prefix.$value;}, 
	  $_static_."/Upload/news/"
	  );
      $newsInfo['pic_urls'] = $pic_urls;
      //上一条新闻
      $preNews = M('media_news')->field("id,title,intro")->where("id<{$news_id} and status=1")->order("id desc")->find();
      $newsInfo['preNews'] = $preNews?$preNews:array();
      //下一条新闻
      $nextNews = M('media_news')->field("id,title,intro")->where("id>{$news_id} and status=1")->order("id asc")->find();
      $newsInfo['nextNews'] = $nextNews?$nextNews:array();
      S("scan_add_".$news_id,1,60);
      return $newsInfo;
  }
?>