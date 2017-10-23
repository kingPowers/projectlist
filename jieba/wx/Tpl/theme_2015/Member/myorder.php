<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/myorder.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script type="text/javascript" src="_STATIC_/2015/member/js/myorder.js"></script>
</head>
<body style="background:#efefef;">
    <div class="maxDiv">
        <!--顶部 开始-->
        <div class="headers">
            <div class="rd">
                <a href="/member/account" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
                <span class="fhwz size2">返回</span>
                </a>
                <span class="orderwx size2">我的订单</span>
            </div>
        </div>
        <!--顶部结束-->
        <div class="d2">
            <a href='/member/myorder?page={$_REQUEST['page']}&status='><p class="allP"><span class="all  size2  <eq name='_REQUEST["status"]' value=''> outline</eq>">全部</span></p></a>
            <a href='/member/myorder?page={$_REQUEST['page']}&status=1'><p class="shingP "><span class="shing size2 <eq name='_REQUEST["status"]' value='1'> outline</eq>">审核中</span></p></a>
            <a href='/member/myorder?page={$_REQUEST['page']}&status=2'><p class="shsuccP "><span class="shsucc size2  <eq name='_REQUEST["status"]' value='2'> outline</eq>">审核成功</span></p></a>
           <a href='/member/myorder?page={$_REQUEST['page']}&status=3'><p class="shsadP"><span class="shsad size2 <eq name='_REQUEST["status"]' value='3'> outline</eq>">审核失败</span></p></a>
        </div>
        <!--审核中  审核失败  内容层  开始-->
        <foreach name='order.orderlist' item='vo'>
        	<eq name='vo.status' value='2'>
        		<div class="d4">
		            <div class="d4op">
		                <p class="sk4imgwz size3">订单成功</p>
		                <eq name='vo.order_type' value='1'>
		                	<a href="/member/loanerp/ContractNo/{$vo.applyCode}/status/{$_REQUEST['status']}"><img src="_STATIC_/2015/member/image/myorder/detail.png" class="detailImg"/></a>
		                </eq>
		                <eq name='vo.order_type' value='2'>
		                	<a href="/member/buydetail/order_id/{$vo.id}/status/{$_REQUEST['status']}"><img src="_STATIC_/2015/member/image/myorder/detail.png" class="detailImg"/></a>
		                </eq>
		                <eq name='vo.order_type' value='3'>
		                	<a href="/credit/orderListDetail/id/{$vo.id}/status/{$_REQUEST['status']}"><img src="_STATIC_/2015/member/image/myorder/detail.png" class="detailImg"/></a>
		                </eq>
		            </div>

		            <div class="d4center">
                        <eq name='vo.order_type' value='1'>
		                <div class="d4center-top">
		                    <p class="mcd4wz size3">车贷宝</p>
		                </div>
		                <div class="d4center-center">
		                    <img src="_STATIC_/2015/member/image/myorder/cars.jpg" alt="" class="bucars4Img"/>
		                    <span class="moneys4wz size4">借款金额：<span style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{:date("Y-m-d",strtotime($vo['timeadd']))}</span></span>
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
                                <span class="moneys4wz size4">借款金额：<span  style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{:date("Y-m-d",strtotime($vo['timeadd']))}</span></span>
                                <span class="htnum4wz size4">合同编号:<span>{$vo.applyCode}</span></span>
                                <span class="jd4wz size4">订单已完成</span>
                            </div>
                        </eq>
                        <eq name='vo.order_type' value='3'>
                            <div class="d4center-top">
                                <p class="mcd4wz size3">车友贷</p>
                            </div>
                            <div class="d4center-center">
                                <img src="_STATIC_/2015/member/image/myorder/crcars.jpg" alt="" class="bucars4Img"/>
                                <span class="moneys4wz size4">借款金额：<span  style="width:90%;height:auto;text-align:left;position:absolute;overflow:hidden;text-overflow:ellipsis; -o-text-overflow:ellipsis;white-space:nowrap;"><notempty name='vo.backtotalmoney'>{$vo.backtotalmoney}<else/>{$vo.loanmoney}</notempty></span></span><span class="timess4wz size4">申请时间：<span>{:date("Y-m-d",strtotime($vo['timeadd']))}</span></span>
<!--                                <span class="htnum4wz size4">合同编号:<span>{$vo.applyCode}</span></span>-->
                                <span class="jd4wz size4">订单已完成</span>
                            </div>
                        </eq>
		                <div class="d4bottom">
                            <if condition="$vo['assed_status'] eq null">
                                <a href="/member/assed/orderid/{$vo['id']}/type/{$vo['order_type']}" style="text-decoration:none;">
                                    <img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>
                                </a>
                            <elseif condition="$vo['assed_status'] eq 2" /><!--主动删除 -->
                                <a href="/member/assed/orderid/{$vo['id']}/type/{$vo['order_type']}" style="text-decoration:none;">
                                    <img src="_STATIC_/2015/member/image/myorder/ljpj.png" alt="" class="ljpjImg"/>
                                </a>
                            <elseif condition="$vo['assed_status'] eq 3" /><!--后台删除 -->
                                <a href="/Carlife/detail/id/{$vo['is_assed']}/delete/3">
                                    <img src="_STATIC_/2015/member/image/myorder/ypj.png" alt="" class="ljpjImg"/>
                                </a>
                            <elseif condition="$vo['assed_status'] eq 0" /><!--后台未审核 -->
                                <a href="/Carlife/detail/id/{$vo['is_assed']}/delete/3">
                                    <img src="_STATIC_/2015/member/image/myorder/ypj.png" alt="" class="ljpjImg"/>
                                </a>
                            <else/>
                                <a href="/Carlife/detail/id/{$vo['is_assed']}">
                                    <img src="_STATIC_/2015/member/image/myorder/ypj.png" alt="" class="ljpjImg"/>
                                </a>
 
                            </if>
		                </div>
		            </div>
		        </div>
        	<else/>
		        <div class="d3">
		            <div class="d3top">
		                <p class="skimgwz size3">
		                	<eq name='vo.status' value='1'>审核中</eq>
		                	<eq name='vo.status' value='2'>订单成功</eq>
		                	<eq name='vo.status' value='3'>交易失败</eq>
		                </p>
		            </div>
		            <div class="d3center">
                        <eq name='vo.order_type' value='1'>
		                <div class="d3center-top">
		                    <p class="mcdwz size3">车贷宝 </p>
		                </div>
		                <div class="d3center-center">
		                    <img src="_STATIC_/2015/member/image/myorder/cars.jpg" alt="" class="bucarsImg"/>
		                    <span class="money3swz size4">借款金额:{$vo.loanmoney}</span><span class="timesswz size4">申请时间:{:date("Y-m-d",strtotime($vo['timeadd']))}</span>
		                    <eq name='vo.status' value='3'>
		                    	<span class="jdwz size4">订单关闭</span>
		                    </eq>
		                    <eq name='vo.status' value='1'>
		                    	<eq name='vo.customer_status' value='0'>
		                    		<span class="jdwz size4">订单完成<span>0%</span></span>
		                    		<else/>
		                    		<eq name='vo.store_status' value='0'>
		                    			<span class="jdwz size4">订单完成<span>25%</span></span>
		                    			<else/>
		                    			<span class="jdwz size4">订单完成<span>50%</span></span>
		                    		</eq>
		                    	</eq>
		                    </eq>
		                    
		                </div>
                       </eq>
                       <eq name='vo.order_type' value='2'>
                           <div class="d3center-top">
                               <p class="mcdwz size3">车租宝</p>
                           </div>
                           <div class="d3center-center">
                               <img src="_STATIC_/2015/member/image/myorder/bucars.jpg" alt="" class="bucarsImg"/>
                               <span class="money3swz size4">借款金额:{$vo.loanmoney}</span><span class="timesswz size4">申请时间:{:date("Y-m-d",strtotime($vo['timeadd']))}</span>
                               <eq name='vo.status' value='3'>
                                   <span class="jdwz size4">订单关闭</span>
                               </eq>
                               <eq name='vo.status' value='1'>
                                   <eq name='vo.customer_status' value='0'>
                                       <span class="jdwz size4">订单完成<span>0%</span></span>
                                       <else/>
                                       <eq name='vo.store_status' value='0'>
                                           <span class="jdwz size4">订单完成<span>25%</span></span>
                                           <else/>
                                           <span class="jdwz size4">订单完成<span>50%</span></span>
                                       </eq>
                                   </eq>
                               </eq>
                           </div>
                       </eq>
                        <eq name='vo.order_type' value='3'>
                            <div class="d3center-top">
                                <p class="mcdwz size3">车友贷 </p>
                            </div>
                            <div class="d3center-center">
                                <img src="_STATIC_/2015/member/image/myorder/crcars.jpg" alt="" class="bucarsImg"/>
                                <span class="money3swz size4">借款金额:{$vo.loanmoney}</span><span class="timesswz size4">申请时间:{:date("Y-m-d",strtotime($vo['timeadd']))}</span>
                                <eq name='vo.status' value='3'>
                                    <span class="jdwz size4">订单关闭</span>
                                </eq>
                                <eq name='vo.status' value='1'>
                                    <eq name='vo.customer_status' value='0'>
                                        <span class="jdwz size4">订单完成<span>0%</span></span>
                                        <else/>
                                        <eq name='vo.store_status' value='0'>
                                            <span class="jdwz size4">订单完成<span>25%</span></span>
                                            <else/>
                                            <span class="jdwz size4">订单完成<span>50%</span></span>
                                        </eq>
                                    </eq>
                                </eq>
                            </div>
                        </eq>
		            </div>
		        </div>
	        </eq>
	      </foreach>
    </div>
</body>
<script type="text/javascript">
       $(".outline").css("border-bottom", "2px solid #5495e6");
</script>
</html>   
{__NOLAYOUT__}