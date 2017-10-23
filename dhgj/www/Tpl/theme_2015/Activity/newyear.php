<link rel="stylesheet" href="_STATIC_/2015/activity/css/newyears.css">
<!--最大的容器-->
<div class="maxconDiv">
<!--    banner部分-->
    <div class="headerDiv">
    </div>
<!--    中间部分-->
    <div class="centerDiv">
        <div class="houseDiv">
            <div class="houseDiv_content" id="houseDiv_content">
                <ul class="tab" id="tab">
                    <li class="current">排行榜</li>
                    <li>全部排名</li>
                </ul>
                <!--二级菜单-->
                <div id="content">
                       <ul style="display:block;">
                           <li>名次</li><li>手机号</li><li>元宝数</li>
                           <div class="rankingDiv">
                                <?php
                                     if(!empty($toplist)) {
                                         foreach($toplist as $key => $value){
                                             echo "<li>".($key+1)."</li><li>".hide_mobile($value['mobile'])."</li><li>".$value['sumNum']."个</li>";
                                         }
                                     }
                                ?>
                            </div>
                       </ul>
                        <ul>
                           <li>名次</li><li>手机号</li><li>元宝数</li>
                                <div class="allrankingDiv">
                                <?php
                                     if(!empty($afterlist)) {
                                         $num = ($page_vars['no']) * 5 + 1;
                                         foreach($afterlist as $key => $value){
                                             echo "<li>".$num."</li><li>".hide_mobile($value['mobile'])."</li><li>".$value['sumNum']."个</li>";
                                             $num++;
                                         }
                                     }
                                ?>
                                </div>
                        </ul>
                        
                </div>
<!--                page-->
                <div class="pageDiv"></div>

            </div>
        </div>
        <div class="footDiv">
            <a href="{$url}"><img src="_STATIC_/2015/activity/image/newyears/lefticon.png" class="leftIconImg"/></a>
            <a href="/invest"><img src="_STATIC_/2015/activity/image/newyears/righticon.png" class="rightIconImg"/></a>
        </div>
    </div>

   
</div>
<script>
	$(function(){
		window.onload = function()
		{
			var $li = $('#tab li');
			var $ul = $('#content ul');
						
			$li.mouseover(function(){
				var $this = $(this);
				var $t = $this.index();
				$li.removeClass();
				$this.addClass('current');
				$ul.css('display','none');
				$ul.eq($t).css('display','block');
                               
                            if($t == 0){
                                 pageAjaxSearch(0);
                            } else {
                                 pageAjaxSearch(1);
                            }
			})
		}
	});
        	/*分页条件查询*/
        function pageAjaxSearch(page){
            $.post('/activity/yearDate.html',{'p':page},function(response){
                if(response.status){
                    if(page > 0) {
                        $('.allrankingDiv').html(response.data.html);
                         $('.pageDiv').html(response.data.page);
                    } else {
                          $('.pageDiv').html('');
                          $('.rankingDiv').html(response.data.html);
                    }
                }
                return true;
            }, "json");
        }
</script>
