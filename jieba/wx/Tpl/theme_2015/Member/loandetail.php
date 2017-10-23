<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <!--禁止自动识别电话号码-->
        <meta name="format-detection" content="telephone=no" />
        <!--禁止自动识别邮箱-->
        <meta content="email=no" name="format-detection" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/loandetail.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script src="_STATIC_/2015/member/js/loandetail.js" type="text/javascript"></script>
    </head>
    <body color="#efefef">
        <!--头部-->
        <div class="headers">
            <a href="/member/loanerp/ContractNo/{$_REQUEST['ContractNo']}" style="color:white;text-decoration:none;"><img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz onesize">返回</span></a>
            <span class="zxwx onesize">还款明细</span>
        </div>
        <div class="topDiv">
            <p class="sdf onesize">还款明细</p>
            <p class="sdf1 onesize">还款金额</p>
            <p class="sdf2 onesize">期数</p>
        </div>
        <div class="infoDiv">
        	<foreach name='data.loandetail' item='vo'>
        		<div class="infoDivs">
	                <span class="c1 onesize">{:date("Y-m-d",strtotime($vo['BackDate']))}</span>
	                <span class="c2 onesize">{$vo.BackTotalMoney}</span>
	                <span class="c3 onesize">{$vo.Duration}</span>
	            </div>
        	</foreach>
            
        </div>
    </div>


</body>

</html>
{__NOLAYOUT__}