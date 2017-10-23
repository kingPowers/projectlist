<?php 
  //$page = array("no"=>20,"total"=>20,"count"=>100,"num"=>5);
  //$page_url = "./page1.php";
?>
<div class="ui-pagination" style="width: 700px;margin:0 auto;">
	<ul class="ui-pagination-container">
		<?php
		    $page_html = array(
		    	"info"=>'',
		    	"index"=>'',
		    	"prePage"=>'',
		        "num"=>'',
		        "nextPage"=>'',
		        "lastPage"=>''

		    );//生成的html代码数组
            //边界约束
            $page['no'] = ($page['no'] < 1)?1:$page['no'];
            $page['no'] = ($page['no'] > $page['total'])?$page['total']:$page['no'];
            //自适应页码
            if($page['total'] <= 5)
            {
	    	    for($i = 0;$i < $page['total'];$i++)
	    	    {
	                if($page['no'] == ($i+1))
	                {
	                   $page_html['num'] .= "<li class='disabled'><a href='javascript:;' class='ng-binding'>".($i+1)."</a></li>";
	                   continue;
	                }
	                $page_html['num'] .= "<li><a href='".str_replace('*', ($i+1), $page_url)."'> " .($i+1). " </a></li>";
	    	    }
	    	}else{
	    		if($page['no'] < 4)
	    		{
	        		for($i = 0;$i < 5;$i++)
	        		{
		                if($page['no'] == ($i+1))
		                {
		                   $page_html['num'] .= "<li class='disabled'><a href='javascript:;' class='ng-binding'>".($i+1)."</a></li>";
		                   continue;
		                }
	                 	$page_html['num'] .= "<li><a href='".str_replace('*', ($i+1), $page_url)."'> ".($i+1)." </a></li>";
	    	        }
	    	        $page_html['num'] .= '<li class="disabled"><a href="javascript:;" class="ng-binding">---</a></li>';
	        	}else{
	        		if($page['no'] >= ($page['total']-2))
	        		{
	        			$page_html['num'] .= '<li class="disabled"><a href="javascript:;" class="ng-binding">---</a></li>';
	        			for($i = ($page['no']-3);$i<$page['total'];$i++)
	        			{
		                    if($page['no'] == ($i+1))
		                    {
		                        $page_html['num'] .= "<li class='disabled'><a href='javascript:;' class='ng-binding'>".($i+1)."</a></li>";
		                        continue;
		                    }
	                        $page_html['num'] .= "<li><a href='".str_replace('*', ($i+1), $page_url)."'> ".($i+1)." </a></li>";
	    	           }
	        		}else{
	        			$page_html['num'].='<li class="disabled"><a href="javascript:;" class="ng-binding">---</a></li>';;
	        			for($i=($page['no']-3);$i<($page['no']+2);$i++)
	        			{
	                      if($page['no']==($i+1))
	                      {
	                         $page_html['num'] .= "<li class='disabled'><a href='javascript:;' class='ng-binding'>".($i+1)."</a></li>";
	                          continue;
	                      }
	                      $page_html['num'] .= "<li><a href='".str_replace('*', ($i+1), $page_url)."'> ".($i+1)." </a></li>";
	    	           }
	    	           $page_html['num'].='<li class="disabled"><a href="javascript:;" class="ng-binding">---</a></li>';
	        		}
	        	}
	    	}
	    	//记录信息
    	    $page_html['info']="<li class='disabled' style='float:left'><a>共".$page['count']."条记录 当前".$page['no']."/".$page['total']."页</a></li>";
    	    //自适应首页尾页
	    	if(!($page['no'] == 1) && ($page['no'] != 0))
	    	{
	          	$page_html['index']="<li><a href='".str_replace('*', 1, $page_url)."'>首页</a></li>";
	    	  	$page_html['prePage'] = "<li><a href='".str_replace('*', $page['no']-1, $page_url)."'>上一页</a></li>";
	    	} 
	    	if(!($page['no']==$page['total']))
	    	{
		         $page_html['nextPage']="<li><a href='".str_replace('*', $page['no']+1, $page_url)."'>下一页</a></li>";
		         $page_html['lastPage']="<li><a href='".str_replace('*', $page['total'], $page_url)."'>尾页</a></li>";
	        }
	        echo $page_html['info'].$page_html['index'].$page_html['prePage'].$page_html['num'].$page_html['nextPage'].$page_html['lastPage'];
		?>
	</ul>
</div>
{__NOLAYOUT__}