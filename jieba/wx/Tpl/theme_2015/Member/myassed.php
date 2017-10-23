<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/myassed.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/myassed.js"></script>
</head>
<body style="background:#efefef;">
    <div class="maxDiv">
        <!--顶部 开始-->
        <div class="headers">
            <div class="rd">
                <a href="/member/account" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz size2">返回</span></a>
                <span class="orderwx size2">待评价</span>
            </div>
        </div>
        
		  <!--审核成功 内容层  开始-->
		<foreach name='assedlist' item='vo'>
        <div class="d4">
            <div class="d4op">
                <p class="sk4imgwz size3">交易成功</p>
                <notempty name='vo.applyCode'>
                	<a href="/member/loanerp/ContractNo/{$vo.applyCode}"><img src="_STATIC_/2015/member/image/myorder/detail.png" class="detailImg"/></a>
                </notempty>
                <if condition="$vo.order_type eq 3">
                    <a href="/credit/orderListDetail/id/{$vo.id}"><img src="_STATIC_/2015/member/image/myorder/detail.png" class="detailImg"/></a>
                </if>
            </div>
            <div class="d4center">
                <eq name='vo.order_type' value='1'>
                <div class="d4center-top">
                    <p class="mcd4wz size3">车贷宝</p>
                </div>
                <div class="d4center-center">
                    <img src="_STATIC_/2015/member/image/myorder/cars.jpg" alt="" class="bucars4Img"/>
                    <span class="moneys4wz size4">借款金额：<span  style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{$vo.timeadd}</span></span>
                    <span class="htnum4wz size4">合同编号:<span>{$vo.applyCode}</span></span>
                    <span class="jd4wz size4">订单已完成</span>
                </div>
                </eq>
                <eq name='vo.order_type' value='2'>
                    <div class="d4center-top">
                        <p class="mcd4wz size3">车租宝</p>
                    </div>
                    <div class="d4center-center">
                        <img src="_STATIC_/2015/member/image/myorder/bucars.jpg" alt="" class="bucars4Img"/>
                        <span class="moneys4wz size4">借款金额：<span  style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{$vo.timeadd}</span></span>
                        <span class="htnum4wz size4">合同编号:<span>{$vo.applyCode}</span></span>
                        <span class="jd4wz size4">订单已完成</span>
                    </div>
                </eq>
                <eq name='vo.order_type' value='3'>
                    <div class="d4center-top">
                        <p class="mcd4wz size3">信贷宝</p>
                    </div>
                    <div class="d4center-center">
                        <img src="_STATIC_/2015/member/image/myorder/crcars.jpg" alt="" class="bucars4Img"/>
                        <span class="moneys4wz size4">借款金额：<span  style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{$vo.timeadd}</span></span>
<!--                        <span class="htnum4wz size4">合同编号:<span>{$vo.applyCode}</span></span>-->
                        <span class="jd4wz size4">订单已完成</span>
                    </div>
                </eq>
                <div class="d4bottom">
                    <a href="/member/assed/orderid/{$vo['id']}/type/{$vo['order_type']}" style="color:white;text-decoration:none;">
                    <img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>
                    </a>
                </div>
            </div>
        </div>
		</foreach>  
		  
        
        
    </div>
</body>
<script type="text/javascript">
/*	$(function(){
		page = 2; is_loading = 1;
		$(window).scroll(function(){
			if($(document).height()==($(this).height()+$(this).scrollTop()) && is_loading==1){
				$.ajax({
					'type':'post',
					'dataType':'json',
					'url':"/member/myassed",
					"data":{'page':page++,'is_ajax':1},
					success:function(json){
						if(json.status==1){
							var str = "";
							$.each(json.data,function(i,item){
								if(item.order_type==1){
									var order_name = '车贷宝';
									var img_logo  = "cars.jpg";
								}else if(item.order_type==2){
									var order_name = '车租宝';
									var img_logo  = "bucars.jpg";
								}else{
									var order_name = '车友贷';
									var img_logo  = "crcars.jpg";
								}
								var backtotalmoney = (isNaN(parseInt(item.backtotalmoney)) || parseInt(item.backtotalmoney)<1)?(item.loanmoney):(item.backtotalmoney);
								if(item.applyCode==undefined)item.applyCode='';
								str+='<div class="d4">';
								str+='<div class="d4op">';
								str+='<p class="sk4imgwz size3">交易成功</p>';
								if(item.applyCode!='')
									str+='<a href="/member/loanerp/ContractNo/'+item.applyCode+'"><p class="xqimgwz size5">查看详情>></p></a>';
								str+='</div>';
								str+='<div class="d4center">';
								str+='<div class="d4center-top">';
								str+='<p class="mcd4wz size3">'+order_name+'</p>';
								str+=' </div>';
								str+='<div class="d4center-center">';
								str+='<img src="_STATIC_/2015/member/image/myorder/bucars.png" alt="" class="bucars4Img"/>';
								str+='<span class="moneys4wz size4">借款金额：<span>'+backtotalmoney+'</span></span><span class="timess4wz size4">申请时间：<span>'+item.timeadd+'</span></span>';
								str+='<span class="htnum4wz size4">合同编号:<span>'+item.applyCode+'</span></span>';
								str+='<span class="jd4wz size4">订单已完成</span>';
								str+=' </div>';
								str+='<div class="d4bottom">';
								str+='<a href="/member/assed" style="color:white;text-decoration:none;">';
								str+='<img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>';
								str+='</a>';
								str+='</div>';
								str+='</div>';
								str+='</div>';
							});
							$('.maxDiv').append(str);
						}else{
							is_loading=0;
						}
					},
					
				});
			}
		});
	});*/
</script>
</html>   
{__NOLAYOUT__}