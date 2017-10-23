<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
    <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>{$pageseo.title}</title>
    <link rel="stylesheet" href="_STATIC_/2015/member/css/systemnews.css" />
    <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>  
    <style type="text/css">
        .con_div{
            width: 100%;
            position: relative;
            overflow: hidden;          
            margin-top:80px;            
        }
        .message_one{
            width: 100%;
            border-top: 1px #e1e1e1 solid;
            overflow: hidden;
            padding: 3% 0;
        }
        .message_tit{
            font-size: 14px;
            width: 94%;
            margin: 0 auto;
        }
        .message_tit i{
            float: left;
            width: 10%;
            display: inline-block;
        }
        .message_tit i img{
            float: left;
            vertical-align: middle; 
            width: 30%;
            padding-top: 14%;   
        }
        .message_tit h2{
            color: #4c4c4c;
            font-size: 14px;
            display: inline-block;
            width: 60%;
            overflow: hidden;
            text-align: left;
        }
        .message_tit font{
            color: #ddd; 
        }
        .message_con{
            color: #ddd;
            padding: 3% 0;
            width: 94%;
            margin: 0 auto;
            text-align: left;
            font-size: 12px;
        }
    </style> 
</head>
<body style="background:#efefef;">
<div class="maxDiv">
    <!--顶部 开始-->
    <div class="headers">
        <div class="rd">
            <a href="/member/account" style="color:white;text-decoration:none;">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz size2">返回</span>
            </a>
            <span class="orderwx size2">系统消息</span>
        </div>
    </div>
    <!--顶部结束-->
    <div class="d2">
        <a href='/member/systemnews?page={$_REQUEST['page']}&status=0'><p class="new"><span class="news size2  <eq name='_REQUEST["status"]' value='0'> outline</eq>">最新消息</span></p></a>
        <a href='/member/systemnews?page={$_REQUEST['page']}&status=1'><p class="any"><span class="anys size2  <eq name='_REQUEST["status"]' value='1'> outline</eq>">全部</span></p></a>
    </div>
    <!--内容层-->
    
    <div class="con_div">
    	<foreach name='system.systemlist' item='vo'>
    		<a href="/member/systems?id={$vo['id']}" style="text-decoration:none;">
		        <div class="message_one">
		            <div class="message_tit">
		                <i> <empty  name= "vo.is_read">
                        <img src="_STATIC_/2015/member/image/account/squer.png"/>
                    </empty></i>
		                <h2>{$vo.title}</h2>
		                <font>{$vo.timeadd}</font>                
		            </div>
		            <div class="message_con">
		                {$vo.summary}
		            </div>
		        </div>
		    </a>
	   </foreach>
    </div>
</div>
</body>
<script type="text/javascript">
    $(".outline").css("border-bottom", "2px solid #5495e6");

    page = 2; is_loading = 1;status = "{$_REQUEST['status']}";
    $(window).scroll(function(){
        if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
            $.ajax({
                'type':'post',
                'dataType':'json',
                'url':"/member/systemnews",
                "data":{'page':page++,'is_ajax':1,'status':status},
                success:function(json){
                    if(json.status==1){
                        var str = "";
                        $.each(json.data,function(i,item){
                            var order_name = item.order_type==1?'车抵贷':'买车贷';
                            var backtotalmoney = (isNaN(parseInt(item.backtotalmoney)) || parseInt(item.backtotalmoney)<1)?(item.loanmoney):(item.backtotalmoney);
                            if(item.is_read==undefined)item.is_read='';
                            str+='<a href="/member/systems?id='+item.id+'" style="text-decoration:none;">';
                            str+='<div class="message_one"><div class="message_tit"><i>';
                            if(item.is_read=='')
                           	 str+='<img src="_STATIC_/2015/member/image/account/squer.png"/>'; 
                            str+='</i><h2>'+item.title+'</h2>';
                            str+='<font>'+item.timeadd+'</font>';     
                            str+='</div>'; 
                            str+='<div class="message_con">'+item.summary+'</div>';  
                            str+='</div>';
                            str+='</a>';
                            
                        });
                        $('.con_div').append(str);
                    }else{
                        is_loading=0;
                    }
                },
                
            });
        }
    });


    
</script>
</html>
{__NOLAYOUT__}