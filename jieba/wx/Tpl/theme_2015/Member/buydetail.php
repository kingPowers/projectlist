<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta content="telephone=no,email=no" name="format-detection">
        <meta content="yes" name="apple-touch-fullscreen">
        <meta content="yes" name="apple-mobile-web-app-capable">
        <meta name="viewport" content="width=device-width,height=device-heght,initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
        <title>{$pageseo.title}</title>
        <link rel="stylesheet" href="_STATIC_/2015/member/css/loanerp.css" />
        <link href="_STATIC_/2015/css/public.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="_STATIC_/2015/index/js/jquery-1.10.2.min.js"></script>
        <script src="_STATIC_/2015/member/js/loanerp.js" type="text/javascript"></script>
    </head>
    <body bgcolor="#efefef">
        <div class="headers">
            <img src="_STATIC_/2015/member/image/register/return.png" class="retrunImg"/>
            <span class="fhwz onesize"><a href="/member/myorder/status/{$_REQUEST['status']}" style="color:white;text-decoration:none;">返回</a></span>
            <span class="zxwx onesize">贷款详情</span>
            <!--<a href='/member/loandetail/ContractNo/{$_REQUEST["ContractNo"]}'><span class="hqwx onesize">还款明细</span></a>-->
        </div>
        <!--head结束-->
        <div class="centerDiv">
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">合同编号:</div>
                <div class="rightwordsDiv threesize">{$data.applyCode}</div>
            </div>
            
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">申请日期:</div>
                <div class="rightwordsDiv threesize">{$data.timeadd}</div>
            </div>
            
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">实际到帐金额:</div>
                <div class="rightwordsDiv threesize">{$data.backtotalmoney}元</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">贷款期限:</div>
                <div class="rightwordsDiv threesize">{$data.loanmonth}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">贷款城市:</div>
                <div class="rightwordsDiv threesize">{$data.city}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">经销商:</div>
                <div class="rightwordsDiv threesize">{$data.dealer}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">车辆品牌:</div>
                <div class="rightwordsDiv threesize">{$data.car_brand}</div>
            </div>
            <div style="width:97%;border-top:1px solid #efefef;margin-left:3%;"></div>
            <div class="wordDiv">
                <div class="leftwordsDiv threesize">车辆型号:</div>
                <div class="rightwordsDiv threesize">{$data.car_class}</div>
            </div>
        </div>
        <div class="secondDiv"></div><!--弹出灰色弹窗-->
        <div class="thirdDiv">
            <img src="_STATIC_/2015/member/image/account/border.png" class="borderImg">
            <p class="info tsize" style='font-weight:normal;top:17%'>您需要与客服联系确认身份证号码后<br/><br/>才可查看详情<a href="tel:400663966">400663966</a></p>
            <a href="javascript:void(0)"  onclick='history.go(-1);' class='jump_url'><input type="button" value="确定" class="surebtn ssize"/></a>
        </div><!--弹出内容-->
    </body>
    <script type="text/javascript">
        	var is_back = "{$applycode_err}";//合同编号错误
        	if(is_back>0)showWindow('','');
            function showWindow(message,url){
                //$('.info').html(message);
                $(".secondDiv").css("display","block");
                $(".thirdDiv").css("display","block");
                //$('.jump_url').attr('href',url);
            }
   </script>
</html>
{__NOLAYOUT__}