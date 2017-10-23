<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta content="telephone=no,email=no" name="format-detection">
    <meta content="yes" name="apple-touch-fullscreen">
    <meta content="yes" name="apple-mobile-web-app-capable">
   	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/> 
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/agent/agent.css">
    <link rel="stylesheet" type="text/css" href="_STATIC_/2015/css/yydai/style.css">
    <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript">
$(function(){
    page = 2; is_loading = 1;
            $(window).scroll(function(){
                
                if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
                    var data= {};
                    $(data).attr("page",page++);
                    $(data).attr("is_ajax",1);
                    $.post("/agent/purchase_history",data,function(F){
                           console.log(F);
                           if(F.status == 1){
                               var str = "";
                                $.each(F.data,function(i,item){       
                                    str += "<tr><td>" + item.timeadd + "</td><td>" + item.remark + "</td><td>" + item.money + "元</td></tr>";  
                                });
                                $('.record').append(str);
                           }else{                         
                               $('#hint').html("没有更多记录了...");
                                is_loading=0;
                           }
                    },'json')
                }
            });             
});

</script>
</head>
<body style="background: #efefef;">
<header>
<a href="/agent/agent_account" class="btn_back">
    <img src="_STATIC_/2015/member/image/register/return.png">
    <font class="fl">返回</font>
</a>
<h1>账户记录</h1>
</header>
<table class="record" cellpadding="0" cellspacing="0">
	<tr><th>操作日期</th><th>操作</th><th>账户变化</th></tr>
    <foreach name="purchase_list" item="vo">
	<tr><td >{$vo.timeadd}</td><td>{$vo.remark}</td><td>{$vo.money}元</td></tr>
    </foreach>
</table>
<if condition="$more eq 1">
<center id="hint" style='color:#999;font-size: 15px;'>上拉获取更多记录...</center>
<else/>
<center id="hint" style='color:#999;font-size: 15px;'>没有更多记录了...</center>
</if>
</body>
</html>
{__NOLAYOUT__}