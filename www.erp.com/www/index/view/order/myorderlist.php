<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/wx/css/style.css"> 
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/wx/css/order.css"> 
    <script type="text/javascript" src="_STATIC_/2015/wx/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="_STATIC_/2015/wx/js/font.js"></script>
</head>  
<body>
<header >
	<a href="javascript:history.back();" class="btn_back"  id="headers">
        <img src="_STATIC_/2015/wx/images/return.png">
        <font class="fl">返回</font>
    </a>   
    <h1>全部订单</h1>
    <a id="btn_apply">申请</a>    
</header>
{foreach name='list' item='vo' key='k'}  
        <section class="proh {eq name='k' value='0'} m40 {/eq}"  >
        	<time>{$vo.yearMonth}</time>
        </section>
        <section class="bgf">
        	<ul class='xyy'>
            {foreach name='vo.list' item='v'}
                 <li class="mui-view-cell" id="{$v.id}" onclick="detail({$v.id});" >
                    <b>{$v.names}<br/><font>{$v.car_number}</font></b>
                    <span>{$v.status_name}</span>	
        	    </li>
             {/foreach}   
        	</ul>	
        </section> 
{/foreach}
</body>
<script type="text/javascript">
      function detail(id){
        window.location.href="/order/orderInfo?id="+id;
    }
  $(function(){
         page = 2; is_loading = 1; 
        $(window).scroll(function(){
            if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
                $.ajax({
                    'type':'post',
                    'dataType':'json',
                    'url':"/Order/myOrderList",
                    "data":{'page':page++,'is_ajax':1},
                    success:function(json){
                        if(json.status==1){
                            var str1 = "";
                            var str2 = "";
                            $.each(json.data,function(i,item){
                                //获取最后一个月份标签
                                 var time=$("time:last").text();
                                     if(item.yearMonth==time){
                                            $.each(item.list,function(k,v){    
                                                str1+='<li class="mui-view-cell" id='+v.id+' onclick="detail('+v.id+');" >';
                                                str1+='<b>'+v.names+'<br/><font>'+v.car_number+'</font></b><span>'+v.status_name+'</span>';                          
                                                str1+='</li>';
                                            });
                                             $('.xyy:last').append(str1);
                                     }else{
                                            str2+='<section class="proh" ><time>'+item.yearMonth+'</time></section>';
                                            str2+='<section class="bgf"><ul class="xyy">';
                                            $.each(item.list,function(k,v){
                                                    str2+='<li class="mui-view-cell" id='+v.id+' onclick="detail('+v.id+');" >';
                                                    str2+='<b>'+v.names+'<br/><font>'+v.car_number+'</font></b><span>'+v.status_name+'</span>';                          
                                                    str2+='</li>';
                                                });     
                                             str2+='</ul></section>';
                                             $('body').append(str2);
                                             str2='';
                                    }
                            });
                        }else{
                            is_loading=0;
                            // alert('最后一页');
                        }
                    },
                    
                });
            }
        });
         $(window).scroll();

    $('#btn_apply').click(function(){
        window.location.href="/order/addOrder";
    });
    /*
    $('.fl').click(function(){
         // window.location.href="https://www.baidu.com/";
    })
    */

})
</script>
</html>
